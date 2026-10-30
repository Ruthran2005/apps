@extends('layouts.app')

@section('title', 'Announcements — Student Workspace')
@section('header_title', 'Campus & School Announcements')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)]">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Campus Notices & Bulletins</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Official circulars, academic notices, and schedule updates published by school administration and teachers.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-2xl bg-purple-50 text-purple-800 font-semibold text-xs border border-purple-200/60 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                Official School Bulletin
            </span>
        </div>
    </div>

    <!-- Announcements Feed -->
    <div class="space-y-4">
        @forelse($announcements as $notice)
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/70 shadow-[0_4px_20px_-2px_rgba(0,0,0,0.04)] hover:border-purple-200 transition-all duration-300">
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
                        <span class="px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200 text-[10px] font-bold uppercase tracking-wider">
                            General Notice
                        </span>
                    @endif

                    <span class="text-xs text-slate-400 font-medium">Audience: <strong class="text-slate-700 capitalize">{{ $notice->audience }}</strong></span>
                </div>

                <div class="flex items-center gap-2 text-xs text-slate-400">
                    <span>{{ \Carbon\Carbon::parse($notice->published_at)->format('M d, Y') }}</span>
                    <span>&bull;</span>
                    <span class="font-semibold text-slate-600">By {{ $notice->author_name ?? 'School Administration' }}</span>
                </div>
            </div>

            <div class="mt-4">
                <h3 class="font-display text-base sm:text-lg font-bold text-slate-900">{{ $notice->title }}</h3>
                <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed whitespace-pre-line">{{ $notice->content }}</p>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
            <div class="w-16 h-16 rounded-3xl bg-purple-50 text-purple-600 mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            </div>
            <h3 class="font-display font-bold text-base text-slate-900">No Announcements Active</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">All current notices have expired or no new updates have been posted.</p>
        </div>
        @endforelse

        <!-- Pagination -->
        <div class="pt-2">
            {{ $announcements->links() }}
        </div>
    </div>

</div>
@endsection
