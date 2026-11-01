@extends('layouts.app')

@section('title', 'Master Timetable — Admin Workspace')
@section('header_title', 'Master Lecture Matrix & Scheduling')

@section('content')
<div class="space-y-6" x-data="{ 
    addSlotModal: false,
    selectedModalClass: '{{ $selectedClassId }}',
    allClasses: @js($classes),
    availableSections: [],
    init() {
        this.updateSections();
    },
    updateSections() {
        const found = this.allClasses.find(c => String(c.id) === String(this.selectedModalClass));
        this.availableSections = found && found.sections ? found.sections : [];
    }
}">

    <!-- Header Banner Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Master Lecture Timetable</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Schedule and manage classroom lecture matrices across all standards and academic faculty.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="addSlotModal = true" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>Schedule Period</span>
            </button>
        </div>
    </div>

    <!-- Filter by Class -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.timetable') }}" class="flex flex-wrap items-center gap-3">
            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Filter Standard:</span>
            <select name="class_id" onchange="this.form.submit()" class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 focus:outline-none focus:bg-white shadow-2xs transition">
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </form>

        <span class="text-xs font-semibold text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-200/60 shadow-2xs">
            📅 Active Semester 1 Matrix
        </span>
    </div>

    <!-- Schedule Matrix by Day -->
    <div class="space-y-6">
        @foreach($days as $day)
        @php
            $isToday = \Carbon\Carbon::now()->format('l') === $day;
            $daySlots = $schedule[$day] ?? collect();
        @endphp
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 sm:p-7 border {{ $isToday ? 'border-indigo-400 ring-4 ring-indigo-400/10 shadow-lg' : 'border-slate-200/80' }} shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] transition-all duration-300">
            
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <div class="flex items-center space-x-3">
                    <div class="w-3.5 h-3.5 rounded-full {{ $isToday ? 'bg-indigo-600 ring-4 ring-indigo-100 animate-pulse' : 'bg-slate-300' }}"></div>
                    <h3 class="font-display text-base sm:text-lg font-bold text-slate-900">{{ $day }}</h3>
                    @if($isToday)
                        <span class="px-2.5 py-0.5 rounded-full bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-[10px] font-bold uppercase tracking-wider shadow-xs">Today</span>
                    @endif
                </div>
                <span class="text-xs font-semibold text-slate-400">
                    {{ $daySlots->count() }} {{ \Illuminate\Support\Str::plural('Lecture', $daySlots->count()) }}
                </span>
            </div>

            @if($daySlots->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($daySlots as $slot)
                <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200/70 hover:border-indigo-300 hover:bg-white hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 rounded-xl bg-indigo-50 text-indigo-900 font-mono text-xs font-bold border border-indigo-200/60 shadow-2xs">
                                {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-lg bg-white border border-slate-200 text-[11px] font-semibold text-slate-600 shadow-2xs">
                                📍 {{ $slot->room_number ?? 'Room 101' }}
                            </span>
                        </div>
                        <h4 class="font-display font-bold text-slate-900 text-base mt-1">{{ $slot->subject->name ?? 'Subject' }}</h4>
                        <p class="text-xs text-slate-500 mt-1 font-medium">{{ $slot->schoolClass->name }} &bull; Section {{ $slot->section->name }}</p>
                    </div>

                    <div class="mt-4 pt-3.5 border-t border-slate-200/60 flex items-center justify-between">
                        <span class="text-xs font-bold text-indigo-700 truncate mr-2">👨‍🏫 {{ $slot->teacher->user->name ?? 'Faculty' }}</span>
                        <form method="POST" action="{{ route('admin.timetable.destroy', $slot->id) }}" onsubmit="return confirm('Are you sure you want to delete this lecture slot?');" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Period">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="py-8 text-center text-slate-400 text-xs">
                No lecture periods scheduled for {{ $day }}.
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Schedule Period Modal -->
    <div x-cloak x-show="addSlotModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="addSlotModal" class="fixed inset-0 bg-slate-950/75 backdrop-blur-md transition-opacity" @click="addSlotModal = false"></div>

            <div x-show="addSlotModal" class="inline-block w-full max-w-lg p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10 border border-slate-100 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-900">Schedule Lecture Period</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Assign subject, teacher, day, and room number.</p>
                    </div>
                    <button @click="addSlotModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.timetable.store') }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Class / Standard</label>
                            <select name="school_class_id" x-model="selectedModalClass" @change="updateSections()" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none font-semibold text-slate-800">
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Section</label>
                            <select name="section_id" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none font-semibold text-slate-800">
                                <template x-for="sec in availableSections" :key="sec.id">
                                    <option :value="sec.id" x-text="'Section ' + sec.name + (sec.room_number ? ' (' + sec.room_number + ')' : '')"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Subject</label>
                            <select name="subject_id" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none font-semibold text-slate-800">
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Faculty / Teacher</label>
                            <select name="teacher_id" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none font-semibold text-slate-800">
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}">👨‍🏫 {{ $t->user->name }} ({{ $t->employee_id }} &bull; {{ $t->specialization ?? 'Faculty' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Day of Week</label>
                            <select name="day_of_week" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none font-semibold text-slate-800">
                                @foreach($days as $day)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Start Time</label>
                            <input type="time" name="start_time" value="09:00" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">End Time</label>
                            <input type="time" name="end_time" value="10:00" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Room Number / Lab (Optional)</label>
                        <input type="text" name="room_number" placeholder="e.g. Hall 204 or Physics Lab" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="addSlotModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 transition active:scale-95">Add Period</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
