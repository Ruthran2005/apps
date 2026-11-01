@extends('layouts.app')

@section('title', 'Change Password — Admin Workspace')
@section('header_title', 'Administrator Password & Security')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header Banner Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-violet-500 text-white flex items-center justify-center shadow-lg shadow-indigo-600/30 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
            </div>
            <div>
                <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Change Password</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Keep your administrator account credentials secure by updating your access key.</p>
            </div>
        </div>
    </div>

    <!-- Password Form Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        
        <form method="POST" action="{{ route('admin.change-password.update') }}" class="space-y-6">
            @csrf

            <!-- Signed In Account Card -->
            <div class="p-4 rounded-2xl bg-slate-50/90 border border-slate-200/70 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 block">Signed in as Administrator:</span>
                    <span class="text-sm font-bold text-slate-900">{{ $admin->name ?? 'Principal Arthur Hayes' }}</span>
                    <span class="text-xs text-slate-500 block font-mono">{{ $admin->email ?? 'admin@school.com' }}</span>
                </div>
                <span class="px-3 py-1 rounded-xl bg-indigo-50 text-indigo-700 text-xs font-mono font-bold border border-indigo-200/60 shadow-2xs">
                    {{ empty($admin->permissions) ? 'SUPER_ADMIN' : 'ADMIN_ROLE' }}
                </span>
            </div>

            <!-- Current Password -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Current Password</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" 
                           name="current_password" 
                           required 
                           placeholder="Enter current password"
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 focus:outline-none transition pr-11">
                    <button type="button" @click="show = !show" class="absolute right-3.5 top-3.5 text-slate-400 hover:text-slate-600">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                    </button>
                </div>
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">New Password</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" 
                           name="new_password" 
                           required 
                           minlength="6"
                           placeholder="Enter at least 6 characters"
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 focus:outline-none transition pr-11">
                    <button type="button" @click="show = !show" class="absolute right-3.5 top-3.5 text-slate-400 hover:text-slate-600">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Confirm New Password -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Confirm New Password</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" 
                           name="new_password_confirmation" 
                           required 
                           placeholder="Re-enter new password"
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 focus:outline-none transition pr-11">
                    <button type="button" @click="show = !show" class="absolute right-3.5 top-3.5 text-slate-400 hover:text-slate-600">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Password Requirements Note -->
            <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100 text-xs text-indigo-900 space-y-1">
                <span class="font-bold flex items-center gap-1.5 text-indigo-950">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Security Recommendations</span>
                </span>
                <p class="text-indigo-800/80 leading-relaxed">
                    Use a strong passphrase with at least 8 characters combining letters, numbers, and symbols. This password is verified across all administrative endpoints.
                </p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    <span>Update Administrator Password</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
