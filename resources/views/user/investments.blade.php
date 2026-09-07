@extends('layouts.user')

@section('title', 'My Investments - BlockHarvest')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Portfolio Investments</h1>
            <p class="text-xs text-slate-500">Manage active yield funds and deploy available capital.</p>
        </div>
    </div>

    <!-- Active Plans Options -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <h3 class="text-lg font-bold text-slate-900">Available Investment Tiers</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($plans as $plan)
            <div x-data="{ openModal: false }" class="p-6 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-white hover:border-indigo-500 hover:shadow-md transition space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex justify-between items-start">
                        <h4 class="font-bold text-slate-900 text-base">{{ $plan->name }}</h4>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase">{{ $plan->roi_frequency }}</span>
                    </div>
                    <p class="text-2xl font-black text-indigo-600">{{ $plan->roi_rate }}% <span class="text-xs font-semibold text-slate-500">Yield</span></p>
                    <p class="text-xs text-slate-500">Min: {{ auth()->user()->currency_symbol }}{{ number_format($plan->min_amount, 2) }} • Max: {{ auth()->user()->currency_symbol }}{{ number_format($plan->max_amount, 2) }}</p>
                </div>
                <div>
                    <button @click="openModal = true" class="w-full py-2.5 px-4 rounded-xl text-white font-bold text-xs btn-primary">
                        Invest Now
                    </button>
                </div>

                <!-- Modal Form -->
                <div x-show="openModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                    <div @click.away="openModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-6">
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                            <h4 class="font-bold text-slate-900 text-lg">Activate {{ $plan->name }}</h4>
                            <button @click="openModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                        </div>
                        <form action="{{ route('user.investments.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Investment Amount ({{ auth()->user()->currency_symbol }})</label>
                                <input type="number" step="0.01" min="{{ $plan->min_amount }}" max="{{ $plan->max_amount }}" name="amount" required value="{{ $plan->min_amount }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-900 text-sm focus:ring-2 focus:ring-indigo-600 outline-none">
                                <p class="text-[11px] text-slate-500 mt-1">Available Wallet: <strong>{{ auth()->user()->currency_symbol }}{{ number_format($wallet->available_balance ?? 0, 2) }}</strong></p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-1 text-slate-600">
                                <p><strong>Duration:</strong> {{ $plan->duration_value }} {{ $plan->duration_unit }}</p>
                                <p><strong>Rate:</strong> {{ $plan->roi_rate }}% ({{ $plan->calculation_type }})</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">4-Digit Transaction PIN</label>
                                <input type="password" maxlength="4" inputmode="numeric" name="transaction_pin" required placeholder="Enter 4-digit PIN" class="w-full px-4 py-3 rounded-xl border border-slate-300 font-mono text-sm tracking-widest outline-none focus:ring-2 focus:ring-indigo-600">
                                @if(!auth()->user()->hasTransactionPin())
                                    <p class="text-[11px] text-rose-600 mt-1 font-semibold">⚠️ You have not set a PIN yet. <a href="{{ route('user.profile') }}#transaction-pin-card" class="underline font-bold">Set PIN in Profile</a></p>
                                @endif
                            </div>
                            <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm btn-primary shadow-lg">
                                Confirm & Debit Wallet
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Active Investments Table -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <h3 class="text-lg font-bold text-slate-900">Your Investments History</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 uppercase text-[10px] text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Reference</th>
                        <th class="px-4 py-3">Plan</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Rate</th>
                        <th class="px-4 py-3">Total Earned</th>
                        <th class="px-4 py-3">Matures</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($investments as $inv)
                    <tr>
                        <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $inv->reference }}</td>
                        <td class="px-4 py-3 font-bold text-indigo-600">{{ $inv->plan->name ?? 'Plan' }}</td>
                        <td class="px-4 py-3 font-bold text-slate-900">{{ auth()->user()->currency_symbol }}{{ number_format($inv->amount, 2) }}</td>
                        <td class="px-4 py-3 font-bold text-slate-800">{{ $inv->roi_rate }}% {{ $inv->roi_frequency }}</td>
                        <td class="px-4 py-3 font-bold text-emerald-600">{{ auth()->user()->currency_symbol }}{{ number_format($inv->total_earned_roi, 2) }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $inv->matures_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $inv->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ $inv->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
