@extends('layouts.app')

@section('title', 'Students Directory — Admin Workspace')
@section('header_title', 'Student Management & Roster')

@section('content')
<div class="space-y-6" x-data="{ 
    addModalOpen: false,
    editModalOpen: false,
    editStudent: {
        id: '',
        name: '',
        email: '',
        phone: '',
        school_class_id: '',
        section_id: '',
        gender: '',
        roll_number: '',
        guardian_name: '',
        guardian_phone: '',
        address: ''
    },
    openEdit(s) {
        this.editStudent = {
            id: s.id,
            name: s.name,
            email: s.email,
            phone: s.phone || '',
            school_class_id: s.school_class_id || '',
            section_id: s.section_id || '',
            gender: s.gender || 'Male',
            roll_number: s.roll_number || '',
            guardian_name: s.guardian_name || '',
            guardian_phone: s.guardian_phone || '',
            address: s.address || ''
        };
        this.editModalOpen = true;
    }
}">

    <!-- Top Action & Search Bar Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.students') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[260px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student name, email, or admission ID..." 
                       class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200/90 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 bg-slate-50/70 hover:bg-white focus:bg-white transition-all shadow-xs placeholder-slate-400">
                <svg class="w-4 h-4 text-slate-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <div class="relative">
                <select name="class_id" onchange="this.form.submit()" class="py-3 pl-4 pr-10 rounded-2xl border border-slate-200/90 text-sm bg-slate-50/70 hover:bg-white focus:bg-white shadow-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-slate-700 font-semibold transition">
                    <option value="">All Academic Grades</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            
            @if(request()->hasAny(['search', 'class_id']))
                <a href="{{ route('admin.students') }}" class="px-3 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition">Clear Filters</a>
            @endif
        </form>

        <div class="flex items-center gap-3 shrink-0">
            <span class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-2xl bg-indigo-50/80 text-indigo-700 text-xs font-bold border border-indigo-200/60 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                {{ $students->total() }} Scholars Enrolled
            </span>

            <button @click="addModalOpen = true" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all flex items-center space-x-2 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>Enroll New Student</span>
            </button>
        </div>
    </div>

    <!-- Students Directory Table Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/90 text-[11px] uppercase font-extrabold tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Student Profile</th>
                        <th class="px-6 py-4">Admission #</th>
                        <th class="px-6 py-4">Class Standard</th>
                        <th class="px-6 py-4">Roll</th>
                        <th class="px-6 py-4">Guardian Contact</th>
                        <th class="px-6 py-4">Student Phone</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3.5">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-500 to-violet-500 text-white font-bold text-xs flex items-center justify-center shadow-md shadow-indigo-500/20 shrink-0">
                                    {{ substr($student->user->name, 0, 2) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 leading-tight truncate">{{ $student->user->name }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5 truncate">{{ $student->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-xs text-indigo-600">
                            <span class="bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-200/60">{{ $student->admission_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold bg-indigo-50/70 text-indigo-700 border border-indigo-100/80">
                                {{ $student->schoolClass->name ?? 'Unassigned' }} &bull; {{ $student->section->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700 font-mono">
                            {{ $student->roll_number ? '#' . $student->roll_number : '-' }}
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <p class="font-bold text-slate-800">{{ $student->guardian_name ?? 'N/A' }}</p>
                            <p class="text-slate-400 mt-0.5 font-mono">{{ $student->guardian_phone ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-mono">
                            {{ $student->user->phone ?? 'Not registered' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-1.5">
                                <button type="button" 
                                        @click="openEdit({
                                            id: {{ $student->id }},
                                            name: @js($student->user->name),
                                            email: @js($student->user->email),
                                            phone: @js($student->user->phone ?? ''),
                                            school_class_id: {{ $student->school_class_id ?? 'null' }},
                                            section_id: {{ $student->section_id ?? 'null' }},
                                            gender: @js($student->gender ?? 'Male'),
                                            roll_number: @js($student->roll_number ?? ''),
                                            guardian_name: @js($student->guardian_name ?? ''),
                                            guardian_phone: @js($student->guardian_phone ?? ''),
                                            address: @js($student->address ?? '')
                                        })"
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition" 
                                        title="Edit Student Profile">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <form method="POST" action="{{ route('admin.students.destroy', $student->id) }}" onsubmit="return confirm('Are you sure you want to delete student \'{{ addslashes($student->user->name) }}\'? This will permanently delete their academic records.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Delete Student">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center text-slate-400">
                            <div class="max-w-xs mx-auto text-center">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <p class="font-bold text-slate-700">No students found</p>
                                <p class="text-xs text-slate-400 mt-1">Try adjusting your search query or class filter.</p>
                            </div>
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
            <div x-show="addModalOpen" class="fixed inset-0 bg-slate-950/75 backdrop-blur-md transition-opacity" @click="addModalOpen = false"></div>

            <div x-show="addModalOpen" class="inline-block w-full max-w-2xl p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10 border border-slate-100 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-900">Enroll New Student</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Creates credentials and profile synchronized across all 3 portals.</p>
                    </div>
                    <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Name</label>
                            <input type="text" name="name" required placeholder="e.g. Liam Smith" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                            <input type="email" name="email" required placeholder="e.g. liam@example.com" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Class / Grade</label>
                            <select name="school_class_id" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Section</label>
                            <select name="section_id" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                                @foreach($classes as $c)
                                    @foreach($c->sections as $sec)
                                        <option value="{{ $sec->id }}">{{ $c->name }} &bull; {{ $sec->name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Gender</label>
                            <select name="gender" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Guardian Name</label>
                            <input type="text" name="guardian_name" required placeholder="e.g. Robert Smith" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Guardian Phone</label>
                            <input type="text" name="guardian_phone" required placeholder="+1 (555) 000-0000" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Student Phone (Optional)</label>
                            <input type="text" name="phone" placeholder="+1 (555) 111-2222" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Default Password</label>
                            <input type="password" name="password" value="password" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Residential Address</label>
                        <textarea name="address" rows="2" placeholder="Street, City, Postal Code..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="addModalOpen = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 transition active:scale-95">Enroll Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Student Modal with Blur -->
    <div x-cloak x-show="editModalOpen" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="editModalOpen" class="fixed inset-0 bg-slate-950/75 backdrop-blur-md transition-opacity" @click="editModalOpen = false"></div>

            <div x-show="editModalOpen" class="inline-block w-full max-w-2xl p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10 border border-slate-100 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-900">Edit Student Profile</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Update student details, academic grade assignment, or reset password.</p>
                    </div>
                    <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" :action="'/students/' + editStudent.id" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Name</label>
                            <input type="text" name="name" x-model="editStudent.name" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                            <input type="email" name="email" x-model="editStudent.email" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Class / Grade</label>
                            <select name="school_class_id" x-model="editStudent.school_class_id" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Section</label>
                            <select name="section_id" x-model="editStudent.section_id" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                                @foreach($classes as $c)
                                    @foreach($c->sections as $sec)
                                        <option value="{{ $sec->id }}">{{ $c->name }} &bull; {{ $sec->name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Gender</label>
                            <select name="gender" x-model="editStudent.gender" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Roll Number</label>
                            <input type="text" name="roll_number" x-model="editStudent.roll_number" placeholder="e.g. 15" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Guardian Name</label>
                            <input type="text" name="guardian_name" x-model="editStudent.guardian_name" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Guardian Phone</label>
                            <input type="text" name="guardian_phone" x-model="editStudent.guardian_phone" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Student Phone (Optional)</label>
                            <input type="text" name="phone" x-model="editStudent.phone" placeholder="+1 (555) 111-2222" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reset Password (Optional)</label>
                            <input type="password" name="password" placeholder="Leave blank to keep existing" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Residential Address</label>
                        <textarea name="address" x-model="editStudent.address" rows="2" placeholder="Street, City, Postal Code..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="editModalOpen = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 transition active:scale-95">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
