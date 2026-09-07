@extends('layouts.public')

@section('title', 'FAQs - BlockHarvest')

@section('content')
<div class="py-16 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center space-y-3">
            <h1 class="text-4xl font-black text-slate-900">Frequently Asked Questions</h1>
            <p class="text-slate-600">Database-managed answers to common investor questions.</p>
        </div>

        <div class="space-y-4">
            @foreach($faqs as $faq)
            <div x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                <button @click="open = !open" class="w-full flex justify-between items-center text-left font-bold text-slate-900 text-base">
                    <span>{{ $faq->question }}</span>
                    <span x-text="open ? '−' : '+'" class="text-indigo-600 text-xl font-bold"></span>
                </button>
                <div x-show="open" x-cloak class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    {{ $faq->answer }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
