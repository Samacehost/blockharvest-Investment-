@extends('layouts.public')

@section('title', 'Contact Us - BlockHarvest')

@section('content')
<div class="py-16 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200 shadow-xl space-y-8">
            <div class="text-center space-y-2">
                <h1 class="text-3xl font-black text-slate-900">Contact BlockHarvest Support</h1>
                <p class="text-sm text-slate-600">Have questions about investment strategies, deposit processing, or account limits?</p>
            </div>

            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold text-center">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('public.contact.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Your Name</label>
                        <input name="name" type="text" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address</label>
                        <input name="email" type="email" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 outline-none text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Subject</label>
                    <input name="subject" type="text" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 outline-none text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Message</label>
                    <textarea name="message" rows="5" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 outline-none text-sm"></textarea>
                </div>

                <div>
                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm btn-primary shadow-lg transition">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
