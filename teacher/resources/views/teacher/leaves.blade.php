@extends('layouts.app')

@section('title', 'Leave Management — Faculty Workspace')
@section('header_title', 'Faculty Leave Requests & Approvals')

@section('content')
<div class="space-y-6" x-data="{ leaveModal: false }">

    <!-- Top Action Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/90 backdrop-blur-sm p-6 rounded-3xl border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200/60">Faculty HR & Time Off</span>
            </div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Apply for Leave & Track Status</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Submit casual, medical, or duty leave requests to the administrative office and view real-time approval status.</p>
        </div>
        <button @click="leaveModal = true" class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/30 transition active:scale-95 flex items-center gap-2 shrink-0 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Apply for Leave</span>
        </button>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Approved Days -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Approved Leaves</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-slate-900">{{ $totalApproved }} <span class="text-sm font-normal text-slate-400">days</span></p>
            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60 mt-2 inline-block">Authorized Days</span>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Pending Review</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-amber-600">{{ $totalPending }}</p>
            <span class="text-xs font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200/60 mt-2 inline-block">With Principal</span>
        </div>

        <!-- Rejected Requests -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Rejected Requests</span>
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-slate-900">{{ $totalRejected }}</p>
            <span class="text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200 mt-2 inline-block">Not Approved</span>
        </div>
    </div>

    <!-- Feedback messages -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Leaves History Table -->
    <div class="bg-white/95 backdrop-blur-sm rounded-3xl border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="font-display text-base font-bold text-slate-900">Your Leave History & Status</h3>
            <p class="text-xs text-slate-400 mt-0.5">Audit log of submitted requests, approval timestamps, and administration remarks</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Leave Type</th>
                        <th class="py-3 px-4">Duration & Dates</th>
                        <th class="py-3 px-4">Days</th>
                        <th class="py-3 px-4">Reason for Leave</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Admin Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900">{{ $leave->leave_type }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-600">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}
                                <span class="text-slate-400">to</span>
                                {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-800">
                                {{ $leave->days_count }} {{ \Illuminate\Support\Str::plural('day', $leave->days_count) }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-700 max-w-xs truncate" title="{{ $leave->reason }}">
                                {{ $leave->reason }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold uppercase tracking-wider {{ $leave->status == 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($leave->status == 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                    {{ $leave->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 italic max-w-xs truncate">
                                {{ $leave->admin_remarks ?? ($leave->status == 'pending' ? 'Under review by administration' : '—') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">No leave applications recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Apply Leave Modal -->
    <div x-cloak x-show="leaveModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5" @click.away="leaveModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-display text-lg font-bold text-slate-900">Submit Leave Application</h3>
                <button @click="leaveModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('teacher.leaves.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Leave Category</label>
                    <select name="leave_type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                        <option value="Casual Leave">Casual Leave (CL)</option>
                        <option value="Medical Leave">Medical Leave (Prescribed)</option>
                        <option value="Duty Leave">Academic Duty / Conference Leave</option>
                        <option value="Maternity / Paternity">Maternity / Paternity Leave</option>
                        <option value="Unpaid Leave">Unpaid Special Leave</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Start Date</label>
                        <input type="date" name="start_date" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">End Date</label>
                        <input type="date" name="end_date" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Reason for Leave & Handover Details</label>
                    <textarea name="reason" rows="3" required placeholder="State your reason and any substitute arrangement made for your classes..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="leaveModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md transition active:scale-95">Submit to Principal</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
