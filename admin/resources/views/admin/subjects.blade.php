@extends('layouts.app')

@section('title', 'Subjects & Syllabus - SchoolNexus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-slate-900 tracking-tight">Subjects & Academic Syllabus</h1>
            <p class="text-sm text-slate-500 mt-1">Manage institutional course offerings, credit allocations, modular syllabus units & curriculum pacing.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="$dispatch('open-modal-subject')" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Subject</span>
            </button>
            <button @click="$dispatch('open-modal-syllabus')" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-600/30 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span>Add Syllabus Unit</span>
            </button>
        </div>
    </div>

    <!-- Subjects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($subjects as $sub)
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-200">{{ $sub->code }}</span>
                    <span class="text-xs font-semibold text-slate-500">{{ $sub->credits }} Credits</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 mt-1">{{ $sub->name }}</h3>
                <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $sub->description ?? 'Comprehensive academic subject discipline.' }}</p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                    <span class="text-slate-400">Allocated Teachers:</span>
                    <span class="font-semibold text-slate-800">{{ $sub->teacher_subject_assignments_count ?? 0 }} Faculty Members</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-end">
                <form action="{{ route('admin.subjects.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Delete this subject discipline?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold p-1 hover:bg-rose-50 rounded">Delete Subject</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400">
            No subjects configured.
        </div>
        @endforelse
    </div>

    <!-- Syllabus Modules Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Curriculum Syllabus Pacing & Learning Units</h2>
                <p class="text-xs text-slate-500">Unit-wise topic distribution, expected outcomes, and completion pacing.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Unit</th>
                        <th class="px-5 py-3.5">Subject & Class</th>
                        <th class="px-5 py-3.5">Topic / Title</th>
                        <th class="px-5 py-3.5">Learning Objectives</th>
                        <th class="px-5 py-3.5">Term</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($syllabi as $syl)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-indigo-600">{{ $syl->unit_code ?? 'Unit' }}</td>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-slate-900">{{ $syl->subject->name ?? 'N/A' }}</div>
                            <div class="text-[11px] text-slate-400">{{ $syl->schoolClass->name ?? 'All' }}</div>
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $syl->title }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-500 max-w-xs truncate">{{ $syl->learning_objectives ?? 'Not specified' }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-600 font-medium">{{ $syl->term }}</td>
                        <td class="px-5 py-3.5">
                            @if($syl->status === 'Completed')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Completed</span>
                            @elseif($syl->status === 'Ongoing')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">In Progress</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 border border-slate-200">Planned</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <form action="{{ route('admin.subjects.syllabus.destroy', $syl->id) }}" method="POST" onsubmit="return confirm('Remove syllabus unit?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold p-1 hover:bg-rose-50 rounded">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">No syllabus units entered.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $syllabi->links() }}
        </div>
    </div>
</div>

<!-- Modal 1: Add Subject -->
<div x-data="{ open: false }" @open-modal-subject.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Add Academic Subject</h3>
            <p class="text-xs text-slate-500 mb-4">Register a new curriculum discipline in the academy.</p>
            <form action="{{ route('admin.subjects.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Subject Name</label>
                    <input type="text" name="name" required placeholder="e.g. Advanced Biology" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Subject Code</label>
                        <input type="text" name="code" required placeholder="BIO-201" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Credits</label>
                        <input type="number" name="credits" value="4" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Description / Overview</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Curriculum summary..."></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">Save Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Add Syllabus -->
<div x-data="{ open: false }" @open-modal-syllabus.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Add Syllabus Module</h3>
            <p class="text-xs text-slate-500 mb-4">Upload curriculum objectives and unit pacing for a class.</p>
            <form action="{{ route('admin.subjects.syllabus.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Subject</label>
                        <select name="subject_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Class</label>
                        <select name="school_class_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Unit / Chapter Code</label>
                        <input type="text" name="unit_code" placeholder="Unit 1 / Chapter 3" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Term</label>
                        <select name="term" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="Term 1">Term 1</option>
                            <option value="Term 2">Term 2</option>
                            <option value="Annual">Annual</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Unit Title</label>
                    <input type="text" name="title" required placeholder="e.g. Thermodynamics & Fluid Dynamics" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Learning Objectives</label>
                    <textarea name="learning_objectives" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Key skills, outcomes, labs..."></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pacing Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="Planned">Planned</option>
                        <option value="Ongoing">In Progress (Ongoing)</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">Save Syllabus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
