@extends('layouts.public')

@section('title', 'Risk Disclosure Statement - BlockHarvest')

@section('content')
<div class="py-16 bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <!-- Header Hero Box -->
        <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-8 sm:p-12 shadow-2xl text-white space-y-4 text-center border border-slate-800">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-xs font-bold uppercase tracking-wider">
                ⚠️ High-Risk Investment Warning
            </div>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">Risk Disclosure Statement</h1>
            <p class="text-sm text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Comprehensive breakdown of financial, market, currency, technology, and operational risks associated with digital asset harvesting and managed yield allocation strategies.
            </p>
        </div>

        <!-- Dynamic Setting Alert Box -->
        <div class="p-6 sm:p-8 rounded-3xl bg-amber-50 border-2 border-amber-200 shadow-md text-amber-950 space-y-3">
            <div class="flex items-center gap-3">
                <span class="text-2xl">📢</span>
                <h3 class="font-extrabold text-base text-amber-900 uppercase tracking-wide">Official Platform Regulatory Notice</h3>
            </div>
            <p class="text-xs sm:text-sm font-semibold leading-relaxed">
                {{ \App\Services\DynamicSettingService::get('risk_warning_text', 'Trading and investing in algorithmic yield portfolios involves substantial risk of capital loss. Past performance yields are not indicative of future returns. Ensure you fully understand all risks involved before committing capital.') }}
            </p>
        </div>

        <!-- Risk Breakdown Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Risk Card 1: Capital & Market Volatility -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xl mb-2">
                    📉
                </div>
                <h3 class="text-lg font-bold text-slate-900">1. Capital at Risk & Market Volatility</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Financial allocations on BlockHarvest are subject to market volatility. Asset prices and yield parameters fluctuate rapidly due to macroeconomic conditions, liquidity shifts, and digital market developments.
                </p>
                <div class="p-3 rounded-xl bg-rose-50/60 border border-rose-100 text-rose-900 text-xs font-semibold">
                    Important: You should never invest funds that you cannot afford to lose entirely.
                </div>
            </div>

            <!-- Risk Card 2: Multi-Currency Exchange Volatility -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl mb-2">
                    💱
                </div>
                <h3 class="text-lg font-bold text-slate-900">2. Multi-Currency Exchange Volatility</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    BlockHarvest supports multi-currency accounts in <strong>USD ($)</strong>, <strong>GBP (£)</strong>, and <strong>CAD (C$)</strong>.
                </p>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Foreign exchange rates fluctuate constantly. If your primary bank balance is denominated in a non-USD currency, value variations between USD, GBP, and CAD may affect your final realized returns upon conversion.
                </p>
            </div>

            <!-- Risk Card 3: Non-Guaranteed Projections -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl mb-2">
                    🧮
                </div>
                <h3 class="text-lg font-bold text-slate-900">3. ROI Calculator & Yield Projections</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Projections rendered by our platform ROI calculator, investment tier summaries, and historical earnings charts are generated purely for illustrative, hypothetical purposes.
                </p>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    They do not guarantee specific rate distributions, as daily yields are subject to database validation and market performance.
                </p>
            </div>

            <!-- Risk Card 4: Technical & System Operations -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl mb-2">
                    🖥️
                </div>
                <h3 class="text-lg font-bold text-slate-900">4. Technological & Infrastructure Risks</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Our platform relies on automated double-entry ledger databases, API data feeds, and server infrastructure.
                </p>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    While we maintain 99.9% operational uptime and enterprise encryption, hardware outages, network latency, or third-party gateway disruptions may occasionally affect payout schedules.
                </p>
            </div>

            <!-- Risk Card 5: Liquidity & Lock-up Controls -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl mb-2">
                    🔒
                </div>
                <h3 class="text-lg font-bold text-slate-900">5. Liquidity Holds & Lock-up Periods</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Investment tiers require capital commitment for specified durations (e.g. 7 days, 14 days, 30 days). Capital locked in active yield contracts cannot be withdrawn prior to cycle maturity without incurring emergency processing penalties.
                </p>
            </div>

            <!-- Risk Card 6: Regulatory & Tax Obligations -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl mb-2">
                    ⚖️
                </div>
                <h3 class="text-lg font-bold text-slate-900">6. Regulatory Compliance & Tax Duty</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Tax laws regarding digital asset investments and yield earnings vary by legal jurisdiction. Investors are solely responsible for reporting income and fulfilling tax liabilities to their national tax authorities.
                </p>
            </div>

        </div>

        <!-- Investor Acknowledgment Box -->
        <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-xl border border-slate-200 space-y-6 text-center">
            <div class="max-w-2xl mx-auto space-y-3">
                <h2 class="text-2xl font-black text-slate-900">Investor Duty of Care & Acknowledgment</h2>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    By funding an account or starting an investment contract on BlockHarvest, you acknowledge that you have read, understood, and accepted all risk disclosures outlined above.
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('public.plans') }}" class="px-8 py-3.5 rounded-2xl bg-indigo-600 text-white font-bold text-xs shadow-lg hover:bg-indigo-700 transition w-full sm:w-auto">
                    Explore Investment Plans
                </a>
                <a href="{{ route('public.terms') }}" class="px-8 py-3.5 rounded-2xl bg-slate-100 text-slate-700 font-bold text-xs border border-slate-300 hover:bg-slate-200 transition w-full sm:w-auto">
                    Read Terms & Conditions
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
