@extends('layouts.public')

@section('title', 'BlockHarvest - Database-Driven Institutional Investment Management')

@section('content')

<!-- Section 3: Detailed Hero Section -->
<section class="relative bg-gradient-to-b from-white via-slate-50 to-white pt-12 pb-20 border-b border-slate-200 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                        Next-Gen Portfolio Yield Engine
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200">
                        Active Currency: <strong class="text-indigo-600 font-black">{{ $currentCurrency }}</strong>
                    </span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-tight">
                    Grow Your Wealth with Next-Gen 
                    <span x-data="{
                            words: ['Yield Harvesting', 'Crypto Portfolios', 'Algorithmic Staking', 'DeFi Arbitrage', 'Institutional Yield'],
                            currentIndex: 0,
                            visible: true,
                            init() {
                                setInterval(() => {
                                    this.visible = false;
                                    setTimeout(() => {
                                        this.currentIndex = (this.currentIndex + 1) % this.words.length;
                                        this.visible = true;
                                    }, 250);
                                }, 2000);
                            }
                          }" 
                          class="inline-block transition-all duration-300 transform" 
                          :class="visible ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 -translate-y-2 scale-95'">
                        <span x-text="words[currentIndex]" 
                              class="bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-500 bg-clip-text text-transparent font-black underline decoration-purple-300/50 decoration-4 underline-offset-8">
                            Yield Harvesting
                        </span>
                    </span>
                </h1>

                <p class="text-lg text-slate-600 leading-relaxed font-medium">
                    {{ \App\Services\DynamicSettingService::get('hero_subheadline', 'Turn your capital into consistent daily returns with automated portfolio strategies. Enjoy high-yielding investment tiers, seamless multi-currency payouts in USD ($), GBP (£), and CAD (C$), and total asset security.') }}
                </p>

                <div class="flex flex-wrap gap-4 pt-2">
                    <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl text-white font-bold text-base btn-primary shadow-xl shadow-indigo-200 hover:-translate-y-0.5 transition">
                        Open Account in {{ $currentCurrency }}
                    </a>
                    <a href="{{ route('public.calculator') }}" class="px-8 py-4 rounded-xl text-slate-800 font-bold text-base bg-white hover:bg-slate-100 border border-slate-300 transition shadow-xs">
                        Calculate Projected Return
                    </a>
                </div>

                <div class="pt-6 grid grid-cols-3 gap-6 border-t border-slate-200">
                    <div>
                        <p class="text-2xl font-black text-slate-900">$10M+</p>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-0.5">Verified Liquidity</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-slate-900">100%</p>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-0.5">Immutable Ledger</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-slate-900">Daily</p>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-0.5">Automated Accruals</p>
                    </div>
                </div>
            </div>

            <!-- Hero Investment Summary Card -->
            <div class="lg:col-span-5 bg-slate-900 p-8 rounded-3xl text-white shadow-2xl space-y-6 border border-slate-800">
                <div class="flex justify-between items-center pb-4 border-b border-slate-800">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-400">Live Strategy Calculator</span>
                    <span class="text-xs text-slate-400">Display: {{ $currentCurrency }}</span>
                </div>
                <div x-data="{
                    amount: 5000,
                    days: 30,
                    rate: 1.8,
                    symbol: '{{ $currentCurrency === 'GBP' ? '£' : ($currentCurrency === 'CAD' ? 'C$' : '$') }}',
                    calculate() {
                        return (this.amount * (this.rate / 100) * this.days).toFixed(2);
                    }
                }" class="space-y-5">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Principal Amount (<span x-text="symbol"></span>)</label>
                        <input type="number" x-model="amount" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white font-bold outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">
                            <span>Duration</span>
                            <span x-text="days + ' Days'"></span>
                        </div>
                        <input type="range" min="7" max="90" x-model="days" class="w-full accent-indigo-500">
                    </div>
                    <div class="p-4 rounded-2xl bg-indigo-950/60 border border-indigo-500/30 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-indigo-300 font-semibold">Projected Net Yield</p>
                            <p class="text-2xl font-black text-emerald-400"><span x-text="symbol"></span><span x-text="calculate()"></span></p>
                        </div>
                        <a href="{{ route('public.calculator') }}" class="text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 px-4 py-2.5 rounded-xl transition">Full Breakdown →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 4: Trust and Platform Highlights -->
