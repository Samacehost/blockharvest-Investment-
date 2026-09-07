@extends('layouts.admin')

@section('title', 'Manage Payment Methods - Super Admin')
@section('page-title', 'Payment & Payout Methods')

@section('content')
<div class="space-y-8" x-data="{ depositModal: false, withdrawalModal: false }">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Payment & Payout Methods</h1>
            <p class="text-xs text-slate-500">Configure currency-scoped funding and withdrawal gateways (USD, GBP, CAD, or ALL).</p>
        </div>
        <div class="flex gap-3">
            <button @click="depositModal = true" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition">
                + Add Deposit Method
            </button>
            <button @click="withdrawalModal = true" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition">
                + Add Withdrawal Method
            </button>
        </div>
    </div>

    <!-- Deposit Methods Section -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                <span>💳</span> Deposit Funding Methods
            </h3>
            <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-200">
                {{ $depositMethods->count() }} Configured
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Method Name</th>
                        <th class="px-4 py-3">Currency Scope</th>
                        <th class="px-4 py-3">Limits (Min - Max)</th>
                        <th class="px-4 py-3">Flat / % Fee</th>
                        <th class="px-4 py-3">Type / Details</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($depositMethods as $dm)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-bold text-slate-900">
                                {{ $dm->name }}
                            </td>
                            <td class="px-4 py-3">
                                @if($dm->currency_code === 'ALL')
                                    <span class="px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-200 font-bold">🌐 ALL Currencies</span>
                                @elseif($dm->currency_code === 'GBP')
                                    <span class="px-2.5 py-0.5 rounded-md bg-sky-50 text-sky-700 border border-sky-200 font-bold">🇬🇧 GBP Only</span>
                                @elseif($dm->currency_code === 'CAD')
                                    <span class="px-2.5 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200 font-bold">🇨🇦 CAD Only</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold">🇺🇸 USD Only</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-semibold text-slate-800">
                                ${{ number_format($dm->min_amount) }} - ${{ number_format($dm->max_amount) }}
                            </td>
                            <td class="px-4 py-3 text-slate-500">
                                ${{ number_format($dm->fee_flat, 2) }} + {{ number_format($dm->fee_percent, 1) }}%
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                @if(!empty($dm->bank_details_json))
                                    <span class="text-indigo-700 font-bold">Bank:</span> {{ $dm->bank_details_json['bank_name'] ?? '' }}
                                @elseif(!empty($dm->crypto_address_json))
                                    <span class="text-emerald-700 font-bold">Crypto:</span> {{ $dm->crypto_address_json['network'] ?? '' }}
                                @else
                                    Standard Gateway
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $dm->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    {{ $dm->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <form action="{{ route('admin.payment-methods.deposit.toggle', $dm) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-lg text-xs font-bold transition {{ $dm->is_active ? 'bg-rose-100 text-rose-700 hover:bg-rose-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }}">
                                        {{ $dm->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payout Withdrawal Methods Section -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                <span>⚡</span> Payout Withdrawal Gateways
            </h3>
            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                {{ $withdrawalMethods->count() }} Configured
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Method Name</th>
                        <th class="px-4 py-3">Currency Scope</th>
                        <th class="px-4 py-3">Payout Limits</th>
                        <th class="px-4 py-3">Flat / % Payout Fee</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($withdrawalMethods as $wm)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-bold text-slate-900">
                                {{ $wm->name }}
                            </td>
                            <td class="px-4 py-3">
                                @if($wm->currency_code === 'ALL')
                                    <span class="px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-200 font-bold">🌐 ALL Currencies</span>
                                @elseif($wm->currency_code === 'GBP')
                                    <span class="px-2.5 py-0.5 rounded-md bg-sky-50 text-sky-700 border border-sky-200 font-bold">🇬🇧 GBP Only</span>
                                @elseif($wm->currency_code === 'CAD')
                                    <span class="px-2.5 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200 font-bold">🇨🇦 CAD Only</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold">🇺🇸 USD Only</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-semibold text-slate-800">
                                ${{ number_format($wm->min_amount) }} - ${{ number_format($wm->max_amount) }}
                            </td>
                            <td class="px-4 py-3 text-slate-500">
                                ${{ number_format($wm->fee_flat, 2) }} + {{ number_format($wm->fee_percent, 1) }}%
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $wm->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    {{ $wm->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <form action="{{ route('admin.payment-methods.withdrawal.toggle', $wm) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-lg text-xs font-bold transition {{ $wm->is_active ? 'bg-rose-100 text-rose-700 hover:bg-rose-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }}">
                                        {{ $wm->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Add Deposit Method -->
    <div x-show="depositModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4">
        <div @click.away="depositModal = false" class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl space-y-6 max-h-[90vh] overflow-y-auto text-slate-800">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <h4 class="font-black text-slate-900 text-lg">Add New Deposit Method</h4>
                <button @click="depositModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form action="{{ route('admin.payment-methods.deposit.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Method Name</label>
                        <input type="text" name="name" required placeholder="e.g. Barclays Wire Transfer" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 font-bold text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Currency Scope</label>
                        <select name="currency_code" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm outline-none font-bold">
                            <option value="ALL">🌐 ALL Currencies (Crypto / Global)</option>
                            <option value="USD">🇺🇸 USD Only (United States)</option>
                            <option value="GBP">🇬🇧 GBP Only (United Kingdom)</option>
                            <option value="CAD">🇨🇦 CAD Only (Canada)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Min Deposit ($)</label>
                        <input type="number" step="0.01" min="1" name="min_amount" value="50" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 font-bold text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Max Deposit ($)</label>
                        <input type="number" step="0.01" min="1" name="max_amount" value="100000" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 font-bold text-sm outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Flat Fee ($)</label>
                        <input type="number" step="0.01" min="0" name="fee_flat" value="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 font-bold text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Percent Fee (%)</label>
                        <input type="number" step="0.1" min="0" name="fee_percent" value="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 font-bold text-sm outline-none">
                    </div>
                </div>

                <!-- Optional Bank Details -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <p class="text-xs font-bold text-indigo-700 uppercase">Bank Wire Instructions (Optional):</p>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="bank_name" placeholder="Bank Name" class="px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900">
                        <input type="text" name="account_name" placeholder="Account Name" class="px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900">
                        <input type="text" name="account_number" placeholder="Account Number / IBAN" class="px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900">
                        <input type="text" name="swift_code" placeholder="SWIFT / Sort Code" class="px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900">
                    </div>
                </div>

                <!-- Optional Crypto Address -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <p class="text-xs font-bold text-emerald-700 uppercase">Crypto Payment Wallet (Optional):</p>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="crypto_network" placeholder="Network (e.g. TRON TRC20)" class="px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900">
                        <input type="text" name="crypto_address" placeholder="Wallet Address" class="px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">User Instructions</label>
                    <textarea name="instructions" rows="2" placeholder="Include your reference code in payment memo..." class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 outline-none"></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm bg-indigo-600 hover:bg-indigo-700 shadow-md transition">
                    Create Deposit Method
                </button>
            </form>
        </div>
    </div>

    <!-- Modal: Add Withdrawal Method -->
    <div x-show="withdrawalModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4">
        <div @click.away="withdrawalModal = false" class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-6 text-slate-800">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <h4 class="font-black text-slate-900 text-lg">Add Withdrawal Payout Gateway</h4>
                <button @click="withdrawalModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form action="{{ route('admin.payment-methods.withdrawal.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Method Name</label>
                    <input type="text" name="name" required placeholder="e.g. UK Bank Wire Payout" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm outline-none font-bold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Currency Scope</label>
                    <select name="currency_code" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm outline-none font-bold">
                        <option value="ALL">🌐 ALL Currencies (Crypto / Global)</option>
                        <option value="USD">🇺🇸 USD Only (United States)</option>
                        <option value="GBP">🇬🇧 GBP Only (United Kingdom)</option>
                        <option value="CAD">🇨🇦 CAD Only (Canada)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Min Payout ($)</label>
                        <input type="number" step="0.01" min="1" name="min_amount" value="50" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm outline-none font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Max Payout ($)</label>
                        <input type="number" step="0.01" min="1" name="max_amount" value="50000" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm outline-none font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Flat Fee ($)</label>
                        <input type="number" step="0.01" min="0" name="fee_flat" value="10" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm outline-none font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Percent Fee (%)</label>
                        <input type="number" step="0.1" min="0" name="fee_percent" value="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm outline-none font-bold">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm bg-emerald-600 hover:bg-emerald-700 shadow-md transition">
                    Create Payout Gateway
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
