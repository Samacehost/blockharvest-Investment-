@extends('layouts.public')

@section('title', 'About Us - BlockHarvest Global Asset Management')
@section('meta_description', 'Discover BlockHarvest Global Asset Management Ltd — pioneering quantitative asset harvesting, double-entry immutable ledger auditing, and multi-currency yield strategies for global investors.')

@section('content')
<div class="space-y-16 py-8">

    <!-- 1. Hero Banner Section -->
    <div class="relative overflow-hidden bg-slate-950 text-white py-20 px-4 sm:px-6 lg:px-8 rounded-3xl max-w-7xl mx-auto shadow-2xl border border-slate-800">
        <!-- Subtle Glow Elements -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left Hero Content -->
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-400/20 text-indigo-300 text-xs font-bold uppercase tracking-widest">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Institutional Asset Harvesting & Yield Management
                </div>
                <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight leading-tight">
                    Pioneering <span class="bg-gradient-to-r from-indigo-400 via-indigo-200 to-emerald-400 bg-clip-text text-transparent">Database Precision</span> in Algorithmic Asset Yields
                </h1>
                <p class="text-slate-300 text-base sm:text-lg leading-relaxed font-normal">
                    BlockHarvest Global Asset Management Ltd is a premier technology-driven asset harvesting institution. Engineered on immutable double-entry ledger auditing, server-validated ROI calculations, and multi-currency capital preservation, we empower private and institutional investors to generate consistent yields with zero opacity.
                </p>
                <div class="flex flex-wrap gap-4 pt-2">
                    <a href="{{ route('register') }}" class="px-6 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm transition shadow-lg shadow-indigo-600/30">
                        Create Investor Account →
                    </a>
                    <a href="{{ route('public.plans') }}" class="px-6 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 font-bold text-sm border border-slate-700 transition">
                        Explore Strategy Tiers
                    </a>
                </div>
            </div>

            <!-- Right Hero Visual Card -->
            <div class="lg:col-span-5 relative">
                <div class="rounded-2xl overflow-hidden border border-slate-800 shadow-2xl group">
                    <img src="{{ asset('assets/images/about-hero.jpg') }}" alt="BlockHarvest Institutional Asset Platform" class="w-full h-auto object-cover transform group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 p-4 rounded-xl bg-slate-900/90 backdrop-blur-md border border-slate-800 text-xs space-y-1">
                        <div class="flex justify-between items-center text-slate-300 font-bold">
                            <span>Double-Entry Core Engine</span>
                            <span class="text-emerald-400">✓ Audited & Active</span>
                        </div>
                        <p class="text-[11px] text-slate-400">Atomic state synchronization across USD ($), GBP (£), and CAD (C$) vaults.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics Bar -->
        <div class="mt-16 pt-10 border-t border-slate-800/80 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="space-y-1">
                <p class="text-2xl sm:text-3xl font-black text-white">$480M+</p>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Assets Under Mgmt</p>
            </div>
            <div class="space-y-1">
                <p class="text-2xl sm:text-3xl font-black text-indigo-400">100%</p>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Double-Entry Audited</p>
            </div>
            <div class="space-y-1">
                <p class="text-2xl sm:text-3xl font-black text-emerald-400">37,500+</p>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Global Investors</p>
            </div>
            <div class="space-y-1">
                <p class="text-2xl sm:text-3xl font-black text-white">99.99%</p>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">System Uptime & Payouts</p>
            </div>
        </div>
    </div>

    <!-- 2. Our Mission & Corporate Vision -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100 text-xs font-bold uppercase tracking-wider">
                    Our Founding Vision
                </div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight leading-tight">
                    Democratizing Institutional Quantitative Yields for Global Capital
                </h2>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                    Founded in 2022 by a team of Wall Street quantitative traders, blockchain system architects, and asset management executives, BlockHarvest was created to eliminate the structural inefficiencies, high management fees, and lack of transparency inherent in legacy investment funds.
                </p>
                <p class="text-slate-600 text-sm leading-relaxed">
                    We believe that modern capital allocation requires uncompromising database verification. By combining high-frequency algorithmic liquidity provision with strict double-entry ledger bookkeeping, BlockHarvest offers investors direct access to daily income strategies with real-time portfolio oversight.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                        <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                            <span class="text-indigo-600">🎯</span> Institutional Mission
                        </h4>
                        <p class="text-xs text-slate-600">Provide verifiable, low-latency yield harvesting with absolute capital protection policies.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                        <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                            <span class="text-emerald-600">🛡️</span> Security Philosophy
                        </h4>
                        <p class="text-xs text-slate-600">Server-side mathematical execution, encrypted identity vaults, and mandatory PIN authorization.</p>
                    </div>
                </div>
            </div>

            <!-- Vision Feature Cards -->
            <div class="space-y-4">
                <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3 hover:border-indigo-300 transition">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center text-lg">
                        📊
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Zero Client-Side Calculation Offloading</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Unlike retail platforms that rely on unverified client scripts, all BlockHarvest ROI projections, compounding intervals, and daily payouts are computed strictly server-side with 4-decimal precision math.
                    </p>
                </div>

                <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3 hover:border-indigo-300 transition">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 font-bold flex items-center justify-center text-lg">
                        💱
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Native Multi-Currency Account Architecture</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Investors operate natively in United States Dollars (USD - $), British Pound Sterling (GBP - £), or Canadian Dollars (CAD - C$). Account statements, yield distributions, and withdrawal requests match your chosen currency without FX slippage.
                    </p>
                </div>

                <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3 hover:border-indigo-300 transition">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 font-bold flex items-center justify-center text-lg">
                        🤝
                    </div>
                    <h3 class="text-base font-bold text-slate-900">5% Automated Referral Partnership</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Our growth is driven by investor satisfaction. We offer an instant 5% referral commission on all activated investment principal referred to our platform, credited straight to your withdrawable wallet.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. The 4 Core Pillars of BlockHarvest -->
    <div class="bg-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 rounded-3xl max-w-7xl mx-auto border border-slate-800 shadow-2xl space-y-12">
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <span class="px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-bold uppercase tracking-widest border border-indigo-500/30">
                Architectural Integrity
            </span>
            <h2 class="text-3xl font-black text-white tracking-tight">The Four Pillars of BlockHarvest Excellence</h2>
            <p class="text-slate-400 text-xs sm:text-sm">
                Built from the ground up to ensure total auditability, financial solvency, and rapid execution.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pillar 1 -->
            <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 space-y-4 hover:border-indigo-500/50 transition">
                <div class="w-12 h-12 rounded-xl bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-black text-xl flex items-center justify-center">
                    01
                </div>
                <h3 class="font-bold text-white text-base">Double-Entry Ledger</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Every transaction generates immutable debit/credit paired records, maintaining absolute mathematical integrity and transaction history for audits.
                </p>
            </div>

            <!-- Pillar 2 -->
            <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 space-y-4 hover:border-indigo-500/50 transition">
                <div class="w-12 h-12 rounded-xl bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 font-black text-xl flex items-center justify-center">
                    02
                </div>
                <h3 class="font-bold text-white text-base">Delta-Neutral Yields</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Capital is deployed into delta-neutral market making and automated arbitrage strategies designed to capture yield regardless of market direction.
                </p>
            </div>

            <!-- Pillar 3 -->
            <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 space-y-4 hover:border-indigo-500/50 transition">
                <div class="w-12 h-12 rounded-xl bg-amber-600/20 text-amber-400 border border-amber-500/30 font-black text-xl flex items-center justify-center">
                    03
                </div>
                <h3 class="font-bold text-white text-base">Bank-Grade Vaults</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Private customer identity verification (KYC) documents are protected in non-public encrypted storage with 15-minute temporary access tokens.
                </p>
            </div>

            <!-- Pillar 4 -->
            <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 space-y-4 hover:border-indigo-500/50 transition">
                <div class="w-12 h-12 rounded-xl bg-purple-600/20 text-purple-400 border border-purple-500/30 font-black text-xl flex items-center justify-center">
                    04
                </div>
                <h3 class="font-bold text-white text-base">4-Digit Security PIN</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Mandatory 4-digit Transaction Security PIN protects all investments and payout withdrawals, preventing unauthorized account actions.
                </p>
            </div>
        </div>
    </div>

    <!-- 4. How Yield is Generated (Strategy Allocation) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="px-3 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-bold uppercase tracking-wider">
                Quantitative Execution
            </span>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">How BlockHarvest Generates Daily Returns</h2>
            <p class="text-slate-600 text-xs sm:text-sm">
                Our quantitative engines deploy capital across three uncorrelated liquidity tiers to maintain high yield velocity and capital protection.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Strategy 1 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold uppercase text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md">Strategy Alpha</span>
                        <span class="text-xs font-bold text-slate-400">40% Allocation</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Delta-Neutral Market Making</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Captures bid-ask spreads and liquidity rebates across decentralized and centralized order books without exposure to underlying price volatility.
                    </p>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs font-semibold text-slate-700">
                    Est. Daily Return: <strong class="text-indigo-600 font-bold">1.2% Daily</strong>
                </div>
            </div>

            <!-- Strategy 2 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold uppercase text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md">Strategy Beta</span>
                        <span class="text-xs font-bold text-slate-400">35% Allocation</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">High-Frequency Cross-Venue Arbitrage</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Exploits microsecond price dislocations across international digital asset exchanges, executing automated trade pairs with minimal latency.
                    </p>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs font-semibold text-slate-700">
                    Est. Daily Return: <strong class="text-emerald-600 font-bold">1.8% Daily</strong>
                </div>
            </div>

            <!-- Strategy 3 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold uppercase text-purple-600 bg-purple-50 px-2.5 py-1 rounded-md">Strategy Gamma</span>
                        <span class="text-xs font-bold text-slate-400">25% Allocation</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Automated Compounding Vaults</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Reinvests daily yield dividends into money-market treasury pools, accelerating compounding velocity over 90-day maturity horizons.
                    </p>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs font-semibold text-slate-700">
                    Est. Daily Return: <strong class="text-purple-600 font-bold">2.4% Compound</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Executive Leadership Team -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="px-3 py-1 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100 text-xs font-bold uppercase tracking-wider">
                Leadership & Governance
            </span>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Executive Management Team</h2>
            <p class="text-slate-600 text-xs sm:text-sm">
                Led by seasoned quantitative analysts, risk managers, and financial technology executives.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Executive 1 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3 text-center hover:shadow-md transition">
                <div class="w-16 h-16 rounded-2xl bg-indigo-600 text-white font-black text-xl flex items-center justify-center mx-auto shadow-lg shadow-indigo-200">
                    AS
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-base">Alexander Sterling</h4>
                    <p class="text-xs text-indigo-600 font-semibold">Chief Executive Officer & Co-Founder</p>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Former Head of Quantitative Trading at Goldman Sachs with over 15 years in algorithmic asset allocation.
                </p>
            </div>

            <!-- Executive 2 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3 text-center hover:shadow-md transition">
                <div class="w-16 h-16 rounded-2xl bg-emerald-600 text-white font-black text-xl flex items-center justify-center mx-auto shadow-lg shadow-emerald-200">
                    EV
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-base">Elena Vance, CFA</h4>
                    <p class="text-xs text-emerald-600 font-semibold">Chief Investment Officer</p>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Former Senior Risk Analyst at Barclays Capital specializing in delta-neutral derivative portfolio strategies.
                </p>
            </div>

            <!-- Executive 3 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3 text-center hover:shadow-md transition">
                <div class="w-16 h-16 rounded-2xl bg-purple-600 text-white font-black text-xl flex items-center justify-center mx-auto shadow-lg shadow-purple-200">
                    MT
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-base">Marcus Thorne</h4>
                    <p class="text-xs text-purple-600 font-semibold">Chief Technology Officer</p>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Distributed systems engineer and double-entry database architect with 12+ years in institutional fintech security.
                </p>
            </div>

            <!-- Executive 4 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3 text-center hover:shadow-md transition">
                <div class="w-16 h-16 rounded-2xl bg-amber-600 text-white font-black text-xl flex items-center justify-center mx-auto shadow-lg shadow-amber-200">
                    SL
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-base">Dr. Sophia Lin, PhD</h4>
                    <p class="text-xs text-amber-600 font-semibold">Head of Quantitative Research</p>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    PhD in Computational Finance from MIT. Pioneer in high-frequency liquidity pricing and cross-venue latency models.
                </p>
            </div>
        </div>
    </div>

    <!-- 6. Regulatory & Compliance Framework -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="p-8 sm:p-10 rounded-3xl bg-gradient-to-br from-slate-900 to-indigo-950 text-white shadow-xl border border-slate-800 space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-800 pb-6">
                <div>
                    <span class="text-xs font-bold uppercase text-emerald-400 tracking-wider">Compliance & Risk Disclosure</span>
                    <h3 class="text-2xl font-black text-white mt-1">Our Uncompromising Compliance Commitment</h3>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('public.terms') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold text-white transition">Terms & Conditions</a>
                    <a href="{{ route('public.risk-disclosure') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold text-white transition">Risk Disclosure</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-slate-300">
                <div class="space-y-2">
                    <h4 class="font-bold text-white text-sm flex items-center gap-1.5">
                        <span class="text-indigo-400">🛡️</span> Anti-Money Laundering (AML)
                    </h4>
                    <p class="leading-relaxed">
                        BlockHarvest enforces strict Anti-Money Laundering (AML) and Know Your Customer (KYC) identity checks for all investor accounts, adhering to global FATF standards.
                    </p>
                </div>

                <div class="space-y-2">
                    <h4 class="font-bold text-white text-sm flex items-center gap-1.5">
                        <span class="text-emerald-400">🔒</span> Encrypted Data Protection
                    </h4>
                    <p class="leading-relaxed">
                        Customer identification documents are stored in non-public encrypted vaults and accessed exclusively via temporary signed 15-minute URLs during compliance reviews.
                    </p>
                </div>

                <div class="space-y-2">
                    <h4 class="font-bold text-white text-sm flex items-center gap-1.5">
                        <span class="text-amber-400">📊</span> Real-Time Solvency Audit
                    </h4>
                    <p class="leading-relaxed">
                        Our double-entry accounting engine maintains real-time ledger balance proofs, ensuring every dollar credited to an investor wallet is backed by liquid treasury capital.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 7. Call To Action (CTA) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-slate-900 text-white text-center space-y-6 shadow-2xl relative overflow-hidden">
            <div class="relative z-10 max-w-3xl mx-auto space-y-4">
                <h2 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                    Start Harvesting Institutional Yields Today
                </h2>
                <p class="text-indigo-100 text-sm sm:text-base leading-relaxed">
                    Join over 37,500 investors worldwide leveraging database precision, multi-currency accounts, and automated daily payouts.
                </p>
                <div class="pt-4 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl bg-white hover:bg-slate-100 text-indigo-900 font-extrabold text-sm transition shadow-xl">
                        Open Free Investor Account
                    </a>
                    <a href="{{ route('public.calculator') }}" class="px-8 py-4 rounded-xl bg-indigo-900/80 hover:bg-indigo-900 text-white font-bold text-sm border border-indigo-400/30 transition">
                        Calculate Projected Returns
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
