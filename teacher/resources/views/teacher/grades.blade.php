@extends('layouts.app')

@section('title', 'Gradebook')
@section('header_title', 'Examinations & Marks Recording')

@section('content')
<div class="space-y-6" x-data="{ addGradeModal: false }">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('teacher.grades') }}" class="flex items-center gap-3">
            <label class="text-xs font-bold text-slate-500 uppercase">Subject:</label>
            <select name="subject_id" onchange="this.form.submit()" class="py-2 px-3.5 rounded-xl border border-slate-300 text-sm bg-white shadow-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500">
                @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}" {{ $selectedSubjectId == $sub->id ? 'selected' : '' }}>{{ $sub->name }} ({{ $sub->code }})</option>
                @endforeach
            </select>
        </form>

        <button @click="addGradeModal = true" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md transition flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Record Exam Marks</span>
        </button>
    </div>

    <!-- Grades Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900">Student Grade Records</h3>
            <span class="text-xs text-slate-400">Database synchronization active</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Class</th>
                        <th class="px-6 py-4">Assessment / Exam</th>
                        <th class="px-6 py-4">Score</th>
                        <th class="px-6 py-4">Letter Grade</th>
                        <th class="px-6 py-4">Teacher's Evaluation</th>
                        <th class="px-6 py-4 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($grades as $grade)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $grade->student->user->name ?? 'Student' }}</p>
                            <p class="text-xs text-slate-400 font-mono">{{ $grade->student->admission_number ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-700">
                            {{ $grade->student->schoolClass->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-800">
                            {{ $grade->exam_name }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ number_format($grade->marks_obtained, 1) }} / {{ number_format($grade->max_marks, 0) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ in_array($grade->grade_letter, ['A+', 'A']) ? 'bg-emerald-100 text-emerald-800' : (in_array($grade->grade_letter, ['B+', 'B']) ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ $grade->grade_letter }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 max-w-xs truncate">
                            {{ $grade->remarks ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-400 text-right">
                            {{ $grade->exam_date ? $grade->exam_date->format('M d, Y') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            No grade records found for this subject.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($grades->hasPages())
        <div class="p-4 bg-slate-50 border-t border-slate-100">
            {{ $grades->links() }}
        </div>
        @endif
    </div>

    <!-- Record Grade Modal -->
    <div x-cloak x-show="addGradeModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="addGradeModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addGradeModal = false"></div>

            <div x-show="addGradeModal" class="inline-block w-full max-w-lg p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Record Student Assessment</h3>
                        <p class="text-xs text-slate-500">Grades instantly reflect on the student's report card.</p>
                    </div>
                    <button @click="addGradeModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('teacher.grades.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Student</label>
                        <select name="student_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            @foreach($students as $st)
                                <option value="{{ $st->id }}">{{ $st->user->name }} ({{ $st->admission_number }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Subject</label>
                        <select name="subject_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}" {{ $selectedSubjectId == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Exam / Assessment Name</label>
                        <input type="text" name="exam_name" placeholder="e.g. Unit 3 Quiz or Term 2 Final" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Marks Obtained</label>
                            <input type="number" step="0.5" name="marks_obtained" required placeholder="85" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Total Marks</label>
                            <input type="number" step="1" name="max_marks" value="100" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Evaluation Notes / Remarks</label>
                        <textarea name="remarks" rows="3" placeholder="Commendations or areas needing improvement..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="addGradeModal = false" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-md transition">Save Grade</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
