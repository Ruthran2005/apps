@extends('layouts.app')

@section('title', 'Attendance Audit & Export — Admin Workspace')
@section('header_title', 'Campus Attendance Register & Export')

@section('content')
<div class="space-y-6">

    <!-- Header Banner Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Institutional Attendance Audit</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Audit daily roll call registers recorded by faculty across classes, and export compliance reports.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <!-- Export CSV Button with active query parameters -->
            <a href="{{ route('admin.attendance.export', ['class_id' => $selectedClassId, 'from_date' => $fromDate, 'to_date' => $toDate]) }}" 
               class="px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-emerald-600/30 transition-all duration-200 flex items-center space-x-2 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Export CSV Report</span>
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <form method="GET" action="{{ route('admin.attendance') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Class / Standard</label>
                <select name="class_id" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    <option value="">All Classes & Grades</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">From Date</label>
                <input type="date" name="from_date" value="{{ $fromDate }}" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">To Date</label>
                <input type="date" name="to_date" value="{{ $toDate }}" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm rounded-2xl shadow-sm transition active:scale-95 flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Filter Logs</span>
                </button>
                <a href="{{ route('admin.attendance') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-2xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- KPI Metric Cards for Filtered Range -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
            <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 block">Total Logs In Range</span>
            <p class="font-display text-3xl font-extrabold text-slate-900 mt-2">{{ $totalLogs }}</p>
            <span class="text-xs text-slate-400">Recorded sessions</span>
        </div>

        <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
            <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-600 block">Present Count</span>
            <p class="font-display text-3xl font-extrabold text-emerald-600 mt-2">{{ $presentLogs }}</p>
            <span class="text-xs text-slate-400">Attended lectures</span>
        </div>

        <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
            <span class="text-[11px] font-extrabold uppercase tracking-wider text-rose-600 block">Absent Count</span>
            <p class="font-display text-3xl font-extrabold text-rose-600 mt-2">{{ $absentLogs }}</p>
            <span class="text-xs text-slate-400">Recorded absences</span>
        </div>

        <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
            <span class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-600 block">Attendance Rate</span>
            <p class="font-display text-3xl font-extrabold text-indigo-600 mt-2">{{ $attendanceRate }}%</p>
            <span class="text-xs text-slate-400">Institutional Benchmark</span>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] border border-slate-200/80 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-display text-base font-bold text-slate-900">Attendance Register Entries</h3>
                <p class="text-xs text-slate-400 mt-0.5">Showing verified student attendance for the selected date window</p>
            </div>
            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200/60 font-mono">
                {{ $attendances->total() }} Records
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/90 text-[11px] uppercase font-extrabold tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Class Standard</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attendances as $att)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-mono font-semibold text-xs text-slate-800">
                            {{ $att->date->format('M d, Y (D)') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                    {{ substr($att->student->user->name ?? 'S', 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 text-xs">{{ $att->student->user->name ?? 'Student' }}</p>
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $att->student->admission_number ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-700">
                            {{ $att->schoolClass->name ?? 'Class' }} &bull; Sec {{ $att->section->name ?? 'A' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($att->status === 'present')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/70 shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                    Present
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/70 shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                    Absent
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-400">
                            {{ $att->remarks ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            No attendance records found for this criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendances->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $attendances->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
