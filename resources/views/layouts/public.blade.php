<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BlockHarvest - Institutional Asset Management')</title>
    <meta name="description" content="BlockHarvest offers database-driven, high-yield asset portfolio harvesting and institutional investment plans.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        {!! \App\Services\DynamicSettingService::generateCssVariables() !!}
        body { font-family: var(--font-family); }
        .bg-primary { background-color: var(--primary-color); }
        .text-primary { color: var(--primary-color); }
        .border-primary { border-color: var(--primary-color); }
        .btn-primary { background-color: var(--primary-color); color: #ffffff; border-radius: var(--button-radius); }
        .btn-primary:hover { opacity: 0.92; }
    </style>
</head>
<body class="h-full flex flex-col font-sans text-slate-800 antialiased selection:bg-indigo-500 selection:text-white"
      x-data="{
          firstVisitModalOpen: {{ !auth()->check() && !request()->cookie('guest_currency') ? 'true' : 'false' }},
          selectedCurrency: '{{ \App\Services\CurrencyService::getCurrentCurrency() }}',
          successModalOpen: {{ session('success') ? 'true' : 'false' }},
          errorModalOpen: {{ session('error') || $errors->any() ? 'true' : 'false' }},
          setCurrency(code) {
              fetch('{{ route('public.currency.set') }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  },
                  body: JSON.stringify({ currency: code })
              }).then(() => {
                  window.location.reload();
              });
          }
      }">

    <!-- Announcement Bar -->
    <div class="bg-slate-900 text-slate-200 text-xs py-2 px-4 text-center font-medium flex items-center justify-center gap-2">
        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
        <span>Platform Operational • Daily Compound Yield Engine Active</span>
    </div>

    <!-- First-Visit Currency Selection Modal -->
    <div x-show="firstVisitModalOpen" x-cloak class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-6 text-center">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-black text-2xl flex items-center justify-center mx-auto shadow-lg shadow-indigo-200">
                B
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900">Select Your Preferred Currency</h3>
                <p class="text-xs text-slate-500 mt-1">Choose your preferred currency to customize strategy returns and deposit options.</p>
            </div>

            <div class="space-y-3 text-left">
                <button @click="setCurrency('USD'); firstVisitModalOpen = false" class="w-full p-4 rounded-2xl border-2 border-slate-200 hover:border-indigo-600 hover:bg-indigo-50/50 flex items-center justify-between transition">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🇺🇸</span>
                        <div>
                            <p class="font-bold text-slate-900 text-sm">United States</p>
                            <p class="text-xs text-slate-500">US Dollar ($ USD)</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-indigo-600 bg-indigo-100 px-2.5 py-1 rounded-lg">Base Currency</span>
                </button>

                <button @click="setCurrency('GBP'); firstVisitModalOpen = false" class="w-full p-4 rounded-2xl border-2 border-slate-200 hover:border-indigo-600 hover:bg-indigo-50/50 flex items-center justify-between transition">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🇬🇧</span>
                        <div>
                            <p class="font-bold text-slate-900 text-sm">United Kingdom</p>
                            <p class="text-xs text-slate-500">British Pound (£ GBP)</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-slate-600">Select £</span>
                </button>

                <button @click="setCurrency('CAD'); firstVisitModalOpen = false" class="w-full p-4 rounded-2xl border-2 border-slate-200 hover:border-indigo-600 hover:bg-indigo-50/50 flex items-center justify-between transition">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🇨🇦</span>
                        <div>
                            <p class="font-bold text-slate-900 text-sm">Canada</p>
                            <p class="text-xs text-slate-500">Canadian Dollar (C$ CAD)</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-slate-600">Select C$</span>
                </button>
            </div>

            <p class="text-[11px] text-slate-400">You can change your guest display currency anytime from the navigation header.</p>
        </div>
    </div>

    <!-- Navigation Header -->
    <header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('public.home') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-black text-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                        B
                    </div>
                    <span class="text-2xl font-black tracking-tight text-slate-900">
                        Block<span class="text-indigo-600">Harvest</span>
                    </span>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden lg:flex items-center space-x-7 text-xs font-bold uppercase tracking-wider text-slate-600">
                    <a href="{{ route('public.home') }}" class="hover:text-indigo-600 transition">Home</a>
                    <a href="{{ route('public.about') }}" class="hover:text-indigo-600 transition">About Us</a>
                    <a href="{{ route('public.plans') }}" class="hover:text-indigo-600 transition">Plans</a>
                    <a href="{{ route('public.calculator') }}" class="hover:text-indigo-600 transition">Calculator</a>
                    <a href="{{ route('public.contact') }}" class="hover:text-indigo-600 transition">Contact</a>
                </nav>

                <!-- Auth & Currency Selector -->
                <div class="hidden md:flex items-center space-x-4">
                    @php $curr = \App\Services\CurrencyService::getCurrentCurrency(); @endphp
                    <!-- Currency Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold border border-slate-200 transition">
                            <span>
                                @if($curr === 'USD') 🇺🇸 USD ($)
                                @elseif($curr === 'GBP') 🇬🇧 GBP (£)
                                @elseif($curr === 'CAD') 🇨🇦 CAD (C$)
                                @else 🇺🇸 USD ($) @endif
                            </span>
                            <span class="text-slate-400 text-[10px]">▼</span>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-44 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50 text-xs space-y-1">
                            <button @click="setCurrency('USD'); open = false" class="w-full text-left px-4 py-2 hover:bg-indigo-50 font-bold flex items-center justify-between">
                                <span>🇺🇸 USD ($)</span>
                                @if($curr === 'USD') <span class="text-indigo-600 font-bold">✓</span> @endif
                            </button>
                            <button @click="setCurrency('GBP'); open = false" class="w-full text-left px-4 py-2 hover:bg-indigo-50 font-bold flex items-center justify-between">
                                <span>🇬🇧 GBP (£)</span>
                                @if($curr === 'GBP') <span class="text-indigo-600 font-bold">✓</span> @endif
                            </button>
                            <button @click="setCurrency('CAD'); open = false" class="w-full text-left px-4 py-2 hover:bg-indigo-50 font-bold flex items-center justify-between">
                                <span>🇨🇦 CAD (C$)</span>
                                @if($curr === 'CAD') <span class="text-indigo-600 font-bold">✓</span> @endif
                            </button>
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 transition">Admin Portal</a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white btn-primary shadow-md transition">Investor Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold text-slate-700 hover:text-indigo-600 px-3 py-2 transition">Sign In</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white btn-primary shadow-md transition">Create Account</a>
                    @endauth
                </div>

                <!-- Mobile Hamburger Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-white border-b border-slate-200 px-4 pt-2 pb-6 space-y-3 text-sm font-semibold">
            <a href="{{ route('public.home') }}" class="block px-3 py-2 rounded-md hover:bg-slate-50">Home</a>
            <a href="{{ route('public.about') }}" class="block px-3 py-2 rounded-md hover:bg-slate-50">About Us</a>
            <a href="{{ route('public.plans') }}" class="block px-3 py-2 rounded-md hover:bg-slate-50">Investment Plans</a>
            <a href="{{ route('public.calculator') }}" class="block px-3 py-2 rounded-md hover:bg-slate-50">ROI Calculator</a>
            <a href="{{ route('public.contact') }}" class="block px-3 py-2 rounded-md hover:bg-slate-50">Contact</a>
            
            <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                <div class="flex items-center justify-between px-3 py-2 bg-slate-50 rounded-xl">
                    <span class="text-xs text-slate-500 font-bold">Currency:</span>
                    <div class="flex gap-1 text-xs font-bold">
                        <button @click="setCurrency('USD')" class="px-2 py-1 rounded {{ $curr === 'USD' ? 'bg-indigo-600 text-white' : 'text-slate-700' }}">USD</button>
                        <button @click="setCurrency('GBP')" class="px-2 py-1 rounded {{ $curr === 'GBP' ? 'bg-indigo-600 text-white' : 'text-slate-700' }}">GBP</button>
                        <button @click="setCurrency('CAD')" class="px-2 py-1 rounded {{ $curr === 'CAD' ? 'bg-indigo-600 text-white' : 'text-slate-700' }}">CAD</button>
                    </div>
                </div>
                @auth
                    <a href="{{ route('user.dashboard') }}" class="w-full text-center px-4 py-3 rounded-xl text-xs font-bold text-white btn-primary">Investor Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 border border-slate-300">Sign In</a>
                    <a href="{{ route('register') }}" class="w-full text-center px-4 py-2.5 rounded-xl text-xs font-bold text-white btn-primary">Create Account</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    @unless(request()->routeIs('login', 'register') || View::hasSection('hide_footer'))
    <footer class="bg-slate-950 text-slate-400 border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-black text-lg flex items-center justify-center">B</div>
                        <span class="text-xl font-bold text-white">BlockHarvest</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-400">
                        Institutional-grade asset harvesting platform providing secure, transparent, database-backed portfolio management.
                    </p>
                    <div class="flex items-center gap-2 text-xs text-slate-300 font-bold">
                        <span>Supported Currencies:</span>
                        <span class="px-2 py-0.5 bg-slate-900 border border-slate-800 rounded">🇺🇸 USD</span>
                        <span class="px-2 py-0.5 bg-slate-900 border border-slate-800 rounded">🇬🇧 GBP</span>
                        <span class="px-2 py-0.5 bg-slate-900 border border-slate-800 rounded">🇨🇦 CAD</span>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-4">Navigation</h4>
                    <ul class="space-y-2 text-xs font-medium">
                        <li><a href="{{ route('public.home') }}" class="hover:text-white transition">Home</a></li>
                        <li><a href="{{ route('public.about') }}" class="hover:text-white transition">About Us</a></li>
                        <li><a href="{{ route('public.plans') }}" class="hover:text-white transition">Investment Plans</a></li>
                        <li><a href="{{ route('public.calculator') }}" class="hover:text-white transition">ROI Calculator</a></li>
                        <li><a href="{{ route('public.how-it-works') }}" class="hover:text-white transition">How It Works</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-4">Legal & Compliance</h4>
                    <ul class="space-y-2 text-xs font-medium">
                        <li><a href="{{ route('public.privacy-policy') }}" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="{{ route('public.terms') }}" class="hover:text-white transition">Terms & Conditions</a></li>
                        <li><a href="{{ route('public.risk-disclosure') }}" class="hover:text-white transition">Risk Disclosure</a></li>
                        <li><a href="{{ route('public.aml-kyc-policy') }}" class="hover:text-white transition">AML / KYC Policy</a></li>
                        <li><a href="{{ route('public.cookie-policy') }}" class="hover:text-white transition">Cookie Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-4">Contact & Support</h4>
                    <ul class="space-y-2 text-xs">
                        <li class="flex items-center gap-2"><span>Email:</span> <span class="text-slate-200 font-medium">{{ \App\Services\DynamicSettingService::get('support_email', 'support@blockharvest.top') }}</span></li>
                        <li class="flex items-center gap-2"><span>Phone:</span> <span class="text-slate-200 font-medium">{{ \App\Services\DynamicSettingService::get('phone_number', '+1 (888) 492-9102') }}</span></li>
                        <li class="mt-3 text-xs leading-relaxed text-slate-500">
                            {{ \App\Services\DynamicSettingService::get('office_address', '100 Wall Street, New York, NY') }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-900 text-xs text-center space-y-3 text-slate-500">
                <p>{{ \App\Services\DynamicSettingService::get('risk_warning_text') }}</p>
                <p>{{ \App\Services\DynamicSettingService::get('footer_copyright') }}</p>
            </div>
        </div>
    </footer>
    @endunless

    <!-- Cookie Consent Banner -->
    <div x-data="{ consentGiven: localStorage.getItem('cookieConsent') }" x-show="!consentGiven" class="fixed bottom-4 left-4 max-w-sm bg-white p-5 rounded-2xl shadow-2xl border border-slate-200 z-50">
        <h5 class="font-bold text-slate-900 text-sm">Cookie & Privacy Notice</h5>
        <p class="text-xs text-slate-600 mt-1">We use cookies to secure session logs and preserve currency choices.</p>
        <div class="mt-4 flex gap-2">
            <button @click="localStorage.setItem('cookieConsent', 'true'); consentGiven = true" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold w-full">Accept All</button>
        </div>
    </div>

    <!-- Floating Live Chat Widget -->
    <div x-data="{ 
            chatOpen: false, 
            unread: true,
            chatMessages: [
                { sender: 'support', text: 'Hello! Welcome to BlockHarvest Live Support. How can our team assist your investment account today?' }
            ], 
            newMessage: '',
            sendMessage(customText = null) {
                const text = customText || this.newMessage.trim();
                if(!text) return;
                this.chatMessages.push({ sender: 'user', text: text });
                if(!customText) this.newMessage = '';
                
                setTimeout(() => {
                    let reply = 'Thank you for reaching out. An institutional account manager will reply shortly.';
                    const lower = text.toLowerCase();
                    if(lower.includes('deposit') || lower.includes('fund')) {
                        reply = 'Deposits are supported in USD ($), GBP (£), and CAD (C$). Funds are reconciled automatically using double-entry ledger validation.';
                    } else if(lower.includes('kyc') || lower.includes('verification')) {
                        reply = 'KYC verification requires a valid Passport or National ID. Verification takes under 15 minutes in your account portal.';
                    } else if(lower.includes('withdraw')) {
                        reply = 'Withdrawal requests are processed promptly in your chosen target currency with strict cryptographic security checks.';
                    } else if(lower.includes('plan') || lower.includes('rate') || lower.includes('yield')) {
                        reply = 'Our yield strategies offer up to 4.2% daily ROI depending on selected asset allocation tier. Check the Plans page for details.';
                    }
                    this.chatMessages.push({ sender: 'support', text: reply });
                    this.$nextTick(() => {
                        const box = document.getElementById('chat-scroll-area');
                        if(box) box.scrollTop = box.scrollHeight;
                    });
                }, 800);
            }
        }" 
        class="fixed bottom-6 right-6 z-50">
        
        <!-- Live Chat Modal / Window -->
        <div x-show="chatOpen" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             x-cloak 
             class="mb-4 w-80 sm:w-96 bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col h-[460px]">
            
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
                        <h4 class="font-bold text-sm leading-none text-white">BlockHarvest Live Chat</h4>
                        <span class="text-[10px] text-indigo-200 flex items-center gap-1 mt-1 font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Desk Active 24/7
                        </span>
                    </div>
                </div>
                <button @click="chatOpen = false" class="text-white/70 hover:text-white p-1.5 rounded-xl hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Chat Messages Container -->
            <div id="chat-scroll-area" class="flex-grow p-4 overflow-y-auto space-y-3 bg-slate-50 text-xs">
                <template x-for="(msg, index) in chatMessages" :key="index">
                    <div class="flex flex-col" :class="msg.sender === 'user' ? 'items-end' : 'items-start'">
                        <div class="max-w-[85%] px-3.5 py-2.5 rounded-2xl shadow-sm leading-relaxed"
                             :class="msg.sender === 'user' 
                                 ? 'bg-indigo-600 text-white rounded-br-none font-medium' 
                                 : 'bg-white text-slate-800 border border-slate-200 rounded-bl-none'">
                            <span x-text="msg.text"></span>
                        </div>
                        <span class="text-[9px] text-slate-400 mt-1 px-1" x-text="msg.sender === 'user' ? 'You' : 'Support Desk'"></span>
                    </div>
                </template>
            </div>

            <!-- Quick Action Prompts -->
            <div class="px-3 py-2 bg-white border-t border-slate-100 flex gap-1.5 overflow-x-auto text-[10px] no-scrollbar">
                <button @click="sendMessage('How do I make a deposit?')" class="px-2.5 py-1 rounded-full bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold whitespace-nowrap transition">
                    💳 Deposit Info
                </button>
                <button @click="sendMessage('What are the KYC requirements?')" class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold whitespace-nowrap transition">
                    🛡️ KYC Guidelines
                </button>
                <button @click="sendMessage('How fast are withdrawals?')" class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold whitespace-nowrap transition">
                    ⚡ Withdrawals
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
    <!-- LIVE INVESTOR ACTIVITY TOAST NOTIFICATIONS (BOTTOM LEFT) -->
    <!-- ========================================================================= -->
    @if(\App\Services\DynamicSettingService::get('enable_live_activity_toast', '1') == '1')
    <div x-data="{
            visible: false,
            dismissed: false,
            currentActivity: null,
            firstNames: [
                'Alexander', 'Sophia', 'Michael', 'Emma', 'David', 'Olivia', 'Marcus', 'Isabella', 
                'Liam', 'Charlotte', 'Ethan', 'Ava', 'Lucas', 'Amelia', 'Benjamin', 'Mia', 
                'Noah', 'Harper', 'James', 'Evelyn', 'Mateo', 'Abigail', 'Henry', 'Emily', 
                'Sebastian', 'Elizabeth', 'Jack', 'Mila', 'William', 'Ella', 'Oliver', 'Avery', 
                'Samuel', 'Sofia', 'Daniel', 'Camila', 'Matthew', 'Aria', 'Joseph', 'Scarlett', 
                'Jackson', 'Victoria', 'Levi', 'Madison', 'Anthony', 'Luna', 'Julian', 'Grace', 
                'John', 'Chloe', 'Gabriel', 'Penelope', 'Carter', 'Layla', 'Owen', 'Riley', 
                'Wyatt', 'Zoey', 'Grayson', 'Nora', 'Dylan', 'Lily', 'Luke', 'Eleanor', 
                'Isaac', 'Lillian', 'Jayden', 'Addison', 'Theodore', 'Aubrey', 'Ellie', 'Leo', 
                'Stella', 'Christopher', 'Natalie', 'Jaxon', 'Zoe', 'Maverick', 'Leah', 'Josiah', 
                'Hazel', 'Andrew', 'Violet', 'Thomas', 'Aurora', 'Joshua', 'Savannah', 'Ezra', 
                'Audrey', 'Hudson', 'Brooklyn', 'Charles', 'Bella', 'Caleb', 'Claire', 'Isaiah', 
                'Skylar', 'Ryan', 'Lucy', 'Nathan', 'Paisley'
            ],
            lastNames: [
                'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 
                'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 
                'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin', 'Lee', 'Perez', 'Thompson', 
                'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson', 'Walker', 
                'Young', 'Allen', 'King', 'Wright', 'Scott', 'Torres', 'Nguyen', 'Hill', 
                'Flores', 'Green', 'Adams', 'Nelson', 'Baker', 'Hall', 'Rivera', 'Campbell', 
                'Mitchell', 'Carter', 'Roberts', 'Gomez', 'Phillips', 'Evans', 'Turner', 'Diaz', 
                'Parker', 'Cruz', 'Edwards', 'Collins', 'Reyes', 'Stewart', 'Morris', 'Morales', 
                'Murphy', 'Cook', 'Rogers', 'Gutierrez', 'Ortiz', 'Morgan', 'Cooper', 'Peterson', 
                'Bailey', 'Reed', 'Kelly', 'Howard', 'Ramos', 'Kim', 'Cox', 'Ward', 
                'Richardson', 'Watson', 'Brooks', 'Chavez', 'Wood', 'Bennett', 'Gray', 'Mendoza', 
                'Ruiz', 'Hughes', 'Price', 'Alvarez', 'Castillo', 'Sanders', 'Patel', 'Myers', 
                'Long', 'Ross', 'Foster', 'Jimenez', 'Powell', 'Jenkins', 'Perry'
            ],
            timeAgoOptions: [
                '3 seconds ago', '12 seconds ago', '35 seconds ago', '48 seconds ago',
                '2 mins ago', '5 mins ago', '18 mins ago', '35 mins ago', '50 mins ago', '60 mins ago',
                '1 hr ago', '3 hrs ago', '7 hrs ago', '14 hrs ago', '22 hrs ago',
                '1 day ago', '2 days ago'
            ],
            currencies: [
                { prefix: '$' },
                { prefix: 'Cad ' },
                { prefix: 'C$' },
                { prefix: '£' }
            ],
            generateActivity() {
                const fn = this.firstNames[Math.floor(Math.random() * this.firstNames.length)];
                const ln = this.lastNames[Math.floor(Math.random() * this.lastNames.length)];
                const timeAgo = this.timeAgoOptions[Math.floor(Math.random() * this.timeAgoOptions.length)];
                const types = ['deposit', 'withdrawal', 'investment'];
                const type = types[Math.floor(Math.random() * types.length)];
                const curr = this.currencies[Math.floor(Math.random() * this.currencies.length)];
                
                let rawAmt = 0;
                if(type === 'investment') {
                    const amts = [1000, 2500, 5000, 7000, 10000, 15000, 25000, 50000];
                    rawAmt = amts[Math.floor(Math.random() * amts.length)];
                } else if(type === 'withdrawal') {
                    const amts = [500, 1200, 2400, 4800, 6000, 9500, 14000];
                    rawAmt = amts[Math.floor(Math.random() * amts.length)];
                } else {
                    const amts = [800, 1500, 3000, 5000, 8500, 12000, 20000];
                    rawAmt = amts[Math.floor(Math.random() * amts.length)];
                }
                
                const formattedAmt = rawAmt.toLocaleString();
                
                let title = '';
                let icon = '';
                let badgeStyle = '';
                
                if(type === 'deposit') {
                    title = `Just Deposited ${curr.prefix}${formattedAmt}`;
                    icon = '💳';
                    badgeStyle = 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30';
                } else if(type === 'withdrawal') {
                    title = `Just Withdrew ${curr.prefix}${formattedAmt}`;
                    icon = '⚡';
                    badgeStyle = 'bg-amber-500/20 text-amber-400 border-amber-500/30';
                } else {
                    title = `Just Invested ${curr.prefix}${formattedAmt}`;
                    icon = '💎';
                    badgeStyle = 'bg-indigo-500/20 text-indigo-400 border-indigo-500/30';
                }
                
                return {
                    name: `${fn} ${ln}`,
                    title: title,
                    timeAgo: timeAgo,
                    icon: icon,
                    badgeStyle: badgeStyle,
                    initials: `${fn.charAt(0)}${ln.charAt(0)}`
                };
            },
            init() {
                setTimeout(() => {
                    this.showNext();
                }, 1200);
            },
            showNext() {
                if(this.dismissed) return;
                this.currentActivity = this.generateActivity();
                this.visible = true;
                
                setTimeout(() => {
                    this.visible = false;
                    if(!this.dismissed) {
                        const nextDelay = Math.floor(Math.random() * 1000) + 1000;
                        setTimeout(() => {
                            this.showNext();
                        }, nextDelay);
                    }
                }, 4500);
            }
        }" 
        class="fixed bottom-6 left-6 z-50">
        
        <div x-show="visible && !dismissed"
             x-transition:enter="transition ease-out duration-500 transform"
             x-transition:enter-start="opacity-0 -translate-x-12 scale-90"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-400 transform"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-x-12 scale-90"
             x-cloak
             class="max-w-xs sm:max-w-sm bg-slate-950/95 text-white backdrop-blur-xl border border-slate-800/90 p-3.5 sm:p-4 rounded-3xl shadow-2xl flex items-center gap-3.5 ring-1 ring-white/10 group hover:border-slate-700 transition">
            
            <!-- Avatar Initials & Type Icon -->
            <div class="relative flex-shrink-0">
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-700 to-slate-900 text-white font-black text-xs sm:text-sm flex items-center justify-center border border-indigo-400/30 shadow-md">
                    <span x-text="currentActivity?.initials"></span>
                </div>
                <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-slate-900 border border-slate-700 text-[10px] flex items-center justify-center shadow" x-text="currentActivity?.icon"></span>
            </div>

            <!-- Notification Details -->
            <div class="min-w-0 flex-grow pr-1">
                <div class="flex items-center gap-1.5">
                    <h5 class="text-xs font-bold text-white truncate" x-text="currentActivity?.name"></h5>
                    <span class="text-emerald-400 text-[10px] font-bold" title="Verified Investor">✓</span>
                </div>
                <p class="text-xs font-black text-indigo-300 mt-0.5" x-text="currentActivity?.title"></p>
                <span class="text-[10px] text-slate-400 font-medium flex items-center gap-1 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span x-text="currentActivity?.timeAgo"></span>
                </span>
            </div>

            <!-- Close / Dismiss Button -->
            <button @click="dismissed = true; visible = false" 
                    title="Dismiss notifications"
                    class="text-slate-500 hover:text-white p-1.5 rounded-xl hover:bg-slate-800 transition flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- ========================================================================= -->
    <!-- PUBLIC GLOBAL SUCCESS POPUP MODAL -->
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
            
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600"></div>

            <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white font-black text-3xl flex items-center justify-center mx-auto shadow-xl shadow-emerald-500/30 ring-8 ring-emerald-50">
                ✓
            </div>

            <div class="space-y-2">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Success!</h3>
                <p class="text-xs sm:text-sm font-semibold text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    {{ session('success') }}
                </p>
            </div>

            <button @click="successModalOpen = false" 
                    class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-emerald-600/30 transition duration-200 transform active:scale-98">
                Continue
            </button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- PUBLIC GLOBAL ERROR POPUP MODAL -->
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
            
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-rose-500 via-amber-500 to-rose-600"></div>

            <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-rose-500 to-rose-700 text-white font-black text-2xl flex items-center justify-center mx-auto shadow-xl shadow-rose-500/30 ring-8 ring-rose-50">
                ⚠️
            </div>

            <div class="space-y-3">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Notice</h3>

                @if(session('error'))
                    <div class="text-xs sm:text-sm font-semibold text-rose-800 bg-rose-50 p-4 rounded-2xl border border-rose-200/80 leading-relaxed text-left">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="text-left bg-rose-50 p-4 rounded-2xl border border-rose-200/80 space-y-1 text-xs font-semibold text-rose-800">
                        <p class="font-bold text-rose-900 mb-1 border-b border-rose-200/60 pb-1">Validation Errors:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <button @click="errorModalOpen = false" 
                    class="w-full py-3.5 px-6 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider shadow-md transition duration-200 transform active:scale-98">
                Dismiss & Try Again
            </button>
        </div>
    </div>

</body>
</html>
