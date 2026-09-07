@extends('layouts.user')

@section('title', 'Support Tickets - BlockHarvest')

@section('content')
<div class="space-y-8" x-data="{ newTicketModal: false }">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Support & Help Desk</h1>
            <p class="text-xs text-slate-500">Communicate directly with our financial support specialists.</p>
        </div>
        <div>
            <button @click="newTicketModal = true" class="px-5 py-3 rounded-xl text-white font-bold text-xs btn-primary shadow-md">
                + Open Support Ticket
            </button>
        </div>
    </div>

    <!-- New Ticket Modal -->
    <div x-show="newTicketModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div @click.away="newTicketModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-6">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                <h4 class="font-bold text-slate-900 text-lg">Open New Support Ticket</h4>
                <button @click="newTicketModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form action="{{ route('user.support.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Category</label>
                    <select name="support_category_id" required class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-900 text-sm outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Subject</label>
                    <input name="subject" type="text" required class="w-full px-4 py-3 rounded-xl border border-slate-300 font-bold text-slate-900 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Message</label>
                    <textarea name="message" rows="4" required class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm outline-none"></textarea>
                </div>
                <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm btn-primary shadow-lg">
                    Submit Ticket
                </button>
            </form>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="space-y-6">
        @foreach($tickets as $t)
        <div x-data="{ expanded: false }" class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex justify-between items-center cursor-pointer" @click="expanded = !expanded">
                <div>
                    <span class="text-xs font-mono font-bold text-indigo-600">#{{ $t->reference }}</span>
                    <h4 class="font-bold text-slate-900 text-base mt-0.5">{{ $t->subject }}</h4>
                    <p class="text-xs text-slate-500 mt-1">Category: {{ $t->category->name ?? 'General' }} • Updated {{ $t->updated_at->diffForHumans() }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $t->status === 'open' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $t->status }}
                    </span>
                    <span x-text="expanded ? '▲' : '▼'" class="text-slate-400 font-bold"></span>
                </div>
            </div>

            <!-- Messages Thread -->
            <div x-show="expanded" x-cloak class="pt-4 border-t border-slate-100 space-y-4">
                <div class="space-y-3">
                    @foreach($t->messages as $m)
                    <div class="p-4 rounded-2xl text-xs space-y-1 {{ $m->sender_type === 'user' ? 'bg-indigo-50 text-indigo-950 ml-6' : 'bg-slate-100 text-slate-900 mr-6' }}">
                        <div class="flex justify-between font-bold text-[11px] uppercase tracking-wider">
                            <span>{{ $m->sender_type === 'user' ? 'You' : 'BlockHarvest Support Agent' }}</span>
                            <span class="text-slate-500 font-normal">{{ $m->created_at->format('M d, H:i') }}</span>
                        </div>
                        <p class="leading-relaxed">{{ $m->message }}</p>
                    </div>
                    @endforeach
                </div>

                <!-- Reply Form -->
                <form action="{{ route('user.support.reply', $t->id) }}" method="POST" class="pt-2 flex gap-3">
                    @csrf
                    <input name="message" type="text" required placeholder="Type your reply..." class="flex-grow px-4 py-2.5 rounded-xl border border-slate-300 text-xs outline-none">
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-white font-bold text-xs btn-primary">Reply</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
