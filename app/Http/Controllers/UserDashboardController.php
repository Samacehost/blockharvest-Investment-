<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\DepositMethod;
use App\Models\Investment;
use App\Models\InvestmentPlan;
use App\Models\LedgerEntry;
use App\Models\SupportCategory;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Models\WithdrawalMethod;
use App\Services\InvestmentEngineService;
use App\Services\KycService;
use App\Services\LedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id, 'currency_code' => 'USD'],
            ['available_balance' => 0.0000, 'invested_balance' => 0.0000, 'earnings_balance' => 0.0000]
        );

        $activeInvestments = Investment::where('user_id', $user->id)->where('status', 'active')->get();
        $recentTransactions = LedgerEntry::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(7)->get();
        $plans = InvestmentPlan::where('is_active', true)->orderBy('display_order')->get();

        return view('user.dashboard', compact('user', 'wallet', 'activeInvestments', 'recentTransactions', 'plans'));
    }

    public function investments()
    {
        $user = Auth::user();
        $investments = Investment::where('user_id', $user->id)->with('plan')->orderBy('created_at', 'desc')->get();
        $plans = InvestmentPlan::where('is_active', true)->orderBy('display_order')->get();
        $wallet = Wallet::where('user_id', $user->id)->first();

        return view('user.investments', compact('investments', 'plans', 'wallet'));
    }

    public function storeInvestment(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:investment_plans,id',
            'amount' => 'required|numeric|min:1',
            'transaction_pin' => 'required|string',
        ]);

        $user = Auth::user();

        if (!$user->hasTransactionPin()) {
            return back()->with('error', 'You must set a 4-digit Transaction Security PIN in your Profile before activating investments.');
        }

        if (!$user->verifyTransactionPin($request->transaction_pin)) {
            return back()->with('error', 'Invalid Transaction Security PIN. Please try again.');
        }

        // Optional KYC Check if enforced
        if ($user->kyc_status !== 'approved' && config('app.kyc_required', false)) {
            return back()->with('error', 'KYC verification is required before activating investments. Please submit your identity documents.');
        }

        $plan = InvestmentPlan::findOrFail($request->plan_id);

        try {
            $investment = InvestmentEngineService::createInvestment($user, $plan, (float)$request->amount);
            return redirect()->route('user.investments')->with('success', "Investment #{$investment->reference} activated successfully!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function wallet()
    {
        $user = Auth::user();
        $userCurrency = $user->currency_code ?? 'USD';
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id, 'currency_code' => $userCurrency],
            ['available_balance' => 0.0000, 'invested_balance' => 0.0000, 'earnings_balance' => 0.0000, 'referral_balance' => 0.0000]
        );
        $depositMethods = DepositMethod::where('is_active', true)
            ->whereIn('currency_code', [$userCurrency, 'ALL'])
            ->get();
        $withdrawalMethods = WithdrawalMethod::where('is_active', true)
            ->whereIn('currency_code', [$userCurrency, 'ALL'])
            ->get();
        $deposits = Deposit::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(10)->get();
        $withdrawals = Withdrawal::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(10)->get();

        return view('user.wallet', compact('wallet', 'depositMethods', 'withdrawalMethods', 'deposits', 'withdrawals'));
    }

    public function storeDeposit(Request $request)
    {
        $request->validate([
            'deposit_method_id' => 'required|exists:deposit_methods,id',
            'amount' => 'required|numeric|min:1',
            'proof' => 'required|file|mimes:jpeg,png,pdf|max:5120',
        ]);

        $user = Auth::user();
        $method = DepositMethod::findOrFail($request->deposit_method_id);

        if ($request->amount < (float)$method->min_amount || $request->amount > (float)$method->max_amount) {
            return back()->with('error', "Deposit amount must be between $" . number_format($method->min_amount, 2) . " and $" . number_format($method->max_amount, 2));
        }

        $fee = (float)$method->fee_flat + (((float)$request->amount * (float)$method->fee_percent) / 100);
        $netAmount = max(0, (float)$request->amount - $fee);

        $path = $request->file('proof')->store('private/deposits/' . $user->id);

        $deposit = Deposit::create([
            'reference' => 'DEP-' . strtoupper(Str::random(10)),
            'user_id' => $user->id,
            'deposit_method_id' => $method->id,
            'amount' => number_format((float)$request->amount, 4, '.', ''),
            'fee' => number_format($fee, 4, '.', ''),
            'net_amount' => number_format($netAmount, 4, '.', ''),
            'currency_code' => $method->currency_code,
            'payment_proof_path' => $path,
            'status' => 'pending',
        ]);

        // Update pending deposit balance
        $wallet = Wallet::where('user_id', $user->id)->first();
        if ($wallet) {
            $wallet->pending_deposit_balance = number_format((float)$wallet->pending_deposit_balance + $netAmount, 4, '.', '');
            $wallet->save();
        }

        return redirect()->route('user.wallet')->with('success', "Deposit request #{$deposit->reference} submitted! Awaiting administrator approval.");
    }

    public function storeWithdrawal(Request $request)
    {
        $request->validate([
            'withdrawal_method_id' => 'required|exists:withdrawal_methods,id',
            'balance_type' => 'nullable|string|in:main,referral',
            'amount' => 'required|numeric|min:1',
            'destination' => 'required|string',
            'transaction_pin' => 'required|string',
        ]);

        $user = Auth::user();

        if (!$user->hasTransactionPin()) {
            return back()->with('error', 'You must set a 4-digit Transaction Security PIN in your Profile before requesting withdrawals.');
        }

        if (!$user->verifyTransactionPin($request->transaction_pin)) {
            return back()->with('error', 'Invalid Transaction Security PIN. Please try again.');
        }

        if ($user->kyc_status !== 'approved') {
            return back()->with('error', 'Identity verification (KYC) is required to process withdrawals.');
        }

        $method = WithdrawalMethod::findOrFail($request->withdrawal_method_id);
        $amount = (float)$request->amount;
        $balanceType = $request->input('balance_type', 'main');

        if ($amount < (float)$method->min_amount || $amount > (float)$method->max_amount) {
            return back()->with('error', "Withdrawal amount must be between $" . number_format($method->min_amount, 2) . " and $" . number_format($method->max_amount, 2));
        }

        $fee = (float)$method->fee_flat + (($amount * (float)$method->fee_percent) / 100);
        $netAmount = max(0, $amount - $fee);

        try {
            $withdrawal = DB::transaction(function () use ($user, $method, $amount, $fee, $netAmount, $balanceType, $request) {
                $record = Withdrawal::create([
                    'reference' => 'WTH-' . strtoupper(Str::random(10)),
                    'user_id' => $user->id,
                    'withdrawal_method_id' => $method->id,
                    'amount' => number_format($amount, 4, '.', ''),
                    'fee' => number_format($fee, 4, '.', ''),
                    'net_amount' => number_format($netAmount, 4, '.', ''),
                    'currency_code' => $method->currency_code,
                    'destination_details_json' => ['destination' => $request->destination, 'balance_type' => $balanceType],
                    'status' => 'pending',
                ]);

                $ledgerType = $balanceType === 'referral' ? 'WITHDRAWAL_HOLD_REFERRAL' : 'WITHDRAWAL_HOLD';
                $notes = $balanceType === 'referral' ? "Referral Commission withdrawal request hold #{$record->reference}" : "Withdrawal request hold #{$record->reference}";

                // Place funds on hold via ledger
                LedgerService::recordTransaction(
                    user: $user,
                    type: $ledgerType,
                    direction: 'DEBIT',
                    amount: $amount,
                    currencyCode: $method->currency_code,
                    relatedModel: $record,
                    userNotes: $notes
                );

                return $record;
            });

            return redirect()->route('user.wallet')->with('success', "Withdrawal request #{$withdrawal->reference} created!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function referrals()
    {
        $user = Auth::user();
        $referralLink = $user->referral_link;
        $wallet = Wallet::where('user_id', $user->id)->first();
        $referrals = User::where('referred_by_id', $user->id)->with('investments')->orderBy('created_at', 'desc')->get();
        $commissions = \App\Models\ReferralCommission::where('referrer_id', $user->id)->with('referee', 'investment')->orderBy('created_at', 'desc')->get();

        $totalReferralsCount = $referrals->count();
        $totalVolumeInvested = 0;
        foreach ($referrals as $referee) {
            $totalVolumeInvested += $referee->investments->sum('amount');
        }
        $totalCommissionEarned = $commissions->sum('commission_amount');

        return view('user.referrals', compact('user', 'referralLink', 'wallet', 'referrals', 'commissions', 'totalReferralsCount', 'totalVolumeInvested', 'totalCommissionEarned'));
    }

    public function transactions()
    {
        $user = Auth::user();
        $transactions = LedgerEntry::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(15);
        return view('user.transactions', compact('transactions'));
    }

    public function exportTransactions()
    {
        $user = Auth::user();
        $transactions = LedgerEntry::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        $csv = "Reference,Date,Type,Direction,Amount,Currency,Balance Before,Balance After,Notes\n";
        foreach ($transactions as $t) {
            $csv .= "\"{$t->reference}\",\"{$t->created_at}\",\"{$t->transaction_type}\",\"{$t->direction}\",\"{$t->amount}\",\"{$t->currency_code}\",\"{$t->balance_before}\",\"{$t->balance_after}\",\"{$t->user_notes}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="statement-' . date('Y-m-d') . '.csv"',
        ]);
    }

    public function kyc()
    {
        $user = Auth::user();
        $latestSubmission = $user->latestKycSubmission;
        return view('user.kyc', compact('user', 'latestSubmission'));
    }

    public function storeKyc(Request $request)
    {
        $request->validate([
            'id_type' => 'required|string',
            'id_number' => 'nullable|string',
            'id_front' => 'required|file|mimes:jpeg,png,pdf|max:10240',
            'id_back' => 'nullable|file|mimes:jpeg,png,pdf|max:10240',
            'selfie' => 'nullable|file|mimes:jpeg,png|max:10240',
        ]);

        $user = Auth::user();
        $files = array_filter([
            'id_front' => $request->file('id_front'),
            'id_back' => $request->file('id_back'),
            'selfie' => $request->file('selfie'),
        ]);

        KycService::submit($user, $request->id_type, $request->id_number, $files);

        return redirect()->route('user.kyc')->with('success', 'Your verification documents have been submitted for review!');
    }

    public function support()
    {
        $user = Auth::user();
        $tickets = SupportTicket::where('user_id', $user->id)->with('category', 'messages')->orderBy('updated_at', 'desc')->get();
        $categories = SupportCategory::where('is_active', true)->get();

        return view('user.support', compact('tickets', 'categories'));
    }

    public function storeSupportTicket(Request $request)
    {
        $request->validate([
            'support_category_id' => 'required|exists:support_categories,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = Auth::user();
        $ticket = SupportTicket::create([
            'reference' => 'TKT-' . strtoupper(Str::random(8)),
            'user_id' => $user->id,
            'support_category_id' => $request->support_category_id,
            'subject' => $request->subject,
            'priority' => 'medium',
            'status' => 'open',
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'user',
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        return redirect()->route('user.support')->with('success', "Support ticket #{$ticket->reference} opened!");
    }

    public function replySupportTicket(Request $request, SupportTicket $ticket)
    {
        $request->validate(['message' => 'required|string']);
        $user = Auth::user();

        if ($ticket->user_id !== $user->id) {
            abort(403);
        }

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'user',
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        $ticket->update(['status' => 'open']);

        return back()->with('success', 'Reply submitted!');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => "{$request->first_name} {$request->last_name}",
            'phone' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|confirmed|min:8',
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePin(Request $request)
    {
        $user = Auth::user();

        if ($user->hasTransactionPin()) {
            $request->validate([
                'current_pin' => 'required|string',
                'pin' => 'required|numeric|digits:4|confirmed',
            ]);

            if (!Hash::check($request->current_pin, $user->transaction_pin)) {
                return back()->withErrors(['current_pin' => 'Current Transaction PIN is incorrect.']);
            }
        } else {
            $request->validate([
                'pin' => 'required|numeric|digits:4|confirmed',
            ]);
        }

        // List of easy-to-guess / weak PIN patterns
        $weakPins = [
            '0000', '1111', '2222', '3333', '4444', '5555', '6666', '7777', '8888', '9999',
            '1234', '2345', '3456', '4567', '5678', '6789', '7890', '0123',
            '4321', '5432', '6543', '7654', '8765', '9876', '0987', '3210',
            '1122', '2211', '1212', '2121', '1313', '3131', '6969', '2020', '2024', '2025', '2026', '1004', '2000', '1000'
        ];

        if (in_array((string)$request->pin, $weakPins)) {
            return back()->withErrors(['pin' => 'For security reasons, easy-to-guess PINs (such as 1234, 0000, 1111, or sequential numbers) are not allowed. Please choose a strong 4-digit PIN.']);
        }

        $user->update([
            'transaction_pin' => Hash::make($request->pin),
            'pin_set_at' => now(),
        ]);

        return back()->with('success', '4-Digit Transaction Security PIN set successfully!');
    }
}
