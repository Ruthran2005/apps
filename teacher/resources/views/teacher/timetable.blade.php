@extends('layouts.app')

@section('title', 'Timetable — Teacher Workspace')
@section('header_title', 'Weekly Teaching Schedule')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Faculty Timetable</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Live lecture schedules synchronized in real-time with student and administration portals.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-2xl bg-emerald-50 text-emerald-800 font-semibold text-xs border border-emerald-200/60 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Semester 1 Schedule
            </span>
        </div>
    </div>

    <!-- Timetable Days Container -->
    <div class="space-y-6">
        @foreach($days as $day)
        @php
            $isToday = \Carbon\Carbon::now()->format('l') === $day;
            $daySlots = $schedule[$day] ?? collect();
        @endphp
        <div class="bg-white rounded-3xl p-6 border {{ $isToday ? 'border-emerald-300 ring-2 ring-emerald-400/20 shadow-md' : 'border-slate-200/70' }} shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
            
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <div class="flex items-center space-x-3">
                    <div class="w-3.5 h-3.5 rounded-full {{ $isToday ? 'bg-emerald-500 ring-4 ring-emerald-100' : 'bg-slate-300' }}"></div>
                    <h3 class="font-display text-base sm:text-lg font-bold text-slate-900">{{ $day }}</h3>
                    @if($isToday)
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-500 text-white text-[10px] font-bold uppercase tracking-wider">Today</span>
                    @endif
                </div>
                <span class="text-xs font-semibold text-slate-400">
                    {{ $daySlots->count() }} {{ \Illuminate\Support\Str::plural('Period', $daySlots->count()) }}
                </span>
            </div>

            @if($daySlots->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($daySlots as $slot)
                <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/70 hover:border-emerald-300 hover:bg-emerald-50/20 hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2.5 py-1 rounded-xl bg-emerald-100/70 text-emerald-900 font-mono text-xs font-bold shadow-xs">
                                {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-lg bg-white border border-slate-200/80 text-[11px] font-semibold text-slate-600 shadow-2xs">
                                📍 {{ $slot->room_number ?? 'Room 101' }}
                            </span>
                        </div>
                        <h4 class="font-bold text-slate-900 text-sm mt-1.5">{{ $slot->subject->name ?? 'Subject' }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">{{ $slot->schoolClass->name ?? 'Class' }} &bull; Section {{ $slot->section->name ?? 'A' }}</p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">Lecture</span>
                        <a href="{{ route('teacher.attendance', ['class_id' => $slot->school_class_id, 'section_id' => $slot->section_id]) }}" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                            Take Roll &rarr;
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="py-6 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                <p class="text-xs text-slate-400 font-medium">No teaching periods scheduled for {{ $day }}.</p>
            </div>
            @endif

        </div>
        @endforeach
    </div>

</div>
@endsection
