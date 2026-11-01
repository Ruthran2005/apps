@extends('layouts.app')

@section('title', 'Backup & Activity Logs - SchoolNexus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-slate-900 tracking-tight">System Backup & Activity Logs</h1>
            <p class="text-sm text-slate-500 mt-1">Audit trail for compliance, user activity tracking, and one-click database disaster recovery snapshots.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.backup-db') }}" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/30 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>Download Database Backup</span>
            </a>
        </div>
    </div>

    <!-- Health & Security Status Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Database Status</div>
                <div class="text-base font-bold text-slate-900 mt-0.5">Healthy & Synchronized</div>
                <div class="text-xs text-emerald-600 font-medium">Shared Database Engine Active</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Access Security</div>
                <div class="text-base font-bold text-slate-900 mt-0.5">Role Enforcement Active</div>
                <div class="text-xs text-indigo-600 font-medium">Granular Permission Matrix</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center shrink-0 border border-violet-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Audit Log Retention</div>
                <div class="text-base font-bold text-slate-900 mt-0.5">Full Transaction Trail</div>
                <div class="text-xs text-slate-500 font-medium">Logged in real-time</div>
            </div>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Administrative Audit Trail</h2>
                <p class="text-xs text-slate-500">Chronological history of create, update, delete, and security transactions.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Timestamp</th>
                        <th class="px-5 py-3.5">Admin / User</th>
                        <th class="px-5 py-3.5">Action</th>
                        <th class="px-5 py-3.5">Module</th>
                        <th class="px-5 py-3.5">Description</th>
                        <th class="px-5 py-3.5 text-right">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5 text-xs text-slate-500 font-mono">
                            {{ $log->created_at->format('M d, Y H:i:s') }}
                        </td>
                        <td class="px-5 py-3.5 font-bold text-slate-900">{{ $log->user_name }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider 
                                {{ $log->action === 'created' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 
                                   ($log->action === 'deleted' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 
                                   ($log->action === 'updated' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-slate-100 text-slate-700 border border-slate-200')) }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-xs font-semibold text-slate-800">{{ $log->module }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-700 max-w-md">{{ $log->description }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-400 font-mono text-right">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-400 text-sm">No activity logs recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