<section class="py-16 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-2">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center mx-auto text-lg">🛡️</div>
                <h4 class="font-bold text-slate-900 text-xs">Transparent Plans</h4>
                <p class="text-[11px] text-slate-500">Database-driven ROI</p>
            </div>
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-2">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center mx-auto text-lg">🔑</div>
                <h4 class="font-bold text-slate-900 text-xs">Secure Accounts</h4>
                <p class="text-[11px] text-slate-500">Private KYC Vault</p>
            </div>
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-2">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center mx-auto text-lg">🏦</div>
                <h4 class="font-bold text-slate-900 text-xs">Verified Deposits</h4>
                <p class="text-[11px] text-slate-500">Finance Desk Review</p>
            </div>
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-2">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center mx-auto text-lg">📊</div>
                <h4 class="font-bold text-slate-900 text-xs">Immutable Ledger</h4>
                <p class="text-[11px] text-slate-500">Double-Entry Audit</p>
            </div>
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-2">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center mx-auto text-lg">🌐</div>
                <h4 class="font-bold text-slate-900 text-xs">Multi-Currency</h4>
                <p class="text-[11px] text-slate-500">USD, GBP, CAD</p>
            </div>
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-2">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center mx-auto text-lg">💬</div>
                <h4 class="font-bold text-slate-900 text-xs">Dedicated Support</h4>
                <p class="text-[11px] text-slate-500">Threaded Ticket Desk</p>
            </div>
        </div>
    </div>
</section>

<!-- Section 5: About the Company -->
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-6 space-y-6">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Company Overview</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900">About BlockHarvest Global Asset Management</h2>
                <p class="text-slate-600 text-sm leading-relaxed">
                    BlockHarvest is a premier technology-driven asset harvesting and portfolio yield management platform designed for modern investors. We bridge traditional money market stability with algorithmic yields.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-1">
                        <h4 class="font-bold text-slate-900 text-sm">Our Mission</h4>
                        <p class="text-xs text-slate-500">To deliver transparent, server-validated asset returns with zero unverified claims.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-1">
                        <h4 class="font-bold text-slate-900 text-sm">Our Vision</h4>
                        <p class="text-xs text-slate-500">To empower global investors with seamless multi-currency portfolio management.</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('public.about') }}" class="inline-block px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition">
                        Learn More About Us →
                    </a>
                </div>
            </div>
            <div class="lg:col-span-6 bg-gradient-to-br from-indigo-600 to-indigo-900 p-8 rounded-3xl text-white shadow-xl space-y-4">
                <h3 class="text-2xl font-black">Built for Total Security & Precision</h3>
                <p class="text-xs text-indigo-100 leading-relaxed">
                    Every transaction, yield credit, deposit, and withdrawal on BlockHarvest is recorded with exact decimal precision and verified through strict server-side controllers.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Section 6: How It Works (7 Detailed Steps) -->
<section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <h2 class="text-3xl font-black text-slate-900">How BlockHarvest Works</h2>
            <p class="text-slate-600 text-sm">Seven simple steps from account setup to yield harvest.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold text-sm flex items-center justify-center">1</span>
                <h4 class="font-bold text-slate-900 text-base">Create Account</h4>
                <p class="text-xs text-slate-500">Sign up with email and choose your account currency.</p>
            </div>
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold text-sm flex items-center justify-center">2</span>
                <h4 class="font-bold text-slate-900 text-base">Select Currency</h4>
                <p class="text-xs text-slate-500">Set your wallet currency to USD ($), GBP (£), or CAD (C$).</p>
            </div>
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold text-sm flex items-center justify-center">3</span>
                <h4 class="font-bold text-slate-900 text-base">Complete KYC</h4>
                <p class="text-xs text-slate-500">Submit ID documents securely to unlock full withdrawal limits.</p>
            </div>
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold text-sm flex items-center justify-center">4</span>
                <h4 class="font-bold text-slate-900 text-base">Deposit Capital</h4>
                <p class="text-xs text-slate-500">Fund your account via Bank Wire or USDT TRC20.</p>
            </div>
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold text-sm flex items-center justify-center">5</span>
                <h4 class="font-bold text-slate-900 text-base">Select Strategy</h4>
                <p class="text-xs text-slate-500">Choose an active investment plan matching your horizon.</p>
            </div>
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold text-sm flex items-center justify-center">6</span>
                <h4 class="font-bold text-slate-900 text-base">Monitor Yields</h4>
                <p class="text-xs text-slate-500">Watch automated daily ROI payouts credit your ledger.</p>
            </div>
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 lg:col-span-2 space-y-3">
                <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold text-sm flex items-center justify-center">7</span>
                <h4 class="font-bold text-slate-900 text-base">Request Withdrawal</h4>
                <p class="text-xs text-slate-500">Withdraw available earnings directly to your bank or crypto wallet.</p>
            </div>
        </div>
    </div>
</section>

