@extends('layouts.user')

@section('title', 'KYC Identity Verification - BlockHarvest')

@section('content')
<div class="space-y-8 max-w-3xl">
    <div>
        <h1 class="text-2xl font-black text-slate-900">Identity Verification (KYC)</h1>
        <p class="text-xs text-slate-500">Privately store documents to unlock full platform features and withdrawal approvals.</p>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex justify-between items-center">
            <div>
                <p class="text-xs text-slate-500 uppercase font-bold">Current Verification Status</p>
                <p class="text-lg font-black text-slate-900 capitalize mt-0.5">{{ $user->kyc_status }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $user->kyc_status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                {{ $user->kyc_status }}
            </span>
        </div>

        @if($latestSubmission && $latestSubmission->status === 'rejected')
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium">
                <strong>Rejection Reason:</strong> {{ $latestSubmission->rejection_reason }}
            </div>
        @endif

        @if($user->kyc_status !== 'approved')
            <form action="{{ route('user.kyc.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">ID Document Type</label>
                        <select name="id_type" required class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-900 text-sm outline-none">
                            <option value="passport">Government Passport</option>
                            <option value="national_id">National ID Card</option>
                            <option value="drivers_license">Driver's License</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Document Number (Optional)</label>
                        <input name="id_number" type="text" placeholder="e.g. A98765432" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm outline-none">
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Front of ID Document</label>
                        <input name="id_front" type="file" required accept="image/*,application/pdf" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Back of ID Document (Optional)</label>
                        <input name="id_back" type="file" accept="image/*,application/pdf" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Selfie Verification Image (Optional)</label>
                        <input name="selfie" type="file" accept="image/*" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-600 outline-none">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm btn-primary shadow-lg">
                    Submit Verification Documents
                </button>
            </form>
        @else
            <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-emerald-500 text-white font-bold text-xl flex items-center justify-center mx-auto">✓</div>
                <h4 class="font-bold text-emerald-900 text-base">Account Fully Verified</h4>
                <p class="text-xs text-emerald-700">Your identity documents have been approved by compliance.</p>
            </div>
        @endif
    </div>
</div>
@endsection
