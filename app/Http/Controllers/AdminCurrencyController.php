<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Currency;
use App\Models\CurrencyChangeRequest;
use App\Models\CurrencyChangeLog;
use App\Models\User;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminCurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::orderBy('display_position')->get();
        $requests = CurrencyChangeRequest::with('user')->orderBy('created_at', 'desc')->paginate(10);
        $logs = CurrencyChangeLog::with('user')->orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.currencies', compact('currencies', 'requests', 'logs'));
    }

    public function updateCurrency(Request $request, Currency $currency)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'exchange_rate_to_default' => 'required|numeric|min:0.000001',
            'min_deposit' => 'required|numeric|min:0',
            'max_deposit' => 'required|numeric|gte:min_deposit',
            'min_withdrawal' => 'required|numeric|min:0',
            'max_withdrawal' => 'required|numeric|gte:min_withdrawal',
        ]);

        $currency->update([
            'name' => $request->name,
            'symbol' => $request->symbol,
            'exchange_rate_to_default' => $request->exchange_rate_to_default,
            'min_deposit' => $request->min_deposit,
            'max_deposit' => $request->max_deposit,
            'min_withdrawal' => $request->min_withdrawal,
            'max_withdrawal' => $request->max_withdrawal,
            'is_active' => $request->boolean('is_active'),
            'last_rate_update' => now(),
        ]);

        CurrencyService::clearCache();

        return back()->with('success', "Currency [{$currency->code}] settings updated successfully!");
    }

    public function approveChangeRequest(CurrencyChangeRequest $changeRequest)
    {
        if ($changeRequest->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }

        DB::transaction(function () use ($changeRequest) {
            $user = $changeRequest->user;
            $oldCurrency = $user->currency_code;
            $newCurrency = $changeRequest->requested_currency;

            $user->update(['currency_code' => $newCurrency]);
            $changeRequest->update([
                'status' => 'approved',
                'reviewed_by_admin_id' => Auth::id(),
            ]);

            CurrencyChangeLog::create([
                'user_id' => $user->id,
                'old_currency' => $oldCurrency,
                'new_currency' => $newCurrency,
                'changed_by_admin_id' => Auth::id(),
                'admin_notes' => "Approved currency change request #{$changeRequest->id}",
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'event' => 'APPROVE_CURRENCY_CHANGE',
                'auditable_type' => User::class,
                'auditable_id' => $user->id,
                'old_values_json' => ['currency_code' => $oldCurrency],
                'new_values_json' => ['currency_code' => $newCurrency],
                'ip_address' => request()->ip(),
            ]);
        });

        return back()->with('success', 'User account currency change approved!');
    }

    public function rejectChangeRequest(Request $request, CurrencyChangeRequest $changeRequest)
    {
        $changeRequest->update([
            'status' => 'rejected',
            'reviewed_by_admin_id' => Auth::id(),
        ]);

        return back()->with('success', 'Currency change request rejected.');
    }
}