<!-- Section 7: Structured Investment Tiers (Converted to Visitor Currency) -->
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Structured Investment Plans</h2>
            <p class="text-slate-600 text-sm">Displaying plan limits converted into your selected currency: <strong class="text-indigo-600 font-black">{{ $currentCurrency }}</strong></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($plans as $plan)
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-lg flex flex-col justify-between relative {{ $plan->featured ? 'ring-2 ring-indigo-600' : '' }}">
                @if($plan->popular_badge)
                    <div class="absolute -top-3.5 right-8 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-md">
                        {{ $plan->popular_badge }}
                    </div>
                @endif
                <div class="space-y-6">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">{{ $plan->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1">{{ $plan->short_description }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100 text-center">
                        <p class="text-3xl font-black text-indigo-600">{{ $plan->roi_rate }}%</p>
                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-700 mt-1">{{ ucfirst($plan->roi_frequency) }} Accrual ({{ ucfirst($plan->calculation_type) }})</p>
                    </div>
                    <ul class="space-y-3 text-xs font-medium text-slate-600">
                        <li class="flex justify-between border-b border-slate-100 pb-2">
                            <span>Min Deposit:</span>
                            <span class="font-bold text-slate-900">{{ $plan->formatted_min }}</span>
                        </li>
                        <li class="flex justify-between border-b border-slate-100 pb-2">
                            <span>Max Deposit:</span>
                            <span class="font-bold text-slate-900">{{ $plan->formatted_max }}</span>
                        </li>
                        <li class="flex justify-between border-b border-slate-100 pb-2">
                            <span>Duration:</span>
                            <span class="font-bold text-slate-900">{{ $plan->duration_value }} {{ ucfirst($plan->duration_unit) }}</span>
                        </li>
                        <li class="flex justify-between border-b border-slate-100 pb-2">
                            <span>Capital Return:</span>
                            <span class="font-bold text-emerald-600">{{ $plan->capital_return ? 'Yes (At Maturity)' : 'Included in ROI' }}</span>
                        </li>
                    </ul>
                </div>
                <div class="pt-8">
                    <a href="{{ route('register') }}" class="w-full block text-center py-3.5 px-4 rounded-xl text-white font-bold text-sm btn-primary shadow-md transition">
                        Start Investing in {{ $currentCurrency }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Section 8: ROI Calculator Preview -->
<section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="text-center space-y-2">
            <h2 class="text-3xl font-black text-slate-900">Interactive ROI Calculator Preview</h2>
            <p class="text-slate-600 text-sm">Calculates projected returns directly using active database plans in <strong>{{ $currentCurrency }}</strong>.</p>
        </div>

        <div class="p-8 bg-slate-50 border border-slate-200 rounded-3xl shadow-sm text-center space-y-6">
            <p class="text-xs text-slate-500">Projections are derived from real-time plan parameters. Final earnings depend on strategy activation.</p>
            <a href="{{ route('public.calculator') }}" class="inline-block py-3.5 px-8 rounded-xl text-white font-bold text-sm btn-primary shadow-lg">
                Launch Interactive ROI Calculator →
            </a>
        </div>
    </div>
</section>

<!-- Section 9: Why Choose Us -->
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <h2 class="text-3xl font-black text-slate-900">Why Choose BlockHarvest</h2>
            <p class="text-slate-600 text-sm">Built for security, transparency, and multi-currency convenience.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 bg-white rounded-3xl border border-slate-200 shadow-xs space-y-2">
                <h4 class="font-bold text-slate-900 text-base">🔒 Secure Infrastructure</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Protected with CSRF shields, rate limiting, and encrypted private document storage.</p>
            </div>
            <div class="p-6 bg-white rounded-3xl border border-slate-200 shadow-xs space-y-2">
                <h4 class="font-bold text-slate-900 text-base">🌐 Multi-Currency Support</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Full support for USD ($), GBP (£), and CAD (C$) with server-validated exchange rates.</p>
            </div>
            <div class="p-6 bg-white rounded-3xl border border-slate-200 shadow-xs space-y-2">
                <h4 class="font-bold text-slate-900 text-base">📄 Immutable Ledger</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Every deposit, investment debit, and yield payout creates a traceable double-entry record.</p>
            </div>
        </div>
    </div>
</section>

<!-- Section 10: Security and Transparency -->
<section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="text-center space-y-3">
            <h2 class="text-3xl font-black text-slate-900">Security & Transparency Model</h2>
            <p class="text-slate-600 text-sm">We strictly present verified database activity and compliance standards.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs text-slate-600">
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <h4 class="font-bold text-slate-900 text-sm">Private KYC Vault</h4>
                <p>Identity documents are encrypted and saved in isolated non-public storage directories accessed strictly via 15-minute temporary signed URLs.</p>
            </div>
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <h4 class="font-bold text-slate-900 text-sm">Audit Trail & Alerts</h4>
                <p>Every balance adjustment or status modification generates an immutable audit log entry and notifies the account holder.</p>
            </div>
        </div>
    </div>
</section>

<!-- Section 11: Platform Statistics -->
<section class="py-16 bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <p class="text-4xl font-black text-indigo-400">3</p>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Supported Currencies</p>
            </div>
            <div>
                <p class="text-4xl font-black text-indigo-400">100%</p>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Audited Ledger</p>
            </div>
            <div>
                <p class="text-4xl font-black text-indigo-400">24/7</p>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Ticket Support</p>
            </div>
            <div>
                <p class="text-4xl font-black text-indigo-400">Instant</p>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Accrual Engine</p>
            </div>
        </div>
    </div>
</section>

<!-- Section 12: Supported Countries and Currencies -->
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center space-y-3">
            <h2 class="text-3xl font-black text-slate-900">Supported Countries & Currencies</h2>
            <p class="text-slate-600 text-sm">BlockHarvest natively supports three major global fiat currencies.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 bg-white rounded-3xl border border-slate-200 text-center space-y-3 shadow-xs">
                <span class="text-4xl">🇺🇸</span>
                <h4 class="font-bold text-slate-900 text-lg">United States</h4>
                <p class="text-xs font-mono font-bold text-indigo-600">USD ($)</p>
                <p class="text-[11px] text-slate-500">Platform Base Currency</p>
            </div>
            <div class="p-6 bg-white rounded-3xl border border-slate-200 text-center space-y-3 shadow-xs">
                <span class="text-4xl">🇬🇧</span>
                <h4 class="font-bold text-slate-900 text-lg">United Kingdom</h4>
                <p class="text-xs font-mono font-bold text-indigo-600">GBP (£)</p>
                <p class="text-[11px] text-slate-500">Exchange Rate: ~0.79 USD</p>
            </div>
            <div class="p-6 bg-white rounded-3xl border border-slate-200 text-center space-y-3 shadow-xs">
                <span class="text-4xl">🇨🇦</span>
                <h4 class="font-bold text-slate-900 text-lg">Canada</h4>
                <p class="text-xs font-mono font-bold text-indigo-600">CAD (C$)</p>
                <p class="text-[11px] text-slate-500">Exchange Rate: ~1.36 USD</p>
            </div>
        </div>
    </div>
</section>

<!-- Section 13: Testimonials -->
<section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center space-y-3">
            <h2 class="text-3xl font-black text-slate-900">Investor Reviews & Testimonials</h2>
            <p class="text-slate-600 text-sm">Verified feedback from portfolio managers and investors.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($testimonials as $test)
            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200 space-y-4 flex flex-col justify-between">
                <p class="text-xs text-slate-600 italic leading-relaxed">"{{ $test->content }}"</p>
                <div>
                    <p class="font-bold text-slate-900 text-sm">{{ $test->author_name }}</p>
                    <p class="text-[11px] text-slate-500">{{ $test->author_role }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Section 14: Frequently Asked Questions -->
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center space-y-3">
            <h2 class="text-3xl font-black text-slate-900">Frequently Asked Questions</h2>
            <p class="text-slate-600 text-sm">Database-driven questions and answers.</p>
        </div>

        <div class="space-y-4">
            @foreach($faqs as $faq)
            <div x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                <button @click="open = !open" class="w-full flex justify-between items-center text-left font-bold text-slate-900 text-base">
                    <span>{{ $faq->question }}</span>
                    <span x-text="open ? '−' : '+'" class="text-indigo-600 text-xl font-bold"></span>
                </button>
                <div x-show="open" x-cloak class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    {{ $faq->answer }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Section 15: Contact and Support Section -->
<section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
        <h2 class="text-3xl font-black text-slate-900">Need Assistance?</h2>
        <p class="text-slate-600 text-sm max-w-xl mx-auto">Our specialized financial support team is available via threaded support tickets and email.</p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('public.contact') }}" class="px-6 py-3 rounded-xl bg-slate-900 text-white font-bold text-xs">Contact Desk</a>
            <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold text-xs">Submit Ticket</a>
        </div>
    </div>
</section>

<!-- Section 16: Final Call-to-Action Section -->
<section class="py-24 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <h2 class="text-4xl font-black tracking-tight">Ready to Harvest Institutional Portfolio Yields?</h2>
        <p class="text-slate-300 text-base max-w-2xl mx-auto">Create your account in seconds, select your preferred currency (USD, GBP, CAD), and activate your investment tier.</p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl text-white font-bold text-base btn-primary shadow-xl">
                Open Account Now
            </a>
        </div>
    </div>
</section>

@endsection
