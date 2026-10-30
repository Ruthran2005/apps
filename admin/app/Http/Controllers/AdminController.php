<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Attendance;
use App\Models\FeeInvoice;
use App\Models\SchoolClass;
use App\Models\SchoolSetting;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Timetable;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public const AVAILABLE_TABS = [
        'dashboard' => 'Dashboard',
        'students' => 'Students',
        'teachers' => 'Teachers',
        'classes' => 'Classes',
        'timetable' => 'Timetable',
        'attendance' => 'Attendance',
        'announcements' => 'Announcements',
        'homework' => 'Homework (View Only)',
        'manage-admins' => 'Manage Admins',
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
        return User::where('role', 'admin')->first() ?? User::first();
    }

    public function switchAdmin(Request $request, $id)
    {
        $request->session()->put('active_admin_id', $id);
        return redirect()->back()->with('success', 'Active admin account switched successfully.');
    }

    // Check if the current admin has permission for a specific tab
    protected function checkTabAccess($admin, string $tabKey): bool
    {
        // If no custom permissions set, user is treated as Super Admin with full access
        if (empty($admin->permissions)) {
            return true;
        }
        return in_array($tabKey, $admin->permissions);
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

        return view('admin.dashboard', compact('admin', 'stats', 'recentStudents', 'teachers', 'announcements'));
    }

    protected function calculateOverallAttendanceRate(): float
    {
        $total = Attendance::count();
        if ($total === 0) return 100.0;
        $present = Attendance::whereIn('status', ['present', 'late'])->count();
        return round(($present / $total) * 100, 1);
    }

    // 2. Students
    public function students(Request $request)
    {
        $admin = $this->getActiveAdmin($request);
        $query = Student::with(['user', 'schoolClass', 'section']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('admission_number', 'like', "%{$search}%");
        }

        if ($request->filled('class_id')) {
            $query->where('school_class_id', $request->class_id);
        }

        $students = $query->paginate(10)->withQueryString();
        $classes = SchoolClass::with('sections')->get();

        return view('admin.students', compact('admin', 'students', 'classes'));
    }

    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'gender' => 'required|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'phone' => $validated['phone'],
            'status' => 'active',
        ]);

        $nextNum = Student::count() + 105;
        $admissionNumber = 'STU-2026-' . $nextNum;

        Student::create([
            'user_id' => $user->id,
            'admission_number' => $admissionNumber,
            'roll_number' => (string)$nextNum,
            'school_class_id' => $validated['school_class_id'],
            'section_id' => $validated['section_id'],
            'gender' => $validated['gender'],
            'guardian_name' => $validated['guardian_name'],
            'guardian_phone' => $validated['guardian_phone'],
            'address' => $validated['address'],
        ]);

        return redirect()->route('admin.students')->with('success', "Student {$user->name} successfully enrolled (ID: {$admissionNumber})!");
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
            })->orWhere('employee_id', 'like', "%{$search}%");
        }

        $teachers = $query->paginate(10)->withQueryString();
        $subjects = Subject::all();

        return view('admin.teachers', compact('admin', 'teachers', 'subjects'));
    }

    public function storeTeacher(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'qualification' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'salary' => 'nullable|numeric',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'teacher',
            'phone' => $validated['phone'],
            'status' => 'active',
        ]);

        $employeeId = 'TCH-2026-0' . (Teacher::count() + 4);

        Teacher::create([
            'user_id' => $user->id,
            'employee_id' => $employeeId,
            'qualification' => $validated['qualification'],
            'specialization' => $validated['specialization'],
            'joining_date' => now()->toDateString(),
            'salary' => $validated['salary'] ?? 5500.00,
        ]);

        return redirect()->route('admin.teachers')->with('success', "Teacher {$user->name} added successfully (ID: {$employeeId})!");
    }

    // 4. Classes
    public function classes(Request $request)
    {
        $admin = $this->getActiveAdmin($request);
        $classes = SchoolClass::with(['sections.students', 'timetables'])->get();
        $subjects = Subject::with('teacherSubjectAssignments.teacher.user')->get();

        return view('admin.classes', compact('admin', 'classes', 'subjects'));
    }

    public function storeClass(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'numeric_grade' => 'required|integer',
        ]);

        $class = SchoolClass::create($validated);
        // Create a default Section A
        Section::create([
            'school_class_id' => $class->id,
            'name' => 'A',
            'capacity' => 35,
        ]);

        return redirect()->route('admin.classes')->with('success', "Class '{$class->name}' created successfully with Section A!");
    }

    public function storeSection(Request $request)
    {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'name' => 'required|string|max:50',
            'capacity' => 'required|integer|min:10',
        ]);

        Section::create($validated);

        return redirect()->route('admin.classes')->with('success', "Section {$validated['name']} added successfully!");
    }

    // 5. Timetable
    public function timetable(Request $request)
    {
        $admin = $this->getActiveAdmin($request);
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        $classes = SchoolClass::with('sections')->get();
        $selectedClassId = $request->get('class_id', $classes->first()->id ?? null);

        $query = Timetable::with(['subject', 'teacher.user', 'schoolClass', 'section']);
        if ($selectedClassId) {
            $query->where('school_class_id', $selectedClassId);
        }

        $schedule = $query->orderBy('start_time')->get()->groupBy('day_of_week');

        $subjects = Subject::all();
        $teachers = Teacher::with('user')->get();

        return view('admin.timetable', compact('admin', 'days', 'classes', 'selectedClassId', 'schedule', 'subjects', 'teachers'));
    }

    public function storeTimetable(Request $request)
    {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday',
            'start_time' => 'required',
            'end_time' => 'required',
            'room_number' => 'nullable|string|max:50',
        ]);

        Timetable::create($validated);

        return redirect()->back()->with('success', 'Timetable lecture period scheduled successfully!');
    }

    // 6. Attendance (with export by class and date range)
    public function attendance(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $classes = SchoolClass::with('sections')->get();
        $selectedClassId = $request->get('class_id', '');
        $fromDate = $request->get('from_date', Carbon::now()->subDays(14)->toDateString());
        $toDate = $request->get('to_date', Carbon::now()->toDateString());

        $query = Attendance::with(['student.user', 'student.schoolClass', 'student.section', 'teacher.user'])
            ->whereBetween('attendance_date', [$fromDate, $toDate])
            ->orderByDesc('attendance_date');

        if (!empty($selectedClassId)) {
            $query->where('school_class_id', $selectedClassId);
        }

        $attendances = $query->paginate(15)->withQueryString();

        // Calculate stats for the selected filter
        $statQuery = Attendance::whereBetween('attendance_date', [$fromDate, $toDate]);
        if (!empty($selectedClassId)) {
            $statQuery->where('school_class_id', $selectedClassId);
        }
        $totalLogs = $statQuery->count();
        $presentLogs = (clone $statQuery)->whereIn('status', ['present', 'late'])->count();
        $absentLogs = (clone $statQuery)->where('status', 'absent')->count();
        $attendanceRate = $totalLogs > 0 ? round(($presentLogs / $totalLogs) * 100, 1) : 100.0;

        return view('admin.attendance', compact(
            'admin',
            'classes',
            'selectedClassId',
            'fromDate',
            'toDate',
            'attendances',
            'totalLogs',
            'presentLogs',
            'absentLogs',
            'attendanceRate'
        ));
    }

    // Export Attendance CSV by Class and Date Range
    public function exportAttendance(Request $request): StreamedResponse
    {
        $selectedClassId = $request->get('class_id', '');
        $fromDate = $request->get('from_date', Carbon::now()->subDays(30)->toDateString());
        $toDate = $request->get('to_date', Carbon::now()->toDateString());

        $query = Attendance::with(['student.user', 'student.schoolClass', 'student.section', 'teacher.user'])
            ->whereBetween('attendance_date', [$fromDate, $toDate])
            ->orderBy('attendance_date');

        $className = 'All_Classes';
        if (!empty($selectedClassId)) {
            $query->where('school_class_id', $selectedClassId);
            $cls = SchoolClass::find($selectedClassId);
            if ($cls) {
                $className = str_replace(' ', '_', $cls->name);
            }
        }

        $records = $query->get();
        $filename = "attendance_{$className}_{$fromDate}_to_{$toDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($records) {
            $handle = fopen('php://output', 'w');
            // CSV Header Row
            fputcsv($handle, [
                'Date',
                'Day',
                'Student Name',
                'Admission Number',
                'Roll Number',
                'Class',
                'Section',
                'Status',
                'Marked By Teacher',
                'Remarks'
            ]);

            foreach ($records as $row) {
                fputcsv($handle, [
                    $row->attendance_date ? $row->attendance_date->toDateString() : '',
                    $row->attendance_date ? $row->attendance_date->format('l') : '',
                    $row->student->user->name ?? 'N/A',
                    $row->student->admission_number ?? 'N/A',
                    $row->student->roll_number ?? 'N/A',
                    $row->student->schoolClass->name ?? 'N/A',
                    $row->student->section->name ?? 'N/A',
                    strtoupper($row->status ?? 'PRESENT'),
                    $row->teacher->user->name ?? 'Faculty',
                    $row->remarks ?? '',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
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
            'author_name' => 'School Administration',
            'published_at' => now()->toDateString(),
        ]);

        return redirect()->route('admin.announcements')->with('success', 'Announcement broadcasted successfully!');
    }

    // 8. Homework (view only)
    public function homework(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $classes = SchoolClass::with('sections')->get();
        $selectedClassId = $request->get('class_id', '');

        $query = Assignment::with([
            'subject',
            'teacher.user',
            'schoolClass',
            'section',
            'submissions.student.user'
        ])->latest();

        if (!empty($selectedClassId)) {
            $query->where('school_class_id', $selectedClassId);
        }

        $assignments = $query->paginate(8)->withQueryString();

        return view('admin.homework', compact('admin', 'assignments', 'classes', 'selectedClassId'));
    }

    public function downloadHomeworkFile(Request $request, $type, $id)
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

        return redirect()->back()->with('error', 'Requested attachment file not found on disk.');
    }

    // 9. Manage Admins (create an admin and set per-tab access)
    public function manageAdmins(Request $request)
    {
        $admin = $this->getActiveAdmin($request);
        $admins = User::where('role', 'admin')->latest()->get();
        $availableTabs = self::AVAILABLE_TABS;

        return view('admin.manage-admins', compact('admin', 'admins', 'availableTabs'));
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:50',
            'permissions' => 'nullable|array',
        ]);

        $permissions = $request->input('permissions', []);
        // If all selected or none restricted, store full array or null
        if (count($permissions) === count(self::AVAILABLE_TABS)) {
            $permissions = null; // Full access
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'phone' => $validated['phone'],
            'status' => 'active',
            'permissions' => $permissions,
        ]);

        return redirect()->route('admin.manage-admins')->with('success', "New Administrator {$validated['name']} created with customized per-tab access!");
    }

    public function updateAdminPermissions(Request $request, $id)
    {
        $adminUser = User::where('role', 'admin')->findOrFail($id);
        $permissions = $request->input('permissions', []);

        if (count($permissions) === count(self::AVAILABLE_TABS)) {
            $permissions = null; // Full access
        }

        $adminUser->update([
            'permissions' => $permissions,
        ]);

        return redirect()->route('admin.manage-admins')->with('success', "Per-tab permissions updated successfully for {$adminUser->name}!");
    }

    // 10. Settings
    public function settings(Request $request)
    {
        $admin = $this->getActiveAdmin($request);

        $settings = [
            'school_name' => SchoolSetting::get('school_name', 'SchoolNexus International Academy'),
            'school_tagline' => SchoolSetting::get('school_tagline', 'Excellence in Academic Leadership & Modern Education'),
            'academic_year' => SchoolSetting::get('academic_year', '2026-2027'),
            'current_term' => SchoolSetting::get('current_term', 'Term 1 (Fall Semester)'),
            'contact_email' => SchoolSetting::get('contact_email', 'admissions@schoolnexus.edu'),
            'contact_phone' => SchoolSetting::get('contact_phone', '+1 (555) 019-2834'),
            'address' => SchoolSetting::get('address', '742 Evergreen Campus Parkway, Suite 100, Cambridge, MA'),
            'attendance_threshold' => SchoolSetting::get('attendance_threshold', '75'),
            'currency_symbol' => SchoolSetting::get('currency_symbol', '$'),
        ];

        return view('admin.settings', compact('admin', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_tagline' => 'nullable|string|max:255',
            'academic_year' => 'required|string|max:50',
            'current_term' => 'required|string|max:50',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'attendance_threshold' => 'required|integer|min:50|max:100',
            'currency_symbol' => 'required|string|max:5',
        ]);

        foreach ($validated as $key => $value) {
            SchoolSetting::set($key, $value);
        }

        return redirect()->route('admin.settings')->with('success', 'School configuration & institution branding updated successfully!');
    }

    // 11. Change Password
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

        return redirect()->route('admin.change-password')->with('success', 'Administrator password updated successfully! Keep your credentials secure.');
    }
}
