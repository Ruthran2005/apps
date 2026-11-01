<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Fee Structures & Payments
        if (!Schema::hasTable('fee_structures')) {
            Schema::create('fee_structures', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
                $table->string('fee_type'); // Tuition, Library, Lab, Sports, Transport, Development
                $table->decimal('amount', 10, 2);
                $table->enum('frequency', ['Annual', 'Term', 'Quarterly', 'Monthly'])->default('Term');
                $table->date('due_date')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('fee_payments')) {
            Schema::create('fee_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('fee_invoice_id')->constrained('fee_invoices')->cascadeOnDelete();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->string('receipt_number')->unique();
                $table->decimal('amount_paid', 10, 2);
                $table->string('payment_mode')->default('Cash'); // Cash, Online, UPI, Card, Cheque, Bank Transfer
                $table->string('transaction_reference')->nullable();
                $table->date('paid_at');
                $table->string('received_by')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 2. Exams & Schedules
        if (!Schema::hasTable('exams')) {
            Schema::create('exams', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Mid-Term Examination 2026, Annual Finals
                $table->string('term')->default('Term 1'); // Term 1, Term 2, Annual
                $table->string('academic_year')->default('2026-2027');
                $table->date('start_date');
                $table->date('end_date');
                $table->enum('status', ['Scheduled', 'In-Progress', 'Completed', 'Published'])->default('Scheduled');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('exam_schedules')) {
            Schema::create('exam_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
                $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
                $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
                $table->date('exam_date');
                $table->time('start_time');
                $table->time('end_time');
                $table->string('room_number')->nullable();
                $table->decimal('max_marks', 5, 2)->default(100.00);
                $table->decimal('passing_marks', 5, 2)->default(35.00);
                $table->timestamps();
            });
        }

        // 3. Syllabi
        if (!Schema::hasTable('syllabi')) {
            Schema::create('syllabi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
                $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
                $table->string('title');
                $table->string('unit_code')->nullable(); // Unit 1, Chapter 3
                $table->text('learning_objectives')->nullable();
                $table->string('term')->default('Term 1');
                $table->string('attachment')->nullable();
                $table->enum('status', ['Planned', 'Ongoing', 'Completed'])->default('Planned');
                $table->timestamps();
            });
        }

        // 4. Leave Applications (Teachers & Students)
        if (!Schema::hasTable('leave_applications')) {
            Schema::create('leave_applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->enum('applicant_type', ['teacher', 'student']);
                $table->string('leave_type'); // Casual, Sick, Maternity, Academic, Personal
                $table->date('start_date');
                $table->date('end_date');
                $table->integer('days_count')->default(1);
                $table->text('reason');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->string('reviewed_by')->nullable();
                $table->text('admin_remarks')->nullable();
                $table->timestamps();
            });
        }

        // 5. Library: Books & Issues
        if (!Schema::hasTable('books')) {
            Schema::create('books', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('isbn')->unique()->nullable();
                $table->string('author');
                $table->string('publisher')->nullable();
                $table->string('category')->default('General'); // Science, Literature, Tech, Reference, Mathematics
                $table->integer('total_copies')->default(1);
                $table->integer('available_copies')->default(1);
                $table->string('shelf_location')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('book_issues')) {
            Schema::create('book_issues', function (Blueprint $table) {
                $table->id();
                $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->enum('user_type', ['student', 'teacher']);
                $table->date('issue_date');
                $table->date('due_date');
                $table->date('return_date')->nullable();
                $table->enum('status', ['issued', 'returned', 'overdue'])->default('issued');
                $table->decimal('fine_amount', 8, 2)->default(0.00);
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 6. Transport: Vehicles, Routes, Allocations
        if (!Schema::hasTable('vehicles')) {
            Schema::create('vehicles', function (Blueprint $table) {
                $table->id();
                $table->string('vehicle_number')->unique(); // e.g. KA-01-AB-1234
                $table->string('model'); // e.g. Tata Starbus 40-Seater
                $table->integer('seating_capacity')->default(40);
                $table->string('driver_name');
                $table->string('driver_phone');
                $table->string('driver_license')->nullable();
                $table->enum('status', ['active', 'maintenance', 'inactive'])->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('transport_routes')) {
            Schema::create('transport_routes', function (Blueprint $table) {
                $table->id();
                $table->string('route_name'); // Route 1: North Suburbs
                $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
                $table->string('start_location');
                $table->string('end_location');
                $table->text('stops')->nullable(); // Comma-separated list of pickup/drop stops
                $table->decimal('fare', 8, 2)->default(0.00);
                $table->time('pickup_time')->nullable();
                $table->time('drop_time')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('transport_allocations')) {
            Schema::create('transport_allocations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('transport_route_id')->constrained('transport_routes')->cascadeOnDelete();
                $table->string('pickup_stop')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
            });
        }

        // 7. Events & Holidays Calendar
        if (!Schema::hasTable('school_events')) {
            Schema::create('school_events', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->enum('event_type', ['event', 'holiday', 'sports', 'exam', 'celebration', 'meeting'])->default('event');
                $table->date('start_date');
                $table->date('end_date');
                $table->enum('audience', ['all', 'teachers', 'students', 'parents'])->default('all');
                $table->string('location')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_holiday')->default(false);
                $table->timestamps();
            });
        }

        // 8. Admissions & Enquiries
        if (!Schema::hasTable('admissions_enquiries')) {
            Schema::create('admissions_enquiries', function (Blueprint $table) {
                $table->id();
                $table->string('enquiry_number')->unique();
                $table->string('applicant_name');
                $table->string('parent_name');
                $table->string('email')->nullable();
                $table->string('phone');
                $table->foreignId('school_class_id')->nullable()->constrained('school_classes')->nullOnDelete();
                $table->date('dob')->nullable();
                $table->string('gender')->nullable();
                $table->string('previous_school')->nullable();
                $table->text('address')->nullable();
                $table->enum('status', ['new', 'contacted', 'interview_scheduled', 'admitted', 'rejected'])->default('new');
                $table->text('notes')->nullable();
                $table->date('enquiry_date');
                $table->timestamps();
            });
        }

        // 9. Staff Payroll
        if (!Schema::hasTable('payrolls')) {
            Schema::create('payrolls', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
                $table->string('month'); // e.g. October
                $table->integer('year')->default(2026);
                $table->decimal('basic_salary', 10, 2);
                $table->decimal('allowances', 10, 2)->default(0.00);
                $table->decimal('deductions', 10, 2)->default(0.00);
                $table->decimal('net_salary', 10, 2);
                $table->string('payment_method')->default('Bank Transfer');
                $table->date('payment_date')->nullable();
                $table->enum('status', ['pending', 'paid'])->default('pending');
                $table->string('transaction_id')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 10. Certificates (TC, Bonafide, ID Cards)
        if (!Schema::hasTable('certificates')) {
            Schema::create('certificates', function (Blueprint $table) {
                $table->id();
                $table->string('certificate_type'); // transfer_certificate, bonafide, character, id_card
                $table->string('certificate_number')->unique();
                $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
                $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
                $table->date('issue_date');
                $table->string('reason')->nullable();
                $table->text('details')->nullable(); // JSON or text summary
                $table->string('issued_by')->default('Principal Office');
                $table->timestamps();
            });
        }

        // 11. Sent Notifications (SMS, Email, Push)
        if (!Schema::hasTable('sent_notifications')) {
            Schema::create('sent_notifications', function (Blueprint $table) {
                $table->id();
                $table->enum('channel', ['sms', 'email', 'push', 'portal'])->default('portal');
                $table->enum('target_group', ['all', 'students', 'teachers', 'class_specific'])->default('all');
                $table->foreignId('school_class_id')->nullable()->constrained('school_classes')->nullOnDelete();
                $table->string('title');
                $table->text('message');
                $table->string('sent_by')->default('Admin');
                $table->enum('status', ['sent', 'delivered', 'failed'])->default('sent');
                $table->timestamps();
            });
        }

        // 12. Roles
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Super Admin, Academic Head, Accountant, Librarian, Transport Officer
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->json('permissions')->nullable();
                $table->timestamps();
            });
        }

        // 13. Activity & Audit Logs
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('user_name')->default('System');
                $table->string('action'); // created, updated, deleted, logged_in, exported, backed_up
                $table->string('module'); // Students, Fees, Exams, Leaves, Settings
                $table->text('description');
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('sent_notifications');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('admissions_enquiries');
        Schema::dropIfExists('school_events');
        Schema::dropIfExists('transport_allocations');
        Schema::dropIfExists('transport_routes');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('book_issues');
        Schema::dropIfExists('books');
        Schema::dropIfExists('leave_applications');
        Schema::dropIfExists('syllabi');
        Schema::dropIfExists('exam_schedules');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('fee_payments');
        Schema::dropIfExists('fee_structures');
    }
};
