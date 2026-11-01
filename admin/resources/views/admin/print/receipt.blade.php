<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Receipt - {{ $payment->receipt_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-10 px-4 text-slate-800">
    <div class="max-w-2xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-xl border border-slate-200">
        <!-- Print / Close Controls -->
        <div class="no-print flex items-center justify-between mb-8 pb-4 border-b border-slate-200">
            <a href="{{ route('admin.fees') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1">
                &larr; Back to Fee Management
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Print Official Receipt</span>
            </button>
        </div>

        <!-- School Header -->
        <div class="text-center pb-6 border-b-2 border-indigo-600">
            <h1 class="text-2xl font-black tracking-tight text-slate-900 uppercase">SchoolNexus International Academy</h1>
            <p class="text-xs font-semibold text-indigo-600 tracking-wider uppercase mt-1">Excellence in Education, Character & Innovation</p>
            <p class="text-[11px] text-slate-500 mt-1">742 Evergreen Campus Parkway &bull; admissions@schoolnexus.edu &bull; +1 (800) 555-0199</p>
            <div class="inline-block mt-3 px-4 py-1 bg-slate-900 text-white text-xs font-bold uppercase tracking-widest rounded-full">
                Official Fee Receipt
            </div>
        </div>

        <!-- Receipt Details Meta -->
        <div class="grid grid-cols-2 gap-4 py-6 border-b border-slate-200 text-xs">
            <div>
                <span class="text-slate-400">Receipt Number:</span>
                <div class="font-mono font-bold text-slate-900 text-sm">{{ $payment->receipt_number }}</div>
                <span class="text-slate-400 mt-2 block">Invoice Reference:</span>
                <div class="font-mono text-slate-700">{{ $payment->feeInvoice->invoice_number ?? 'N/A' }}</div>
            </div>
            <div class="text-right">
                <span class="text-slate-400">Payment Date:</span>
                <div class="font-bold text-slate-900 text-sm">{{ Carbon\Carbon::parse($payment->paid_at)->format('F d, Y') }}</div>
                <span class="text-slate-400 mt-2 block">Payment Method:</span>
                <div class="font-bold text-slate-700">{{ $payment->payment_mode }}</div>
            </div>
        </div>

        <!-- Student Meta -->
        <div class="py-4 border-b border-slate-200 grid grid-cols-2 gap-4 text-xs">
            <div>
                <span class="text-slate-400">Student Name:</span>
                <div class="text-base font-bold text-slate-900">{{ $payment->student->user->name ?? 'Student' }}</div>
                <div class="text-slate-500 font-mono">{{ $payment->student->admission_number ?? '' }}</div>
            </div>
            <div class="text-right">
                <span class="text-slate-400">Class & Standard:</span>
                <div class="text-base font-bold text-slate-900">{{ $payment->student->schoolClass->name ?? 'N/A' }}</div>
                <div class="text-slate-500">{{ $payment->student->section->name ?? 'Standard' }}</div>
            </div>
        </div>

        <!-- Line Item Table -->
        <div class="py-6 border-b border-slate-200">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 uppercase font-semibold border-b border-slate-200 pb-2">
                        <th class="py-2">Description</th>
                        <th class="py-2 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="py-3 font-semibold text-slate-800">{{ $payment->feeInvoice->title ?? 'Tuition Fee Payment' }}</td>
                        <td class="py-3 text-right font-bold text-slate-900">${{ number_format($payment->amount_paid, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Total and Balance -->
        <div class="py-4 space-y-1.5 text-xs">
            <div class="flex justify-between">
                <span class="text-slate-500">Invoice Total:</span>
                <span class="font-medium text-slate-800">${{ number_format($payment->feeInvoice->amount ?? $payment->amount_paid, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Total Paid:</span>
                <span class="font-medium text-slate-800">${{ number_format($payment->feeInvoice->paid_amount ?? $payment->amount_paid, 2) }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t border-slate-200 text-sm font-bold">
                <span class="text-slate-900">Current Payment Realized:</span>
                <span class="text-emerald-600 font-extrabold text-base">${{ number_format($payment->amount_paid, 2) }}</span>
            </div>
            @if(isset($payment->feeInvoice))
            <div class="flex justify-between text-xs text-rose-600 font-bold">
                <span>Remaining Due:</span>
                <span>${{ number_format(max(0, $payment->feeInvoice->amount - $payment->feeInvoice->paid_amount), 2) }}</span>
            </div>
            @endif
        </div>

        <!-- Signatures & Footer -->
        <div class="pt-12 mt-6 border-t border-slate-200 grid grid-cols-2 gap-8 text-xs text-center">
            <div>
                <div class="border-b border-slate-300 pb-1 mb-1 font-mono text-[11px] text-slate-500">{{ $payment->received_by ?? 'Central Finance' }}</div>
                <div class="text-slate-400 font-semibold uppercase text-[10px]">Authorized Cashier</div>
            </div>
            <div>
                <div class="border-b border-slate-300 pb-1 mb-1 font-mono text-[11px] text-slate-500">Official Institutional Seal</div>
                <div class="text-slate-400 font-semibold uppercase text-[10px]">Accounts & Bursar Office</div>
            </div>
        </div>
    </div>
</body>
</html>
