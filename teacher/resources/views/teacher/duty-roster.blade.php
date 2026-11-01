@extends('layouts.app')

@section('title', 'Duty & Substitute Roster — Faculty Workspace')
@section('header_title', 'Faculty Duties, Campus Supervision & Substitutions')

@section('content')
<div class="space-y-6">

    <!-- Top Action Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/90 backdrop-blur-sm p-6 rounded-3xl border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-teal-500 animate-ping"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-teal-800 bg-teal-50 px-2.5 py-0.5 rounded-full border border-teal-200/60">Campus Operations</span>
            </div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Substitute & Duty Roster</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">View campus supervisory schedules, assembly duties, morning gate protocol, and coordinate peer substitutions.</p>
        </div>
    </div>

    <!-- Feedback messages -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Duties Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($duties as $duty)
            <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] hover:shadow-lg transition-all flex flex-col justify-between"
                 x-data="{ swapModal: false }">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold uppercase tracking-wider bg-teal-50 text-teal-800 border border-teal-200">
                            {{ $duty->duty_type }}
                        </span>
                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold uppercase tracking-wider {{ $duty->status == 'Completed' ? 'bg-slate-100 text-slate-700 border border-slate-200' : ($duty->status == 'Substituted' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-emerald-500 text-white shadow-2xs') }}">
                            {{ $duty->status }}
                        </span>
                    </div>

                    <h3 class="font-display text-base font-bold text-slate-900 leading-snug">{{ $duty->location }}</h3>
                    <p class="text-xs text-slate-500 mt-1 font-semibold flex items-center gap-2">
                        <span class="text-teal-700 font-mono">{{ $duty->time_slot }}</span>
                        <span>&bull;</span>
                        <span class="font-mono">{{ \Carbon\Carbon::parse($duty->duty_date)->format('M d, Y (D)') }}</span>
                    </p>

                    @if($duty->instructions)
                        <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-2xl border border-slate-100 mt-3.5 leading-relaxed">{{ $duty->instructions }}</p>
                    @endif

                    @if($duty->substituteTeacher)
                        <div class="mt-3.5 p-3 rounded-2xl bg-purple-50/70 border border-purple-200/70 text-xs">
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-purple-800 mb-0.5">Assigned Substitute Faculty</span>
                            <span class="font-bold text-purple-950">{{ $duty->substituteTeacher->user->name }}</span>
                            <span class="text-slate-400 block text-[11px] font-mono">{{ $duty->substituteTeacher->specialization }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-mono text-[11px]">
                        @if(\Carbon\Carbon::parse($duty->duty_date)->isFuture())
                            <span class="text-emerald-700 font-bold">Upcoming</span>
                        @else
                            Past Duty
                        @endif
                    </span>

                    @if($duty->status === 'Assigned')
                        <button @click="swapModal = true" class="px-3 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                            Request Substitute
                        </button>
                    @endif
                </div>

                <!-- Substitute Request Modal -->
                <div x-cloak x-show="swapModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl border border-slate-200 space-y-4" @click.away="swapModal = false">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-display text-base font-bold text-slate-900">Request Substitute Faculty</h3>
                            <button @click="swapModal = false" class="text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form method="POST" action="{{ route('teacher.duty-roster.swap', $duty->id) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Select Colleague to Hand Over Duty</label>
                                <select name="substitute_teacher_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 focus:outline-none">
                                    @foreach($otherTeachers as $ot)
                                        <option value="{{ $ot->id }}">{{ $ot->user->name }} &bull; {{ $ot->specialization }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <p class="text-[11px] text-slate-500">Upon submission, this duty will be reassigned and recorded in the administration roster.</p>

                            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                                <button type="button" @click="swapModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button>
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-bold text-xs shadow-md">Confirm Substitution</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white/70 rounded-3xl border border-dashed border-slate-300">
                <div class="w-16 h-16 rounded-3xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <h3 class="font-display font-bold text-slate-800 text-base">No Campus Duties Assigned</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">You are currently clear of campus supervision or morning gate roster duties.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
