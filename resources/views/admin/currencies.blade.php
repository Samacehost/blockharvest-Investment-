@extends('layouts.admin')

@section('title', 'Currency & Exchange Rate Controls')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Supported Currencies & Limits Desk</h1>
            <p class="text-xs text-slate-500">Base Currency: <strong class="text-indigo-600 font-bold">USD ($)</strong> • All conversion rates are referenced against USD base.</p>
        </div>
    </div>

    <!-- Supported Currencies Cards & Forms -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($currencies as $c)
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">{{ $c->flag }}</span>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">{{ $c->name }}</h3>
                        <p class="text-xs font-mono font-bold text-indigo-600">{{ $c->code }} ({{ $c->symbol }})</p>
                    </div>
                </div>
                @if($c->is_default)
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">BASE</span>
                @endif
            </div>

            <form action="{{ route('admin.currencies.update', $c->id) }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Currency Name</label>
                    <input name="name" type="text" value="{{ $c->name }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Symbol</label>
                    <input name="symbol" type="text" value="{{ $c->symbol }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Exchange Rate (1 USD = ? {{ $c->code }})</label>
                    <input name="exchange_rate_to_default" type="number" step="0.000001" value="{{ $c->exchange_rate_to_default }}" required {{ $c->is_default ? 'readonly' : '' }} class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Min Deposit</label>
                        <input name="min_deposit" type="number" step="0.01" value="{{ $c->min_deposit }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Max Deposit</label>
                        <input name="max_deposit" type="number" step="0.01" value="{{ $c->max_deposit }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Min Withdrawal</label>
                        <input name="min_withdrawal" type="number" step="0.01" value="{{ $c->min_withdrawal }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Max Withdrawal</label>
                        <input name="max_withdrawal" type="number" step="0.01" value="{{ $c->max_withdrawal }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold">
                    </div>
                </div>
                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-700 font-bold">
                        <input type="checkbox" name="is_active" value="1" {{ $c->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600">
                        <span>Active for Display & Registration</span>
                    </label>
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 font-bold text-white rounded-xl shadow-xs transition">
                    Save {{ $c->code }} Settings
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <!-- Currency Change Requests -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4">
        <h3 class="font-bold text-slate-900 text-base">Pending Account Currency Change Requests</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 uppercase text-[10px] text-slate-500 tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Current -> Requested</th>
                        <th class="px-4 py-3">Reason</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requests as $r)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-bold text-slate-900">{{ $r->user->name ?? 'User' }}</td>
                        <td class="px-4 py-3 font-bold text-indigo-600">{{ $r->current_currency }} → {{ $r->requested_currency }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $r->reason }}</td>
                        <td class="px-4 py-3 uppercase font-bold text-amber-700">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $r->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($r->status === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                {{ $r->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            @if($r->status === 'pending')
                                <form action="{{ route('admin.currencies.approve-change', $r->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-xs transition">Approve</button>
                                </form>
                                <form action="{{ route('admin.currencies.reject-change', $r->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-xs transition">Reject</button>
                                </form>
                            @else
                                <span class="text-slate-400 font-bold text-[11px]">Processed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-400 font-bold">No pending currency change requests.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
