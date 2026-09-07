@extends('layouts.admin')

@section('title', 'KYC Review Queue')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-black text-slate-900">KYC Verification Submissions</h1>
        <p class="text-xs text-slate-500">Review uploaded identity proof documents for regulatory compliance.</p>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 uppercase text-[10px] text-slate-500 tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Doc Type</th>
                        <th class="px-4 py-3">ID Number</th>
                        <th class="px-4 py-3">Attached Documents</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($submissions as $sub)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-bold text-slate-900">{{ $sub->user->name ?? 'User' }}</td>
                        <td class="px-4 py-3 uppercase font-bold text-indigo-600">{{ $sub->id_type }}</td>
                        <td class="px-4 py-3 font-mono text-slate-600">{{ $sub->id_number ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                @foreach($sub->documents as $doc)
                                    <a href="{{ route('admin.kyc.download', $doc->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-xs font-bold text-indigo-700 rounded-lg border border-slate-200 flex items-center gap-1 transition">
                                        <span>📄 {{ ucfirst($doc->document_type) }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3 font-bold uppercase">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $sub->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($sub->status === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                {{ $sub->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            @if($sub->status === 'pending')
                                <form action="{{ route('admin.kyc.approve', $sub->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition">Approve</button>
                                </form>
                                <form action="{{ route('admin.kyc.reject', $sub->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="reason" value="Document unreadable or invalid.">
                                    <button class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition">Reject</button>
                                </form>
                            @else
                                <span class="text-slate-400 font-bold text-[11px]">Reviewed</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>{{ $submissions->links() }}</div>
    </div>
</div>
@endsection
