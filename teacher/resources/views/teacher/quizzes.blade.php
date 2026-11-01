@extends('layouts.app')

@section('title', 'Quizzes & Tests — Faculty Workspace')
@section('header_title', 'Quizzes, Diagnostic & Chapter Tests')

@section('content')
<div class="space-y-6" x-data="{ createModal: false }">

    <!-- Top Action Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/90 backdrop-blur-sm p-6 rounded-3xl border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60">Assessment Suite</span>
            </div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Quizzes & Class Tests</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Design, schedule, and administer short assessments, unit tests, and surprise quizzes for your classes.</p>
        </div>
        <button @click="createModal = true" class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/30 transition active:scale-95 flex items-center gap-2 shrink-0 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Create Quiz / Test</span>
        </button>
    </div>

    <!-- Feedback messages -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Quizzes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($quizzes as $quiz)
            <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold uppercase tracking-wider {{ $quiz->quiz_type == 'Surprise Quiz' ? 'bg-amber-50 text-amber-700 border border-amber-200' : ($quiz->quiz_type == 'Unit Test' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') }}">
                            {{ $quiz->quiz_type }}
                        </span>
                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold uppercase tracking-wider {{ $quiz->status == 'Completed' ? 'bg-slate-100 text-slate-700 border border-slate-200' : 'bg-emerald-500 text-white shadow-2xs' }}">
                            {{ $quiz->status }}
                        </span>
                    </div>

                    <h3 class="font-display text-base font-bold text-slate-900 leading-snug">{{ $quiz->title }}</h3>
                    <p class="text-xs text-slate-500 mt-1 font-semibold flex items-center gap-2">
                        <span class="text-emerald-700">{{ $quiz->subject->name ?? 'Subject' }}</span>
                        <span>&bull;</span>
                        <span>{{ $quiz->schoolClass->name ?? 'Class' }}</span>
                    </p>

                    @if($quiz->instructions)
                        <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-2xl border border-slate-100 mt-3.5 leading-relaxed">{{ $quiz->instructions }}</p>
                    @endif

                    <div class="grid grid-cols-3 gap-2 mt-4 pt-4 border-t border-slate-100 text-center">
                        <div class="p-2 rounded-xl bg-slate-50">
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Total Marks</span>
                            <span class="text-xs font-extrabold text-slate-800">{{ $quiz->total_marks }} pts</span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50">
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Pass Mark</span>
                            <span class="text-xs font-extrabold text-emerald-600">{{ $quiz->passing_marks }} pts</span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50">
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Duration</span>
                            <span class="text-xs font-extrabold text-slate-800">{{ $quiz->duration_minutes }}m</span>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-mono text-[11px] flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ \Carbon\Carbon::parse($quiz->quiz_date)->format('M d, Y') }}
                    </span>

                    <div class="flex items-center gap-2">
                        @if($quiz->status !== 'Completed')
                            <form method="POST" action="{{ route('teacher.quizzes.status', $quiz->id) }}" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="Completed">
                                <button type="submit" class="px-2.5 py-1 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-[11px] border border-emerald-200 transition">
                                    Mark Done
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('teacher.quizzes.destroy', $quiz->id) }}" onsubmit="return confirm('Delete this quiz?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-xl hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition" title="Delete Quiz">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white/70 rounded-3xl border border-dashed border-slate-300">
                <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <h3 class="font-display font-bold text-slate-800 text-base">No Quizzes or Tests Scheduled Yet</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Click the button above to schedule a class quiz or diagnostic assessment.</p>
            </div>
        @endforelse
    </div>

    <!-- Create Quiz Modal -->
    <div x-cloak x-show="createModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5" @click.away="createModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-display text-lg font-bold text-slate-900">Schedule New Quiz or Test</h3>
                <button @click="createModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('teacher.quizzes.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Test Title</label>
                    <input type="text" name="title" required placeholder="e.g. Chapter 5 Rapid Assessment" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Test Type</label>
                        <select name="quiz_type" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                            <option value="Class Test">Class Test</option>
                            <option value="Quiz">Quick Quiz</option>
                            <option value="Unit Test">Unit Test</option>
                            <option value="Surprise Quiz">Surprise Quiz</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Test Date</label>
                        <input type="date" name="quiz_date" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Target Class</label>
                        <select name="school_class_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
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

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Total Marks</label>
                        <input type="number" name="total_marks" value="25" min="5" max="200" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pass Marks</label>
                        <input type="number" name="passing_marks" value="10" min="1" max="100" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Duration (Min)</label>
                        <input type="number" name="duration_minutes" value="30" min="5" max="180" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Instructions (Optional)</label>
                    <textarea name="instructions" rows="2" placeholder="e.g. Bring scientific calculator, attempt all 4 sections..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="createModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md transition active:scale-95">Publish & Schedule</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
