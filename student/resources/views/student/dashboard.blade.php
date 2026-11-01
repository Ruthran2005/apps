@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('header_title', 'Student Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8" x-data="{ viewNotice: null }">

    <!-- Personalized Student Hero Banner with Violet Neon Glow -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-purple-950 to-slate-900 p-6 sm:p-10 text-white shadow-2xl border border-purple-900/40">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-fuchsia-600/15 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/20 border border-purple-400/30 text-purple-300 text-xs font-semibold mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-ping"></span>
                    Student Portal Active
                </span>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                    Welcome, {{ $student->user->name }}!
                </h2>
                <p class="text-purple-200/80 text-xs sm:text-sm mt-2 max-w-xl leading-relaxed">
                    {{ $student->schoolClass->name }} &bull; Section {{ $student->section->name }} &bull; Roll: <span class="font-bold text-white">#{{ $student->roll_number }}</span> &bull; Admission ID: <span class="font-mono text-purple-300 font-bold bg-purple-900/60 px-2 py-0.5 rounded border border-purple-700/60">{{ $student->admission_number }}</span>
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('student.announcements') }}" class="px-5 py-3 rounded-2xl bg-purple-500/20 hover:bg-purple-500/30 text-purple-200 border border-purple-400/30 font-bold text-xs sm:text-sm shadow-lg backdrop-blur-md transition-all duration-200 flex items-center space-x-2 active:scale-95">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    <span>Notices ({{ $announcementsCount }})</span>
                </a>
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

