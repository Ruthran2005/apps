@extends('layouts.app')

@section('title', 'My Classes — Teacher Workspace')
@section('header_title', 'My Assigned Classes & Sections')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Assigned Classroom Rosters</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Review student rosters, grade levels, and sections assigned to you for this academic term.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-2xl bg-emerald-50 text-emerald-800 font-semibold text-xs border border-emerald-200/60 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ count($classCards) }} Active Teaching Allocations
            </span>
        </div>
    </div>

    <!-- Class Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($classCards as $card)
        <div class="bg-white rounded-3xl border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] overflow-hidden transition-all duration-300 hover:shadow-lg hover:border-emerald-200 flex flex-col"
             x-data="{ showStudents: false }">
            
            <!-- Card Header -->
            <div class="p-6 bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-950 text-white flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-lg bg-emerald-500/20 text-emerald-300 text-[11px] font-bold uppercase tracking-wider border border-emerald-500/30">
                            {{ $card['subject']->name ?? 'General Subject' }}
                        </span>
                        <span class="px-2 py-0.5 rounded-lg bg-white/10 text-slate-200 text-[11px] font-mono">
                            {{ $card['subject']->code ?? 'SUB' }}
                        </span>
                    </div>
                    <h3 class="font-display text-xl font-bold tracking-tight text-white">
                        {{ $card['class']->name ?? 'Class' }} — Section {{ $card['section']->name ?? 'A' }}
                    </h3>
                    <p class="text-xs text-emerald-200/70 mt-1">
                        Capacity: {{ $card['section']->capacity ?? 35 }} seats &bull; Enrolled: {{ $card['count'] }} students
                    </p>
                </div>
                
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center justify-center text-white shrink-0 shadow-inner">
                    <svg class="w-6 h-6 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>

            <!-- Card Actions Bar -->
            <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <a href="{{ route('teacher.attendance', ['class_id' => $card['class']->id ?? 1, 'section_id' => $card['section']->id ?? 1]) }}" 
                       class="px-3.5 py-1.5 rounded-xl bg-white text-emerald-700 hover:bg-emerald-50 font-bold text-xs border border-emerald-200/80 shadow-sm transition active:scale-95 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span>Mark Attendance</span>
                    </a>
                    <a href="{{ route('teacher.homework') }}" 
                       class="px-3.5 py-1.5 rounded-xl bg-white text-slate-700 hover:bg-slate-100 font-bold text-xs border border-slate-200/80 shadow-sm transition active:scale-95 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>Homework</span>
                    </a>
                </div>

                <button @click="showStudents = !showStudents" 
                        class="text-xs font-bold text-slate-600 hover:text-emerald-700 flex items-center gap-1">
                    <span x-text="showStudents ? 'Hide Roster' : 'View Roster (' + {{ $card['count'] }} + ')'"></span>
                    <svg class="w-4 h-4 transform transition-transform" :class="showStudents ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>

            <!-- Student Roster Drawer / List -->
            <div x-cloak x-show="showStudents" x-collapse class="p-4 sm:p-6 bg-white border-t border-slate-100 flex-1">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Enrolled Students ({{ $card['count'] }})</h4>
                
                @if($card['students']->isEmpty())
                    <p class="text-xs text-slate-400 py-4 text-center">No students currently enrolled in this section.</p>
                @else
                    <div class="space-y-2 max-h-80 overflow-y-auto pr-1">
                        @foreach($card['students'] as $std)
                        <div class="p-3 rounded-2xl bg-slate-50/70 border border-slate-200/60 flex items-center justify-between hover:bg-emerald-50/40 hover:border-emerald-200 transition">
                            <div class="flex items-center space-x-3 min-w-0">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-sm">
                                    {{ substr($std->user->name ?? 'S', 0, 2) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $std->user->name ?? 'Student' }}</p>
                                    <p class="text-[11px] text-slate-400 font-mono">Roll: #{{ $std->roll_number ?? 'N/A' }} &bull; {{ $std->admission_number ?? 'ADM' }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200/80 shrink-0">
                                Active
                            </span>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Summary Footer -->
            <div class="p-4 bg-slate-50/50 mt-auto border-t border-slate-100 text-[11px] text-slate-400 flex items-center justify-between">
                <span>Curriculum: Standard State / CBSE</span>
                <span class="font-medium text-emerald-700">Term 1 Active</span>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200">
            <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h3 class="font-display font-bold text-base text-slate-900">No Teaching Classes Assigned</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">This teacher does not have any active class allocations configured in the shared database yet.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection
