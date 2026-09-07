@extends('layouts.public')

@section('title', 'Sign In - BlockHarvest')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl shadow-xl border border-slate-200">
        <div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-black text-2xl flex items-center justify-center mx-auto shadow-lg shadow-indigo-200">
                B
            </div>
            <h2 class="mt-6 text-center text-3xl font-black text-slate-900 tracking-tight">
                Welcome Back
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600">
                Sign in to manage your investment portfolio
            </p>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none text-slate-900 text-sm">
                </div>
                <div x-data="{ showPassword: false }">
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <input id="password" 
                               name="password" 
                               :type="showPassword ? 'text' : 'password'" 
                               autocomplete="current-password" 
                               required 
                               class="w-full px-4 py-3 pr-11 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none text-slate-900 text-sm">
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                aria-label="Toggle password visibility"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-slate-400 hover:text-indigo-600 focus:outline-none transition rounded-lg hover:bg-slate-100">
                            <template x-if="!showPassword">
                                <!-- Eye Open Icon -->
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </template>
                            <template x-if="showPassword">
                                <!-- Eye Closed / Slash Icon -->
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.04 10.04 0 013.122-.563c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-2.435 4.148M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 6L3 3"></path></svg>
                            </template>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span>Remember me</span>
                </label>
                <a href="#" class="font-bold text-indigo-600 hover:underline">Forgot password?</a>
            </div>

            <div>
                <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm btn-primary shadow-lg shadow-indigo-200 transition">
                    Sign In to Account
                </button>
            </div>
        </form>

        <div class="text-center pt-4 border-t border-slate-100 text-xs text-slate-600">
            Don't have an account? <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:underline">Create Investor Account</a>
        </div>
    </div>
</div>
@endsection
