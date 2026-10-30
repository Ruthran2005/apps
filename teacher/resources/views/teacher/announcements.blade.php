@extends('layouts.app')

@section('title', 'Announcements — Teacher Workspace')
@section('header_title', 'Campus & Class Announcements')

@section('content')
<div class="space-y-6" x-data="{ createModal: false }">

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Broadcast Announcements</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Publish notices directly to students or review institutional bulletins from the school administration.</p>
        </div>
        <button @click="createModal = true" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm shadow-lg shadow-emerald-600/30 transition-all duration-200 flex items-center justify-center space-x-2 active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Post New Notice</span>
        </button>
    </div>

    <!-- Announcements Feed -->
    <div class="space-y-4">
        @forelse($announcements as $notice)
        <div class="bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] hover:border-emerald-200 transition-all duration-300">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    @if($notice->priority === 'urgent')
                        <span class="px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold uppercase tracking-wider">
                            Urgent Notice
                        </span>
                    @elseif($notice->priority === 'important')
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold uppercase tracking-wider">
                            Important
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase tracking-wider">
                            General
                        </span>
                    @endif

                    <span class="text-xs text-slate-400 font-medium">Audience: <strong class="text-slate-700 capitalize">{{ $notice->audience }}</strong></span>
                </div>

                <div class="flex items-center gap-2 text-xs text-slate-400">
                    <span>{{ \Carbon\Carbon::parse($notice->published_at)->format('M d, Y') }}</span>
                    <span>&bull;</span>
                    <span class="font-semibold text-slate-600">By {{ $notice->author_name ?? 'Faculty' }}</span>
                </div>
            </div>

            <div class="mt-4">
                <h3 class="font-display text-base sm:text-lg font-bold text-slate-900">{{ $notice->title }}</h3>
                <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed whitespace-pre-line">{{ $notice->content }}</p>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
            <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            </div>
            <h3 class="font-display font-bold text-base text-slate-900">No Announcements Found</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Click 'Post New Notice' to publish instructions or reminders to students.</p>
        </div>
        @endforelse

        <!-- Pagination -->
        <div class="pt-2">
            {{ $announcements->links() }}
        </div>
    </div>

    <!-- Post Announcement Modal -->
    <div x-cloak x-show="createModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md">
        <div @click.away="createModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="font-display text-base font-bold text-slate-900">Publish Faculty Notice</h3>
                    <p class="text-xs text-slate-500">Will be broadcasted to students immediately</p>
                </div>
                <button @click="createModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('teacher.announcements.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Announcement Title</label>
                    <input type="text" name="title" required placeholder="e.g. Physics Mid-Term Lab Preparation" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Target Audience</label>
                        <select name="audience" class="w-full px-3 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="students">Students Only</option>
                            <option value="all">Entire Campus</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Priority</label>
                        <select name="priority" class="w-full px-3 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="normal">Normal</option>
                            <option value="important">Important</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Notice Message</label>
                    <textarea name="content" rows="4" required placeholder="Type the announcement details and instructions here..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end space-x-3">
                    <button type="button" @click="createModal = false" class="px-5 py-2.5 rounded-2xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-600/30">Broadcast Notice</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
