@extends('layouts.app')

@section('title', 'Attendance — Teacher Workspace')
@section('header_title', 'Student Attendance Register')

@section('content')
<div class="space-y-6" x-data="{
    markAll(status) {
        document.querySelectorAll('input[type=radio]').forEach(input => {
            if (input.value === status) {
                input.checked = true;
                // Trigger change event if needed
                input.dispatchEvent(new Event('change'));
            }
        });
    }
}">

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Classroom Attendance</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Mark students as either <span class="font-bold text-emerald-600">Present</span> or <span class="font-bold text-rose-600">Absent</span>. Data instantly syncs to Student and Admin portals.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-2xl bg-emerald-50 text-emerald-800 font-semibold text-xs border border-emerald-200/60 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}
            </span>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
        <form method="GET" action="{{ route('teacher.attendance') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Date</label>
                <input type="date" name="date" value="{{ $date }}" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Class / Grade</label>
                <select name="class_id" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Section</label>
                <select name="section_id" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                    @foreach($classes as $c)
                        @foreach($c->sections as $sec)
                            <option value="{{ $sec->id }}" {{ $selectedSectionId == $sec->id ? 'selected' : '' }}>{{ $c->name }} &bull; Sec {{ $sec->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-2xl shadow-sm transition active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Load Class Roster</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Attendance Form -->
    <div class="bg-white rounded-3xl shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] border border-slate-200/70 overflow-hidden">
        
        <!-- Action bar with Quick Bulk Mark -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-display text-base sm:text-lg font-bold text-slate-900">Student Roll Call (Present / Absent)</h3>
                <p class="text-xs text-slate-500 mt-0.5">Toggle each student's status or use quick one-tap shortcuts below</p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <button type="button" @click="markAll('present')" 
                        class="px-3.5 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs border border-emerald-200 transition active:scale-95 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    <span>Mark All Present</span>
                </button>
                <button type="button" @click="markAll('absent')" 
                        class="px-3.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition active:scale-95 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <span>Mark All Absent</span>
                </button>
                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200/60">
                    {{ $students->count() }} Students
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('teacher.attendance.store') }}">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
            <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">

            <!-- Mobile-Friendly Cards on Small Screens / Table on Large Screens -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-100 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Student Name</th>
                            <th class="px-6 py-4">Admission #</th>
                            <th class="px-6 py-4">Roll</th>
                            <th class="px-6 py-4 text-center">Attendance (Present or Absent)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $st)
                        @php
                            $currentStatus = $st->attendances->first()->status ?? 'present';
                            if (!in_array($currentStatus, ['present', 'absent'])) {
                                $currentStatus = 'present';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-sm">
                                        {{ substr($st->user->name ?? 'S', 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $st->user->name ?? 'Student' }}</p>
                                        <p class="text-xs text-slate-400">{{ $st->guardian_phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-xs text-emerald-800">
                                {{ $st->admission_number ?? 'ADM' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-700">
                                #{{ $st->roll_number ?? '0' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-3">
                                    <!-- Present Radio Button -->
                                    <label class="relative flex items-center gap-2 px-4 py-2 rounded-2xl border cursor-pointer transition select-none has-[:checked]:bg-emerald-50 has-[:checked]:border-emerald-300 has-[:checked]:text-emerald-800 border-slate-200 text-slate-600 hover:bg-slate-50">
                                        <input type="radio" name="attendance[{{ $st->id }}]" value="present" {{ $currentStatus === 'present' ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                                        <span class="text-xs font-bold">Present</span>
                                    </label>
                                    
                                    <!-- Absent Radio Button -->
                                    <label class="relative flex items-center gap-2 px-4 py-2 rounded-2xl border cursor-pointer transition select-none has-[:checked]:bg-rose-50 has-[:checked]:border-rose-300 has-[:checked]:text-rose-800 border-slate-200 text-slate-600 hover:bg-slate-50">
                                        <input type="radio" name="attendance[{{ $st->id }}]" value="absent" {{ $currentStatus === 'absent' ? 'checked' : '' }} class="w-4 h-4 text-rose-600 focus:ring-rose-500">
                                        <span class="text-xs font-bold">Absent</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-medium">
                                No students registered in this class section.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View (Card List for Mobile Screens) -->
            <div class="sm:hidden p-4 space-y-3">
                @forelse($students as $st)
                @php
                    $currentStatus = $st->attendances->first()->status ?? 'present';
                    if (!in_array($currentStatus, ['present', 'absent'])) {
                        $currentStatus = 'present';
                    }
                @endphp
                <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white font-bold text-xs flex items-center justify-center">
                                {{ substr($st->user->name ?? 'S', 0, 2) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-sm">{{ $st->user->name ?? 'Student' }}</p>
                                <p class="text-[11px] text-slate-400 font-mono">Roll #{{ $st->roll_number }} &bull; {{ $st->admission_number }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-200/60">
                        <label class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl border cursor-pointer transition select-none has-[:checked]:bg-emerald-500 has-[:checked]:border-emerald-600 has-[:checked]:text-white border-slate-200 bg-white text-slate-700">
                            <input type="radio" name="attendance[{{ $st->id }}]" value="present" {{ $currentStatus === 'present' ? 'checked' : '' }} class="hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-xs font-bold">Present</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl border cursor-pointer transition select-none has-[:checked]:bg-rose-500 has-[:checked]:border-rose-600 has-[:checked]:text-white border-slate-200 bg-white text-slate-700">
                            <input type="radio" name="attendance[{{ $st->id }}]" value="absent" {{ $currentStatus === 'absent' ? 'checked' : '' }} class="hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span class="text-xs font-bold">Absent</span>
                        </label>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-400 py-6 text-center">No students found.</p>
                @endforelse
            </div>

            @if($students->isNotEmpty())
            <!-- Submit Action Footer -->
            <div class="p-5 sm:p-6 bg-slate-50/80 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <span class="text-xs text-slate-500 text-center sm:text-left">Attendance statuses will immediately reflect on the single shared database.</span>
                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-emerald-600/30 transition active:scale-95 flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    <span>Save Attendance (Present/Absent)</span>
                </button>
            </div>
            @endif
        </form>

    </div>

</div>
@endsection
