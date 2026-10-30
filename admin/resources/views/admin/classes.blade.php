@extends('layouts.app')

@section('title', 'Classes & Subjects')
@section('header_title', 'Curriculum & Structure')

@section('content')
<div class="space-y-8">

    <!-- Class Standards Cards -->
    <div>
        <h2 class="text-base font-bold text-slate-900 mb-4">Active Academic Classes & Sections</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($classes as $c)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">{{ $c->code }}</span>
                        <h3 class="text-lg font-bold text-slate-900 mt-1">{{ $c->name }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-sm">
                        {{ $c->sections->count() }} Sec
                    </div>
                </div>

                <p class="text-xs text-slate-500 mt-3">{{ $c->description ?? 'No curriculum notes recorded.' }}</p>

                <div class="mt-4 space-y-2.5">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Sections & Rooms</span>
                    @foreach($c->sections as $sec)
                    <div class="p-3 bg-slate-50 rounded-xl flex items-center justify-between text-xs border border-slate-100">
                        <div>
                            <span class="font-bold text-slate-800">{{ $sec->name }}</span>
                            <span class="text-slate-400 block text-[11px]">{{ $sec->room_number ?? 'Room TBD' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-indigo-600">{{ $sec->students->count() }}</span>
                            <span class="text-slate-400 text-[11px]">/ {{ $sec->capacity }} Students</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Subjects Catalog -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-900">Accredited Subjects & Teaching Assignments</h3>
            <p class="text-xs text-slate-500">Shared course directory across all portals</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Subject Name</th>
                        <th class="px-6 py-4">Course Code</th>
                        <th class="px-6 py-4">Credits</th>
                        <th class="px-6 py-4">Assigned Faculty</th>
                        <th class="px-6 py-4">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($subjects as $sub)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $sub->name }}
                        </td>
                        <td class="px-6 py-4 font-mono text-xs font-semibold text-indigo-600">
                            {{ $sub->code }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-xs font-bold text-slate-700">
                                {{ $sub->credits }} Credits
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <div class="flex flex-wrap gap-1">
                                @forelse($sub->teacherSubjectAssignments as $assign)
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 font-semibold border border-emerald-100">
                                        {{ $assign->teacher->user->name ?? 'Faculty' }}
                                    </span>
                                @empty
                                    <span class="text-slate-400">Unassigned</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 max-w-xs truncate">
                            {{ $sub->description }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
