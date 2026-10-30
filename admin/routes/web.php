<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// 1. Dashboard
Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/switch-admin/{id}', [AdminController::class, 'switchAdmin'])->name('admin.switch');

// 2. Students
Route::get('/students', [AdminController::class, 'students'])->name('admin.students');
Route::post('/students', [AdminController::class, 'storeStudent'])->name('admin.students.store');

// 3. Teachers
Route::get('/teachers', [AdminController::class, 'teachers'])->name('admin.teachers');
Route::post('/teachers', [AdminController::class, 'storeTeacher'])->name('admin.teachers.store');

// 4. Classes
Route::get('/classes', [AdminController::class, 'classes'])->name('admin.classes');
Route::post('/classes', [AdminController::class, 'storeClass'])->name('admin.classes.store');
Route::post('/classes/sections', [AdminController::class, 'storeSection'])->name('admin.sections.store');

// 5. Timetable
Route::get('/timetable', [AdminController::class, 'timetable'])->name('admin.timetable');
Route::post('/timetable', [AdminController::class, 'storeTimetable'])->name('admin.timetable.store');

// 6. Attendance (with export by class and date range)
Route::get('/attendance', [AdminController::class, 'attendance'])->name('admin.attendance');
Route::get('/attendance/export', [AdminController::class, 'exportAttendance'])->name('admin.attendance.export');

// 7. Announcements
Route::get('/announcements', [AdminController::class, 'announcements'])->name('admin.announcements');
Route::post('/announcements', [AdminController::class, 'storeAnnouncement'])->name('admin.announcements.store');

// 8. Homework (view only)
Route::get('/homework', [AdminController::class, 'homework'])->name('admin.homework');
Route::get('/homework/download/{type}/{id}', [AdminController::class, 'downloadHomeworkFile'])->name('admin.homework.download');

// 9. Manage Admins (create an admin and set per-tab access)
Route::get('/manage-admins', [AdminController::class, 'manageAdmins'])->name('admin.manage-admins');
Route::post('/manage-admins', [AdminController::class, 'storeAdmin'])->name('admin.manage-admins.store');
Route::post('/manage-admins/{id}/permissions', [AdminController::class, 'updateAdminPermissions'])->name('admin.manage-admins.permissions');

// 10. Settings
Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');

// 11. Change Password
Route::get('/change-password', [AdminController::class, 'changePassword'])->name('admin.change-password');
Route::post('/change-password', [AdminController::class, 'updatePassword'])->name('admin.change-password.update');
