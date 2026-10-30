@extends('layouts.app')

@section('title', 'Students Directory')
@section('header_title', 'Student Management')

@section('content')
<div class="space-y-6" x-data="{ addModalOpen: false }">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.students') }}" class="flex flex-wrap items-center gap-3 flex-1 max-w-xl">
            <div class="relative flex-1 min-w-[240px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student name, email, or admission ID..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200/80 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 bg-white shadow-sm transition">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <select name="class_id" onchange="this.form.submit()" class="py-2.5 px-4 rounded-2xl border border-slate-200/80 text-sm bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-slate-700 font-medium">
                <option value="">All Academic Grades</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            
            @if(request()->hasAny(['search', 'class_id']))
                <a href="{{ route('admin.students') }}" class="text-xs font-bold text-slate-500 hover:text-indigo-600 transition">Reset</a>
            @endif
        </form>

        <button @click="addModalOpen = true" class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Enroll New Student</span>
        </button>
    </div>

    <!-- Students Directory Table Card -->
    <div class="bg-white rounded-3xl shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] border border-slate-200/70 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-[11px] uppercase font-bold tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Student Profile</th>
                        <th class="px-6 py-4">Admission #</th>
                        <th class="px-6 py-4">Class Standard</th>
                        <th class="px-6 py-4">Roll</th>
                        <th class="px-6 py-4">Guardian Contact</th>
                        <th class="px-6 py-4">Student Phone</th>
                        <th class="px-6 py-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80">
                    @forelse($students as $student)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3.5">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-500 to-violet-500 text-white font-bold text-xs flex items-center justify-center shadow-sm">
                                    {{ substr($student->user->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 leading-tight">{{ $student->user->name }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $student->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-xs text-indigo-600">
                            {{ $student->admission_number }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold bg-indigo-50/70 text-indigo-700 border border-indigo-100">
                                {{ $student->schoolClass->name ?? 'Unassigned' }} &bull; {{ $student->section->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700">
                            {{ $student->roll_number ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <p class="font-bold text-slate-800">{{ $student->guardian_name ?? 'N/A' }}</p>
                            <p class="text-slate-400 mt-0.5">{{ $student->guardian_phone ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">
                            {{ $student->user->phone ?? 'Not registered' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                Active
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            No students match your search criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $students->links() }}
        </div>
        @endif
    </div>

    <!-- Enroll Student Modal with Blur -->
    <div x-cloak x-show="addModalOpen" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="addModalOpen" class="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity" @click="addModalOpen = false"></div>

            <div x-show="addModalOpen" class="inline-block w-full max-w-2xl p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10 border border-slate-100 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-900">Enroll New Student</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Creates credentials and profile synchronized across all 3 portals.</p>
                    </div>
                    <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name</label>
                            <input type="text" name="name" required placeholder="e.g. Liam Smith" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address</label>
                            <input type="email" name="email" required placeholder="e.g. liam@example.com" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Class / Grade</label>
                            <select name="school_class_id" required class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Section</label>
                            <select name="section_id" required class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                                @foreach($classes as $c)
                                    @foreach($c->sections as $sec)
                                        <option value="{{ $sec->id }}">{{ $c->name }} &bull; {{ $sec->name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Gender</label>
                            <select name="gender" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Guardian Name</label>
                            <input type="text" name="guardian_name" required placeholder="e.g. Robert Smith" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Guardian Phone</label>
                            <input type="text" name="guardian_phone" required placeholder="+1 (555) 000-0000" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Student Phone (Optional)</label>
                            <input type="text" name="phone" placeholder="+1 (555) 111-2222" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Default Password</label>
                            <input type="password" name="password" value="password" required class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Residential Address</label>
                        <textarea name="address" rows="2" placeholder="Street, City, Postal Code..." class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="addModalOpen = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 transition">Enroll Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
