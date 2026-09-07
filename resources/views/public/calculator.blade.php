@extends('layouts.public')

@section('title', 'Interactive ROI Calculator - BlockHarvest')

@section('content')
<div class="py-16 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 space-y-3">
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Interactive ROI Projection Engine</h1>
            <p class="text-slate-600">Projections are computed dynamically by our server-side engine based on active database plans.</p>
        </div>

        <div x-data="{
            planId: '{{ $plans->first()->id ?? 1 }}',
            amount: 5000,
            loading: false,
            result: null,
            error: null,
            async calculate() {
                this.loading = true;
                this.error = null;
                try {
                    const res = await fetch('{{ route('public.calculator.api') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ plan_id: this.planId, amount: this.amount })
                    });
                    if (!res.ok) throw new Error('Calculation error');
                    this.result = await res.json();
                } catch(e) {
                    this.error = e.message;
                } finally {
                    this.loading = false;
                }
            }
        }" x-init="calculate()" class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200 shadow-xl space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Select Strategy Plan</label>
                    <select x-model="planId" @change="calculate()" class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-900 text-sm focus:ring-2 focus:ring-indigo-600 outline-none">
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} ({{ $plan->roi_rate }}% {{ ucfirst($plan->roi_frequency) }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Investment Principal ($)</label>
                    <input type="number" x-model="amount" @input.debounce.300ms="calculate()" class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-900 text-sm focus:ring-2 focus:ring-indigo-600 outline-none">
                </div>
            </div>

            <!-- Projection Output -->
            <template x-if="result">
                <div class="p-6 rounded-2xl bg-indigo-900 text-white space-y-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-b border-indigo-800 pb-6 text-center">
                        <div>
                            <p class="text-xs text-indigo-300 font-medium uppercase">Principal</p>
                            <p class="text-xl font-black text-white" x-text="'$' + result.amount"></p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-300 font-medium uppercase">Gross Profit</p>
                            <p class="text-xl font-black text-emerald-400" x-text="'$' + result.gross_profit"></p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-300 font-medium uppercase">Total Return</p>
                            <p class="text-xl font-black text-indigo-200" x-text="'$' + result.total_projected_return"></p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-300 font-medium uppercase">Periods</p>
                            <p class="text-xl font-black text-white" x-text="result.total_periods"></p>
                        </div>
                    </div>

                    <div class="text-xs text-indigo-200 flex justify-between items-center">
                        <span>Accrual Frequency: <strong class="text-white uppercase" x-text="result.roi_frequency"></strong></span>
                        <span>Calculation Model: <strong class="text-white uppercase" x-text="result.calculation_type"></strong></span>
                        <span>Capital Return: <strong class="text-white" x-text="result.capital_returned ? 'Yes' : 'No'"></strong></span>
                    </div>
                </div>
            </template>

            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs leading-relaxed">
                <strong>Disclaimer:</strong> All values displayed above represent server-validated estimates based on current plan rates. Returns depend on plan activation and terms acceptance.
            </div>

            <div class="text-center pt-4">
                <a href="{{ route('register') }}" class="inline-block py-4 px-10 rounded-xl text-white font-bold text-base btn-primary shadow-xl">
                    Activate Strategy Now
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
