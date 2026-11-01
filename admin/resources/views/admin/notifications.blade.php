@extends('layouts.app')

@section('title', 'Notifications - SchoolNexus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-slate-900 tracking-tight">Multi-Channel Notifications</h1>
            <p class="text-sm text-slate-500 mt-1">Broadcast SMS alerts, email circulars, and mobile push notifications to students, parents, and faculty.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="$dispatch('open-modal-notify')" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-600/30 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                <span>Broadcast Notification</span>
            </button>
        </div>
    </div>

    <!-- Communication Channels Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">SMS Gateway</div>
                <div class="text-base font-bold text-slate-900 mt-0.5">High Priority Alerts</div>
                @if($smsConfigured)
                    <div class="text-xs text-emerald-600 font-medium">Credentials configured</div>
                @else
                    <div class="text-xs text-amber-600 font-medium">Twilio setup required</div>
                @endif
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center shrink-0 border border-violet-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Email System</div>
                <div class="text-base font-bold text-slate-900 mt-0.5">HTML Newsletters & Marks</div>
                <div class="text-xs text-slate-500 font-medium">Not verified by this page</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Mobile Push</div>
                <div class="text-base font-bold text-slate-900 mt-0.5">Portal & App In-Box</div>
                <div class="text-xs text-slate-500 font-medium">Portal inbox only</div>
            </div>
        </div>
    </div>

    <!-- Notification Dispatches History -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Broadcast Dispatch History</h2>
                <p class="text-xs text-slate-500">Record of SMS requests, recipient groups, Twilio acceptance, and failed numbers.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Channel</th>
                        <th class="px-5 py-3.5">Title & Message</th>
                        <th class="px-5 py-3.5">Target Group</th>
                        <th class="px-5 py-3.5">Dispatched By</th>
                        <th class="px-5 py-3.5">Sent Time</th>
                        <th class="px-5 py-3.5 text-right">Gateway Result</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($notifications as $notif)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider 
                                {{ $notif->channel === 'sms' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 
                                   ($notif->channel === 'email' ? 'bg-violet-50 text-violet-700 border border-violet-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') }}">
                                {{ $notif->channel }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 max-w-md">
                            <div class="font-bold text-slate-900">{{ $notif->title }}</div>
                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">{{ $notif->message }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-700 font-semibold capitalize">
                            @if($notif->recipient_phone)
                                Individual: {{ $notif->recipient_phone }}
                            @else
                                {{ str_replace('_', ' ', $notif->target_group) }}
                            @endif
                            @if(!$notif->recipient_phone && $notif->schoolClass)
                                ({{ $notif->schoolClass->name }})
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">{{ $notif->sent_by }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-500">{{ $notif->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $notif->status === 'failed' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($notif->status === 'sent' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                                {{ $notif->status === 'failed' ? 'Failed' : ($notif->status === 'sent' ? 'Accepted by Twilio' : 'Legacy / unverified') }}
                            </span>
                            <div class="text-[11px] text-slate-500 mt-1">
                                @if(($notif->recipient_count ?? 0) > 0)
                                    {{ $notif->sent_count }}/{{ $notif->recipient_count }} accepted · {{ $notif->failed_count }} failed
                                @else
                                    Per-recipient result not tracked
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-400 text-sm">No notification dispatches recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

<!-- Modal: Broadcast Notification -->
<div x-data="{ open: @json($errors->any()), group: @json(old('target_group', 'all')) }" @open-modal-notify.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-dvh px-4 py-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full max-h-[calc(100dvh-2rem)] overflow-y-auto p-5 sm:p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Send SMS</h3>
            <p class="text-xs text-slate-500 mb-4">Send to one phone number or a bulk audience. Phone numbers must use international format, for example +919876543210.</p>
            @unless($smsConfigured)
                <div class="mb-4 p-3 rounded-xl border border-amber-200 bg-amber-50 text-xs text-amber-800">
                    SMS is disabled until <code>TWILIO_ACCOUNT_SID</code>, <code>TWILIO_AUTH_TOKEN</code>, and <code>TWILIO_FROM</code> are set in the admin app environment. Twilio acceptance is not a delivery receipt.
                </div>
            @endunless
            <form action="{{ route('admin.notifications.send') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Delivery Channel</label>
                        <input type="text" value="SMS" readonly class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm bg-slate-50 text-slate-600">
                        <input type="hidden" name="channel" value="sms">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Recipients</label>
                        <select name="target_group" x-model="group" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="single">One phone number</option>
                            <option value="all">All Stakeholders (Everyone)</option>
                            <option value="students">Students Only</option>
                            <option value="teachers">Faculty Only</option>
                            <option value="class_specific">Students in a Class</option>
                        </select>
                    </div>
                </div>
                <div x-cloak x-show="group === 'single'">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Phone Number (E.164)</label>
                    <input type="tel" name="recipient_phone" value="{{ old('recipient_phone') }}" :required="group === 'single'" placeholder="+919876543210" autocomplete="tel" pattern="\+[1-9][0-9]{7,14}" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div x-cloak x-show="group === 'class_specific'">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Target Class</label>
                    <select name="school_class_id" :required="group === 'class_specific'" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select a class</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" @selected(old('school_class_id') == $c->id)>{{ $c->name }} ({{ $c->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">SMS Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required maxlength="80" placeholder="e.g. School notice" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Message Content</label>
                    <textarea name="message" rows="3" required maxlength="1400" placeholder="Type your SMS..." class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">{{ old('message') }}</textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" @disabled(!$smsConfigured) class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">Send SMS</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
