<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Timetable;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    // Active student context (Alex Morgan default or switchable)
    protected function getActiveStudent(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'student') {
            $userStudent = Student::with(['user', 'schoolClass', 'section'])
                ->where('user_id', Auth::id())
                ->first();

            if ($userStudent) {
                return $userStudent;
            }
        }

        $studentId = $request->session()->get('active_student_id', 1);
        return Student::with(['user', 'schoolClass', 'section'])
            ->find($studentId) ?? Student::first();
    }

    public function switchStudent(Request $request, $id)
    {
        $request->session()->put('active_student_id', $id);
        return redirect()->back()->with('success', 'Switched student account demo successfully.');
    }

    // 1. Dashboard
    public function index(Request $request)
    {
        $student = $this->getActiveStudent($request);

        // 1. Attendance Metrics (Strictly Present / Absent)
        $totalAttendance = Attendance::where('student_id', $student->id)->count();
        $presentCount = Attendance::where('student_id', $student->id)
            ->whereIn('status', ['present', 'late'])
            ->count();
        $absentCount = Attendance::where('student_id', $student->id)
            ->where('status', 'absent')
            ->count();
        $attendanceRate = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100, 1) : 100.0;

        // 2. Today's Classes
        $dayOfWeek = Carbon::now()->format('l');
        $todaySchedule = Timetable::with(['subject', 'teacher.user'])
            ->where('school_class_id', $student->school_class_id)
            ->where('section_id', $student->section_id)
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get();

        if ($todaySchedule->isEmpty()) {
            $todaySchedule = Timetable::with(['subject', 'teacher.user'])
                ->where('school_class_id', $student->school_class_id)
                ->where('section_id', $student->section_id)
                ->where('day_of_week', 'Monday')
                ->orderBy('start_time')
                ->get();
            $dayOfWeek = 'Monday (Upcoming)';
        }

        // 3. Homework & Deadlines
        $assignments = Assignment::with(['subject', 'teacher.user', 'submissions' => function ($q) use ($student) {
            $q->where('student_id', $student->id);
        }])
        ->where('school_class_id', $student->school_class_id)
        ->where('section_id', $student->section_id)
        ->latest()
        ->take(4)
        ->get();

        $completedHomeworkCount = AssignmentSubmission::where('student_id', $student->id)->count();

        // 4. School Announcements
        $announcements = Announcement::whereIn('audience', ['all', 'students'])
            ->latest()
            ->take(4)
            ->get();
        $announcementsCount = Announcement::whereIn('audience', ['all', 'students'])->count();

        return view('student.dashboard', compact(
            'student',
            'attendanceRate',
            'totalAttendance',
            'presentCount',
            'absentCount',
            'todaySchedule',
            'dayOfWeek',
            'assignments',
            'completedHomeworkCount',
            'announcements',
            'announcementsCount'
        ));
    }

    // 2. Timetable
    public function timetable(Request $request)
    {
        $student = $this->getActiveStudent($request);
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $schedule = Timetable::with(['subject', 'teacher.user'])
            ->where('school_class_id', $student->school_class_id)
            ->where('section_id', $student->section_id)
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        return view('student.timetable', compact('student', 'days', 'schedule'));
    }

    // 3. Attendance (shows present or absent)
    public function attendance(Request $request)
    {
        $student = $this->getActiveStudent($request);
        $attendances = Attendance::where('student_id', $student->id)
            ->orderByDesc('attendance_date')
            ->get();

        $total = $attendances->count();
        $present = $attendances->whereIn('status', ['present', 'late'])->count();
        $absent = $attendances->whereIn('status', ['absent', 'excused'])->count();

        $rate = $total > 0 ? round(($present / $total) * 100, 1) : 100;

        return view('student.attendance', compact('student', 'attendances', 'total', 'present', 'absent', 'rate'));
    }

    // 4. Homework (view, upload answers, see result)
    public function homework(Request $request)
    {
        $student = $this->getActiveStudent($request);
        
        $assignments = Assignment::with(['subject', 'teacher.user', 'submissions' => function ($q) use ($student) {
            $q->where('student_id', $student->id);
        }])
        ->where('school_class_id', $student->school_class_id)
        ->where('section_id', $student->section_id)
        ->latest()
        ->get();

        return view('student.homework', compact('student', 'assignments'));
    }

    public function submitHomework(Request $request, $id)
    {
        $student = $this->getActiveStudent($request);

        $validated = $request->validate([
            'submission_text' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        if (empty($validated['submission_text']) && !$request->hasFile('attachment')) {
            return redirect()->back()->withErrors(['submission_text' => 'Please provide an answer explanation or attach your homework document.']);
        }

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = 'sub_' . time() . '_' . $file->getClientOriginalName();
            $file->storeAs('submissions', $filename, 'public');
            $filePath = 'submissions/' . $filename;
        }

        $submission = AssignmentSubmission::where('assignment_id', $id)
            ->where('student_id', $student->id)
            ->first();

        if ($submission) {
            $submission->update([
                'submission_text' => $validated['submission_text'] ?? $submission->submission_text,
                'file_attachment' => $filePath ?? $submission->file_attachment,
                'submitted_at' => now(),
                'status' => 'submitted',
            ]);
        } else {
            AssignmentSubmission::create([
                'assignment_id' => $id,
                'student_id' => $student->id,
                'submission_text' => $validated['submission_text'] ?? 'Completed homework assignment attached.',
                'file_attachment' => $filePath,
                'submitted_at' => now(),
                'status' => 'submitted',
            ]);
        }

        return redirect()->route('student.homework')->with('success', 'Your homework answer has been successfully uploaded and submitted for review!');
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

        return redirect()->back()->with('error', 'Requested document file is not available on storage disk.');
    }

    // 5. Announcements
    public function announcements(Request $request)
    {
        $student = $this->getActiveStudent($request);
        $announcements = Announcement::whereIn('audience', ['all', 'students'])
            ->latest()
            ->paginate(10);

        return view('student.announcements', compact('student', 'announcements'));
    }

    // 6. Profile
    public function profile(Request $request)
    {
        $student = $this->getActiveStudent($request);
        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $student = $this->getActiveStudent($request);

        $validated = $request->validate([
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        $student->update($validated);

        return redirect()->route('student.profile')->with('success', 'Profile contact details updated successfully.');
    }

    // 7. Change Password
    public function changePassword(Request $request)
    {
        $student = $this->getActiveStudent($request);
        return view('student.change-password', compact('student'));
    }

    public function updatePassword(Request $request)
    {
        $student = $this->getActiveStudent($request);
        $user = $student->user;

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password entered is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return redirect()->route('student.change-password')->with('success', 'Password updated successfully! Keep your new password secure.');
    }
}
