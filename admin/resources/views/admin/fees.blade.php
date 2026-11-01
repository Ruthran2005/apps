@extends('layouts.app')

@section('title', 'Fee Management - SchoolNexus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-slate-900 tracking-tight">Fee Management</h1>
            <p class="text-sm text-slate-500 mt-1">Institutional fee structures, student invoices, electronic payment receipts & pending dues.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="$dispatch('open-modal-structure')" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Fee Structure</span>
            </button>
            <button @click="$dispatch('open-modal-payment')" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-600/30 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>Collect Payment</span>
            </button>
        </div>
    </div>

    <!-- Financial KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Invoiced</div>
            <div class="text-2xl font-bold font-display text-slate-900 mt-1">${{ number_format($stats['total_invoiced'], 2) }}</div>
            <div class="text-xs text-indigo-600 font-medium mt-2 flex items-center gap-1">
                <span>{{ $invoices->total() }} active invoices generated</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Collected</div>
            <div class="text-2xl font-bold font-display text-emerald-600 mt-1">${{ number_format($stats['total_collected'], 2) }}</div>
            <div class="text-xs text-emerald-600 font-medium mt-2 flex items-center gap-1">
                <span>{{ $stats['paid_invoices_count'] }} fully settled accounts</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Outstanding Dues</div>
            <div class="text-2xl font-bold font-display text-rose-600 mt-1">${{ number_format($stats['total_pending'], 2) }}</div>
            <div class="text-xs text-rose-600 font-medium mt-2 flex items-center gap-1">
                <span>{{ $stats['pending_invoices_count'] }} accounts pending settlement</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Collection Efficiency</div>
            @php
                $eff = $stats['total_invoiced'] > 0 ? round(($stats['total_collected'] / $stats['total_invoiced']) * 100, 1) : 0;
            @endphp
            <div class="text-2xl font-bold font-display text-slate-900 mt-1">{{ $eff }}%</div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-1.5 rounded-full" style="width: {{ min($eff, 100) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Fee Structure Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Institutional Fee Structures</h2>
                <p class="text-xs text-slate-500">Configured tuition tiers, lab dues, transport and activity funds by academic grade.</p>
            </div>
            <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg">{{ $feeStructures->count() }} Tiers</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Class / Standard</th>
                        <th class="px-5 py-3.5">Fee Category</th>
                        <th class="px-5 py-3.5">Amount</th>
                        <th class="px-5 py-3.5">Frequency</th>
                        <th class="px-5 py-3.5">Due Date</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($feeStructures as $item)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5 font-bold text-slate-900">{{ $item->schoolClass->name ?? 'All Grades' }}</td>
                        <td class="px-5 py-3.5 font-medium text-slate-800">{{ $item->fee_type }}</td>
                        <td class="px-5 py-3.5 font-bold text-emerald-600">${{ number_format($item->amount, 2) }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-md bg-slate-100 text-slate-700 border border-slate-200">{{ $item->frequency }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-500">{{ $item->due_date ? Carbon\Carbon::parse($item->due_date)->format('M d, Y') : 'Per schedule' }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <form action="{{ route('admin.fees.structure.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Remove fee tier?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold p-1 hover:bg-rose-50 rounded">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-400 text-sm">No fee structure configured yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Student Invoices & Pending Dues -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Student Invoices & Dues Ledger</h2>
                <p class="text-xs text-slate-500">Live invoice balance tracking, settlement status, and receipt printing.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Invoice #</th>
                        <th class="px-5 py-3.5">Student</th>
                        <th class="px-5 py-3.5">Class</th>
                        <th class="px-5 py-3.5">Description</th>
                        <th class="px-5 py-3.5">Total Amount</th>
                        <th class="px-5 py-3.5">Paid</th>
                        <th class="px-5 py-3.5">Balance Due</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $inv)
                    @php
                        $due = max(0, $inv->amount - $inv->paid_amount);
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-indigo-600">{{ $inv->invoice_number }}</td>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-slate-900">{{ $inv->student->user->name ?? 'Student' }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $inv->student->admission_number ?? '' }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">{{ $inv->student->schoolClass->name ?? 'N/A' }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-700">{{ $inv->title }}</td>
                        <td class="px-5 py-3.5 font-semibold text-slate-900">${{ number_format($inv->amount, 2) }}</td>
                        <td class="px-5 py-3.5 font-semibold text-emerald-600">${{ number_format($inv->paid_amount, 2) }}</td>
                        <td class="px-5 py-3.5 font-bold {{ $due > 0 ? 'text-rose-600' : 'text-slate-400' }}">${{ number_format($due, 2) }}</td>
                        <td class="px-5 py-3.5">
                            @if($inv->status === 'paid')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Paid in Full</span>
                            @elseif($inv->status === 'partial')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200">Partial Payment</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-rose-50 text-rose-700 border border-rose-200">Unpaid / Due</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-8 text-center text-slate-400 text-sm">No invoices recorded in ledger.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $invoices->links() }}
        </div>
    </div>

    <!-- Recent Electronic Receipts -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Dispatched Payment Receipts</h2>
                <p class="text-xs text-slate-500">Official printable receipts with unique sequence IDs and transaction references.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Receipt #</th>
                        <th class="px-5 py-3.5">Student</th>
                        <th class="px-5 py-3.5">Amount Paid</th>
                        <th class="px-5 py-3.5">Payment Mode</th>
                        <th class="px-5 py-3.5">Paid Date</th>
                        <th class="px-5 py-3.5">Received By</th>
                        <th class="px-5 py-3.5 text-right">Receipt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $p)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-slate-900">{{ $p->receipt_number }}</td>
                        <td class="px-5 py-3.5 font-medium text-slate-800">{{ $p->student->user->name ?? 'Student' }}</td>
                        <td class="px-5 py-3.5 font-bold text-emerald-600">${{ number_format($p->amount_paid, 2) }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">{{ $p->payment_mode }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-500">{{ Carbon\Carbon::parse($p->paid_at)->format('M d, Y') }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">{{ $p->received_by ?? 'Finance Desk' }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.fees.receipt', $p->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition border border-indigo-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                <span>Print Receipt</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">No payment receipts yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal 1: Add Fee Structure -->
<div x-data="{ open: false }" @open-modal-structure.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Create Fee Structure</h3>
            <p class="text-xs text-slate-500 mb-4">Define standard tuition or lab fee component for a class.</p>
            <form action="{{ route('admin.fees.structure.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Target Class</label>
                    <select name="school_class_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Fee Category / Title</label>
                    <input type="text" name="fee_type" required placeholder="e.g. Tuition Fee, Science Lab, Sports" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Amount ($)</label>
                        <input type="number" step="0.01" name="amount" required placeholder="1200.00" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Frequency</label>
                        <select name="frequency" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="Term">Per Term</option>
                            <option value="Annual">Annual</option>
                            <option value="Monthly">Monthly</option>
                            <option value="Quarterly">Quarterly</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Due Date</label>
                    <input type="date" name="due_date" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Description / Notes</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Optional policy details..."></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">Save Fee Structure</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Collect Payment -->
<div x-data="{ open: false }" @open-modal-payment.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Collect Fee Payment</h3>
            <p class="text-xs text-slate-500 mb-4">Record student settlement and issue an immediate payment receipt.</p>
            <form action="{{ route('admin.fees.payment.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Select Student Invoice</label>
                    <select name="fee_invoice_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                        @foreach($invoices->where('status', '!=', 'paid') as $inv)
                            <option value="{{ $inv->id }}">{{ $inv->invoice_number }} - {{ $inv->student->user->name ?? 'Student' }} (Due: Rs. {{ number_format($inv->amount - $inv->paid_amount, 2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Payment Amount ($)</label>
                    <input type="number" step="0.01" name="amount_paid" required placeholder="500.00" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Payment Mode</label>
                    <select name="payment_mode" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="Cash">Cash at Counter</option>
                        <option value="Online UPI">Online UPI Transfer</option>
                        <option value="Credit / Debit Card">Credit / Debit Card (POS)</option>
                        <option value="Net Banking">Direct Bank Transfer</option>
                        <option value="Cheque">Bank Cheque / Draft</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Transaction Ref / Cheque No.</label>
                    <input type="text" name="transaction_reference" placeholder="e.g. TXN98765432 or Cheque #10294" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700">Record & Issue Receipt</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
