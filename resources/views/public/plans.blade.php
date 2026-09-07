@extends('layouts.public')

@section('title', 'Investment Plans - BlockHarvest')

@section('content')
<div class="py-16 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Investment Plans & Comparison</h1>
            <p class="text-slate-600">All terms, minimums, maximums, and payout rules are database-configured and server-validated.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($plans as $plan)
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-lg flex flex-col justify-between relative">
                @if($plan->popular_badge)
                    <div class="absolute -top-3.5 right-8 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-md">
                        {{ $plan->popular_badge }}
                    </div>
                @endif
                <div class="space-y-6">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">{{ $plan->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1">{{ $plan->short_description }}</p>
                    </div>
                    <div class="p-6 rounded-2xl bg-indigo-50 border border-indigo-100 text-center">
                        <p class="text-4xl font-black text-indigo-600">{{ $plan->roi_rate }}%</p>
                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-700 mt-1">{{ ucfirst($plan->roi_frequency) }} Accrual ({{ ucfirst($plan->calculation_type) }})</p>
                    </div>
                    <div class="space-y-3 text-sm text-slate-600">
                        <p class="text-xs font-bold uppercase text-slate-400">Plan Terms & Details</p>
                        <p class="text-xs leading-relaxed text-slate-500">{{ $plan->description }}</p>
                        <div class="pt-2 space-y-2 text-xs">
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span>Min Investment:</span>
                                <span class="font-bold text-slate-900">${{ number_format($plan->min_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span>Max Investment:</span>
                                <span class="font-bold text-slate-900">${{ number_format($plan->max_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span>Duration:</span>
                                <span class="font-bold text-slate-900">{{ $plan->duration_value }} {{ ucfirst($plan->duration_unit) }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span>Capital Return:</span>
                                <span class="font-bold text-emerald-600">{{ $plan->capital_return ? 'Returned at Maturity' : 'Amortized in Yield' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-8">
                    <a href="{{ route('register') }}" class="w-full block text-center py-3.5 px-4 rounded-xl text-white font-bold text-sm btn-primary shadow-md transition">
                        Invest in {{ $plan->name }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
