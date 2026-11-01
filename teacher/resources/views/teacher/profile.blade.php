@extends('layouts.app')

@section('title', 'Faculty Profile — Teacher Workspace')
@section('header_title', 'Faculty Profile & Academic Records')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Profile Hero Header with Emerald Neon Glow -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-emerald-950 to-slate-900 p-6 sm:p-8 text-white shadow-xl border border-emerald-900/40">
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-teal-600/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row items-center gap-6 relative z-10 text-center sm:text-left">
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-gradient-to-tr from-emerald-500 via-teal-500 to-emerald-400 text-white font-display text-3xl font-extrabold flex items-center justify-center shadow-lg shadow-emerald-500/30 shrink-0">
                {{ substr($teacher->user->name ?? 'T', 0, 2) }}
            </div>
            <div class="space-y-1">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-white">{{ $teacher->user->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold uppercase tracking-wider border border-emerald-500/30">Verified Faculty</span>
                </div>
                <p class="text-xs sm:text-sm text-emerald-200/80">
                    {{ $teacher->qualification ?? 'Faculty Member' }} &bull; Department of {{ $teacher->specialization ?? 'Academics' }}
                </p>
                <p class="text-xs text-emerald-300/60 font-mono">
                    Employee ID: <span class="text-emerald-200 font-bold">{{ $teacher->employee_id }}</span> &bull; {{ $teacher->user->email }}
                </p>
            </div>
        </div>
    </div>

    <!-- 2 Column Layout: Official Records & Editable Profile Form -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Left Column: Official Records (Read-only Academic Credentials) -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
                <h3 class="font-display text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Official Records</h3>
                
                <div class="space-y-3.5 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Employee ID:</span>
                        <span class="font-bold text-slate-900 font-mono text-xs">{{ $teacher->employee_id }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Department / Field:</span>
                        <span class="font-bold text-slate-900">{{ $teacher->specialization ?? 'General Academics' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Highest Degree / Qualification:</span>
                        <span class="font-bold text-slate-900">{{ $teacher->qualification ?? 'Master of Science' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Appointment Date:</span>
                        <span class="font-bold text-slate-900">{{ $teacher->joining_date ? $teacher->joining_date->format('M d, Y') : 'Aug 15, 2021' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Status:</span>
                        <span class="font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/70 inline-block mt-0.5">Full-Time Faculty</span>
                    </div>
                </div>
            </div>

            <!-- Teaching Allocations Overview -->
            <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
                <h3 class="font-display text-sm font-bold text-slate-900 mb-2">Teaching Allocations</h3>
                <p class="text-xs text-slate-500 mb-3">Classes currently under your instruction:</p>
                
                <div class="space-y-2">
                    @forelse($teacher->teacherSubjectAssignments as $assign)
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/70 flex items-center justify-between text-xs">
                        <span class="font-semibold text-slate-800">{{ $assign->schoolClass->name ?? 'Class' }} — Sec {{ $assign->section->name ?? 'A' }}</span>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-mono text-[10px] font-bold border border-emerald-100">{{ $assign->subject->name ?? 'Subject' }}</span>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400">No classroom assignments recorded.</p>
                    @endforelse
                </div>
            </div>

            <!-- Quick Security Box -->
            <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
                <h3 class="font-display text-sm font-bold text-slate-900 mb-2">Account Security</h3>
                <p class="text-xs text-slate-500 mb-4">Want to update your faculty portal login password?</p>
                <a href="{{ route('teacher.change-password') }}" class="block text-center py-2.5 px-4 rounded-2xl bg-slate-100 hover:bg-emerald-50 text-emerald-700 font-bold text-xs border border-slate-200 transition">
                    Change Password &rarr;
                </a>
            </div>
        </div>

        <!-- Right Column: Editable Profile Information Form -->
        <div class="md:col-span-2">
            <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
                <div class="pb-4 border-b border-slate-100 mb-6">
                    <h3 class="font-display text-base font-bold text-slate-900">Faculty Profile Details</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Keep your personal contact information and academic specialization up to date.</p>
                </div>

                @if(isset($errors) && $errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-800 space-y-1">
                    <div class="font-bold flex items-center gap-1.5 text-rose-900">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>Please correct the errors below:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('teacher.profile.update') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Legal Name</label>
                        <input type="text" name="name" value="{{ old('name', $teacher->user->name) }}" required placeholder="e.g. Dr. Sarah Jenkins" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Official Email Address</label>
                            <input type="email" value="{{ $teacher->user->email }}" disabled class="w-full px-4 py-2.5 rounded-2xl bg-slate-100 border border-slate-200 text-sm text-slate-500 font-semibold cursor-not-allowed">
                            <span class="text-[10px] text-slate-400 mt-1 block">Managed by school administrator</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Contact Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $teacher->user->phone) }}" placeholder="+1 (555) 234-5678" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Academic Qualification</label>
                            <input type="text" name="qualification" value="{{ old('qualification', $teacher->qualification) }}" placeholder="e.g. Ph.D. in Physics, M.Sc." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Department / Specialization</label>
                            <input type="text" name="specialization" value="{{ old('specialization', $teacher->specialization) }}" placeholder="e.g. Physics & STEM" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-end">
                        <button type="submit" class="px-7 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs sm:text-sm rounded-2xl shadow-lg shadow-emerald-600/30 transition-all duration-200 active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span>Save Profile Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection
