@extends('layouts.user')

@section('title', 'Ledger Activity Statement - BlockHarvest')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Immutable Financial Ledger</h1>
            <p class="text-xs text-slate-500">Every wallet debit and credit with before-and-after snapshots.</p>
        </div>
        <div>
            <a href="{{ route('user.transactions.export') }}" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                <span>Export CSV Statement</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 uppercase text-[10px] text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Reference</th>
                        <th class="px-4 py-3">Transaction Type</th>
                        <th class="px-4 py-3">Direction</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Balance Before</th>
                        <th class="px-4 py-3">Balance After</th>
                        <th class="px-4 py-3">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($transactions as $t)
                    <tr>
                        <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $t->reference }}</td>
                        <td class="px-4 py-3 font-bold text-slate-800">{{ $t->transaction_type }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $t->direction === 'CREDIT' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $t->direction }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-bold {{ $t->direction === 'CREDIT' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $t->direction === 'CREDIT' ? '+' : '-' }}{{ auth()->user()->currency_symbol }}{{ number_format($t->amount, 2) }}
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ auth()->user()->currency_symbol }}{{ number_format($t->balance_before, 2) }}</td>
                        <td class="px-4 py-3 font-bold text-slate-900">{{ auth()->user()->currency_symbol }}{{ number_format($t->balance_after, 2) }}</td>
                        <td class="px-4 py-3 text-slate-500 max-w-xs truncate">{{ $t->user_notes }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
