<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/students', [AdminController::class, 'students'])->name('admin.students');
Route::post('/students', [AdminController::class, 'storeStudent'])->name('admin.students.store');

Route::get('/teachers', [AdminController::class, 'teachers'])->name('admin.teachers');
Route::post('/teachers', [AdminController::class, 'storeTeacher'])->name('admin.teachers.store');

Route::get('/classes', [AdminController::class, 'classes'])->name('admin.classes');
Route::get('/finance', [AdminController::class, 'finance'])->name('admin.finance');
Route::post('/finance/{id}/pay', [AdminController::class, 'markInvoicePaid'])->name('admin.finance.pay');

Route::get('/announcements', [AdminController::class, 'announcements'])->name('admin.announcements');
Route::post('/announcements', [AdminController::class, 'storeAnnouncement'])->name('admin.announcements.store');
