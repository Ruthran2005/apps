@extends('layouts.app')

@section('title', 'Announcements — Admin Workspace')
@section('header_title', 'Campus Notices & Announcements')

@section('content')
<div class="space-y-6" x-data="{ 
    addNoticeModal: false, 
    editNoticeModal: false, 
    editNotice: { id: null, title: '', audience: 'all', priority: 'normal', content: '' } 
}">

    <!-- Header Action Banner Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Institutional Bulletins</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Notices published here are instantly distributed to Teacher and Student workspaces.</p>
        </div>
        <button @click="addNoticeModal = true" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all flex items-center space-x-2 active:scale-95 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Broadcast Notice</span>
        </button>
    </div>

    <!-- Announcements Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($announcements as $ann)
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 sm:p-7 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] border border-slate-200/80 flex flex-col justify-between hover:shadow-[0_15px_30px_-8px_rgba(99,102,241,0.1)] transition-all duration-300">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider {{ $ann->priority == 'urgent' ? 'bg-rose-50 text-rose-700 border border-rose-200/80' : ($ann->priority == 'important' ? 'bg-amber-50 text-amber-700 border border-amber-200/80' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
                        @if($ann->priority == 'urgent')
                            🔥 Urgent Notice
                        @elseif($ann->priority == 'important')
                            ⚡ Important
                        @else
                            📌 Standard Notice
                        @endif
                    </span>
                    <span class="text-xs font-semibold text-slate-400 font-mono">{{ $ann->published_at ? $ann->published_at->format('M d, Y') : now()->format('M d, Y') }}</span>
                </div>
                <h3 class="font-display text-lg font-bold text-slate-900 leading-snug">{{ $ann->title }}</h3>
                <p class="text-sm text-slate-600 mt-2.5 leading-relaxed whitespace-pre-line">{{ $ann->content }}</p>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <div class="flex items-center space-x-3">
                    <span>Target: <strong class="text-slate-800 capitalize bg-slate-100 px-2 py-0.5 rounded-md font-semibold">{{ $ann->audience }}</strong></span>
                    <span>&bull;</span>
                    <span>By: <strong class="text-slate-800 font-semibold">{{ $ann->author_name ?? 'Administration' }}</strong></span>
                </div>

                <!-- Action Buttons: Edit & Delete -->
                <div class="flex items-center space-x-1">
                    <button type="button" 
                            @click="editNotice = { id: {{ $ann->id }}, title: {{ json_encode($ann->title) }}, audience: {{ json_encode($ann->audience) }}, priority: {{ json_encode($ann->priority) }}, content: {{ json_encode($ann->content) }} }; editNoticeModal = true" 
                            class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition active:scale-95" 
                            title="Edit Announcement">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <form method="POST" action="{{ route('admin.announcements.destroy', $ann->id) }}" onsubmit="return confirm('Are you sure you want to delete this notice: \'{{ addslashes($ann->title) }}\'?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition active:scale-95" title="Delete Announcement">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-2 bg-white/90 backdrop-blur-sm rounded-3xl p-16 text-center text-slate-400 border border-slate-200/80">
            <div class="max-w-xs mx-auto text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                </div>
                <p class="font-bold text-slate-700">No notices published yet</p>
                <p class="text-xs text-slate-400 mt-1">Click Broadcast Notice to share campus announcements.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    @if(method_exists($announcements, 'links'))
    <div class="pt-4">
        {{ $announcements->links() }}
    </div>
    @endif

    <!-- Broadcast New Notice Modal -->
    <div x-cloak x-show="addNoticeModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="addNoticeModal" class="fixed inset-0 bg-slate-950/75 backdrop-blur-md transition-opacity" @click="addNoticeModal = false"></div>

            <div x-show="addNoticeModal" class="inline-block w-full max-w-lg p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10 border border-slate-100 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-900">Broadcast Campus Notice</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Distribute to faculty and students in real-time.</p>
                    </div>
                    <button @click="addNoticeModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Notice Headline</label>
                        <input type="text" name="title" required placeholder="e.g. Midterm Examination Schedule Released" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Target Audience</label>
                            <select name="audience" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                                <option value="all">Campus-Wide (Everyone)</option>
                                <option value="students">Students Only</option>
                                <option value="teachers">Faculty Only</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Priority Level</label>
                            <select name="priority" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                                <option value="normal">Standard</option>
                                <option value="important">Important</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Notice Content / Instructions</label>
                        <textarea name="content" rows="4" required placeholder="Type the announcement details here..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="addNoticeModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 transition active:scale-95">Publish Notice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Notice Modal -->
    <div x-cloak x-show="editNoticeModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="editNoticeModal" class="fixed inset-0 bg-slate-950/75 backdrop-blur-md transition-opacity" @click="editNoticeModal = false"></div>

            <div x-show="editNoticeModal" class="inline-block w-full max-w-lg p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10 border border-slate-100 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-900">Edit Campus Notice</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Modify announcement details and broadcast targets.</p>
                    </div>
                    <button @click="editNoticeModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" :action="'{{ url('/announcements') }}/' + editNotice.id" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Notice Headline</label>
                        <input type="text" name="title" x-model="editNotice.title" required placeholder="e.g. Midterm Examination Schedule Released" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Target Audience</label>
                            <select name="audience" x-model="editNotice.audience" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                                <option value="all">Campus-Wide (Everyone)</option>
                                <option value="students">Students Only</option>
                                <option value="teachers">Faculty Only</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Priority Level</label>
                            <select name="priority" x-model="editNotice.priority" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none">
                                <option value="normal">Standard</option>
                                <option value="important">Important</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Notice Content / Instructions</label>
                        <textarea name="content" x-model="editNotice.content" rows="4" required placeholder="Type the announcement details here..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="editNoticeModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 transition active:scale-95">Update Notice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
