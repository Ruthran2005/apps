<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Payslip - {{ $teacher->user->name }} - {{ $payroll->month }} {{ $payroll->year }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen p-4 sm:p-8 text-slate-800 font-sans antialiased">

    <!-- Top Action Bar -->
    <div class="max-w-3xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('teacher.salary-slips') }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
            &larr; Back to Salary Slips
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>Print Official Payslip</span>
        </button>
    </div>

    <!-- Official Printable Payslip Document -->
    <div class="max-w-3xl mx-auto bg-white rounded-3xl p-8 sm:p-12 shadow-xl border border-slate-200 relative">
        <!-- Header -->
        <div class="border-b-2 border-emerald-600 pb-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $schoolName }}</h1>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mt-0.5">Faculty Remuneration & Payroll Statement</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-extrabold uppercase">
                        Salary Slip
                    </span>
                    <p class="text-xs font-mono font-bold text-slate-600 mt-1.5">{{ $payroll->month }} {{ $payroll->year }}</p>
                </div>
            </div>
        </div>

        <!-- Employee Details Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 mb-6 text-xs">
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Employee Name</span>
                <span class="font-extrabold text-slate-900">{{ $teacher->user->name }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Employee ID</span>
                <span class="font-mono font-bold text-slate-800">{{ $teacher->employee_id }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Department</span>
                <span class="font-bold text-slate-800">{{ $teacher->specialization ?? 'Academics' }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Disbursement Date</span>
                <span class="font-mono font-bold text-slate-800">{{ \Carbon\Carbon::parse($payroll->payment_date)->format('M d, Y') }}</span>
            </div>
        </div>

        <!-- Earnings & Deductions Breakdown -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8 text-xs">
            <!-- Earnings -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden">
                <div class="bg-emerald-50/80 px-4 py-2.5 font-bold text-emerald-900 border-b border-slate-200 flex justify-between">
                    <span>EARNINGS</span>
                    <span>AMOUNT (Rs. )</span>
                </div>
                <div class="p-4 space-y-2.5 divide-y divide-slate-100">
                    <div class="flex justify-between pt-1">
                        <span class="text-slate-600">Basic Academic Pay</span>
                        <span class="font-bold text-slate-900">Rs. {{ number_format($payroll->basic_salary, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2">
                        <span class="text-slate-600">House Rent Allowance (HRA)</span>
                        <span class="font-bold text-slate-900">Rs. {{ number_format($payroll->allowances * 0.6, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2">
                        <span class="text-slate-600">Dearness Allowance (DA)</span>
                        <span class="font-bold text-slate-900">Rs. {{ number_format($payroll->allowances * 0.4, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2 font-bold text-emerald-700 border-t border-slate-200">
                        <span>Total Gross Earnings</span>
                        <span>Rs. {{ number_format($payroll->basic_salary + $payroll->allowances, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Deductions -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden">
                <div class="bg-rose-50/80 px-4 py-2.5 font-bold text-rose-900 border-b border-slate-200 flex justify-between">
                    <span>DEDUCTIONS</span>
                    <span>AMOUNT (Rs. )</span>
                </div>
                <div class="p-4 space-y-2.5 divide-y divide-slate-100">
                    <div class="flex justify-between pt-1">
                        <span class="text-slate-600">Employees' Provident Fund (EPF)</span>
                        <span class="font-bold text-slate-900">Rs. {{ number_format($payroll->deductions * 0.7, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2">
                        <span class="text-slate-600">Professional Tax (PT)</span>
                        <span class="font-bold text-slate-900">Rs. {{ number_format($payroll->deductions * 0.3, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2 font-bold text-rose-700 border-t border-slate-200">
                        <span>Total Deductions</span>
                        <span>Rs. {{ number_format($payroll->deductions, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Net Pay Highlight -->
        <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border border-emerald-200 flex items-center justify-between mb-8">
            <div>
                <span class="block text-xs font-extrabold uppercase tracking-wider text-emerald-800">Net Take-Home Salary</span>
                <span class="text-[11px] text-slate-500">Credited via {{ $payroll->payment_method }} &bull; Ref: {{ $payroll->transaction_id ?? 'Direct Bank Transfer' }}</span>
            </div>
            <div class="text-right">
                <span class="text-3xl font-black text-slate-900">Rs. {{ number_format($payroll->net_salary, 2) }}</span>
            </div>
        </div>

        <!-- Signature Footer -->
        <div class="pt-8 border-t border-slate-200 grid grid-cols-2 gap-8 text-center text-xs">
            <div>
                <div class="h-12 border-b border-slate-300 w-44 mx-auto mb-2"></div>
                <span class="font-bold text-slate-800">Faculty Signature</span>
            </div>
            <div>
                <div class="h-12 border-b border-slate-300 w-44 mx-auto mb-2"></div>
                <span class="font-bold text-slate-800">Accounts & Bursar Officer</span>
            </div>
        </div>

        <!-- Security Disclaimer -->
        <p class="text-[10px] text-slate-400 text-center mt-8">This is a system-generated computer salary payslip of {{ $schoolName }}.</p>
    </div>

</body>
</html>
