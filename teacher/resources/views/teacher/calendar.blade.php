@extends('layouts.app')

@section('title', 'Events & Holiday Calendar — Faculty Workspace')
@section('header_title', 'Academic Schedule & Holiday Calendar')

@section('content')
<div class="space-y-6">

    <!-- Top Action Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/90 backdrop-blur-sm p-6 rounded-3xl border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60">Annual Calendar</span>
            </div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Events & Holiday Calendar</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Institutional academic calendar, term examination periods, gazetted public holidays, and faculty development sessions.</p>
        </div>
    </div>

    <!-- Calendar Events List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
            <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold uppercase tracking-wider {{ $event->is_holiday ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($event->event_type == 'exam' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200') }}">
                            {{ $event->is_holiday ? 'Holiday' : ucfirst($event->event_type) }}
                        </span>

                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            Audience: {{ ucfirst($event->audience) }}
                        </span>
                    </div>

                    <h3 class="font-display text-base font-bold text-slate-900 leading-snug">{{ $event->title }}</h3>

                    <div class="mt-3 flex items-center space-x-2 text-xs text-slate-500">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>{{ $event->location ?? 'Campus Wide' }}</span>
                    </div>

                    @if($event->description)
                        <p class="text-xs text-slate-600 mt-3 leading-relaxed bg-slate-50 p-3.5 rounded-2xl border border-slate-100">{{ $event->description }}</p>
                    @endif
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="font-mono font-bold text-slate-700 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ \Carbon\Carbon::parse($event->start_date)->format('M d') }}
                        @if($event->end_date && $event->end_date != $event->start_date)
                            - {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                        @else
                            , {{ \Carbon\Carbon::parse($event->start_date)->format('Y') }}
                        @endif
                    </span>

                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold {{ \Carbon\Carbon::parse($event->start_date)->isFuture() ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        @if(\Carbon\Carbon::parse($event->start_date)->isFuture())
                            In {{ \Carbon\Carbon::parse($event->start_date)->diffForHumans(null, true) }}
                        @else
                            Passed
                        @endif
                    </span>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white/70 rounded-3xl border border-dashed border-slate-300">
                <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="font-display font-bold text-slate-800 text-base">No School Events on Calendar</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Calendar events and institutional holidays will be listed here.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
