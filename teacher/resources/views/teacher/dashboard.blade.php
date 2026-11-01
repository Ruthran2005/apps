@extends('layouts.app')

@section('title', 'Teacher Dashboard')
@section('header_title', 'Academic Faculty Workspace')

@section('content')
<div class="space-y-8">

    <!-- Teacher Welcome Hero Banner with Emerald Neon Glow -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-emerald-950 to-slate-900 p-8 sm:p-10 text-white shadow-2xl border border-emerald-900/40">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-teal-600/15 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Faculty Workspace
                </span>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                    Welcome back, {{ $teacher->user->name }}!
                </h2>
                <p class="text-emerald-200/80 text-sm mt-2 max-w-xl leading-relaxed">
                    {{ $teacher->qualification }} &bull; {{ $teacher->specialization }} &bull; Employee ID: <span class="font-mono font-bold text-white bg-emerald-900/60 px-2 py-0.5 rounded border border-emerald-700/60">{{ $teacher->employee_id }}</span>
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('teacher.attendance') }}" class="px-5 py-3 rounded-2xl bg-white text-slate-950 hover:bg-emerald-50 font-bold text-sm shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span>Mark Attendance</span>
                </a>
                <a href="{{ route('teacher.homework') }}" class="px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-lg shadow-emerald-600/40 hover:shadow-emerald-500/60 transition-all duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span>Upload Homework</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Students -->
        <div class="group bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_30px_-10px_rgba(16,185,129,0.12)] hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Enrolled Students</span>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500/10 to-emerald-500/20 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-slate-900 tracking-tight mt-4">{{ $studentsCount }}</p>
            <div class="flex items-center mt-2.5 space-x-2">
                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60">Active Roster</span>
                <span class="text-xs text-slate-400">Under instruction</span>
            </div>
        </div>

        <!-- Assignments -->
        <div class="group bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_30px_-10px_rgba(20,184,166,0.12)] hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Published Coursework</span>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-teal-500/10 to-teal-500/20 text-teal-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-slate-900 tracking-tight mt-4">{{ $assignmentsCount }}</p>
            <div class="flex items-center mt-2.5 space-x-2">
                <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-200/60">Homework & Labs</span>
                <span class="text-xs text-slate-400">Semester 1</span>
            </div>
        </div>

        <!-- Pending Submissions -->
        <div class="group bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_30px_-10px_rgba(245,158,11,0.12)] hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pending Evaluation</span>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500/10 to-amber-500/20 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
            </div>
            <p class="font-display text-3xl font-extrabold text-amber-600 tracking-tight mt-4">{{ $pendingSubmissionsCount }}</p>
            <div class="flex items-center mt-2.5 space-x-2">
                <span class="text-xs font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200/60">Action Needed</span>
                <span class="text-xs text-slate-400">Submissions awaiting grade</span>
            </div>
        </div>
    </div>

    <!-- Faculty Enterprise Controls Strip -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <!-- Leaves -->
        <a href="{{ route('teacher.leaves') }}" class="group bg-white rounded-2xl p-4 border border-slate-200/70 hover:border-emerald-300 hover:shadow-md transition-all flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div class="min-w-0">
                <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Leave Status</div>
                <div class="text-sm font-extrabold text-slate-900 truncate">
                    @if($pendingLeavesCount > 0)
                        <span class="text-amber-600">{{ $pendingLeavesCount }} Pending</span>
                    @else
                        Active / Good
                    @endif
                </div>
            </div>
        </a>

        <!-- Duty Roster -->
        <a href="{{ route('teacher.duty-roster') }}" class="group bg-white rounded-2xl p-4 border border-slate-200/70 hover:border-teal-300 hover:shadow-md transition-all flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-teal-500/10 text-teal-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <div class="min-w-0">
                <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Duty Roster</div>
                <div class="text-sm font-extrabold text-slate-900 truncate">{{ $upcomingDutiesCount }} Assigned</div>
            </div>
        </a>

        <!-- Exam Invigilation -->
        <a href="{{ route('teacher.invigilation') }}" class="group bg-white rounded-2xl p-4 border border-slate-200/70 hover:border-indigo-300 hover:shadow-md transition-all flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </div>
            <div class="min-w-0">
                <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Exam Duties</div>
                <div class="text-sm font-extrabold text-slate-900 truncate">{{ $upcomingExamsCount }} Scheduled</div>
            </div>
        </a>

        <!-- Parent Messages -->
        <a href="{{ route('teacher.communications') }}" class="group bg-white rounded-2xl p-4 border border-slate-200/70 hover:border-purple-300 hover:shadow-md transition-all flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
            <div class="min-w-0">
                <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Parent Messages</div>
                <div class="text-sm font-extrabold text-slate-900 truncate">
                    @if($unreadMessagesCount > 0)
                        <span class="text-purple-600">{{ $unreadMessagesCount }} Unread</span>
                    @else
                        Up to Date
                    @endif
                </div>
            </div>
        </a>
    </div>

    <!-- Dual Columns: Teaching Schedule & Submissions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Today's Schedule -->
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] border border-slate-200/70 p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="font-display text-base font-bold text-slate-900">Teaching Schedule &bull; {{ $dayOfWeek }}</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Assigned lecture periods & classrooms</p>
                </div>
                <a href="{{ route('teacher.timetable') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Full Week &rarr;</a>
            </div>

            <div class="space-y-3.5">
                @forelse($todaySchedule as $period)
                <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/60 flex items-center justify-between hover:border-emerald-300 hover:bg-white hover:shadow-md transition-all duration-200">
                    <div class="flex items-center space-x-3.5">
                        <div class="px-3 py-2 bg-emerald-50 text-emerald-800 border border-emerald-200/60 rounded-xl text-xs font-mono font-bold shadow-sm">
                            {{ substr($period->start_time, 0, 5) }} - {{ substr($period->end_time, 0, 5) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">{{ $period->subject->name }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $period->schoolClass->name }} &bull; {{ $period->section->name }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-xl bg-white border border-slate-200/80 text-xs font-semibold text-slate-700 shadow-sm">
                        {{ $period->room_number ?? 'Room TBA' }}
                    </span>
                </div>
                @empty
                <p class="text-xs text-slate-400 text-center py-8">No teaching sessions scheduled for this day.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Submissions -->
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] border border-slate-200/70 p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="font-display text-base font-bold text-slate-900">Submissions Requiring Review</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Turned in student homework</p>
                </div>
                <a href="{{ route('teacher.homework') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">All Homework &rarr;</a>
            </div>

            <div class="space-y-3.5">
                @forelse($recentSubmissions as $sub)
                <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/60 hover:border-emerald-300 hover:bg-white hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-bold text-slate-900 text-xs">{{ $sub->student->user->name }}</span>
                        <span class="text-[11px] text-slate-400">{{ $sub->submitted_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-emerald-800 font-semibold">{{ $sub->assignment->title }}</p>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $sub->status == 'graded' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                            {{ $sub->status == 'graded' ? 'Score: ' . $sub->marks_awarded . ' pts' : 'Needs Grade' }}
                        </span>
                        <a href="{{ route('teacher.homework') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Evaluate &rarr;</a>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-400 text-center py-8">All student submissions are up to date.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
