@extends('layouts.app')

@section('title', 'Manage Admins — Admin Workspace')
@section('header_title', 'Admin Role & Permission Manager')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, editPermModal: false, editUser: { id: 0, name: '', permissions: [] } }">

    <!-- Header Banner Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">System Administrators & Permissions</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Create administrative accounts and configure granular, per-tab access permissions for each staff member.</p>
        </div>
        <button @click="createModal = true" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all active:scale-95 flex items-center gap-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Create New Admin</span>
        </button>
    </div>

    <!-- Administrators Roster Table Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] border border-slate-200/80 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-display text-base font-bold text-slate-900">Administrator Accounts</h3>
                <p class="text-xs text-slate-400 mt-0.5">Control staff logins and modular navigation authority</p>
            </div>
            <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-200/60 shadow-2xs">
                {{ $admins->count() }} Registered Admins
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/90 text-[11px] uppercase font-extrabold tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Administrator</th>
                        <th class="px-6 py-4">Contact Phone</th>
                        <th class="px-6 py-4">Role & Access Scope</th>
                        <th class="px-6 py-4">Allowed Tabs</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($admins as $adm)
                    @php
                        $isSuper = empty($adm->permissions);
                        $perms = $adm->permissions ?? [];
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3.5">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-500 to-violet-500 text-white font-bold text-xs flex items-center justify-center shadow-md shadow-indigo-500/20 shrink-0">
                                    {{ substr($adm->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 text-xs sm:text-sm">{{ $adm->name }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5 font-mono">{{ $adm->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-slate-600">
                            {{ $adm->phone ?? 'None registered' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($isSuper)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Super Admin (Full Access)</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                    <span>Custom Scoped ({{ count($perms) }} Tabs)</span>
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($isSuper)
                                <span class="text-xs text-slate-400 italic font-medium">All 11 Admin Tabs Granted</span>
                            @else
                                <div class="flex flex-wrap gap-1 max-w-sm">
                                    @foreach($perms as $p)
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-mono text-[10px] font-semibold">
                                            {{ $p }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" 
                                        @click="editUser = { id: {{ $adm->id }}, name: '{{ addslashes($adm->name) }}', permissions: {{ json_encode($perms) }} }; editPermModal = true;"
                                        class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 font-bold text-xs border border-slate-200 transition active:scale-95 shadow-2xs">
                                    Configure Access
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Admin Modal -->
    <div x-cloak x-show="createModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="createModal" class="fixed inset-0 bg-slate-950/75 backdrop-blur-md transition-opacity" @click="createModal = false"></div>

            <div x-show="createModal" class="inline-block w-full max-w-lg p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10 border border-slate-100 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-900">Create New Administrator</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Assign staff credentials and select accessible tabs.</p>
                    </div>
                    <button @click="createModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.manage-admins.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Administrator Name</label>
                        <input type="text" name="name" required placeholder="e.g. Vice Principal Karen White" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                            <input type="email" name="email" required placeholder="karen@school.com" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Phone Number</label>
                            <input type="text" name="phone" placeholder="+1 (555) 345-6789" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Default Password</label>
                        <input type="password" name="password" value="password" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition">
                    </div>

                    <!-- Per-Tab Access Checkboxes -->
                    <div class="pt-3 border-t border-slate-100">
                        <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Granted Tab Access (Leave all unchecked for Super Admin)</label>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            @foreach($availableTabs as $key => $label)
                            <label class="flex items-center space-x-2 p-2 rounded-xl bg-slate-50 hover:bg-indigo-50/50 border border-slate-200/60 cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}" class="rounded text-indigo-600 focus:ring-indigo-500">
                                <span class="font-semibold text-slate-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="createModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 transition active:scale-95">Save Administrator</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Permissions Modal -->
    <div x-cloak x-show="editPermModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="editPermModal" class="fixed inset-0 bg-slate-950/75 backdrop-blur-md transition-opacity" @click="editPermModal = false"></div>

            <div x-show="editPermModal" class="inline-block w-full max-w-lg p-8 my-8 text-left bg-white rounded-3xl shadow-2xl relative z-10 border border-slate-100 animate-scale-up">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-900">Per-Tab Access Permissions</h3>
                        <p class="text-xs text-slate-400 mt-0.5" x-text="'Configuring access for ' + editUser.name"></p>
                    </div>
                    <button @click="editPermModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form method="POST" :action="'{{ url('manage-admins') }}/' + editUser.id + '/permissions'" class="space-y-4">
                    @csrf
                    
                    <p class="text-xs text-slate-500">Uncheck all tabs to promote this user to <strong>Super Admin</strong> (unrestricted full access).</p>

                    <div class="grid grid-cols-2 gap-2 text-xs max-h-72 overflow-y-auto p-1">
                        @foreach($availableTabs as $key => $label)
                        <label class="flex items-center space-x-2 p-2.5 rounded-xl bg-slate-50 hover:bg-indigo-50/50 border border-slate-200/60 cursor-pointer">
                            <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                                   :checked="editUser.permissions.includes('{{ $key }}')"
                                   class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span class="font-semibold text-slate-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="editPermModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 transition active:scale-95">Update Permissions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
