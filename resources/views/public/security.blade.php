@extends('layouts.public')

@section('title', 'Security & Transparency - BlockHarvest')

@section('content')
<div class="py-16 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center space-y-3">
            <h1 class="text-4xl font-black text-slate-900">Security & Financial Integrity Model</h1>
            <p class="text-slate-600">Built to protect institutional capital and private investor identity.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="p-8 bg-white rounded-3xl border border-slate-200 shadow-md space-y-4">
                <h3 class="font-bold text-slate-900 text-lg">Private KYC Vault</h3>
                <p class="text-sm text-slate-600">All identity verification files are saved in isolated non-public storage directories accessible strictly via temporary signed URLs expiring after 15 minutes.</p>
            </div>

            <div class="p-8 bg-white rounded-3xl border border-slate-200 shadow-md space-y-4">
                <h3 class="font-bold text-slate-900 text-lg">Double-Entry Financial Ledger</h3>
                <p class="text-sm text-slate-600">Wallet balance updates require auditable ledger logs with before-and-after snapshots, preventing unauthorized manual balance edits.</p>
            </div>
        </div>
    </div>
</div>
@endsection
