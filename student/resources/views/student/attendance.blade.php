@extends('layouts.app')

@section('title', 'Attendance — Student Workspace')
@section('header_title', 'Attendance Record')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Attendance Record</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Live daily attendance entries marked by faculty. Clearly shows <span class="font-bold text-emerald-600">Present</span> or <span class="font-bold text-rose-600">Absent</span> status.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-2xl bg-purple-50 text-purple-800 font-semibold text-xs border border-purple-200/60 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                {{ $student->schoolClass->name }} &bull; Section {{ $student->section->name }}
            </span>
        </div>
    </div>

    <!-- KPI Summary Cards (Present vs Absent) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Attendance Rate Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Overall Attendance</span>
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    %
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-purple-700 tracking-tight mt-3">{{ $rate }}%</p>
            <p class="text-xs text-slate-400 mt-1">{{ $total }} Total school days recorded</p>
        </div>

        <!-- Days Present Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Total Present</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-emerald-600 tracking-tight mt-3">{{ $present }}</p>
            <p class="text-xs text-slate-400 mt-1">Days marked Present in class</p>
        </div>

        <!-- Days Absent Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-600">Total Absent</span>
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-rose-600 tracking-tight mt-3">{{ $absent }}</p>
            <p class="text-xs text-slate-400 mt-1">Days marked Absent from class</p>
        </div>
    </div>

    <!-- Attendance History List / Table -->
    <div class="bg-white rounded-3xl shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] border border-slate-200/70 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-display text-base sm:text-lg font-bold text-slate-900">Daily Attendance History</h3>
                <p class="text-xs text-slate-500 mt-0.5">Chronological record of classroom attendance</p>
            </div>
            <span class="text-xs font-semibold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200/60">
                {{ $attendances->count() }} Entries
            </span>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-100 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Attendance Date</th>
                        <th class="px-6 py-4">Day of Week</th>
                        <th class="px-6 py-4 text-center">Status (Present / Absent)</th>
                        <th class="px-6 py-4">Teacher Remark</th>
                        <th class="px-6 py-4 text-right">Faculty Instructor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attendances as $att)
                    @php
                        $isPresent = in_array($att->status, ['present', 'late']);
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-4 font-bold text-slate-900 text-xs sm:text-sm">
                            {{ $att->attendance_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-500">
                            {{ $att->attendance_date->format('l') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($isPresent)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Present</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/80 shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    <span>Absent</span>
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600">
                            {{ $att->remarks ?? ($isPresent ? 'Marked Present' : 'Marked Absent') }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 text-right font-medium">
                            {{ $att->teacher->user->name ?? 'Classroom Faculty' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium">
                            No attendance history logged yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List View -->
        <div class="sm:hidden p-4 space-y-3">
            @forelse($attendances as $att)
            @php
                $isPresent = in_array($att->status, ['present', 'late']);
            @endphp
            <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 flex items-center justify-between">
                <div>
                    <p class="font-bold text-slate-900 text-sm">{{ $att->attendance_date->format('M d, Y') }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $att->attendance_date->format('l') }} &bull; {{ $att->teacher->user->name ?? 'Faculty' }}</p>
                </div>
                <div>
                    @if($isPresent)
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span>Present</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span>Absent</span>
                        </span>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-xs text-slate-400 py-6 text-center">No attendance history logged.</p>
            @endforelse
        </div>

    </div>

</div>
@endsection
