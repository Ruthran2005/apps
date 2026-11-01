<?php

namespace Database\Seeders;

use App\Models\AdmissionEnquiry;
use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Certificate;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\LeaveApplication;
use App\Models\Payroll;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\SchoolEvent;
use App\Models\SchoolSetting;
use App\Models\SentNotification;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Syllabus;
use App\Models\Teacher;
use App\Models\TransportAllocation;
use App\Models\TransportRoute;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EnterpriseErpSeeder extends Seeder
{
    public function run(): void
    {
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $teachers = Teacher::with('user')->get();
        $students = Student::with('user')->get();

        // 1. Fee Structures & Payments
        if (FeeStructure::count() === 0 && $classes->count() > 0) {
            foreach ($classes as $cls) {
                FeeStructure::create([
                    'school_class_id' => $cls->id,
                    'fee_type' => 'Tuition Fee',
                    'amount' => 1200.00,
                    'frequency' => 'Term',
                    'due_date' => Carbon::now()->addDays(20)->toDateString(),
                    'description' => 'Standard academic term tuition fee including laboratory & digital learning access.',
                ]);
                FeeStructure::create([
                    'school_class_id' => $cls->id,
                    'fee_type' => 'Library & Tech Lab',
                    'amount' => 250.00,
                    'frequency' => 'Annual',
                    'due_date' => Carbon::now()->addDays(30)->toDateString(),
                    'description' => 'Annual library resource access, digital catalog and computer science lab maintenance.',
                ]);
                FeeStructure::create([
                    'school_class_id' => $cls->id,
                    'fee_type' => 'Sports & Development',
                    'amount' => 150.00,
                    'frequency' => 'Annual',
                    'due_date' => Carbon::now()->addDays(40)->toDateString(),
                    'description' => 'Athletics, sports equipment, campus wellness, and student development fund.',
                ]);
            }
        }

        if (FeePayment::count() === 0 && $students->count() > 0) {
            $invoices = FeeInvoice::all();
            foreach ($invoices as $inv) {
                if ($inv->paid_amount > 0) {
                    FeePayment::create([
                        'fee_invoice_id' => $inv->id,
                        'student_id' => $inv->student_id,
                        'receipt_number' => 'RCPT-' . strtoupper(uniqid()),
                        'amount_paid' => $inv->paid_amount,
                        'payment_mode' => $inv->payment_method ?? 'Online UPI',
                        'transaction_reference' => 'TXN-' . rand(10000000, 99999999),
                        'paid_at' => $inv->payment_date ?? Carbon::now()->subDays(rand(1, 15))->toDateString(),
                        'received_by' => 'Central Finance Desk',
                        'notes' => 'Received with thanks. Official electronic receipt generated.',
                    ]);
                }
            }
        }

        // 2. Exams & Schedules
        if (Exam::count() === 0) {
            $exam1 = Exam::create([
                'name' => 'Mid-Term Summative Assessment 2026',
                'term' => 'Term 1',
                'academic_year' => '2026-2027',
                'start_date' => Carbon::now()->addDays(14)->toDateString(),
                'end_date' => Carbon::now()->addDays(24)->toDateString(),
                'status' => 'Scheduled',
                'description' => 'Comprehensive mid-term evaluation covering Units 1, 2, and 3 across all academic divisions.',
            ]);

            $exam2 = Exam::create([
                'name' => 'Quarterly Benchmark Evaluation',
                'term' => 'Term 1',
                'academic_year' => '2026-2027',
                'start_date' => Carbon::now()->subDays(30)->toDateString(),
                'end_date' => Carbon::now()->subDays(20)->toDateString(),
                'status' => 'Completed',
                'description' => 'Quarterly unit test to evaluate foundation skills and baseline competencies.',
            ]);

            if ($classes->count() > 0 && $subjects->count() > 0) {
                foreach ($subjects->take(3) as $idx => $sub) {
                    ExamSchedule::create([
                        'exam_id' => $exam1->id,
                        'school_class_id' => $classes->first()->id,
                        'subject_id' => $sub->id,
                        'exam_date' => Carbon::now()->addDays(15 + $idx)->toDateString(),
                        'start_time' => '09:30:00',
                        'end_time' => '12:30:00',
                        'room_number' => 'Hall-' . (101 + $idx),
                        'max_marks' => 100.00,
                        'passing_marks' => 35.00,
                    ]);
                }
            }
        }

        // 3. Syllabi
        if (Syllabus::count() === 0 && $subjects->count() > 0 && $classes->count() > 0) {
            $firstClass = $classes->first();
            foreach ($subjects as $idx => $sub) {
                Syllabus::create([
                    'subject_id' => $sub->id,
                    'school_class_id' => $firstClass->id,
                    'title' => 'Core Fundamentals & Methodology',
                    'unit_code' => 'Unit ' . ($idx + 1),
                    'learning_objectives' => 'Understand primary concepts, theoretical frameworks, problem solving drills and practical laboratory applications.',
                    'term' => 'Term 1',
                    'status' => 'Ongoing',
                ]);
                Syllabus::create([
                    'subject_id' => $sub->id,
                    'school_class_id' => $firstClass->id,
                    'title' => 'Advanced Applied Modules & Project',
                    'unit_code' => 'Unit ' . ($idx + 2),
                    'learning_objectives' => 'Case studies, research projects, critical thinking exercises, and term-end synthesis.',
                    'term' => 'Term 2',
                    'status' => 'Planned',
                ]);
            }
        }

        // 4. Leave Applications
        if (LeaveApplication::count() === 0) {
            if ($teachers->count() > 0) {
                LeaveApplication::create([
                    'user_id' => $teachers->first()->user_id,
                    'applicant_type' => 'teacher',
                    'leave_type' => 'Casual Leave',
                    'start_date' => Carbon::now()->addDays(2)->toDateString(),
                    'end_date' => Carbon::now()->addDays(3)->toDateString(),
                    'days_count' => 2,
                    'reason' => 'Attending University Educational Symposium & pedagogy workshop.',
                    'status' => 'pending',
                ]);
            }
            if ($students->count() > 0) {
                LeaveApplication::create([
                    'user_id' => $students->first()->user_id,
                    'applicant_type' => 'student',
                    'leave_type' => 'Medical Leave',
                    'start_date' => Carbon::now()->subDays(2)->toDateString(),
                    'end_date' => Carbon::now()->toDateString(),
                    'days_count' => 3,
                    'reason' => 'Viral fever and prescribed medical rest as per attached physician note.',
                    'status' => 'approved',
                    'reviewed_by' => 'Principal Office',
                    'admin_remarks' => 'Approved on medical grounds. Medical certificate verified.',
                ]);
            }
        }

        // 5. Library Books & Book Issues
        if (Book::count() === 0) {
            $b1 = Book::create([
                'title' => 'Calculus: Early Transcendentals',
                'isbn' => '978-0134763644',
                'author' => 'James Stewart',
                'publisher' => 'Cengage Learning',
                'category' => 'Mathematics',
                'total_copies' => 15,
                'available_copies' => 12,
                'shelf_location' => 'Rack A-04',
            ]);
            $b2 = Book::create([
                'title' => 'Concepts of Modern Physics',
                'isbn' => '978-0072448481',
                'author' => 'Arthur Beiser',
                'publisher' => 'McGraw-Hill Education',
                'category' => 'Physics',
                'total_copies' => 10,
                'available_copies' => 8,
                'shelf_location' => 'Rack B-02',
            ]);
            $b3 = Book::create([
                'title' => 'To Kill a Mockingbird',
                'isbn' => '978-0060935467',
                'author' => 'Harper Lee',
                'publisher' => 'Harper Perennial',
                'category' => 'Literature',
                'total_copies' => 20,
                'available_copies' => 18,
                'shelf_location' => 'Rack C-01',
            ]);
            $b4 = Book::create([
                'title' => 'Introduction to Algorithms (CLRS)',
                'isbn' => '978-0262033848',
                'author' => 'Thomas H. Cormen',
                'publisher' => 'MIT Press',
                'category' => 'Computer Science',
                'total_copies' => 8,
                'available_copies' => 6,
                'shelf_location' => 'Rack D-05',
            ]);

            if ($students->count() > 0) {
                BookIssue::create([
                    'book_id' => $b1->id,
                    'user_id' => $students->first()->user_id,
                    'user_type' => 'student',
                    'issue_date' => Carbon::now()->subDays(5)->toDateString(),
                    'due_date' => Carbon::now()->addDays(9)->toDateString(),
                    'status' => 'issued',
                    'remarks' => 'Issued for midterm calculus problem solving.',
                ]);
            }
        }

        // 6. Transport: Vehicles, Routes, Allocations
        if (Vehicle::count() === 0) {
            $v1 = Vehicle::create([
                'vehicle_number' => 'KA-04-NX-1042',
                'model' => 'Tata Starbus 42-Seater AC',
                'seating_capacity' => 42,
                'driver_name' => 'Rajesh Sharma',
                'driver_phone' => '+91 98450 12345',
                'driver_license' => 'DL-KA04-2018-98765',
                'status' => 'active',
            ]);
            $v2 = Vehicle::create([
                'vehicle_number' => 'KA-04-NX-2085',
                'model' => 'Ashok Leyland Sunshine 36-Seater',
                'seating_capacity' => 36,
                'driver_name' => 'Michael D Souza',
                'driver_phone' => '+91 98450 67890',
                'driver_license' => 'DL-KA04-2019-44321',
                'status' => 'active',
            ]);

            $r1 = TransportRoute::create([
                'route_name' => 'Route 1: North Metro Corridor',
                'vehicle_id' => $v1->id,
                'start_location' => 'North Gate Circle',
                'end_location' => 'Main Campus Hub',
                'stops' => 'Greenwood Junction, Lotus Garden, Palm Meadows, Central Park, Tech Park Boulevard',
                'fare' => 450.00,
                'pickup_time' => '07:15:00',
                'drop_time' => '16:00:00',
            ]);

            $r2 = TransportRoute::create([
                'route_name' => 'Route 2: South City Express',
                'vehicle_id' => $v2->id,
                'start_location' => 'Royal Palms South',
                'end_location' => 'Main Campus Hub',
                'stops' => 'Oakridge Ave, Lakeview Point, Harmony Cross, Sunshine Circle',
                'fare' => 400.00,
                'pickup_time' => '07:25:00',
                'drop_time' => '15:50:00',
            ]);

            if ($students->count() > 0) {
                TransportAllocation::create([
                    'student_id' => $students->first()->id,
                    'transport_route_id' => $r1->id,
                    'pickup_stop' => 'Lotus Garden',
                    'status' => 'active',
                ]);
            }
        }

        // 7. Events & Holiday Calendar
        if (SchoolEvent::count() === 0) {
            SchoolEvent::create([
                'title' => 'Annual Inter-School Science & Robotics Fair',
                'event_type' => 'event',
                'start_date' => Carbon::now()->addDays(8)->toDateString(),
                'end_date' => Carbon::now()->addDays(9)->toDateString(),
                'audience' => 'all',
                'location' => 'School Auditorium & Tech Wing',
                'description' => 'Showcasing cutting-edge robotics, green tech inventions, and STEM innovation projects.',
                'is_holiday' => false,
            ]);

            SchoolEvent::create([
                'title' => 'National Independence & Foundation Day',
                'event_type' => 'holiday',
                'start_date' => Carbon::now()->addDays(18)->toDateString(),
                'end_date' => Carbon::now()->addDays(18)->toDateString(),
                'audience' => 'all',
                'location' => 'Campus Grounds',
                'description' => 'Flag hoisting assembly in the morning followed by official academic holiday.',
                'is_holiday' => true,
            ]);

            SchoolEvent::create([
                'title' => 'Parent-Faculty Collaborative Summit',
                'event_type' => 'meeting',
                'start_date' => Carbon::now()->addDays(25)->toDateString(),
                'end_date' => Carbon::now()->addDays(25)->toDateString(),
                'audience' => 'parents',
                'location' => 'Academic Conference Hall',
                'description' => 'One-on-one progress review between parents, subject heads, and class advisors.',
                'is_holiday' => false,
            ]);
        }

        // 8. Admissions & Enquiries
        if (AdmissionEnquiry::count() === 0) {
            AdmissionEnquiry::create([
                'enquiry_number' => 'ENQ-2026-001',
                'applicant_name' => 'Aarav Singhania',
                'parent_name' => 'Dr. Vikram Singhania',
                'email' => 'vikram.singhania@hospital.org',
                'phone' => '+91 98860 11223',
                'school_class_id' => $classes->first()?->id,
                'dob' => '2012-05-14',
                'gender' => 'Male',
                'previous_school' => 'Delhi Public School, R.K. Puram',
                'address' => 'Villa 42, Palm Meadows, Whitefield',
                'status' => 'interview_scheduled',
                'notes' => 'Top academic track record in mathematics and competitive chess.',
                'enquiry_date' => Carbon::now()->subDays(4)->toDateString(),
            ]);

            AdmissionEnquiry::create([
                'enquiry_number' => 'ENQ-2026-002',
                'applicant_name' => 'Ananya Deshmukh',
                'parent_name' => 'Pooja Deshmukh',
                'email' => 'pooja.deshmukh@fintech.com',
                'phone' => '+91 97410 44556',
                'school_class_id' => $classes->first()?->id,
                'dob' => '2013-08-22',
                'gender' => 'Female',
                'previous_school' => 'Bishop Cotton Girls School',
                'address' => 'Tower C-904, Prestige Lakeside Habitat',
                'status' => 'new',
                'notes' => 'Interested in IB curriculum transition and classical music arts program.',
                'enquiry_date' => Carbon::now()->subDays(1)->toDateString(),
            ]);
        }

        // 9. Staff Payroll
        if (Payroll::count() === 0 && $teachers->count() > 0) {
            foreach ($teachers as $tch) {
                $base = $tch->salary ?? 4500.00;
                $allowances = 500.00;
                $deductions = 250.00;
                Payroll::create([
                    'teacher_id' => $tch->id,
                    'month' => 'October',
                    'year' => 2026,
                    'basic_salary' => $base,
                    'allowances' => $allowances,
                    'deductions' => $deductions,
                    'net_salary' => ($base + $allowances) - $deductions,
                    'payment_method' => 'Direct Bank Transfer',
                    'payment_date' => Carbon::now()->subDays(3)->toDateString(),
                    'status' => 'paid',
                    'transaction_id' => 'HDFC-SAL-' . rand(1000000, 9999999),
                    'notes' => 'October salary disbursed including conveyance & academic allowances.',
                ]);
            }
        }

        // 10. Certificates
        if (Certificate::count() === 0 && $students->count() > 0) {
            Certificate::create([
                'certificate_type' => 'bonafide',
                'certificate_number' => 'BON-2026-0042',
                'student_id' => $students->first()->id,
                'issue_date' => Carbon::now()->subDays(7)->toDateString(),
                'reason' => 'Passport renewal and institutional verification.',
                'details' => 'This is to certify that the student is a bona fide scholar of this institution in good standing.',
                'issued_by' => 'Principal Dr. Eleanor Vance',
            ]);
        }

        // 11. Sent Notifications
        if (SentNotification::count() === 0) {
            SentNotification::create([
                'channel' => 'sms',
                'target_group' => 'all',
                'title' => 'Parent-Teacher Conference Reminder',
                'message' => 'Dear Parents, PTM is scheduled for Saturday 9:00 AM. Please book your time slot in the portal.',
                'sent_by' => 'Central Administration',
                'status' => 'delivered',
            ]);
            SentNotification::create([
                'channel' => 'email',
                'target_group' => 'teachers',
                'title' => 'Midterm Question Paper Submission Deadline',
                'message' => 'All department heads are requested to upload finalized examination question papers by Friday.',
                'sent_by' => 'Exam Controller',
                'status' => 'delivered',
            ]);
        }

        // 12. Roles
        if (Role::count() === 0) {
            Role::create([
                'name' => 'Super Administrator',
                'slug' => 'super-admin',
                'description' => 'Unrestricted institutional privileges across every module, security rule, and financial ledger.',
                'permissions' => array_keys(\App\Http\Controllers\AdminController::AVAILABLE_TABS),
            ]);
            Role::create([
                'name' => 'Academic Dean',
                'slug' => 'academic-dean',
                'description' => 'Manages teachers, students, curriculum, timetable, exams, and attendance.',
                'permissions' => ['dashboard', 'students', 'teachers', 'classes', 'timetable', 'attendance', 'exams', 'subjects', 'leaves', 'announcements'],
            ]);
            Role::create([
                'name' => 'Finance Controller & Bursar',
                'slug' => 'finance-controller',
                'description' => 'Oversees student fee structures, invoices, payment receipts, payroll, and financial reports.',
                'permissions' => ['dashboard', 'fees', 'payroll', 'reports'],
            ]);
            Role::create([
                'name' => 'Chief Librarian',
                'slug' => 'librarian',
                'description' => 'Manages book catalog, barcode tracking, lending, returns, and overdue fees.',
                'permissions' => ['dashboard', 'library'],
            ]);
            Role::create([
                'name' => 'Transport Incharge',
                'slug' => 'transport-manager',
                'description' => 'Fleet management, bus route planning, driver records, and student transport allocations.',
                'permissions' => ['dashboard', 'transport'],
            ]);
        }

        // 13. Settings Defaults
        $settingsDefaults = [
            'school_name' => 'SchoolNexus International Academy',
            'school_motto' => 'Excellence in Education, Character, and Innovation',
            'academic_year' => '2026-2027',
            'principal_name' => 'Dr. Eleanor Vance, Ph.D.',
            'affiliation_number' => 'CBSE/AFF/2026/99412',
            'currency_symbol' => '$',
            'school_email' => 'admin@schoolnexus.edu',
            'school_phone' => '+1 (800) 555-0199',
            'school_address' => '742 Evergreen Campus Avenue, Silicon Hills, CA 94025',
            'attendance_threshold' => '75',
            'grading_system' => 'Letter (A+, A, B, C, D, F)',
            'library_issue_limit' => '3',
            'library_fine_per_day' => '1.00',
        ];

        foreach ($settingsDefaults as $k => $v) {
            SchoolSetting::firstOrCreate(['key' => $k], ['value' => $v]);
        }
    }
}
