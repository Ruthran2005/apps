@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('header_title', 'Institutional Overview')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Students Metric Card -->
        <div class="group bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_30px_-10px_rgba(99,102,241,0.12)] hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Enrolled</span>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500/10 to-indigo-500/20 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="font-display text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['total_students'] }}</p>
                <div class="flex items-center mt-2.5 space-x-2">
                    <span class="inline-flex items-center text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60">
                        100% Active
                    </span>
                    <span class="text-xs text-slate-400">All registered</span>
                </div>
            </div>
        </div>

        <!-- Teachers Metric Card -->
        <div class="group bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_30px_-10px_rgba(16,185,129,0.12)] hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Academic Faculty</span>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500/10 to-emerald-500/20 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="font-display text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['total_teachers'] }}</p>
                <div class="flex items-center mt-2.5 space-x-2">
                    <span class="inline-flex items-center text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-full">
                        STEM & Arts
                    </span>
                    <span class="text-xs text-slate-400">Certified Instructors</span>
                </div>
            </div>
        </div>

        <!-- Classes Metric Card -->
        <div class="group bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_30px_-10px_rgba(139,92,246,0.12)] hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Class Standards</span>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-violet-500/10 to-violet-500/20 text-violet-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="font-display text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['total_classes'] }} Grades</p>
                <div class="flex items-center mt-2.5 space-x-2">
                    <span class="text-xs font-bold text-violet-700 bg-violet-50 px-2 py-0.5 rounded-full border border-violet-200/60">
                        {{ $stats['total_subjects'] }} Subjects
                    </span>
                    <span class="text-xs text-slate-400">Curriculum</span>
                </div>
            </div>
        </div>

        <!-- Attendance Metric Card -->
        <div class="group bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_30px_-10px_rgba(16,185,129,0.12)] hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Attendance Rate</span>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500/10 to-emerald-500/20 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="font-display text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['attendance_rate'] }}%</p>
                <div class="flex items-center mt-2.5 space-x-2">
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60">
                        Campus-wide
                    </span>
                    <a href="{{ route('admin.attendance') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">View Logs &rarr;</a>
                </div>
            </div>
        </div>

    </div>

    <!-- Secondary ERP Fast Insight Strips -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('admin.fees') }}" class="p-4 bg-white rounded-2xl border border-slate-200/80 hover:border-emerald-300 hover:shadow-md transition group">
            <div class="flex items-center justify-between text-xs text-slate-400 font-semibold">
                <span>Fee Collection</span>
                <span class="text-emerald-600 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
            </div>
            <div class="text-xl font-bold font-display text-slate-900 mt-1">${{ number_format($stats['total_collected_fees'] ?? 0, 0) }}</div>
            <div class="text-[11px] text-emerald-600 font-medium mt-0.5">Realized Revenue</div>
        </a>

        <a href="{{ route('admin.leaves') }}" class="p-4 bg-white rounded-2xl border border-slate-200/80 hover:border-amber-300 hover:shadow-md transition group">
            <div class="flex items-center justify-between text-xs text-slate-400 font-semibold">
                <span>Pending Leaves</span>
                <span class="text-amber-600 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
            </div>
            <div class="text-xl font-bold font-display text-amber-600 mt-1">{{ $stats['pending_leaves'] ?? 0 }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">Awaiting Decision</div>
        </a>

        <a href="{{ route('admin.exams') }}" class="p-4 bg-white rounded-2xl border border-slate-200/80 hover:border-indigo-300 hover:shadow-md transition group">
            <div class="flex items-center justify-between text-xs text-slate-400 font-semibold">
                <span>Upcoming Exams</span>
                <span class="text-indigo-600 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
            </div>
            <div class="text-xl font-bold font-display text-indigo-600 mt-1">{{ $stats['upcoming_exams'] ?? 0 }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">Scheduled Sessions</div>
        </a>

        <a href="{{ route('admin.library') }}" class="p-4 bg-white rounded-2xl border border-slate-200/80 hover:border-violet-300 hover:shadow-md transition group">
            <div class="flex items-center justify-between text-xs text-slate-400 font-semibold">
                <span>Library Volumes</span>
                <span class="text-violet-600 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
            </div>
            <div class="text-xl font-bold font-display text-slate-900 mt-1">{{ $stats['total_books'] ?? 0 }}</div>
            <div class="text-[11px] text-violet-600 font-medium mt-0.5">Holding Copies</div>
        </a>
    </div>

    <!-- Administrative Command Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-slate-900 p-6 sm:p-10 text-white shadow-2xl border border-indigo-900/40">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-violet-600/15 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="max-w-xl">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-semibold mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Central Administration
                </span>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                    Institutional Command Center
                </h2>
                <p class="text-indigo-200/80 text-xs sm:text-sm mt-2 leading-relaxed">
                    Oversee school operations, enroll scholars, allocate faculty, coordinate master timetables, and audit daily attendance registers.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.students') }}" class="px-5 py-3 rounded-2xl bg-white text-slate-950 hover:bg-indigo-50 font-bold text-xs sm:text-sm shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2 active:scale-95">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span>Enroll Student</span>
                </a>
                <a href="{{ route('admin.teachers') }}" class="px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-indigo-600/40 hover:shadow-indigo-500/60 transition-all duration-200 flex items-center space-x-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    <span>Add Faculty</span>
                </a>
                <a href="{{ route('admin.attendance') }}" class="px-5 py-3 rounded-2xl bg-slate-900/80 hover:bg-slate-800 text-slate-200 font-semibold text-xs sm:text-sm border border-slate-700/80 transition-all duration-200 backdrop-blur-sm active:scale-95">
                    Attendance Export
                </a>
            </div>
        </div>
    </div>

    <!-- Dual Layout: Recent Students & Notices -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Students Table (2 Columns) -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] border border-slate-200/70 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-display text-base font-bold text-slate-900">Recently Enrolled Students</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Newly registered student admissions</p>
                </div>
                <a href="{{ route('admin.students') }}" class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-700 group">
                    <span>View Directory</span>
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/70 text-[11px] uppercase font-bold tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Student Profile</th>
                            <th class="px-6 py-4">Admission #</th>
                            <th class="px-6 py-4">Class Standard</th>
                            <th class="px-6 py-4">Guardian Details</th>
                            <th class="px-6 py-4 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80">
                        @forelse($recentStudents as $student)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-500 to-violet-500 text-white font-bold text-xs flex items-center justify-center shadow-sm">
                                        {{ substr($student->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 leading-tight">{{ $student->user->name }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $student->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono font-semibold text-xs text-indigo-600">
                                {{ $student->admission_number }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200/50">
                                    {{ $student->schoolClass->name ?? 'N/A' }} &bull; {{ $student->section->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <p class="font-semibold text-slate-800">{{ $student->guardian_name }}</p>
                                <p class="text-slate-400 mt-0.5">{{ $student->guardian_phone }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                    Active
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400">No students found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Campus Notices Card (1 Column) -->
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] border border-slate-200/70 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <div>
                        <h3 class="font-display text-base font-bold text-slate-900">Campus Bulletins</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Published notices</p>
                    </div>
                    <a href="{{ route('admin.announcements') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Manage</a>
                </div>

                <div class="space-y-3.5">
                    @forelse($announcements as $notice)
                    <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/60 hover:border-indigo-300 hover:bg-white hover:shadow-md transition-all duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $notice->priority == 'urgent' ? 'bg-rose-100 text-rose-700 border border-rose-200/50' : ($notice->priority == 'important' ? 'bg-amber-100 text-amber-700 border border-amber-200/50' : 'bg-slate-200 text-slate-700') }}">
                                {{ $notice->priority }}
                            </span>
                            <span class="text-[11px] font-medium text-slate-400">{{ $notice->published_at->format('M d') }}</span>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 leading-snug">{{ $notice->title }}</h4>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $notice->content }}</p>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 text-center py-6">No notices currently published.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.announcements') }}" class="w-full py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-2xl text-xs font-bold flex items-center justify-center transition">
                    + Publish Circular
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
