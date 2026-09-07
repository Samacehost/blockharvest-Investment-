@extends('layouts.admin')

@section('title', 'Super-Admin Overview')

@section('content')
<div class="space-y-8">
    <!-- Admin KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
            <p class="text-[10px] font-bold uppercase text-slate-500">Total Users</p>
            <p class="text-2xl font-black text-slate-900">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
            <p class="text-[10px] font-bold uppercase text-slate-500">Active AUM</p>
            <p class="text-2xl font-black text-indigo-600">${{ number_format($totalAum, 2) }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
            <p class="text-[10px] font-bold uppercase text-slate-500">Active Investments</p>
            <p class="text-2xl font-black text-emerald-600">{{ $activeInvestmentsCount }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
            <p class="text-[10px] font-bold uppercase text-slate-500">Pending Deposits</p>
            <p class="text-2xl font-black text-amber-600">{{ $pendingDepositsCount }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
            <p class="text-[10px] font-bold uppercase text-slate-500">Pending Withdrawals</p>
            <p class="text-2xl font-black text-rose-600">{{ $pendingWithdrawalsCount }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
            <p class="text-[10px] font-bold uppercase text-slate-500">Pending KYC</p>
            <p class="text-2xl font-black text-sky-600">{{ $pendingKycCount }}</p>
        </div>
    </div>

    <!-- Queues Table -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                <h3 class="font-black text-slate-900 text-base">Pending Deposits Queue</h3>
                <a href="{{ route('admin.deposits') }}" class="text-xs font-bold text-indigo-600 hover:underline">View All →</a>
            </div>
            <div class="space-y-3">
                @foreach($recentDeposits as $d)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex justify-between items-center text-xs">
                    <div>
                        <p class="font-bold text-slate-900">{{ $d->user->name ?? 'User' }}</p>
                        <p class="text-slate-500 font-mono text-[10px]">{{ $d->reference }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-emerald-600">+${{ number_format($d->net_amount, 2) }}</p>
                        <span class="text-[10px] font-bold uppercase text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">{{ $d->status }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                <h3 class="font-black text-slate-900 text-base">Pending Withdrawals Desk</h3>
                <a href="{{ route('admin.withdrawals') }}" class="text-xs font-bold text-indigo-600 hover:underline">View All →</a>
            </div>
            <div class="space-y-3">
                @foreach($recentWithdrawals as $w)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex justify-between items-center text-xs">
                    <div>
                        <p class="font-bold text-slate-900">{{ $w->user->name ?? 'User' }}</p>
                        <p class="text-slate-500 font-mono text-[10px]">{{ $w->reference }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-rose-600">-${{ number_format($w->amount, 2) }}</p>
                        <span class="text-[10px] font-bold uppercase text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">{{ $w->status }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
