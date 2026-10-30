@extends('layouts.app')

@section('title', 'Teachers & Faculty')
@section('header_title', 'Faculty Directory')

@section('content')
<div class="space-y-6" x-data="{ addTeacherModal: false }">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.teachers') }}" class="flex items-center gap-3 flex-1 max-w-md">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search faculty by name or employee ID..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            @if(request('search'))
                <a href="{{ route('admin.teachers') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">Clear</a>
            @endif
        </form>

        <button @click="addTeacherModal = true" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md transition flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Add New Teacher</span>
        </button>
    </div>

    <!-- Teachers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($teachers as $teacher)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-600 text-white font-bold text-base flex items-center justify-center shadow-md shadow-emerald-500/20">
                        {{ substr($teacher->user->name, 0, 2) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base leading-tight">{{ $teacher->user->name }}</h3>
                        <span class="text-xs font-mono font-semibold text-indigo-600">{{ $teacher->employee_id }}</span>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                    Faculty
                </span>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 space-y-2 text-xs">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Specialization</span>
                    <p class="font-semibold text-slate-800 mt-0.5">{{ $teacher->specialization ?? 'General Academics' }}</p>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Qualifications</span>
                    <p class="text-slate-600 mt-0.5">{{ $teacher->qualification ?? 'Standard Certification' }}</p>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Assigned Subjects</span>
                    <div class="flex flex-wrap gap-1.5 mt-1">
                        @forelse($teacher->teacherSubjectAssignments as $assign)
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-medium">
                                {{ $assign->subject->name ?? 'Course' }}
                            </span>
                        @empty
                            <span class="text-slate-400 italic">No courses currently assigned</span>
                        @endforelse
                    </div>
                </div>
                <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-slate-500">
                    <span>{{ $teacher->user->email }}</span>
                    <span>{{ $teacher->user->phone ?? 'Phone unset' }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white rounded-2xl p-12 text-center text-slate-400 border border-slate-200">
            No teachers found.
        </div>
        @endforelse
    </div>

    @if($teachers->hasPages())
    <div class="bg-white p-4 rounded-2xl border border-slate-200">
        {{ $teachers->links() }}
    </div>
    @endif

    <!-- Add Teacher Modal -->
    <div x-cloak x-show="addTeacherModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="addTeacherModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addTeacherModal = false"></div>

            <div x-show="addTeacherModal" class="inline-block w-full max-w-lg p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Add Academic Faculty</h3>
                        <p class="text-xs text-slate-500">Creates user and teacher credentials.</p>
                    </div>
                    <button @click="addTeacherModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.teachers.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name</label>
                        <input type="text" name="name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address</label>
                        <input type="email" name="email" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Specialization</label>
                            <input type="text" name="specialization" placeholder="e.g. Pure Mathematics" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Qualification</label>
                            <input type="text" name="qualification" placeholder="e.g. M.Sc., B.Ed" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
                            <input type="text" name="phone" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Monthly Salary ($)</label>
                            <input type="number" step="0.01" name="salary" value="6200.00" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Default Password</label>
                        <input type="password" name="password" value="password" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="addTeacherModal = false" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md transition">Save Faculty Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
