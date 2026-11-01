@extends('layouts.app')

@section('title', 'Transport Management - SchoolNexus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-slate-900 tracking-tight">Transport Management</h1>
            <p class="text-sm text-slate-500 mt-1">Fleet vehicles, driver licensing, transport routes, and student pickup allocations.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="$dispatch('open-modal-vehicle')" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Vehicle</span>
            </button>
            <button @click="$dispatch('open-modal-route')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                <span>Add Route</span>
            </button>
            <button @click="$dispatch('open-modal-allocate')" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/30 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                <span>Allocate Student</span>
            </button>
        </div>
    </div>

    <!-- Vehicles Fleet Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($vehicles as $veh)
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="font-mono text-xs font-bold px-2.5 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200">{{ $veh->vehicle_number }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $veh->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                        {{ $veh->status }}
                    </span>
                </div>
                <h3 class="text-base font-bold text-slate-900 mt-1">{{ $veh->model }}</h3>
                <div class="text-xs text-slate-500 mt-1 font-semibold">Capacity: {{ $veh->seating_capacity }} Seats</div>
                
                <div class="mt-4 pt-3 border-t border-slate-100 space-y-1 text-xs text-slate-600">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Driver:</span>
                        <span class="font-bold text-slate-800">{{ $veh->driver_name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Contact:</span>
                        <span class="font-mono text-slate-700">{{ $veh->driver_phone }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-end">
                <form action="{{ route('admin.transport.vehicles.destroy', $veh->id) }}" method="POST" onsubmit="return confirm('Remove vehicle from fleet?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold p-1 hover:bg-rose-50 rounded">Delete Vehicle</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400">
            No transport fleet registered.
        </div>
        @endforelse
    </div>

    <!-- Routes Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Transport Routes & Bus Stops</h2>
                <p class="text-xs text-slate-500">Pick-up schedules, allocated buses, route stops and transport fares.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Route Name</th>
                        <th class="px-5 py-3.5">Assigned Vehicle</th>
                        <th class="px-5 py-3.5">Terminals</th>
                        <th class="px-5 py-3.5">Pickup Stops</th>
                        <th class="px-5 py-3.5">Timings</th>
                        <th class="px-5 py-3.5">Fare</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($routes as $rt)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5 font-bold text-slate-900">{{ $rt->route_name }}</td>
                        <td class="px-5 py-3.5 font-mono text-xs text-indigo-600 font-semibold">{{ $rt->vehicle->vehicle_number ?? 'Unassigned' }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-700">{{ $rt->start_location }} &rarr; {{ $rt->end_location }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-500 max-w-xs truncate" title="{{ $rt->stops }}">{{ $rt->stops ?? 'Standard corridor stops' }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-600 font-medium">{{ $rt->pickup_time ?? '07:30' }} - {{ $rt->drop_time ?? '16:00' }}</td>
                        <td class="px-5 py-3.5 font-bold text-emerald-600">${{ number_format($rt->fare, 2) }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <form action="{{ route('admin.transport.routes.destroy', $rt->id) }}" method="POST" onsubmit="return confirm('Delete this route?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold p-1 hover:bg-rose-50 rounded">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">No routes configured yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Student Allocations Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Student Transport Allocations</h2>
                <p class="text-xs text-slate-500">Active student bus passes, pickup points, and assigned vehicles.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Student</th>
                        <th class="px-5 py-3.5">Assigned Route</th>
                        <th class="px-5 py-3.5">Bus / Vehicle</th>
                        <th class="px-5 py-3.5">Boarding / Drop Stop</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Revoke</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($allocations as $al)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-slate-900">{{ $al->student->user->name ?? 'Student' }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $al->student->admission_number ?? '' }}</div>
                        </td>
                        <td class="px-5 py-3.5 font-medium text-slate-800">{{ $al->route->route_name ?? 'N/A' }}</td>
                        <td class="px-5 py-3.5 font-mono text-xs text-indigo-600">{{ $al->route->vehicle->vehicle_number ?? 'Bus' }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-700 font-semibold">{{ $al->pickup_stop ?? 'Main stop' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Active Pass</span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <form action="{{ route('admin.transport.allocations.destroy', $al->id) }}" method="POST" onsubmit="return confirm('Revoke student transport allocation?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold p-1 hover:bg-rose-50 rounded">Revoke</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-400 text-sm">No student transport allocations.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $allocations->links() }}
        </div>
    </div>
</div>

<!-- Modal 1: Add Vehicle -->
<div x-data="{ open: false }" @open-modal-vehicle.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Add Fleet Vehicle</h3>
            <p class="text-xs text-slate-500 mb-4">Register an institutional bus or van with driver details.</p>
            <form action="{{ route('admin.transport.vehicles.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Registration / Bus Number</label>
                    <input type="text" name="vehicle_number" required placeholder="e.g. KA-04-AB-1234" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Model & Make</label>
                        <input type="text" name="model" required placeholder="Tata Starbus 40-Seater" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Seating Capacity</label>
                        <input type="number" name="seating_capacity" value="40" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Driver Name</label>
                        <input type="text" name="driver_name" required placeholder="John Driver" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Driver Phone</label>
                        <input type="text" name="driver_phone" required placeholder="+1 555-0199" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Driver Commercial License</label>
                    <input type="text" name="driver_license" placeholder="DL-123456789" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="active">Active & In Service</option>
                        <option value="maintenance">Under Maintenance</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-sm font-semibold hover:bg-slate-800">Save Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Add Route -->
<div x-data="{ open: false }" @open-modal-route.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Configure Route</h3>
            <p class="text-xs text-slate-500 mb-4">Set up transport corridor, intermediate stops and allocated bus.</p>
            <form action="{{ route('admin.transport.routes.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Route Name</label>
                    <input type="text" name="route_name" required placeholder="Route 3: Eastern Express" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Assign Fleet Vehicle</label>
                    <select name="vehicle_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">No vehicle assigned yet</option>
                        @foreach($vehicles as $veh)
                            <option value="{{ $veh->id }}">{{ $veh->vehicle_number }} - {{ $veh->model }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Start Location</label>
                        <input type="text" name="start_location" required placeholder="East Gate" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Destination</label>
                        <input type="text" name="end_location" value="Main Campus Hub" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Intermediate Pickup Stops</label>
                    <textarea name="stops" rows="2" placeholder="Comma-separated stops: Stop A, Stop B, Crossroad..." class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Route Fare ($)</label>
                    <input type="number" step="0.01" name="fare" value="450.00" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">Save Route</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 3: Allocate Student -->
<div x-data="{ open: false }" @open-modal-allocate.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Allocate Student to Route</h3>
            <p class="text-xs text-slate-500 mb-4">Grant student bus pass for institutional transit.</p>
            <form action="{{ route('admin.transport.allocations.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Select Student</label>
                    <select name="student_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                        @foreach($students as $st)
                            <option value="{{ $st->id }}">{{ $st->user->name ?? 'Student' }} ({{ $st->admission_number }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Select Bus Route</label>
                    <select name="transport_route_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                        @foreach($routes as $r)
                            <option value="{{ $r->id }}">{{ $r->route_name }} ({{ $r->vehicle->vehicle_number ?? 'Bus' }}) - ${{ number_format($r->fare, 2) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pickup / Boarding Stop</label>
                    <input type="text" name="pickup_stop" required placeholder="e.g. Lotus Garden Junction" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700">Confirm Allocation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
