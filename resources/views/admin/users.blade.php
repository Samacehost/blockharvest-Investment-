@extends('layouts.admin')

@section('title', 'User Management & Ledger Adjustments')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Registered Investor Accounts</h1>
            <p class="text-xs text-slate-500">Manage user account statuses, roles, wallet balances, and generate synthetic deposit, withdrawal, and investment histories.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 uppercase text-[10px] text-slate-500 tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">User & Currency</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">KYC Status</th>
                        <th class="px-4 py-3">Available Balance</th>
                        <th class="px-4 py-3">Account Status</th>
                        <th class="px-4 py-3 text-right">Synthetic Tools & Ledger</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $u)
                    <tr x-data="{ adjustModal: false, setBalanceModal: false, genDepositModal: false, genWithdrawalModal: false, genInvestmentModal: false }" class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3.5">
                            <p class="font-bold text-slate-900 text-sm">{{ $u->name }}</p>
                            <p class="text-slate-500 font-mono text-[11px]">{{ $u->email }} • <strong class="text-indigo-600 font-bold">{{ $u->currency_code ?? 'USD' }}</strong></p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200">{{ $u->role }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $u->kyc_status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">{{ $u->kyc_status }}</span>
                        </td>
                        <td class="px-4 py-3 font-black text-slate-900 text-sm">${{ number_format($u->wallet->available_balance ?? 0, 2) }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.users.status', $u->id) }}" method="POST" class="inline">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="bg-slate-50 border border-slate-300 text-xs text-slate-900 rounded-xl px-2.5 py-1 font-bold outline-none">
                                    <option value="active" {{ $u->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="suspended" {{ $u->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    <option value="banned" {{ $u->status === 'banned' ? 'selected' : '' }}>Banned</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                <button @click="setBalanceModal = true" class="px-2.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-[11px] font-bold rounded-xl shadow-xs transition" title="Set Exact Wallet Balance">
                                    Set Balance
                                </button>
                                <button @click="genDepositModal = true" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold rounded-xl shadow-xs transition" title="Generate Deposit History">
                                    + Deposits
                                </button>
                                <button @click="genWithdrawalModal = true" class="px-2.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-[11px] font-bold rounded-xl shadow-xs transition" title="Generate Withdrawal History">
                                    + Withdrawals
                                </button>
                                <button @click="genInvestmentModal = true" class="px-2.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-[11px] font-bold rounded-xl shadow-xs transition" title="Generate Investment History">
                                    + Investments
                                </button>
                                <button @click="adjustModal = true" class="px-2.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-bold rounded-xl shadow-xs transition" title="Adjust Balance +/-">
                                    ± Adjust
                                </button>
                            </div>

                            <!-- Set Exact Balance Modal -->
                            <div x-show="setBalanceModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/70 flex items-center justify-center p-4 text-left">
                                <div @click.away="setBalanceModal = false" class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-md w-full text-slate-800 space-y-4 shadow-2xl">
                                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                                        <h4 class="font-black text-slate-900 text-lg">Set Wallet Balance: {{ $u->name }}</h4>
                                        <button @click="setBalanceModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                                    </div>
                                    <form action="{{ route('admin.users.set-balance', $u->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Available Balance ($)</label>
                                            <input name="available_balance" type="number" step="0.01" value="{{ number_format($u->wallet->available_balance ?? 0, 2, '.', '') }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2 text-sm text-slate-900 font-bold">
                                        </div>
                                        <div class="grid grid-cols-3 gap-2">
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase text-slate-700 mb-1">Invested ($)</label>
                                                <input name="invested_balance" type="number" step="0.01" value="{{ number_format($u->wallet->invested_balance ?? 0, 2, '.', '') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 font-bold">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase text-slate-700 mb-1">Earnings ($)</label>
                                                <input name="earnings_balance" type="number" step="0.01" value="{{ number_format($u->wallet->earnings_balance ?? 0, 2, '.', '') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 font-bold">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase text-slate-700 mb-1">Referral ($)</label>
                                                <input name="referral_balance" type="number" step="0.01" value="{{ number_format($u->wallet->referral_balance ?? 0, 2, '.', '') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 font-bold">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Admin Password Confirmation</label>
                                            <input name="admin_password" type="password" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2 text-sm text-slate-900">
                                        </div>
                                        <div class="pt-2">
                                            <button type="submit" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs shadow-md transition">Update Exact Balances</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Generate Deposit History Modal -->
                            <div x-show="genDepositModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/70 flex items-center justify-center p-4 text-left">
                                <div @click.away="genDepositModal = false" class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-md w-full text-slate-800 space-y-4 shadow-2xl">
                                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                                        <h4 class="font-black text-slate-900 text-lg">Generate Deposits: {{ $u->name }}</h4>
                                        <button @click="genDepositModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                                    </div>
                                    <p class="text-xs text-slate-500">Generate synthetic deposit records auto-matched to <strong>{{ $u->currency_code ?? 'USD' }}</strong> gateways.</p>
                                    <form action="{{ route('admin.users.generate-deposits', $u->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Deposit Count (e.g. 50)</label>
                                            <input name="count" type="number" value="50" min="1" max="500" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2 text-sm text-slate-900 font-bold">
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Min Amount</label>
                                                <input name="min_amount" type="number" step="0.01" value="100" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 font-bold">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Max Amount</label>
                                                <input name="max_amount" type="number" step="0.01" value="10000" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 font-bold">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Time Window (Days Back)</label>
                                            <input name="days_back" type="number" value="30" min="1" max="365" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2 text-xs text-slate-900 font-bold">
                                        </div>
                                        <div class="pt-2">
                                            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md transition">Generate {{ $u->currency_code ?? 'USD' }} Deposit History</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Generate Withdrawal History Modal -->
                            <div x-show="genWithdrawalModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/70 flex items-center justify-center p-4 text-left">
                                <div @click.away="genWithdrawalModal = false" class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-md w-full text-slate-800 space-y-4 shadow-2xl">
                                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                                        <h4 class="font-black text-slate-900 text-lg">Generate Withdrawals: {{ $u->name }}</h4>
                                        <button @click="genWithdrawalModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                                    </div>
                                    <p class="text-xs text-slate-500">Generate synthetic approved withdrawal records auto-matched to <strong>{{ $u->currency_code ?? 'USD' }}</strong> gateways.</p>
                                    <form action="{{ route('admin.users.generate-withdrawals', $u->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Withdrawal Count (e.g. 10)</label>
                                            <input name="count" type="number" value="10" min="1" max="500" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2 text-sm text-slate-900 font-bold">
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Min Amount</label>
                                                <input name="min_amount" type="number" step="0.01" value="50" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 font-bold">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Max Amount</label>
                                                <input name="max_amount" type="number" step="0.01" value="5000" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 font-bold">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Time Window (Days Back)</label>
                                            <input name="days_back" type="number" value="30" min="1" max="365" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2 text-xs text-slate-900 font-bold">
                                        </div>
                                        <div class="pt-2">
                                            <button type="submit" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs shadow-md transition">Generate {{ $u->currency_code ?? 'USD' }} Withdrawal History</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Generate Investment History Modal -->
                            <div x-show="genInvestmentModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/70 flex items-center justify-center p-4 text-left">
                                <div @click.away="genInvestmentModal = false" class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-md w-full text-slate-800 space-y-4 shadow-2xl">
                                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                                        <h4 class="font-black text-slate-900 text-lg">Generate Investments: {{ $u->name }}</h4>
                                        <button @click="genInvestmentModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                                    </div>
                                    <p class="text-xs text-slate-500">Auto-generates investments across date range, calculating maturity, ROI payouts, funding deposits, and wallet balance updates.</p>
                                    <form action="{{ route('admin.users.generate-investments', $u->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Investment Count (e.g. 40)</label>
                                            <input name="count" type="number" value="40" min="1" max="500" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2 text-sm text-slate-900 font-bold">
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">From Date</label>
                                                <input name="from_date" type="date" value="{{ now()->subDays(60)->format('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 font-bold">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">To Date</label>
                                                <input name="to_date" type="date" value="{{ now()->format('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 font-bold">
                                            </div>
                                        </div>
                                        <div class="pt-2">
                                            <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl text-xs shadow-md transition">Generate Investment Engine History</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Adjust Balance Modal -->
                            <div x-show="adjustModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/70 flex items-center justify-center p-4 text-left">
                                <div @click.away="adjustModal = false" class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 max-w-md w-full text-slate-800 space-y-4 shadow-2xl">
                                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                                        <h4 class="font-black text-slate-900 text-lg">Adjust Balance: {{ $u->name }}</h4>
                                        <button @click="adjustModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                                    </div>
                                    <form action="{{ route('admin.users.adjust-balance', $u->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Direction</label>
                                            <select name="direction" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
                                                <option value="CREDIT">Credit Balance (+)</option>
                                                <option value="DEBIT">Debit Balance (-)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Amount ($)</label>
                                            <input name="amount" type="number" step="0.01" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 font-bold">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Audit Reason</label>
                                            <input name="reason" type="text" required placeholder="Reason for manual adjustment..." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Admin Password Confirmation</label>
                                            <input name="admin_password" type="password" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900">
                                        </div>
                                        <div class="flex gap-2 pt-2">
                                            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-md transition">Execute Audit Adjustment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>{{ $users->links() }}</div>
    </div>
</div>
@endsection
