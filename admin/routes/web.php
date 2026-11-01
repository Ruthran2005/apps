<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Public Authentication Routes
Route::get('/login', [AuthController::class, 'showAdminLogin'])->name('login');
Route::post('/login', [AuthController::class, 'adminLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Admin Routes
Route::middleware('auth')->group(function () {
    // 1. Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/switch-admin/{id}', [AdminController::class, 'switchAdmin'])->name('admin.switch');

    // 2. Students
    Route::get('/students', [AdminController::class, 'students'])->name('admin.students');
    Route::post('/students', [AdminController::class, 'storeStudent'])->name('admin.students.store');
    Route::put('/students/{id}', [AdminController::class, 'updateStudent'])->name('admin.students.update');
    Route::delete('/students/{id}', [AdminController::class, 'destroyStudent'])->name('admin.students.destroy');

    // 3. Teachers
    Route::get('/teachers', [AdminController::class, 'teachers'])->name('admin.teachers');
    Route::post('/teachers', [AdminController::class, 'storeTeacher'])->name('admin.teachers.store');
    Route::put('/teachers/{id}', [AdminController::class, 'updateTeacher'])->name('admin.teachers.update');
    Route::delete('/teachers/{id}', [AdminController::class, 'destroyTeacher'])->name('admin.teachers.destroy');

    // 4. Classes
    Route::get('/classes', [AdminController::class, 'classes'])->name('admin.classes');
    Route::post('/classes', [AdminController::class, 'storeClass'])->name('admin.classes.store');
    Route::post('/classes/sections', [AdminController::class, 'storeSection'])->name('admin.sections.store');
    Route::delete('/classes/{id}', [AdminController::class, 'destroyClass'])->name('admin.classes.destroy');

    // 5. Timetable
    Route::get('/timetable', [AdminController::class, 'timetable'])->name('admin.timetable');
    Route::post('/timetable', [AdminController::class, 'storeTimetable'])->name('admin.timetable.store');
    Route::delete('/timetable/{id}', [AdminController::class, 'destroyTimetable'])->name('admin.timetable.destroy');

    // 6. Attendance (with export by class and date range)
    Route::get('/attendance', [AdminController::class, 'attendance'])->name('admin.attendance');
    Route::get('/attendance/export', [AdminController::class, 'exportAttendance'])->name('admin.attendance.export');

    // 7. Announcements
    Route::get('/announcements', [AdminController::class, 'announcements'])->name('admin.announcements');
    Route::post('/announcements', [AdminController::class, 'storeAnnouncement'])->name('admin.announcements.store');
    Route::put('/announcements/{id}', [AdminController::class, 'updateAnnouncement'])->name('admin.announcements.update');
    Route::delete('/announcements/{id}', [AdminController::class, 'destroyAnnouncement'])->name('admin.announcements.destroy');

    // 8. Homework (view only)
    Route::get('/homework', [AdminController::class, 'homework'])->name('admin.homework');
    Route::get('/homework/download/{type}/{id}', [AdminController::class, 'downloadHomeworkFile'])->name('admin.homework.download');

    // 9. Fee Management
    Route::get('/fees', [AdminController::class, 'fees'])->name('admin.fees');
    Route::post('/fees/structure', [AdminController::class, 'storeFeeStructure'])->name('admin.fees.structure.store');
    Route::delete('/fees/structure/{id}', [AdminController::class, 'destroyFeeStructure'])->name('admin.fees.structure.destroy');
    Route::post('/fees/payment', [AdminController::class, 'storeFeePayment'])->name('admin.fees.payment.store');
    Route::get('/fees/receipt/{id}', [AdminController::class, 'printReceipt'])->name('admin.fees.receipt');

    // 10. Exams & Marks
    Route::get('/exams', [AdminController::class, 'exams'])->name('admin.exams');
    Route::post('/exams', [AdminController::class, 'storeExam'])->name('admin.exams.store');
    Route::delete('/exams/{id}', [AdminController::class, 'destroyExam'])->name('admin.exams.destroy');
    Route::post('/exams/schedule', [AdminController::class, 'storeExamSchedule'])->name('admin.exams.schedule.store');
    Route::delete('/exams/schedule/{id}', [AdminController::class, 'destroyExamSchedule'])->name('admin.exams.schedule.destroy');
    Route::post('/exams/marks', [AdminController::class, 'storeMarks'])->name('admin.exams.marks.store');
    Route::get('/exams/report-card/{student_id}', [AdminController::class, 'reportCard'])->name('admin.exams.report-card');

    // 11. Subjects & Syllabus
    Route::get('/subjects', [AdminController::class, 'subjects'])->name('admin.subjects');
    Route::post('/subjects', [AdminController::class, 'storeSubject'])->name('admin.subjects.store');
    Route::delete('/subjects/{id}', [AdminController::class, 'destroySubject'])->name('admin.subjects.destroy');
    Route::post('/subjects/syllabus', [AdminController::class, 'storeSyllabus'])->name('admin.subjects.syllabus.store');
    Route::delete('/subjects/syllabus/{id}', [AdminController::class, 'destroySyllabus'])->name('admin.subjects.syllabus.destroy');

    // 12. Leave Management
    Route::get('/leaves', [AdminController::class, 'leaves'])->name('admin.leaves');
    Route::post('/leaves/{id}/status', [AdminController::class, 'updateLeaveStatus'])->name('admin.leaves.status');

    // 13. Library Management
    Route::get('/library', [AdminController::class, 'library'])->name('admin.library');
    Route::post('/library/books', [AdminController::class, 'storeBook'])->name('admin.library.books.store');
    Route::delete('/library/books/{id}', [AdminController::class, 'destroyBook'])->name('admin.library.books.destroy');
    Route::post('/library/issue', [AdminController::class, 'issueBook'])->name('admin.library.issue');
    Route::post('/library/return/{id}', [AdminController::class, 'returnBook'])->name('admin.library.return');

    // 14. Transport
    Route::get('/transport', [AdminController::class, 'transport'])->name('admin.transport');
    Route::post('/transport/vehicles', [AdminController::class, 'storeVehicle'])->name('admin.transport.vehicles.store');
    Route::delete('/transport/vehicles/{id}', [AdminController::class, 'destroyVehicle'])->name('admin.transport.vehicles.destroy');
    Route::post('/transport/routes', [AdminController::class, 'storeTransportRoute'])->name('admin.transport.routes.store');
    Route::delete('/transport/routes/{id}', [AdminController::class, 'destroyTransportRoute'])->name('admin.transport.routes.destroy');
    Route::post('/transport/allocations', [AdminController::class, 'allocateTransport'])->name('admin.transport.allocations.store');
    Route::delete('/transport/allocations/{id}', [AdminController::class, 'destroyTransportAllocation'])->name('admin.transport.allocations.destroy');

    // 15. Events & Holiday Calendar
    Route::get('/events', [AdminController::class, 'events'])->name('admin.events');
    Route::post('/events', [AdminController::class, 'storeEvent'])->name('admin.events.store');
    Route::delete('/events/{id}', [AdminController::class, 'destroyEvent'])->name('admin.events.destroy');

    // 16. Admissions & Enquiries
    Route::get('/admissions', [AdminController::class, 'admissions'])->name('admin.admissions');
    Route::post('/admissions', [AdminController::class, 'storeEnquiry'])->name('admin.admissions.store');
    Route::post('/admissions/{id}/status', [AdminController::class, 'updateEnquiryStatus'])->name('admin.admissions.status');
    Route::delete('/admissions/{id}', [AdminController::class, 'destroyEnquiry'])->name('admin.admissions.destroy');

    // 17. Staff Payroll
    Route::get('/payroll', [AdminController::class, 'payroll'])->name('admin.payroll');
    Route::post('/payroll/generate', [AdminController::class, 'generatePayroll'])->name('admin.payroll.generate');
    Route::post('/payroll/{id}/pay', [AdminController::class, 'markPayrollPaid'])->name('admin.payroll.pay');
    Route::get('/payroll/payslip/{id}', [AdminController::class, 'payslip'])->name('admin.payroll.payslip');

    // 18. ID Cards & Certificates
    Route::get('/certificates', [AdminController::class, 'certificates'])->name('admin.certificates');
    Route::post('/certificates/generate', [AdminController::class, 'generateCertificate'])->name('admin.certificates.generate');
    Route::get('/certificates/print/{id}', [AdminController::class, 'printCertificate'])->name('admin.certificates.print');

    // 19. Notifications
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('admin.notifications');
    Route::post('/notifications/send', [AdminController::class, 'sendNotification'])->name('admin.notifications.send');

    // 20. Roles & Permissions
    Route::get('/roles', [AdminController::class, 'roles'])->name('admin.roles');
    Route::post('/roles', [AdminController::class, 'storeRole'])->name('admin.roles.store');
    Route::put('/roles/{id}', [AdminController::class, 'updateRole'])->name('admin.roles.update');
    Route::delete('/roles/{id}', [AdminController::class, 'destroyRole'])->name('admin.roles.destroy');

    // 21. Reports & Analytics
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');

    // 22. Backup & Activity Logs
    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('admin.audit-logs');
    Route::get('/audit-logs/backup-db', [AdminController::class, 'backupDatabase'])->name('admin.backup-db');

    // 23. Manage Admins
    Route::get('/manage-admins', [AdminController::class, 'manageAdmins'])->name('admin.manage-admins');
    Route::post('/manage-admins', [AdminController::class, 'storeAdmin'])->name('admin.manage-admins.store');
    Route::post('/manage-admins/{id}/permissions', [AdminController::class, 'updateAdminPermissions'])->name('admin.manage-admins.permissions');

    // 24. Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');

    // 25. Change Password
    Route::get('/change-password', [AdminController::class, 'changePassword'])->name('admin.change-password');
    Route::post('/change-password', [AdminController::class, 'updatePassword'])->name('admin.change-password.update');
});
