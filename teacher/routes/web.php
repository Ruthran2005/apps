<?php

use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// 1. Dashboard
Route::get('/', [TeacherController::class, 'index'])->name('teacher.dashboard');
Route::get('/switch-teacher/{id}', [TeacherController::class, 'switchTeacher'])->name('teacher.switch');

// 2. My Classes
Route::get('/my-classes', [TeacherController::class, 'classes'])->name('teacher.classes');

// 3. Timetable
Route::get('/timetable', [TeacherController::class, 'timetable'])->name('teacher.timetable');

// 4. Attendance (mark present or absent)
Route::get('/attendance', [TeacherController::class, 'attendance'])->name('teacher.attendance');
Route::post('/attendance', [TeacherController::class, 'storeAttendance'])->name('teacher.attendance.store');

// 5. Homework (upload, view submissions, download files, auto-grade)
Route::get('/homework', [TeacherController::class, 'homework'])->name('teacher.homework');
Route::post('/homework/upload', [TeacherController::class, 'uploadHomework'])->name('teacher.homework.upload');
Route::get('/homework/download/{type}/{id}', [TeacherController::class, 'downloadFile'])->name('teacher.homework.download');
Route::post('/homework/{id}/auto-grade', [TeacherController::class, 'autoGrade'])->name('teacher.homework.auto-grade');
Route::post('/homework/grade/{id}', [TeacherController::class, 'gradeSubmission'])->name('teacher.submissions.grade');

// 6. Announcements
Route::get('/announcements', [TeacherController::class, 'announcements'])->name('teacher.announcements');
Route::post('/announcements', [TeacherController::class, 'storeAnnouncement'])->name('teacher.announcements.store');

// 7. Change Password
Route::get('/change-password', [TeacherController::class, 'changePassword'])->name('teacher.change-password');
Route::post('/change-password', [TeacherController::class, 'updatePassword'])->name('teacher.change-password.update');
