<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Public Authentication Routes
Route::get('/login', [AuthController::class, 'showStudentLogin'])->name('login');
Route::post('/login', [AuthController::class, 'studentLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Student Routes (All 7 Tabs)
Route::middleware('auth')->group(function () {
    // 1. Dashboard
    Route::get('/', [StudentController::class, 'index'])->name('student.dashboard');
    Route::get('/switch-student/{id}', [StudentController::class, 'switchStudent'])->name('student.switch');

    // 2. Timetable
    Route::get('/timetable', [StudentController::class, 'timetable'])->name('student.timetable');

    // 3. Attendance (shows present or absent)
    Route::get('/attendance', [StudentController::class, 'attendance'])->name('student.attendance');

    // 4. Homework (view, upload answers, see result)
    Route::get('/homework', [StudentController::class, 'homework'])->name('student.homework');
    Route::post('/homework/{id}/submit', [StudentController::class, 'submitHomework'])->name('student.homework.submit');
    Route::get('/homework/download/{type}/{id}', [StudentController::class, 'downloadFile'])->name('student.homework.download');

    // 5. Announcements
    Route::get('/announcements', [StudentController::class, 'announcements'])->name('student.announcements');

    // 6. Profile
    Route::get('/profile', [StudentController::class, 'profile'])->name('student.profile');
    Route::post('/profile', [StudentController::class, 'updateProfile'])->name('student.profile.update');

    // 7. Change Password
    Route::get('/change-password', [StudentController::class, 'changePassword'])->name('student.change-password');
    Route::post('/change-password', [StudentController::class, 'updatePassword'])->name('student.change-password.update');
});
