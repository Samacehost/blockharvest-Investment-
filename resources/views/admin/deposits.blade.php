@extends('layouts.admin')

@section('title', 'Deposits Review Queue')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-black text-slate-900">Deposit Approvals Desk</h1>
        <p class="text-xs text-slate-500">Verify incoming payment receipts and credit investor wallet balances.</p>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 uppercase text-[10px] text-slate-500 tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Reference</th>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Method</th>
                        <th class="px-4 py-3">Net Amount</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($deposits as $d)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $d->reference }}</td>
                        <td class="px-4 py-3 font-bold text-slate-900">{{ $d->user->name ?? 'User' }}</td>
                        <td class="px-4 py-3 text-indigo-600 font-bold">{{ $d->depositMethod->name ?? 'Method' }}</td>
                        <td class="px-4 py-3 font-black text-emerald-600 text-sm">${{ number_format($d->net_amount, 2) }}</td>
                        <td class="px-4 py-3 font-bold uppercase">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $d->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($d->status === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                {{ $d->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            @if($d->status === 'pending')
                                <form action="{{ route('admin.deposits.approve', $d->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition">Approve & Credit</button>
                                </form>
                                <form action="{{ route('admin.deposits.reject', $d->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition">Reject</button>
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
        <div>{{ $deposits->links() }}</div>
    </div>
</div>
@endsection
