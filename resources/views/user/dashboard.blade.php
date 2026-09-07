@extends('layouts.user')

@section('title', 'Investor Overview - BlockHarvest')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header Card -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-8 text-white shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-400">Account Overview</span>
            <h1 class="text-3xl font-black mt-1">Welcome back, {{ $user->first_name }}!</h1>
            <p class="text-xs text-slate-300 mt-1">Managed Portfolio Ref: <code class="font-mono bg-indigo-900/60 px-2 py-0.5 rounded">{{ $user->uuid }}</code></p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('user.wallet') }}" class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg transition">
                + Make Deposit
            </a>
            <a href="{{ route('user.investments') }}" class="px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs border border-slate-700 transition">
                Explore Strategies
            </a>
        </div>
    </div>

    <!-- Balance KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-2">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Available Balance</p>
            <p class="text-3xl font-black text-slate-900">{{ $user->currency_symbol }}{{ number_format($wallet->available_balance, 2) }}</p>
            <p class="text-xs text-slate-400">Liquid for withdrawal or investment</p>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-2">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Invested Capital</p>
            <p class="text-3xl font-black text-indigo-600">{{ $user->currency_symbol }}{{ number_format($wallet->invested_balance, 2) }}</p>
            <p class="text-xs text-slate-400">Active in strategy plans</p>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-2">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Cumulative Earnings</p>
            <p class="text-3xl font-black text-emerald-600">{{ $user->currency_symbol }}{{ number_format($wallet->earnings_balance, 2) }}</p>
            <p class="text-xs text-slate-400">Total ROI harvested to date</p>
        </div>
    </div>

    <!-- Active Investments Grid -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900">Active Investments</h3>
            <a href="{{ route('user.investments') }}" class="text-xs font-bold text-indigo-600 hover:underline">View All →</a>
        </div>

        @if($activeInvestments->isEmpty())
            <div class="text-center py-10 bg-slate-50 rounded-2xl border border-dashed border-slate-200 space-y-3">
                <p class="text-sm font-semibold text-slate-600">No active investments found.</p>
                <a href="{{ route('user.investments') }}" class="inline-block px-5 py-2.5 rounded-xl text-white font-bold text-xs btn-primary">Activate a Plan</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 uppercase text-[10px] text-slate-500 tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Reference</th>
                            <th class="px-4 py-3">Plan</th>
                            <th class="px-4 py-3">Principal</th>
                            <th class="px-4 py-3">ROI Rate</th>
                            <th class="px-4 py-3">Earned Yield</th>
                            <th class="px-4 py-3">Next Accrual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($activeInvestments as $inv)
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $inv->reference }}</td>
                            <td class="px-4 py-3 font-bold text-indigo-600">{{ $inv->plan->name ?? 'Plan' }}</td>
                            <td class="px-4 py-3 font-bold text-slate-900">{{ $user->currency_symbol }}{{ number_format($inv->amount, 2) }}</td>
                            <td class="px-4 py-3 font-bold text-slate-800">{{ $inv->roi_rate }}% {{ $inv->roi_frequency }}</td>
                            <td class="px-4 py-3 font-bold text-emerald-600">{{ $user->currency_symbol }}{{ number_format($inv->total_earned_roi, 2) }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $inv->next_payout_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Recent Ledger Transactions -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900">Recent Immutable Ledger Activity</h3>
            <a href="{{ route('user.transactions') }}" class="text-xs font-bold text-indigo-600 hover:underline">Full Statement →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 uppercase text-[10px] text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Reference</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Balance After</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentTransactions as $txn)
                    <tr>
                        <td class="px-4 py-3 font-mono text-slate-800">{{ $txn->reference }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $txn->direction === 'CREDIT' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $txn->transaction_type }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-bold {{ $txn->direction === 'CREDIT' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $txn->direction === 'CREDIT' ? '+' : '-' }}{{ $user->currency_symbol }}{{ number_format($txn->amount, 2) }}
                        </td>
                        <td class="px-4 py-3 font-bold text-slate-900">{{ $user->currency_symbol }}{{ number_format($txn->balance_after, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
