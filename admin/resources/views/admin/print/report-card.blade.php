<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Report Card - {{ $student->user->name ?? 'Student' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-10 px-4 text-slate-800">
    <div class="max-w-3xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-xl border border-slate-200">
        <!-- Controls -->
        <div class="no-print flex items-center justify-between mb-8 pb-4 border-b border-slate-200">
            <a href="{{ route('admin.exams') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1">
                &larr; Back to Exams & Marks
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Print Official Report Card</span>
            </button>
        </div>

        <!-- School Header -->
        <div class="text-center pb-6 border-b-2 border-indigo-600">
            <h1 class="text-2xl font-black tracking-tight text-slate-900 uppercase">SchoolNexus International Academy</h1>
            <p class="text-xs font-semibold text-indigo-600 tracking-wider uppercase mt-1">Excellence in Academic Mastery & Character Formation</p>
            <div class="inline-block mt-3 px-4 py-1 bg-slate-900 text-white text-xs font-bold uppercase tracking-widest rounded-full">
                Cumulative Academic Performance Report Card
            </div>
            <p class="text-xs font-medium text-slate-500 mt-2">Academic Year 2026-2027 &bull; Term Assessment Record</p>
        </div>

        <!-- Student Meta -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-6 border-b border-slate-200 text-xs">
            <div>
                <span class="text-slate-400 block">Student Name:</span>
                <span class="font-bold text-slate-900 text-sm">{{ $student->user->name ?? 'Student' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block">Admission Number:</span>
                <span class="font-mono font-bold text-slate-900">{{ $student->admission_number }}</span>
            </div>
            <div>
                <span class="text-slate-400 block">Grade / Class:</span>
                <span class="font-bold text-slate-900">{{ $student->schoolClass->name ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block">Section / Roll:</span>
                <span class="font-bold text-slate-900">{{ $student->section->name ?? 'A' }} (Roll #{{ $student->roll_number ?? '1' }})</span>
            </div>
        </div>

        <!-- Marks Assessment Table -->
        <div class="py-6 border-b border-slate-200">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 uppercase text-slate-500 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-3">Subject</th>
                        <th class="py-3 px-3">Exam Session</th>
                        <th class="py-3 px-3 text-right">Marks Obtained</th>
                        <th class="py-3 px-3 text-right">Max Marks</th>
                        <th class="py-3 px-3 text-right">Percentage</th>
                        <th class="py-3 px-3 text-center">Grade</th>
                        <th class="py-3 px-3">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($grades as $g)
                    @php
                        $pct = $g->max_marks > 0 ? round(($g->marks_obtained / $g->max_marks) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td class="py-3 px-3 font-bold text-slate-900">{{ $g->subject->name ?? 'Subject' }}</td>
                        <td class="py-3 px-3 text-slate-600">{{ $g->exam_name }}</td>
                        <td class="py-3 px-3 text-right font-bold text-slate-900">{{ $g->marks_obtained }}</td>
                        <td class="py-3 px-3 text-right text-slate-600">{{ $g->max_marks }}</td>
                        <td class="py-3 px-3 text-right font-semibold text-emerald-600">{{ $pct }}%</td>
                        <td class="py-3 px-3 text-center font-extrabold text-indigo-600">{{ $g->grade_letter ?? 'A' }}</td>
                        <td class="py-3 px-3 text-slate-500 italic">{{ $g->remarks ?? 'Satisfactory mastery' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-slate-400">No grades registered for this student.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary Statistics -->
        <div class="py-6 grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <div class="text-xs text-slate-400 uppercase font-semibold">Total Score</div>
                <div class="text-xl font-bold text-slate-900 mt-1">{{ $totalObtained }} / {{ $totalMax }}</div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <div class="text-xs text-slate-400 uppercase font-semibold">Overall Percentage</div>
                <div class="text-xl font-bold text-emerald-600 mt-1">{{ $percentage }}%</div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <div class="text-xs text-slate-400 uppercase font-semibold">Consolidated Grade</div>
                <div class="text-xl font-extrabold text-indigo-600 mt-1">{{ $overallLetter }}</div>
            </div>
        </div>

        <!-- Grading Legend -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-[11px] text-slate-500 flex justify-between items-center">
            <span class="font-bold text-slate-700">Grading Scale:</span>
            <span>A+ (90-100%) Outstanding</span>
            <span>A (80-89%) Excellent</span>
            <span>B (70-79%) Good</span>
            <span>C (60-69%) Satisfactory</span>
            <span>D (40-59%) Pass</span>
            <span>F (&lt;40%) Needs Improvement</span>
        </div>

        <!-- Signature Block -->
        <div class="pt-16 mt-8 border-t border-slate-200 grid grid-cols-3 gap-6 text-xs text-center">
            <div>
                <div class="border-b border-slate-300 pb-1 mb-1 font-semibold text-slate-700">Class Academic Advisor</div>
                <div class="text-slate-400 text-[10px]">Evaluation Lead</div>
            </div>
            <div>
                <div class="border-b border-slate-300 pb-1 mb-1 font-semibold text-slate-700">Exam Controller</div>
                <div class="text-slate-400 text-[10px]">Assessment Board</div>
            </div>
            <div>
                <div class="border-b border-slate-300 pb-1 mb-1 font-semibold text-slate-700">Dr. Eleanor Vance</div>
                <div class="text-slate-400 text-[10px]">Principal & Head of School</div>
            </div>
        </div>
    </div>
</body>
</html>
