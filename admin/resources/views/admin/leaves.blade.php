@extends('layouts.app')

@section('title', 'Leave Management - SchoolNexus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-slate-900 tracking-tight">Leave Management</h1>
            <p class="text-sm text-slate-500 mt-1">Review, approve and manage leave applications from teachers and students.</p>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pending Requests</div>
            <div class="text-2xl font-bold font-display text-amber-600 mt-1">{{ $pendingCount }}</div>
            <div class="text-xs text-slate-500 mt-1">Awaiting administrative decision</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Approved Leaves</div>
            <div class="text-2xl font-bold font-display text-emerald-600 mt-1">{{ $approvedCount }}</div>
            <div class="text-xs text-slate-500 mt-1">Authorized absences this term</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Rejected Applications</div>
            <div class="text-2xl font-bold font-display text-rose-600 mt-1">{{ $rejectedCount }}</div>
            <div class="text-xs text-slate-500 mt-1">Disallowed / non-compliant requests</div>
        </div>
    </div>

    <!-- Leave Requests Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Leave Applications Ledger</h2>
                <p class="text-xs text-slate-500">Chronological submissions with applicant role, type, reason and instant review action.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Applicant</th>
                        <th class="px-5 py-3.5">Role</th>
                        <th class="px-5 py-3.5">Leave Type</th>
                        <th class="px-5 py-3.5">Dates & Days</th>
                        <th class="px-5 py-3.5">Reason</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Decision</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leaves as $lv)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-slate-900">{{ $lv->user->name ?? 'User' }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $lv->user->email ?? '' }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $lv->applicant_type === 'teacher' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                {{ ucfirst($lv->applicant_type) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-medium text-slate-800">{{ $lv->leave_type }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">
                            <div>{{ Carbon\Carbon::parse($lv->start_date)->format('M d') }} - {{ Carbon\Carbon::parse($lv->end_date)->format('M d, Y') }}</div>
                            <div class="text-[11px] font-bold text-slate-400 mt-0.5">{{ $lv->days_count }} day(s)</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600 max-w-xs">
                            <p class="truncate" title="{{ $lv->reason }}">{{ $lv->reason }}</p>
                            @if($lv->admin_remarks)
                                <div class="text-[11px] text-slate-400 mt-1 italic">Admin: {{ $lv->admin_remarks }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($lv->status === 'approved')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Approved</span>
                            @elseif($lv->status === 'rejected')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-rose-50 text-rose-700 border border-rose-200">Rejected</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">Pending Review</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            @if($lv->status === 'pending')
                            <div class="flex items-center justify-end gap-1.5">
                                <form action="{{ route('admin.leaves.status', $lv->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.leaves.status', $lv->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="px-2.5 py-1 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-xs font-semibold rounded-lg transition">
                                        Reject
                                    </button>
                                </form>
                            </div>
                            @else
                                <span class="text-xs text-slate-400">Decided by {{ $lv->reviewed_by ?? 'Admin' }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">No leave requests logged.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $leaves->links() }}
        </div>
    </div>
</div>
@endsection
