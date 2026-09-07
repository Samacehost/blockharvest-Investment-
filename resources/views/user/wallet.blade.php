@extends('layouts.user')

@section('title', 'Wallet & Funds - BlockHarvest')

@section('content')
<div class="space-y-8" x-data="{ depositModal: false, withdrawalModal: false, selectedDepositMethod: '{{ $depositMethods->first()->id ?? 1 }}', balanceType: 'main' }">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Wallet Management</h1>
            <p class="text-xs text-slate-500">Deposit capital or request payout withdrawals.</p>
        </div>
        <div class="flex gap-3">
            <button @click="depositModal = true" class="px-5 py-3 rounded-xl text-white font-bold text-xs btn-primary shadow-md">
                + Make Deposit
            </button>
            <button @click="withdrawalModal = true" class="px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs shadow-md">
                Request Withdrawal
            </button>
        </div>
    </div>

    <!-- Balance Summary Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-1">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Available Balance</p>
            <p class="text-xl font-black text-slate-900">{{ auth()->user()->currency_symbol }}{{ number_format($wallet->available_balance, 2) }}</p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-indigo-100 bg-gradient-to-br from-indigo-50/30 to-white shadow-sm space-y-1">
            <p class="text-[11px] font-bold text-indigo-600 uppercase tracking-wider flex items-center justify-between">
                <span>Referral Balance</span>
                <span class="text-[9px] bg-indigo-100 text-indigo-700 font-black px-1.5 py-0.5 rounded">5% EARNED</span>
            </p>
            <p class="text-xl font-black text-indigo-600">{{ auth()->user()->currency_symbol }}{{ number_format($wallet->referral_balance ?? 0, 2) }}</p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-1">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Invested Capital</p>
            <p class="text-xl font-black text-slate-700">{{ auth()->user()->currency_symbol }}{{ number_format($wallet->invested_balance, 2) }}</p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-1">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pending Deposits</p>
            <p class="text-xl font-black text-amber-600">{{ auth()->user()->currency_symbol }}{{ number_format($wallet->pending_deposit_balance, 2) }}</p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-1">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pending Withdrawals</p>
            <p class="text-xl font-black text-rose-600">{{ auth()->user()->currency_symbol }}{{ number_format($wallet->pending_withdrawal_balance, 2) }}</p>
        </div>
    </div>

    <!-- Deposit Modal -->
    <div x-show="depositModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div @click.away="depositModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <h4 class="font-bold text-slate-900 text-lg">Fund Account Wallet</h4>
                <button @click="depositModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form action="{{ route('user.deposit.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Select Payment Method</label>
                    <select name="deposit_method_id" x-model="selectedDepositMethod" class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-900 text-sm outline-none">
                        @foreach($depositMethods as $m)
                            <option value="{{ $m->id }}">{{ $m->name }} (Min: {{ auth()->user()->currency_symbol }}{{ number_format($m->min_amount, 2) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deposit Amount ({{ auth()->user()->currency_symbol }})</label>
                    <input type="number" step="0.01" min="1" name="amount" required class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-900 text-sm outline-none">
                </div>

                <!-- Display Bank or Crypto instructions based on selection -->
                @foreach($depositMethods as $m)
                    <div x-show="selectedDepositMethod == '{{ $m->id }}'" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs space-y-2 text-slate-700">
                        <p class="font-bold text-indigo-600 uppercase">{{ $m->name }} Payment Details:</p>
                        @if(!empty($m->bank_details_json))
                            <p><strong>Bank:</strong> {{ $m->bank_details_json['bank_name'] ?? '' }}</p>
                            <p><strong>Account Name:</strong> {{ $m->bank_details_json['account_name'] ?? '' }}</p>
                            <p><strong>Account No:</strong> {{ $m->bank_details_json['account_number'] ?? '' }}</p>
                            <p><strong>Routing/SWIFT:</strong> {{ $m->bank_details_json['routing_number'] ?? ($m->bank_details_json['swift_code'] ?? '') }}</p>
                        @endif
                        @if(!empty($m->crypto_address_json))
                            <p><strong>Network:</strong> {{ $m->crypto_address_json['network'] ?? '' }}</p>
                            <p><strong>Address:</strong> <code class="font-mono bg-white px-2 py-0.5 border rounded text-slate-900 select-all">{{ $m->crypto_address_json['address'] ?? '' }}</code></p>
                        @endif
                        <p class="text-slate-500 mt-2">{{ $m->instructions }}</p>
                    </div>
                @endforeach

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Upload Payment Receipt / Proof</label>
                    <input type="file" name="proof" required accept="image/*,application/pdf" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-600 outline-none">
                </div>

                <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm btn-primary shadow-lg">
                    Submit Deposit Proof
                </button>
            </form>
        </div>
    </div>

    <!-- Withdrawal Modal -->
    <div x-show="withdrawalModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div @click.away="withdrawalModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-6">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <h4 class="font-bold text-slate-900 text-lg">Request Payout Withdrawal</h4>
                <button @click="withdrawalModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form action="{{ route('user.withdrawal.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Withdrawal Source Balance</label>
                    <select name="balance_type" x-model="balanceType" class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-900 text-sm outline-none bg-slate-50 focus:bg-white">
                        <option value="main">Main Available Balance ({{ auth()->user()->currency_symbol }}{{ number_format($wallet->available_balance, 2) }})</option>
                        <option value="referral">Referral Commission Balance ({{ auth()->user()->currency_symbol }}{{ number_format($wallet->referral_balance ?? 0, 2) }})</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Select Payout Method</label>
                    <select name="withdrawal_method_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-900 text-sm outline-none">
                        @foreach($withdrawalMethods as $wm)
                            <option value="{{ $wm->id }}">{{ $wm->name }} (Fee: {{ auth()->user()->currency_symbol }}{{ number_format($wm->fee_flat, 2) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Withdrawal Amount ({{ auth()->user()->currency_symbol }})</label>
                    <input type="number" step="0.01" min="1" :max="balanceType === 'referral' ? {{ $wallet->referral_balance ?? 0 }} : {{ $wallet->available_balance }}" name="amount" required class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-900 text-sm outline-none">
                    <p class="text-[11px] text-slate-500 mt-1">
                        Max available from <span x-text="balanceType === 'referral' ? 'Referral Balance' : 'Main Balance'" class="font-bold"></span>: 
                        <strong x-text="balanceType === 'referral' ? '{{ auth()->user()->currency_symbol }}{{ number_format($wallet->referral_balance ?? 0, 2) }}' : '{{ auth()->user()->currency_symbol }}{{ number_format($wallet->available_balance, 2) }}'"></strong>
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Destination Address / Bank Account Details</label>
                    <textarea name="destination" rows="3" required placeholder="Enter bank IBAN / Swift or Crypto wallet address..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">4-Digit Transaction PIN</label>
                    <input type="password" maxlength="4" inputmode="numeric" name="transaction_pin" required placeholder="Enter 4-digit PIN" class="w-full px-4 py-3 rounded-xl border border-slate-300 font-mono text-sm tracking-widest outline-none focus:ring-2 focus:ring-indigo-600">
                    @if(!auth()->user()->hasTransactionPin())
                        <p class="text-[11px] text-rose-600 mt-1 font-semibold">⚠️ You have not set a PIN yet. <a href="{{ route('user.profile') }}#transaction-pin-card" class="underline font-bold">Set PIN in Profile</a></p>
                    @endif
                </div>

                <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm bg-slate-900 hover:bg-slate-800 shadow-lg">
                    Confirm Withdrawal Request
                </button>
            </form>
        </div>
    </div>

    <!-- Deposit & Withdrawal History -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-900 text-base">Recent Deposits</h3>
            <div class="space-y-3">
                @foreach($deposits as $dep)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex justify-between items-center text-xs">
                    <div>
                        <p class="font-bold text-slate-900">{{ $dep->reference }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-emerald-600">+{{ auth()->user()->currency_symbol }}{{ number_format($dep->net_amount, 2) }}</p>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $dep->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($dep->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                            {{ $dep->status }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-900 text-base">Recent Withdrawals</h3>
            <div class="space-y-3">
                @foreach($withdrawals as $wth)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex justify-between items-center text-xs">
                    <div>
                        <p class="font-bold text-slate-900">{{ $wth->reference }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-rose-600">-{{ auth()->user()->currency_symbol }}{{ number_format($wth->amount, 2) }}</p>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $wth->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($wth->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                            {{ $wth->status }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
