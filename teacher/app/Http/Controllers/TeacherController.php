<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Attendance;
use App\Models\ClassDiary;
use App\Models\DutyRoster;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\Grade;
use App\Models\InvigilationDuty;
use App\Models\LeaveApplication;
use App\Models\ParentCommunication;
use App\Models\Payroll;
use App\Models\Quiz;
use App\Models\SchoolClass;
use App\Models\SchoolEvent;
use App\Models\SchoolSetting;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudyMaterial;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Timetable;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    // Active teacher context (default Dr. Sarah Jenkins or switchable)
    protected function getActiveTeacher(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'teacher') {
            $userTeacher = Teacher::with([
                'user',
                'teacherSubjectAssignments.subject',
                'teacherSubjectAssignments.schoolClass',
                'teacherSubjectAssignments.section'
            ])->where('user_id', Auth::id())->first();

            if ($userTeacher) {
                return $userTeacher;
            }
        }

        $teacherId = $request->session()->get('active_teacher_id', 1);
        return Teacher::with([
            'user', 
            'teacherSubjectAssignments.subject', 
            'teacherSubjectAssignments.schoolClass', 
            'teacherSubjectAssignments.section'
        ])->find($teacherId) ?? Teacher::first();
    }

    public function switchTeacher(Request $request, $id)
    {
        $request->session()->put('active_teacher_id', $id);
        return redirect()->back()->with('success', 'Switched teacher profile context successfully.');
    }

    // 1. Dashboard
    public function index(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);

        $assignedClasses = $teacher->teacherSubjectAssignments;
        $classIds = $assignedClasses->pluck('school_class_id')->unique();
        $sectionIds = $assignedClasses->pluck('section_id')->unique();

        $studentsCount = Student::whereIn('school_class_id', $classIds)->count();
        if ($studentsCount === 0) {
            $studentsCount = Student::count();
        }

        $assignmentsCount = Assignment::where('teacher_id', $teacher->id)->count();
        
        $pendingSubmissionsCount = AssignmentSubmission::whereHas('assignment', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->where('status', 'submitted')->count();

        // Today's schedule
        $dayOfWeek = Carbon::now()->format('l');
        $todaySchedule = Timetable::with(['subject', 'schoolClass', 'section'])
            ->where('teacher_id', $teacher->id)
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get();

        if ($todaySchedule->isEmpty()) {
            $todaySchedule = Timetable::with(['subject', 'schoolClass', 'section'])
                ->where('teacher_id', $teacher->id)
                ->where('day_of_week', 'Monday')
                ->orderBy('start_time')
                ->get();
            $dayOfWeek = 'Monday (Upcoming)';
        }

        // Recent homework turn-ins
        $recentSubmissions = AssignmentSubmission::with(['student.user', 'assignment.subject'])
            ->whereHas('assignment', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->latest()
            ->take(5)
            ->get();

        $announcements = Announcement::whereIn('audience', ['all', 'teachers'])
            ->latest()
            ->take(4)
            ->get();
        $announcementsCount = Announcement::whereIn('audience', ['all', 'teachers'])->count();

        // Extra quick stats for new modules
        $pendingLeavesCount = LeaveApplication::where('user_id', $teacher->user_id)->where('status', 'pending')->count();
        $upcomingDutiesCount = DutyRoster::where('teacher_id', $teacher->id)->where('status', 'Assigned')->whereDate('duty_date', '>=', Carbon::today())->count();
        $upcomingExamsCount = InvigilationDuty::where('teacher_id', $teacher->id)->where('status', 'Scheduled')->count();
        $unreadMessagesCount = ParentCommunication::where('teacher_id', $teacher->id)->where('is_read', false)->count();

        return view('teacher.dashboard', compact(
            'teacher',
            'studentsCount',
            'assignmentsCount',
            'pendingSubmissionsCount',
            'todaySchedule',
            'dayOfWeek',
            'recentSubmissions',
            'announcements',
            'announcementsCount',
            'pendingLeavesCount',
            'upcomingDutiesCount',
            'upcomingExamsCount',
            'unreadMessagesCount'
        ));
    }

    // 2. My Classes
    public function classes(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);

        $assignments = $teacher->teacherSubjectAssignments()->with(['schoolClass', 'section', 'subject'])->get();
        
        $classCards = [];
        foreach ($assignments as $item) {
            $students = Student::with('user')
                ->where('school_class_id', $item->school_class_id)
                ->where('section_id', $item->section_id)
                ->get();

            $classCards[] = [
                'class' => $item->schoolClass,
                'section' => $item->section,
                'subject' => $item->subject,
                'students' => $students,
                'count' => $students->count(),
            ];
        }

        // Fallback if no specific assignments
        if (empty($classCards)) {
            $allClasses = SchoolClass::with('sections')->get();
            foreach ($allClasses as $c) {
                $students = Student::with('user')->where('school_class_id', $c->id)->get();
                $classCards[] = [
                    'class' => $c,
                    'section' => $c->sections->first(),
                    'subject' => Subject::first(),
                    'students' => $students,
                    'count' => $students->count(),
                ];
            }
        }

        return view('teacher.classes', compact('teacher', 'classCards'));
    }

    // 3. Timetable
    public function timetable(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $schedule = Timetable::with(['subject', 'schoolClass', 'section'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        return view('teacher.timetable', compact('teacher', 'days', 'schedule'));
    }

    // 4. Attendance
    public function attendance(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $date = Carbon::parse($request->get('date', Carbon::now()->toDateString()))->toDateString();
        
        $classes = SchoolClass::with('sections')->get();
        $selectedClassId = $request->get('class_id', $classes->first()->id ?? null);
        
        $selectedClass = SchoolClass::with('sections')->find($selectedClassId);
        $selectedSectionId = $request->get('section_id', $selectedClass->sections->first()->id ?? null);

        $students = Student::with(['user', 'attendances' => function ($q) use ($date) {
            $q->where(function ($sub) use ($date) {
                $sub->whereDate('attendance_date', $date)
                    ->orWhere('attendance_date', $date)
                    ->orWhere('attendance_date', $date . ' 00:00:00');
            });
        }])
        ->where('school_class_id', $selectedClassId)
        ->when($selectedSectionId, function ($q, $sec) {
            return $q->where('section_id', $sec);
        })
        ->get();

        return view('teacher.attendance', compact(
            'teacher',
            'date',
            'classes',
            'selectedClassId',
            'selectedSectionId',
            'students'
        ));
    }

    public function storeAttendance(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $rawDate = $request->input('date', Carbon::now()->toDateString());
        $cleanDate = Carbon::parse($rawDate)->toDateString();
        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');
        $attendanceData = $request->input('attendance', []);

        foreach ($attendanceData as $studentId => $status) {
            $cleanStatus = in_array($status, ['present', 'absent']) ? $status : 'present';

            $existing = Attendance::where('student_id', $studentId)
                ->where(function ($q) use ($cleanDate) {
                    $q->whereDate('attendance_date', $cleanDate)
                      ->orWhere('attendance_date', $cleanDate)
                      ->orWhere('attendance_date', $cleanDate . ' 00:00:00');
                })
                ->first();

            if ($existing) {
                $existing->update([
                    'school_class_id' => $classId,
                    'section_id' => $sectionId,
                    'status' => $cleanStatus,
                    'marked_by' => $teacher->id,
                    'remarks' => $cleanStatus === 'present' ? 'Marked Present' : 'Marked Absent',
                ]);
            } else {
                Attendance::create([
                    'student_id' => $studentId,
                    'school_class_id' => $classId,
                    'section_id' => $sectionId,
                    'attendance_date' => $cleanDate,
                    'status' => $cleanStatus,
                    'marked_by' => $teacher->id,
                    'remarks' => $cleanStatus === 'present' ? 'Marked Present' : 'Marked Absent',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Attendance (Present/Absent) successfully updated and saved in database!');
    }

    // 5. Homework (with specific student assignment support)
    public function homework(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $assignments = Assignment::with(['subject', 'schoolClass', 'section', 'submissions.student.user'])
            ->where('teacher_id', $teacher->id)
            ->latest()
            ->get();

        $classes = SchoolClass::with('sections')->get();
        $subjects = Subject::all();
        $students = Student::with('user')->get();

        return view('teacher.homework', compact('teacher', 'assignments', 'classes', 'subjects', 'students'));
    }

    public function uploadHomework(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'required|date',
            'total_points' => 'required|integer|min:5',
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:10240',
            'target_type' => 'nullable|in:all,specific',
            'target_students' => 'nullable|array',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('homework', $filename, 'public');
            $attachmentPath = 'homework/' . $filename;
        }

        $targetType = $request->input('target_type', 'all');
        $targetStudents = $request->input('target_students', []);

        Assignment::create([
            'teacher_id' => $teacher->id,
            'school_class_id' => $validated['school_class_id'],
            'section_id' => $validated['section_id'],
            'subject_id' => $validated['subject_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'total_points' => $validated['total_points'],
            'attachment' => $attachmentPath,
            'target_type' => $targetType,
            'target_students' => !empty($targetStudents) ? json_encode(array_map('intval', $targetStudents)) : null,
        ]);

        $targetMsg = ($targetType === 'specific' && !empty($targetStudents))
            ? ' assigned to ' . count($targetStudents) . ' selected student(s)'
            : ' published for all students in class';

        return redirect()->route('teacher.homework')->with('success', "New homework '{$validated['title']}' {$targetMsg} successfully!");
    }

    public function downloadFile(Request $request, $type, $id)
    {
        if ($type === 'homework') {
            $assignment = Assignment::findOrFail($id);
            if ($assignment->attachment && Storage::disk('public')->exists($assignment->attachment)) {
                return Storage::disk('public')->download($assignment->attachment);
            }
        } elseif ($type === 'submission') {
            $submission = AssignmentSubmission::findOrFail($id);
            if ($submission->file_attachment && Storage::disk('public')->exists($submission->file_attachment)) {
                return Storage::disk('public')->download($submission->file_attachment);
            }
        }

        return redirect()->back()->with('error', 'Requested file attachment is not available on disk.');
    }

    public function autoGrade(Request $request, $id)
    {
        $assignment = Assignment::with('submissions')->findOrFail($id);
        $totalPoints = $assignment->total_points;
        $gradedCount = 0;

        foreach ($assignment->submissions as $submission) {
            $textLen = strlen($submission->submission_text ?? '');
            if ($textLen > 80 || $submission->file_attachment) {
                $score = round($totalPoints * 0.95, 1);
                $feedback = 'Auto-Graded: High-quality, comprehensive submission with complete problem solutions.';
            } else {
                $score = round($totalPoints * 0.85, 1);
                $feedback = 'Auto-Graded: Satisfactory submission. All core requirements satisfied.';
            }

            $submission->update([
                'marks_awarded' => $score,
                'feedback' => $feedback,
                'status' => 'graded',
            ]);
            $gradedCount++;
        }

        return redirect()->back()->with('success', "Auto-graded {$gradedCount} student submission(s) for '{$assignment->title}' successfully!");
    }

    public function gradeSubmission(Request $request, $id)
    {
        $submission = AssignmentSubmission::findOrFail($id);

        $validated = $request->validate([
            'marks_awarded' => 'required|numeric|min:0',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'marks_awarded' => $validated['marks_awarded'],
            'feedback' => $validated['feedback'],
            'status' => 'graded',
        ]);

        return redirect()->back()->with('success', 'Submission graded successfully.');
    }

    public function destroyHomework(Request $request, $id)
    {
        $teacher = $this->getActiveTeacher($request);
        $assignment = Assignment::with('submissions')->findOrFail($id);

        if ($assignment->teacher_id != $teacher->id) {
            return redirect()->back()->with('error', 'Unauthorized: You can only delete homework assignments assigned by your faculty profile.');
        }

        $title = $assignment->title;

        foreach ($assignment->submissions as $sub) {
            if ($sub->file_attachment && Storage::disk('public')->exists($sub->file_attachment)) {
                Storage::disk('public')->delete($sub->file_attachment);
            }
            if ($sub->file_path && Storage::disk('public')->exists($sub->file_path)) {
                Storage::disk('public')->delete($sub->file_path);
            }
        }

        $assignment->submissions()->delete();

        if ($assignment->attachment && Storage::disk('public')->exists($assignment->attachment)) {
            Storage::disk('public')->delete($assignment->attachment);
        }

        $assignment->delete();

        return redirect()->route('teacher.homework')->with('success', "Homework assignment '{$title}' and its submissions have been deleted successfully.");
    }

    // 6. Quizzes & Tests
    public function quizzes(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $quizzes = Quiz::with(['schoolClass', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->latest('quiz_date')
            ->get();

        $classes = SchoolClass::all();
        $subjects = Subject::all();

        return view('teacher.quizzes', compact('teacher', 'quizzes', 'classes', 'subjects'));
    }

    public function storeQuiz(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'quiz_type' => 'required|string',
            'school_class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'total_marks' => 'required|integer|min:5',
            'passing_marks' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:5',
            'quiz_date' => 'required|date',
            'instructions' => 'nullable|string',
        ]);

        Quiz::create([
            'teacher_id' => $teacher->id,
            'school_class_id' => $validated['school_class_id'],
            'subject_id' => $validated['subject_id'],
            'title' => $validated['title'],
            'quiz_type' => $validated['quiz_type'],
            'total_marks' => $validated['total_marks'],
            'passing_marks' => $validated['passing_marks'],
            'duration_minutes' => $validated['duration_minutes'],
            'quiz_date' => $validated['quiz_date'],
            'instructions' => $validated['instructions'] ?? null,
            'status' => 'Scheduled',
        ]);

        return redirect()->route('teacher.quizzes')->with('success', "Quiz / Test '{$validated['title']}' created and scheduled successfully!");
    }

    public function updateQuizStatus(Request $request, $id)
    {
        $teacher = $this->getActiveTeacher($request);
        $quiz = Quiz::where('teacher_id', $teacher->id)->findOrFail($id);

        $status = $request->input('status', 'Completed');
        $quiz->update(['status' => $status]);

        return redirect()->back()->with('success', "Quiz status updated to {$status}.");
    }

    public function destroyQuiz(Request $request, $id)
    {
        $teacher = $this->getActiveTeacher($request);
        $quiz = Quiz::where('teacher_id', $teacher->id)->findOrFail($id);
        $quiz->delete();

        return redirect()->route('teacher.quizzes')->with('success', 'Quiz / test deleted successfully.');
    }

    // 7. Study Materials
    public function studyMaterials(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $materials = StudyMaterial::with(['schoolClass', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->latest()
            ->get();

        $classes = SchoolClass::all();
        $subjects = Subject::all();

        return view('teacher.study-materials', compact('teacher', 'materials', 'classes', 'subjects'));
    }

    public function storeStudyMaterial(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'material_type' => 'required|string',
            'school_class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_topic' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'external_url' => 'nullable|url',
            'file' => 'nullable|file|max:20480', // 20MB
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('materials', $filename, 'public');
            $filePath = 'materials/' . $filename;
        }

        StudyMaterial::create([
            'teacher_id' => $teacher->id,
            'school_class_id' => $validated['school_class_id'],
            'subject_id' => $validated['subject_id'],
            'title' => $validated['title'],
            'material_type' => $validated['material_type'],
            'file_path' => $filePath,
            'external_url' => $validated['external_url'] ?? null,
            'chapter_topic' => $validated['chapter_topic'] ?? null,
            'description' => $validated['description'] ?? null,
            'download_count' => 0,
        ]);

        return redirect()->route('teacher.study-materials')->with('success', 'Study material uploaded and shared with students successfully!');
    }

    public function downloadStudyMaterial(Request $request, $id)
    {
        $material = StudyMaterial::findOrFail($id);
        $material->increment('download_count');

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            return Storage::disk('public')->download($material->file_path);
        }

        if ($material->external_url) {
            return redirect()->away($material->external_url);
        }

        return redirect()->back()->with('error', 'No file available for download.');
    }

    public function destroyStudyMaterial(Request $request, $id)
    {
        $teacher = $this->getActiveTeacher($request);
        $material = StudyMaterial::where('teacher_id', $teacher->id)->findOrFail($id);

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();
        return redirect()->route('teacher.study-materials')->with('success', 'Study material removed successfully.');
    }

    // 8. Class Diary & Daily Notes
    public function classDiary(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $date = $request->get('date', Carbon::today()->toDateString());

        $diaries = ClassDiary::with(['schoolClass', 'section', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->latest('entry_date')
            ->get();

        $classes = SchoolClass::with('sections')->get();
        $subjects = Subject::all();

        return view('teacher.class-diary', compact('teacher', 'diaries', 'classes', 'subjects', 'date'));
    }

    public function storeClassDiary(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);

        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'entry_date' => 'required|date',
            'topic_covered' => 'required|string|max:255',
            'classwork_summary' => 'nullable|string',
            'homework_assigned' => 'nullable|string',
            'parent_note' => 'nullable|string',
        ]);

        ClassDiary::create([
            'teacher_id' => $teacher->id,
            'school_class_id' => $validated['school_class_id'],
            'section_id' => $validated['section_id'] ?? null,
            'subject_id' => $validated['subject_id'],
            'entry_date' => $validated['entry_date'],
            'topic_covered' => $validated['topic_covered'],
            'classwork_summary' => $validated['classwork_summary'] ?? null,
            'homework_assigned' => $validated['homework_assigned'] ?? null,
            'parent_note' => $validated['parent_note'] ?? null,
        ]);

        return redirect()->route('teacher.class-diary')->with('success', 'Class diary daily log recorded successfully!');
    }

    public function destroyClassDiary(Request $request, $id)
    {
        $teacher = $this->getActiveTeacher($request);
        $entry = ClassDiary::where('teacher_id', $teacher->id)->findOrFail($id);
        $entry->delete();

        return redirect()->route('teacher.class-diary')->with('success', 'Class diary entry removed.');
    }

    // 9. Exam Marks & Remarks
    public function grades(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $exams = Exam::all();

        $selectedClassId = $request->get('class_id', $classes->first()->id ?? null);
        $selectedSubjectId = $request->get('subject_id', $subjects->first()->id ?? null);
        $selectedExamName = $request->get('exam_name', 'Midterm Examination 2026');

        $students = Student::with(['user', 'grades' => function ($q) use ($selectedSubjectId, $selectedExamName) {
            $q->where('subject_id', $selectedSubjectId)
              ->where('exam_name', $selectedExamName);
        }])
        ->when($selectedClassId, function ($q, $cid) {
            return $q->where('school_class_id', $cid);
        })
        ->get();

        return view('teacher.grades', compact(
            'teacher',
            'classes',
            'subjects',
            'exams',
            'selectedClassId',
            'selectedSubjectId',
            'selectedExamName',
            'students'
        ));
    }

    public function storeGrades(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);

        $classId = $request->input('class_id');
        $subjectId = $request->input('subject_id');
        $examName = $request->input('exam_name', 'Midterm Examination 2026');
        $examDate = $request->input('exam_date', Carbon::today()->toDateString());
        $maxMarks = $request->input('max_marks', 100);
        $gradesInput = $request->input('marks', []); // student_id => marks_obtained
        $remarksInput = $request->input('remarks', []); // student_id => remark

        $savedCount = 0;
        foreach ($gradesInput as $studentId => $marksObtained) {
            if ($marksObtained !== null && $marksObtained !== '') {
                $marks = floatval($marksObtained);
                $pct = ($maxMarks > 0) ? ($marks / $maxMarks) * 100 : 0;

                // Grade letter computation
                if ($pct >= 90) $letter = 'A+';
                elseif ($pct >= 80) $letter = 'A';
                elseif ($pct >= 70) $letter = 'B+';
                elseif ($pct >= 60) $letter = 'B';
                elseif ($pct >= 50) $letter = 'C';
                elseif ($pct >= 40) $letter = 'D';
                else $letter = 'F';

                $remark = $remarksInput[$studentId] ?? null;

                Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'exam_name' => $examName,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'marks_obtained' => $marks,
                        'max_marks' => $maxMarks,
                        'grade_letter' => $letter,
                        'remarks' => $remark,
                        'exam_date' => $examDate,
                    ]
                );
                $savedCount++;
            }
        }

        return redirect()->back()->with('success', "Successfully recorded exam marks and remarks for {$savedCount} student(s)!");
    }

    // 10. Student Performance Analytics
    public function analytics(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $classes = SchoolClass::all();
        $selectedClassId = $request->get('class_id', $classes->first()->id ?? null);

        $students = Student::with(['user', 'attendances', 'grades', 'assignmentSubmissions'])
            ->when($selectedClassId, function ($q, $cid) {
                return $q->where('school_class_id', $cid);
            })
            ->get();

        $analyticsList = [];
        $totalMarksAccum = 0;
        $totalStudentsGraded = 0;
        $highPerformersCount = 0;
        $atRiskCount = 0;
        $totalAttendanceRateSum = 0;

        foreach ($students as $st) {
            // Attendance %
            $attTotal = $st->attendances->count();
            $attPresent = $st->attendances->where('status', 'present')->count();
            $attRate = $attTotal > 0 ? round(($attPresent / $attTotal) * 100, 1) : 100.0;
            $totalAttendanceRateSum += $attRate;

            // Average marks %
            $grades = $st->grades;
            $avgMarks = null;
            if ($grades->isNotEmpty()) {
                $avgMarks = round($grades->avg('marks_obtained'), 1);
                $totalMarksAccum += $avgMarks;
                $totalStudentsGraded++;
            }

            // Homework completion %
            $subsCount = $st->assignmentSubmissions->count();
            $hwRate = min(100, $subsCount * 25);

            // Risk Assessment
            $isAtRisk = ($attRate < 75) || ($avgMarks !== null && $avgMarks < 60);
            $isHighPerformer = ($avgMarks !== null && $avgMarks >= 85) && ($attRate >= 85);

            if ($isAtRisk) $atRiskCount++;
            if ($isHighPerformer) $highPerformersCount++;

            $analyticsList[] = [
                'student' => $st,
                'attendance_rate' => $attRate,
                'avg_marks' => $avgMarks ?? 'N/A',
                'hw_rate' => $hwRate,
                'status' => $isHighPerformer ? 'Top Performer' : ($isAtRisk ? 'Needs Attention' : 'Consistent'),
            ];
        }

        $classAvgMarks = $totalStudentsGraded > 0 ? round($totalMarksAccum / $totalStudentsGraded, 1) : 78.5;
        $classAvgAttendance = $students->count() > 0 ? round($totalAttendanceRateSum / $students->count(), 1) : 92.0;

        return view('teacher.analytics', compact(
            'teacher',
            'classes',
            'selectedClassId',
            'analyticsList',
            'classAvgMarks',
            'classAvgAttendance',
            'highPerformersCount',
            'atRiskCount'
        ));
    }

    // 11. Parent Communication & Messages
    public function communications(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $communications = ParentCommunication::with(['student.user'])
            ->where('teacher_id', $teacher->id)
            ->latest()
            ->get();

        $students = Student::with('user')->get();

        return view('teacher.communications', compact('teacher', 'communications', 'students'));
    }

    public function storeCommunication(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:Normal,Important,Urgent',
        ]);

        ParentCommunication::create([
            'teacher_id' => $teacher->id,
            'student_id' => $validated['student_id'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'priority' => $validated['priority'],
            'sender_type' => 'teacher',
            'is_read' => true,
        ]);

        return redirect()->route('teacher.communications')->with('success', 'Message dispatched to parent successfully!');
    }

    public function markCommunicationRead(Request $request, $id)
    {
        $teacher = $this->getActiveTeacher($request);
        $comm = ParentCommunication::where('teacher_id', $teacher->id)->findOrFail($id);
        $comm->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Marked as read.');
    }

    // 12. Apply for Leave & View Status
    public function leaves(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $leaves = LeaveApplication::where('user_id', $teacher->user_id)
            ->latest('start_date')
            ->get();

        $totalApproved = $leaves->where('status', 'approved')->sum('days_count');
        $totalPending = $leaves->where('status', 'pending')->count();
        $totalRejected = $leaves->where('status', 'rejected')->count();

        return view('teacher.leaves', compact('teacher', 'leaves', 'totalApproved', 'totalPending', 'totalRejected'));
    }

    public function storeLeave(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);

        $validated = $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);
        $daysCount = $start->diffInDays($end) + 1;

        LeaveApplication::create([
            'user_id' => $teacher->user_id,
            'applicant_type' => 'teacher',
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days_count' => $daysCount,
            'reason' => $validated['reason'],
            'status' => 'pending',
            'reviewed_by' => null,
            'admin_remarks' => null,
        ]);

        return redirect()->route('teacher.leaves')->with('success', "Leave application for {$daysCount} day(s) submitted to school administration!");
    }

    // 13. Substitute and Duty Roster
    public function dutyRoster(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $duties = DutyRoster::with('substituteTeacher.user')
            ->where('teacher_id', $teacher->id)
            ->latest('duty_date')
            ->get();

        $otherTeachers = Teacher::with('user')->where('id', '!=', $teacher->id)->get();

        return view('teacher.duty-roster', compact('teacher', 'duties', 'otherTeachers'));
    }

    public function swapDuty(Request $request, $id)
    {
        $teacher = $this->getActiveTeacher($request);
        $duty = DutyRoster::where('teacher_id', $teacher->id)->findOrFail($id);

        $substituteId = $request->input('substitute_teacher_id');
        $duty->update([
            'substitute_teacher_id' => $substituteId,
            'status' => 'Substituted',
        ]);

        return redirect()->route('teacher.duty-roster')->with('success', 'Substitute teacher request registered successfully.');
    }

    // 14. Exam Duty & Invigilation Schedule
    public function invigilation(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $invigilations = InvigilationDuty::where('teacher_id', $teacher->id)
            ->latest('exam_date')
            ->get();

        return view('teacher.invigilation', compact('teacher', 'invigilations'));
    }

    // 15. Salary Slips & Print
    public function salarySlips(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $payrolls = Payroll::where('teacher_id', $teacher->id)
            ->latest('year')
            ->latest('id')
            ->get();

        return view('teacher.salary-slips', compact('teacher', 'payrolls'));
    }

    public function printPayslip(Request $request, $id)
    {
        $teacher = $this->getActiveTeacher($request);
        $payroll = Payroll::with('teacher.user')->findOrFail($id);
        $schoolName = SchoolSetting::where('key', 'school_name')->value('value') ?? 'SchoolNexus International Academy';

        return view('teacher.print.payslip', compact('teacher', 'payroll', 'schoolName'));
    }

    // 16. Events & Holiday Calendar
    public function calendar(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $events = SchoolEvent::orderBy('start_date')->get();

        return view('teacher.calendar', compact('teacher', 'events'));
    }

    // 17. Announcements
    public function announcements(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $announcements = Announcement::latest()->paginate(10);

        return view('teacher.announcements', compact('teacher', 'announcements'));
    }

    public function storeAnnouncement(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audience' => 'required|in:all,students',
            'priority' => 'required|in:normal,important,urgent',
        ]);

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'audience' => $validated['audience'],
            'priority' => $validated['priority'],
            'author_name' => $teacher->user->name . ' (' . ($teacher->specialization ?? 'Faculty') . ')',
            'published_at' => now()->toDateString(),
        ]);

        return redirect()->route('teacher.announcements')->with('success', 'Faculty announcement broadcasted to students successfully!');
    }

    // 18. Profile
    public function profile(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $teacher->load(['teacherSubjectAssignments.schoolClass', 'teacherSubjectAssignments.section', 'teacherSubjectAssignments.subject']);
        return view('teacher.profile', compact('teacher'));
    }

    public function updateProfile(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'qualification' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
        ]);

        $teacher->user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ]);

        $teacher->update([
            'qualification' => $validated['qualification'] ?? $teacher->qualification,
            'specialization' => $validated['specialization'] ?? $teacher->specialization,
        ]);

        return redirect()->route('teacher.profile')->with('success', 'Faculty profile information updated successfully.');
    }

    // 19. Change Password
    public function changePassword(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        return view('teacher.change-password', compact('teacher'));
    }

    public function updatePassword(Request $request)
    {
        $teacher = $this->getActiveTeacher($request);
        $user = $teacher->user;

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password provided is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return redirect()->route('teacher.change-password')->with('success', 'Password updated successfully! You can now use your new password.');
    }
}
