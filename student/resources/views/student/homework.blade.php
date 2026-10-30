@extends('layouts.app')

@section('title', 'Homework — Student Workspace')
@section('header_title', 'Homework & Coursework')

@section('content')
<div class="space-y-6" x-data="{ submitModal: false, activeHw: { subject: {}, teacher: { user: {} } } }">

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Homework & Tasks</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Review assignments, download question files, upload your answers, and check your grading results and feedback.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-2xl bg-purple-50 text-purple-800 font-semibold text-xs border border-purple-200/60 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                {{ $assignments->count() }} Coursework {{ \Illuminate\Support\Str::plural('Assignment', $assignments->count()) }}
            </span>
        </div>
    </div>

    <!-- Assignments Cards List -->
    <div class="space-y-6">
        @forelse($assignments as $hw)
        @php
            $mySub = $hw->submissions->first();
            $isGraded = $mySub && $mySub->status === 'graded';
            $isSubmitted = $mySub !== null;
            $isPastDue = \Carbon\Carbon::now()->isAfter($hw->due_date);
        @endphp
        <div class="bg-white rounded-3xl p-6 sm:p-7 border {{ $isGraded ? 'border-emerald-200/90' : ($isSubmitted ? 'border-purple-200/90' : 'border-slate-200/70') }} shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] overflow-hidden transition-all duration-300 hover:shadow-lg">
            
            <!-- Assignment Top Bar -->
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 pb-5 border-b border-slate-100">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-3 py-1 rounded-xl bg-purple-50 text-purple-800 text-xs font-bold border border-purple-200/70">
                            {{ $hw->subject->name ?? 'Subject' }}
                        </span>
                        <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold">
                            Faculty: {{ $hw->teacher->user->name ?? 'Instructor' }}
                        </span>
                        <span class="px-2.5 py-1 rounded-xl bg-slate-50 text-slate-500 text-xs font-mono">
                            Max: {{ $hw->total_points }} pts
                        </span>
                    </div>
                    <h3 class="font-display text-lg sm:text-xl font-bold text-slate-900 tracking-tight">{{ $hw->title }}</h3>
                </div>

                <!-- Status Badges -->
                <div class="flex flex-wrap items-center gap-3">
                    @if($isGraded)
                        <div class="px-4 py-2 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-right">
                            <span class="text-[10px] font-bold uppercase tracking-wider block text-emerald-600">Evaluated Score</span>
                            <span class="font-display text-lg font-extrabold text-emerald-700">{{ $mySub->marks_awarded }} / {{ $hw->total_points }} pts</span>
                        </div>
                    @elseif($isSubmitted)
                        <div class="px-4 py-2 rounded-2xl bg-purple-50 border border-purple-200/80 text-purple-800 text-right">
                            <span class="text-[10px] font-bold uppercase tracking-wider block text-purple-600">Status</span>
                            <span class="text-xs font-bold text-purple-700">Turned In (Pending Grade)</span>
                        </div>
                    @else
                        <div class="px-4 py-2 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-800 text-right">
                            <span class="text-[10px] font-bold uppercase tracking-wider block text-amber-600">Status</span>
                            <span class="text-xs font-bold text-amber-700">Pending Submission</span>
                        </div>
                    @endif

                    <div class="text-right pl-2 border-l border-slate-200">
                        <span class="text-[11px] text-slate-400 block font-medium">Due Date</span>
                        <span class="text-xs font-bold {{ $isPastDue ? 'text-rose-600' : 'text-slate-800' }}">
                            {{ $hw->due_date->format('M d, Y - h:i A') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Assignment Description -->
            <div class="mt-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Instructions</h4>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $hw->description }}</p>
            </div>

            <!-- Homework Attachment File Download (if teacher attached one) -->
            @if($hw->attachment)
            <div class="mt-4 p-3.5 rounded-2xl bg-slate-50/80 border border-slate-200/70 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs">
                        📎
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-800">Teacher Attachment File</p>
                        <p class="text-[11px] text-slate-400">Download assignment questions & reference material</p>
                    </div>
                </div>
                <a href="{{ route('student.homework.download', ['type' => 'homework', 'id' => $hw->id]) }}" 
                   class="px-4 py-2 rounded-xl bg-white hover:bg-purple-50 text-purple-700 font-bold text-xs border border-purple-200 shadow-2xs transition active:scale-95 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Download File</span>
                </a>
            </div>
            @endif

            <!-- Result & Submission Details (If already submitted) -->
            @if($isSubmitted)
            <div class="mt-5 p-5 rounded-2xl bg-gradient-to-r from-slate-50 to-purple-50/40 border border-slate-200/80 space-y-3">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200/60">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">My Answer Submission</span>
                        <span class="text-[11px] text-slate-400">&bull; Submitted {{ $mySub->submitted_at->diffForHumans() }}</span>
                    </div>
                    @if($mySub->file_attachment)
                    <a href="{{ route('student.homework.download', ['type' => 'submission', 'id' => $mySub->id]) }}" 
                       class="inline-flex items-center gap-1 text-xs font-bold text-purple-600 hover:text-purple-800">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download My Submitted File</span>
                    </a>
                    @endif
                </div>

                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed italic">
                    "{{ $mySub->submission_text }}"
                </p>

                <!-- Graded Result and Teacher Feedback -->
                @if($isGraded)
                <div class="mt-3 p-4 rounded-xl bg-emerald-50/90 border border-emerald-200/80">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase text-emerald-800 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span>Teacher Evaluation & Result</span>
                        </span>
                        <span class="text-xs font-extrabold text-emerald-900 bg-emerald-100 px-2.5 py-0.5 rounded-lg">
                            Score: {{ $mySub->marks_awarded }} / {{ $hw->total_points }}
                        </span>
                    </div>
                    <p class="text-xs text-emerald-900 mt-2 font-medium leading-relaxed">
                        {{ $mySub->feedback ?? 'Work checked and approved by subject instructor.' }}
                    </p>
                </div>
                @endif
            </div>
            @endif

            <!-- Action Bar (Upload Answer / Re-submit) -->
            <div class="mt-5 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                <span class="text-xs text-slate-400">
                    {{ $isGraded ? 'Evaluation completed' : ($isSubmitted ? 'You can update your submission anytime before grading' : 'Answer submission is open') }}
                </span>

                <button @click="activeHw = {{ json_encode($hw) }}; submitModal = true;" 
                        class="px-5 py-2.5 rounded-2xl {{ $isSubmitted ? 'bg-slate-100 hover:bg-purple-50 text-purple-700 border border-slate-200' : 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white shadow-md shadow-purple-600/30' }} font-bold text-xs sm:text-sm transition active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span>{{ $isSubmitted ? 'Re-upload / Update Answer' : 'Upload Answer' }}</span>
                </button>
            </div>

        </div>
        @empty
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
            <div class="w-16 h-16 rounded-3xl bg-purple-50 text-purple-600 mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="font-display font-bold text-base text-slate-900">No Homework Assigned</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">There are currently no outstanding assignments or homework tasks assigned for your class section.</p>
        </div>
        @endforelse
    </div>

    <!-- Upload Answer Modal -->
    <div x-cloak x-show="submitModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md">
        <div @click.away="submitModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                <div>
                    <h3 class="font-display text-lg font-bold text-slate-900">Upload Homework Answer</h3>
                    <p class="text-xs text-slate-500" x-text="activeHw?.title || 'Assignment'"></p>
                </div>
                <button @click="submitModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" :action="'/homework/' + (activeHw?.id || 0) + '/submit'" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Written Solution / Notes</label>
                    <textarea name="submission_text" rows="4" placeholder="Write your answer, derivation, code snippet, or summary of your work..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:outline-none transition"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Attach Answer Document (PDF, Word, Scan, Image)</label>
                    <input type="file" name="attachment" class="w-full px-3 py-2 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition">
                    <p class="text-[11px] text-slate-400 mt-1">Maximum file size: 10MB. PDF, DOCX, PNG, JPG allowed.</p>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-3">
                    <button type="button" @click="submitModal = false" class="px-5 py-2.5 rounded-2xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold text-xs shadow-md shadow-purple-600/30">Upload & Submit Answer</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
