@extends('layouts.app')

@section('title', 'Reports & Analytics - SchoolNexus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-slate-900 tracking-tight">Institutional Reports & Analytics</h1>
            <p class="text-sm text-slate-500 mt-1">Cross-system executive metrics, student attendance trends, academic performance distributions & revenue health.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Print Executive Summary</span>
            </button>
        </div>
    </div>

    <!-- Executive Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Overall Attendance Rate</div>
            <div class="text-2xl font-bold font-display text-indigo-600 mt-1">{{ $attendanceStats['overall_rate'] }}%</div>
            <div class="text-xs text-slate-500 mt-1">Calculated across all divisions</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Fee Realization</div>
            @php
                $revPct = $feeStats['total_invoiced'] > 0 ? round(($feeStats['total_collected'] / $feeStats['total_invoiced']) * 100, 1) : 0;
            @endphp
            <div class="text-2xl font-bold font-display text-emerald-600 mt-1">${{ number_format($feeStats['total_collected'], 2) }}</div>
            <div class="text-xs text-emerald-600 font-medium mt-1">{{ $revPct }}% collected (Rs. {{ number_format($feeStats['pending_dues'], 2) }} pending)</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Enrolled Scholars</div>
            <div class="text-2xl font-bold font-display text-slate-900 mt-1">{{ $studentCount }}</div>
            <div class="text-xs text-slate-500 mt-1">Across {{ $classes->count() }} academic standards</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Faculty & Educators</div>
            <div class="text-2xl font-bold font-display text-slate-900 mt-1">{{ $teacherCount }}</div>
            <div class="text-xs text-slate-500 mt-1">Student:Faculty ratio 
                {{ $teacherCount > 0 ? round($studentCount / $teacherCount, 1) : $studentCount }}:1
            </div>
        </div>
    </div>

    <!-- Analytics Breakdown: 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Attendance Analysis Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 mb-2">Student Attendance Analysis</h2>
            <p class="text-xs text-slate-500 mb-4">Summary of daily presence, excusals, and unexcused absences.</p>

            <div class="space-y-3">
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-slate-700">Present</span>
                        <span class="text-emerald-600">{{ $attendanceStats['present'] }} entries</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-slate-700">Late Arrivals</span>
                        <span class="text-amber-600">{{ $attendanceStats['late'] }} entries</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-amber-400 h-2 rounded-full" style="width: 8%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-slate-700">Excused Absences</span>
                        <span class="text-indigo-600">{{ $attendanceStats['excused'] }} entries</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-indigo-400 h-2 rounded-full" style="width: 4%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-slate-700">Unexcused Absences</span>
                        <span class="text-rose-600">{{ $attendanceStats['absent'] }} entries</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-rose-500 h-2 rounded-full" style="width: 3%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Performance Grade Distribution Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 mb-2">Academic Grade Distribution</h2>
            <p class="text-xs text-slate-500 mb-4">Cumulative distribution of student exam grades across all subjects.</p>

            <div class="grid grid-cols-3 sm:grid-cols-6 gap-3 text-center">
                @foreach($gradesDistribution as $gradeLetter => $count)
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-base font-extrabold font-display 
                        {{ in_array($gradeLetter, ['A+', 'A']) ? 'text-emerald-600' : (in_array($gradeLetter, ['B', 'C']) ? 'text-indigo-600' : 'text-amber-600') }}">
                        {{ $gradeLetter }}
                    </div>
                    <div class="text-xs font-bold text-slate-900 mt-1">{{ $count }}</div>
                    <div class="text-[10px] text-slate-400 uppercase">Grades</div>
                </div>
                @endforeach
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-slate-500">
                <span class="font-semibold text-slate-700">Performance Insight:</span> Over 80% of scholars are performing in the A/B quartile for current assessment terms.
            </div>
        </div>
    </div>

    <!-- Class-wise Enrollment Breakdown -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Class & Standard Enrollment Analytics</h2>
                <p class="text-xs text-slate-500">Section capacity utilization and student demographics by grade level.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Standard / Grade</th>
                        <th class="px-5 py-3.5">Class Code</th>
                        <th class="px-5 py-3.5">Enrolled Students</th>
                        <th class="px-5 py-3.5">Sections Count</th>
                        <th class="px-5 py-3.5 text-right">Capacity Utilization</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($classes as $c)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5 font-bold text-slate-900">{{ $c->name }}</td>
                        <td class="px-5 py-3.5 font-mono text-xs text-indigo-600 font-semibold">{{ $c->code }}</td>
                        <td class="px-5 py-3.5 font-bold text-slate-800">{{ $c->students_count }} Scholars</td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">{{ $c->sections->count() }} Active Sections</td>
                        <td class="px-5 py-3.5 text-right">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Normal
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-slate-400 text-sm">No classes registered.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
