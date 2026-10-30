@extends('layouts.app')

@section('title', 'Assignments')
@section('header_title', 'Coursework & Student Submissions')

@section('content')
<div class="space-y-8" x-data="{ addAssignModal: false, gradeModal: false, activeSub: {} }">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <p class="text-xs text-slate-500">Manage homework, projects, and grade submitted student files.</p>
        <button @click="addAssignModal = true" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md transition flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Create Assignment</span>
        </button>
    </div>

    <!-- Active Assignments List -->
    <div class="space-y-6">
        @forelse($assignments as $assign)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-100">
                            {{ $assign->subject->name }}
                        </span>
                        <span class="text-xs font-semibold text-slate-500">
                            {{ $assign->schoolClass->name }} - {{ $assign->section->name }}
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mt-1.5">{{ $assign->title }}</h3>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-400 block">Due Date:</span>
                    <span class="text-sm font-bold text-slate-800">{{ $assign->due_date->format('M d, Y - h:i A') }}</span>
                    <span class="text-xs text-emerald-600 font-semibold block mt-0.5">{{ $assign->total_points }} Points Max</span>
                </div>
            </div>

            <p class="text-xs text-slate-600 mt-4 leading-relaxed">{{ $assign->description }}</p>

            <!-- Submissions Table for this assignment -->
            <div class="mt-6 pt-4 border-t border-slate-100">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                    Student Submissions ({{ $assign->submissions->count() }})
                </h4>

                @if($assign->submissions->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 font-semibold uppercase">
                            <tr>
                                <th class="px-4 py-2.5">Student</th>
                                <th class="px-4 py-2.5">Submitted At</th>
                                <th class="px-4 py-2.5">Submission Note</th>
                                <th class="px-4 py-2.5">Status</th>
                                <th class="px-4 py-2.5">Marks</th>
                                <th class="px-4 py-2.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($assign->submissions as $sub)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-4 py-3 font-bold text-slate-900">
                                    {{ $sub->student->user->name }}
                                </td>
                                <td class="px-4 py-3 text-slate-500">
                                    {{ $sub->submitted_at->format('M d, h:i A') }}
                                </td>
                                <td class="px-4 py-3 max-w-xs truncate text-slate-600">
                                    {{ $sub->submission_text ?? 'Attached document' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $sub->status == 'graded' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $sub->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-bold text-slate-900">
                                    {{ $sub->marks_awarded ? number_format($sub->marks_awarded, 1) . ' / ' . $assign->total_points : 'Unmarked' }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button @click="activeSub = {{ json_encode($sub) }}; gradeModal = true;" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition">
                                        {{ $sub->status == 'graded' ? 'Update Grade' : 'Grade' }}
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-xs text-slate-400 italic">No students have submitted work for this assignment yet.</p>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-12 text-center text-slate-400 border border-slate-200">
            No coursework assignments created yet.
        </div>
        @endforelse
    </div>

    <!-- Create Assignment Modal -->
    <div x-cloak x-show="addAssignModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="addAssignModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addAssignModal = false"></div>

            <div x-show="addAssignModal" class="inline-block w-full max-w-xl p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Create New Coursework Assignment</h3>
                        <p class="text-xs text-slate-500">Students will be notified on their dashboard immediately.</p>
                    </div>
                    <button @click="addAssignModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('teacher.assignments.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Assignment Title</label>
                        <input type="text" name="title" required placeholder="e.g. Thermodynamics Chapter 4 Problem Set" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Subject</label>
                            <select name="subject_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Class & Section</label>
                            <select name="section_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                @foreach($classes as $c)
                                    @foreach($c->sections as $sec)
                                        <option value="{{ $sec->id }}">{{ $c->name }} - {{ $sec->name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            <input type="hidden" name="school_class_id" value="{{ $classes->first()->id ?? 1 }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Due Date & Time</label>
                            <input type="datetime-local" name="due_date" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Total Points</label>
                            <input type="number" name="total_points" value="100" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Instructions & Guidelines</label>
                        <textarea name="description" rows="4" required placeholder="Outline task specifications, reading chapters, or submission format..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="addAssignModal = false" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-md transition">Publish Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Grade Submission Modal -->
    <div x-cloak x-show="gradeModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="gradeModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="gradeModal = false"></div>

            <div x-show="gradeModal" class="inline-block w-full max-w-md p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Evaluate Submission</h3>
                        <p class="text-xs text-slate-500">Provide score & constructive feedback.</p>
                    </div>
                    <button @click="gradeModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="'{{ url('/assignments/grade') }}/' + activeSub.id" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Marks Awarded</label>
                        <input type="number" step="0.5" name="marks_awarded" :value="activeSub.marks_awarded" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Faculty Feedback</label>
                        <textarea name="feedback" rows="3" :value="activeSub.feedback" placeholder="Comment on student performance..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="gradeModal = false" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-md transition">Save Evaluation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
