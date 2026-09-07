@extends('layouts.public')

@section('title', 'Terms & Conditions - BlockHarvest')

@section('content')
<div class="py-16 bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <!-- Header Banner -->
        <div class="bg-white rounded-3xl p-8 sm:p-12 shadow-xl border border-slate-200 text-center space-y-4">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold uppercase tracking-wider">
                🛡️ Legal Governance & Platform Agreement
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Terms & Conditions</h1>
            <p class="text-sm text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Last updated: {{ date('F j, Y') }}. Please read these Terms and Conditions carefully before using the BlockHarvest platform. By accessing or opening an account, you agree to be bound by these legal provisions.
            </p>
        </div>

        <!-- Table of Contents & Important Alert -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Sidebar Table of Contents -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-200 sticky top-28 space-y-4">
                    <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider">Document Outline</h3>
                    <nav class="space-y-2 text-xs font-semibold text-slate-600">
                        <a href="#section-1" class="block p-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition">1. Acceptance & Eligibility</a>
                        <a href="#section-2" class="block p-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition">2. Account Security & KYC</a>
                        <a href="#section-3" class="block p-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition">3. Immutable Ledger Rules</a>
                        <a href="#section-4" class="block p-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition">4. Multi-Currency Policy</a>
                        <a href="#section-5" class="block p-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition">5. Deposits & Yield Allocation</a>
                        <a href="#section-6" class="block p-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition">6. Withdrawals & AML Controls</a>
                        <a href="#section-7" class="block p-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition">7. Performance & Risk Limits</a>
                        <a href="#section-8" class="block p-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition">8. Prohibited Activities</a>
                        <a href="#section-9" class="block p-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition">9. Limitation of Liability</a>
                        <a href="#section-10" class="block p-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition">10. Governing Law</a>
                    </nav>
                </div>
            </div>

            <!-- Main Legal Content -->
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-indigo-900 text-white rounded-2xl p-6 shadow-lg space-y-2">
                    <h4 class="font-bold text-sm text-indigo-200">Legal Agreement Summary</h4>
                    <p class="text-xs text-indigo-100 leading-relaxed">
                        BlockHarvest operates a regulated institutional asset harvesting and double-entry portfolio tracking system. Accounts created are personal, non-transferable, and subject to strict anti-money laundering (AML) and identity verification protocols.
                    </p>
                </div>

                <!-- Section 1 -->
                <div id="section-1" class="bg-white rounded-2xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">1</span>
                        <h2 class="text-xl font-bold text-slate-900">Acceptance of Terms & Investor Eligibility</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        By accessing, browsing, registering, or depositing funds into BlockHarvest ("Platform"), you confirm that you are at least 18 years of age (or the legal age of majority in your jurisdiction) and possess full legal capacity to enter into binding contracts.
                    </p>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Access to our services may be restricted in certain jurisdictions where investment harvesting or digital asset allocation is prohibited by local statutes. It is your sole responsibility to ensure compliance with laws applicable in your country of residence.
                    </p>
                </div>

                <!-- Section 2 -->
                <div id="section-2" class="bg-white rounded-2xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">2</span>
                        <h2 class="text-xl font-bold text-slate-900">Account Registration, Security & KYC Compliance</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        To access investment features, users must complete registration by providing accurate, current, and complete legal personal information. 
                    </p>
                    <ul class="list-disc pl-5 text-xs sm:text-sm text-slate-600 space-y-1">
                        <li>You are responsible for maintaining the confidentiality of your login credentials and two-factor authentication keys.</li>
                        <li>BlockHarvest mandates Identity Verification (KYC) prior to executing capital withdrawals or changing primary wallet preferences.</li>
                        <li>Submitting falsified identity documents will result in immediate account termination and reporting to compliance authorities.</li>
                    </ul>
                </div>

                <!-- Section 3 -->
                <div id="section-3" class="bg-white rounded-2xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">3</span>
                        <h2 class="text-xl font-bold text-slate-900">Immutable Ledger & Double-Entry Accounting Rules</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        All platform financial operations—including deposits, active investments, yield distributions, fees, and withdrawals—are governed by an immutable double-entry ledger database architecture.
                    </p>
                    <div class="p-4 rounded-xl bg-slate-100 text-slate-700 text-xs font-mono space-y-1 border border-slate-200">
                        <p class="font-bold text-slate-900">Ledger Invariant Contract:</p>
                        <p>Total Assets = Total Liabilities + Total Investor Equity</p>
                        <p class="text-[11px] text-slate-500">Every transaction generates cryptographic credit/debit pairs verified server-side prior to execution.</p>
                    </div>
                </div>

                <!-- Section 4 -->
                <div id="section-4" class="bg-white rounded-2xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">4</span>
                        <h2 class="text-xl font-bold text-slate-900">Multi-Currency Policy (USD, GBP, CAD)</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        BlockHarvest natively supports account denominated transactions in United States Dollars (USD), British Pounds Sterling (GBP), and Canadian Dollars (CAD).
                    </p>
                    <ul class="list-disc pl-5 text-xs sm:text-sm text-slate-600 space-y-1">
                        <li>Each user selects a primary account currency during registration or via guest preferences.</li>
                        <li>Foreign currency amounts are converted based on real-time institutional exchange rates stored in the database.</li>
                        <li>To maintain ledger consistency, registered accounts with active investments or non-zero balances cannot change their base currency without formal compliance review.</li>
                    </ul>
                </div>

                <!-- Section 5 -->
                <div id="section-5" class="bg-white rounded-2xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">5</span>
                        <h2 class="text-xl font-bold text-slate-900">Deposits, Investment Tiers & Yield Allocation</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Deposits must be executed using authorized payment gateways or verified cryptocurrency deposit addresses displayed in your dashboard.
                    </p>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Once allocated to an Investment Tier (e.g., Core Yield, Institutional Growth, Quantum Harvest), funds undergo automated lock-up periods during which interest yields accrue on a scheduled cron basis. Capital principal and returns are credited directly to your primary wallet upon cycle completion.
                    </p>
                </div>

                <!-- Section 6 -->
                <div id="section-6" class="bg-white rounded-2xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">6</span>
                        <h2 class="text-xl font-bold text-slate-900">Withdrawals, Limits & AML Anti-Fraud Controls</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Investors may request payouts of available wallet balances at any time, subject to minimum withdrawal thresholds established per currency:
                    </p>
                    <div class="grid grid-cols-3 gap-3 text-center text-xs font-bold my-2">
                        <div class="p-3 rounded-xl bg-slate-100 border border-slate-200">
                            <span class="text-slate-500 block text-[10px]">USD Minimum</span>
                            <span class="text-indigo-700 font-extrabold">$50.00 USD</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-100 border border-slate-200">
                            <span class="text-slate-500 block text-[10px]">GBP Minimum</span>
                            <span class="text-indigo-700 font-extrabold">£40.00 GBP</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-100 border border-slate-200">
                            <span class="text-slate-500 block text-[10px]">CAD Minimum</span>
                            <span class="text-indigo-700 font-extrabold">C$65.00 CAD</span>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        All withdrawals undergo automated risk checks and manual compliance audits to prevent money laundering and unauthorized fund transfers.
                    </p>
                </div>

                <!-- Section 7 -->
                <div id="section-7" class="bg-white rounded-2xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">7</span>
                        <h2 class="text-xl font-bold text-slate-900">No Guarantees & Market Risk Disclaimer</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        While BlockHarvest employs automated algorithmic harvesting strategies to optimize asset yields, all investments carry inherent market risks. Past yield performance is not a guarantee of future returns. 
                    </p>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Calculations rendered on our public ROI calculator represent estimated projections based on current tier settings and should not be construed as guaranteed financial outcomes.
                    </p>
                </div>

                <!-- Section 8 -->
                <div id="section-8" class="bg-white rounded-2xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">8</span>
                        <h2 class="text-xl font-bold text-slate-900">Prohibited Activities & Account Termination</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Users are strictly prohibited from engaging in:
                    </p>
                    <ul class="list-disc pl-5 text-xs sm:text-sm text-slate-600 space-y-1">
                        <li>Attempting to bypass server-side validation checks or double-entry ledger logic.</li>
                        <li>Registering multiple accounts to exploit referral structures or promotional bonuses.</li>
                        <li>Utilizing automated bots or scripts to scrape data or overload platform infrastructure.</li>
                    </ul>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Violation of these terms will lead to immediate account suspension, asset freezing, and potential legal action.
                    </p>
                </div>

                <!-- Section 9 -->
                <div id="section-9" class="bg-white rounded-2xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">9</span>
                        <h2 class="text-xl font-bold text-slate-900">Limitation of Liability & System Availability</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        To the maximum extent permitted by law, BlockHarvest, its directors, employees, and technology providers shall not be liable for any indirect, incidental, or consequential damages resulting from platform downtime, network latency, or force majeure events beyond reasonable control.
                    </p>
                </div>

                <!-- Section 10 -->
                <div id="section-10" class="bg-white rounded-2xl p-6 sm:p-8 shadow-md border border-slate-200 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">10</span>
                        <h2 class="text-xl font-bold text-slate-900">Governing Law, Amendments & Contact</h2>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        These Terms & Conditions are governed by international financial technology standards and applicable commercial law. We reserve the right to amend these terms at any time by posting updated versions on the platform.
                    </p>
                    <div class="mt-4 p-4 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-indigo-900 block">Have questions regarding our Terms?</span>
                            <span class="text-[11px] text-indigo-700">Contact our Legal & Compliance Team</span>
                        </div>
                        <a href="{{ route('public.contact') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-indigo-600 text-white hover:bg-indigo-700 transition">Contact Legal</a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
