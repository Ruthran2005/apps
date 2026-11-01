<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// Public Authentication Routes
Route::get('/login', [AuthController::class, 'showTeacherLogin'])->name('login');
Route::post('/login', [AuthController::class, 'teacherLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Teacher Routes
Route::middleware('auth')->group(function () {
    // 1. Dashboard & Profile Switcher
    Route::get('/', [TeacherController::class, 'index'])->name('teacher.dashboard');
    Route::get('/switch-teacher/{id}', [TeacherController::class, 'switchTeacher'])->name('teacher.switch');

    // 2. My Classes
    Route::get('/my-classes', [TeacherController::class, 'classes'])->name('teacher.classes');

    // 3. Timetable
    Route::get('/timetable', [TeacherController::class, 'timetable'])->name('teacher.timetable');

    // 4. Attendance
    Route::get('/attendance', [TeacherController::class, 'attendance'])->name('teacher.attendance');
    Route::post('/attendance', [TeacherController::class, 'storeAttendance'])->name('teacher.attendance.store');

    // 5. Homework (with specific student assignment support)
    Route::get('/homework', [TeacherController::class, 'homework'])->name('teacher.homework');
    Route::post('/homework', [TeacherController::class, 'uploadHomework'])->name('teacher.homework.store');
    Route::post('/homework/upload', [TeacherController::class, 'uploadHomework'])->name('teacher.homework.upload');
    Route::get('/homework/download/{type}/{id}', [TeacherController::class, 'downloadFile'])->name('teacher.homework.download');
    Route::post('/homework/{id}/auto-grade', [TeacherController::class, 'autoGrade'])->name('teacher.homework.auto-grade');
    Route::post('/homework/grade/{id}', [TeacherController::class, 'gradeSubmission'])->name('teacher.submissions.grade');
    Route::delete('/homework/{id}', [TeacherController::class, 'destroyHomework'])->name('teacher.homework.destroy');

    // 6. Quizzes & Tests
    Route::get('/quizzes', [TeacherController::class, 'quizzes'])->name('teacher.quizzes');
    Route::post('/quizzes', [TeacherController::class, 'storeQuiz'])->name('teacher.quizzes.store');
    Route::post('/quizzes/{id}/status', [TeacherController::class, 'updateQuizStatus'])->name('teacher.quizzes.status');
    Route::delete('/quizzes/{id}', [TeacherController::class, 'destroyQuiz'])->name('teacher.quizzes.destroy');

    // 7. Study Materials (PDF, video, notes)
    Route::get('/study-materials', [TeacherController::class, 'studyMaterials'])->name('teacher.study-materials');
    Route::post('/study-materials', [TeacherController::class, 'storeStudyMaterial'])->name('teacher.study-materials.store');
    Route::get('/study-materials/download/{id}', [TeacherController::class, 'downloadStudyMaterial'])->name('teacher.study-materials.download');
    Route::delete('/study-materials/{id}', [TeacherController::class, 'destroyStudyMaterial'])->name('teacher.study-materials.destroy');

    // 8. Class Diary & Daily Notes
    Route::get('/class-diary', [TeacherController::class, 'classDiary'])->name('teacher.class-diary');
    Route::post('/class-diary', [TeacherController::class, 'storeClassDiary'])->name('teacher.class-diary.store');
    Route::delete('/class-diary/{id}', [TeacherController::class, 'destroyClassDiary'])->name('teacher.class-diary.destroy');

    // 9. Exam Marks & Remarks
    Route::get('/grades', [TeacherController::class, 'grades'])->name('teacher.grades');
    Route::post('/grades', [TeacherController::class, 'storeGrades'])->name('teacher.grades.store');

    // 10. Student Performance Analytics
    Route::get('/analytics', [TeacherController::class, 'analytics'])->name('teacher.analytics');

    // 11. Parent Communication & Messages
    Route::get('/communications', [TeacherController::class, 'communications'])->name('teacher.communications');
    Route::post('/communications', [TeacherController::class, 'storeCommunication'])->name('teacher.communications.store');
    Route::post('/communications/{id}/read', [TeacherController::class, 'markCommunicationRead'])->name('teacher.communications.read');

    // 12. Apply for Leave & View Status
    Route::get('/leaves', [TeacherController::class, 'leaves'])->name('teacher.leaves');
    Route::post('/leaves', [TeacherController::class, 'storeLeave'])->name('teacher.leaves.store');

    // 13. Substitute & Duty Roster
    Route::get('/duty-roster', [TeacherController::class, 'dutyRoster'])->name('teacher.duty-roster');
    Route::post('/duty-roster/{id}/swap', [TeacherController::class, 'swapDuty'])->name('teacher.duty-roster.swap');

    // 14. Exam Duty & Invigilation Schedule
    Route::get('/invigilation', [TeacherController::class, 'invigilation'])->name('teacher.invigilation');

    // 15. Salary Slips & Print
    Route::get('/salary-slips', [TeacherController::class, 'salarySlips'])->name('teacher.salary-slips');
    Route::get('/salary-slips/print/{id}', [TeacherController::class, 'printPayslip'])->name('teacher.salary-slips.print');

    // 16. Events & Holiday Calendar
    Route::get('/calendar', [TeacherController::class, 'calendar'])->name('teacher.calendar');

    // 17. Announcements
    Route::get('/announcements', [TeacherController::class, 'announcements'])->name('teacher.announcements');
    Route::post('/announcements', [TeacherController::class, 'storeAnnouncement'])->name('teacher.announcements.store');

    // 18. Profile
    Route::get('/profile', [TeacherController::class, 'profile'])->name('teacher.profile');
    Route::post('/profile', [TeacherController::class, 'updateProfile'])->name('teacher.profile.update');

    // 19. Change Password
    Route::get('/change-password', [TeacherController::class, 'changePassword'])->name('teacher.change-password');
    Route::post('/change-password', [TeacherController::class, 'updatePassword'])->name('teacher.change-password.update');
});
