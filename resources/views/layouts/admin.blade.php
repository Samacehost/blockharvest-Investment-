<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin Control Panel - BlockHarvest')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        {!! \App\Services\DynamicSettingService::generateCssVariables() !!}
        body { font-family: var(--font-family); }
        .bg-primary { background-color: var(--primary-color); }
        .btn-primary { background-color: var(--primary-color); color: #ffffff; border-radius: var(--button-radius); }
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

    <!-- Fixed Desktop Sidebar Navigation (Independently Scrollable) -->
    <aside class="hidden md:flex flex-col w-64 h-screen max-h-screen sticky top-0 bg-slate-950 text-slate-300 border-r border-slate-800/80 flex-shrink-0 justify-between overflow-y-auto sidebar-scroll z-30 shadow-2xl">
        
        <div>
            <!-- Sidebar Logo Header -->
            <div class="h-20 flex items-center px-6 border-b border-slate-800/80 justify-between bg-slate-950/80 backdrop-blur">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-2xl bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white font-black text-lg flex items-center justify-center shadow-lg shadow-indigo-600/30">
                        A
                    </div>
                    <div class="flex flex-col">
                        <span class="text-base font-black text-white tracking-tight leading-none">Admin<span class="text-indigo-400">Desk</span></span>
                        <span class="text-[9px] text-slate-400 uppercase tracking-widest font-semibold mt-0.5">Super Admin Portal</span>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">SuperAdmin</span>
            </div>

            <!-- Navigation Links -->
            <nav class="p-3 space-y-1 text-xs font-semibold">
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Overview Dashboard</span>
                </a>

                <a href="{{ route('admin.users') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.users*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Users Desk</span>
                </a>

                <a href="{{ route('admin.kyc') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.kyc*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span>KYC Verifications</span>
                </a>

                <a href="{{ route('admin.deposits') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.deposits*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <span>Deposits Queue</span>
                </a>

                <a href="{{ route('admin.withdrawals') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.withdrawals*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Withdrawals Desk</span>
                </a>

                <a href="{{ route('admin.plans') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.plans*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>Investment Plans</span>
                </a>

                <a href="{{ route('admin.payment-methods.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.payment-methods*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Payment Methods</span>
                </a>

                <a href="{{ route('admin.cms') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.cms*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                    <span>CMS & Settings</span>
                </a>

                <a href="{{ route('admin.support') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.support*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Support Desk</span>
                </a>

                <a href="{{ route('admin.audit-logs') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.audit-logs*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Audit Logs</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-800/80 bg-slate-950/80">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white font-bold text-xs border border-slate-800 transition">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Exit Admin Portal</span>
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

    <!-- Mobile Navigation Drawer Panel -->
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
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-black text-lg flex items-center justify-center">A</div>
                    <span class="text-lg font-black text-white tracking-tight">Admin<span class="text-indigo-400">Desk</span></span>
                </div>
                <button @click="mobileDrawerOpen = false" class="p-2 rounded-xl text-slate-400 hover:text-white bg-slate-900 border border-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <nav class="space-y-1.5 text-xs font-semibold">
                <a href="{{ route('admin.dashboard') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">Overview Dashboard</a>
                <a href="{{ route('admin.users') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.users*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">Users Desk</a>
                <a href="{{ route('admin.kyc') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.kyc*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">KYC Verifications</a>
                <a href="{{ route('admin.deposits') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.deposits*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">Deposits Queue</a>
                <a href="{{ route('admin.withdrawals') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.withdrawals*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">Withdrawals Desk</a>
                <a href="{{ route('admin.plans') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.plans*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">Investment Plans</a>
                <a href="{{ route('admin.payment-methods.index') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.payment-methods*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">Payment Methods</a>
                <a href="{{ route('admin.cms') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.cms*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">CMS & Settings</a>
                <a href="{{ route('admin.support') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.support*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">Support Desk</a>
                <a href="{{ route('admin.audit-logs') }}" @click="mobileDrawerOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.audit-logs*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900' }}">Audit Logs</a>
            </nav>
        </div>

        <div class="pt-4 border-t border-slate-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 text-xs font-bold border border-slate-800">
                    <span>Exit Admin Portal</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Workspace Container (Light Mode Scheme - Independently Scrollable) -->
    <div class="flex-grow flex flex-col min-w-0 h-screen max-h-screen overflow-y-auto">
        
        <!-- Modern Sleek Header Bar -->
        <header class="h-20 bg-white/95 backdrop-blur border-b border-slate-200/80 px-4 sm:px-6 flex items-center justify-between shadow-xs sticky top-0 z-20 flex-shrink-0">
            
            <!-- Mobile Header Content -->
            <div class="flex md:hidden items-center justify-between w-full">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-black text-lg flex items-center justify-center shadow-md">
                        A
                    </div>
                    <span class="text-base font-black text-slate-900 tracking-tight">Admin<span class="text-indigo-600">Desk</span></span>
                </div>
                <button @click="mobileDrawerOpen = true" class="p-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>

            <!-- Desktop Header Content -->
            <div class="hidden md:flex items-center justify-between w-full">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-indigo-50 text-indigo-600 font-bold border border-indigo-100">
                        ⚡
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 leading-tight">@yield('title', 'Super-Admin Control Desk')</h2>
                        <p class="text-[11px] text-slate-500 font-medium">System Status: <span class="text-emerald-600 font-bold">100% Operational</span></p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('public.home') }}" target="_blank" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold border border-slate-200/80 transition flex items-center gap-2">
                        <span>View Live Website</span>
                        <span>↗</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Workspace Content -->
        <main class="flex-grow p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-8">
            @yield('content')
        </main>
    </div>

    <!-- Success Popup Modal -->
    <div x-show="successModalOpen" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div @click.away="successModalOpen = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-6 text-center border border-emerald-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600"></div>
            <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white font-black text-3xl flex items-center justify-center mx-auto shadow-xl shadow-emerald-500/30">✓</div>
            <div class="space-y-2">
                <h3 class="text-xl font-black text-slate-900">Success!</h3>
                <p class="text-xs sm:text-sm font-semibold text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">{{ session('success') }}</p>
            </div>
            <button @click="successModalOpen = false" class="w-full py-3.5 px-6 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg">Continue</button>
        </div>
    </div>

    <!-- Error Popup Modal -->
    <div x-show="errorModalOpen" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div @click.away="errorModalOpen = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-6 text-center border border-rose-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-rose-500 via-amber-500 to-rose-600"></div>
            <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-rose-500 to-rose-700 text-white font-black text-2xl flex items-center justify-center mx-auto shadow-xl">⚠️</div>
            <div class="space-y-3">
                <h3 class="text-xl font-black text-slate-900">Action Notice</h3>
                @if(session('error'))<div class="text-xs sm:text-sm font-semibold text-rose-800 bg-rose-50 p-4 rounded-2xl border border-rose-200/80 text-left">{{ session('error') }}</div>@endif
                @if($errors->any())
                    <div class="text-left bg-rose-50 p-4 rounded-2xl border border-rose-200/80 space-y-1 text-xs font-semibold text-rose-800">
                        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
            </div>
            <button @click="errorModalOpen = false" class="w-full py-3.5 px-6 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider shadow-md">Dismiss</button>
        </div>
    </div>

</body>
</html>
