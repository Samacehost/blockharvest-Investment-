@extends('layouts.admin')

@section('title', 'Investment Plan Manager')

@section('content')
<div class="space-y-8" x-data="{ newPlanModal: false }">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Investment Plans Engine</h1>
            <p class="text-xs text-slate-500">Configure yield strategies, ROI rates, calculation frequency, and minimum capital limits.</p>
        </div>
        <button @click="newPlanModal = true" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
            + Create Investment Plan
        </button>
    </div>

    <!-- Create Plan Modal -->
    <div x-show="newPlanModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/70 flex items-center justify-center p-4">
        <div @click.away="newPlanModal = false" class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-lg w-full text-slate-800 space-y-4 max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h4 class="font-black text-slate-900 text-lg">Create New Plan</h4>
                <button @click="newPlanModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form action="{{ route('admin.plans.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Plan Name</label>
                    <input name="name" type="text" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Min Deposit ($)</label>
                        <input name="min_amount" type="number" step="0.01" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Max Deposit ($)</label>
                        <input name="max_amount" type="number" step="0.01" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Duration Value</label>
                        <input name="duration_value" type="number" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Duration Unit</label>
                        <select name="duration_unit" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">ROI Rate (%)</label>
                        <input name="roi_rate" type="number" step="0.01" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Payout Frequency</label>
                        <select name="roi_frequency" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="at_maturity">At Maturity</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Calculation Type</label>
                    <select name="calculation_type" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
                        <option value="simple">Simple Interest</option>
                        <option value="compound">Compound Interest</option>
                    </select>
                </div>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                        <input type="checkbox" name="capital_return" value="1" checked class="rounded border-slate-300 text-indigo-600">
                        <span>Return Capital at Maturity</span>
                    </label>
                </div>
                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 font-bold rounded-xl text-xs text-white shadow-md transition">
                    Save New Strategy Plan
                </button>
            </form>
        </div>
    </div>

    <!-- Plans List -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($plans as $p)
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4 hover:border-slate-300 transition">
            <div class="flex justify-between items-start">
                <h3 class="font-bold text-slate-900 text-lg">{{ $p->name }}</h3>
                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase">{{ $p->roi_frequency }}</span>
            </div>
            <p class="text-3xl font-black text-indigo-600">{{ $p->roi_rate }}% <span class="text-xs text-slate-500 font-normal">ROI</span></p>
            <div class="text-xs space-y-1.5 text-slate-600 pt-2 border-t border-slate-100">
                <div class="flex justify-between"><span class="text-slate-400">Capital Range:</span> <span class="font-bold text-slate-800">${{ number_format($p->min_amount, 2) }} - ${{ number_format($p->max_amount, 2) }}</span></div>
                <div class="flex justify-between"><span class="text-slate-400">Duration:</span> <span class="font-bold text-slate-800">{{ $p->duration_value }} {{ $p->duration_unit }}</span></div>
                <div class="flex justify-between"><span class="text-slate-400">Calculation:</span> <span class="font-bold text-slate-800">{{ ucfirst($p->calculation_type) }}</span></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
