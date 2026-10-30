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
        // 1. Classes / Grades
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Grade 10
            $table->string('code')->unique(); // e.g. G10
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Sections
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->string('name'); // e.g. Section A
            $table->string('room_number')->nullable();
            $table->integer('capacity')->default(40);
            $table->timestamps();
        });

        // 3. Subjects
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Mathematics, Advanced Physics
            $table->string('code')->unique(); // e.g. MATH-101
            $table->integer('credits')->default(3);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. Teachers
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('employee_id')->unique(); // e.g. TCH-001
            $table->string('qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->date('joining_date')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->timestamps();
        });

        // 5. Students
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('admission_number')->unique(); // e.g. STU-2026-001
            $table->string('roll_number')->nullable();
            $table->foreignId('school_class_id')->nullable()->constrained('school_classes')->nullOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable(); // Male, Female, Other
            $table->string('blood_group')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_email')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // 6. Teacher Subject Allocation (Class + Section + Subject + Teacher)
        Schema::create('teacher_subject_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->timestamps();
        });

        // 7. Attendance
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->string('remarks')->nullable();
            $table->foreignId('marked_by')->nullable()->constrained('teachers')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'attendance_date']);
        });

        // 8. Exam Grades & Marks
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->string('exam_name'); // e.g. Midterm 2026, Final Assessment
            $table->decimal('marks_obtained', 5, 2);
            $table->decimal('max_marks', 5, 2)->default(100.00);
            $table->string('grade_letter')->nullable(); // A+, A, B, C, D, F
            $table->text('remarks')->nullable();
            $table->date('exam_date')->nullable();
            $table->timestamps();
        });

        // 9. Assignments
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->dateTime('due_date');
            $table->integer('total_points')->default(100);
            $table->string('attachment')->nullable();
            $table->timestamps();
        });

        // 10. Student Assignment Submissions
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->text('submission_text')->nullable();
            $table->string('file_attachment')->nullable();
            $table->dateTime('submitted_at');
            $table->decimal('marks_awarded', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['submitted', 'graded', 'late', 'resubmit'])->default('submitted');
            $table->timestamps();

            $table->unique(['assignment_id', 'student_id']);
        });

        // 11. Timetables
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->string('day_of_week'); // Mon, Tue, Wed, Thu, Fri, Sat
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room_number')->nullable();
            $table->timestamps();
        });

        // 12. Announcements / Notice Board
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('audience', ['all', 'teachers', 'students'])->default('all');
            $table->enum('priority', ['normal', 'important', 'urgent'])->default('normal');
            $table->string('author_name')->default('School Administration');
            $table->date('published_at');
            $table->timestamps();
        });

        // 13. Fee Invoices
        Schema::create('fee_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->date('due_date');
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_invoices');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('timetables');
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('teacher_subject_assignments');
        Schema::dropIfExists('students');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('school_classes');
    }
};
