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
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users & Roles
        $adminUser = User::create([
            'name' => 'Principal Arthur Hayes',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1 (555) 019-2831',
            'status' => 'active',
        ]);

        $teacherUser1 = User::create([
            'name' => 'Dr. Sarah Jenkins',
            'email' => 'teacher@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '+1 (555) 438-9102',
            'status' => 'active',
        ]);

        $teacherUser2 = User::create([
            'name' => 'Prof. Marcus Vance',
            'email' => 'math.teacher@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '+1 (555) 234-5678',
            'status' => 'active',
        ]);

        $teacherUser3 = User::create([
            'name' => 'Ms. Elena Rostova',
            'email' => 'english.teacher@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '+1 (555) 876-5432',
            'status' => 'active',
        ]);

        $studentUser1 = User::create([
            'name' => 'Alex Morgan',
            'email' => 'student@school.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1 (555) 678-1234',
            'status' => 'active',
        ]);

        $studentUser2 = User::create([
            'name' => 'Emma Watson',
            'email' => 'emma.watson@school.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1 (555) 789-2345',
            'status' => 'active',
        ]);

        $studentUser3 = User::create([
            'name' => 'Liam Smith',
            'email' => 'liam.smith@school.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1 (555) 890-3456',
            'status' => 'active',
        ]);

        $studentUser4 = User::create([
            'name' => 'Sophia Chen',
            'email' => 'sophia.chen@school.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1 (555) 901-4567',
            'status' => 'active',
        ]);

        // 2. Classes & Sections
        $grade10 = SchoolClass::create([
            'name' => 'Grade 10',
            'code' => 'G10',
            'description' => 'Sophomore secondary level education',
        ]);

        $grade9 = SchoolClass::create([
            'name' => 'Grade 9',
            'code' => 'G9',
            'description' => 'Freshman secondary level education',
        ]);

        $grade11 = SchoolClass::create([
            'name' => 'Grade 11',
            'code' => 'G11',
            'description' => 'Junior higher secondary level education',
        ]);

        $sec10A = Section::create([
            'school_class_id' => $grade10->id,
            'name' => 'Section A',
            'room_number' => 'Hall 204',
            'capacity' => 35,
        ]);

        $sec10B = Section::create([
            'school_class_id' => $grade10->id,
            'name' => 'Section B',
            'room_number' => 'Hall 205',
            'capacity' => 35,
        ]);

        $sec9A = Section::create([
            'school_class_id' => $grade9->id,
            'name' => 'Section A',
            'room_number' => 'Hall 102',
            'capacity' => 30,
        ]);

        $sec11A = Section::create([
            'school_class_id' => $grade11->id,
            'name' => 'Science Track A',
            'room_number' => 'Lab Building 301',
            'capacity' => 30,
        ]);

        // 3. Subjects
        $subMath = Subject::create([
            'name' => 'Advanced Mathematics',
            'code' => 'MATH-101',
            'credits' => 4,
            'description' => 'Trigonometry, Analytical Geometry, Functions, and Calculus fundamentals.',
        ]);

        $subPhysics = Subject::create([
            'name' => 'Physics & Mechanics',
            'code' => 'PHY-101',
            'credits' => 4,
            'description' => 'Kinematics, Thermodynamics, Electricity, and Experimental Physics.',
        ]);

        $subChem = Subject::create([
            'name' => 'Chemistry & Lab Sciences',
            'code' => 'CHEM-101',
            'credits' => 3,
            'description' => 'Atomic Structure, Chemical Bonding, Stoichiometry, and Lab experiments.',
        ]);

        $subEnglish = Subject::create([
            'name' => 'World Literature & English',
            'code' => 'ENG-101',
            'credits' => 3,
            'description' => 'Classical literature analysis, essay composition, and rhetorical writing.',
        ]);

        $subCS = Subject::create([
            'name' => 'Computer Science & Coding',
            'code' => 'CS-101',
            'credits' => 4,
            'description' => 'Algorithms, Python programming, Data structures, and Web foundations.',
        ]);

        // 4. Teachers
        $teacher1 = Teacher::create([
            'user_id' => $teacherUser1->id,
            'employee_id' => 'TCH-2026-001',
            'qualification' => 'Ph.D. in Physics, M.Ed.',
            'specialization' => 'Quantum Mechanics & STEM Education',
            'joining_date' => '2021-08-15',
            'salary' => 6800.00,
        ]);

        $teacher2 = Teacher::create([
            'user_id' => $teacherUser2->id,
            'employee_id' => 'TCH-2026-002',
            'qualification' => 'M.Sc. Mathematics',
            'specialization' => 'Applied Calculus & Linear Algebra',
            'joining_date' => '2022-01-10',
            'salary' => 6400.00,
        ]);

        $teacher3 = Teacher::create([
            'user_id' => $teacherUser3->id,
            'employee_id' => 'TCH-2026-003',
            'qualification' => 'M.A. English Literature',
            'specialization' => 'Comparative Literature & Linguistics',
            'joining_date' => '2023-09-01',
            'salary' => 5900.00,
        ]);

        // 5. Teacher Subject Assignments
        TeacherSubjectAssignment::create([
            'teacher_id' => $teacher1->id,
            'school_class_id' => $grade10->id,
            'section_id' => $sec10A->id,
            'subject_id' => $subPhysics->id,
        ]);

        TeacherSubjectAssignment::create([
            'teacher_id' => $teacher1->id,
            'school_class_id' => $grade10->id,
            'section_id' => $sec10A->id,
            'subject_id' => $subChem->id,
        ]);

        TeacherSubjectAssignment::create([
            'teacher_id' => $teacher2->id,
            'school_class_id' => $grade10->id,
            'section_id' => $sec10A->id,
            'subject_id' => $subMath->id,
        ]);

        TeacherSubjectAssignment::create([
            'teacher_id' => $teacher2->id,
            'school_class_id' => $grade10->id,
            'section_id' => $sec10A->id,
            'subject_id' => $subCS->id,
        ]);

        TeacherSubjectAssignment::create([
            'teacher_id' => $teacher3->id,
            'school_class_id' => $grade10->id,
            'section_id' => $sec10A->id,
            'subject_id' => $subEnglish->id,
        ]);

        // 6. Students
        $student1 = Student::create([
            'user_id' => $studentUser1->id,
            'admission_number' => 'STU-2026-101',
            'roll_number' => '101',
            'school_class_id' => $grade10->id,
            'section_id' => $sec10A->id,
            'dob' => '2010-04-12',
            'gender' => 'Male',
            'blood_group' => 'O+',
            'guardian_name' => 'Robert Morgan',
            'guardian_phone' => '+1 (555) 234-9988',
            'guardian_email' => 'robert.morgan@example.com',
            'address' => '742 Evergreen Terrace, Springfield',
        ]);

        $student2 = Student::create([
            'user_id' => $studentUser2->id,
            'admission_number' => 'STU-2026-102',
            'roll_number' => '102',
            'school_class_id' => $grade10->id,
            'section_id' => $sec10A->id,
            'dob' => '2010-09-20',
            'gender' => 'Female',
            'blood_group' => 'A+',
            'guardian_name' => 'Catherine Watson',
            'guardian_phone' => '+1 (555) 345-1122',
            'guardian_email' => 'catherine.watson@example.com',
            'address' => '12 Oak Ridge Road, Springfield',
        ]);

        $student3 = Student::create([
            'user_id' => $studentUser3->id,
            'admission_number' => 'STU-2026-103',
            'roll_number' => '103',
            'school_class_id' => $grade10->id,
            'section_id' => $sec10B->id,
            'dob' => '2009-12-05',
            'gender' => 'Male',
            'blood_group' => 'B+',
            'guardian_name' => 'David Smith',
            'guardian_phone' => '+1 (555) 456-2233',
            'guardian_email' => 'david.smith@example.com',
            'address' => '88 Maple Street, Springfield',
        ]);

        $student4 = Student::create([
            'user_id' => $studentUser4->id,
            'admission_number' => 'STU-2026-104',
            'roll_number' => '104',
            'school_class_id' => $grade9->id,
            'section_id' => $sec9A->id,
            'dob' => '2011-06-18',
            'gender' => 'Female',
            'blood_group' => 'AB+',
            'guardian_name' => 'Grace Chen',
            'guardian_phone' => '+1 (555) 567-3344',
            'guardian_email' => 'grace.chen@example.com',
            'address' => '54 Riverdale Avenue, Springfield',
        ]);

        // 7. Attendances (last 7 weekdays)
        $dates = [
            Carbon::now()->subDays(6)->toDateString(),
            Carbon::now()->subDays(5)->toDateString(),
            Carbon::now()->subDays(4)->toDateString(),
            Carbon::now()->subDays(3)->toDateString(),
            Carbon::now()->subDays(2)->toDateString(),
            Carbon::now()->subDays(1)->toDateString(),
            Carbon::now()->toDateString(),
        ];

        foreach ($dates as $idx => $date) {
            // Student 1 (Alex Morgan): Mostly present, 1 late
            Attendance::create([
                'student_id' => $student1->id,
                'school_class_id' => $grade10->id,
                'section_id' => $sec10A->id,
                'attendance_date' => $date,
                'status' => ($idx == 2) ? 'late' : 'present',
                'remarks' => ($idx == 2) ? 'Bus delay 15 mins' : 'On time',
                'marked_by' => $teacher1->id,
            ]);

            // Student 2 (Emma Watson): All present
            Attendance::create([
                'student_id' => $student2->id,
                'school_class_id' => $grade10->id,
                'section_id' => $sec10A->id,
                'attendance_date' => $date,
                'status' => 'present',
                'remarks' => 'Active participation',
                'marked_by' => $teacher1->id,
            ]);
        }

        // 8. Exam Grades
        $exams = [
            [
                'student_id' => $student1->id,
                'subject_id' => $subMath->id,
                'teacher_id' => $teacher2->id,
                'exam_name' => 'Midterm Examination 2026',
                'marks_obtained' => 92.50,
                'max_marks' => 100.00,
                'grade_letter' => 'A+',
                'remarks' => 'Outstanding analytical reasoning and calculus problem solving.',
                'exam_date' => Carbon::now()->subDays(14)->toDateString(),
            ],
            [
                'student_id' => $student1->id,
                'subject_id' => $subPhysics->id,
                'teacher_id' => $teacher1->id,
                'exam_name' => 'Midterm Examination 2026',
                'marks_obtained' => 88.00,
                'max_marks' => 100.00,
                'grade_letter' => 'A',
                'remarks' => 'Very good grasp of classical kinematics and energy conservation.',
                'exam_date' => Carbon::now()->subDays(12)->toDateString(),
            ],
            [
                'student_id' => $student1->id,
                'subject_id' => $subCS->id,
                'teacher_id' => $teacher2->id,
                'exam_name' => 'Midterm Examination 2026',
                'marks_obtained' => 96.00,
                'max_marks' => 100.00,
                'grade_letter' => 'A+',
                'remarks' => 'Excellent coding clean code and algorithm design.',
                'exam_date' => Carbon::now()->subDays(10)->toDateString(),
            ],
            [
                'student_id' => $student1->id,
                'subject_id' => $subEnglish->id,
                'teacher_id' => $teacher3->id,
                'exam_name' => 'Midterm Examination 2026',
                'marks_obtained' => 84.50,
                'max_marks' => 100.00,
                'grade_letter' => 'B+',
                'remarks' => 'Thoughtful literary critique, improve conclusion transitions.',
                'exam_date' => Carbon::now()->subDays(8)->toDateString(),
            ],
            // Student 2 (Emma Watson)
            [
                'student_id' => $student2->id,
                'subject_id' => $subPhysics->id,
                'teacher_id' => $teacher1->id,
                'exam_name' => 'Midterm Examination 2026',
                'marks_obtained' => 95.00,
                'max_marks' => 100.00,
                'grade_letter' => 'A+',
                'remarks' => 'Perfect experimental analysis in lab report and theory.',
                'exam_date' => Carbon::now()->subDays(12)->toDateString(),
            ],
            [
                'student_id' => $student2->id,
                'subject_id' => $subMath->id,
                'teacher_id' => $teacher2->id,
                'exam_name' => 'Midterm Examination 2026',
                'marks_obtained' => 98.00,
                'max_marks' => 100.00,
                'grade_letter' => 'A+',
                'remarks' => 'Top scorer in Grade 10 Section A.',
                'exam_date' => Carbon::now()->subDays(14)->toDateString(),
            ],
        ];

        foreach ($exams as $exam) {
            Grade::create($exam);
        }

        // 9. Assignments
        $assign1 = Assignment::create([
            'teacher_id' => $teacher1->id,
            'school_class_id' => $grade10->id,
            'section_id' => $sec10A->id,
            'subject_id' => $subPhysics->id,
            'title' => 'Newtonian Mechanics & Projectile Motion Lab Report',
            'description' => 'Complete the laboratory analysis on trajectory angles, calculating velocity vectors and air resistance impact. Submit your formal PDF report.',
            'due_date' => Carbon::now()->addDays(3)->setTime(23, 59),
            'total_points' => 100,
            'attachment' => 'lab_guideline_week4.pdf',
        ]);

        $assign2 = Assignment::create([
            'teacher_id' => $teacher2->id,
            'school_class_id' => $grade10->id,
            'section_id' => $sec10A->id,
            'subject_id' => $subMath->id,
            'title' => 'Quadratic Functions & Parabolic Curve Modeling',
            'description' => 'Solve chapters 4.2 through 4.6 practice problem sets. Include step-by-step discriminant calculations and graphical sketches.',
            'due_date' => Carbon::now()->addDays(5)->setTime(23, 59),
            'total_points' => 50,
            'attachment' => 'math_problem_set_ch4.pdf',
        ]);

        $assign3 = Assignment::create([
            'teacher_id' => $teacher3->id,
            'school_class_id' => $grade10->id,
            'section_id' => $sec10A->id,
            'subject_id' => $subEnglish->id,
            'title' => 'Shakespearean Tragedy Essay: Ambition & Fate',
            'description' => 'Write a 1,200-word critical essay exploring the descent of Macbeth with reference to Act 1 through Act 3 motifs.',
            'due_date' => Carbon::now()->addDays(8)->setTime(18, 00),
            'total_points' => 100,
            'attachment' => 'essay_rubric.pdf',
        ]);

        // 10. Assignment Submissions
        AssignmentSubmission::create([
            'assignment_id' => $assign1->id,
            'student_id' => $student1->id,
            'submission_text' => 'Here is my complete lab calculation with trajectory velocity calculations attached.',
            'file_attachment' => 'alex_morgan_physics_lab.pdf',
            'submitted_at' => Carbon::now()->subDays(1),
            'marks_awarded' => 95.00,
            'feedback' => 'Exceptional graph representations and precise error propagation calculations!',
            'status' => 'graded',
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assign1->id,
            'student_id' => $student2->id,
            'submission_text' => 'Lab report submitted along with experimental data table in appendix.',
            'file_attachment' => 'emma_watson_lab_report.pdf',
            'submitted_at' => Carbon::now()->subHours(18),
            'marks_awarded' => 98.00,
            'feedback' => 'Flawless work! Highly detailed discussion of experimental uncertainties.',
            'status' => 'graded',
        ]);

        // 11. Timetables (Monday to Friday schedule for Grade 10-A)
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $schedule = [
            ['08:30:00', '09:30:00', $subMath->id, $teacher2->id, 'Hall 204'],
            ['09:45:00', '10:45:00', $subPhysics->id, $teacher1->id, 'Physics Lab 1'],
            ['11:00:00', '12:00:00', $subEnglish->id, $teacher3->id, 'Hall 204'],
            ['13:00:00', '14:00:00', $subCS->id, $teacher2->id, 'Computer Lab A'],
            ['14:15:00', '15:15:00', $subChem->id, $teacher1->id, 'Chemistry Lab 2'],
        ];

        foreach ($days as $day) {
            foreach ($schedule as $slot) {
                Timetable::create([
                    'school_class_id' => $grade10->id,
                    'section_id' => $sec10A->id,
                    'subject_id' => $slot[2],
                    'teacher_id' => $slot[3],
                    'day_of_week' => $day,
                    'start_time' => $slot[0],
                    'end_time' => $slot[1],
                    'room_number' => $slot[4],
                ]);
            }
        }

        // 12. Announcements
        Announcement::create([
            'title' => 'Annual STEM Science & Robotics Expo 2026',
            'content' => 'Registration for the inter-school science and robotics exhibition is now open. Students interested in submitting robotics or renewable energy projects should submit project abstracts to Dr. Sarah Jenkins before November 15.',
            'audience' => 'all',
            'priority' => 'urgent',
            'author_name' => 'Principal Arthur Hayes',
            'published_at' => Carbon::now()->subDays(2)->toDateString(),
        ]);

        Announcement::create([
            'title' => 'Term 1 Midterm Grade Reports Published',
            'content' => 'The academic evaluation board has released midterm evaluation reports across all departments. Students and teachers can view published grades on their respective portals.',
            'audience' => 'all',
            'priority' => 'important',
            'author_name' => 'Dean of Academics',
            'published_at' => Carbon::now()->subDays(4)->toDateString(),
        ]);

        Announcement::create([
            'title' => 'Faculty Staff Meeting: Curriculum Review',
            'content' => 'All senior faculty members are invited to the bi-weekly curriculum sync on Friday at 03:30 PM in the Conference Hall.',
            'audience' => 'teachers',
            'priority' => 'normal',
            'author_name' => 'Principal Arthur Hayes',
            'published_at' => Carbon::now()->subDays(1)->toDateString(),
        ]);

        // 13. Fee Invoices
        FeeInvoice::create([
            'student_id' => $student1->id,
            'invoice_number' => 'INV-2026-0081',
            'title' => 'Academic Tuition - Semester 1 (2026)',
            'amount' => 1250.00,
            'paid_amount' => 1250.00,
            'due_date' => Carbon::now()->subMonth()->toDateString(),
            'status' => 'paid',
            'payment_date' => Carbon::now()->subDays(20)->toDateString(),
            'payment_method' => 'Credit Card (Online)',
        ]);

        FeeInvoice::create([
            'student_id' => $student1->id,
            'invoice_number' => 'INV-2026-0142',
            'title' => 'STEM Laboratory & Advanced Computing Access',
            'amount' => 350.00,
            'paid_amount' => 350.00,
            'due_date' => Carbon::now()->addDays(15)->toDateString(),
            'status' => 'paid',
            'payment_date' => Carbon::now()->subDays(2)->toDateString(),
            'payment_method' => 'Bank Wire',
        ]);

        FeeInvoice::create([
            'student_id' => $student1->id,
            'invoice_number' => 'INV-2026-0219',
            'title' => 'Annual Sports & Extracurricular Activities Fund',
            'amount' => 180.00,
            'paid_amount' => 0.00,
            'due_date' => Carbon::now()->addDays(20)->toDateString(),
            'status' => 'unpaid',
            'payment_date' => null,
            'payment_method' => null,
        ]);

        FeeInvoice::create([
            'student_id' => $student2->id,
            'invoice_number' => 'INV-2026-0082',
            'title' => 'Academic Tuition - Semester 1 (2026)',
            'amount' => 1250.00,
            'paid_amount' => 1250.00,
            'due_date' => Carbon::now()->subMonth()->toDateString(),
            'status' => 'paid',
            'payment_date' => Carbon::now()->subDays(22)->toDateString(),
            'payment_method' => 'Net Banking',
        ]);
    }
}
