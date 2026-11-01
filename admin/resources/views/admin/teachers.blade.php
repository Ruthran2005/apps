@extends('layouts.app')

@section('title', 'Teachers & Faculty — Admin Workspace')
@section('header_title', 'Faculty Directory & Allocations')

@section('content')
<div class="space-y-6" x-data="{ 
    addTeacherModal: false,
    editTeacherModal: false,
    editTeacher: {
        id: '',
        name: '',
        email: '',
        phone: '',
        specialization: '',
        qualification: '',
        salary: ''
    },
    openEditTeacher(t) {
        this.editTeacher = {
            id: t.id,
            name: t.name,
            email: t.email,
            phone: t.phone || '',
            specialization: t.specialization || '',
            qualification: t.qualification || '',
            salary: t.salary || ''
        };
        this.editTeacherModal = true;
    }
}">

    <!-- Top Action & Search Bar Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.teachers') }}" class="flex items-center gap-3 flex-1 max-w-md">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search faculty by name or employee ID..." 
                       class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200/90 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 bg-slate-50/70 hover:bg-white focus:bg-white shadow-xs transition-all placeholder-slate-400">
                <svg class="w-4 h-4 text-slate-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            @if(request('search'))
                <a href="{{ route('admin.teachers') }}" class="px-3 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition">Clear</a>
            @endif
        </form>

        <div class="flex items-center gap-3 shrink-0">
            <span class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-2xl bg-emerald-50/80 text-emerald-800 text-xs font-bold border border-emerald-200/60 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                {{ $teachers->total() }} Certified Faculty
            </span>

            <button @click="addTeacherModal = true" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all flex items-center space-x-2 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>Add New Teacher</span>
            </button>
        </div>
    </div>

    <!-- Teachers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($teachers as $teacher)
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] border border-slate-200/80 hover:shadow-[0_15px_30px_-8px_rgba(99,102,241,0.12)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
            <div>
                <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3.5 min-w-0">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-600 text-white font-bold text-base flex items-center justify-center shadow-md shadow-emerald-500/20 shrink-0">
                            {{ substr($teacher->user->name, 0, 2) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-display font-bold text-slate-900 text-base leading-tight truncate">{{ $teacher->user->name }}</h3>
                            <span class="text-xs font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100 mt-1 inline-block">{{ $teacher->employee_id }}</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1 shrink-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 mr-1">
                            Faculty
                        </span>
                        <button type="button" 
                                @click="openEditTeacher({
                                    id: {{ $teacher->id }},
                                    name: @js($teacher->user->name),
                                    email: @js($teacher->user->email),
                                    phone: @js($teacher->user->phone ?? ''),
                                    specialization: @js($teacher->specialization ?? ''),
                                    qualification: @js($teacher->qualification ?? ''),
                                    salary: @js($teacher->salary ?? '')
                                })"
                                class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition" 
                                title="Edit Teacher">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <form method="POST" action="{{ route('admin.teachers.destroy', $teacher->id) }}" onsubmit="return confirm('Are you sure you want to delete teacher \'{{ addslashes($teacher->user->name) }}\'? This will remove their course assignments.');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Delete Teacher">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-4 space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-extrabold tracking-wider">Specialization</span>
                        <p class="font-bold text-slate-800 mt-0.5">{{ $teacher->specialization ?? 'General Academics' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-extrabold tracking-wider">Qualifications</span>
                        <p class="text-slate-600 mt-0.5">{{ $teacher->qualification ?? 'Standard Certification' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-extrabold tracking-wider">Assigned Subjects</span>
                        <div class="flex flex-wrap gap-1.5 mt-1.5">
                            @forelse($teacher->teacherSubjectAssignments as $assign)
                                <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 font-semibold border border-slate-200/60 shadow-2xs">
                                    {{ $assign->subject->name ?? 'Course' }}
                                </span>
                            @empty
                                <span class="text-slate-400 italic">No courses currently assigned</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between text-slate-400 text-xs font-mono">
                <span class="truncate">{{ $teacher->user->email }}</span>
                <span class="shrink-0">{{ $teacher->user->phone ?? 'Phone unset' }}</span>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white/90 backdrop-blur-sm rounded-3xl p-16 text-center text-slate-400 border border-slate-200/80">
            <div class="max-w-xs mx-auto text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <p class="font-bold text-slate-700">No teachers found</p>
                <p class="text-xs text-slate-400 mt-1">Try adjusting your search query.</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($teachers->hasPages())
    <div class="bg-white/90 backdrop-blur-sm p-4 rounded-3xl border border-slate-200/80">
        {{ $teachers->links() }}
    </div>
    @endif

    <!-- Add Teacher Modal -->
    <div x-cloak x-show="addTeacherModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="addTeacherModal" class="fixed inset-0 bg-slate-950/75 backdrop-blur-md transition-opacity" @click="addTeacherModal = false"></div>

            <div x-show="addTeacherModal" class="inline-block w-full max-w-xl p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10 border border-slate-100 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-900">Add Academic Faculty</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Creates teacher profile and login access to Faculty Workspace.</p>
                    </div>
                    <button @click="addTeacherModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.teachers.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Name</label>
                        <input type="text" name="name" required placeholder="e.g. Dr. Robert Lang" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                            <input type="email" name="email" required placeholder="e.g. robert.lang@school.com" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Phone Number</label>
                            <input type="text" name="phone" placeholder="+1 (555) 019-2834" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Specialization</label>
                            <input type="text" name="specialization" placeholder="e.g. Theoretical Physics / STEM" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Highest Qualification</label>
                            <input type="text" name="qualification" placeholder="e.g. Ph.D. Physics, MIT" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Default Password</label>
                        <input type="password" name="password" value="password" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="addTeacherModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 transition active:scale-95">Create Faculty Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Teacher Modal -->
    <div x-cloak x-show="editTeacherModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="editTeacherModal" class="fixed inset-0 bg-slate-950/75 backdrop-blur-md transition-opacity" @click="editTeacherModal = false"></div>

            <div x-show="editTeacherModal" class="inline-block w-full max-w-xl p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10 border border-slate-100 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-900">Edit Faculty Profile</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Update credentials, contact information, or qualifications.</p>
                    </div>
                    <button @click="editTeacherModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" :action="'/teachers/' + editTeacher.id" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Name</label>
                        <input type="text" name="name" x-model="editTeacher.name" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                            <input type="email" name="email" x-model="editTeacher.email" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Phone Number</label>
                            <input type="text" name="phone" x-model="editTeacher.phone" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Specialization</label>
                            <input type="text" name="specialization" x-model="editTeacher.specialization" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Highest Qualification</label>
                            <input type="text" name="qualification" x-model="editTeacher.qualification" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Salary ($/mo)</label>
                            <input type="number" step="0.01" name="salary" x-model="editTeacher.salary" placeholder="e.g. 5500.00" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reset Password (Optional)</label>
                            <input type="password" name="password" placeholder="Leave blank to keep existing" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="editTeacherModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 transition active:scale-95">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
