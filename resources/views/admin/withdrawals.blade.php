@extends('layouts.admin')

@section('title', 'Withdrawals Desk')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-black text-slate-900">Withdrawals Review Desk</h1>
        <p class="text-xs text-slate-500">Review pending investor withdrawal payouts and release held funds or authorize gateway transfers.</p>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 uppercase text-[10px] text-slate-500 tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Reference</th>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Method</th>
                        <th class="px-4 py-3">Requested Amount</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($withdrawals as $w)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $w->reference }}</td>
                        <td class="px-4 py-3 font-bold text-slate-900">{{ $w->user->name ?? 'User' }}</td>
                        <td class="px-4 py-3 text-indigo-600 font-bold">{{ $w->withdrawalMethod->name ?? 'Method' }}</td>
                        <td class="px-4 py-3 font-black text-rose-600 text-sm">${{ number_format($w->amount, 2) }}</td>
                        <td class="px-4 py-3 font-bold uppercase">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $w->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($w->status === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                {{ $w->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            @if($w->status === 'pending')
                                <form action="{{ route('admin.withdrawals.approve', $w->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition">Approve Payout</button>
                                </form>
                                <form action="{{ route('admin.withdrawals.reject', $w->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition">Reject & Release Hold</button>
                                </form>
                            @else
                                <span class="text-slate-400 font-bold text-[11px]">Processed</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>{{ $withdrawals->links() }}</div>
    </div>
</div>
@endsection

