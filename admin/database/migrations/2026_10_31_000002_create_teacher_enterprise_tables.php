<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Quizzes & Tests
        if (!Schema::hasTable('quizzes')) {
            Schema::create('quizzes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
                $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->string('title');
                $table->string('quiz_type')->default('Class Test'); // Quiz, Unit Test, Chapter Exam, Surprise Quiz
                $table->integer('total_marks')->default(25);
                $table->integer('passing_marks')->default(10);
                $table->integer('duration_minutes')->default(30);
                $table->date('quiz_date');
                $table->text('instructions')->nullable();
                $table->string('status')->default('Scheduled'); // Scheduled, Active, Completed
                $table->timestamps();
            });
        }

        // 2. Study Materials (PDF, Video, Notes, Slides)
        if (!Schema::hasTable('study_materials')) {
            Schema::create('study_materials', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
                $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->string('title');
                $table->string('material_type')->default('PDF Document'); // PDF Document, Lecture Video, Study Notes, Presentation, Reference URL
                $table->string('file_path')->nullable();
                $table->string('external_url')->nullable();
                $table->string('chapter_topic')->nullable();
                $table->text('description')->nullable();
                $table->integer('download_count')->default(0);
                $table->timestamps();
            });
        }

        // 3. Class Diary & Daily Notes
        if (!Schema::hasTable('class_diaries')) {
            Schema::create('class_diaries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
                $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
                $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->date('entry_date');
                $table->string('topic_covered');
                $table->text('classwork_summary')->nullable();
                $table->text('homework_assigned')->nullable();
                $table->text('parent_note')->nullable();
                $table->timestamps();
            });
        }

        // 4. Parent Communications & Direct Messages
        if (!Schema::hasTable('parent_communications')) {
            Schema::create('parent_communications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->string('subject');
                $table->text('message');
                $table->string('sender_type')->default('teacher'); // teacher, parent
                $table->string('priority')->default('Normal'); // Normal, Important, Urgent
                $table->boolean('is_read')->default(false);
                $table->text('parent_reply')->nullable();
                $table->dateTime('replied_at')->nullable();
                $table->timestamps();
            });
        }

        // 5. Substitute and Duty Roster
        if (!Schema::hasTable('duty_rosters')) {
            Schema::create('duty_rosters', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
                $table->string('duty_type'); // Morning Gate Supervision, Lunch Break Monitor, Assembly In-charge, etc.
                $table->date('duty_date');
                $table->string('time_slot'); // e.g. 08:00 AM - 08:45 AM
                $table->string('location');
                $table->text('instructions')->nullable();
                $table->string('status')->default('Assigned'); // Assigned, Completed, Substituted
                $table->foreignId('substitute_teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
                $table->timestamps();
            });
        }

        // 6. Exam Duty & Invigilation Schedule
        if (!Schema::hasTable('invigilation_duties')) {
            Schema::create('invigilation_duties', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
                $table->foreignId('exam_id')->nullable()->constrained('exams')->nullOnDelete();
                $table->string('exam_name');
                $table->string('subject_name');
                $table->date('exam_date');
                $table->string('time_slot'); // e.g. 09:30 AM - 12:30 PM
                $table->string('room_number');
                $table->string('role')->default('Hall Invigilator'); // Chief Invigilator, Hall Invigilator, Reliever
                $table->text('instructions')->nullable();
                $table->string('status')->default('Scheduled'); // Scheduled, In Progress, Completed
                $table->timestamps();
            });
        }

        // 7. Add specific student targeting to assignments if missing
        if (Schema::hasTable('assignments')) {
            Schema::table('assignments', function (Blueprint $table) {
                if (!Schema::hasColumn('assignments', 'target_type')) {
                    $table->string('target_type')->default('all')->after('attachment'); // all, specific
                }
                if (!Schema::hasColumn('assignments', 'target_students')) {
                    $table->text('target_students')->nullable()->after('target_type'); // JSON array of student IDs
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('invigilation_duties');
        Schema::dropIfExists('duty_rosters');
        Schema::dropIfExists('parent_communications');
        Schema::dropIfExists('class_diaries');
        Schema::dropIfExists('study_materials');
        Schema::dropIfExists('quizzes');
    }
};
