@extends('layouts.app')

@section('title', 'Finance & Fees')
@section('header_title', 'Fee & Invoicing Oversight')

@section('content')
<div class="space-y-6">

    <!-- KPI Totals -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Billed Fees</span>
            <p class="text-2xl font-extrabold text-slate-900 mt-2">${{ number_format($totalBilled, 2) }}</p>
            <p class="text-xs text-slate-400 mt-1">Across all student accounts</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Collected Revenue</span>
            <p class="text-2xl font-extrabold text-emerald-600 mt-2">${{ number_format($totalPaid, 2) }}</p>
            <p class="text-xs text-emerald-600 font-semibold mt-1">Settled & Verified</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Pending / Outstanding</span>
            <p class="text-2xl font-extrabold text-amber-600 mt-2">${{ number_format($totalPending, 2) }}</p>
            <p class="text-xs text-slate-400 mt-1">Awaiting settlement</p>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900">Student Fee Invoices</h3>
            <span class="text-xs text-slate-500">Visible to students in Student Portal</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Invoice #</th>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">Due Date</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $inv)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-mono font-bold text-xs text-indigo-600">
                            {{ $inv->invoice_number }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $inv->student->user->name ?? 'Student' }}</p>
                            <p class="text-xs text-slate-400">{{ $inv->student->admission_number ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-700">
                            {{ $inv->title }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">
                            {{ $inv->due_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">${{ number_format($inv->amount, 2) }}</p>
                            @if($inv->paid_amount > 0 && $inv->status != 'paid')
                                <p class="text-[11px] text-slate-400">Paid: ${{ number_format($inv->paid_amount, 2) }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $inv->status == 'paid' ? 'bg-emerald-100 text-emerald-800' : ($inv->status == 'partial' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                {{ $inv->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($inv->status != 'paid')
                                <form method="POST" action="{{ route('admin.finance.pay', $inv->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm">
                                        Mark Paid
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-slate-400 font-semibold">{{ $inv->payment_method ?? 'Settled' }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">No invoices recorded</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())
        <div class="p-4 bg-slate-50 border-t border-slate-100">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
