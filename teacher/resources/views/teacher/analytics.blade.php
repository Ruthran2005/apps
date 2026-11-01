@extends('layouts.app')

@section('title', 'Student Performance Analytics — Faculty Workspace')
@section('header_title', 'Student Performance & Academic Analytics')

@section('content')
<div class="space-y-6">

    <!-- Top Action Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/90 backdrop-blur-sm p-6 rounded-3xl border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-cyan-500 animate-ping"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-cyan-800 bg-cyan-50 px-2.5 py-0.5 rounded-full border border-cyan-200/60">Classroom Intelligence</span>
            </div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Student Performance Analytics</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Holistic evaluation combining roll-call attendance, homework completion rate, and examination scores.</p>
        </div>

        <form method="GET" action="{{ route('teacher.analytics') }}" class="flex items-center gap-2">
            <label class="text-xs font-bold text-slate-500 uppercase">Class:</label>
            <select name="class_id" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Class Average Marks -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Class Average Score</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-slate-900">{{ $classAvgMarks }}%</p>
            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60 mt-2 inline-block">Term Evaluation</span>
        </div>

        <!-- Attendance Rate -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Average Attendance</span>
                <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-slate-900">{{ $classAvgAttendance }}%</p>
            <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-200/60 mt-2 inline-block">Roll Call Ratio</span>
        </div>

        <!-- Top Performers -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Top Achievers</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-slate-900">{{ $highPerformersCount }}</p>
            <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200/60 mt-2 inline-block">&ge;85% Score</span>
        </div>

        <!-- At-Risk Students -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Needs Attention</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-amber-600">{{ $atRiskCount }}</p>
            <span class="text-xs font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200/60 mt-2 inline-block">Remedial Focus</span>
        </div>
    </div>

    <!-- Comparative Student Performance Table -->
    <div class="bg-white/95 backdrop-blur-sm rounded-3xl border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-display text-base font-bold text-slate-900">Student Academic & Attendance Register</h3>
                <p class="text-xs text-slate-400 mt-0.5">Comprehensive audit of attendance discipline, assignment turn-in rate, and examination marks</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3 px-4">Admission #</th>
                        <th class="py-3 px-4">Attendance Rate</th>
                        <th class="py-3 px-4">Exam Average</th>
                        <th class="py-3 px-4">Homework Rate</th>
                        <th class="py-3 px-4 text-center">Status Flag</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($analyticsList as $row)
                        @php
                            $st = $row['student'];
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-500 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                        {{ substr($st->user->name ?? 'S', 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $st->user->name ?? 'Student' }}</p>
                                        <p class="text-[11px] text-slate-400">{{ $st->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-semibold text-slate-600">{{ $st->admission_number ?? 'N/A' }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-24 bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full {{ $row['attendance_rate'] >= 85 ? 'bg-emerald-500' : ($row['attendance_rate'] >= 75 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $row['attendance_rate'] }}%"></div>
                                    </div>
                                    <span class="font-bold text-slate-800 text-xs">{{ $row['attendance_rate'] }}%</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-xs {{ $row['avg_marks'] !== 'N/A' && $row['avg_marks'] >= 80 ? 'text-emerald-700' : ($row['avg_marks'] !== 'N/A' && $row['avg_marks'] < 60 ? 'text-rose-600' : 'text-slate-800') }}">
                                    {{ $row['avg_marks'] }}{{ $row['avg_marks'] !== 'N/A' ? '%' : '' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-semibold text-slate-700">{{ $row['hw_rate'] }}%</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold uppercase tracking-wider {{ $row['status'] == 'Top Performer' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($row['status'] == 'Needs Attention' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">No student performance records available for this class.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
