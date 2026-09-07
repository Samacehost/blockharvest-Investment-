@extends('layouts.admin')

@section('title', 'Support Tickets Queue')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-black text-slate-900">Support Desk Queue</h1>
        <p class="text-xs text-slate-500">Respond to investor help tickets and resolve inquiries directly.</p>
    </div>

    <div class="space-y-4">
        @foreach($tickets as $t)
        <div x-data="{ open: false }" class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                <div>
                    <span class="text-xs font-mono font-bold text-indigo-600">#{{ $t->reference }}</span>
                    <h3 class="font-bold text-slate-900 text-base mt-0.5">{{ $t->subject }}</h3>
                    <p class="text-xs text-slate-500">User: <strong class="text-slate-800">{{ $t->user->name ?? 'User' }}</strong> • {{ $t->updated_at->diffForHumans() }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $t->status === 'open' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">{{ $t->status }}</span>
                    <span x-text="open ? '▲' : '▼'" class="text-slate-400 font-bold text-xs"></span>
                </div>
            </div>

            <div x-show="open" x-cloak class="pt-4 border-t border-slate-100 space-y-4">
                <div class="space-y-3">
                    @foreach($t->messages as $m)
                    <div class="p-4 rounded-2xl text-xs space-y-1 {{ $m->sender_type === 'admin' ? 'bg-indigo-50 text-indigo-950 border border-indigo-200 ml-6 shadow-xs' : 'bg-slate-50 text-slate-900 border border-slate-200 mr-6 shadow-xs' }}">
                        <div class="flex justify-between font-bold text-[10px] uppercase tracking-wider">
                            <span class="{{ $m->sender_type === 'admin' ? 'text-indigo-700' : 'text-slate-500' }}">{{ $m->sender_type === 'admin' ? 'Staff Response' : 'Investor' }}</span>
                            <span class="text-slate-400">{{ $m->created_at->format('M d H:i') }}</span>
                        </div>
                        <p class="font-medium leading-relaxed">{{ $m->message }}</p>
                    </div>
                    @endforeach
                </div>

                <form action="{{ route('admin.support.reply', $t->id) }}" method="POST" class="flex gap-2 pt-2">
                    @csrf
                    <input name="message" type="text" required placeholder="Type staff response..." class="flex-grow bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs text-slate-900 placeholder:text-slate-400 font-medium">
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 font-bold rounded-xl text-xs text-white shadow-xs transition">Send Staff Reply</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
