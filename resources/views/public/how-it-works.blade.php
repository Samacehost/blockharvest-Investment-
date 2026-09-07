@extends('layouts.public')

@section('title', 'How It Works - BlockHarvest')

@section('content')
<div class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center space-y-3">
            <h1 class="text-4xl font-black text-slate-900">How the Platform Works</h1>
            <p class="text-slate-600">A clear, transparent guide to managing your investments on BlockHarvest.</p>
        </div>

        <div class="space-y-8">
            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 flex gap-6">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold text-xl flex items-center justify-center flex-shrink-0">1</div>
                <div class="space-y-2">
                    <h3 class="text-xl font-bold text-slate-900">Create & Verify Account</h3>
                    <p class="text-sm text-slate-600">Sign up using a valid email address. Complete KYC document submission to enable higher withdrawal limits and account protection.</p>
                </div>
            </div>

            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 flex gap-6">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold text-xl flex items-center justify-center flex-shrink-0">2</div>
                <div class="space-y-2">
                    <h3 class="text-xl font-bold text-slate-900">Deposit Funds to Wallet</h3>
                    <p class="text-sm text-slate-600">Deposit USD via Bank Wire or USDT TRC20. All incoming deposits are reviewed by our finance desk before wallet credit.</p>
                </div>
            </div>

            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 flex gap-6">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold text-xl flex items-center justify-center flex-shrink-0">3</div>
                <div class="space-y-2">
                    <h3 class="text-xl font-bold text-slate-900">Choose Strategy & Accrue Yields</h3>
                    <p class="text-sm text-slate-600">Select an active investment plan. The automated scheduler calculates and posts daily earnings to your immutable ledger.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
