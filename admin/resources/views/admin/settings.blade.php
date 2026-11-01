@extends('layouts.app')

@section('title', 'Institution Settings — Admin Workspace')
@section('header_title', 'School Profile, Academic Year & Branding Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Banner Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-violet-500 text-white flex items-center justify-center shadow-lg shadow-indigo-600/30 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Institution Profile & Branding</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage school profile, academic year sessions, institutional affiliation, principal credentials & branding settings.</p>
            </div>
        </div>
    </div>

    <!-- Settings Form Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-8">
            @csrf

            <!-- Section 1: Institution Identity -->
            <div>
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 mb-5">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    <h3 class="font-display text-base font-bold text-slate-900">1. School Profile & Legal Affiliation</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">School / Academy Name</label>
                        <input type="text" name="school_name" value="{{ old('school_name', $settings['school_name']) }}" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Motto / Branding Tagline</label>
                        <input type="text" name="school_motto" value="{{ old('school_motto', $settings['school_motto']) }}" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Principal / Head of Institution</label>
                        <input type="text" name="principal_name" value="{{ old('principal_name', $settings['principal_name']) }}" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Affiliation & Board Registration Code</label>
                        <input type="text" name="affiliation_number" value="{{ old('affiliation_number', $settings['affiliation_number']) }}" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Section 2: Academic Period & Grading -->
            <div>
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 mb-5">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    <h3 class="font-display text-base font-bold text-slate-900">2. Academic Year & Assessment Policy</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Academic Session Year</label>
                        <input type="text" name="academic_year" value="{{ old('academic_year', $settings['academic_year']) }}" required placeholder="e.g. 2026-2027" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Active Academic Term</label>
                        <input type="text" name="current_term" value="{{ old('current_term', $settings['current_term']) }}" required placeholder="e.g. Term 1 (Fall)" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Min Attendance Benchmark (%)</label>
                        <input type="number" name="attendance_threshold" value="{{ old('attendance_threshold', $settings['attendance_threshold']) }}" required min="50" max="100" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Grading Scale Format</label>
                        <input type="text" name="grading_system" value="{{ old('grading_system', $settings['grading_system']) }}" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Section 3: Contact Channels & Location -->
            <div>
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 mb-5">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    <h3 class="font-display text-base font-bold text-slate-900">3. Official Contact & Campus Location</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Administrative Office Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Helpline Phone Number</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">School Official Website</label>
                        <input type="url" name="school_website" value="{{ old('school_website', $settings['school_website']) }}" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Physical Campus Address</label>
                    <textarea name="address" rows="2" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">{{ old('address', $settings['address']) }}</textarea>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    <span>Save Institutional Settings</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
