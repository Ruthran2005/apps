<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Attendance;
use App\Models\FeeInvoice;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubjectAssignment;
use App\Models\Timetable;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Users
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Office Admin',
            'email' => 'office@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Exam Admin',
            'email' => 'exam@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Old Admin',
            'email' => 'old@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'inactive',
        ]);

        // 2. Classes & Sections (Matching Guide: Class 9-A, 9-B, 10-A, 10-B, 8-A, 8-B, 7-A, 6-A)
        $classesData = [
            ['name' => 'Class 9', 'code' => '9', 'sections' => ['A', 'B', 'C']],
            ['name' => 'Class 10', 'code' => '10', 'sections' => ['A', 'B', 'C']],
            ['name' => 'Class 8', 'code' => '8', 'sections' => ['A', 'B']],
            ['name' => 'Class 7', 'code' => '7', 'sections' => ['A']],
            ['name' => 'Class 6', 'code' => '6', 'sections' => ['A', 'B']],
        ];

        $classMap = [];
        $sectionMap = [];

        foreach ($classesData as $c) {
            $class = SchoolClass::create([
                'name' => $c['name'],
                'code' => $c['code'],
                'description' => 'Secondary standard ' . $c['name'],
            ]);
            $classMap[$c['code']] = $class;

            foreach ($c['sections'] as $secName) {
                $section = Section::create([
                    'school_class_id' => $class->id,
                    'name' => $secName,
                    'room_number' => 'Room ' . $c['code'] . $secName,
                    'capacity' => 40,
                ]);
                $sectionMap[$c['code'] . '-' . $secName] = $section;
            }
        }

        // 3. Subjects from Guide
        $subjectsData = [
            ['name' => 'Maths', 'code' => 'MATH', 'credits' => 4],
            ['name' => 'Science', 'code' => 'SCI', 'credits' => 4],
            ['name' => 'English', 'code' => 'ENG', 'credits' => 3],
            ['name' => 'Social', 'code' => 'SOC', 'credits' => 3],
            ['name' => 'Tamil', 'code' => 'TAM', 'credits' => 3],
            ['name' => 'Physics', 'code' => 'PHY', 'credits' => 3],
            ['name' => 'Biology', 'code' => 'BIO', 'credits' => 3],
            ['name' => 'Computer', 'code' => 'CS', 'credits' => 3],
            ['name' => 'PT', 'code' => 'PT', 'credits' => 1],
            ['name' => 'Art', 'code' => 'ART', 'credits' => 1],
            ['name' => 'Library', 'code' => 'LIB', 'credits' => 1],
        ];

        $subjectMap = [];
        foreach ($subjectsData as $s) {
            $sub = Subject::create([
                'name' => $s['name'],
                'code' => $s['code'],
                'credits' => $s['credits'],
                'description' => $s['name'] . ' curriculum',
            ]);
            $subjectMap[$s['name']] = $sub;
        }

        // 4. Teachers from Guide (Page 4)
        $teachersData = [
            ['name' => 'Priya Sharma', 'email' => 'priya@school.com', 'subject' => 'Maths', 'classes' => ['9-A', '9-B', '10-A'], 'phone' => '+91 98765 43210', 'status' => 'active'],
            ['name' => 'Kumar R.', 'email' => 'kumar@school.com', 'subject' => 'Science', 'classes' => ['8-B', '9-A'], 'phone' => '+91 98765 43211', 'status' => 'active'],
            ['name' => 'Anitha S.', 'email' => 'anitha@school.com', 'subject' => 'English', 'classes' => ['10-C', '9-A'], 'phone' => '+91 98765 43212', 'status' => 'active'],
            ['name' => 'Rajesh M.', 'email' => 'rajesh@school.com', 'subject' => 'Social', 'classes' => ['7-A', '9-A'], 'phone' => '+91 98765 43213', 'status' => 'inactive'],
            ['name' => 'Divya K.', 'email' => 'divya@school.com', 'subject' => 'Tamil', 'classes' => ['6-A', '6-B', '9-A'], 'phone' => '+91 98765 43214', 'status' => 'active'],
            ['name' => 'Suresh P.', 'email' => 'suresh@school.com', 'subject' => 'Physics', 'classes' => ['10-A'], 'phone' => '+91 98765 43215', 'status' => 'active'],
            ['name' => 'Meena L.', 'email' => 'meena@school.com', 'subject' => 'Biology', 'classes' => ['9-C'], 'phone' => '+91 98765 43216', 'status' => 'active'],
            ['name' => 'Arun V.', 'email' => 'arun@school.com', 'subject' => 'Computer', 'classes' => ['8-A', '9-A'], 'phone' => '+91 98765 43217', 'status' => 'active'],
            ['name' => 'Mr. Ravi', 'email' => 'ravi@school.com', 'subject' => 'PT', 'classes' => ['9-A'], 'phone' => '+91 98765 43218', 'status' => 'active'],
            ['name' => 'Ms. Lata', 'email' => 'lata@school.com', 'subject' => 'Art', 'classes' => ['9-A'], 'phone' => '+91 98765 43219', 'status' => 'active'],
        ];

        $teacherMap = [];
        $i = 1;
        foreach ($teachersData as $t) {
            $user = User::create([
                'name' => $t['name'],
                'email' => $t['email'],
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'phone' => $t['phone'],
                'status' => $t['status'],
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'employee_id' => 'TCH-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                'specialization' => $t['subject'],
                'qualification' => 'Master of Education, B.Ed',
                'joining_date' => '2022-06-01',
                'salary' => 6000.00,
            ]);
            $teacherMap[$t['name']] = $teacher;

            // Assign subject & classes
            if (isset($subjectMap[$t['subject']])) {
                foreach ($t['classes'] as $clsKey) {
                    if (isset($sectionMap[$clsKey])) {
                        $sec = $sectionMap[$clsKey];
                        TeacherSubjectAssignment::create([
                            'teacher_id' => $teacher->id,
                            'school_class_id' => $sec->school_class_id,
                            'section_id' => $sec->id,
                            'subject_id' => $subjectMap[$t['subject']]->id,
                        ]);
                    }
                }
            }
        }

        // 5. Students from Guide (Page 6 & 10)
        $studentsData = [
            ['roll' => '01', 'name' => 'Arjun K.', 'email' => 'arjun@school.com', 'class' => '9-A', 'phone' => '+91 98765 00001', 'status' => 'present'],
            ['roll' => '02', 'name' => 'Bala S.', 'email' => 'bala@school.com', 'class' => '9-A', 'phone' => '+91 98765 00002', 'status' => 'present'],
            ['roll' => '03', 'name' => 'Charan M.', 'email' => 'charan@school.com', 'class' => '9-A', 'phone' => '+91 98765 00003', 'status' => 'absent'],
            ['roll' => '04', 'name' => 'Deepa R.', 'email' => 'deepa@school.com', 'class' => '9-A', 'phone' => '+91 98765 00004', 'status' => 'present'],
            ['roll' => '05', 'name' => 'Eswar P.', 'email' => 'eswar@school.com', 'class' => '9-A', 'phone' => '+91 98765 00005', 'status' => 'present'],
            ['roll' => '06', 'name' => 'Farhan A.', 'email' => 'farhan@school.com', 'class' => '9-A', 'phone' => '+91 98765 00006', 'status' => 'absent'],
            ['roll' => '07', 'name' => 'Gayathri N.', 'email' => 'gayathri@school.com', 'class' => '9-A', 'phone' => '+91 98765 00007', 'status' => 'present'],
            ['roll' => '08', 'name' => 'Harish V.', 'email' => 'harish@school.com', 'class' => '9-A', 'phone' => '+91 98765 00008', 'status' => 'present'],
            ['roll' => '09', 'name' => 'Indu L.', 'email' => 'indu@school.com', 'class' => '9-A', 'phone' => '+91 98765 00009', 'status' => 'present'],
        ];

        $studentMap = [];
        $priyaTeacher = $teacherMap['Priya Sharma'];

        foreach ($studentsData as $st) {
            $user = User::create([
                'name' => $st['name'],
                'email' => $st['email'],
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone' => $st['phone'],
                'status' => 'active',
            ]);

            $sec = $sectionMap[$st['class']];
            $student = Student::create([
                'user_id' => $user->id,
                'admission_number' => 'STU-2026-' . $st['roll'],
                'roll_number' => $st['roll'],
                'school_class_id' => $sec->school_class_id,
                'section_id' => $sec->id,
                'guardian_name' => 'Parent of ' . $st['name'],
                'guardian_phone' => $st['phone'],
                'address' => 'Springfield School District',
            ]);
            $studentMap[$st['name']] = $student;

            // Attendance for Today (30 Oct 2026)
            Attendance::create([
                'student_id' => $student->id,
                'school_class_id' => $sec->school_class_id,
                'section_id' => $sec->id,
                'attendance_date' => '2026-10-30',
                'status' => $st['status'],
                'remarks' => $st['status'] == 'present' ? 'On time' : 'Absent',
                'marked_by' => $priyaTeacher->id,
            ]);
        }

        // Attendance calendar for Arjun K. across October 2026 (matching Page 18: 23 present, 2 absent, holidays)
        $arjun = $studentMap['Arjun K.'];
        for ($d = 1; $d <= 30; $d++) {
            $dt = Carbon::create(2026, 10, $d);
            if ($dt->isSunday()) continue;

            $status = 'present';
            if ($d == 7 || $d == 15) {
                $status = 'absent';
            }

            if ($d != 30) { // 30 is already created above
                Attendance::create([
                    'student_id' => $arjun->id,
                    'school_class_id' => $arjun->school_class_id,
                    'section_id' => $arjun->section_id,
                    'attendance_date' => $dt->toDateString(),
                    'status' => $status,
                    'remarks' => $status == 'present' ? 'Verified' : 'Absent',
                    'marked_by' => $priyaTeacher->id,
                ]);
            }
        }

        // 6. Homework from Guide (Page 11 & 16)
        $homeworkData = [
            [
                'title' => 'Algebra Exercise 4.2',
                'teacher' => 'Priya Sharma',
                'class' => '9-A',
                'subject' => 'Maths',
                'due' => '2026-11-02 23:59:00',
                'status' => 'active',
                'desc' => 'Solve all problems from Exercise 4.2 and show every step. Upload a clear photo or PDF of your answer.',
                'attachment' => 'worksheet.pdf',
            ],
            [
                'title' => 'Light - Reflection',
                'teacher' => 'Kumar R.',
                'class' => '9-B',
                'subject' => 'Science',
                'due' => '2026-11-03 23:59:00',
                'status' => 'active',
                'desc' => 'Draw ray diagrams for concave and convex mirrors.',
                'attachment' => 'optics_guide.pdf',
            ],
            [
                'title' => 'Letter Writing',
                'teacher' => 'Anitha S.',
                'class' => '9-A',
                'subject' => 'English',
                'due' => '2026-11-04 18:00:00',
                'status' => 'active',
                'desc' => 'Write a formal letter to the editor regarding local park conservation.',
                'attachment' => 'letter_format.pdf',
            ],
            [
                'title' => 'Kavithai Essay',
                'teacher' => 'Divya K.',
                'class' => '9-A',
                'subject' => 'Tamil',
                'due' => '2026-10-28 18:00:00',
                'status' => 'inactive',
                'desc' => 'Classic poetic composition analysis.',
                'attachment' => null,
            ],
            [
                'title' => 'Map Work - India',
                'teacher' => 'Rajesh M.',
                'class' => '9-A',
                'subject' => 'Social',
                'due' => '2026-11-07 23:59:00',
                'status' => 'active',
                'desc' => 'Locate major river basins and mountain ranges.',
                'attachment' => 'map_outline.pdf',
            ],
            [
                'title' => 'Mensuration Set 1',
                'teacher' => 'Priya Sharma',
                'class' => '10-A',
                'subject' => 'Maths',
                'due' => '2026-11-05 23:59:00',
                'status' => 'active',
                'desc' => 'Surface area and volume of combined solids.',
                'attachment' => null,
            ],
            [
                'title' => 'Quadratic Eq. Quiz',
                'teacher' => 'Priya Sharma',
                'class' => '9-A',
                'subject' => 'Maths',
                'due' => '2026-10-28 23:59:00',
                'status' => 'inactive',
                'desc' => 'Factorization and quadratic formula application.',
                'attachment' => null,
            ],
        ];

        $hwMap = [];
        foreach ($homeworkData as $hw) {
            $sec = $sectionMap[$hw['class']];
            $t = $teacherMap[$hw['teacher']];
            $s = $subjectMap[$hw['subject']];

            $assign = Assignment::create([
                'teacher_id' => $t->id,
                'school_class_id' => $sec->school_class_id,
                'section_id' => $sec->id,
                'subject_id' => $s->id,
                'title' => $hw['title'],
                'description' => $hw['desc'],
                'due_date' => $hw['due'],
                'total_points' => 100,
                'attachment' => $hw['attachment'],
            ]);
            $hwMap[$hw['title']] = $assign;
        }

        // 7. Submissions & Grades (matching Page 12 & 17)
        $algebraHw = $hwMap['Algebra Exercise 4.2'];
        
        // Arjun K. submission
        AssignmentSubmission::create([
            'assignment_id' => $algebraHw->id,
            'student_id' => $arjun->id,
            'submission_text' => "x = 4, y = 7. Solved using substitution method.",
            'file_attachment' => 'answer_arjun.pdf',
            'submitted_at' => Carbon::now()->subHours(2),
            'marks_awarded' => 85.00,
            'feedback' => 'Good work, keep it up. Show steps more clearly.',
            'status' => 'graded',
        ]);

        Grade::create([
            'student_id' => $arjun->id,
            'subject_id' => $subjectMap['Maths']->id,
            'teacher_id' => $priyaTeacher->id,
            'exam_name' => 'Algebra Ex. 4.2',
            'marks_obtained' => 85.00,
            'max_marks' => 100.00,
            'grade_letter' => 'Excellent',
            'remarks' => 'Good work, keep it up. Show steps more clearly.',
            'exam_date' => '2026-10-30',
        ]);

        // Additional results for Arjun K. (Page 17)
        $resultsData = [
            ['subject' => 'Science', 'exam' => 'Light - Reflection', 'score' => 72, 'grade' => 'Good', 'remarks' => 'Good understanding of ray diagrams.'],
            ['subject' => 'English', 'exam' => 'Letter Writing', 'score' => 64, 'grade' => 'Good', 'remarks' => 'Maintain correct formal structure.'],
            ['subject' => 'Maths', 'exam' => 'Quadratic Quiz', 'score' => 91, 'grade' => 'Excellent', 'remarks' => 'Exceptional algebra solutions.'],
            ['subject' => 'Social', 'exam' => 'Map Work', 'score' => 58, 'grade' => 'Nice', 'remarks' => 'Practice coastal boundaries.'],
            ['subject' => 'Tamil', 'exam' => 'Kavithai', 'score' => 78, 'grade' => 'Good', 'remarks' => 'Well expressed vocabulary.'],
        ];

        foreach ($resultsData as $res) {
            Grade::create([
                'student_id' => $arjun->id,
                'subject_id' => $subjectMap[$res['subject']]->id,
                'teacher_id' => $priyaTeacher->id,
                'exam_name' => $res['exam'],
                'marks_obtained' => $res['score'],
                'max_marks' => 100,
                'grade_letter' => $res['grade'],
                'remarks' => $res['remarks'],
                'exam_date' => Carbon::now()->subDays(5)->toDateString(),
            ]);
        }

        // Submissions for Bala S., Charan M., Deepa R., Eswar P., Farhan A., Gayathri N. (Page 12)
        $subStudents = [
            ['name' => 'Bala S.', 'status' => 'graded', 'score' => 88],
            ['name' => 'Charan M.', 'status' => 'submitted', 'score' => null],
            ['name' => 'Deepa R.', 'status' => 'graded', 'score' => 92],
            ['name' => 'Eswar P.', 'status' => 'graded', 'score' => 80],
            ['name' => 'Farhan A.', 'status' => 'submitted', 'score' => null],
            ['name' => 'Gayathri N.', 'status' => 'graded', 'score' => 95],
        ];

        foreach ($subStudents as $s) {
            if (isset($studentMap[$s['name']])) {
                AssignmentSubmission::create([
                    'assignment_id' => $algebraHw->id,
                    'student_id' => $studentMap[$s['name']]->id,
                    'submission_text' => 'Solved substitution homework attached.',
                    'file_attachment' => 'answer_' . strtolower(explode(' ', $s['name'])[0]) . '.pdf',
                    'submitted_at' => Carbon::now()->subHours(rand(1, 10)),
                    'marks_awarded' => $s['score'],
                    'feedback' => $s['score'] ? 'Good effort and clean calculations.' : null,
                    'status' => $s['status'],
                ]);
            }
        }

        // 8. Timetable from Guide (Page 5, 13, 19)
        // Schedule for Class 9-A across Mon-Sat
        $timetableGrid = [
            // Period, Start, Mon, Tue, Wed, Thu, Fri, Sat
            ['P1', '09:00:00', '09:45:00', ['Mon' => ['Maths', 'Priya Sharma'], 'Tue' => ['English', 'Anitha S.'], 'Wed' => ['Science', 'Kumar R.'], 'Thu' => ['Tamil', 'Divya K.'], 'Fri' => ['Social', 'Rajesh M.'], 'Sat' => ['Maths', 'Priya Sharma']]],
            ['P2', '09:50:00', '10:35:00', ['Mon' => ['English', 'Anitha S.'], 'Tue' => ['Maths', 'Priya Sharma'], 'Wed' => ['Tamil', 'Divya K.'], 'Thu' => ['Science', 'Kumar R.'], 'Fri' => ['Maths', 'Priya Sharma'], 'Sat' => ['Computer', 'Arun V.']]],
            ['P3', '10:45:00', '11:30:00', ['Mon' => ['Science', 'Kumar R.'], 'Tue' => ['Maths', 'Priya Sharma'], 'Wed' => ['Social', 'Rajesh M.'], 'Thu' => ['English', 'Anitha S.'], 'Fri' => ['Tamil', 'Divya K.'], 'Sat' => ['Science', 'Kumar R.']]],
            ['P4', '11:35:00', '12:20:00', ['Mon' => ['Tamil', 'Divya K.'], 'Tue' => ['Science', 'Kumar R.'], 'Wed' => ['English', 'Anitha S.'], 'Thu' => ['Maths', 'Priya Sharma'], 'Fri' => ['Science', 'Kumar R.'], 'Sat' => ['Social', 'Rajesh M.']]],
            ['P5', '13:00:00', '13:45:00', ['Mon' => ['Social', 'Rajesh M.'], 'Tue' => ['Computer', 'Arun V.'], 'Wed' => ['Maths', 'Priya Sharma'], 'Thu' => ['Social', 'Rajesh M.'], 'Fri' => ['English', 'Anitha S.'], 'Sat' => ['Tamil', 'Divya K.']]],
            ['P6', '14:00:00', '14:45:00', ['Mon' => ['PT', 'Mr. Ravi'], 'Tue' => ['Library', 'Kumar R.'], 'Wed' => ['Science', 'Kumar R.'], 'Thu' => ['Art', 'Ms. Lata'], 'Fri' => ['Computer', 'Arun V.'], 'Sat' => ['English', 'Anitha S.']]],
        ];

        $sec9A = $sectionMap['9-A'];

        foreach ($timetableGrid as $slot) {
            foreach ($slot[3] as $dayName => $alloc) {
                $subName = $alloc[0];
                $teachName = $alloc[1];

                Timetable::create([
                    'school_class_id' => $sec9A->school_class_id,
                    'section_id' => $sec9A->id,
                    'subject_id' => $subjectMap[$subName]->id,
                    'teacher_id' => $teacherMap[$teachName]->id,
                    'day_of_week' => $dayName,
                    'start_time' => $slot[1],
                    'end_time' => $slot[2],
                    'room_number' => 'Hall 9A',
                ]);
            }
        }

        // 9. Announcements from Guide (Page 3, 9, 15)
        Announcement::create([
            'title' => 'Annual Day on 12 Nov',
            'content' => 'Annual day celebrations and cultural exhibitions scheduled for 12th November in the main auditorium. All students and staff are invited.',
            'audience' => 'all',
            'priority' => 'important',
            'author_name' => 'Admin Office',
            'published_at' => '2026-10-25',
        ]);

        Announcement::create([
            'title' => 'PTA Meeting Saturday',
            'content' => 'Parent-Teacher association meeting will take place this Saturday from 10:00 AM to 01:00 PM.',
            'audience' => 'teachers',
            'priority' => 'normal',
            'author_name' => 'Principal Office',
            'published_at' => '2026-10-28',
        ]);

        Announcement::create([
            'title' => 'Unit Test Timetable',
            'content' => 'The revised unit test timetable for Class 9 and Class 10 has been published on the respective portal dashboards.',
            'audience' => 'all',
            'priority' => 'urgent',
            'author_name' => 'Academic Dean',
            'published_at' => '2026-10-29',
        ]);

        // 10. Fee Invoices
        FeeInvoice::create([
            'student_id' => $arjun->id,
            'invoice_number' => 'INV-2026-001',
            'title' => 'Term 1 Tuition & Lab Fee',
            'amount' => 1250.00,
            'paid_amount' => 1250.00,
            'due_date' => '2026-10-15',
            'status' => 'paid',
            'payment_date' => '2026-10-10',
            'payment_method' => 'Online NetBanking',
        ]);

        FeeInvoice::create([
            'student_id' => $arjun->id,
            'invoice_number' => 'INV-2026-002',
            'title' => 'Term 2 Tuition & Sports Fee',
            'amount' => 1400.00,
            'paid_amount' => 0.00,
            'due_date' => '2026-11-15',
            'status' => 'unpaid',
        ]);
    }
}
