@extends('layouts.app')

@section('title', 'Exams & Marks - SchoolNexus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-slate-900 tracking-tight">Exams & Student Marks</h1>
            <p class="text-sm text-slate-500 mt-1">Manage institutional examination sessions, paper timetables, marks entry, and student report cards.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="$dispatch('open-modal-exam')" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Create Exam</span>
            </button>
            <button @click="$dispatch('open-modal-schedule')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Schedule Paper</span>
            </button>
            <button @click="$dispatch('open-modal-marks')" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/30 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Enter Marks</span>
            </button>
        </div>
    </div>

    <!-- Active Exams List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($exams as $exam)
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider 
                        {{ $exam->status === 'Published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 
                           ($exam->status === 'In-Progress' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-indigo-50 text-indigo-700 border border-indigo-200') }}">
                        {{ $exam->status }}
                    </span>
                    <span class="text-xs font-semibold text-slate-400">{{ $exam->term }} ({{ $exam->academic_year }})</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 mt-1">{{ $exam->name }}</h3>
                <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $exam->description ?? 'Standard institutional assessment session.' }}</p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                    <div>
                        <span class="text-slate-400">Duration:</span>
                        <span class="font-semibold text-slate-800">{{ Carbon\Carbon::parse($exam->start_date)->format('M d') }} - {{ Carbon\Carbon::parse($exam->end_date)->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-indigo-600">{{ $exam->schedules->count() }} Papers</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-end">
                <form action="{{ route('admin.exams.destroy', $exam->id) }}" method="POST" onsubmit="return confirm('Delete this exam session?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold p-1 hover:bg-rose-50 rounded">Delete Exam</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400">
            No exams scheduled yet. Click "Create Exam" to begin.
        </div>
        @endforelse
    </div>

    <!-- Recent Grades & Report Card Quick Access -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Recorded Student Exam Marks</h2>
                <p class="text-xs text-slate-500">Continuous assessment scores, percentage evaluations & printable report cards.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Student</th>
                        <th class="px-5 py-3.5">Examination</th>
                        <th class="px-5 py-3.5">Subject</th>
                        <th class="px-5 py-3.5">Marks Obtained</th>
                        <th class="px-5 py-3.5">Percentage</th>
                        <th class="px-5 py-3.5">Grade</th>
                        <th class="px-5 py-3.5 text-right">Report Card</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($grades as $g)
                    @php
                        $pct = $g->max_marks > 0 ? round(($g->marks_obtained / $g->max_marks) * 100, 1) : 0;
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-slate-900">{{ $g->student->user->name ?? 'Student' }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $g->student->admission_number ?? '' }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs font-semibold text-slate-800">{{ $g->exam_name }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-700 font-medium">{{ $g->subject->name ?? 'N/A' }}</td>
                        <td class="px-5 py-3.5 font-bold text-slate-900">{{ $g->marks_obtained }} / {{ $g->max_marks }}</td>
                        <td class="px-5 py-3.5 font-semibold {{ $pct >= 50 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $pct }}%</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200">
                                {{ $g->grade_letter ?? 'A' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.exams.report-card', $g->student_id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold rounded-lg transition border border-slate-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span>Report Card</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">No exam marks entered yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $grades->links() }}
        </div>
    </div>
</div>

<!-- Modal 1: Create Exam -->
<div x-data="{ open: false }" @open-modal-exam.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Create Examination Session</h3>
            <p class="text-xs text-slate-500 mb-4">Set up a new semester examination or summative test.</p>
            <form action="{{ route('admin.exams.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Exam Title</label>
                    <input type="text" name="name" required placeholder="e.g. Mid-Term Examination 2026" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Academic Term</label>
                        <select name="term" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="Term 1">Term 1</option>
                            <option value="Term 2">Term 2</option>
                            <option value="Final Term">Final Term</option>
                            <option value="Quarterly">Quarterly</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Academic Year</label>
                        <input type="text" name="academic_year" value="2026-2027" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">End Date</label>
                        <input type="date" name="end_date" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="Scheduled">Scheduled</option>
                        <option value="In-Progress">In-Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="Published">Published</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Instructions / Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Assessment scope..."></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-sm font-semibold hover:bg-slate-800">Save Exam</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Schedule Paper -->
<div x-data="{ open: false }" @open-modal-schedule.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Schedule Exam Paper</h3>
            <p class="text-xs text-slate-500 mb-4">Add a specific paper, date, hall and marks allocation.</p>
            <form action="{{ route('admin.exams.schedule.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Examination</label>
                    <select name="exam_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        @foreach($exams as $e)
                            <option value="{{ $e->id }}">{{ $e->name }} ({{ $e->term }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Class</label>
                        <select name="school_class_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Subject</label>
                        <select name="subject_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Date</label>
                        <input type="date" name="exam_date" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Start Time</label>
                        <input type="time" name="start_time" value="09:30" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">End Time</label>
                        <input type="time" name="end_time" value="12:30" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Room / Hall</label>
                        <input type="text" name="room_number" placeholder="Hall 101" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Max Marks</label>
                        <input type="number" name="max_marks" value="100" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Passing</label>
                        <input type="number" name="passing_marks" value="35" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">Add Paper</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 3: Enter Marks -->
<div x-data="{ open: false }" @open-modal-marks.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Enter Student Marks</h3>
            <p class="text-xs text-slate-500 mb-4">Record student score and automatic grade calculation.</p>
            <form action="{{ route('admin.exams.marks.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Student</label>
                    <select name="student_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                        @foreach($students as $st)
                            <option value="{{ $st->id }}">{{ $st->user->name ?? 'Student' }} ({{ $st->admission_number }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Exam Name</label>
                    <input type="text" name="exam_name" value="Mid-Term Assessment 2026" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Subject</label>
                    <select name="subject_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Marks Obtained</label>
                        <input type="number" step="0.5" name="marks_obtained" required placeholder="85.5" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Max Marks</label>
                        <input type="number" step="1" name="max_marks" value="100" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Remarks / Faculty Feedback</label>
                    <input type="text" name="remarks" placeholder="Excellent analytical clarity..." class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700">Save Grade</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
