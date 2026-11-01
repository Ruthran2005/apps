@extends('layouts.app')

@section('title', 'Parent Communication — Faculty Workspace')
@section('header_title', 'Parent Messages & Direct Communication')

@section('content')
<div class="space-y-6" x-data="{ composeModal: false }">

    <!-- Top Action Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/90 backdrop-blur-sm p-6 rounded-3xl border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-purple-500 animate-ping"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-purple-700 bg-purple-50 px-2.5 py-0.5 rounded-full border border-purple-200/60">Guardian Bridge</span>
            </div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Parent Communication & Messages</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Directly notify parents regarding academic commendations, homework alerts, discipline, and attendance feedback.</p>
        </div>
        <button @click="composeModal = true" class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/30 transition active:scale-95 flex items-center gap-2 shrink-0 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Message Parent</span>
        </button>
    </div>

    <!-- Feedback messages -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Messages Threads List -->
    <div class="space-y-4">
        @forelse($communications as $comm)
            <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] hover:shadow-md transition-all">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-2 mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-xs border border-purple-200/60 shadow-2xs">
                            {{ substr($comm->student->user->name ?? 'P', 0, 2) }}
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                Parent of {{ $comm->student->user->name ?? 'Student' }}
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $comm->priority == 'Urgent' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($comm->priority == 'Important' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
                                    {{ $comm->priority }}
                                </span>
                            </span>
                            <span class="text-[11px] text-slate-400 font-mono">Admission #{{ $comm->student->admission_number ?? 'N/A' }} &bull; Sent {{ $comm->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if(!$comm->is_read)
                            <form method="POST" action="{{ route('teacher.communications.read', $comm->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold border border-purple-200 transition">
                                    Mark Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <h4 class="font-display text-sm font-bold text-slate-900 leading-snug">{{ $comm->subject }}</h4>
                        <p class="text-xs text-slate-700 mt-1.5 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">{{ $comm->message }}</p>
                    </div>

                    @if($comm->parent_reply)
                        <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200/80 flex items-start gap-3 mt-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-[11px] font-extrabold uppercase tracking-wider text-emerald-800 mb-0.5">Parent Response</span>
                                <p class="text-xs text-emerald-950 font-medium leading-relaxed">{{ $comm->parent_reply }}</p>
                                <span class="text-[10px] text-emerald-700 mt-1 inline-block font-mono">{{ $comm->replied_at ? $comm->replied_at->format('M d, Y - h:i A') : '' }}</span>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-1.5 text-[11px] text-slate-400 italic pt-1">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Awaiting parent acknowledgement / response</span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-16 text-center bg-white/70 rounded-3xl border border-dashed border-slate-300">
                <div class="w-16 h-16 rounded-3xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <h3 class="font-display font-bold text-slate-800 text-base">No Parent Communication Records</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Send a message to parents regarding student progress, homework alerts, or commendations.</p>
            </div>
        @endforelse
    </div>

    <!-- Compose Modal -->
    <div x-cloak x-show="composeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5" @click.away="composeModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-display text-lg font-bold text-slate-900">Send Message to Parent</h3>
                <button @click="composeModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('teacher.communications.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Select Student (Parent Recipient)</label>
                    <select name="student_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                        @foreach($students as $st)
                            <option value="{{ $st->id }}">{{ $st->user->name }} &bull; {{ $st->schoolClass->name ?? '' }} (Adm: {{ $st->admission_number }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Subject</label>
                        <input type="text" name="subject" required placeholder="e.g. Mathematics Practice Notice" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Priority</label>
                        <select name="priority" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none">
                            <option value="Normal">Normal</option>
                            <option value="Important">Important</option>
                            <option value="Urgent">Urgent Alert</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Message Content</label>
                    <textarea name="message" rows="4" required placeholder="Write your observation, feedback, or meeting request for the student's parents..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="composeModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md transition active:scale-95">Send Communication</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
