@extends('layouts.app')

@section('title', 'Events & Holiday Calendar - SchoolNexus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-slate-900 tracking-tight">Events & Holiday Calendar</h1>
            <p class="text-sm text-slate-500 mt-1">Official institutional academic calendar, annual celebrations, examinations, and official holidays.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="$dispatch('open-modal-event')" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Event / Holiday</span>
            </button>
        </div>
    </div>

    <!-- Calendar Timeline & Official Holidays Split View -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Main Events Feed (8 cols) -->
        <div class="lg:col-span-8 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6">
                <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    <span>Upcoming Institutional Events & Academic Milestones</span>
                </h2>
                
                <div class="space-y-4">
                    @forelse($events as $ev)
                    <div class="p-4 rounded-xl border border-slate-100 hover:border-slate-200 transition bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-12 h-12 rounded-xl flex flex-col items-center justify-center shrink-0 
                                {{ $ev->is_holiday ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-indigo-50 text-indigo-600 border border-indigo-200' }}">
                                <span class="text-[10px] font-bold uppercase">{{ Carbon\Carbon::parse($ev->start_date)->format('M') }}</span>
                                <span class="text-base font-extrabold leading-none">{{ Carbon\Carbon::parse($ev->start_date)->format('d') }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-bold text-slate-900">{{ $ev->title }}</h3>
                                    @if($ev->is_holiday)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700">Official Holiday</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-200 text-slate-700 capitalize">{{ $ev->event_type }}</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 mt-1">{{ $ev->description ?? 'Academic event on campus.' }}</p>
                                <div class="flex items-center gap-4 mt-2 text-[11px] text-slate-400 font-medium">
                                    <span>Location: {{ $ev->location ?? 'Campus Grounds' }}</span>
                                    <span>&bull;</span>
                                    <span class="capitalize">Audience: {{ $ev->audience }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end">
                            <form action="{{ route('admin.events.destroy', $ev->id) }}" method="POST" onsubmit="return confirm('Remove event from calendar?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold p-1.5 hover:bg-rose-50 rounded-lg transition">Remove</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-slate-400 text-sm">No events currently scheduled.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Official Holidays Summary (4 cols) -->
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h2 class="text-base font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    <span>Approved School Holidays</span>
                </h2>
                <p class="text-xs text-slate-500 mb-4">Official closure dates recognized by the academic board.</p>

                <div class="divide-y divide-slate-100">
                    @forelse($holidays as $h)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-bold text-slate-900">{{ $h->title }}</div>
                            <div class="text-xs text-rose-600 font-medium mt-0.5">
                                {{ Carbon\Carbon::parse($h->start_date)->format('D, M d, Y') }}
                            </div>
                        </div>
                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-rose-50 text-rose-700 border border-rose-200">Closed</span>
                    </div>
                    @empty
                    <div class="py-4 text-center text-slate-400 text-xs">No holidays recorded.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add Event / Holiday -->
<div x-data="{ open: false }" @open-modal-event.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Add Event or Holiday</h3>
            <p class="text-xs text-slate-500 mb-4">Schedule a date on the institutional calendar.</p>
            <form action="{{ route('admin.events.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Event Title</label>
                    <input type="text" name="title" required placeholder="e.g. Annual Sports Day or Winter Break" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Event Type</label>
                        <select name="event_type" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="event">Campus Event</option>
                            <option value="holiday">Official Holiday</option>
                            <option value="sports">Athletics / Sports</option>
                            <option value="exam">Exam Schedule</option>
                            <option value="celebration">Celebration</option>
                            <option value="meeting">PTM / Board Meeting</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Target Audience</label>
                        <select name="audience" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="all">Everyone (All)</option>
                            <option value="students">Students</option>
                            <option value="teachers">Faculty Only</option>
                            <option value="parents">Parents</option>
                        </select>
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
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Location / Venue</label>
                    <input type="text" name="location" placeholder="Auditorium, Main Grounds..." class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Description / Notes</label>
                    <textarea name="description" rows="2" placeholder="Details of the event..." class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_holiday" id="is_holiday" value="1" class="rounded text-rose-600 focus:ring-rose-500">
                    <label for="is_holiday" class="text-xs font-semibold text-slate-700">Mark as Official Holiday (Campus Closed)</label>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">Publish Event</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
