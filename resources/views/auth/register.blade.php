@extends('layouts.public')

@section('title', 'Register Investor Account - BlockHarvest')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl shadow-xl border border-slate-200">
        <div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-black text-2xl flex items-center justify-center mx-auto shadow-lg shadow-indigo-200">
                B
            </div>
            <h2 class="mt-6 text-center text-3xl font-black text-slate-900 tracking-tight">
                Create Investor Account
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600">
                Start harvesting institutional yields with full transparency
            </p>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form class="mt-6 space-y-4" action="{{ route('register') }}" method="POST">
            @csrf
            @if(!empty($refCode))
                <input type="hidden" name="ref" value="{{ $refCode }}">
                <div class="p-3 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-800 text-xs font-semibold flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Referred by Code: <strong class="font-mono text-indigo-900">{{ $refCode }}</strong>
                    </span>
                    <span class="bg-indigo-200 text-indigo-900 text-[10px] uppercase font-bold px-2 py-0.5 rounded-full">5% Yield Bonus</span>
                </div>
            @endif
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">First Name</label>
                    <input name="first_name" type="text" required value="{{ old('first_name') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none text-slate-900 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Last Name</label>
                    <input name="last_name" type="text" required value="{{ old('last_name') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none text-slate-900 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                <input name="email" type="email" required value="{{ old('email') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none text-slate-900 text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Phone Number</label>
                    <input name="phone" type="text" value="{{ old('phone') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none text-slate-900 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Preferred Account Currency</label>
                    @php $preselected = session('selected_currency', 'USD'); @endphp
                    <select name="preferred_currency" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 font-bold text-slate-900 text-sm outline-none">
                        <option value="USD" {{ old('preferred_currency', $preselected) === 'USD' ? 'selected' : '' }}>🇺🇸 USD — US Dollar ($)</option>
                        <option value="GBP" {{ old('preferred_currency', $preselected) === 'GBP' ? 'selected' : '' }}>🇬🇧 GBP — British Pound (£)</option>
                        <option value="CAD" {{ old('preferred_currency', $preselected) === 'CAD' ? 'selected' : '' }}>🇨🇦 CAD — Canadian Dollar (C$)</option>
                    </select>
                </div>
            </div>

            <!-- Password Fields with Eye Toggle -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-data="{ showPass: false, showConfirmPass: false }">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                    <div class="relative">
                        <input name="password" 
                               :type="showPass ? 'text' : 'password'" 
                               required 
                               class="w-full px-4 py-2.5 pr-10 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none text-slate-900 text-sm">
                        <button type="button" 
                                @click="showPass = !showPass" 
                                aria-label="Toggle password visibility"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-indigo-600 focus:outline-none transition rounded-lg hover:bg-slate-100">
                            <template x-if="!showPass">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </template>
                            <template x-if="showPass">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.04 10.04 0 013.122-.563c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-2.435 4.148M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 6L3 3"></path></svg>
                            </template>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Confirm Password</label>
                    <div class="relative">
                        <input name="password_confirmation" 
                               :type="showConfirmPass ? 'text' : 'password'" 
                               required 
                               class="w-full px-4 py-2.5 pr-10 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none text-slate-900 text-sm">
                        <button type="button" 
                                @click="showConfirmPass = !showConfirmPass" 
                                aria-label="Toggle confirm password visibility"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-indigo-600 focus:outline-none transition rounded-lg hover:bg-slate-100">
                            <template x-if="!showConfirmPass">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </template>
                            <template x-if="showConfirmPass">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.04 10.04 0 013.122-.563c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-2.435 4.148M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 6L3 3"></path></svg>
                            </template>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <label class="flex items-start gap-2 cursor-pointer text-xs text-slate-600">
                    <input type="checkbox" name="terms" required class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span>I accept the <a href="{{ route('public.terms') }}" target="_blank" class="text-indigo-600 font-bold hover:underline">Terms & Conditions</a> and acknowledge the <a href="{{ route('public.risk-disclosure') }}" target="_blank" class="text-indigo-600 font-bold hover:underline">Risk Disclosure</a>.</span>
                </label>
            </div>

            <div>
                <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm btn-primary shadow-lg shadow-indigo-200 transition mt-2">
                    Create Investor Account
                </button>
            </div>
        </form>

        <div class="text-center pt-4 border-t border-slate-100 text-xs text-slate-600">
            Already have an account? <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:underline">Sign In</a>
        </div>
    </div>
</div>
@endsection
