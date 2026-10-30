@extends('layouts.app')

@section('title', 'Student Profile — SchoolNexus')
@section('header_title', 'Student Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Profile Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-purple-950 to-slate-900 p-6 sm:p-8 text-white shadow-xl border border-purple-900/40">
        <div class="flex flex-col sm:flex-row items-center gap-6 relative z-10 text-center sm:text-left">
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-gradient-to-tr from-purple-500 via-violet-500 to-fuchsia-400 text-white font-display text-3xl font-extrabold flex items-center justify-center shadow-lg shadow-purple-500/30 shrink-0">
                {{ substr($student->user->name ?? 'S', 0, 2) }}
            </div>
            <div class="space-y-1">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                    <h2 class="font-display text-xl sm:text-2xl font-extrabold text-white">{{ $student->user->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold uppercase tracking-wider border border-emerald-500/30">Active Scholar</span>
                </div>
                <p class="text-xs sm:text-sm text-purple-200/80">
                    {{ $student->schoolClass->name }} &bull; Section {{ $student->section->name }} &bull; Roll Number: <span class="font-bold text-white">#{{ $student->roll_number }}</span>
                </p>
                <p class="text-xs text-purple-300/60 font-mono">
                    Admission #: <span class="text-purple-200 font-bold">{{ $student->admission_number }}</span> &bull; {{ $student->user->email }}
                </p>
            </div>
        </div>
    </div>

    <!-- 2 Column Layout: Academic Information & Contact Form -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Left Column: Academic Credentials (Read-only) -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
                <h3 class="font-display text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Official Records</h3>
                
                <div class="space-y-3.5 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Enrolled Class:</span>
                        <span class="font-bold text-slate-900">{{ $student->schoolClass->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Section:</span>
                        <span class="font-bold text-slate-900">Section {{ $student->section->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Roll Number:</span>
                        <span class="font-bold text-slate-900 font-mono">#{{ $student->roll_number }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Date of Birth:</span>
                        <span class="font-bold text-slate-900">{{ $student->dob ? $student->dob->format('M d, Y') : '15 May 2008' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Gender:</span>
                        <span class="font-bold text-slate-900 capitalize">{{ $student->gender ?? 'Unspecified' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Blood Group:</span>
                        <span class="font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200/70 inline-block mt-0.5">{{ $student->blood_group ?? 'O+' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Security Box -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
                <h3 class="font-display text-sm font-bold text-slate-900 mb-2">Account Security</h3>
                <p class="text-xs text-slate-500 mb-4">Need to change your student login credentials?</p>
                <a href="{{ route('student.change-password') }}" class="block text-center py-2.5 px-4 rounded-2xl bg-slate-100 hover:bg-purple-50 text-purple-700 font-bold text-xs border border-slate-200 transition">
                    Change Password &rarr;
                </a>
            </div>
        </div>

        <!-- Right Column: Editable Contact & Guardian Details -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
                <div class="pb-4 border-b border-slate-100 mb-6">
                    <h3 class="font-display text-base font-bold text-slate-900">Guardian & Contact Information</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Keep your home address and parent/guardian emergency contact up to date.</p>
                </div>

                <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Parent / Guardian Full Name</label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" placeholder="e.g. Robert Morgan" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-purple-500 focus:outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Emergency Contact Phone</label>
                            <input type="text" name="guardian_phone" value="{{ old('guardian_phone', $student->guardian_phone) }}" placeholder="+1 (555) 234-5678" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-purple-500 focus:outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Student Account Email</label>
                            <input type="email" value="{{ $student->user->email }}" disabled class="w-full px-4 py-2.5 rounded-2xl bg-slate-100 border border-slate-200 text-sm text-slate-500 font-semibold cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Residential Home Address</label>
                        <textarea name="address" rows="3" placeholder="Residential street, apartment, city, state, zip..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:outline-none transition">{{ old('address', $student->address) }}</textarea>
                    </div>

                    <div class="pt-3 flex items-center justify-end">
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold text-xs sm:text-sm shadow-md shadow-purple-600/30 transition active:scale-95 flex items-center gap-2">
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
