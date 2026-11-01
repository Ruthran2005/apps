@extends('layouts.app')

@section('title', 'Salary Slips — Faculty Workspace')
@section('header_title', 'Monthly Faculty Salary Slips & Remuneration')

@section('content')
<div class="space-y-6">

    <!-- Top Action Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/90 backdrop-blur-sm p-6 rounded-3xl border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)]">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60">Institutional Payroll</span>
            </div>
            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Faculty Salary Slips</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Review your monthly compensation breakdown, allowances, tax deductions, and download official electronic payslips.</p>
        </div>
    </div>

    <!-- Salary Slips Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($payrolls as $pay)
            <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200">
                            {{ $pay->month }} {{ $pay->year }}
                        </span>
                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold uppercase tracking-wider bg-emerald-500 text-white shadow-2xs">
                            {{ $pay->status }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">Net Disbursed Salary</span>
                        <p class="font-display text-3xl font-extrabold text-slate-900 mt-0.5">Rs. {{ number_format($pay->net_salary, 2) }}</p>
                    </div>

                    <div class="space-y-2 p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Basic Pay</span>
                            <span class="font-bold text-slate-800">Rs. {{ number_format($pay->basic_salary, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Allowances (DA, HRA)</span>
                            <span class="font-bold text-emerald-600">+Rs. {{ number_format($pay->allowances, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Deductions (EPF, Tax)</span>
                            <span class="font-bold text-rose-600">-Rs. {{ number_format($pay->deductions, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-4 text-[11px] text-slate-500 space-y-1">
                        <div class="flex justify-between">
                            <span>Payment Mode:</span>
                            <span class="font-semibold text-slate-700">{{ $pay->payment_method }}</span>
                        </div>
                        @if($pay->transaction_id)
                            <div class="flex justify-between">
                                <span>Reference:</span>
                                <span class="font-mono text-slate-700">{{ $pay->transaction_id }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] text-slate-400 font-mono">
                        {{ \Carbon\Carbon::parse($pay->payment_date)->format('M d, Y') }}
                    </span>
                    <a href="{{ route('teacher.salary-slips.print', $pay->id) }}" target="_blank" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md transition active:scale-95 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        <span>Print Payslip</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white/70 rounded-3xl border border-dashed border-slate-300">
                <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="font-display font-bold text-slate-800 text-base">No Salary Slips Generated Yet</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Salary slips for processed monthly payroll runs will appear here automatically.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
