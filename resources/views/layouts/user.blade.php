<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Investor Dashboard - BlockHarvest')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        {!! \App\Services\DynamicSettingService::generateCssVariables() !!}
        body { font-family: var(--font-family); }
        .bg-primary { background-color: var(--primary-color); }
        .btn-primary { background-color: var(--primary-color); color: #ffffff; border-radius: var(--button-radius); }
        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.6); }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(99, 102, 241, 0.4); border-radius: 4px; }
    </style>
</head>
<body x-data="{ 
    mobileDrawerOpen: false,
    successModalOpen: {{ session('success') ? 'true' : 'false' }},
    errorModalOpen: {{ session('error') || $errors->any() ? 'true' : 'false' }}
}" class="h-screen w-screen overflow-hidden flex flex-col md:flex-row bg-slate-100 font-sans antialiased text-slate-800">

    <!-- Fixed Desktop Sidebar Navigation -->
    <aside class="hidden md:flex flex-col w-64 h-screen max-h-screen sticky top-0 bg-slate-950 text-slate-300 border-r border-slate-800/80 flex-shrink-0 justify-between overflow-y-auto sidebar-scroll z-30 shadow-2xl">
        
        <div>
            <!-- Sidebar Logo Header -->
            <div class="h-20 flex items-center px-6 border-b border-slate-800/80 gap-3 bg-slate-950/80 backdrop-blur">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white font-black text-xl flex items-center justify-center shadow-lg shadow-indigo-600/30">
                    B
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-black text-white tracking-tight leading-none">Block<span class="text-indigo-400">Harvest</span></span>
                    <span class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold mt-0.5">Investor Portal</span>
                </div>
            </div>

            <!-- Investor Profile Card -->
            <div class="px-5 py-4 m-3 rounded-2xl bg-slate-900/90 border border-slate-800 space-y-2 shadow-inner">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-bold flex items-center justify-center text-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-grow">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-400 font-mono truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <!-- Status Badges Grid -->
                <div class="flex flex-wrap gap-1.5 pt-1 text-[10px] font-bold">
                    @php $userCurr = auth()->user()->currency_code ?? session('guest_currency', 'USD'); @endphp
                    <span class="px-2 py-0.5 rounded-md bg-slate-800 text-indigo-300 border border-slate-700">
                        {{ $userCurr === 'GBP' ? '🇬🇧 GBP' : ($userCurr === 'CAD' ? '🇨🇦 CAD' : '🇺🇸 USD') }}
                    </span>

                    @if(auth()->user()->kyc_status === 'approved')
                        <span class="px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">✓ KYC Approved</span>
                    @else
                        <span class="px-2 py-0.5 rounded-md bg-amber-500/10 text-amber-400 border border-amber-500/30">KYC {{ ucfirst(auth()->user()->kyc_status) }}</span>
                    @endif

                    @if(auth()->user()->hasTransactionPin())
                        <span class="px-2 py-0.5 rounded-md bg-indigo-500/10 text-indigo-400 border border-indigo-500/30">🛡️ PIN Active</span>
                    @else
                        <span class="px-2 py-0.5 rounded-md bg-rose-500/10 text-rose-400 border border-rose-500/30 animate-pulse">⚠️ No PIN</span>
                    @endif
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="px-3 py-2 space-y-1 text-xs font-semibold">
                
                <a href="{{ route('user.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('user.dashboard') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Overview Dashboard</span>
                </a>

                <a href="{{ route('user.investments') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('user.investments*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>My Investments</span>
                </a>

                <a href="{{ route('user.wallet') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('user.wallet*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <span>Wallet & Deposits</span>
                </a>

                <a href="{{ route('user.referrals') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('user.referrals*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Referral Program</span>
                </a>

                <a href="{{ route('user.transactions') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('user.transactions*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Ledger Activity</span>
                </a>

                <a href="{{ route('user.kyc') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('user.kyc*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span>KYC Identity Check</span>
                </a>

                <a href="{{ route('user.support') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('user.support*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Support Desk</span>
                </a>

                <a href="{{ route('user.profile') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('user.profile*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span>Profile & PIN Security</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer Action -->
        <div class="p-4 border-t border-slate-800/80 bg-slate-950/80">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white font-bold text-xs border border-slate-800 transition">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Slide-Over Navigation Drawer Backdrop -->
    <div x-show="mobileDrawerOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileDrawerOpen = false" 
         x-cloak 
         class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm md:hidden"></div>

    <!-- Mobile Slide-Over Navigation Drawer Panel -->
    <div x-show="mobileDrawerOpen" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         x-cloak 
         class="fixed top-0 left-0 bottom-0 w-80 max-w-[85vw] bg-slate-950 text-slate-300 z-50 flex flex-col justify-between p-5 shadow-2xl border-r border-slate-800 md:hidden overflow-y-auto sidebar-scroll">
        
        <div class="space-y-6">
            <!-- Mobile Drawer Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white font-black text-lg flex items-center justify-center">
                        B
                    </div>
                    <span class="text-lg font-black text-white tracking-tight">Block<span class="text-indigo-400">Harvest</span></span>
                </div>
                <button @click="mobileDrawerOpen = false" class="p-2 rounded-xl text-slate-400 hover:text-white bg-slate-900 border border-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Investor Profile Quick View -->
            <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 space-y-2">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600/20 text-indigo-400 font-bold flex items-center justify-center text-xs">
                        {{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-400 font-mono truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-1.5 pt-1 text-[10px] font-bold">
                    <span class="px-2 py-0.5 rounded bg-slate-800 text-indigo-300 border border-slate-700">
                        {{ $userCurr === 'GBP' ? '🇬🇧 GBP' : ($userCurr === 'CAD' ? '🇨🇦 CAD' : '🇺🇸 USD') }}
                    </span>
                    @if(auth()->user()->kyc_status === 'approved')
                        <span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">✓ KYC Approved</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/30">KYC {{ ucfirst(auth()->user()->kyc_status) }}</span>
                    @endif
                </div>
            </div>

            <!-- Drawer Links -->
            <nav class="space-y-1.5 text-xs font-semibold">
                <a href="{{ route('user.dashboard') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('user.dashboard') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <span>Overview Dashboard</span>
                </a>
                <a href="{{ route('user.investments') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('user.investments*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>My Investments</span>
                </a>
                <a href="{{ route('user.wallet') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('user.wallet*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <span>Wallet & Deposits</span>
                </a>
                <a href="{{ route('user.referrals') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('user.referrals*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Referral Program</span>
                </a>
                <a href="{{ route('user.transactions') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('user.transactions*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Ledger Activity</span>
                </a>
                <a href="{{ route('user.kyc') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('user.kyc*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span>KYC Identity Check</span>
                </a>
                <a href="{{ route('user.support') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('user.support*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Support Desk</span>
                </a>
                <a href="{{ route('user.profile') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('user.profile*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span>Profile & Security</span>
                </a>
            </nav>
        </div>

        <div class="pt-4 border-t border-slate-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 text-xs font-bold border border-slate-800">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Workspace Container (Independently Scrollable) -->
    <div class="flex-grow flex flex-col min-w-0 h-screen max-h-screen overflow-y-auto pb-24 md:pb-0">
        
        <!-- Modern Sleek Header Bar -->
        <header class="h-20 bg-white/95 backdrop-blur border-b border-slate-200/80 px-4 sm:px-6 flex items-center justify-between shadow-xs sticky top-0 z-20 flex-shrink-0">
            
            <!-- Mobile Header Content -->
            <div class="flex md:hidden items-center justify-between w-full">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white font-black text-lg flex items-center justify-center shadow-md shadow-indigo-200">
                        B
                    </div>
                    <div>
                        <span class="text-base font-black text-slate-900 tracking-tight leading-none block">Block<span class="text-indigo-600">Harvest</span></span>
                        <span class="text-[9px] text-emerald-600 font-bold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Portfolio Desk Active
                        </span>
                    </div>
                </div>

                <!-- Hamburger Button -->
                <button @click="mobileDrawerOpen = true" 
                        class="p-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200/80 transition flex items-center justify-center shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Desktop Header Content -->
            <div class="hidden md:flex items-center justify-between w-full">
                <!-- Page Context / Title -->
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-indigo-50 text-indigo-600 font-bold border border-indigo-100/80">
                        🛡️
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 leading-tight">Institutional Investor Portal</h2>
                        <p class="text-[11px] text-slate-500 font-medium">Double-entry ledger status: <span class="text-emerald-600 font-bold">Synchronized</span></p>
                    </div>
                </div>

                <!-- Desktop User Profile & Actions -->
                <div class="flex items-center gap-4">
                    <!-- Account Currency Badge -->
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-800 text-xs font-bold border border-slate-200/80">
                        <span>
                            @if($userCurr === 'GBP') 🇬🇧 Account Currency: GBP (£)
                            @elseif($userCurr === 'CAD') 🇨🇦 Account Currency: CAD (C$)
                            @else 🇺🇸 Account Currency: USD ($) @endif
                        </span>
                    </div>

                    <!-- User Profile Quick Link -->
                    <a href="{{ route('user.profile') }}" class="flex items-center gap-3 p-1.5 pr-3 rounded-2xl bg-slate-50 hover:bg-slate-100 border border-slate-200/80 transition">
                        <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs shadow-xs">
                            {{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}
                        </div>
                        <span class="text-xs font-bold text-slate-800">{{ auth()->user()->first_name }}</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Dynamic Flash & Security Messages -->
        <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 mt-6 space-y-3">
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-semibold flex items-center justify-between shadow-sm">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-sm font-semibold flex items-center justify-between shadow-sm">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- PIN Requirement Alert Banner -->
            @if(!auth()->user()->hasTransactionPin())
                <div class="p-4 rounded-2xl bg-gradient-to-r from-indigo-900 via-indigo-950 to-slate-900 text-white shadow-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border border-indigo-700/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600/40 text-amber-400 font-bold flex items-center justify-center text-lg flex-shrink-0">
                            🛡️
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-white">Transaction PIN Setup Required</h4>
                            <p class="text-xs text-indigo-200">Set a 4-digit security PIN to confirm future investments and withdrawal requests.</p>
                        </div>
                    </div>
                    <a href="{{ route('user.profile') }}#transaction-pin-card" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md transition flex-shrink-0">
                        Set PIN Now →
                    </a>
                </div>
            @endif

            @if(auth()->user()->kyc_status !== 'approved')
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-950 text-sm font-medium flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 shadow-xs">
                    <div>
                        <span class="font-bold text-amber-900">Identity Verification Notice:</span> Your account KYC status is <span class="uppercase font-bold text-amber-800 px-2 py-0.5 rounded bg-amber-200/60">{{ auth()->user()->kyc_status }}</span>. Submit identity documents to unlock full withdrawal limits.
                    </div>
                    <a href="{{ route('user.kyc') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl transition flex-shrink-0">Verify Now</a>
                </div>
            @endif
        </div>

        <!-- Main Workspace Body -->
        <main class="flex-grow p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-8">
            @yield('content')
        </main>
    </div>

    <!-- Sleek & Modern Fixed Bottom Navigation Bar on Mobile -->
    <nav class="md:hidden fixed bottom-3 left-3 right-3 bg-slate-950/95 backdrop-blur-xl border border-slate-800/90 rounded-3xl flex justify-around items-center h-16 z-40 px-2 shadow-2xl">
        
        <a href="{{ route('user.dashboard') }}" 
           class="flex flex-col items-center gap-0.5 transition duration-200 {{ request()->routeIs('user.dashboard') ? 'text-indigo-400 font-bold bg-indigo-600/10 px-3 py-1.5 rounded-2xl border border-indigo-500/20 shadow-xs' : 'text-slate-400 hover:text-white px-2 py-1' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            <span class="text-[10px] tracking-tight">Home</span>
        </a>

        <a href="{{ route('user.investments') }}" 
           class="flex flex-col items-center gap-0.5 transition duration-200 {{ request()->routeIs('user.investments*') ? 'text-indigo-400 font-bold bg-indigo-600/10 px-3 py-1.5 rounded-2xl border border-indigo-500/20 shadow-xs' : 'text-slate-400 hover:text-white px-2 py-1' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            <span class="text-[10px] tracking-tight">Invest</span>
        </a>

        <a href="{{ route('user.wallet') }}" 
           class="flex flex-col items-center gap-0.5 transition duration-200 {{ request()->routeIs('user.wallet*') ? 'text-indigo-400 font-bold bg-indigo-600/10 px-3 py-1.5 rounded-2xl border border-indigo-500/20 shadow-xs' : 'text-slate-400 hover:text-white px-2 py-1' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            <span class="text-[10px] tracking-tight">Wallet</span>
        </a>

        <a href="{{ route('user.transactions') }}" 
           class="flex flex-col items-center gap-0.5 transition duration-200 {{ request()->routeIs('user.transactions*') ? 'text-indigo-400 font-bold bg-indigo-600/10 px-3 py-1.5 rounded-2xl border border-indigo-500/20 shadow-xs' : 'text-slate-400 hover:text-white px-2 py-1' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span class="text-[10px] tracking-tight">Ledger</span>
        </a>

        <a href="{{ route('user.profile') }}" 
           class="flex flex-col items-center gap-0.5 transition duration-200 {{ request()->routeIs('user.profile*') ? 'text-indigo-400 font-bold bg-indigo-600/10 px-3 py-1.5 rounded-2xl border border-indigo-500/20 shadow-xs' : 'text-slate-400 hover:text-white px-2 py-1' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            <span class="text-[10px] tracking-tight">Profile</span>
        </a>
    </nav>

    <!-- Floating Live Chat Widget (Investor Portal) -->
    <div x-data="{ 
            chatOpen: false, 
            unread: true,
            chatMessages: [
                { sender: 'support', text: 'Hello {{ auth()->user()->first_name }}! Welcome to Institutional Support. How can our desk assist your portfolio today?' }
            ], 
            newMessage: '',
            sendMessage(customText = null) {
                const text = customText || this.newMessage.trim();
                if(!text) return;
                this.chatMessages.push({ sender: 'user', text: text });
                if(!customText) this.newMessage = '';
                
                setTimeout(() => {
                    let reply = 'Thank you for contacting BlockHarvest Investor Support. A senior portfolio manager has been notified.';
                    const lower = text.toLowerCase();
                    if(lower.includes('deposit') || lower.includes('fund')) {
                        reply = 'Deposits are credited automatically to your available balance upon receipt of payment proof. Minimum deposit bounds depend on payment method.';
                    } else if(lower.includes('kyc') || lower.includes('verification')) {
                        reply = 'Your account status is currently {{ auth()->user()->kyc_status }}. Submissions are processed within 15 minutes by our compliance team.';
                    } else if(lower.includes('withdraw') || lower.includes('payout')) {
                        reply = 'Withdrawals are processed in your account currency ({{ $userCurr }}) after PIN authorization and security validation.';
                    } else if(lower.includes('plan') || lower.includes('yield') || lower.includes('roi')) {
                        reply = 'Yield accruals are processed automatically according to your active plan calculation cycle.';
                    }
                    this.chatMessages.push({ sender: 'support', text: reply });
                    this.$nextTick(() => {
                        const box = document.getElementById('investor-chat-scroll-area');
                        if(box) box.scrollTop = box.scrollHeight;
                    });
                }, 800);
            }
        }" 
        class="fixed bottom-22 right-4 sm:bottom-6 sm:right-6 z-50">
        
        <!-- Live Chat Modal / Window -->
        <div x-show="chatOpen" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             x-cloak 
             class="mb-3 w-80 sm:w-96 bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col h-[460px]">
            
            <!-- Chat Header -->
            <div class="p-4 bg-gradient-to-r from-indigo-600 via-indigo-700 to-slate-900 text-white flex items-center justify-between shadow-md">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-white/10 backdrop-blur border border-white/20 text-white font-bold flex items-center justify-center text-sm shadow-inner">
                            BH
                        </div>
                        <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-emerald-400 border-2 border-indigo-700"></span>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm leading-none text-white">Investor Priority Support</h4>
                        <span class="text-[10px] text-indigo-200 flex items-center gap-1 mt-1 font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Dedicated Desk Active
                        </span>
                    </div>
                </div>
                <button @click="chatOpen = false" class="text-white/70 hover:text-white p-1.5 rounded-xl hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Chat Messages Container -->
            <div id="investor-chat-scroll-area" class="flex-grow p-4 overflow-y-auto space-y-3 bg-slate-50 text-xs">
                <template x-for="(msg, index) in chatMessages" :key="index">
                    <div class="flex flex-col" :class="msg.sender === 'user' ? 'items-end' : 'items-start'">
                        <div class="max-w-[85%] px-3.5 py-2.5 rounded-2xl shadow-sm leading-relaxed"
                             :class="msg.sender === 'user' 
                                 ? 'bg-indigo-600 text-white rounded-br-none font-medium' 
                                 : 'bg-white text-slate-800 border border-slate-200 rounded-bl-none'">
                            <span x-text="msg.text"></span>
                        </div>
                        <span class="text-[9px] text-slate-400 mt-1 px-1" x-text="msg.sender === 'user' ? 'You' : 'Priority Desk'"></span>
                    </div>
                </template>
            </div>

            <!-- Quick Action Prompts -->
            <div class="px-3 py-2 bg-white border-t border-slate-100 flex gap-1.5 overflow-x-auto text-[10px] no-scrollbar">
                <button @click="sendMessage('Deposit status update')" class="px-2.5 py-1 rounded-full bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold whitespace-nowrap transition">
                    💳 Deposit Help
                </button>
                <button @click="sendMessage('Withdrawal processing')" class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold whitespace-nowrap transition">
                    ⚡ Withdrawals
                </button>
                <button @click="sendMessage('KYC verification status')" class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold whitespace-nowrap transition">
                    🛡️ KYC Status
                </button>
            </div>

            <!-- Chat Input Form -->
            <form @submit.prevent="sendMessage()" class="p-3 bg-white border-t border-slate-200 flex gap-2 items-center">
                <input type="text" 
                       x-model="newMessage" 
                       placeholder="Type your message here..." 
                       class="flex-grow px-3 py-2 rounded-xl bg-slate-100 border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition" />
                <button type="submit" 
                        class="p-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md transition flex items-center justify-center">
                    <svg class="w-4 h-4 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>

        <!-- Floating Chat Icon Toggle Button -->
        <button @click="chatOpen = !chatOpen; unread = false" 
                class="relative group flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-2xl hover:scale-105 active:scale-95 transition duration-300 ring-4 ring-indigo-600/20">
            <!-- Unread Badge indicator -->
            <span x-show="unread" class="absolute -top-1 -right-1 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white"></span>
            </span>
            
            <svg x-show="!chatOpen" class="w-7 h-7 transform group-hover:rotate-12 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            
            <svg x-show="chatOpen" x-cloak class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>

    <!-- ========================================================================= -->
    <!-- GLOBAL SUCCESS POPUP MODAL -->
    <!-- ========================================================================= -->
    <div x-show="successModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak 
         class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        
        <div @click.away="successModalOpen = false" 
             x-show="successModalOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-4"
             class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-6 text-center border border-emerald-100 relative overflow-hidden">
            
            <!-- Gradient Top Line -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600"></div>

            <!-- Success Icon Circle -->
            <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white font-black text-3xl flex items-center justify-center mx-auto shadow-xl shadow-emerald-500/30 ring-8 ring-emerald-50">
                ✓
            </div>

            <div class="space-y-2">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Success!</h3>
                <p class="text-xs sm:text-sm font-semibold text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    {{ session('success') }}
                </p>
            </div>

            <!-- Action Button -->
            <button @click="successModalOpen = false" 
                    class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-emerald-600/30 transition duration-200 transform active:scale-98">
                Continue
            </button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- GLOBAL ERROR & TRANSACTION PIN ERROR POPUP MODAL -->
    <!-- ========================================================================= -->
    <div x-show="errorModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak 
         class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        
        <div @click.away="errorModalOpen = false" 
             x-show="errorModalOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-4"
             class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-6 text-center border border-rose-100 relative overflow-hidden">
            
            <!-- Gradient Top Line -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-rose-500 via-amber-500 to-rose-600"></div>

            @php
                $isPinError = (session('error') && (str_contains(strtolower(session('error')), 'pin') || str_contains(strtolower(session('error')), 'security'))) ||
                              $errors->has('pin') || $errors->has('current_pin') || $errors->has('transaction_pin');
            @endphp

            <!-- Warning / Security Badge -->
            <div class="w-16 h-16 rounded-3xl {{ $isPinError ? 'bg-gradient-to-tr from-rose-600 to-amber-500 shadow-rose-500/30' : 'bg-gradient-to-tr from-rose-500 to-rose-700 shadow-rose-500/30' }} text-white font-black text-2xl flex items-center justify-center mx-auto shadow-xl ring-8 ring-rose-50">
                {{ $isPinError ? '🛡️' : '⚠️' }}
            </div>

            <div class="space-y-3">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">
                    {{ $isPinError ? 'Transaction Security PIN Error' : 'Action Failed' }}
                </h3>

                @if(session('error'))
                    <div class="text-xs sm:text-sm font-semibold text-rose-800 bg-rose-50 p-4 rounded-2xl border border-rose-200/80 leading-relaxed text-left">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="text-left bg-rose-50 p-4 rounded-2xl border border-rose-200/80 space-y-1 text-xs font-semibold text-rose-800">
                        <p class="font-bold text-rose-900 mb-1 border-b border-rose-200/60 pb-1">Validation Failure Details:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="space-y-2">
                @if($isPinError || (auth()->check() && !auth()->user()->hasTransactionPin()))
                    <a href="{{ route('user.profile') }}#transaction-pin-card" 
                       class="w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                        <span>⚙️ Manage Security PIN in Profile</span>
                    </a>
                @endif

                <button @click="errorModalOpen = false" 
                        class="w-full py-3.5 px-6 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider shadow-md transition duration-200 transform active:scale-98">
                    Dismiss & Try Again
                </button>
            </div>
        </div>
    </div>

</body>
</html>
