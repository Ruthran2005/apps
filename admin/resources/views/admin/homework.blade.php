@extends('layouts.app')

@section('title', 'Homework Oversight (View Only) — Admin Workspace')
@section('header_title', 'Homework & Coursework Oversight')

@section('content')
<div class="space-y-6">

    <!-- Header Banner Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Homework & Tasks Oversight</h2>
                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold uppercase tracking-wider border border-slate-200">View Only</span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500">Administrative audit of all assignments, teacher uploads, student submission rates, and evaluation marks.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-2xl bg-indigo-50/80 text-indigo-800 font-bold text-xs border border-indigo-200/60 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                {{ $assignments->total() }} Published Assignments
            </span>
        </div>
    </div>

    <!-- Filter by Class -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.homework') }}" class="flex flex-wrap items-center gap-3">
            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Filter Standard:</span>
            <select name="class_id" onchange="this.form.submit()" class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 focus:outline-none focus:bg-white shadow-2xs transition">
                <option value="">All Standards</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </form>

        <span class="text-xs text-slate-400 font-medium">
            Read-only administrative inspection view
        </span>
    </div>

    <!-- Assignments List -->
    <div class="space-y-6">
        @forelse($assignments as $assign)
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] overflow-hidden transition-all duration-300 hover:shadow-[0_15px_30px_-8px_rgba(99,102,241,0.1)]"
             x-data="{ showSubmissions: true }">
            
            <!-- Assignment Top Bar -->
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 pb-5 border-b border-slate-100">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-3 py-1 rounded-xl bg-indigo-50 text-indigo-800 text-xs font-bold border border-indigo-200/70 shadow-2xs">
                            {{ $assign->subject->name ?? 'Subject' }}
                        </span>
                        <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold">
                            {{ $assign->schoolClass->name ?? 'Class' }} &bull; Sec {{ $assign->section->name ?? 'A' }}
                        </span>
                        <span class="px-2.5 py-1 rounded-xl bg-slate-50 text-slate-600 text-xs font-medium border border-slate-200/50">
                            Instructor: <strong class="text-slate-800">{{ $assign->teacher->user->name ?? 'Faculty' }}</strong>
                        </span>
                        <span class="px-2.5 py-1 rounded-xl bg-slate-50 text-slate-500 text-xs font-mono border border-slate-200/50">
                            Max: {{ $assign->total_points }} pts
                        </span>
                    </div>
                    <h3 class="font-display text-lg sm:text-xl font-bold text-slate-900 tracking-tight">{{ $assign->title }}</h3>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    @if($assign->attachment)
                    <a href="{{ route('admin.homework.download', ['type' => 'homework', 'id' => $assign->id]) }}" 
                       class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs border border-slate-200 transition active:scale-95 flex items-center gap-1.5 shadow-2xs">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Question File</span>
                    </a>
                    @endif

                    <div class="text-right pl-3 border-l border-slate-200">
                        <span class="text-[11px] text-slate-400 block font-medium">Due Date</span>
                        <span class="text-xs font-bold text-slate-800 font-mono">{{ $assign->due_date->format('M d, Y') }}</span>
                    </div>

                    <button @click="showSubmissions = !showSubmissions" class="p-2 rounded-xl hover:bg-slate-100 text-slate-400 transition" title="Toggle submissions">
                        <svg class="w-5 h-5 transition-transform" :class="showSubmissions ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Description -->
            <p class="text-xs sm:text-sm text-slate-600 mt-4 leading-relaxed">{{ $assign->description }}</p>

            <!-- Submissions Audit Tray -->
            <div x-show="showSubmissions" class="mt-6 pt-5 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Student Turn-ins ({{ $assign->submissions->count() }})</span>
                    <span class="text-xs font-semibold text-slate-400">Read-only oversight</span>
                </div>

                @if($assign->submissions->isNotEmpty())
                <div class="overflow-x-auto rounded-2xl border border-slate-200/70">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50/80 text-[10px] uppercase font-extrabold tracking-wider text-slate-400 border-b border-slate-200/60">
                            <tr>
                                <th class="px-4 py-3">Student</th>
                                <th class="px-4 py-3">Submitted At</th>
                                <th class="px-4 py-3">File Upload</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Awarded Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($assign->submissions as $sub)
                            <tr class="hover:bg-slate-50/60">
                                <td class="px-4 py-3">
                                    <span class="font-bold text-slate-800">{{ $sub->student->user->name ?? 'Student' }}</span>
                                    <span class="text-slate-400 block text-[10px] font-mono">{{ $sub->student->admission_number ?? '' }}</span>
                                </td>
                                <td class="px-4 py-3 font-mono text-slate-500">
                                    {{ $sub->submitted_at ? $sub->submitted_at->format('M d, h:i A') : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($sub->file_path)
                                        <a href="{{ route('admin.homework.download', ['type' => 'submission', 'id' => $sub->id]) }}" class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            <span>Download File</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic">No file attached</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($sub->status === 'graded')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Graded</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Pending Review</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-bold">
                                    @if($sub->status === 'graded')
                                        <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">{{ $sub->marks_awarded }} / {{ $assign->total_points }} pts</span>
                                    @else
                                        <span class="text-slate-400 font-normal italic">Ungraded</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-xs text-slate-400 italic py-2">No student submissions recorded yet.</p>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-16 text-center text-slate-400 border border-slate-200/80">
            No coursework assignments found.
        </div>
        @endforelse
    </div>

    @if($assignments->hasPages())
    <div class="bg-white/90 backdrop-blur-sm p-4 rounded-3xl border border-slate-200/80">
        {{ $assignments->links() }}
    </div>
    @endif

</div>
@endsection
