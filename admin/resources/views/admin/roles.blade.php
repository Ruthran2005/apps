@extends('layouts.app')

@section('title', 'Roles & Permissions - SchoolNexus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-slate-900 tracking-tight">Roles & Access Permissions</h1>
            <p class="text-sm text-slate-500 mt-1">Configure institutional administrative roles, security profiles, and fine-grained module privileges.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="$dispatch('open-modal-role')" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Create Role</span>
            </button>
        </div>
    </div>

    <!-- Pre-defined & Custom Roles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($roles as $role)
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between"
             x-data="{ editOpen: false }">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-200">{{ $role->slug }}</span>
                    <span class="text-xs font-semibold text-slate-400">{{ count($role->permissions ?? []) }} Modules</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 mt-1">{{ $role->name }}</h3>
                <p class="text-xs text-slate-500 mt-1">{{ $role->description ?? 'Institutional role profile.' }}</p>

                <!-- Permission Tags Preview -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap gap-1 max-h-24 overflow-y-auto">
                    @foreach($role->permissions ?? [] as $perm)
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[10px] font-medium border border-slate-200">
                            {{ $availableTabs[$perm] ?? $perm }}
                        </span>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                <button type="button" @click="editOpen = !editOpen" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                    Edit Permissions
                </button>
                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Delete this role?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold p-1 hover:bg-rose-50 rounded">Delete</button>
                </form>
            </div>

            <!-- Inline Edit Modal / Drawer for Permissions -->
            <div x-cloak x-show="editOpen" class="fixed inset-0 z-50 overflow-y-auto">
                <div class="min-h-screen px-4 flex items-center justify-center">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editOpen = false"></div>
                    <div class="bg-white rounded-2xl max-w-xl w-full p-6 relative z-10 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Edit Role: {{ $role->name }}</h3>
                        <p class="text-xs text-slate-500 mb-4">Toggle module permissions assigned to users holding this role.</p>
                        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Role Name</label>
                                <input type="text" name="name" value="{{ $role->name }}" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Description</label>
                                <input type="text" name="description" value="{{ $role->description }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Module Access Privileges</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    @foreach($availableTabs as $tabKey => $tabName)
                                    <label class="flex items-center space-x-2 text-xs p-2 rounded-lg bg-slate-50 border border-slate-200 hover:bg-slate-100 cursor-pointer">
                                        <input type="checkbox" name="permissions[]" value="{{ $tabKey }}" 
                                            {{ in_array($tabKey, $role->permissions ?? []) ? 'checked' : '' }}
                                            class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span class="font-medium text-slate-700 truncate">{{ $tabName }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                                <button type="button" @click="editOpen = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">Save Role</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400">
            No custom roles configured.
        </div>
        @endforelse
    </div>
</div>

<!-- Modal: Create Custom Role -->
<div x-data="{ open: false }" @open-modal-role.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 relative z-10 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Create Institutional Role</h3>
            <p class="text-xs text-slate-500 mb-4">Define a new security persona and grant specific operational modules.</p>
            <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Role Title</label>
                        <input type="text" name="name" required placeholder="e.g. Campus Registrar" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Slug Identifier</label>
                        <input type="text" name="slug" required placeholder="campus-registrar" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Description / Responsibility Scope</label>
                    <input type="text" name="description" placeholder="Oversees student records, admissions and transcripts..." class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Module Permissions</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach($availableTabs as $tabKey => $tabName)
                        <label class="flex items-center space-x-2 text-xs p-2 rounded-lg bg-slate-50 border border-slate-200 hover:bg-slate-100 cursor-pointer">
                            <input type="checkbox" name="permissions[]" value="{{ $tabKey }}" class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span class="font-medium text-slate-700 truncate">{{ $tabName }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
