@extends('layouts.user')

@section('title', 'Referral Program - Earn 5% Commission - BlockHarvest')
@section('page-title', 'Referral Program')

@section('content')
<div class="space-y-6" x-data="{ copied: false, link: '{{ $referralLink }}' }">

    <!-- Top Banner & Share Link Card -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <div class="lg:col-span-7 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-200 border border-indigo-400/30 text-xs font-bold uppercase tracking-widest">
                    <span>⚡ 5% Instant Commission</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Invite Friends & Earn 5% of Their Investments
                </h2>
                <p class="text-slate-300 text-sm max-w-xl leading-relaxed">
                    Share your unique referral link with colleagues, friends, and family. Every time someone registers using your link and activates an investment plan, you earn an instant 5% commission directly into your withdrawable referral balance.
                </p>
            </div>

            <!-- Copy Box -->
            <div class="lg:col-span-5 bg-white/10 backdrop-blur-md p-5 rounded-2xl border border-white/10 space-y-3">
                <div class="flex items-center justify-between text-xs text-indigo-200 font-bold uppercase tracking-wider">
                    <span>Your Referral Link</span>
                    <span>Code: <strong class="text-white font-mono">{{ $user->referral_code }}</strong></span>
                </div>
                <div class="flex items-center gap-2 bg-slate-950/60 p-1.5 pl-3 rounded-xl border border-white/10">
                    <input type="text" readonly :value="link" class="bg-transparent text-xs font-mono text-indigo-200 w-full outline-none truncate select-all">
                    <button type="button" 
                            @click="navigator.clipboard.writeText(link); copied = true; setTimeout(() => copied = false, 3000)" 
                            class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition flex items-center gap-1.5 shrink-0 shadow-lg shadow-indigo-600/30">
                        <template x-if="!copied">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                Copy Link
                            </span>
                        </template>
                        <template x-if="copied">
                            <span class="flex items-center gap-1 text-emerald-300">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Copied!
                            </span>
                        </template>
                    </button>
                </div>
                <div x-show="copied" x-transition class="text-[11px] text-emerald-400 font-semibold text-center">
                    ✓ Link copied to clipboard! Share it with your friends.
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Stat 1: Total Referrals -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Referrals</p>
                <h3 class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalReferralsCount) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Friends registered</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Stat 2: Total Volume Invested -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Referral Investment Volume</p>
                <h3 class="text-3xl font-black text-slate-900 mt-1">{{ $user->currency_symbol }}{{ number_format($totalVolumeInvested, 2) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Invested by your network</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>

        <!-- Stat 3: Total Commission Earned -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Commissions (5%)</p>
                <h3 class="text-3xl font-black text-indigo-600 mt-1">{{ $user->currency_symbol }}{{ number_format($totalCommissionEarned, 2) }}</h3>
                <p class="text-xs font-bold text-emerald-600 mt-1">Available: {{ $user->currency_symbol }}{{ number_format($wallet->referral_balance ?? 0, 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Quick Withdrawal CTA Banner if Balance > 0 -->
    @if(($wallet->referral_balance ?? 0) > 0)
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold">
                    💵
                </div>
                <div>
                    <h4 class="text-sm font-bold text-amber-900">You have {{ $user->currency_symbol }}{{ number_format($wallet->referral_balance, 2) }} in Referral Commissions!</h4>
                    <p class="text-xs text-amber-800">You can withdraw your referral commissions instantly to your crypto wallet or bank account.</p>
                </div>
            </div>
            <a href="{{ route('user.wallet') }}" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs transition shadow-md shadow-amber-600/20 whitespace-nowrap">
                Withdraw Commission Now →
            </a>
        </div>
    @endif

    <!-- Referred Users Table -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">My Referred Investors</h3>
                <p class="text-xs text-slate-500">List of friends who joined using your referral link</p>
            </div>
            <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                {{ $referrals->count() }} Referred Investors
            </span>
        </div>

        @if($referrals->isEmpty())
            <div class="py-12 text-center space-y-3 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                <div class="w-12 h-12 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center mx-auto text-xl font-bold">
                    👥
                </div>
                <h4 class="text-sm font-bold text-slate-700">No Referrals Yet</h4>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    Share your unique referral link above to start building your network and earning 5% commission on all their investments.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-y border-slate-200">
                        <tr>
                            <th class="px-4 py-3">Investor Name</th>
                            <th class="px-4 py-3">Currency</th>
                            <th class="px-4 py-3">Investments Count</th>
                            <th class="px-4 py-3">Total Invested</th>
                            <th class="px-4 py-3">Your Commission (5%)</th>
                            <th class="px-4 py-3">Joined Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($referrals as $referee)
                            @php
                                $totalInvestedByReferee = $referee->investments->sum('amount');
                                $commissionFromReferee = $totalInvestedByReferee * 0.05;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3.5 font-bold text-slate-900 flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 font-black flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($referee->first_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div>{{ $referee->name }}</div>
                                        <div class="text-[11px] text-slate-400 font-normal">{{ Str::limit($referee->email, 18) }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-semibold text-slate-700">
                                    {{ $referee->currency_code }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-slate-900">
                                    {{ $referee->investments->count() }} active
                                </td>
                                <td class="px-4 py-3 font-bold text-slate-900">
                                    {{ $referee->currency_symbol }}{{ number_format($totalInvestedByReferee, 2) }}
                                </td>
                                <td class="px-4 py-3 font-bold text-emerald-600">
                                    +{{ $user->currency_symbol }}{{ number_format($commissionFromReferee, 2) }}
                                </td>
                                <td class="px-4 py-3 text-slate-500 font-medium">
                                    {{ $referee->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Referral Commission History Table -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Commission Earnings History</h3>
                <p class="text-xs text-slate-500">Log of all 5% commissions credited to your wallet</p>
            </div>
            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                5% Automated Payouts
            </span>
        </div>

        @if($commissions->isEmpty())
            <div class="py-10 text-center text-xs text-slate-500 bg-slate-50 rounded-xl border border-slate-100">
                No referral commissions earned yet. Commissions appear automatically as soon as your referrals activate investments.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-y border-slate-200">
                        <tr>
                            <th class="px-4 py-3">Referred Friend</th>
                            <th class="px-4 py-3">Investment Amount</th>
                            <th class="px-4 py-3">Commission Rate</th>
                            <th class="px-4 py-3">Commission Earned</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Date Credited</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($commissions as $comm)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3.5 font-bold text-slate-900">
                                    {{ $comm->referee ? $comm->referee->name : 'Referred Investor' }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    {{ $comm->referee ? $comm->referee->currency_symbol : '$' }}{{ number_format($comm->investment_amount, 2) }}
                                </td>
                                <td class="px-4 py-3 font-bold text-indigo-600">
                                    {{ number_format($comm->commission_rate, 2) }}%
                                </td>
                                <td class="px-4 py-3 font-extrabold text-emerald-600">
                                    +{{ $user->currency_symbol }}{{ number_format($comm->commission_amount, 2) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                        {{ $comm->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-500 font-medium">
                                    {{ $comm->created_at->format('M d, Y H:i') }}
                                </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
