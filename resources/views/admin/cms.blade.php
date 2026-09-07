@extends('layouts.admin')

@section('title', 'Branding & Dynamic Settings')

@section('content')
<div class="space-y-8 max-w-4xl">
    <div>
        <h1 class="text-2xl font-black text-slate-900">Dynamic CMS & Theme Customization</h1>
        <p class="text-xs text-slate-500">Manage site branding tokens, dynamic text content, and toast notification toggles.</p>
    </div>

    <!-- Dynamic Theme Color Settings -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-6">
        <h3 class="font-black text-slate-900 text-base border-b border-slate-100 pb-3">Theme Colors & Styling Tokens</h3>
        <form action="{{ route('admin.cms.branding') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Primary Color</label>
                    <div class="flex items-center gap-2">
                        <input name="primary_color" type="color" value="{{ $branding->primary_color }}" class="w-10 h-10 rounded border-0 cursor-pointer">
                        <input type="text" value="{{ $branding->primary_color }}" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Secondary Color</label>
                    <div class="flex items-center gap-2">
                        <input name="secondary_color" type="color" value="{{ $branding->secondary_color }}" class="w-10 h-10 rounded border-0 cursor-pointer">
                        <input type="text" value="{{ $branding->secondary_color }}" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Accent Color</label>
                    <div class="flex items-center gap-2">
                        <input name="accent_color" type="color" value="{{ $branding->accent_color }}" class="w-10 h-10 rounded border-0 cursor-pointer">
                        <input type="text" value="{{ $branding->accent_color }}" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Button Border Radius</label>
                <input name="button_radius" type="text" value="{{ $branding->button_radius }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
            </div>

            <button type="submit" class="py-3.5 px-6 bg-indigo-600 hover:bg-indigo-700 font-bold rounded-xl text-xs text-white shadow-md transition">
                Update Theme Variables & Refresh Cache
            </button>
        </form>
    </div>

    <!-- General Settings Form -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-6">
        <h3 class="font-black text-slate-900 text-base border-b border-slate-100 pb-3">Business & Site Settings</h3>
        <form action="{{ route('admin.cms.settings') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Live Investor Activity Toast Notifications (Bottom-Left)</label>
                    <select name="enable_live_activity_toast" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
                        <option value="1" {{ (\App\Services\DynamicSettingService::get('enable_live_activity_toast', '1') == '1') ? 'selected' : '' }}>Enabled (Show Live Activity Toasts)</option>
                        <option value="0" {{ (\App\Services\DynamicSettingService::get('enable_live_activity_toast', '1') == '0') ? 'selected' : '' }}>Disabled (Hide Live Activity Toasts)</option>
                    </select>
                    <p class="text-[11px] text-slate-500 mt-1">Super Admin control to enable or disable the live deposit, withdrawal, and investment activity toast popups on the public website.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Hero Main Headline</label>
                    <input name="hero_headline" type="text" value="{{ $settings['hero_headline']->value ?? 'Grow Your Wealth with Next-Gen Yield Harvesting' }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Hero Sub-headline / Paragraph</label>
                    <textarea name="hero_subheadline" rows="2" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs text-slate-900 font-medium">{{ $settings['hero_subheadline']->value ?? '' }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Company Full Name</label>
                    <input name="company_full_name" type="text" value="{{ $settings['company_full_name']->value ?? '' }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Support Email</label>
                    <input name="support_email" type="email" value="{{ $settings['support_email']->value ?? '' }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Risk Warning Text</label>
                <textarea name="risk_warning_text" rows="3" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs text-slate-900 font-medium">{{ $settings['risk_warning_text']->value ?? '' }}</textarea>
            </div>
            <button type="submit" class="py-3.5 px-6 bg-slate-900 hover:bg-slate-800 font-bold rounded-xl text-xs text-white shadow-md transition">
                Save Site Settings
            </button>
        </form>
    </div>
</div>
@endsection
