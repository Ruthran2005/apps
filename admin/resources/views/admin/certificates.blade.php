@extends('layouts.app')

@section('title', 'ID Cards & Certificates - SchoolNexus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-slate-900 tracking-tight">ID Cards & Official Certificates</h1>
            <p class="text-sm text-slate-500 mt-1">Issue verified Transfer Certificates (TC), Bonafide testimonials, character endorsements, and student ID cards.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="$dispatch('open-modal-cert')" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-600/30 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                <span>Generate Certificate</span>
            </button>
        </div>
    </div>

    <!-- Quick Preview Templates -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-indigo-900 to-slate-900 p-5 rounded-2xl text-white shadow-md relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-10 -bottom-10 w-36 h-36 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>
            <div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/20 text-indigo-200">Official Document</span>
                <h3 class="text-lg font-bold mt-2">Transfer Certificate (TC)</h3>
                <p class="text-xs text-indigo-200 mt-1">Formal academic release document including conduct rating, attendance clearance and curriculum record.</p>
            </div>
            <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs text-indigo-300">
                <span>With institutional seal</span>
                <span class="font-bold text-white">Legal Template</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-violet-900 to-slate-900 p-5 rounded-2xl text-white shadow-md relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-10 -bottom-10 w-36 h-36 bg-violet-500/20 rounded-full blur-2xl pointer-events-none"></div>
            <div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/20 text-violet-200">Institutional Attestation</span>
                <h3 class="text-lg font-bold mt-2">Bonafide Certificate</h3>
                <p class="text-xs text-violet-200 mt-1">Official certificate of enrollment for visa applications, passport authorities, and competitive scholarships.</p>
            </div>
            <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs text-violet-300">
                <span>Instant PDF / Print</span>
                <span class="font-bold text-white">Verified Stamp</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-900 to-slate-900 p-5 rounded-2xl text-white shadow-md relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-10 -bottom-10 w-36 h-36 bg-emerald-500/20 rounded-full blur-2xl pointer-events-none"></div>
            <div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/20 text-emerald-200">Smart Campus Pass</span>
                <h3 class="text-lg font-bold mt-2">Student & Faculty ID Cards</h3>
                <p class="text-xs text-emerald-200 mt-1">Compact badge preview with student photo placeholder, QR verification code, blood group and emergency contact.</p>
            </div>
            <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs text-emerald-300">
                <span>Printable Card format</span>
                <span class="font-bold text-white">QR Secured</span>
            </div>
        </div>
    </div>

    <!-- Generated Certificates History Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Issued Certificates & ID Register</h2>
                <p class="text-xs text-slate-500">Record of verified institutional certificates, sequence codes and printing actions.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Certificate #</th>
                        <th class="px-5 py-3.5">Type</th>
                        <th class="px-5 py-3.5">Recipient</th>
                        <th class="px-5 py-3.5">Class / Role</th>
                        <th class="px-5 py-3.5">Issue Date</th>
                        <th class="px-5 py-3.5">Reason / Purpose</th>
                        <th class="px-5 py-3.5 text-right">Print Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($certificates as $cert)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-indigo-600">{{ $cert->certificate_number }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider 
                                {{ $cert->certificate_type === 'transfer_certificate' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 
                                   ($cert->certificate_type === 'id_card' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-indigo-50 text-indigo-700 border border-indigo-200') }}">
                                {{ str_replace('_', ' ', $cert->certificate_type) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-bold text-slate-900">
                            {{ $cert->student->user->name ?? ($cert->teacher->user->name ?? 'Recipient') }}
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">
                            {{ $cert->student->schoolClass->name ?? ($cert->teacher ? 'Faculty' : 'General') }}
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-500">{{ Carbon\Carbon::parse($cert->issue_date)->format('M d, Y') }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-600 max-w-xs truncate">{{ $cert->reason ?? 'Official campus verification' }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.certificates.print', $cert->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition border border-indigo-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                <span>Print Certificate</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">No certificates generated yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $certificates->links() }}
        </div>
    </div>
</div>

<!-- Modal: Generate Certificate -->
<div x-data="{ open: false, type: 'bonafide' }" @open-modal-cert.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Generate Certificate / ID Card</h3>
            <p class="text-xs text-slate-500 mb-4">Produce a verified institutional credential document.</p>
            <form action="{{ route('admin.certificates.generate') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Document Type</label>
                    <select name="certificate_type" x-model="type" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="bonafide">Bonafide Certificate</option>
                        <option value="transfer_certificate">Transfer Certificate (TC)</option>
                        <option value="character">Character & Conduct Certificate</option>
                        <option value="id_card">Student / Staff ID Badge Card</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Select Student</label>
                    <select name="student_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select Student...</option>
                        @foreach($students as $st)
                            <option value="{{ $st->id }}">{{ $st->user->name ?? 'Student' }} ({{ $st->admission_number }}) - {{ $st->schoolClass->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Reason / Purpose of Issue</label>
                    <input type="text" name="reason" placeholder="e.g. Higher education application, passport renewal..." class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Custom Endorsement Statement (Optional)</label>
                    <textarea name="details" rows="2" placeholder="Specific commendation or clearance notes..." class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">Issue Document</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
