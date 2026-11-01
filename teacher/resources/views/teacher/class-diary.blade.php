@extends('layouts.app')

@section('title', 'Class Diary & Daily Notes — Faculty Workspace')
@section('header_title', 'Class Diary & Teacher Daily Journal')

@section('content')
<div class="space-y-6" x-data="{ logModal: false }">

    <!-- Top Action Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/90 backdrop-blur-sm p-6 rounded-3xl border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60">Classroom Transparency</span>
            </div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Class Diary & Lecture Log</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Record daily topics covered in class, classwork exercises, homework assigned, and guidance notes for parents.</p>
        </div>
        <button @click="logModal = true" class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/30 transition active:scale-95 flex items-center gap-2 shrink-0 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Add Diary Entry</span>
        </button>
    </div>

    <!-- Feedback messages -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Diary Entries Timeline -->
    <div class="space-y-4">
        @forelse($diaries as $entry)
            <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] hover:shadow-md transition-all">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-2 mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-xs shadow-inner border border-emerald-200/60">
                            {{ \Carbon\Carbon::parse($entry->entry_date)->format('d') }}
                            <span class="text-[9px] uppercase ml-0.5">{{ \Carbon\Carbon::parse($entry->entry_date)->format('M') }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                {{ $entry->schoolClass->name ?? 'Class' }}
                                @if($entry->section) &bull; Sec {{ $entry->section->name }} @endif
                                <span class="text-slate-300">&bull;</span>
                                <span class="text-emerald-700 font-extrabold">{{ $entry->subject->name ?? 'Subject' }}</span>
                            </span>
                            <span class="text-[11px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($entry->entry_date)->format('l, F j, Y') }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('teacher.class-diary.destroy', $entry->id) }}" onsubmit="return confirm('Delete this diary log?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 rounded-xl hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition" title="Delete Entry">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-0.5">Topic Covered</span>
                        <p class="text-sm font-bold text-slate-900 leading-snug">{{ $entry->topic_covered }}</p>
                    </div>

                    @if($entry->classwork_summary)
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-emerald-800 mb-1">Classwork Summary</span>
                            <p class="text-slate-700 leading-relaxed">{{ $entry->classwork_summary }}</p>
                        </div>
                    @endif

                    @if($entry->homework_assigned)
                        <div class="p-3 rounded-2xl bg-amber-50/70 border border-amber-200/60">
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-800 mb-1">Assigned Homework & Practice</span>
                            <p class="text-amber-950 font-medium leading-relaxed">{{ $entry->homework_assigned }}</p>
                        </div>
                    @endif

                    @if($entry->parent_note)
                        <div class="p-3 rounded-2xl bg-purple-50/60 border border-purple-200/60 flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-purple-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-purple-800 mb-0.5">Note for Parents</span>
                                <p class="text-purple-950 leading-relaxed">{{ $entry->parent_note }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-16 text-center bg-white/70 rounded-3xl border border-dashed border-slate-300">
                <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="font-display font-bold text-slate-800 text-base">No Class Diary Entries Recorded Yet</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Record today's teaching summary, exercises completed, and homework instructions.</p>
            </div>
        @endforelse
    </div>

    <!-- Add Diary Modal -->
    <div x-cloak x-show="logModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5" @click.away="logModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-display text-lg font-bold text-slate-900">Record Class Diary Entry</h3>
                <button @click="logModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('teacher.class-diary.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Date</label>
                        <input type="date" name="entry_date" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Subject</label>
                        <select name="subject_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Class</label>
                        <select name="school_class_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Section (Optional)</label>
                        <select name="section_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                            <option value="">All Sections</option>
                            @foreach($classes as $c)
                                @foreach($c->sections as $sec)
                                    <option value="{{ $sec->id }}">{{ $c->name }} - Sec {{ $sec->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Topic Covered</label>
                    <input type="text" name="topic_covered" required placeholder="e.g. Intro to Trigonometric Ratios & Identity Proofs" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Classwork Exercises / Board Work</label>
                    <textarea name="classwork_summary" rows="2" placeholder="e.g. Solved Exercise 4.2 problems 1 through 8 in class notebook..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Homework Assigned</label>
                    <textarea name="homework_assigned" rows="2" placeholder="e.g. Exercise 4.2 Q9 to Q15, memorize sine/cosine table..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Note for Parents (Optional)</label>
                    <input type="text" name="parent_note" placeholder="e.g. Ensure students bring geometry compass tomorrow..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="logModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md transition active:scale-95">Save Diary Entry</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
