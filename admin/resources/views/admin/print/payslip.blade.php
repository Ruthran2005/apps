<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Payslip - {{ $payroll->teacher->user->name ?? 'Staff' }} ({{ $payroll->month }} {{ $payroll->year }})</title>
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
        <!-- Controls -->
        <div class="no-print flex items-center justify-between mb-8 pb-4 border-b border-slate-200">
            <a href="{{ route('admin.payroll') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1">
                &larr; Back to Staff Payroll
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Print Official Payslip</span>
            </button>
        </div>

        <!-- School Header -->
        <div class="text-center pb-6 border-b-2 border-indigo-600">
            <h1 class="text-2xl font-black tracking-tight text-slate-900 uppercase">SchoolNexus International Academy</h1>
            <p class="text-xs font-semibold text-indigo-600 tracking-wider uppercase mt-1">Faculty & Staff Compensation Statement</p>
            <div class="inline-block mt-3 px-4 py-1 bg-slate-900 text-white text-xs font-bold uppercase tracking-widest rounded-full">
                Monthly Salary Slip: {{ $payroll->month }} {{ $payroll->year }}
            </div>
        </div>

        <!-- Employee Meta -->
        <div class="grid grid-cols-2 gap-4 py-6 border-b border-slate-200 text-xs">
            <div>
                <span class="text-slate-400 block">Faculty Member:</span>
                <span class="font-bold text-slate-900 text-base">{{ $payroll->teacher->user->name ?? 'Staff' }}</span>
                <span class="text-slate-500 font-mono block mt-1">ID: {{ $payroll->teacher->employee_id ?? 'TCH-001' }}</span>
                <span class="text-slate-500 block">Designation: {{ $payroll->teacher->specialization ?? 'Senior Faculty' }}</span>
            </div>
            <div class="text-right">
                <span class="text-slate-400 block">Disbursal Status:</span>
                <span class="font-bold uppercase text-xs px-2.5 py-0.5 rounded-full inline-block mt-1 {{ $payroll->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                    {{ $payroll->status }}
                </span>
                <span class="text-slate-400 block mt-2">Disbursement Date:</span>
                <span class="font-semibold text-slate-800">{{ $payroll->payment_date ? Carbon\Carbon::parse($payroll->payment_date)->format('M d, Y') : 'Pending Wire' }}</span>
                <span class="text-slate-400 block mt-1">Transaction Ref:</span>
                <span class="font-mono text-slate-600 text-[11px]">{{ $payroll->transaction_id ?? 'N/A' }}</span>
            </div>
        </div>

        <!-- Earnings & Deductions Breakdown -->
        <div class="py-6 border-b border-slate-200 grid grid-cols-2 gap-8 text-xs">
            <!-- Earnings -->
            <div class="space-y-2">
                <h4 class="font-bold text-slate-900 uppercase text-[11px] border-b border-slate-200 pb-1">Earnings & Allowances</h4>
                <div class="flex justify-between">
                    <span class="text-slate-600">Basic Academic Pay:</span>
                    <span class="font-bold text-slate-800">${{ number_format($payroll->basic_salary, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Conveyance & Medical Allowance:</span>
                    <span class="font-bold text-slate-800">${{ number_format($payroll->allowances, 2) }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-slate-100 font-bold text-indigo-700">
                    <span>Gross Earnings:</span>
                    <span>${{ number_format($payroll->basic_salary + $payroll->allowances, 2) }}</span>
                </div>
            </div>

            <!-- Deductions -->
            <div class="space-y-2">
                <h4 class="font-bold text-slate-900 uppercase text-[11px] border-b border-slate-200 pb-1">Statutory Deductions</h4>
                <div class="flex justify-between">
                    <span class="text-slate-600">Provident Pension Fund / Tax:</span>
                    <span class="font-bold text-rose-600">-${{ number_format($payroll->deductions, 2) }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-slate-100 font-bold text-rose-700">
                    <span>Total Deductions:</span>
                    <span>-${{ number_format($payroll->deductions, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Net Salary Box -->
        <div class="py-6 bg-slate-50 rounded-2xl p-4 my-6 flex items-center justify-between border border-slate-200">
            <div>
                <span class="text-xs uppercase font-extrabold text-slate-500 tracking-wider">Net Amount Disbursed</span>
                <p class="text-[11px] text-slate-400">Credited directly to employee salary bank account.</p>
            </div>
            <div class="text-3xl font-black text-emerald-600 font-display">
                ${{ number_format($payroll->net_salary, 2) }}
            </div>
        </div>

        <!-- Signatures -->
        <div class="pt-12 mt-6 border-t border-slate-200 grid grid-cols-2 gap-8 text-xs text-center">
            <div>
                <div class="border-b border-slate-300 pb-1 mb-1 font-semibold text-slate-700">Bursar & Payroll Comptroller</div>
                <div class="text-slate-400 text-[10px]">Finance Division</div>
            </div>
            <div>
                <div class="border-b border-slate-300 pb-1 mb-1 font-semibold text-slate-700">Dr. Eleanor Vance, Ph.D.</div>
                <div class="text-slate-400 text-[10px]">Principal & Trustee</div>
            </div>
        </div>
    </div>
</body>
</html>
