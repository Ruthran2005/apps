@extends('layouts.app')

@section('title', 'Exam Duty & Invigilation — Faculty Workspace')
@section('header_title', 'Examination Duty & Invigilation Schedule')

@section('content')
<div class="space-y-6">

    <!-- Top Action Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/90 backdrop-blur-sm p-6 rounded-3xl border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-ping"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-200/60">Examination Cell</span>
            </div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Exam Duty & Invigilation Roster</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Official examination hall allocations, supervisory roles, strongroom dispatch timings, and protocol instructions.</p>
        </div>
    </div>

    <!-- Invigilation Duties Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($invigilations as $inv)
            <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold uppercase tracking-wider bg-indigo-50 text-indigo-800 border border-indigo-200">
                            {{ $inv->role }}
                        </span>
                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold uppercase tracking-wider {{ $inv->status == 'Completed' ? 'bg-slate-100 text-slate-700 border border-slate-200' : 'bg-indigo-600 text-white shadow-2xs' }}">
                            {{ $inv->status }}
                        </span>
                    </div>

                    <h3 class="font-display text-base font-bold text-slate-900 leading-snug">{{ $inv->subject_name }}</h3>
                    <p class="text-xs text-slate-500 mt-1 font-semibold flex items-center gap-2">
                        <span class="text-indigo-700">{{ $inv->exam_name }}</span>
                    </p>

                    <div class="grid grid-cols-2 gap-2 mt-4 pt-4 border-t border-slate-100">
                        <div class="p-2.5 rounded-2xl bg-slate-50">
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Exam Hall</span>
                            <span class="text-xs font-extrabold text-slate-900">{{ $inv->room_number }}</span>
                        </div>
                        <div class="p-2.5 rounded-2xl bg-slate-50">
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Time Window</span>
                            <span class="text-xs font-mono font-bold text-indigo-700">{{ $inv->time_slot }}</span>
                        </div>
                    </div>

                    @if($inv->instructions)
                        <div class="mt-4 p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-0.5">Confidential Instructions</span>
                            <p class="text-slate-700 leading-relaxed">{{ $inv->instructions }}</p>
                        </div>
                    @endif
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-mono text-[11px] font-bold flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ \Carbon\Carbon::parse($inv->exam_date)->format('l, M d, Y') }}
                    </span>

                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ \Carbon\Carbon::parse($inv->exam_date)->isFuture() ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ \Carbon\Carbon::parse($inv->exam_date)->isFuture() ? 'Scheduled' : 'Concluded' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white/70 rounded-3xl border border-dashed border-slate-300">
                <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </div>
                <h3 class="font-display font-bold text-slate-800 text-base">No Invigilation Duties Scheduled</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">You currently have no exam hall supervision duties assigned by the examination office.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
