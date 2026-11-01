<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Certificate - {{ $cert->certificate_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-10 px-4 text-slate-800">
    <div class="max-w-2xl mx-auto">
        <!-- Controls -->
        <div class="no-print flex items-center justify-between mb-8 pb-4 border-b border-slate-200">
            <a href="{{ route('admin.certificates') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1">
                &larr; Back to Certificates
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Print Document</span>
            </button>
        </div>

        @if($cert->certificate_type === 'id_card')
        <!-- SMART ID CARD FORMAT -->
        <div class="max-w-sm mx-auto bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-white rounded-3xl p-6 shadow-2xl border border-indigo-500/30 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-600/20 rounded-full blur-2xl"></div>
            
            <!-- School Header -->
            <div class="flex items-center gap-3 border-b border-slate-800 pb-4">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center font-bold text-white shadow-md">
                    SN
                </div>
                <div>
                    <h2 class="text-xs font-black tracking-wider uppercase text-white">SchoolNexus Academy</h2>
                    <p class="text-[9px] uppercase tracking-widest text-indigo-400 font-bold">Institutional Smart ID Pass</p>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="mt-6 flex gap-4 items-center">
                <div class="w-20 h-24 rounded-2xl bg-slate-800 border-2 border-indigo-400/50 flex flex-col items-center justify-center text-slate-500 shrink-0">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="text-[8px] uppercase mt-1 font-bold text-slate-400">Photo</span>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-black text-white leading-tight">
                        {{ $cert->student->user->name ?? ($cert->teacher->user->name ?? 'Scholar') }}
                    </h3>
                    <p class="text-xs font-mono text-indigo-300 font-bold">
                        ID: {{ $cert->student->admission_number ?? ($cert->teacher->employee_id ?? 'ID-001') }}
                    </p>
                    <div class="text-[11px] text-slate-300">
                        Class: <span class="font-bold text-white">{{ $cert->student->schoolClass->name ?? 'Faculty' }}</span>
                    </div>
                    <div class="text-[11px] text-slate-300">
                        Blood Group: <span class="font-bold text-rose-400">{{ $cert->student->blood_group ?? 'O+' }}</span>
                    </div>
                </div>
            </div>

            <!-- Card Bottom Bar -->
            <div class="mt-6 pt-3 border-t border-slate-800/80 flex items-center justify-between text-[10px] text-slate-400">
                <span>Card #: {{ $cert->certificate_number }}</span>
                <span class="font-bold text-emerald-400">Status: Verified</span>
            </div>
        </div>

        @else
        <!-- FORMAL CERTIFICATE (BONAFIDE / TRANSFER / CHARACTER) -->
        <div class="bg-white p-10 sm:p-14 rounded-3xl shadow-xl border-4 border-double border-indigo-900/40 relative">
            <!-- Decorative Corners -->
            <div class="text-center pb-8 border-b-2 border-slate-900">
                <h1 class="text-2xl font-black tracking-tight text-slate-900 uppercase">SchoolNexus International Academy</h1>
                <p class="text-xs font-semibold text-indigo-700 tracking-wider uppercase mt-1">Accredited by the International Council of Secondary & Higher Education</p>
                <p class="text-[11px] text-slate-500 mt-0.5">742 Evergreen Campus Parkway, Silicon Hills &bull; admissions@schoolnexus.edu</p>
                <div class="inline-block mt-4 px-6 py-1.5 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded-full">
                    {{ str_replace('_', ' ', strtoupper($cert->certificate_type)) }}
                </div>
            </div>

            <!-- Certificate Meta -->
            <div class="flex justify-between py-4 text-xs font-mono text-slate-600 border-b border-slate-100">
                <span>Certificate No: <strong class="text-slate-900">{{ $cert->certificate_number }}</strong></span>
                <span>Date of Issue: <strong class="text-slate-900">{{ Carbon\Carbon::parse($cert->issue_date)->format('F d, Y') }}</strong></span>
            </div>

            <!-- Certificate Text -->
            <div class="py-10 text-sm leading-relaxed text-slate-800 space-y-4">
                @if($cert->certificate_type === 'bonafide')
                <p>
                    This is to formally certify that <strong class="text-slate-950 font-bold underline decoration-indigo-400 decoration-2 underline-offset-4">{{ $cert->student->user->name ?? 'the applicant' }}</strong>, 
                    bearing Admission Number <strong class="font-mono">{{ $cert->student->admission_number ?? 'STU-001' }}</strong>, 
                    is a bona fide scholar of <strong class="font-semibold">SchoolNexus International Academy</strong>, 
                    currently pursuing studies in <strong class="font-semibold">{{ $cert->student->schoolClass->name ?? 'the current standard' }}</strong> 
                    for the Academic Year <strong class="font-semibold">2026-2027</strong>.
                </p>
                <p>
                    As per official institutional records, their moral character, discipline, and scholarly conduct have been consistently 
                    <strong class="font-bold text-emerald-700">Exemplary</strong>. This certificate is issued upon formal request for 
                    <em class="font-semibold text-slate-900">{{ $cert->reason ?? 'official institutional and government verification purposes' }}</em>.
                </p>
                @elseif($cert->certificate_type === 'transfer_certificate')
                <p>
                    This Transfer Certificate certifies that <strong class="text-slate-950 font-bold underline decoration-indigo-400 decoration-2 underline-offset-4">{{ $cert->student->user->name ?? 'the student' }}</strong>, 
                    Admission Number <strong class="font-mono">{{ $cert->student->admission_number ?? 'STU-001' }}</strong>, 
                    was admitted to this institution and has successfully satisfied all academic assessments in 
                    <strong class="font-semibold">{{ $cert->student->schoolClass->name ?? 'Grade Standard' }}</strong>.
                </p>
                <p>
                    All institutional library dues, laboratory clearances, sports equipment, and academic tuition fees have been settled in full. 
                    The scholar is formally discharged from the rolls of this academy with our highest commendations for their future endeavors.
                </p>
                @else
                <p>
                    This is to certify that <strong class="text-slate-950 font-bold underline decoration-indigo-400 decoration-2 underline-offset-4">{{ $cert->student->user->name ?? 'the student' }}</strong> 
                    is well known to the administration. During their tenure at this institution, they demonstrated high academic aptitude, 
                    integrity, respectful demeanor towards peers and faculty, and active participation in school community service.
                </p>
                @endif

                @if($cert->details)
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 mt-4">
                    <span class="font-bold text-slate-700">Special Endorsement:</span> {{ $cert->details }}
                </div>
                @endif
            </div>

            <!-- Signatures -->
            <div class="pt-16 mt-8 border-t border-slate-300 grid grid-cols-2 gap-8 text-xs text-center">
                <div>
                    <div class="border-b border-slate-400 pb-1 mb-1 font-semibold text-slate-800">Academic Registrar</div>
                    <div class="text-slate-400 text-[10px]">Office of Records & Admissions</div>
                </div>
                <div>
                    <div class="border-b border-slate-400 pb-1 mb-1 font-semibold text-slate-800">{{ $cert->issued_by ?? 'Dr. Eleanor Vance, Ph.D.' }}</div>
                    <div class="text-slate-400 text-[10px]">Head of Institution & Principal</div>
                </div>
            </div>
        </div>
        @endif
    </div>
</body>
</html>