<!-- Key Metrics Cards (4 Stat Cards Grid) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Attendance Metric Card (Present vs Absent) -->
        <div class="group bg-white/90 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_30px_-10px_rgba(16,185,129,0.12)] hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Attendance Rate</span>
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-500/10 to-emerald-500/20 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <p class="font-display text-3xl font-extrabold text-slate-900 tracking-tight">{{ $attendanceRate }}%</p>
                <div class="flex items-center gap-1.5 text-xs font-semibold">
                    <span class="text-emerald-700 font-bold">{{ $presentCount }}P</span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-rose-600 font-bold">{{ $absentCount }}A</span>
                </div>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2 mt-3 overflow-hidden p-0.5">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-1.5 rounded-full transition-all duration-700" style="width: {{ $attendanceRate }}%"></div>
            </div>
        </div>

        <!-- Homework Completed Card -->
        <div class="group bg-white/90 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_30px_-10px_rgba(168,85,247,0.12)] hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Homework Turned In</span>
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-purple-500/10 to-purple-500/20 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-purple-700 tracking-tight mt-3">{{ $completedHomeworkCount }}</p>
            <div class="flex items-center mt-2.5 space-x-2">
                <span class="text-xs font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200/60">Submissions Logged</span>
                <a href="{{ route('student.homework') }}" class="text-xs text-purple-600 hover:text-purple-700 font-bold">&rarr;</a>
            </div>
        </div>

        <!-- Campus Announcements Stat Card -->
        <div class="group bg-white/90 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_30px_-10px_rgba(244,63,94,0.12)] hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">New Announcements</span>
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-rose-500/10 to-purple-500/20 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-slate-900 tracking-tight mt-3">{{ $announcementsCount }}</p>
            <div class="flex items-center mt-2.5 space-x-2">
                <span class="text-xs font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200/60">Active Notices</span>
                <a href="{{ route('student.announcements') }}" class="text-xs text-rose-600 hover:text-rose-700 font-semibold">View All &rarr;</a>
            </div>
        </div>

        <!-- Academic Standing Card -->
        <div class="group bg-white/90 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_30px_-10px_rgba(59,130,246,0.12)] hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Academic Standing</span>
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-500/10 to-blue-500/20 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
            <p class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-3">Active Scholar</p>
            <div class="flex items-center mt-2.5 space-x-2">
                <span class="text-xs font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200/60">Term 1 Enrolled</span>
                <a href="{{ route('student.profile') }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold">Profile &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Campus Announcements Dashboard Section -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-ping"></span>
                    <span class="text-xs font-bold uppercase tracking-wider text-purple-700 bg-purple-50 px-2.5 py-0.5 rounded-full border border-purple-200/60">Official Bulletin</span>
                </div>
                <h3 class="font-display text-lg sm:text-xl font-bold text-slate-900 tracking-tight">New Announcements & Notices</h3>
                <p class="text-xs text-slate-400 mt-0.5">Stay updated with class schedules, examination notices, and campus news</p>
            </div>
            <a href="{{ route('student.announcements') }}" class="px-4 py-2.5 rounded-2xl bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold text-xs border border-purple-200/80 transition-all duration-200 flex items-center gap-1.5 self-start sm:self-auto">
                <span>View All Announcements ({{ $announcementsCount }})</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            @forelse($announcements as $ann)
            <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200/70 hover:border-purple-300 hover:bg-purple-50/20 hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $ann->priority === 'urgent' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($ann->priority === 'important' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-purple-50 text-purple-700 border border-purple-200') }}">
                            {{ $ann->priority === 'urgent' ? '🔥 Urgent Notice' : ($ann->priority === 'important' ? '⚡ Important' : '📌 Notice') }}
                        </span>
                        <span class="text-[11px] text-slate-400 font-medium">
                            {{ \Carbon\Carbon::parse($ann->published_at)->format('M d, Y') }}
                        </span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm sm:text-base tracking-tight line-clamp-1">{{ $ann->title }}</h4>
                    <p class="text-xs text-slate-500 mt-1.5 line-clamp-2 leading-relaxed">{{ $ann->content }}</p>
                </div>
                
                <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                    <span class="text-slate-400 text-[11px]">By {{ $ann->author_name ?? 'Faculty Office' }}</span>
                    <button @click="viewNotice = { title: {{ json_encode($ann->title) }}, content: {{ json_encode($ann->content) }}, priority: {{ json_encode($ann->priority) }}, author: {{ json_encode($ann->author_name ?? 'Faculty Office') }}, date: {{ json_encode(\Carbon\Carbon::parse($ann->published_at)->format('M d, Y')) }} }" 
                            class="font-bold text-purple-600 hover:text-purple-700 text-xs flex items-center gap-1">
                        <span>Read Full Notice</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full py-10 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                <p class="text-xs text-slate-400 font-medium">No new announcements at this time.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Dual Columns: Today's Schedule & Homework Tasks -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Today's Schedule -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] border border-slate-200/80 p-6 sm:p-7">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="font-display text-base sm:text-lg font-bold text-slate-900">Today's Class Schedule &bull; {{ $dayOfWeek }}</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Assigned lecture periods & classrooms</p>
                </div>
                <a href="{{ route('student.timetable') }}" class="text-xs font-bold text-purple-600 hover:text-purple-700 flex items-center gap-1">
                    <span>Full Week</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <div class="space-y-3.5">
                @forelse($todaySchedule as $period)
                <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/70 flex items-center justify-between hover:border-purple-300 hover:bg-white hover:shadow-md transition-all duration-200">
                    <div class="flex items-center space-x-3.5">
                        <div class="px-3 py-2 bg-purple-50 text-purple-800 border border-purple-200/60 rounded-xl text-xs font-mono font-bold shadow-2xs">
                            {{ substr($period->start_time, 0, 5) }} - {{ substr($period->end_time, 0, 5) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">{{ $period->subject->name }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $period->teacher->user->name ?? 'Faculty' }} &bull; {{ $period->room_number ?? 'Room 101' }}</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-xl bg-white border border-slate-200/80 text-xs font-semibold text-slate-700 shadow-2xs">
                        Period
                    </span>
                </div>
                @empty
                <p class="text-xs text-slate-400 text-center py-8">No class periods scheduled for today.</p>
                @endforelse
            </div>
        </div>

        <!-- Homework & Coursework Due -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] border border-slate-200/80 p-6 sm:p-7">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="font-display text-base sm:text-lg font-bold text-slate-900">Current Homework</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Tasks, deadlines, and grades</p>
                </div>
                <a href="{{ route('student.homework') }}" class="text-xs font-bold text-purple-600 hover:text-purple-700 flex items-center gap-1">
                    <span>All Tasks</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <div class="space-y-3.5">
                @forelse($assignments as $hw)
                @php
                    $mySub = $hw->submissions->first();
                @endphp
                <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/70 hover:border-purple-300 hover:bg-white hover:shadow-md transition-all duration-200">
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

    <!-- Student Read Announcement Detail Modal -->
    <div x-cloak x-show="viewNotice !== null" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md">
        <div @click.away="viewNotice = null" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 relative">
            <template x-if="viewNotice">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider"
                                  :class="viewNotice.priority === 'urgent' ? 'bg-rose-50 text-rose-700 border border-rose-200' : (viewNotice.priority === 'important' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-purple-50 text-purple-700 border border-purple-200')"
                                  x-text="viewNotice.priority === 'urgent' ? '🔥 Urgent Notice' : (viewNotice.priority === 'important' ? '⚡ Important Notice' : '📌 Notice')">
                            </span>
                            <span class="text-xs text-slate-400" x-text="viewNotice.date"></span>
                        </div>
                        <button @click="viewNotice = null" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <h3 class="font-display text-xl font-bold text-slate-900 tracking-tight" x-text="viewNotice.title"></h3>
                    <p class="text-xs text-slate-500 mt-1.5 mb-4">
                        Published by <strong class="text-slate-800" x-text="viewNotice.author"></strong>
                    </p>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line" x-text="viewNotice.content">
                    </div>

                    <div class="mt-5 flex justify-end">
                        <button type="button" @click="viewNotice = null" class="px-5 py-2.5 rounded-2xl bg-purple-600 text-white font-bold text-xs hover:bg-purple-700 transition active:scale-95 shadow-md shadow-purple-600/30">Close Notice</button>
                    </div>
                </div>
            </template>
        </div>
    </div>

</div>
@endsection
