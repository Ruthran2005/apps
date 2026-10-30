@extends('layouts.app')

@section('title', 'Grades & Report Card')
@section('header_title', 'Academic Performance & Report Card')

@section('content')
<div class="space-y-6">

    <!-- Overall GPA Banner -->
    <div class="bg-gradient-to-r from-purple-800 to-indigo-900 rounded-3xl p-6 text-white shadow-lg flex items-center justify-between">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-purple-200">Current Semester Performance</span>
            <h2 class="text-2xl font-bold mt-1">Official Term Evaluation</h2>
            <p class="text-xs text-purple-200/80 mt-0.5">Enrolled: {{ $student->schoolClass->name }} - {{ $student->section->name }}</p>
        </div>
        <div class="text-right">
            <span class="text-xs font-bold uppercase text-purple-300 block">Overall Average</span>
            <span class="text-4xl font-extrabold text-white">{{ $overallAvg }}%</span>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900">Graded Assessments & Exams</h3>
            <span class="text-xs text-slate-400">Validated by Course Faculty</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Assessment</th>
                        <th class="px-6 py-4">Instructor</th>
                        <th class="px-6 py-4">Marks Obtained</th>
                        <th class="px-6 py-4">Grade</th>
                        <th class="px-6 py-4">Teacher's Feedback</th>
                        <th class="px-6 py-4 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($grades as $grade)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $grade->subject->name }}</p>
                            <p class="text-xs font-mono text-purple-600 font-semibold">{{ $grade->subject->code }}</p>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-800 text-xs">
                            {{ $grade->exam_name }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600">
                            {{ $grade->teacher->user->name ?? 'Faculty' }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ number_format($grade->marks_obtained, 1) }} / {{ number_format($grade->max_marks, 0) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ in_array($grade->grade_letter, ['A+', 'A']) ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $grade->grade_letter }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 max-w-xs">
                            {{ $grade->remarks ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-400 text-right">
                            {{ $grade->exam_date ? $grade->exam_date->format('M d, Y') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            No evaluations posted yet for this student.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
