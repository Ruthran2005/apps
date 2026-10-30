@extends('layouts.app')

@section('title', 'Announcements')
@section('header_title', 'Campus Notices & Announcements')

@section('content')
<div class="space-y-6" x-data="{ addNoticeModal: false }">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <p class="text-xs text-slate-500">Notices published here are instantly distributed to Teacher & Student portals.</p>
        <button @click="addNoticeModal = true" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md transition flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Publish Notice</span>
        </button>
    </div>

    <!-- Announcements Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($announcements as $ann)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $ann->priority == 'urgent' ? 'bg-rose-100 text-rose-800' : ($ann->priority == 'important' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-800') }}">
                        {{ $ann->priority }}
                    </span>
                    <span class="text-xs text-slate-400">{{ $ann->published_at->format('M d, Y') }}</span>
                </div>
                <h3 class="text-base font-bold text-slate-900">{{ $ann->title }}</h3>
                <p class="text-sm text-slate-600 mt-2 leading-relaxed">{{ $ann->content }}</p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Target: <strong class="text-slate-800 capitalize">{{ $ann->audience }}</strong></span>
                <span>By: <strong>{{ $ann->author_name }}</strong></span>
            </div>
        </div>
        @empty
        <div class="col-span-2 bg-white rounded-2xl p-12 text-center text-slate-400">
            No notices published yet.
        </div>
        @endforelse
    </div>

    <!-- Publish Notice Modal -->
    <div x-cloak x-show="addNoticeModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="addNoticeModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addNoticeModal = false"></div>

            <div x-show="addNoticeModal" class="inline-block w-full max-w-lg p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Broadcast Campus Notice</h3>
                        <p class="text-xs text-slate-500">Distribute to faculty and students instantly.</p>
                    </div>
                    <button @click="addNoticeModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Notice Title</label>
                        <input type="text" name="title" required placeholder="e.g. Annual Sports Day Registration" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Audience</label>
                            <select name="audience" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="all">Everyone (All)</option>
                                <option value="teachers">Teachers Only</option>
                                <option value="students">Students Only</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Priority</label>
                            <select name="priority" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="normal">Normal</option>
                                <option value="important">Important</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Message Body</label>
                        <textarea name="content" rows="4" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Provide full details of the notice..."></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="addNoticeModal = false" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md transition">Publish Notice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
