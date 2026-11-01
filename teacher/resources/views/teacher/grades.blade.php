@extends('layouts.app')

@section('title', 'Exam Marks & Remarks — Faculty Workspace')
@section('header_title', 'Exam Evaluation & Marks Register')

@section('content')
<div class="space-y-6">

    <!-- Top Action Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/90 backdrop-blur-sm p-6 rounded-3xl border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60">Official Gradebook</span>
            </div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Enter Exam Marks & Remarks</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Record official terminal exam scores, letter grades, and personalized remarks for students.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <form method="GET" action="{{ route('teacher.grades') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Class</label>
                <select name="class_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Subject</label>
                <select name="subject_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ $selectedSubjectId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Examination Term</label>
                <input type="text" name="exam_name" value="{{ $selectedExamName }}" placeholder="e.g. Midterm Examination 2026" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
            </div>

            <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md transition active:scale-95 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                <span>Load Students</span>
            </button>
        </form>
    </div>

    <!-- Feedback messages -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Marks Entry Form Table -->
    <div class="bg-white/95 backdrop-blur-sm rounded-3xl border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] overflow-hidden">
        <form method="POST" action="{{ route('teacher.grades.store') }}">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
            <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">
            <input type="hidden" name="exam_name" value="{{ $selectedExamName }}">

            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-700">Exam: <span class="text-emerald-700">{{ $selectedExamName }}</span></span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-xs font-bold text-slate-700">Max Marks:</span>
                    <input type="number" name="max_marks" value="100" min="10" max="200" class="w-20 px-2 py-1 rounded-lg border border-slate-300 text-xs text-center font-bold">
                </div>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md shadow-emerald-600/30 transition active:scale-95 flex items-center gap-1.5 self-start sm:self-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    <span>Save All Marks & Remarks</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Student</th>
                            <th class="py-3 px-4">Admission #</th>
                            <th class="py-3 px-4 text-center">Current Grade</th>
                            <th class="py-3 px-4 w-32">Marks (Obtained)</th>
                            <th class="py-3 px-4">Teacher Remark</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $st)
                            @php
                                $existingGrade = $st->grades->first();
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0 border border-emerald-200/60">
                                            {{ substr($st->user->name ?? 'S', 0, 2) }}
                                        </div>
                                        <span class="font-bold text-slate-900">{{ $st->user->name ?? 'Student' }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-slate-500">{{ $st->admission_number ?? 'N/A' }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($existingGrade && $existingGrade->grade_letter)
                                        <span class="px-2.5 py-1 rounded-xl text-xs font-extrabold {{ in_array($existingGrade->grade_letter, ['A+', 'A']) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                            {{ $existingGrade->grade_letter }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic">Pending</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <input type="number" step="0.5" min="0" max="100" name="marks[{{ $st->id }}]" value="{{ $existingGrade ? $existingGrade->marks_obtained : '' }}" placeholder="0 - 100" class="w-full px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-center focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                                </td>
                                <td class="py-3.5 px-4">
                                    <input type="text" name="remarks[{{ $st->id }}]" value="{{ $existingGrade ? $existingGrade->remarks : '' }}" placeholder="e.g. Excellent conceptual clarity, regular homework completion..." class="w-full px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">No students enrolled in this class.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->isNotEmpty())
                <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md shadow-emerald-600/30 transition active:scale-95">
                        Save All Marks & Remarks
                    </button>
                </div>
            @endif
        </form>
    </div>

</div>
@endsection
