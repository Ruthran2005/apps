@extends('layouts.app')

@section('title', 'Homework — Teacher Workspace')
@section('header_title', 'Homework & Assignment Center')

@section('content')
<div class="space-y-6" x-data="{ uploadModal: false, gradeModal: false, activeSub: { student: { user: {} } } }">

    <!-- Header Banner Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Homework Management</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Upload coursework, review student submissions, download submitted files, and run one-click auto-grading.</p>
        </div>
        <button @click="uploadModal = true" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/50 transition-all duration-200 flex items-center justify-center space-x-2 active:scale-95 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Upload New Homework</span>
        </button>
    </div>

    <!-- Assignments List -->
    <div class="space-y-6">
        @forelse($assignments as $assign)
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] overflow-hidden transition-all duration-300 hover:shadow-[0_15px_30px_-8px_rgba(16,185,129,0.12)]"
             x-data="{ showSubmissions: true }">
            
            <!-- Assignment Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-3 py-1 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200/70 shadow-2xs">
                            {{ $assign->subject->name ?? 'Subject' }}
                        </span>
                        <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold">
                            {{ $assign->schoolClass->name ?? 'Class' }} &bull; Sec {{ $assign->section->name ?? 'A' }}
                        </span>
                        <span class="px-2.5 py-1 rounded-xl bg-slate-50 text-slate-500 text-xs font-mono border border-slate-200/50">
                            Max: {{ $assign->total_points }} pts
                        </span>
                        @if($assign->target_type === 'specific' && $assign->target_students)
                            @php $tgtCount = count(json_decode($assign->target_students, true) ?? []); @endphp
                            <span class="px-2.5 py-1 rounded-xl bg-purple-50 text-purple-700 text-xs font-bold border border-purple-200/70">
                                Assigned to {{ $tgtCount }} Specific {{ \Illuminate\Support\Str::plural('Student', $tgtCount) }}
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200/70">
                                Entire Class
                            </span>
                        @endif
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
                       class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs border border-slate-200 transition active:scale-95 flex items-center gap-1.5 shadow-2xs">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download Homework File</span>
                    </a>
                    @endif

                    <div class="text-right pl-3 border-l border-slate-200">
                        <span class="text-[11px] text-slate-400 block font-medium">Due Date</span>
                        <span class="text-xs font-bold text-slate-800 font-mono">{{ $assign->due_date->format('M d, Y - h:i A') }}</span>
                    </div>

                    <!-- Delete Homework Option -->
                    <form method="POST" action="{{ route('teacher.homework.destroy', $assign->id) }}" 
                          onsubmit="return confirm('Are you sure you want to delete homework \'{{ addslashes($assign->title) }}\'? This will permanently delete this homework assignment and all student submissions.');" 
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200/80 transition active:scale-95" 
                                title="Delete Homework Assignment">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>

                    <button @click="showSubmissions = !showSubmissions" class="p-2 rounded-xl hover:bg-slate-100 text-slate-400 transition" title="Toggle submissions">
                        <svg class="w-5 h-5 transition-transform" :class="showSubmissions ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Description -->
            <p class="text-xs sm:text-sm text-slate-600 mt-4 leading-relaxed">{{ $assign->description }}</p>

            <!-- Submissions Section -->
            <div x-show="showSubmissions" class="mt-6 pt-5 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Student Submissions ({{ $assign->submissions->count() }})</span>
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60 shadow-2xs">
                        {{ $assign->submissions->where('status', 'graded')->count() }} / {{ $assign->submissions->count() }} Graded
                    </span>
                </div>

                @if($assign->submissions->isNotEmpty())
                <div class="overflow-x-auto rounded-2xl border border-slate-200/70">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50/80 text-[10px] uppercase font-extrabold tracking-wider text-slate-400 border-b border-slate-200/60">
                            <tr>
                                <th class="px-4 py-3">Student</th>
                                <th class="px-4 py-3">Submitted At</th>
                                <th class="px-4 py-3">Submission File</th>
                                <th class="px-4 py-3">Answer Note</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Action / Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($assign->submissions as $sub)
                            <tr class="hover:bg-slate-50/60">
                                <td class="px-4 py-3">
                                    <span class="font-bold text-slate-900">{{ $sub->student->user->name ?? 'Student' }}</span>
                                    <span class="text-slate-400 block text-[10px] font-mono">{{ $sub->student->admission_number ?? '' }}</span>
                                </td>
                                <td class="px-4 py-3 font-mono text-slate-500">
                                    {{ $sub->submitted_at ? $sub->submitted_at->format('M d, h:i A') : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($sub->file_path)
                                        <a href="{{ route('teacher.homework.download', ['type' => 'submission', 'id' => $sub->id]) }}" 
                                           class="text-emerald-600 hover:text-emerald-800 font-bold flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            <span>Download</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic">No file attached</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-600 max-w-xs truncate">
                                    {{ $sub->content ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($sub->status === 'graded')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Graded</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Needs Review</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" 
                                            @click="activeSub = {{ json_encode($sub) }}; gradeModal = true"
                                            class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs border border-emerald-200/80 transition active:scale-95 shadow-2xs">
                                        {{ $sub->status === 'graded' ? 'Grade: ' . $sub->marks_awarded . ' pts (Edit)' : 'Grade Now' }}
                                    </button>
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
            No coursework assignments created yet.
        </div>
        @endforelse
    </div>

    <!-- Upload Homework Modal -->
    <div x-cloak x-show="uploadModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="uploadModal" class="fixed inset-0 bg-slate-950/75 backdrop-blur-md transition-opacity" @click="uploadModal = false"></div>

            <div x-show="uploadModal" class="inline-block w-full max-w-xl p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10 border border-slate-100 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-900">Upload New Homework</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Publish assignment to class roster with optional attachment.</p>
                    </div>
                    <button @click="uploadModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('teacher.homework.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Assignment Title</label>
                        <input type="text" name="title" required placeholder="e.g. Chapter 4 Thermodynamics Problem Set" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/30 focus:outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Subject</label>
                            <select name="subject_id" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Class / Section</label>
                            <select name="section_id" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                                @foreach($classes as $c)
                                    @foreach($c->sections as $sec)
                                        <option value="{{ $sec->id }}">{{ $c->name }} &bull; Sec {{ $sec->name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Submission Deadline</label>
                            <input type="datetime-local" name="due_date" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Maximum Points</label>
                            <input type="number" name="total_points" value="100" min="1" max="1000" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Instructions & Description</label>
                        <textarea name="description" rows="3" required placeholder="Describe task instructions, submission guidelines..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/30 focus:outline-none transition"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Attach Problem Sheet / PDF (Optional)</label>
                        <input type="file" name="attachment" class="w-full px-4 py-2 rounded-2xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:outline-none file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    </div>

                    <!-- Target Students (All or Specific) -->
                    <div x-data="{ targetMode: 'all' }" class="space-y-2 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Assign To Audience</label>
                        <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-700">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="target_type" value="all" x-model="targetMode" class="text-emerald-600 focus:ring-emerald-500" checked>
                                <span>Entire Class</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="target_type" value="specific" x-model="targetMode" class="text-emerald-600 focus:ring-emerald-500">
                                <span>Specific Students Only (Remedial / Custom)</span>
                            </label>
                        </div>

                        <div x-show="targetMode === 'specific'" class="pt-2 border-t border-slate-200 space-y-1.5">
                            <p class="text-[11px] font-bold text-slate-500">Check Students to Assign:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-36 overflow-y-auto pr-1">
                                @foreach($students as $st)
                                    <label class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200 text-xs hover:border-emerald-300 cursor-pointer">
                                        <input type="checkbox" name="target_students[]" value="{{ $st->id }}" class="rounded text-emerald-600 focus:ring-emerald-500">
                                        <span class="truncate font-medium text-slate-800">{{ $st->user->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="uploadModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-emerald-600/30 transition active:scale-95">Publish Homework</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Manual Grade Submission Modal -->
    <div x-cloak x-show="gradeModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="gradeModal" class="fixed inset-0 bg-slate-950/75 backdrop-blur-md transition-opacity" @click="gradeModal = false"></div>

            <div x-show="gradeModal" class="inline-block w-full max-w-md p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10 border border-slate-100 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-900">Evaluate Student Submission</h3>
                        <p class="text-xs text-slate-400 mt-0.5" x-text="'Student: ' + (activeSub.student?.user?.name || '')"></p>
                    </div>
                    <button @click="gradeModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" :action="'{{ url('homework/grade') }}/' + activeSub.id" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Marks Awarded (Points)</label>
                        <input type="number" name="marks_awarded" :value="activeSub.marks_awarded || 85" min="0" max="1000" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Teacher Feedback</label>
                        <textarea name="feedback" rows="3" placeholder="Provide constructive remarks..." :value="activeSub.feedback || ''" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/30 focus:outline-none"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="gradeModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-emerald-600/30 transition active:scale-95">Save Grade</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
