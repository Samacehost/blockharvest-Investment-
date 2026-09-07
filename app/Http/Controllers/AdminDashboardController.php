<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\BrandingSetting;
use App\Models\Deposit;
use App\Models\Faq;
use App\Models\Investment;
use App\Models\InvestmentPlan;
use App\Models\KycDocument;
use App\Models\KycSubmission;
use App\Models\LedgerEntry;
use App\Models\Setting;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Services\DynamicSettingService;
use App\Services\KycService;
use App\Services\LedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::where('role', 'investor')->count();
        $totalAum = Investment::where('status', 'active')->sum('amount');
        $activeInvestmentsCount = Investment::where('status', 'active')->count();
        $pendingDepositsCount = Deposit::where('status', 'pending')->count();
        $pendingWithdrawalsCount = Withdrawal::where('status', 'pending')->count();
        $pendingKycCount = KycSubmission::where('status', 'pending')->count();

        $recentDeposits = Deposit::with('user', 'depositMethod')->orderBy('created_at', 'desc')->take(5)->get();
        $recentWithdrawals = Withdrawal::with('user', 'withdrawalMethod')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalAum', 'activeInvestmentsCount',
            'pendingDepositsCount', 'pendingWithdrawalsCount', 'pendingKycCount',
            'recentDeposits', 'recentWithdrawals'
        ));
    }

    public function users()
    {
        $users = User::with('wallet')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $request->validate(['status' => 'required|in:active,suspended,banned']);
        $user->update(['status' => $request->status]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => 'UPDATE_USER_STATUS',
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'new_values_json' => ['status' => $request->status],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "User account status changed to {$request->status}.");
    }

    public function adjustBalance(Request $request, User $user)
    {
        $request->validate([
            'direction' => 'required|in:CREDIT,DEBIT',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:255',
            'admin_password' => 'required',
        ]);

        if (!password_verify($request->admin_password, Auth::user()->password)) {
            return back()->withErrors(['admin_password' => 'Administrator password confirmation failed.']);
        }

        try {
            LedgerService::recordTransaction(
                user: $user,
                type: 'ADMIN_ADJUSTMENT',
                direction: $request->direction,
                amount: (float)$request->amount,
                currencyCode: 'USD',
                adminUser: Auth::user(),
                internalNotes: $request->reason,
                userNotes: "Administrative balance adjustment ({$request->direction})"
            );

            return back()->with('success', "Balance adjustment of ${$request->amount} successfully executed!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function setExactBalance(Request $request, User $user)
    {
        $request->validate([
            'available_balance' => 'required|numeric|min:0',
            'invested_balance' => 'nullable|numeric|min:0',
            'earnings_balance' => 'nullable|numeric|min:0',
            'referral_balance' => 'nullable|numeric|min:0',
            'admin_password' => 'required',
        ]);

        if (!password_verify($request->admin_password, Auth::user()->password)) {
            return back()->withErrors(['admin_password' => 'Administrator password confirmation failed.']);
        }

        $userCurrency = $user->currency_code ?? 'USD';
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id, 'currency_code' => $userCurrency],
            [
                'available_balance' => 0.0000,
                'invested_balance' => 0.0000,
                'earnings_balance' => 0.0000,
                'pending_deposit_balance' => 0.0000,
                'pending_withdrawal_balance' => 0.0000,
                'referral_balance' => 0.0000,
            ]
        );

        $wallet->update([
            'available_balance' => number_format((float)$request->available_balance, 4, '.', ''),
            'invested_balance' => number_format((float)($request->invested_balance ?? $wallet->invested_balance), 4, '.', ''),
            'earnings_balance' => number_format((float)($request->earnings_balance ?? $wallet->earnings_balance), 4, '.', ''),
            'referral_balance' => number_format((float)($request->referral_balance ?? $wallet->referral_balance), 4, '.', ''),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => 'SET_EXACT_BALANCE',
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'new_values_json' => [
                'available_balance' => $request->available_balance,
                'invested_balance' => $request->invested_balance,
                'earnings_balance' => $request->earnings_balance,
                'referral_balance' => $request->referral_balance,
            ],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Exact balances for investor {$user->name} set successfully!");
    }

    public function generateDepositHistory(Request $request, User $user)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:500',
            'min_amount' => 'required|numeric|min:1',
            'max_amount' => 'required|numeric|gte:min_amount',
            'days_back' => 'nullable|integer|min:1|max:365',
        ]);

        $userCurrency = $user->currency_code ?? 'USD';
        $methods = \App\Models\DepositMethod::where('is_active', true)
            ->where(function($q) use ($userCurrency) {
                $q->where('currency_code', $userCurrency)
                  ->orWhere('currency_code', 'ALL')
                  ->orWhere('currency_code', 'USD');
            })->get();

        if ($methods->isEmpty()) {
            $methods = \App\Models\DepositMethod::where('is_active', true)->get();
        }

        if ($methods->isEmpty()) {
            return back()->with('error', 'No active deposit methods available in system.');
        }

        $count = (int)$request->count;
        $min = (float)$request->min_amount;
        $max = (float)$request->max_amount;
        $daysBack = (int)($request->days_back ?? 30);

        DB::transaction(function() use ($user, $methods, $userCurrency, $count, $min, $max, $daysBack) {
            for ($i = 0; $i < $count; $i++) {
                $method = $methods->random();
                $amount = round(rand($min * 100, $max * 100) / 100, 2);
                $fee = round($amount * ($method->fee_percent / 100) + $method->fee_flat, 2);
                $net = max(0.01, $amount - $fee);

                $randomSeconds = rand(0, $daysBack * 86400);
                $createdAt = now()->subSeconds($randomSeconds);

                $deposit = Deposit::create([
                    'reference' => 'DEP-' . strtoupper(Str::random(10)),
                    'user_id' => $user->id,
                    'deposit_method_id' => $method->id,
                    'amount' => $amount,
                    'fee' => $fee,
                    'net_amount' => $net,
                    'currency_code' => $userCurrency,
                    'status' => 'approved',
                    'approved_by_admin_id' => Auth::id(),
                    'admin_notes' => 'Synthetic deposit generated by Super Admin',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                LedgerService::recordTransaction(
                    user: $user,
                    type: 'DEPOSIT_CREDIT',
                    direction: 'CREDIT',
                    amount: $net,
                    currencyCode: $userCurrency,
                    relatedModel: $deposit,
                    adminUser: Auth::user(),
                    userNotes: "Approved Deposit #{$deposit->reference}"
                );
            }
        });

        return back()->with('success', "Generated {$count} synthetic deposit records for {$user->name} in {$userCurrency}!");
    }

    public function generateWithdrawalHistory(Request $request, User $user)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:500',
            'min_amount' => 'required|numeric|min:1',
            'max_amount' => 'required|numeric|gte:min_amount',
            'days_back' => 'nullable|integer|min:1|max:365',
        ]);

        $userCurrency = $user->currency_code ?? 'USD';
        $methods = \App\Models\WithdrawalMethod::where('is_active', true)
            ->where(function($q) use ($userCurrency) {
                $q->where('currency_code', $userCurrency)
                  ->orWhere('currency_code', 'ALL')
                  ->orWhere('currency_code', 'USD');
            })->get();

        if ($methods->isEmpty()) {
            $methods = \App\Models\WithdrawalMethod::where('is_active', true)->get();
        }

        if ($methods->isEmpty()) {
            return back()->with('error', 'No active withdrawal methods available in system.');
        }

        $count = (int)$request->count;
        $min = (float)$request->min_amount;
        $max = (float)$request->max_amount;
        $daysBack = (int)($request->days_back ?? 30);

        DB::transaction(function() use ($user, $methods, $userCurrency, $count, $min, $max, $daysBack) {
            for ($i = 0; $i < $count; $i++) {
                $method = $methods->random();
                $amount = round(rand($min * 100, $max * 100) / 100, 2);
                $fee = round($amount * ($method->fee_percent / 100) + $method->fee_flat, 2);
                $net = max(0.01, $amount - $fee);

                $randomSeconds = rand(0, $daysBack * 86400);
                $createdAt = now()->subSeconds($randomSeconds);

                $withdrawal = Withdrawal::create([
                    'reference' => 'WTH-' . strtoupper(Str::random(10)),
                    'user_id' => $user->id,
                    'withdrawal_method_id' => $method->id,
                    'amount' => $amount,
                    'fee' => $fee,
                    'net_amount' => $net,
                    'currency_code' => $userCurrency,
                    'destination_details_json' => ['account_type' => 'Bank Wire / Crypto Wallet', 'address' => 'Historical Synthetic Payout'],
                    'status' => 'approved',
                    'approved_by_admin_id' => Auth::id(),
                    'admin_notes' => 'Synthetic withdrawal generated by Super Admin',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                LedgerService::recordTransaction(
                    user: $user,
                    type: 'WITHDRAWAL_PAYOUT',
                    direction: 'CREDIT',
                    amount: $amount,
                    currencyCode: $userCurrency,
                    relatedModel: $withdrawal,
                    adminUser: Auth::user(),
                    userNotes: "Completed Withdrawal Payout #{$withdrawal->reference}"
                );
            }
        });

        return back()->with('success', "Generated {$count} synthetic withdrawal records for {$user->name} in {$userCurrency}!");
    }

    public function generateInvestmentHistory(Request $request, User $user)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:500',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'plan_id' => 'nullable|exists:investment_plans,id',
        ]);

        $plans = InvestmentPlan::where('is_active', true)->get();
        if ($plans->isEmpty()) {
            return back()->with('error', 'No active investment plans available.');
        }

        if ($request->filled('plan_id')) {
            $plans = $plans->where('id', $request->plan_id);
        }

        $count = (int)$request->count;
        $fromDate = \Carbon\Carbon::parse($request->from_date)->startOfDay();
        $toDate = \Carbon\Carbon::parse($request->to_date)->endOfDay();
        $diffInSeconds = max(1, $toDate->timestamp - $fromDate->timestamp);

        $userCurrency = $user->currency_code ?? 'USD';

        DB::transaction(function() use ($user, $plans, $count, $fromDate, $diffInSeconds, $userCurrency) {
            $depositMethod = \App\Models\DepositMethod::where('is_active', true)->first();

            for ($i = 0; $i < $count; $i++) {
                $plan = $plans->random();
                $amount = round(rand($plan->min_amount * 100, $plan->max_amount * 100) / 100, 2);

                $randomOffset = rand(0, $diffInSeconds);
                $startedAt = (clone $fromDate)->addSeconds($randomOffset);

                $maturesAt = clone $startedAt;
                $val = (int)$plan->duration_value;
                switch ($plan->duration_unit) {
                    case 'hours': $maturesAt->addHours($val); break;
                    case 'weeks': $maturesAt->addWeeks($val); break;
                    case 'months': $maturesAt->addMonths($val); break;
                    case 'years': $maturesAt->addYears($val); break;
                    default: $maturesAt->addDays($val); break;
                }

                $isMaturesInPast = $maturesAt->isPast();
                $status = $isMaturesInPast ? 'completed' : 'active';
                $earnedAmount = round(($amount * ($plan->roi_rate / 100)), 2);

                if ($depositMethod) {
                    $deposit = Deposit::create([
                        'reference' => 'DEP-' . strtoupper(Str::random(10)),
                        'user_id' => $user->id,
                        'deposit_method_id' => $depositMethod->id,
                        'amount' => $amount,
                        'fee' => 0.00,
                        'net_amount' => $amount,
                        'currency_code' => $userCurrency,
                        'status' => 'approved',
                        'approved_by_admin_id' => Auth::id(),
                        'admin_notes' => 'Synthetic funding deposit for investment',
                        'created_at' => (clone $startedAt)->subMinutes(5),
                        'updated_at' => (clone $startedAt)->subMinutes(5),
                    ]);

                    LedgerService::recordTransaction(
                        user: $user,
                        type: 'DEPOSIT_CREDIT',
                        direction: 'CREDIT',
                        amount: $amount,
                        currencyCode: $userCurrency,
                        relatedModel: $deposit,
                        adminUser: Auth::user(),
                        userNotes: "Deposit for Investment Plan {$plan->name}"
                    );
                }

                $investment = Investment::create([
                    'reference' => 'INV-' . strtoupper(Str::random(10)),
                    'user_id' => $user->id,
                    'investment_plan_id' => $plan->id,
                    'amount' => $amount,
                    'currency_code' => $userCurrency,
                    'roi_rate' => $plan->roi_rate,
                    'roi_frequency' => $plan->roi_frequency,
                    'calculation_type' => $plan->calculation_type,
                    'capital_return' => $plan->capital_return,
                    'total_projected_roi' => $earnedAmount,
                    'total_earned_roi' => $status === 'completed' ? $earnedAmount : 0.00,
                    'status' => $status,
                    'started_at' => $startedAt,
                    'next_payout_at' => $status === 'active' ? (clone $startedAt)->addDay() : null,
                    'matures_at' => $maturesAt,
                    'completed_at' => $status === 'completed' ? $maturesAt : null,
                    'created_at' => $startedAt,
                    'updated_at' => $status === 'completed' ? $maturesAt : $startedAt,
                ]);

                LedgerService::recordTransaction(
                    user: $user,
                    type: 'INVESTMENT_DEBIT',
                    direction: 'DEBIT',
                    amount: $amount,
                    currencyCode: $userCurrency,
                    relatedModel: $investment,
                    adminUser: Auth::user(),
                    userNotes: "Invested in {$plan->name}"
                );

                if ($status === 'completed') {
                    \App\Models\InvestmentEarning::create([
                        'investment_id' => $investment->id,
                        'user_id' => $user->id,
                        'amount' => $earnedAmount,
                        'calculation_snapshot' => "Synthetic yield payout ({$plan->roi_rate}% ROI)",
                        'earned_at' => $maturesAt,
                        'created_at' => $maturesAt,
                        'updated_at' => $maturesAt,
                    ]);

                    LedgerService::recordTransaction(
                        user: $user,
                        type: 'ROI_EARNING_CREDIT',
                        direction: 'CREDIT',
                        amount: $earnedAmount,
                        currencyCode: $userCurrency,
                        relatedModel: $investment,
                        adminUser: Auth::user(),
                        userNotes: "ROI yield payout for {$plan->name}"
                    );

                    if ($plan->capital_return) {
                        LedgerService::recordTransaction(
                            user: $user,
                            type: 'CAPITAL_RETURN_CREDIT',
                            direction: 'CREDIT',
                            amount: $amount,
                            currencyCode: $userCurrency,
                            relatedModel: $investment,
                            adminUser: Auth::user(),
                            userNotes: "Capital return at maturity for {$plan->name}"
                        );
                    }
                }
            }
        });

        return back()->with('success', "Generated {$count} synthetic investment records for {$user->name}!");
    }

    public function kyc()
    {
        $submissions = KycSubmission::with('user', 'documents')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.kyc', compact('submissions'));
    }

    public function downloadKycDoc(KycDocument $document)
    {
        if (!Storage::exists($document->file_path)) {
            abort(404, 'Document file not found.');
        }

        return Storage::download($document->file_path, $document->original_filename);
    }

    public function approveKyc(KycSubmission $submission)
    {
        KycService::approve($submission, Auth::user());
        return back()->with('success', 'KYC Submission approved!');
    }

    public function rejectKyc(Request $request, KycSubmission $submission)
    {
        $request->validate(['reason' => 'required|string']);
        KycService::reject($submission, Auth::user(), $request->reason);

        return back()->with('success', 'KYC Submission rejected.');
    }

    public function deposits()
    {
        $deposits = Deposit::with('user', 'depositMethod')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.deposits', compact('deposits'));
    }

    public function approveDeposit(Deposit $deposit)
    {
        if ($deposit->status !== 'pending') {
            return back()->with('error', 'Deposit is already processed.');
        }

        DB::transaction(function () use ($deposit) {
            $deposit->update([
                'status' => 'approved',
                'approved_by_admin_id' => Auth::id(),
            ]);

            LedgerService::recordTransaction(
                user: $deposit->user,
                type: 'DEPOSIT_CREDIT',
                direction: 'CREDIT',
                amount: $deposit->net_amount,
                currencyCode: $deposit->currency_code,
                relatedModel: $deposit,
                adminUser: Auth::user(),
                userNotes: "Approved Deposit #{$deposit->reference}"
            );
        });

        return back()->with('success', "Deposit #{$deposit->reference} approved and credited!");
    }

    public function rejectDeposit(Request $request, Deposit $deposit)
    {
        if ($deposit->status !== 'pending') {
            return back()->with('error', 'Deposit is already processed.');
        }

        $deposit->update([
            'status' => 'rejected',
            'admin_notes' => $request->reason ?? 'Rejected by administrator',
        ]);

        return back()->with('success', "Deposit #{$deposit->reference} rejected.");
    }

    public function withdrawals()
    {
        $withdrawals = Withdrawal::with('user', 'withdrawalMethod')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.withdrawals', compact('withdrawals'));
    }

    public function approveWithdrawal(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal is already processed.');
        }

        DB::transaction(function () use ($withdrawal) {
            $withdrawal->update([
                'status' => 'approved',
                'approved_by_admin_id' => Auth::id(),
            ]);

            LedgerService::recordTransaction(
                user: $withdrawal->user,
                type: 'WITHDRAWAL_PAYOUT',
                direction: 'CREDIT',
                amount: $withdrawal->amount,
                currencyCode: $withdrawal->currency_code,
                relatedModel: $withdrawal,
                adminUser: Auth::user(),
                userNotes: "Completed Withdrawal Payout #{$withdrawal->reference}"
            );
        });

        return back()->with('success', "Withdrawal #{$withdrawal->reference} approved!");
    }

    public function rejectWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal is already processed.');
        }

        DB::transaction(function () use ($withdrawal, $request) {
            $withdrawal->update([
                'status' => 'rejected',
                'admin_notes' => $request->reason ?? 'Rejected by admin',
            ]);

            // Release hold funds back to user via ledger
            LedgerService::recordTransaction(
                user: $withdrawal->user,
                type: 'WITHDRAWAL_REJECT_RELEASE',
                direction: 'CREDIT',
                amount: $withdrawal->amount,
                currencyCode: $withdrawal->currency_code,
                relatedModel: $withdrawal,
                adminUser: Auth::user(),
                userNotes: "Withdrawal #{$withdrawal->reference} rejected; funds released back to available balance"
            );
        });

        return back()->with('success', "Withdrawal #{$withdrawal->reference} rejected and funds restored.");
    }

    public function plans()
    {
        $plans = InvestmentPlan::orderBy('display_order')->get();
        return view('admin.plans', compact('plans'));
    }

    public function storePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'min_amount' => 'required|numeric|min:1',
            'max_amount' => 'required|numeric|gte:min_amount',
            'duration_value' => 'required|integer|min:1',
            'duration_unit' => 'required|in:hours,days,weeks,months,years',
            'roi_rate' => 'required|numeric|min:0.01',
            'roi_frequency' => 'required|in:hourly,daily,weekly,monthly,yearly,at_maturity',
            'calculation_type' => 'required|in:simple,compound',
        ]);

        InvestmentPlan::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'currency_code' => 'USD',
            'duration_value' => $request->duration_value,
            'duration_unit' => $request->duration_unit,
            'roi_rate' => $request->roi_rate,
            'roi_frequency' => $request->roi_frequency,
            'calculation_type' => $request->calculation_type,
            'capital_return' => $request->boolean('capital_return'),
            'featured' => $request->boolean('featured'),
            'popular_badge' => $request->popular_badge,
            'is_active' => true,
        ]);

        return back()->with('success', 'New Investment Plan created!');
    }

    public function cms()
    {
        $branding = DynamicSettingService::branding();
        $settings = Setting::all()->keyBy('key');
        $faqs = Faq::orderBy('display_order')->get();
        $testimonials = Testimonial::orderBy('display_order')->get();

        return view('admin.cms', compact('branding', 'settings', 'faqs', 'testimonials'));
    }

    public function updateBranding(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'accent_color' => 'required|string',
            'button_radius' => 'required|string',
        ]);

        $branding = DynamicSettingService::branding();
        $branding->update([
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'accent_color' => $request->accent_color,
            'button_radius' => $request->button_radius,
        ]);

        DynamicSettingService::clearCache();

        return back()->with('success', 'Branding & Theme colors updated!');
    }

    public function updateSettings(Request $request)
    {
        $inputs = $request->except('_token');
        foreach ($inputs as $key => $val) {
            DynamicSettingService::set($key, $val);
        }

        return back()->with('success', 'Website dynamic settings saved successfully!');
    }

    public function support()
    {
        $tickets = SupportTicket::with('user', 'category', 'messages')->orderBy('updated_at', 'desc')->paginate(15);
        return view('admin.support', compact('tickets'));
    }

    public function replySupport(Request $request, SupportTicket $ticket)
    {
        $request->validate(['message' => 'required|string']);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'admin',
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        $ticket->update(['status' => 'answered']);

        return back()->with('success', 'Response posted!');
    }

    public function auditLogs()
    {
        $logs = AuditLog::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.audit-logs', compact('logs'));
    }
}
