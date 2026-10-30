<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\FeeInvoice;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_classes' => SchoolClass::count(),
            'total_subjects' => Subject::count(),
            'fees_collected' => FeeInvoice::sum('paid_amount'),
            'fees_pending' => FeeInvoice::where('status', '!=', 'paid')->sum('amount') - FeeInvoice::where('status', '!=', 'paid')->sum('paid_amount'),
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
            ->take(4)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentStudents', 'teachers', 'announcements'));
    }

    public function students(Request $request)
    {
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

        return view('admin.students', compact('students', 'classes'));
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

    public function teachers(Request $request)
    {
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

        return view('admin.teachers', compact('teachers', 'subjects'));
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

    public function classes()
    {
        $classes = SchoolClass::with(['sections.students', 'timetables'])->get();
        $subjects = Subject::with('teacherSubjectAssignments.teacher.user')->get();

        return view('admin.classes', compact('classes', 'subjects'));
    }

    public function finance()
    {
        $invoices = FeeInvoice::with(['student.user', 'student.schoolClass'])->latest()->paginate(10);
        $totalBilled = FeeInvoice::sum('amount');
        $totalPaid = FeeInvoice::sum('paid_amount');
        $totalPending = $totalBilled - $totalPaid;

        return view('admin.finance', compact('invoices', 'totalBilled', 'totalPaid', 'totalPending'));
    }

    public function markInvoicePaid($id)
    {
        $invoice = FeeInvoice::findOrFail($id);
        $invoice->update([
            'paid_amount' => $invoice->amount,
            'status' => 'paid',
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Office Cash/Card',
        ]);

        return redirect()->back()->with('success', "Invoice #{$invoice->invoice_number} marked as paid in full.");
    }

    public function announcements()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('admin.announcements', compact('announcements'));
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

        return redirect()->route('admin.announcements')->with('success', 'Announcement published across portals!');
    }
}
