@extends('layouts.app')

@section('title', 'Admissions & Enquiry Management - SchoolNexus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-slate-900 tracking-tight">Admissions & Enquiries</h1>
            <p class="text-sm text-slate-500 mt-1">Manage prospective student inquiries, admissions interviews, and new scholar enrollment pipelines.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="$dispatch('open-modal-enquiry')" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-600/30 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                <span>New Admission Enquiry</span>
            </button>
        </div>
    </div>

    <!-- Enquiry Pipeline Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Prospects</div>
            <div class="text-2xl font-bold font-display text-slate-900 mt-1">{{ $stats['total'] }}</div>
            <div class="text-xs text-indigo-600 font-medium mt-1">Total application leads</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">New Enquiries</div>
            <div class="text-2xl font-bold font-display text-indigo-600 mt-1">{{ $stats['new'] }}</div>
            <div class="text-xs text-slate-500 mt-1">Awaiting counsellor call</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Interviews Scheduled</div>
            <div class="text-2xl font-bold font-display text-amber-600 mt-1">{{ $stats['interview'] }}</div>
            <div class="text-xs text-amber-600 font-medium mt-1">Assessment / meeting planned</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Confirmed Admitted</div>
            <div class="text-2xl font-bold font-display text-emerald-600 mt-1">{{ $stats['admitted'] }}</div>
            <div class="text-xs text-emerald-600 font-medium mt-1">Converted to enrolled students</div>
        </div>
    </div>

    <!-- Enquiries Ledger Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Admissions Prospect Inquiries</h2>
                <p class="text-xs text-slate-500">Contact information, previous schooling, pipeline stage, and follow-up notes.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Enquiry ID</th>
                        <th class="px-5 py-3.5">Applicant Name</th>
                        <th class="px-5 py-3.5">Parent / Guardian</th>
                        <th class="px-5 py-3.5">Contact Details</th>
                        <th class="px-5 py-3.5">Target Class</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Update Stage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($enquiries as $enq)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-indigo-600">{{ $enq->enquiry_number }}</td>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-slate-900">{{ $enq->applicant_name }}</div>
                            <div class="text-xs text-slate-400">{{ $enq->previous_school ?? 'Previous school unlisted' }}</div>
                        </td>
                        <td class="px-5 py-3.5 font-medium text-slate-800">{{ $enq->parent_name }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">
                            <div>{{ $enq->phone }}</div>
                            <div class="text-slate-400 font-mono text-[11px]">{{ $enq->email ?? '' }}</div>
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $enq->schoolClass->name ?? 'N/A' }}</td>
                        <td class="px-5 py-3.5">
                            @if($enq->status === 'admitted')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Admitted</span>
                            @elseif($enq->status === 'interview_scheduled')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200">Interview Scheduled</span>
                            @elseif($enq->status === 'contacted')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">Contacted</span>
                            @elseif($enq->status === 'rejected')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-rose-50 text-rose-700 border border-rose-200">Closed / Declined</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-700 border border-slate-200">New Prospect</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <form action="{{ route('admin.admissions.status', $enq->id) }}" method="POST" class="inline-flex items-center gap-1.5">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="text-xs py-1 px-2 border border-slate-200 rounded-lg text-slate-700 bg-slate-50 focus:ring-1 focus:ring-indigo-500">
                                    <option value="new" {{ $enq->status === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="contacted" {{ $enq->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="interview_scheduled" {{ $enq->status === 'interview_scheduled' ? 'selected' : '' }}>Interview</option>
                                    <option value="admitted" {{ $enq->status === 'admitted' ? 'selected' : '' }}>Admit</option>
                                    <option value="rejected" {{ $enq->status === 'rejected' ? 'selected' : '' }}>Decline</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">No admission enquiries recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $enquiries->links() }}
        </div>
    </div>
</div>

<!-- Modal: New Admission Enquiry -->
<div x-data="{ open: false }" @open-modal-enquiry.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">New Admission Prospect Enquiry</h3>
            <p class="text-xs text-slate-500 mb-4">Capture prospective student details and contact information.</p>
            <form action="{{ route('admin.admissions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Applicant / Child Name</label>
                        <input type="text" name="applicant_name" required placeholder="e.g. Leo Vance" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Parent / Guardian Name</label>
                        <input type="text" name="parent_name" required placeholder="e.g. Marcus Vance" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Primary Phone</label>
                        <input type="text" name="phone" required placeholder="+1 555-0199" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Parent Email</label>
                        <input type="email" name="email" placeholder="parent@domain.com" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Target Class</label>
                        <select name="school_class_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Date of Birth</label>
                        <input type="date" name="dob" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Previous School Attended</label>
                    <input type="text" name="previous_school" placeholder="e.g. Cambridge Preparatory" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Residence Address</label>
                    <textarea name="address" rows="2" placeholder="Residential street address..." class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">Register Enquiry</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
