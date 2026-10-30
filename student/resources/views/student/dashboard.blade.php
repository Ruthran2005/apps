@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('header_title', 'Student Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Personalized Student Hero Banner with Violet Neon Glow -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-purple-950 to-slate-900 p-6 sm:p-10 text-white shadow-2xl border border-purple-900/40">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-fuchsia-600/15 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/20 border border-purple-400/30 text-purple-300 text-xs font-semibold mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-ping"></span>
                    Student Portal
                </span>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                    Welcome, {{ $student->user->name }}!
                </h2>
                <p class="text-purple-200/80 text-xs sm:text-sm mt-2 max-w-xl leading-relaxed">
                    {{ $student->schoolClass->name }} &bull; Section {{ $student->section->name }} &bull; Roll: <span class="font-bold text-white">#{{ $student->roll_number }}</span> &bull; Admission ID: <span class="font-mono text-purple-300 font-bold bg-purple-900/60 px-2 py-0.5 rounded border border-purple-700/60">{{ $student->admission_number }}</span>
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('student.homework') }}" class="px-5 py-3 rounded-2xl bg-white text-slate-950 hover:bg-purple-50 font-bold text-xs sm:text-sm shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2 active:scale-95">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>View Homework</span>
                </a>
                <a href="{{ route('student.timetable') }}" class="px-5 py-3 rounded-2xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-purple-600/40 hover:shadow-purple-500/60 transition-all duration-200 flex items-center space-x-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Class Timetable</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Attendance Metric Card (Present vs Absent) -->
        <div class="group bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_30px_-10px_rgba(16,185,129,0.12)] hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Attendance Rate</span>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500/10 to-emerald-500/20 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <p class="font-display text-3xl font-extrabold text-slate-900 tracking-tight">{{ $attendanceRate }}%</p>
                <div class="flex items-center gap-1.5 text-xs font-semibold">
                    <span class="text-emerald-700 font-bold">{{ $presentCount }} Present</span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-rose-600 font-bold">{{ $absentCount }} Absent</span>
                </div>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5 mt-3 overflow-hidden p-0.5">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-2 rounded-full transition-all duration-700 shadow-sm" style="width: {{ $attendanceRate }}%"></div>
            </div>
        </div>

        <!-- Homework Completed Card -->
        <div class="group bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_30px_-10px_rgba(168,85,247,0.12)] hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Homework Turned In</span>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-500/10 to-purple-500/20 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <p class="font-display text-3xl font-extrabold text-purple-700 tracking-tight">{{ $completedHomeworkCount }}</p>
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200/60">Submissions Logged</span>
            </div>
            <div class="flex items-center mt-2.5 space-x-2">
                <a href="{{ route('student.homework') }}" class="text-xs text-purple-600 hover:text-purple-700 font-bold">View Grades & Feedback &rarr;</a>
            </div>
        </div>

        <!-- Profile / Status Card -->
        <div class="group bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_30px_-10px_rgba(59,130,246,0.12)] hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Academic Standing</span>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-500/10 to-blue-500/20 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="font-display text-2xl font-extrabold text-slate-900 tracking-tight">Active Scholar</p>
                <div class="flex items-center mt-2.5 space-x-2">
                    <span class="text-xs font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200/60">Term 1 Enrolled</span>
                    <a href="{{ route('student.profile') }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold">View Profile &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Dual Columns: Today's Schedule & Homework Tasks -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Today's Schedule -->
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] border border-slate-200/70 p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="font-display text-base font-bold text-slate-900">Today's Class Schedule &bull; {{ $dayOfWeek }}</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Assigned lecture periods & classrooms</p>
                </div>
                <a href="{{ route('student.timetable') }}" class="text-xs font-bold text-purple-600 hover:text-purple-700">Full Timetable &rarr;</a>
            </div>

            <div class="space-y-3.5">
                @forelse($todaySchedule as $period)
                <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/60 flex items-center justify-between hover:border-purple-300 hover:bg-white hover:shadow-md transition-all duration-200">
                    <div class="flex items-center space-x-3.5">
                        <div class="px-3 py-2 bg-purple-50 text-purple-800 border border-purple-200/60 rounded-xl text-xs font-mono font-bold shadow-sm">
                            {{ substr($period->start_time, 0, 5) }} - {{ substr($period->end_time, 0, 5) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">{{ $period->subject->name }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $period->teacher->user->name ?? 'Faculty' }} &bull; {{ $period->room_number ?? 'Room 101' }}</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-xl bg-white border border-slate-200 text-xs font-semibold text-slate-700 shadow-2xs">
                        Period
                    </span>
                </div>
                @empty
                <p class="text-xs text-slate-400 text-center py-8">No class periods scheduled for today.</p>
                @endforelse
            </div>
        </div>

        <!-- Homework & Coursework Due -->
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] border border-slate-200/70 p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="font-display text-base font-bold text-slate-900">Current Homework</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Tasks, deadlines, and grades</p>
                </div>
                <a href="{{ route('student.homework') }}" class="text-xs font-bold text-purple-600 hover:text-purple-700">All Homework &rarr;</a>
            </div>

            <div class="space-y-3.5">
                @forelse($assignments as $hw)
                @php
                    $mySub = $hw->submissions->first();
                @endphp
                <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/60 hover:border-purple-300 hover:bg-white hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="px-2 py-0.5 rounded-lg bg-purple-50 text-purple-800 text-[10px] font-bold border border-purple-100">
                            {{ $hw->subject->name }}
                        </span>
                        <span class="text-[11px] text-slate-400 font-medium">Due: {{ $hw->due_date->format('M d, h:i A') }}</span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm mt-1">{{ $hw->title }}</h4>
                    <div class="mt-3 flex items-center justify-between pt-2 border-t border-slate-200/60">
                        @if($mySub && $mySub->status == 'graded')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Result: {{ $mySub->marks_awarded }} / {{ $hw->total_points }} pts
                            </span>
                        @elseif($mySub)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-purple-50 text-purple-700 border border-purple-200">
                                Turned In &bull; Pending Review
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-50 text-rose-700 border border-rose-200">
                                Not Submitted
                            </span>
                        @endif

                        <a href="{{ route('student.homework') }}" class="text-xs font-bold text-purple-600 hover:text-purple-700">
                            {{ $mySub ? 'View Result' : 'Upload Answer' }} &rarr;
                        </a>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-400 text-center py-8">No homework assigned at this time.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Campus Announcements Section -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <div>
                <h3 class="font-display text-base font-bold text-slate-900">Campus Notices & Announcements</h3>
                <p class="text-xs text-slate-400 mt-0.5">Official communications from faculty and administration</p>
            </div>
            <a href="{{ route('student.announcements') }}" class="text-xs font-bold text-purple-600 hover:text-purple-700">All Notices &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($announcements as $ann)
            <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/60 hover:bg-purple-50/30 hover:border-purple-200 transition">
                <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1.5">
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $ann->priority === 'urgent' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                        {{ $ann->priority }}
                    </span>
                    <span>{{ \Carbon\Carbon::parse($ann->published_at)->format('M d') }}</span>
                </div>
                <h4 class="font-bold text-slate-900 text-xs sm:text-sm line-clamp-1">{{ $ann->title }}</h4>
                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $ann->content }}</p>
            </div>
            @empty
            <p class="text-xs text-slate-400 py-4 col-span-full text-center">No current announcements.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
