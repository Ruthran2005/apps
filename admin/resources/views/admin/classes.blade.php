@extends('layouts.app')

@section('title', 'Classes & Sections — Admin Workspace')
@section('header_title', 'Curriculum, Classes & Sections')

@section('content')
<div class="space-y-6" x-data="{ addClassModal: false, addSectionModal: false }">

    <!-- Header Banner Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Academic Classes & Cohorts</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage grade levels, division sections, seating capacity, and curriculum allocations.</p>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap">
            <button @click="addClassModal = true" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Class</span>
            </button>
            <button @click="addSectionModal = true" class="px-5 py-3 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm shadow-md transition-all active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Section</span>
            </button>
        </div>
    </div>

    <!-- Class Standards Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($classes as $c)
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] hover:shadow-[0_15px_30px_-8px_rgba(99,102,241,0.12)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-mono font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-xl border border-indigo-100/80">Grade {{ $c->numeric_grade ?? $loop->iteration }}</span>
                        <h3 class="font-display text-lg font-bold text-slate-900 mt-2">{{ $c->name }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-50 to-indigo-100/80 text-indigo-700 font-bold flex items-center justify-center text-xs border border-indigo-200/50 shadow-xs">
                        {{ $c->sections->count() }} Sec
                    </div>
                </div>

                <div class="mt-5 space-y-2.5">
                    <span class="text-[10px] uppercase font-extrabold tracking-wider text-slate-400 block">Class Sections</span>
                    @forelse($c->sections as $sec)
                    <div class="p-3.5 bg-slate-50/80 rounded-2xl flex items-center justify-between text-xs border border-slate-200/60 hover:bg-indigo-50/30 hover:border-indigo-200 transition">
                        <div>
                            <span class="font-bold text-slate-800">Section {{ $sec->name }}</span>
                            <span class="text-slate-400 block text-[11px] mt-0.5 font-mono">Capacity: {{ $sec->capacity }} seats</span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-200/60">{{ $sec->students->count() }}</span>
                            <span class="text-slate-400 text-[11px] block mt-0.5 font-medium">Enrolled</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic py-2">No sections created yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.timetable', ['class_id' => $c->id]) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">View Schedule &rarr;</a>
                    <span>&bull;</span>
                    <a href="{{ route('admin.attendance', ['class_id' => $c->id]) }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition">Attendance &rarr;</a>
                </div>
                <form method="POST" action="{{ route('admin.classes.destroy', $c->id) }}" onsubmit="return confirm('Are you sure you want to delete class \'{{ addslashes($c->name) }}\'? This will also delete all associated sections and student records.');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Delete Class">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Subjects Catalog Table -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] border border-slate-200/80 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-display text-base font-bold text-slate-900">Curriculum Subjects Directory</h3>
                <p class="text-xs text-slate-400 mt-0.5">Academic disciplines and subject code mappings</p>
            </div>
            <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-200/60">
                {{ $subjects->count() }} Subjects
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/90 text-[11px] uppercase font-extrabold tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Subject Name</th>
                        <th class="px-6 py-4">Subject Code</th>
                        <th class="px-6 py-4">Subject Type</th>
                        <th class="px-6 py-4">Class Standard</th>
                        <th class="px-6 py-4 text-right">Faculty</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subjects as $sub)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $sub->name }}
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-xs text-indigo-600">
                            <span class="bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-200/60">{{ $sub->code }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold capitalize {{ $sub->type == 'theory' ? 'bg-indigo-50 text-indigo-700' : 'bg-emerald-50 text-emerald-700' }}">
                                {{ $sub->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-700">
                            {{ $sub->schoolClass->name ?? 'All Grades' }}
                        </td>
                        <td class="px-6 py-4 text-right text-xs">
                            <span class="font-semibold text-slate-800">{{ $sub->teacher->user->name ?? 'Unassigned' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">No subjects cataloged yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Class Modal -->
    <div x-cloak x-show="addClassModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="addClassModal" class="fixed inset-0 bg-slate-950/75 backdrop-blur-md transition-opacity" @click="addClassModal = false"></div>

            <div x-show="addClassModal" class="inline-block w-full max-w-md p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10 border border-slate-100 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-900">Add Academic Class</h3>
                        <p class="text-xs text-slate-400 mt-0.5">e.g. Grade 11 or Standard 12</p>
                    </div>
                    <button @click="addClassModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.classes.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Class / Standard Name</label>
                        <input type="text" name="name" required placeholder="e.g. Grade 11-Science" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Numeric Grade Level</label>
                        <input type="number" name="numeric_grade" required min="1" max="15" placeholder="11" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="addClassModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 transition active:scale-95">Create Class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Section Modal -->
    <div x-cloak x-show="addSectionModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="addSectionModal" class="fixed inset-0 bg-slate-950/75 backdrop-blur-md transition-opacity" @click="addSectionModal = false"></div>

            <div x-show="addSectionModal" class="inline-block w-full max-w-md p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10 border border-slate-100 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-900">Add Division Section</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Attach a section to a standard (e.g. Section C)</p>
                    </div>
                    <button @click="addSectionModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.sections.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Parent Academic Class</label>
                        <select name="school_class_id" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Section Name / Division</label>
                        <input type="text" name="name" required placeholder="e.g. C or Alpha" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Seating Capacity</label>
                        <input type="number" name="capacity" value="40" min="1" max="100" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="addSectionModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 transition active:scale-95">Add Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
