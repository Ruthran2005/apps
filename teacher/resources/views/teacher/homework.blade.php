@extends('layouts.app')

@section('title', 'Homework — Teacher Workspace')
@section('header_title', 'Homework & Assignment Center')

@section('content')
<div class="space-y-6" x-data="{ uploadModal: false, gradeModal: false, activeSub: { student: { user: {} } } }">

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Homework Management</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Upload coursework, review student submissions, download submitted files, and run one-click auto-grading.</p>
        </div>
        <button @click="uploadModal = true" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm shadow-lg shadow-emerald-600/30 transition-all duration-200 flex items-center justify-center space-x-2 active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Upload New Homework</span>
        </button>
    </div>

    <!-- Assignments List -->
    <div class="space-y-6">
        @forelse($assignments as $assign)
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] overflow-hidden transition-all duration-300 hover:border-emerald-200"
             x-data="{ showSubmissions: true }">
            
            <!-- Assignment Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-3 py-1 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200/70">
                            {{ $assign->subject->name ?? 'Subject' }}
                        </span>
                        <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold">
                            {{ $assign->schoolClass->name ?? 'Class' }} &bull; Sec {{ $assign->section->name ?? 'A' }}
                        </span>
                        <span class="px-2.5 py-1 rounded-xl bg-slate-50 text-slate-500 text-xs font-mono">
                            Max: {{ $assign->total_points }} pts
                        </span>
                    </div>
                    <h3 class="font-display text-lg sm:text-xl font-bold text-slate-900 tracking-tight">{{ $assign->title }}</h3>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Auto-Grade Button -->
                    <form method="POST" action="{{ route('teacher.homework.auto-grade', $assign->id) }}">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Auto-grade all submissions for this assignment?')"
                                class="px-4 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs border border-amber-200/80 transition active:scale-95 flex items-center gap-1.5 shadow-2xs">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span>⚡ Auto-Grade Submissions</span>
                        </button>
                    </form>

                    <!-- Homework File Attachment Download -->
                    @if($assign->attachment)
                    <a href="{{ route('teacher.homework.download', ['type' => 'homework', 'id' => $assign->id]) }}" 
                       class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs border border-slate-200 transition active:scale-95 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download Homework File</span>
                    </a>
                    @endif

                    <div class="text-right pl-2 border-l border-slate-200">
                        <span class="text-[11px] text-slate-400 block font-medium">Due Date</span>
                        <span class="text-xs font-bold text-slate-800">{{ $assign->due_date->format('M d, Y - h:i A') }}</span>
                    </div>
                </div>
            </div>

            <p class="text-xs sm:text-sm text-slate-600 mt-4 leading-relaxed">{{ $assign->description }}</p>

            <!-- Submissions Header & Toggle -->
            <div class="mt-6 pt-5 border-t border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <h4 class="font-display text-sm font-bold uppercase tracking-wider text-slate-700">
                            Student Submissions ({{ $assign->submissions->count() }})
                        </h4>
                        @php
                            $gradedCount = $assign->submissions->where('status', 'graded')->count();
                        @endphp
                        <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                            {{ $gradedCount }} Graded
                        </span>
                    </div>

                    <button @click="showSubmissions = !showSubmissions" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                        <span x-text="showSubmissions ? 'Collapse Submissions' : 'Expand Submissions'"></span>
                        <svg class="w-4 h-4 transform transition-transform" :class="showSubmissions ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </div>

                <!-- Submissions Table -->
                <div x-cloak x-show="showSubmissions" x-collapse>
                    @if($assign->submissions->isNotEmpty())
                    <div class="overflow-x-auto rounded-2xl border border-slate-200/70">
                        <table class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-slate-50/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200/70">
                                <tr>
                                    <th class="px-5 py-3.5">Student</th>
                                    <th class="px-5 py-3.5">Submitted On</th>
                                    <th class="px-5 py-3.5">Submission Details</th>
                                    <th class="px-5 py-3.5">Files</th>
                                    <th class="px-5 py-3.5">Status & Marks</th>
                                    <th class="px-5 py-3.5 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($assign->submissions as $sub)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-5 py-3.5 font-bold text-slate-900">
                                        <div class="flex items-center space-x-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-[10px]">
                                                {{ substr($sub->student->user->name ?? 'S', 0, 2) }}
                                            </div>
                                            <span>{{ $sub->student->user->name ?? 'Student' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-500 font-medium">
                                        {{ $sub->submitted_at ? $sub->submitted_at->format('M d, h:i A') : 'N/A' }}
                                    </td>
                                    <td class="px-5 py-3.5 max-w-xs text-slate-600">
                                        <p class="truncate">{{ $sub->submission_text ?? 'Online attachment' }}</p>
                                        @if($sub->feedback)
                                            <p class="text-[10px] text-emerald-700 italic mt-0.5 font-medium truncate">💬 {{ $sub->feedback }}</p>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @if($sub->file_attachment)
                                        <a href="{{ route('teacher.homework.download', ['type' => 'submission', 'id' => $sub->id]) }}" 
                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold text-[11px] border border-slate-200 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            <span>Download File</span>
                                        </a>
                                        @else
                                        <span class="text-slate-400 text-[11px] italic">No file</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $sub->status == 'graded' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                                {{ $sub->status }}
                                            </span>
                                            <span class="font-bold text-slate-900">
                                                {{ $sub->marks_awarded !== null ? $sub->marks_awarded . ' / ' . $assign->total_points : '—' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <button @click="activeSub = {{ json_encode($sub) }}; gradeModal = true;" 
                                                class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs transition active:scale-95 shadow-sm">
                                            {{ $sub->status == 'graded' ? 'Adjust Grade' : 'Grade' }}
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-6 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200 text-center">
                        <p class="text-xs text-slate-400 font-medium">No student submissions submitted for this homework yet.</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>
        @empty
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
            <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="font-display font-bold text-base text-slate-900">No Homework Uploaded Yet</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Get started by clicking 'Upload New Homework' to create questions and upload assignment material for students.</p>
        </div>
        @endforelse
    </div>

    <!-- Upload Homework Modal -->
    <div x-cloak x-show="uploadModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md">
        <div @click.away="uploadModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                <div>
                    <h3 class="font-display text-lg font-bold text-slate-900">Upload & Publish Homework</h3>
                    <p class="text-xs text-slate-500">Provide assignment instructions and upload files</p>
                </div>
                <button @click="uploadModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('teacher.homework.upload') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Homework Title</label>
                    <input type="text" name="title" required placeholder="e.g. Thermodynamics Problem Set 4" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Class & Section</label>
                        <select name="school_class_id" required class="w-full px-3 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Section</label>
                        <select name="section_id" required class="w-full px-3 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            @foreach($classes as $c)
                                @foreach($c->sections as $s)
                                    <option value="{{ $s->id }}">{{ $c->name }} - {{ $s->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Subject</label>
                        <select name="subject_id" required class="w-full px-3 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Total Points</label>
                        <input type="number" name="total_points" value="100" min="10" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Due Date & Time</label>
                    <input type="datetime-local" name="due_date" value="{{ now()->addDays(7)->format('Y-m-d\TH:i') }}" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Description & Guidelines</label>
                    <textarea name="description" rows="3" required placeholder="Outline task instructions, chapter references, and submission criteria..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Upload Assignment File / Document (PDF, Word, Images)</label>
                    <input type="file" name="attachment" class="w-full px-3 py-2 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>

                <div class="pt-4 flex items-center justify-end space-x-3">
                    <button type="button" @click="uploadModal = false" class="px-5 py-2.5 rounded-2xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-600/30">Upload Homework</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Manual Grade Adjustment Modal -->
    <div x-cloak x-show="gradeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md">
        <div @click.away="gradeModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="font-display text-base font-bold text-slate-900">Evaluate Submission</h3>
                    <p class="text-xs text-slate-500" x-text="activeSub?.student?.user?.name || 'Student'"></p>
                </div>
                <button @click="gradeModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" :action="'/homework/grade/' + (activeSub?.id || 0)" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Marks Awarded</label>
                    <input type="number" step="0.5" name="marks_awarded" :value="activeSub?.marks_awarded || ''" required placeholder="e.g. 95" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Teacher Feedback</label>
                    <textarea name="feedback" rows="3" :value="activeSub?.feedback || ''" placeholder="Well reasoned solutions. Good work." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end space-x-3">
                    <button type="button" @click="gradeModal = false" class="px-5 py-2.5 rounded-2xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-600/30">Save Grade</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
