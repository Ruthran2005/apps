@extends('layouts.app')

@section('title', 'Staff Payroll - SchoolNexus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-slate-900 tracking-tight">Staff Payroll & Salary Slips</h1>
            <p class="text-sm text-slate-500 mt-1">Faculty salary records, allowances, tax/pension deductions, disbursement status, and pay slips.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="$dispatch('open-modal-payroll')" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-600/30 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Generate Salary Slip</span>
            </button>
        </div>
    </div>

    <!-- Payroll Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Disbursed</div>
            <div class="text-2xl font-bold font-display text-emerald-600 mt-1">${{ number_format($stats['total_disbursed'], 2) }}</div>
            <div class="text-xs text-slate-500 mt-1">{{ $stats['disbursed_slips'] }} monthly payments settled</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pending Disbursement</div>
            <div class="text-2xl font-bold font-display text-amber-600 mt-1">${{ number_format($stats['pending_salaries'], 2) }}</div>
            <div class="text-xs text-amber-600 font-medium mt-1">Awaiting bank wire approval</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Registered Faculty</div>
            <div class="text-2xl font-bold font-display text-slate-900 mt-1">{{ $teachers->count() }}</div>
            <div class="text-xs text-indigo-600 font-medium mt-1">Full-time & specialist educators</div>
        </div>
    </div>

    <!-- Payroll Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Faculty Salary Disbursal Ledger</h2>
                <p class="text-xs text-slate-500">Gross compensation breakdown, allowances, deductions, and electronic pay slip generation.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Faculty Member</th>
                        <th class="px-5 py-3.5">Period</th>
                        <th class="px-5 py-3.5">Basic Salary</th>
                        <th class="px-5 py-3.5">Allowances</th>
                        <th class="px-5 py-3.5">Deductions</th>
                        <th class="px-5 py-3.5">Net Salary</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payrolls as $pay)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-slate-900">{{ $pay->teacher->user->name ?? 'Teacher' }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $pay->teacher->employee_id ?? '' }}</div>
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $pay->month }} {{ $pay->year }}</td>
                        <td class="px-5 py-3.5 font-medium text-slate-800">${{ number_format($pay->basic_salary, 2) }}</td>
                        <td class="px-5 py-3.5 text-xs text-emerald-600 font-semibold">+Rs. {{ number_format($pay->allowances, 2) }}</td>
                        <td class="px-5 py-3.5 text-xs text-rose-600 font-semibold">-Rs. {{ number_format($pay->deductions, 2) }}</td>
                        <td class="px-5 py-3.5 font-bold text-slate-900">${{ number_format($pay->net_salary, 2) }}</td>
                        <td class="px-5 py-3.5">
                            @if($pay->status === 'paid')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Disbursed</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">Pending Wire</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-2">
                            @if($pay->status === 'pending')
                                <form action="{{ route('admin.payroll.pay', $pay->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                        Mark Disbursed
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.payroll.payslip', $pay->id) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold rounded-lg transition border border-slate-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                <span>Payslip</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-8 text-center text-slate-400 text-sm">No payroll entries created yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $payrolls->links() }}
        </div>
    </div>
</div>

<!-- Modal: Generate Payroll -->
<div x-data="{ open: false }" @open-modal-payroll.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Generate Faculty Salary Slip</h3>
            <p class="text-xs text-slate-500 mb-4">Calculate basic compensation, perks and deductions.</p>
            <form action="{{ route('admin.payroll.generate') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Select Teacher / Staff</label>
                    <select name="teacher_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        @foreach($teachers as $tch)
                            <option value="{{ $tch->id }}">{{ $tch->user->name ?? 'Teacher' }} ({{ $tch->employee_id }}) - Base: Rs. {{ number_format($tch->salary ?? 4500, 2) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Month</label>
                        <select name="month" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                <option value="{{ $m }}" {{ $m === 'October' ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Year</label>
                        <input type="number" name="year" value="2026" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Basic Salary ($)</label>
                    <input type="number" step="0.01" name="basic_salary" value="4500.00" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Allowances ($)</label>
                        <input type="number" step="0.01" name="allowances" value="500.00" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Deductions ($)</label>
                        <input type="number" step="0.01" name="deductions" value="250.00" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Payment Method</label>
                    <select name="payment_method" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="Direct Bank Transfer">Direct Bank Wire</option>
                        <option value="Bank Cheque">Bank Cheque</option>
                        <option value="Cash">Cash Voucher</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">Generate Slip</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
