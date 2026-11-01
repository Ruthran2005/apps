<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\AdmissionEnquiry;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Attendance;
use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Certificate;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\Grade;
use App\Models\LeaveApplication;
use App\Models\Payroll;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\SchoolEvent;
use App\Models\SchoolSetting;
use App\Models\Section;
use App\Models\SentNotification;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Syllabus;
use App\Models\Teacher;
use App\Models\TeacherSubjectAssignment;
use App\Models\Timetable;
use App\Models\TransportAllocation;
use App\Models\TransportRoute;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\TwilioSmsGateway;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public const AVAILABLE_TABS = [
        'dashboard' => 'Dashboard',
        'students' => 'Students',
        'teachers' => 'Teachers',
        'classes' => 'Classes & Sections',
        'subjects' => 'Subjects & Syllabus',
        'timetable' => 'Timetable',
        'attendance' => 'Attendance',
        'exams' => 'Exams & Marks',
        'fees' => 'Fee Management',
        'leaves' => 'Leave Management',
        'library' => 'Library Management',
        'transport' => 'Transport',
        'events' => 'Events & Calendar',
        'admissions' => 'Admissions & Enquiries',
        'payroll' => 'Staff Payroll',
        'certificates' => 'ID Cards & Certificates',
        'notifications' => 'Notifications',
        'homework' => 'Homework (View Only)',
        'reports' => 'Reports & Analytics',
        'announcements' => 'Announcements',
        'roles' => 'Roles & Permissions',
        'manage-admins' => 'Manage Admins',
        'audit-logs' => 'Backup & Activity Logs',
        'settings' => 'Settings',
        'change-password' => 'Change Password',
    ];

    protected function getActiveAdmin(Request $request)
    {
        $adminId = $request->session()->get('active_admin_id', null);
        if ($adminId) {
            $admin = User::where('role', 'admin')->find($adminId);
            if ($admin) {
                return $admin;
            }
        }
        if (Auth::check() && Auth::user()->role === 'admin') {
            return Auth::user();
        }
        return User::where('role', 'admin')->first() ?? User::first();
    }

    public function switchAdmin(Request $request, $id)
    {
        $request->session()->put('active_admin_id', $id);
        return redirect()->back()->with('success', 'Active admin account switched successfully.');
    }

    protected function checkTabAccess($admin, string $tabKey): bool
    {
        if (empty($admin->permissions)) {
            return true;
        }
        return in_array($tabKey, $admin->permissions);
    }

    protected function calculateOverallAttendanceRate(): float
    {
        $total = Attendance::count();
        if ($total === 0) return 96.5;
        $present = Attendance::whereIn('status', ['present', 'late'])->count();
        return round(($present / $total) * 100, 1);
    }

    // 1. Dashboard
    public function index(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_classes' => SchoolClass::count(),
            'total_subjects' => Subject::count(),
            'total_homework' => Assignment::count(),
            'attendance_rate' => $this->calculateOverallAttendanceRate(),
            'total_collected_fees' => FeePayment::sum('amount_paid') + FeeInvoice::sum('paid_amount'),
            'pending_leaves' => LeaveApplication::where('status', 'pending')->count(),
            'upcoming_exams' => Exam::where('status', 'Scheduled')->count(),
            'total_books' => Book::sum('total_copies'),
        ];

        $recentStudents = Student::with(['user', 'schoolClass', 'section'])
            ->latest()
            ->take(5)
            ->get();

        $teachers = Teacher::with(['user', 'teacherSubjectAssignments.subject'])
            ->latest()
            ->take(4)
            ->get();

        $announcements = Announcement::latest()
            ->take(3)
            ->get();

        $upcomingEvents = SchoolEvent::where('start_date', '>=', Carbon::now()->toDateString())
            ->orderBy('start_date')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact('admin', 'stats', 'recentStudents', 'teachers', 'announcements', 'upcomingEvents'));
    }

    // 2. Students
    public function students(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $query = Student::with(['user', 'schoolClass', 'section']);

        if ($request->filled('class_id')) {
            $query->where('school_class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('admission_number', 'like', "%{$search}%")
              ->orWhere('roll_number', 'like', "%{$search}%");
        }

        $students = $query->latest()->paginate(15)->withQueryString();
        $classes = SchoolClass::with('sections')->get();

        return view('admin.students', compact('admin', 'students', 'classes'));
    }

    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'admission_number' => 'required|string|unique:students,admission_number',
            'roll_number' => 'nullable|string',
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'nullable|date',
            'blood_group' => 'nullable|string',
            'guardian_name' => 'nullable|string',
            'guardian_phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'status' => 'active',
        ]);

        Student::create([
            'user_id' => $user->id,
            'admission_number' => $validated['admission_number'],
            'roll_number' => $validated['roll_number'] ?? null,
            'school_class_id' => $validated['school_class_id'],
            'section_id' => $validated['section_id'] ?? null,
            'gender' => $validated['gender'],
            'dob' => $validated['dob'] ?? null,
            'blood_group' => $validated['blood_group'] ?? null,
            'guardian_name' => $validated['guardian_name'] ?? null,
            'guardian_phone' => $validated['guardian_phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        ActivityLog::log('created', 'Students', "Registered student {$user->name} ({$validated['admission_number']})");

        return redirect()->route('admin.students')->with('success', 'Student enrolled successfully!');
    }

    public function updateStudent(Request $request, $id)
    {
        $student = Student::with('user')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'roll_number' => 'nullable|string',
            'gender' => 'required|in:Male,Female,Other',
            'guardian_name' => 'nullable|string',
            'guardian_phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $student->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $student->update([
            'school_class_id' => $validated['school_class_id'],
            'section_id' => $validated['section_id'] ?? null,
            'roll_number' => $validated['roll_number'] ?? null,
            'gender' => $validated['gender'],
            'guardian_name' => $validated['guardian_name'] ?? null,
            'guardian_phone' => $validated['guardian_phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        ActivityLog::log('updated', 'Students', "Updated student profile for {$student->user->name}");

        return redirect()->route('admin.students')->with('success', 'Student details updated successfully!');
    }

    public function destroyStudent($id)
    {
        $student = Student::with('user')->findOrFail($id);
        $name = $student->user->name ?? 'Student';
        $student->user()->delete();

        ActivityLog::log('deleted', 'Students', "Deleted student account for {$name}");

        return redirect()->route('admin.students')->with('success', 'Student record deleted successfully.');
    }

    // 3. Teachers
    public function teachers(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $query = Teacher::with(['user', 'teacherSubjectAssignments.subject', 'teacherSubjectAssignments.schoolClass']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('employee_id', 'like', "%{$search}%")
              ->orWhere('specialization', 'like', "%{$search}%");
        }

        $teachers = $query->latest()->paginate(15)->withQueryString();
        $classes = SchoolClass::with('sections')->get();
        $subjects = Subject::all();

        return view('admin.teachers', compact('admin', 'teachers', 'classes', 'subjects'));
    }

    public function storeTeacher(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'employee_id' => 'required|string|unique:teachers,employee_id',
            'qualification' => 'nullable|string',
            'specialization' => 'nullable|string',
            'salary' => 'nullable|numeric',
            'joining_date' => 'nullable|date',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'teacher',
            'status' => 'active',
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'employee_id' => $validated['employee_id'],
            'qualification' => $validated['qualification'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'salary' => $validated['salary'] ?? 4500.00,
            'joining_date' => $validated['joining_date'] ?? Carbon::now()->toDateString(),
        ]);

        ActivityLog::log('created', 'Teachers', "Added teacher {$user->name} ({$validated['employee_id']})");

        return redirect()->route('admin.teachers')->with('success', 'Teacher added successfully!');
    }

    public function updateTeacher(Request $request, $id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'qualification' => 'nullable|string',
            'specialization' => 'nullable|string',
            'salary' => 'nullable|numeric',
        ]);

        $teacher->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $teacher->update([
            'qualification' => $validated['qualification'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'salary' => $validated['salary'] ?? $teacher->salary,
        ]);

        ActivityLog::log('updated', 'Teachers', "Updated faculty profile for {$teacher->user->name}");

        return redirect()->route('admin.teachers')->with('success', 'Teacher profile updated successfully!');
    }

    public function destroyTeacher($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        $name = $teacher->user->name ?? 'Teacher';
        $teacher->user()->delete();

        ActivityLog::log('deleted', 'Teachers', "Removed faculty record for {$name}");

        return redirect()->route('admin.teachers')->with('success', 'Teacher record removed.');
    }

    // 4. Classes
    public function classes(Request $request)
    {
        $admin = $this->getActiveAdmin($request);
        $classes = SchoolClass::with(['sections', 'students'])->get();
        return view('admin.classes', compact('admin', 'classes'));
    }

    public function storeClass(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:school_classes,code',
            'description' => 'nullable|string',
        ]);

        SchoolClass::create($validated);
        ActivityLog::log('created', 'Classes', "Created academic class {$validated['name']} ({$validated['code']})");

        return redirect()->route('admin.classes')->with('success', 'Class created successfully!');
    }

    public function storeSection(Request $request)
    {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'name' => 'required|string|max:50',
            'room_number' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
        ]);

        Section::create($validated);
        return redirect()->route('admin.classes')->with('success', 'Section added successfully!');
    }

    public function destroyClass($id)
    {
        $class = SchoolClass::findOrFail($id);
        $name = $class->name;
        $class->delete();
        ActivityLog::log('deleted', 'Classes', "Deleted class {$name}");

        return redirect()->route('admin.classes')->with('success', 'Class deleted successfully.');
    }

    // 5. Timetable
    public function timetable(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $selectedClassId = $request->get('class_id', SchoolClass::first()?->id);
        $classes = SchoolClass::with('sections')->get();
        $subjects = Subject::all();
        $teachers = Teacher::with('user')->get();

        $timetables = Timetable::with(['schoolClass', 'section', 'subject', 'teacher.user'])
            ->when($selectedClassId, fn($q) => $q->where('school_class_id', $selectedClassId))
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        return view('admin.timetable', compact('admin', 'timetables', 'classes', 'subjects', 'teachers', 'selectedClassId'));
    }

    public function storeTimetable(Request $request)
    {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required',
            'end_time' => 'required',
            'room_number' => 'nullable|string',
        ]);

        Timetable::create($validated);
        ActivityLog::log('created', 'Timetable', "Added schedule slot on {$validated['day_of_week']}");

        return redirect()->route('admin.timetable', ['class_id' => $validated['school_class_id']])
            ->with('success', 'Timetable slot scheduled successfully!');
    }

    public function destroyTimetable($id)
    {
        $slot = Timetable::findOrFail($id);
        $classId = $slot->school_class_id;
        $slot->delete();

        return redirect()->route('admin.timetable', ['class_id' => $classId])
            ->with('success', 'Timetable slot removed.');
    }

    // 6. Attendance
    public function attendance(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $date = $request->get('date', Carbon::today()->toDateString());
        $classId = $request->get('class_id', SchoolClass::first()?->id);

        $classes = SchoolClass::with('sections')->get();

        $students = Student::with(['user', 'schoolClass', 'section'])
            ->when($classId, fn($q) => $q->where('school_class_id', $classId))
            ->get();

        $attendances = Attendance::where('attendance_date', $date)
            ->when($classId, fn($q) => $q->where('school_class_id', $classId))
            ->get()
            ->keyBy('student_id');

        return view('admin.attendance', compact('admin', 'classes', 'students', 'attendances', 'date', 'classId'));
    }

    public function exportAttendance(Request $request)
    {
        $classId = $request->get('class_id');
        $fromDate = $request->get('from_date', Carbon::now()->startOfMonth()->toDateString());
        $toDate = $request->get('to_date', Carbon::today()->toDateString());

        $records = Attendance::with(['student.user', 'schoolClass', 'section'])
            ->when($classId, fn($q) => $q->where('school_class_id', $classId))
            ->whereBetween('attendance_date', [$fromDate, $toDate])
            ->orderBy('attendance_date', 'desc')
            ->get();

        $response = new StreamedResponse(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Student Name', 'Admission No', 'Class', 'Section', 'Date', 'Status', 'Remarks']);
            foreach ($records as $row) {
                fputcsv($handle, [
                    $row->student->user->name ?? 'N/A',
                    $row->student->admission_number ?? 'N/A',
                    $row->schoolClass->name ?? 'N/A',
                    $row->section->name ?? 'N/A',
                    $row->attendance_date,
                    strtoupper($row->status),
                    $row->remarks ?? '',
                ]);
            }
            fclose($handle);
        });

        $filename = "attendance_export_" . date('Ymd_His') . ".csv";
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");

        return $response;
    }

    // 7. Announcements
    public function announcements(Request $request)
    {
        $admin = $this->getActiveAdmin($request);
        $announcements = Announcement::latest()->paginate(10);
        return view('admin.announcements', compact('admin', 'announcements'));
    }

    public function storeAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audience' => 'required|in:all,teachers,students',
            'priority' => 'required|in:normal,important,urgent',
        ]);

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'audience' => $validated['audience'],
            'priority' => $validated['priority'],
            'author_name' => Auth::user()->name ?? 'School Administration',
            'published_at' => Carbon::now()->toDateString(),
        ]);

        ActivityLog::log('created', 'Announcements', "Broadcasted notice: {$validated['title']}");

        return redirect()->route('admin.announcements')->with('success', 'Announcement published!');
    }

    public function updateAnnouncement(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audience' => 'required|in:all,teachers,students',
            'priority' => 'required|in:normal,important,urgent',
        ]);

        $announcement->update($validated);
        ActivityLog::log('updated', 'Announcements', "Edited notice: {$announcement->title}");

        return redirect()->route('admin.announcements')->with('success', 'Announcement updated successfully!');
    }

    public function destroyAnnouncement($id)
    {
        Announcement::findOrFail($id)->delete();
        return redirect()->route('admin.announcements')->with('success', 'Announcement removed.');
    }

    // 8. Homework
    public function homework(Request $request)
    {
        $admin = $this->getActiveAdmin($request);
        $assignments = Assignment::with(['teacher.user', 'schoolClass', 'section', 'subject', 'submissions.student.user'])
            ->latest()
            ->paginate(10);

        return view('admin.homework', compact('admin', 'assignments'));
    }

    public function downloadHomeworkFile($type, $id)
    {
        if ($type === 'assignment') {
            $assignment = Assignment::findOrFail($id);
            if ($assignment->attachment && Storage::exists($assignment->attachment)) {
                return Storage::download($assignment->attachment);
            }
        } elseif ($type === 'submission') {
            $submission = AssignmentSubmission::findOrFail($id);
            if ($submission->file_attachment && Storage::exists($submission->file_attachment)) {
                return Storage::download($submission->file_attachment);
            }
        }
        return redirect()->back()->withErrors(['File not found on storage server.']);
    }

    // 9. Fee Management
    public function fees(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $classes = SchoolClass::all();
        $feeStructures = FeeStructure::with('schoolClass')->latest()->get();
        $invoices = FeeInvoice::with(['student.user', 'student.schoolClass'])->latest()->paginate(15);
        $payments = FeePayment::with(['student.user', 'feeInvoice'])->latest()->take(20)->get();

        $stats = [
            'total_invoiced' => FeeInvoice::sum('amount'),
            'total_collected' => FeeInvoice::sum('paid_amount'),
            'total_pending' => FeeInvoice::where('status', '!=', 'paid')->sum(DB::raw('amount - paid_amount')),
            'paid_invoices_count' => FeeInvoice::where('status', 'paid')->count(),
            'pending_invoices_count' => FeeInvoice::where('status', '!=', 'paid')->count(),
        ];

        return view('admin.fees', compact('admin', 'classes', 'feeStructures', 'invoices', 'payments', 'stats'));
    }

    public function storeFeeStructure(Request $request)
    {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'fee_type' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:Annual,Term,Quarterly,Monthly',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        FeeStructure::create($validated);
        ActivityLog::log('created', 'Fees', "Added {$validated['fee_type']} of \${$validated['amount']}");

        return redirect()->route('admin.fees')->with('success', 'Fee structure added successfully!');
    }

    public function destroyFeeStructure($id)
    {
        FeeStructure::findOrFail($id)->delete();
        return redirect()->route('admin.fees')->with('success', 'Fee structure tier removed.');
    }

    public function storeFeePayment(Request $request)
    {
        $validated = $request->validate([
            'fee_invoice_id' => 'required|exists:fee_invoices,id',
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_mode' => 'required|string',
            'transaction_reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $invoice = FeeInvoice::findOrFail($validated['fee_invoice_id']);

        $receiptNo = 'RCPT-' . strtoupper(uniqid());

        FeePayment::create([
            'fee_invoice_id' => $invoice->id,
            'student_id' => $invoice->student_id,
            'receipt_number' => $receiptNo,
            'amount_paid' => $validated['amount_paid'],
            'payment_mode' => $validated['payment_mode'],
            'transaction_reference' => $validated['transaction_reference'] ?? null,
            'paid_at' => Carbon::now()->toDateString(),
            'received_by' => Auth::user()->name ?? 'Admin Cashier',
            'notes' => $validated['notes'] ?? null,
        ]);

        $newPaidAmount = $invoice->paid_amount + $validated['amount_paid'];
        $newStatus = $newPaidAmount >= $invoice->amount ? 'paid' : 'partial';

        $invoice->update([
            'paid_amount' => $newPaidAmount,
            'status' => $newStatus,
            'payment_date' => Carbon::now()->toDateString(),
            'payment_method' => $validated['payment_mode'],
        ]);

        ActivityLog::log('created', 'Fees', "Recorded fee payment {$receiptNo} for \${$validated['amount_paid']}");

        return redirect()->route('admin.fees')->with('success', "Payment of \${$validated['amount_paid']} recorded! Receipt: {$receiptNo}");
    }

    public function printReceipt($id)
    {
        $payment = FeePayment::with(['student.user', 'student.schoolClass', 'feeInvoice'])->findOrFail($id);
        return view('admin.print.receipt', compact('payment'));
    }

    // 10. Exams & Marks
    public function exams(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $exams = Exam::with(['schedules.subject', 'schedules.schoolClass'])->latest()->get();
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $students = Student::with('user')->get();
        $grades = Grade::with(['student.user', 'subject'])->latest()->paginate(20);

        return view('admin.exams', compact('admin', 'exams', 'classes', 'subjects', 'students', 'grades'));
    }

    public function storeExam(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'term' => 'required|string|max:50',
            'academic_year' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Scheduled,In-Progress,Completed,Published',
            'description' => 'nullable|string',
        ]);

        Exam::create($validated);
        ActivityLog::log('created', 'Exams', "Created examination {$validated['name']}");

        return redirect()->route('admin.exams')->with('success', 'Exam registered successfully!');
    }

    public function destroyExam($id)
    {
        Exam::findOrFail($id)->delete();
        return redirect()->route('admin.exams')->with('success', 'Exam deleted.');
    }

    public function storeExamSchedule(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'room_number' => 'nullable|string',
            'max_marks' => 'required|numeric|min:1',
            'passing_marks' => 'required|numeric|min:0',
        ]);

        ExamSchedule::create($validated);
        return redirect()->route('admin.exams')->with('success', 'Exam schedule paper added!');
    }

    public function destroyExamSchedule($id)
    {
        ExamSchedule::findOrFail($id)->delete();
        return redirect()->route('admin.exams')->with('success', 'Schedule timetable slot removed.');
    }

    public function storeMarks(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_name' => 'required|string',
            'marks_obtained' => 'required|numeric|min:0',
            'max_marks' => 'required|numeric|min:1',
            'remarks' => 'nullable|string',
        ]);

        $pct = ($validated['marks_obtained'] / $validated['max_marks']) * 100;
        $letter = $pct >= 90 ? 'A+' : ($pct >= 80 ? 'A' : ($pct >= 70 ? 'B' : ($pct >= 60 ? 'C' : ($pct >= 40 ? 'D' : 'F'))));

        Grade::create([
            'student_id' => $validated['student_id'],
            'subject_id' => $validated['subject_id'],
            'exam_name' => $validated['exam_name'],
            'marks_obtained' => $validated['marks_obtained'],
            'max_marks' => $validated['max_marks'],
            'grade_letter' => $letter,
            'remarks' => $validated['remarks'] ?? null,
            'exam_date' => Carbon::now()->toDateString(),
        ]);

        ActivityLog::log('created', 'Exams', "Entered marks for Student ID #{$validated['student_id']}");

        return redirect()->route('admin.exams')->with('success', 'Marks entered and grade letter generated!');
    }

    public function reportCard(Request $request, $studentId)
    {
        $student = Student::with(['user', 'schoolClass', 'section'])->findOrFail($studentId);
        $grades = Grade::with('subject')->where('student_id', $studentId)->get();

        $totalObtained = $grades->sum('marks_obtained');
        $totalMax = $grades->sum('max_marks');
        $percentage = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
        $overallLetter = $percentage >= 90 ? 'A+' : ($percentage >= 80 ? 'A' : ($percentage >= 70 ? 'B' : ($percentage >= 60 ? 'C' : ($percentage >= 40 ? 'D' : 'F'))));

        return view('admin.print.report-card', compact('student', 'grades', 'totalObtained', 'totalMax', 'percentage', 'overallLetter'));
    }

    // 11. Subjects & Syllabus
    public function subjects(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $subjects = Subject::withCount(['teacherSubjectAssignments'])->get();
        $classes = SchoolClass::all();
        $syllabi = Syllabus::with(['subject', 'schoolClass'])->latest()->paginate(15);

        return view('admin.subjects', compact('admin', 'subjects', 'classes', 'syllabi'));
    }

    public function storeSubject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects,code',
            'credits' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Subject::create($validated);
        ActivityLog::log('created', 'Subjects', "Created subject {$validated['name']} ({$validated['code']})");

        return redirect()->route('admin.subjects')->with('success', 'Subject created successfully!');
    }

    public function destroySubject($id)
    {
        Subject::findOrFail($id)->delete();
        return redirect()->route('admin.subjects')->with('success', 'Subject deleted.');
    }

    public function storeSyllabus(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'title' => 'required|string|max:255',
            'unit_code' => 'nullable|string',
            'learning_objectives' => 'nullable|string',
            'term' => 'required|string',
            'status' => 'required|in:Planned,Ongoing,Completed',
        ]);

        Syllabus::create($validated);
        ActivityLog::log('created', 'Syllabus', "Added curriculum unit {$validated['title']}");

        return redirect()->route('admin.subjects')->with('success', 'Syllabus module published!');
    }

    public function destroySyllabus($id)
    {
        Syllabus::findOrFail($id)->delete();
        return redirect()->route('admin.subjects')->with('success', 'Syllabus unit deleted.');
    }

    // 12. Leave Management
    public function leaves(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $leaves = LeaveApplication::with('user')->latest()->paginate(20);
        $pendingCount = LeaveApplication::where('status', 'pending')->count();
        $approvedCount = LeaveApplication::where('status', 'approved')->count();
        $rejectedCount = LeaveApplication::where('status', 'rejected')->count();

        return view('admin.leaves', compact('admin', 'leaves', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function updateLeaveStatus(Request $request, $id)
    {
        $leave = LeaveApplication::with('user')->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_remarks' => 'nullable|string',
        ]);

        $leave->update([
            'status' => $validated['status'],
            'admin_remarks' => $validated['admin_remarks'] ?? null,
            'reviewed_by' => Auth::user()->name ?? 'Principal Office',
        ]);

        ActivityLog::log('updated', 'Leaves', "{$validated['status']} leave for {$leave->user->name}");

        return redirect()->route('admin.leaves')->with('success', "Leave request marked as {$validated['status']}!");
    }

    // 13. Library Management
    public function library(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $books = Book::latest()->paginate(15);
        $issues = BookIssue::with(['book', 'user'])->latest()->paginate(15);
        $users = User::whereIn('role', ['student', 'teacher'])->get();

        $stats = [
            'total_titles' => Book::count(),
            'total_volumes' => Book::sum('total_copies'),
            'currently_issued' => BookIssue::where('status', 'issued')->count(),
            'overdue_count' => BookIssue::where('status', 'overdue')->count(),
        ];

        return view('admin.library', compact('admin', 'books', 'issues', 'users', 'stats'));
    }

    public function storeBook(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'total_copies' => 'required|integer|min:1',
            'shelf_location' => 'nullable|string|max:100',
        ]);

        $validated['available_copies'] = $validated['total_copies'];
        Book::create($validated);

        ActivityLog::log('created', 'Library', "Added book title {$validated['title']}");

        return redirect()->route('admin.library')->with('success', 'Book added to catalog!');
    }

    public function destroyBook($id)
    {
        Book::findOrFail($id)->delete();
        return redirect()->route('admin.library')->with('success', 'Book removed from library.');
    }

    public function issueBook(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date|after:today',
            'remarks' => 'nullable|string',
        ]);

        $book = Book::findOrFail($validated['book_id']);
        if ($book->available_copies < 1) {
            return redirect()->back()->withErrors(['No available copies of this title currently in shelf.']);
        }

        $user = User::findOrFail($validated['user_id']);

        BookIssue::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'user_type' => $user->role === 'teacher' ? 'teacher' : 'student',
            'issue_date' => Carbon::now()->toDateString(),
            'due_date' => $validated['due_date'],
            'status' => 'issued',
            'remarks' => $validated['remarks'] ?? null,
        ]);

        $book->decrement('available_copies');
        ActivityLog::log('created', 'Library', "Issued {$book->title} to {$user->name}");

        return redirect()->route('admin.library')->with('success', 'Book issued successfully!');
    }

    public function returnBook(Request $request, $id)
    {
        $issue = BookIssue::with('book')->findOrFail($id);

        $issue->update([
            'status' => 'returned',
            'return_date' => Carbon::now()->toDateString(),
        ]);

        $issue->book->increment('available_copies');
        ActivityLog::log('updated', 'Library', "Returned book {$issue->book->title}");

        return redirect()->route('admin.library')->with('success', 'Book received and returned to shelf.');
    }

    // 14. Transport
    public function transport(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $vehicles = Vehicle::withCount('routes')->get();
        $routes = TransportRoute::with(['vehicle', 'allocations.student.user'])->get();
        $students = Student::with('user')->get();
        $allocations = TransportAllocation::with(['student.user', 'route.vehicle'])->latest()->paginate(15);

        return view('admin.transport', compact('admin', 'vehicles', 'routes', 'students', 'allocations'));
    }

    public function storeVehicle(Request $request)
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|unique:vehicles,vehicle_number',
            'model' => 'required|string|max:255',
            'seating_capacity' => 'required|integer|min:1',
            'driver_name' => 'required|string|max:255',
            'driver_phone' => 'required|string|max:50',
            'driver_license' => 'nullable|string',
            'status' => 'required|in:active,maintenance,inactive',
        ]);

        Vehicle::create($validated);
        ActivityLog::log('created', 'Transport', "Added fleet vehicle {$validated['vehicle_number']}");

        return redirect()->route('admin.transport')->with('success', 'Vehicle registered!');
    }

    public function destroyVehicle($id)
    {
        Vehicle::findOrFail($id)->delete();
        return redirect()->route('admin.transport')->with('success', 'Vehicle removed from registry.');
    }

    public function storeTransportRoute(Request $request)
    {
        $validated = $request->validate([
            'route_name' => 'required|string|max:255',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'start_location' => 'required|string',
            'end_location' => 'required|string',
            'stops' => 'nullable|string',
            'fare' => 'required|numeric|min:0',
            'pickup_time' => 'nullable',
            'drop_time' => 'nullable',
        ]);

        TransportRoute::create($validated);
        ActivityLog::log('created', 'Transport', "Configured route {$validated['route_name']}");

        return redirect()->route('admin.transport')->with('success', 'Transport route planned!');
    }

    public function destroyTransportRoute($id)
    {
        TransportRoute::findOrFail($id)->delete();
        return redirect()->route('admin.transport')->with('success', 'Route deleted.');
    }

    public function allocateTransport(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'transport_route_id' => 'required|exists:transport_routes,id',
            'pickup_stop' => 'nullable|string',
        ]);

        TransportAllocation::updateOrCreate(
            ['student_id' => $validated['student_id']],
            [
                'transport_route_id' => $validated['transport_route_id'],
                'pickup_stop' => $validated['pickup_stop'] ?? null,
                'status' => 'active',
            ]
        );

        return redirect()->route('admin.transport')->with('success', 'Student transport allocated successfully!');
    }

    public function destroyTransportAllocation($id)
    {
        TransportAllocation::findOrFail($id)->delete();
        return redirect()->route('admin.transport')->with('success', 'Allocation revoked.');
    }

    // 15. Events & Holiday Calendar
    public function events(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $events = SchoolEvent::orderBy('start_date', 'asc')->get();
        $holidays = SchoolEvent::where('is_holiday', true)->orderBy('start_date', 'asc')->get();

        return view('admin.events', compact('admin', 'events', 'holidays'));
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_type' => 'required|in:event,holiday,sports,exam,celebration,meeting',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'audience' => 'required|in:all,teachers,students,parents',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'is_holiday' => 'nullable|boolean',
        ]);

        $validated['is_holiday'] = $request->boolean('is_holiday') || $validated['event_type'] === 'holiday';

        SchoolEvent::create($validated);
        ActivityLog::log('created', 'Events', "Created event {$validated['title']}");

        return redirect()->route('admin.events')->with('success', 'Event added to institutional calendar!');
    }

    public function destroyEvent($id)
    {
        SchoolEvent::findOrFail($id)->delete();
        return redirect()->route('admin.events')->with('success', 'Event deleted from calendar.');
    }

    // 16. Admissions & Enquiries
    public function admissions(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $enquiries = AdmissionEnquiry::with('schoolClass')->latest()->paginate(15);
        $classes = SchoolClass::all();

        $stats = [
            'total' => AdmissionEnquiry::count(),
            'new' => AdmissionEnquiry::where('status', 'new')->count(),
            'interview' => AdmissionEnquiry::where('status', 'interview_scheduled')->count(),
            'admitted' => AdmissionEnquiry::where('status', 'admitted')->count(),
        ];

        return view('admin.admissions', compact('admin', 'enquiries', 'classes', 'stats'));
    }

    public function storeEnquiry(Request $request)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'parent_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:50',
            'school_class_id' => 'nullable|exists:school_classes,id',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'previous_school' => 'nullable|string',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['enquiry_number'] = 'ENQ-' . date('Y') . '-' . rand(1000, 9999);
        $validated['enquiry_date'] = Carbon::now()->toDateString();
        $validated['status'] = 'new';

        AdmissionEnquiry::create($validated);
        ActivityLog::log('created', 'Admissions', "New applicant enquiry: {$validated['applicant_name']}");

        return redirect()->route('admin.admissions')->with('success', "Enquiry registered with ID {$validated['enquiry_number']}!");
    }

    public function updateEnquiryStatus(Request $request, $id)
    {
        $enquiry = AdmissionEnquiry::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:new,contacted,interview_scheduled,admitted,rejected',
            'notes' => 'nullable|string',
        ]);

        $enquiry->update($validated);
        ActivityLog::log('updated', 'Admissions', "Enquiry {$enquiry->enquiry_number} marked as {$validated['status']}");

        return redirect()->route('admin.admissions')->with('success', 'Admission enquiry status updated!');
    }

    public function destroyEnquiry($id)
    {
        AdmissionEnquiry::findOrFail($id)->delete();
        return redirect()->route('admin.admissions')->with('success', 'Enquiry record removed.');
    }

    // 17. Staff Payroll
    public function payroll(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $teachers = Teacher::with('user')->get();
        $payrolls = Payroll::with('teacher.user')->latest()->paginate(20);

        $stats = [
            'total_disbursed' => Payroll::where('status', 'paid')->sum('net_salary'),
            'pending_salaries' => Payroll::where('status', 'pending')->sum('net_salary'),
            'disbursed_slips' => Payroll::where('status', 'paid')->count(),
        ];

        return view('admin.payroll', compact('admin', 'teachers', 'payrolls', 'stats'));
    }

    public function generatePayroll(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'month' => 'required|string',
            'year' => 'required|integer',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $allowances = $validated['allowances'] ?? 0;
        $deductions = $validated['deductions'] ?? 0;
        $net = ($validated['basic_salary'] + $allowances) - $deductions;

        Payroll::create([
            'teacher_id' => $validated['teacher_id'],
            'month' => $validated['month'],
            'year' => $validated['year'],
            'basic_salary' => $validated['basic_salary'],
            'allowances' => $allowances,
            'deductions' => $deductions,
            'net_salary' => $net,
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        ActivityLog::log('created', 'Payroll', "Generated payroll slip for Teacher ID #{$validated['teacher_id']}");

        return redirect()->route('admin.payroll')->with('success', 'Salary slip generated in pending status!');
    }

    public function markPayrollPaid(Request $request, $id)
    {
        $payroll = Payroll::with('teacher.user')->findOrFail($id);

        $payroll->update([
            'status' => 'paid',
            'payment_date' => Carbon::now()->toDateString(),
            'transaction_id' => 'SAL-' . strtoupper(uniqid()),
        ]);

        ActivityLog::log('updated', 'Payroll', "Disbursed salary of \${$payroll->net_salary} to {$payroll->teacher->user->name}");

        return redirect()->route('admin.payroll')->with('success', 'Salary marked as paid and electronic slip dispatched!');
    }

    public function payslip($id)
    {
        $payroll = Payroll::with(['teacher.user'])->findOrFail($id);
        return view('admin.print.payslip', compact('payroll'));
    }

    // 18. ID Cards & Certificates
    public function certificates(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $students = Student::with(['user', 'schoolClass', 'section'])->get();
        $teachers = Teacher::with('user')->get();
        $certificates = Certificate::with(['student.user', 'student.schoolClass', 'teacher.user'])->latest()->paginate(15);

        return view('admin.certificates', compact('admin', 'students', 'teachers', 'certificates'));
    }

    public function generateCertificate(Request $request)
    {
        $validated = $request->validate([
            'certificate_type' => 'required|in:transfer_certificate,bonafide,character,id_card',
            'student_id' => 'required_without:teacher_id|nullable|exists:students,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'reason' => 'nullable|string',
            'details' => 'nullable|string',
        ]);

        $prefix = strtoupper(substr($validated['certificate_type'], 0, 3));
        $certNo = "{$prefix}-" . date('Y') . '-' . rand(10000, 99999);

        Certificate::create([
            'certificate_type' => $validated['certificate_type'],
            'certificate_number' => $certNo,
            'student_id' => $validated['student_id'] ?? null,
            'teacher_id' => $validated['teacher_id'] ?? null,
            'issue_date' => Carbon::now()->toDateString(),
            'reason' => $validated['reason'] ?? null,
            'details' => $validated['details'] ?? null,
            'issued_by' => Auth::user()->name ?? 'Principal Office',
        ]);

        ActivityLog::log('created', 'Certificates', "Generated {$validated['certificate_type']} ({$certNo})");

        return redirect()->route('admin.certificates')->with('success', "Certificate {$certNo} generated successfully!");
    }

    public function printCertificate($id)
    {
        $cert = Certificate::with(['student.user', 'student.schoolClass', 'student.section', 'teacher.user'])->findOrFail($id);
        return view('admin.print.certificate', compact('cert'));
    }

    // 19. Notifications
    public function notifications(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $notifications = SentNotification::with('schoolClass')->latest()->paginate(15);
        $classes = SchoolClass::all();
        $smsConfigured = app(TwilioSmsGateway::class)->isConfigured();

        return view('admin.notifications', compact('admin', 'notifications', 'classes', 'smsConfigured'));
    }

    public function sendNotification(Request $request)
    {
        $validated = $request->validate([
            'channel' => 'required|in:sms',
            'target_group' => 'required|in:single,all,students,teachers,class_specific',
            'school_class_id' => 'required_if:target_group,class_specific|nullable|exists:school_classes,id',
            'recipient_phone' => ['required_if:target_group,single', 'nullable', 'regex:/^\+[1-9]\d{7,14}$/'],
            'title' => 'required|string|max:80',
            'message' => 'required|string|max:1400',
        ]);

        $smsGateway = app(TwilioSmsGateway::class);
        if (! $smsGateway->isConfigured()) {
            return redirect()
                ->route('admin.notifications')
                ->withInput()
                ->withErrors(['sms' => 'SMS was not sent. Configure TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN, and TWILIO_FROM first.']);
        }

        [$recipients, $missingPhoneCount] = $this->resolveSmsRecipients(
            $validated['target_group'],
            isset($validated['school_class_id']) ? (int) $validated['school_class_id'] : null,
            $validated['recipient_phone'] ?? null
        );
        $recipientCount = count($recipients) + $missingPhoneCount;

        if ($recipientCount === 0) {
            throw ValidationException::withMessages([
                'target_group' => 'No recipients with valid phone numbers were found for this selection.',
            ]);
        }

        $notification = SentNotification::create([
            'channel' => 'sms',
            'target_group' => $validated['target_group'] === 'single' ? 'all' : $validated['target_group'],
            'school_class_id' => $validated['school_class_id'] ?? null,
            'recipient_phone' => $validated['recipient_phone'] ?? null,
            'recipient_count' => $recipientCount,
            'sent_count' => 0,
            'failed_count' => $recipientCount,
            'title' => $validated['title'],
            'message' => $validated['message'],
            'sent_by' => Auth::user()->name ?? 'Central Admin',
            'status' => 'failed',
        ]);

        $sentCount = 0;
        $failedCount = $missingPhoneCount;
        $smsBody = $validated['title']."\n".$validated['message'];

        foreach ($recipients as $recipient) {
            try {
                $smsGateway->send($recipient['phone'], $smsBody);
                $sentCount++;
            } catch (ConnectionException|RequestException $exception) {
                Log::warning('SMS delivery attempt failed.', [
                    'notification_id' => $notification->id,
                    'exception' => $exception::class,
                ]);
                $failedCount++;
            }
        }

        $notification->update([
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
            'status' => $sentCount > 0 ? 'sent' : 'failed',
        ]);

        ActivityLog::log(
            'created',
            'Notifications',
            "SMS alert '{$validated['title']}': {$sentCount} sent, {$failedCount} failed."
        );

        if ($sentCount === 0) {
            return redirect()
                ->route('admin.notifications')
                ->withErrors(['sms' => "SMS failed for all {$recipientCount} recipient(s). Check the configured Twilio account and phone numbers."]);
        }

        return redirect()
            ->route('admin.notifications')
            ->with('success', "SMS accepted by Twilio for {$sentCount} of {$recipientCount} recipient(s).");
    }

    /**
     * @return array{0: array<int, array{name: string, phone: string}>, 1: int}
     */
    private function resolveSmsRecipients(string $targetGroup, ?int $schoolClassId, ?string $singlePhone): array
    {
        if ($targetGroup === 'single') {
            return [[['name' => 'Individual recipient', 'phone' => (string) $singlePhone]], 0];
        }

        $recipientsByPhone = [];
        $missingPhoneCount = 0;
        $addRecipient = function (?string $name, ?string $phone) use (&$recipientsByPhone, &$missingPhoneCount): void {
            $phone = trim((string) $phone);

            if ($phone === '') {
                $missingPhoneCount++;

                return;
            }

            if (! preg_match('/^\+[1-9]\d{7,14}$/', $phone)) {
                $missingPhoneCount++;

                return;
            }

            $recipientsByPhone[$phone] = ['name' => $name ?? 'Recipient', 'phone' => $phone];
        };

        if (in_array($targetGroup, ['all', 'students', 'class_specific'], true)) {
            Student::with('user:id,name,phone')
                ->when($targetGroup === 'class_specific', fn ($query) => $query->where('school_class_id', $schoolClassId))
                ->get(['id', 'user_id', 'guardian_phone'])
                ->each(function (Student $student) use ($addRecipient): void {
                    $addRecipient($student->user?->name, $student->guardian_phone ?: $student->user?->phone);
                });
        }

        if (in_array($targetGroup, ['all', 'teachers'], true)) {
            Teacher::with('user:id,name,phone')
                ->get(['id', 'user_id'])
                ->each(function (Teacher $teacher) use ($addRecipient): void {
                    $addRecipient($teacher->user?->name, $teacher->user?->phone);
                });
        }

        return [array_values($recipientsByPhone), $missingPhoneCount];
    }

    // 20. Roles & Permissions
    public function roles(Request $request)
    {
        $admin = $this->getActiveAdmin($request);
        $roles = Role::all();
        $availableTabs = self::AVAILABLE_TABS;

        return view('admin.roles', compact('admin', 'roles', 'availableTabs'));
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:100|unique:roles,slug',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        Role::create($validated);
        ActivityLog::log('created', 'Roles', "Created institutional role: {$validated['name']}");

        return redirect()->route('admin.roles')->with('success', 'Custom role created successfully!');
    }

    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role->update($validated);
        ActivityLog::log('updated', 'Roles', "Updated permissions for role: {$role->name}");

        return redirect()->route('admin.roles')->with('success', 'Role permissions updated!');
    }

    public function destroyRole($id)
    {
        Role::findOrFail($id)->delete();
        return redirect()->route('admin.roles')->with('success', 'Role deleted.');
    }

    // 21. Reports & Analytics
    public function reports(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $studentCount = Student::count();
        $teacherCount = Teacher::count();
        $classes = SchoolClass::withCount('students')->get();

        $attendanceStats = [
            'present' => Attendance::where('status', 'present')->count(),
            'absent' => Attendance::where('status', 'absent')->count(),
            'late' => Attendance::where('status', 'late')->count(),
            'excused' => Attendance::where('status', 'excused')->count(),
            'overall_rate' => $this->calculateOverallAttendanceRate(),
        ];

        $feeStats = [
            'total_invoiced' => FeeInvoice::sum('amount'),
            'total_collected' => FeeInvoice::sum('paid_amount'),
            'pending_dues' => FeeInvoice::where('status', '!=', 'paid')->sum(DB::raw('amount - paid_amount')),
        ];

        $gradesDistribution = [
            'A+' => Grade::where('grade_letter', 'A+')->count(),
            'A' => Grade::where('grade_letter', 'A')->count(),
            'B' => Grade::where('grade_letter', 'B')->count(),
            'C' => Grade::where('grade_letter', 'C')->count(),
            'D' => Grade::where('grade_letter', 'D')->count(),
            'F' => Grade::where('grade_letter', 'F')->count(),
        ];

        return view('admin.reports', compact('admin', 'studentCount', 'teacherCount', 'classes', 'attendanceStats', 'feeStats', 'gradesDistribution'));
    }

    // 22. Backup & Activity Logs
    public function auditLogs(Request $request)
    {
        $admin = $this->getActiveAdmin($request);
        $logs = ActivityLog::latest()->paginate(25);

        return view('admin.audit-logs', compact('admin', 'logs'));
    }

    public function backupDatabase()
    {
        $dbPath = config('database.connections.sqlite.database');

        if (!file_exists($dbPath)) {
            return redirect()->back()->withErrors(['Database file not found.']);
        }

        ActivityLog::log('backed_up', 'System', "Downloaded SQLite database backup snapshot");

        $timestamp = date('Y-m-d_His');
        return response()->download($dbPath, "school_erp_backup_{$timestamp}.sqlite");
    }

    // 23. Manage Admins
    public function manageAdmins(Request $request)
    {
        $admin = $this->getActiveAdmin($request);
        $admins = User::where('role', 'admin')->get();
        $availableTabs = self::AVAILABLE_TABS;

        return view('admin.manage-admins', compact('admin', 'admins', 'availableTabs'));
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'permissions' => 'nullable|array',
        ]);

        $newAdmin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'status' => 'active',
            'permissions' => $validated['permissions'] ?? null,
        ]);

        ActivityLog::log('created', 'Security', "Created new admin profile {$newAdmin->name}");

        return redirect()->route('admin.manage-admins')->with('success', 'Admin user created successfully!');
    }

    public function updateAdminPermissions(Request $request, $id)
    {
        $adminUser = User::where('role', 'admin')->findOrFail($id);

        $validated = $request->validate([
            'permissions' => 'nullable|array',
        ]);

        $adminUser->update([
            'permissions' => $validated['permissions'] ?? null,
        ]);

        ActivityLog::log('updated', 'Security', "Updated permissions for admin {$adminUser->name}");

        return redirect()->route('admin.manage-admins')->with('success', "Permissions updated for {$adminUser->name}!");
    }

    // 24. Settings
    public function settings(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $settings = [
            'school_name' => SchoolSetting::get('school_name', 'SchoolNexus International Academy'),
            'school_motto' => SchoolSetting::get('school_motto', 'Excellence in Education, Character, and Innovation'),
            'academic_year' => SchoolSetting::get('academic_year', '2026-2027'),
            'current_term' => SchoolSetting::get('current_term', 'Term 1 (Fall Semester)'),
            'principal_name' => SchoolSetting::get('principal_name', 'Dr. Eleanor Vance, Ph.D.'),
            'affiliation_number' => SchoolSetting::get('affiliation_number', 'CBSE/AFF/2026/99412'),
            'contact_email' => SchoolSetting::get('contact_email', 'admissions@schoolnexus.edu'),
            'contact_phone' => SchoolSetting::get('contact_phone', '+1 (555) 019-2834'),
            'school_website' => SchoolSetting::get('school_website', 'https://schoolnexus.edu'),
            'address' => SchoolSetting::get('address', '742 Evergreen Campus Parkway, Suite 100, Cambridge, MA'),
            'attendance_threshold' => SchoolSetting::get('attendance_threshold', '75'),
            'currency_symbol' => SchoolSetting::get('currency_symbol', '$'),
            'grading_system' => SchoolSetting::get('grading_system', 'Letter (A+, A, B, C, D, F)'),
        ];

        return view('admin.settings', compact('admin', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_motto' => 'nullable|string|max:255',
            'academic_year' => 'required|string|max:50',
            'current_term' => 'required|string|max:50',
            'principal_name' => 'nullable|string|max:255',
            'affiliation_number' => 'nullable|string|max:100',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'school_website' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'attendance_threshold' => 'required|integer|min:50|max:100',
            'currency_symbol' => 'required|string|max:5',
            'grading_system' => 'nullable|string|max:100',
        ]);

        foreach ($validated as $key => $value) {
            SchoolSetting::set($key, $value);
        }

        ActivityLog::log('updated', 'Settings', "Updated school profile, academic year, and branding settings");

        return redirect()->route('admin.settings')->with('success', 'School configuration & institutional branding updated successfully!');
    }

    // 25. Change Password
    public function changePassword(Request $request)
    {
        $admin = $this->getActiveAdmin($request);
        return view('admin.change-password', compact('admin'));
    }

    public function updatePassword(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $admin->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password provided is incorrect.']);
        }

        $admin->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        ActivityLog::log('updated', 'Security', "Changed password for admin account {$admin->email}");

        return redirect()->route('admin.change-password')->with('success', 'Administrator password updated successfully! Keep your credentials secure.');
    }
}
