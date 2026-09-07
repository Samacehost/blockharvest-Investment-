@extends('layouts.user')

@section('title', 'Profile & Security - BlockHarvest')

@section('content')
<div class="space-y-8 max-w-3xl">
    <div>
        <h1 class="text-2xl font-black text-slate-900">Profile & Security Settings</h1>
        <p class="text-xs text-slate-500">Update account credentials, contact information, and your 4-Digit Transaction PIN.</p>
    </div>

    <!-- Personal & Account Information -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">First Name</label>
                    <input name="first_name" type="text" value="{{ $user->first_name }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-900 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Last Name</label>
                    <input name="last_name" type="text" value="{{ $user->last_name }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-900 text-sm outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address (Immutable)</label>
                <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-100 font-bold text-slate-500 text-sm outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Phone Number</label>
                <input name="phone" type="text" value="{{ $user->phone }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-900 text-sm outline-none">
            </div>

            <div class="pt-4 border-t border-slate-100 space-y-4">
                <h4 class="font-bold text-slate-900 text-sm uppercase tracking-wider">Change Password</h4>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Current Password</label>
                    <input name="current_password" type="password" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">New Password</label>
                        <input name="password" type="password" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Confirm New Password</label>
                        <input name="password_confirmation" type="password" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm outline-none">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm btn-primary shadow-lg">
                Save Profile Changes
            </button>
        </form>
    </div>

    <!-- Transaction Security PIN Setup / Update Card -->
    <div id="transaction-pin-card" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6" x-data="{ showPin: false }">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center text-lg">
                    🛡️
                </div>
                <div>
                    <h3 class="font-black text-slate-900 text-lg">4-Digit Transaction Security PIN</h3>
                    <p class="text-xs text-slate-500">Required to authorize capital investments and payout withdrawals.</p>
                </div>
            </div>
            <div>
                @if($user->hasTransactionPin())
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1">
                        ✓ PIN Active
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 flex items-center gap-1">
                        ⚠️ Not Configured
                    </span>
                @endif
            </div>
        </div>

        <form action="{{ route('user.profile.pin') }}" method="POST" class="space-y-4">
            @csrf

            @if($user->hasTransactionPin())
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Current 4-Digit PIN</label>
                    <input name="current_pin" type="password" maxlength="4" inputmode="numeric" placeholder="Enter current 4-digit PIN" required class="w-full px-4 py-3 rounded-xl border border-slate-300 font-mono text-sm tracking-widest outline-none focus:ring-2 focus:ring-indigo-600">
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        {{ $user->hasTransactionPin() ? 'New 4-Digit PIN' : 'Set 4-Digit PIN' }}
                    </label>
                    <div class="relative">
                        <input name="pin" 
                               :type="showPin ? 'text' : 'password'" 
                               maxlength="4" 
                               inputmode="numeric" 
                               placeholder="e.g. 7492" 
                               required 
                               class="w-full px-4 py-3 pr-10 rounded-xl border border-slate-300 font-mono text-sm tracking-widest outline-none focus:ring-2 focus:ring-indigo-600">
                        <button type="button" 
                                @click="showPin = !showPin" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-indigo-600 focus:outline-none transition">
                            <template x-if="!showPin">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </template>
                            <template x-if="showPin">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.04 10.04 0 013.122-.563c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-2.435 4.148M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 6L3 3"></path></svg>
                            </template>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Confirm PIN</label>
                    <div class="relative">
                        <input name="pin_confirmation" 
                               :type="showPin ? 'text' : 'password'" 
                               maxlength="4" 
                               inputmode="numeric" 
                               placeholder="Confirm 4-digit PIN" 
                               required 
                               class="w-full px-4 py-3 pr-10 rounded-xl border border-slate-300 font-mono text-sm tracking-widest outline-none focus:ring-2 focus:ring-indigo-600">
                    </div>
                </div>
            </div>

            <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-medium space-y-1">
                <p><strong>🔒 Security Policy:</strong> Easy-to-guess PINs (such as <code>1234</code>, <code>0000</code>, <code>1111</code>, <code>4321</code>) are prohibited.</p>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm bg-slate-900 hover:bg-slate-800 transition shadow-md">
                {{ $user->hasTransactionPin() ? 'Update 4-Digit PIN' : 'Save 4-Digit Transaction PIN' }}
            </button>
        </form>
    </div>

</div>
@endsection
