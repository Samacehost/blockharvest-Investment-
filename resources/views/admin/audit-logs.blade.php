@extends('layouts.admin')

@section('title', 'Audit Logs Trail')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-black text-slate-900">System Immutable Audit Trail</h1>
        <p class="text-xs text-slate-500">Security audit records of administrator balance edits, currency modifications, and platform changes.</p>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 uppercase text-[10px] text-slate-500 tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Timestamp</th>
                        <th class="px-4 py-3">Administrator / User</th>
                        <th class="px-4 py-3">Event</th>
                        <th class="px-4 py-3">Auditable Item</th>
                        <th class="px-4 py-3">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($logs as $l)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-mono text-slate-500">{{ $l->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="px-4 py-3 font-bold text-slate-900">{{ $l->user->name ?? 'System' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200">{{ $l->event }}</span>
                        </td>
                        <td class="px-4 py-3 font-mono text-slate-600 text-[11px]">{{ $l->auditable_type }} #{{ $l->auditable_id }}</td>
                        <td class="px-4 py-3 text-slate-500 font-mono">{{ $l->ip_address }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>{{ $logs->links() }}</div>
    </div>
</div>
@endsection
