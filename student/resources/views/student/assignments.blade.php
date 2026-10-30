@extends('layouts.app')

@section('title', 'My Homework & Assignments')
@section('header_title', 'Coursework & Homework Portal')

@section('content')
<div class="space-y-6" x-data="{ submitModal: false, activeAssign: {} }">

    <div class="flex items-center justify-between">
        <p class="text-xs text-slate-500">View tasks set by your course instructors and turn in your solutions.</p>
        <span class="text-xs font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-full border border-purple-200">
            {{ $assignments->count() }} Coursework Items
        </span>
    </div>

    <!-- Assignments Grid -->
    <div class="space-y-6">
        @forelse($assignments as $assign)
        @php
            $sub = $assign->submissions->first();
        @endphp
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 text-xs font-bold border border-purple-100">
                            {{ $assign->subject->name }}
                        </span>
                        <span class="text-xs text-slate-500">By: {{ $assign->teacher->user->name ?? 'Instructor' }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mt-1.5">{{ $assign->title }}</h3>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-400 block">Deadline:</span>
                    <span class="text-sm font-bold text-slate-800">{{ $assign->due_date->format('M d, Y - h:i A') }}</span>
                    <span class="text-xs text-purple-600 font-semibold block mt-0.5">{{ $assign->total_points }} Points Maximum</span>
                </div>
            </div>

            <p class="text-sm text-slate-600 mt-4 leading-relaxed">{{ $assign->description }}</p>

            <div class="mt-6 pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    @if($sub)
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $sub->status == 'graded' ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                                {{ $sub->status == 'graded' ? 'Graded: ' . $sub->marks_awarded . ' / ' . $assign->total_points : 'Submitted & Awaiting Review' }}
                            </span>
                            <span class="text-xs text-slate-400">Turned in {{ $sub->submitted_at->diffForHumans() }}</span>
                        </div>
                        @if($sub->feedback)
                            <p class="text-xs text-slate-600 mt-2 italic bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                <strong>Teacher Feedback:</strong> {{ $sub->feedback }}
                            </p>
                        @endif
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-100 text-rose-800">
                            Not Yet Submitted
                        </span>
                    @endif
                </div>

                <div>
                    <button @click="activeAssign = {{ json_encode($assign) }}; submitModal = true;" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        <span>{{ $sub ? 'Resubmit Solution' : 'Submit Assignment' }}</span>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-12 text-center text-slate-400 border border-slate-200">
            No active coursework assignments.
        </div>
        @endforelse
    </div>

    <!-- Submit Assignment Modal -->
    <div x-cloak x-show="submitModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="submitModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="submitModal = false"></div>

            <div x-show="submitModal" class="inline-block w-full max-w-lg p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Submit Coursework Solution</h3>
                        <p class="text-xs text-slate-500" x-text="activeAssign.title"></p>
                    </div>
                    <button @click="submitModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="'{{ url('/assignments') }}/' + activeAssign.id + '/submit'" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Your Submission Work / Notes / GitHub Link</label>
                        <textarea name="submission_text" rows="5" required placeholder="Type your answers, write notes, or paste link to document..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="submitModal = false" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm rounded-xl shadow-md transition">Turn In Work</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
