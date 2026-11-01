<?php

namespace Database\Seeders;

use App\Models\ClassDiary;
use App\Models\DutyRoster;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\InvigilationDuty;
use App\Models\LeaveApplication;
use App\Models\ParentCommunication;
use App\Models\Payroll;
use App\Models\Quiz;
use App\Models\SchoolClass;
use App\Models\SchoolEvent;
use App\Models\Student;
use App\Models\StudyMaterial;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TeacherEnterpriseSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = Teacher::with('user')->first();
        if (!$teacher) {
            return;
        }

        $classes = SchoolClass::all();
        $class = $classes->first();
        $subjects = Subject::all();
        $subject = $subjects->first();
        $students = Student::with('user')->get()->values();

        // 1. Leave Applications
        if (LeaveApplication::where('user_id', $teacher->user_id)->count() === 0) {
            LeaveApplication::create([
                'user_id' => $teacher->user_id,
                'applicant_type' => 'teacher',
                'leave_type' => 'Casual Leave',
                'start_date' => Carbon::now()->subDays(10)->toDateString(),
                'end_date' => Carbon::now()->subDays(9)->toDateString(),
                'days_count' => 2,
                'reason' => 'Attending family celebration in native town.',
                'status' => 'approved',
                'reviewed_by' => 'Principal Office',
                'admin_remarks' => 'Approved. Class substitute arranged with Kumar R.',
            ]);

            LeaveApplication::create([
                'user_id' => $teacher->user_id,
                'applicant_type' => 'teacher',
                'leave_type' => 'Medical Leave',
                'start_date' => Carbon::now()->subDays(25)->toDateString(),
                'end_date' => Carbon::now()->subDays(24)->toDateString(),
                'days_count' => 2,
                'reason' => 'Viral fever and prescribed medical rest by physician.',
                'status' => 'approved',
                'reviewed_by' => 'Principal Office',
                'admin_remarks' => 'Approved with medical certificate verified.',
            ]);

            LeaveApplication::create([
                'user_id' => $teacher->user_id,
                'applicant_type' => 'teacher',
                'leave_type' => 'Duty Leave',
                'start_date' => Carbon::now()->addDays(5)->toDateString(),
                'end_date' => Carbon::now()->addDays(6)->toDateString(),
                'days_count' => 2,
                'reason' => 'CBSE Regional Faculty Development Workshop on STEM Pedagogy.',
                'status' => 'pending',
                'reviewed_by' => null,
                'admin_remarks' => null,
            ]);
        }

        // 2. Exam Marks & Remarks (Grades)
        if ($students->count() > 0 && $subject) {
            foreach ($students as $idx => $st) {
                Grade::updateOrCreate(
                    [
                        'student_id' => $st->id,
                        'subject_id' => $subject->id,
                        'exam_name' => 'Midterm Examination 2026',
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'marks_obtained' => [88, 94, 76, 85, 92, 68][$idx % 6],
                        'max_marks' => 100,
                        'grade_letter' => ['A', 'A+', 'B+', 'A', 'A+', 'B'][$idx % 6],
                        'remarks' => [
                            'Consistent performance and keen conceptual clarity.',
                            'Exemplary problem-solving capability. Keep it up!',
                            'Good effort. Needs regular practice in algebraic proofs.',
                            'Demonstrates strong analytical thinking.',
                            'Outstanding exam presentation and accurate calculations.',
                            'Needs dedicated focus on homework revision and formula mastery.'
                        ][$idx % 6],
                        'exam_date' => Carbon::now()->subDays(15)->toDateString(),
                    ]
                );
            }
        }

        // 3. Quizzes & Tests
        if (Quiz::where('teacher_id', $teacher->id)->count() === 0 && $class && $subject) {
            Quiz::create([
                'teacher_id' => $teacher->id,
                'school_class_id' => $class->id,
                'subject_id' => $subject->id,
                'title' => 'Diagnostic Assessment: Trigonometric Ratios & Angles',
                'quiz_type' => 'Class Test',
                'total_marks' => 25,
                'passing_marks' => 10,
                'duration_minutes' => 30,
                'quiz_date' => Carbon::now()->addDays(2)->toDateString(),
                'instructions' => 'Scientific calculators permitted. Attempt all questions with step-by-step working.',
                'status' => 'Scheduled',
            ]);

            Quiz::create([
                'teacher_id' => $teacher->id,
                'school_class_id' => $class->id,
                'subject_id' => $subject->id,
                'title' => 'Chapter 3: Quadratic Equations Rapid Quiz',
                'quiz_type' => 'Quiz',
                'total_marks' => 20,
                'passing_marks' => 8,
                'duration_minutes' => 20,
                'quiz_date' => Carbon::now()->subDays(4)->toDateString(),
                'instructions' => 'Multiple choice and short answer questions.',
                'status' => 'Completed',
            ]);

            Quiz::create([
                'teacher_id' => $teacher->id,
                'school_class_id' => $class->id,
                'subject_id' => $subject->id,
                'title' => 'Coordinate Geometry & Straight Lines Unit Exam',
                'quiz_type' => 'Unit Test',
                'total_marks' => 50,
                'passing_marks' => 20,
                'duration_minutes' => 60,
                'quiz_date' => Carbon::now()->addDays(9)->toDateString(),
                'instructions' => 'Section A contains 10 MCQs, Section B contains 5 long-form derivation problems.',
                'status' => 'Scheduled',
            ]);
        }

        // 4. Study Materials
        if (StudyMaterial::where('teacher_id', $teacher->id)->count() === 0 && $class && $subject) {
            StudyMaterial::create([
                'teacher_id' => $teacher->id,
                'school_class_id' => $class->id,
                'subject_id' => $subject->id,
                'title' => 'Trigonometric Identities & Standard Angles Reference Sheet',
                'material_type' => 'PDF Document',
                'file_path' => null,
                'external_url' => 'https://example.com/materials/trig-formulas.pdf',
                'chapter_topic' => 'Chapter 4: Trigonometry',
                'description' => 'Comprehensive formula cheat sheet covering all reciprocal, quotient, and Pythagorean identities.',
                'download_count' => 42,
            ]);

            StudyMaterial::create([
                'teacher_id' => $teacher->id,
                'school_class_id' => $class->id,
                'subject_id' => $subject->id,
                'title' => 'Video Walkthrough: Factoring Hard Quadratic Polynomials',
                'material_type' => 'Lecture Video',
                'file_path' => null,
                'external_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'chapter_topic' => 'Chapter 3: Quadratic Equations',
                'description' => '20-minute masterclass illustrating splitting the middle term and completing the square methods.',
                'download_count' => 68,
            ]);

            StudyMaterial::create([
                'teacher_id' => $teacher->id,
                'school_class_id' => $class->id,
                'subject_id' => $subject->id,
                'title' => 'Coordinate Geometry Board Exam Question Bank & Solutions',
                'material_type' => 'Study Notes',
                'file_path' => null,
                'external_url' => 'https://example.com/materials/coordinate-geometry-notes.pdf',
                'chapter_topic' => 'Chapter 7: Coordinate Geometry',
                'description' => 'Curated collection of 10-year past paper questions with model answers and scoring breakdown.',
                'download_count' => 55,
            ]);
        }

        // 5. Class Diary
        if (ClassDiary::where('teacher_id', $teacher->id)->count() === 0 && $class && $subject) {
            ClassDiary::create([
                'teacher_id' => $teacher->id,
                'school_class_id' => $class->id,
                'section_id' => $class->sections->first()?->id,
                'subject_id' => $subject->id,
                'entry_date' => Carbon::now()->toDateString(),
                'topic_covered' => 'Introduction to Trigonometric Ratios (sin, cos, tan)',
                'classwork_summary' => 'Explained right-angled triangle definitions of ratios; solved Exercise 8.1 problems 1 through 6 on the smartboard.',
                'homework_assigned' => 'Exercise 8.1 questions 7, 8, 9, and 10 to be completed in the homework notebook.',
                'parent_note' => 'Please verify that students have a geometry ruler and scientific compass ready for tomorrow.',
            ]);

            ClassDiary::create([
                'teacher_id' => $teacher->id,
                'school_class_id' => $class->id,
                'section_id' => $class->sections->first()?->id,
                'subject_id' => $subject->id,
                'entry_date' => Carbon::now()->subDay()->toDateString(),
                'topic_covered' => 'Solving Quadratic Equations via Quadratic Formula Method',
                'classwork_summary' => 'Derived discriminant formula b^2 - 4ac and examined conditions for real, distinct, and imaginary roots.',
                'homework_assigned' => 'Page 94, questions 1 to 8 odd numbers.',
                'parent_note' => 'Encourage daily 20 minutes formula recitation at home.',
            ]);
        }

        // 6. Parent Communications
        if (ParentCommunication::where('teacher_id', $teacher->id)->count() === 0 && $students->count() > 0) {
            ParentCommunication::create([
                'teacher_id' => $teacher->id,
                'student_id' => $students[0]->id,
                'subject' => 'Commendation: Outstanding Performance in Mathematics',
                'message' => 'Dear Parent, your ward has demonstrated exceptional problem-solving and scored strong results in recent class assessments. We encourage continued practice for the upcoming competitions.',
                'sender_type' => 'teacher',
                'priority' => 'Normal',
                'is_read' => true,
                'parent_reply' => 'Thank you, Dr. Priya! We appreciate your guidance and support.',
                'replied_at' => Carbon::now()->subHours(6),
            ]);

            if ($students->count() > 1) {
                ParentCommunication::create([
                    'teacher_id' => $teacher->id,
                    'student_id' => $students[1]->id,
                    'subject' => 'Homework Submission & Class Preparation Alert',
                    'message' => 'Dear Parent, kindly note that yesterday\'s algebra homework was incomplete. Regular practice is essential for maintaining strong grades in Term 1.',
                    'sender_type' => 'teacher',
                    'priority' => 'Important',
                    'is_read' => false,
                    'parent_reply' => null,
                    'replied_at' => null,
                ]);
            }
        }

        // 7. Duty Roster
        if (DutyRoster::where('teacher_id', $teacher->id)->count() === 0) {
            DutyRoster::create([
                'teacher_id' => $teacher->id,
                'duty_type' => 'Morning Gate Supervision',
                'duty_date' => Carbon::now()->addDays(1)->toDateString(),
                'time_slot' => '08:00 AM - 08:45 AM',
                'location' => 'Main Gate & Student Entry Corridor',
                'instructions' => 'Monitor student uniform compliance, ID cards, and orderly queueing into morning assembly.',
                'status' => 'Assigned',
            ]);

            DutyRoster::create([
                'teacher_id' => $teacher->id,
                'duty_type' => 'Lunch Break Monitor',
                'duty_date' => Carbon::now()->addDays(3)->toDateString(),
                'time_slot' => '12:30 PM - 01:15 PM',
                'location' => 'Junior Wing Cafeteria & Courtyard',
                'instructions' => 'Ensure clean table etiquette and supervise corridor movement.',
                'status' => 'Assigned',
            ]);

            DutyRoster::create([
                'teacher_id' => $teacher->id,
                'duty_type' => 'Campus Assembly In-charge',
                'duty_date' => Carbon::now()->subDays(2)->toDateString(),
                'time_slot' => '08:30 AM - 09:15 AM',
                'location' => 'Central School Auditorium',
                'instructions' => 'Conduct stage microphone check, coordinate class prayer, and oversee news reading.',
                'status' => 'Completed',
            ]);
        }

        // 8. Invigilation Duties
        if (InvigilationDuty::where('teacher_id', $teacher->id)->count() === 0) {
            InvigilationDuty::create([
                'teacher_id' => $teacher->id,
                'exam_id' => Exam::first()?->id,
                'exam_name' => 'Midterm Examination 2026',
                'subject_name' => 'Physics Theory (Class 10)',
                'exam_date' => Carbon::now()->addDays(4)->toDateString(),
                'time_slot' => '09:00 AM - 12:00 PM',
                'room_number' => 'Examination Hall 204',
                'role' => 'Chief Invigilator',
                'instructions' => 'Collect question paper packets from confidential strongroom at 08:30 AM. Supervise 35 students.',
                'status' => 'Scheduled',
            ]);

            InvigilationDuty::create([
                'teacher_id' => $teacher->id,
                'exam_id' => Exam::first()?->id,
                'exam_name' => 'Midterm Examination 2026',
                'subject_name' => 'Social Science (Class 8)',
                'exam_date' => Carbon::now()->addDays(7)->toDateString(),
                'time_slot' => '01:30 PM - 04:30 PM',
                'room_number' => 'Hall 102 (West Wing)',
                'role' => 'Hall Invigilator',
                'instructions' => 'Verify student admit cards, distribute graph sheets, and maintain strict examination decorum.',
                'status' => 'Scheduled',
            ]);

            InvigilationDuty::create([
                'teacher_id' => $teacher->id,
                'exam_id' => Exam::first()?->id,
                'exam_name' => 'Pre-Assessment Diagnostic Exam',
                'subject_name' => 'Mathematics (Class 9)',
                'exam_date' => Carbon::now()->subDays(12)->toDateString(),
                'time_slot' => '09:00 AM - 11:30 AM',
                'room_number' => 'Room 305',
                'role' => 'Hall Invigilator',
                'instructions' => 'Collected and deposited answer scripts at the examination cell.',
                'status' => 'Completed',
            ]);
        }

        // 9. Payrolls
        if (Payroll::where('teacher_id', $teacher->id)->count() === 0) {
            Payroll::create([
                'teacher_id' => $teacher->id,
                'month' => Carbon::now()->subMonth()->format('F'),
                'year' => Carbon::now()->year,
                'basic_salary' => 45000.00,
                'allowances' => 12500.00,
                'deductions' => 4200.00,
                'net_salary' => 53300.00,
                'payment_method' => 'Direct Bank Transfer',
                'payment_date' => Carbon::now()->subMonth()->endOfMonth()->toDateString(),
                'status' => 'Paid',
                'transaction_id' => 'NEFT-8849201948',
                'notes' => 'Monthly academic salary credited. Includes Dearness Allowance and HRA.',
            ]);

            Payroll::create([
                'teacher_id' => $teacher->id,
                'month' => Carbon::now()->subMonths(2)->format('F'),
                'year' => Carbon::now()->year,
                'basic_salary' => 45000.00,
                'allowances' => 12500.00,
                'deductions' => 4200.00,
                'net_salary' => 53300.00,
                'payment_method' => 'Direct Bank Transfer',
                'payment_date' => Carbon::now()->subMonths(2)->endOfMonth()->toDateString(),
                'status' => 'Paid',
                'transaction_id' => 'NEFT-7738194012',
                'notes' => 'Monthly academic salary credited.',
            ]);
        }

        // 10. School Events & Holidays Calendar
        if (SchoolEvent::count() < 4) {
            SchoolEvent::create([
                'title' => 'Diwali Cultural Break & Deepavali Festival',
                'event_type' => 'holiday',
                'start_date' => Carbon::now()->addDays(8)->toDateString(),
                'end_date' => Carbon::now()->addDays(11)->toDateString(),
                'audience' => 'all',
                'location' => 'Campus Wide',
                'description' => 'Institution closed for Diwali festivities. Academic lectures resume on Monday.',
                'is_holiday' => true,
            ]);

            SchoolEvent::create([
                'title' => 'Annual Inter-House Science & Robotics Exhibition',
                'event_type' => 'celebration',
                'start_date' => Carbon::now()->addDays(16)->toDateString(),
                'end_date' => Carbon::now()->addDays(17)->toDateString(),
                'audience' => 'all',
                'location' => 'Main Sports Arena & Science Pavilion',
                'description' => 'Student innovation showcase with working models, robotics displays, and guest lectures.',
                'is_holiday' => false,
            ]);

            SchoolEvent::create([
                'title' => 'Term 1 Midterm Examinations',
                'event_type' => 'exam',
                'start_date' => Carbon::now()->addDays(20)->toDateString(),
                'end_date' => Carbon::now()->addDays(28)->toDateString(),
                'audience' => 'all',
                'location' => 'Academic Blocks A, B, C',
                'description' => 'Comprehensive academic evaluation for grades 6 through 12.',
                'is_holiday' => false,
            ]);

            SchoolEvent::create([
                'title' => 'Parent-Teacher Academic Conference',
                'event_type' => 'meeting',
                'start_date' => Carbon::now()->addDays(32)->toDateString(),
                'end_date' => Carbon::now()->addDays(32)->toDateString(),
                'audience' => 'all',
                'location' => 'Classrooms 101 - 310',
                'description' => 'Personalized one-on-one review of student performance, attendance, and exam marks.',
                'is_holiday' => false,
            ]);
        }
    }
}
