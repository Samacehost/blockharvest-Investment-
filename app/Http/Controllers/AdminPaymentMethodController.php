<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\DepositMethod;
use App\Models\WithdrawalMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminPaymentMethodController extends Controller
{
    public function index()
    {
        $depositMethods = DepositMethod::orderBy('id', 'desc')->get();
        $withdrawalMethods = WithdrawalMethod::orderBy('id', 'desc')->get();

        return view('admin.payment-methods', compact('depositMethods', 'withdrawalMethods'));
    }

    public function storeDepositMethod(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'currency_code' => 'required|string|in:USD,GBP,CAD,ALL',
            'min_amount' => 'required|numeric|min:0.01',
            'max_amount' => 'required|numeric|gte:min_amount',
            'fee_flat' => 'nullable|numeric|min:0',
            'fee_percent' => 'nullable|numeric|min:0',
            'instructions' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'account_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'routing_number' => 'nullable|string',
            'swift_code' => 'nullable|string',
            'crypto_network' => 'nullable|string',
            'crypto_address' => 'nullable|string',
        ]);

        $code = Str::slug($request->name) . '_' . strtolower($request->currency_code) . '_' . Str::random(4);

        $bankJson = null;
        if ($request->filled('bank_name') || $request->filled('account_number')) {
            $bankJson = [
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'routing_number' => $request->routing_number,
                'swift_code' => $request->swift_code,
            ];
        }

        $cryptoJson = null;
        if ($request->filled('crypto_address')) {
            $cryptoJson = [
                'network' => $request->crypto_network ?? 'Crypto Network',
                'address' => $request->crypto_address,
            ];
        }

        $method = DepositMethod::create([
            'name' => $request->name,
            'code' => $code,
            'type' => 'manual',
            'currency_code' => $request->currency_code,
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'fee_flat' => $request->fee_flat ?? 0,
            'fee_percent' => $request->fee_percent ?? 0,
            'bank_details_json' => $bankJson,
            'crypto_address_json' => $cryptoJson,
            'instructions' => $request->instructions,
            'is_active' => true,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => 'CREATE_DEPOSIT_METHOD',
            'auditable_type' => DepositMethod::class,
            'auditable_id' => $method->id,
            'new_values_json' => $method->toArray(),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Deposit Method '{$method->name}' created successfully for {$request->currency_code}!");
    }

    public function toggleDepositMethod(DepositMethod $method)
    {
        $method->update(['is_active' => !$method->is_active]);
        return back()->with('success', "Deposit Method '{$method->name}' status updated to " . ($method->is_active ? 'Active' : 'Disabled') . ".");
    }

    public function storeWithdrawalMethod(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'currency_code' => 'required|string|in:USD,GBP,CAD,ALL',
            'min_amount' => 'required|numeric|min:0.01',
            'max_amount' => 'required|numeric|gte:min_amount',
            'fee_flat' => 'nullable|numeric|min:0',
            'fee_percent' => 'nullable|numeric|min:0',
        ]);

        $code = Str::slug($request->name) . '_payout_' . strtolower($request->currency_code) . '_' . Str::random(4);

        $method = WithdrawalMethod::create([
            'name' => $request->name,
            'code' => $code,
            'currency_code' => $request->currency_code,
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'fee_flat' => $request->fee_flat ?? 0,
            'fee_percent' => $request->fee_percent ?? 0,
            'is_active' => true,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => 'CREATE_WITHDRAWAL_METHOD',
            'auditable_type' => WithdrawalMethod::class,
            'auditable_id' => $method->id,
            'new_values_json' => $method->toArray(),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Payout Withdrawal Method '{$method->name}' created successfully for {$request->currency_code}!");
    }

    public function toggleWithdrawalMethod(WithdrawalMethod $method)
    {
        $method->update(['is_active' => !$method->is_active]);
        return back()->with('success', "Withdrawal Method '{$method->name}' status updated to " . ($method->is_active ? 'Active' : 'Disabled') . ".");
    }
}
