@extends('layouts.app')

@section('title', 'Fees & Invoices')
@section('header_title', 'Tuition & Invoicing Statement')

@section('content')
<div class="space-y-6">

    <!-- Fee Totals -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Billed Fees</span>
            <p class="text-2xl font-extrabold text-slate-900 mt-2">${{ number_format($totalAmount, 2) }}</p>
            <p class="text-xs text-slate-400 mt-1">Academic year 2026</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Paid to Date</span>
            <p class="text-2xl font-extrabold text-emerald-600 mt-2">${{ number_format($paidAmount, 2) }}</p>
            <p class="text-xs text-emerald-600 font-semibold mt-1">Receipts cleared</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Outstanding Balance</span>
            <p class="text-2xl font-extrabold text-amber-600 mt-2">${{ number_format($pendingAmount, 2) }}</p>
            <p class="text-xs text-slate-400 mt-1">Due according to schedule</p>
        </div>
    </div>

    <!-- Fee Invoices Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900">Invoices & Statements</h3>
            <span class="text-xs text-slate-400">Institutional Bursar Office</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Invoice #</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">Due Date</th>
                        <th class="px-6 py-4">Total Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Payment Info</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $inv)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-mono font-bold text-xs text-purple-700">
                            {{ $inv->invoice_number }}
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-900 text-xs">
                            {{ $inv->title }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">
                            {{ $inv->due_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">
                            ${{ number_format($inv->amount, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $inv->status == 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $inv->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 text-right">
                            @if($inv->status == 'paid')
                                <span>{{ $inv->payment_method ?? 'Bank Transfer' }} ({{ $inv->payment_date ? $inv->payment_date->format('M d') : 'Verified' }})</span>
                            @else
                                <span class="text-amber-600 font-semibold">Payment Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            No billing statements currently recorded.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
