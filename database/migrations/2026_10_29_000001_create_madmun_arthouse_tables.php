<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('STUDENT')->after('password');
            $table->string('phone')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('address')->nullable()->after('bio');
            $table->unsignedInteger('hourly_rate')->nullable()->after('address');
            $table->boolean('is_active')->default(true)->after('hourly_rate');
        });

        Schema::create('art_courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('duration_hours')->default(24);
            $table->string('level', 30)->default('beginner');
            $table->unsignedInteger('tuition_fee')->default(0);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });

        Schema::create('art_classes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('course_id')->constrained('art_courses')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('schedule')->nullable();
            $table->string('room')->nullable();
            $table->string('status', 20)->default('upcoming');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedInteger('max_students')->default(20);
            $table->timestamps();
        });

        Schema::create('class_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('art_class_id')->constrained('art_classes')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('enrolled_at')->nullable();
            $table->unique(['art_class_id', 'student_id']);
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('art_class_id')->constrained('art_classes')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->date('session_date');
            $table->string('status', 20)->default('present');
            $table->string('note')->nullable();
            $table->timestamps();
            $table->unique(['art_class_id', 'student_id', 'session_date']);
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('art_class_id')->nullable()->constrained('art_classes')->nullOnDelete();
            $table->unsignedInteger('amount');
            $table->string('status', 20)->default('unpaid');
            $table->string('method')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });

        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('period_month');
            $table->unsignedSmallInteger('period_year');
            $table->decimal('teaching_hours', 8, 1)->default(0);
            $table->unsignedInteger('amount')->default(0);
            $table->string('status', 20)->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });

        Schema::create('artworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('image');
            $table->text('description')->nullable();
            $table->string('status', 20)->default('pending');
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('review_note')->nullable();
            $table->timestamps();
        });

        Schema::create('artwork_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artwork_id')->constrained('artworks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('comment');
            $table->timestamps();
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->text('content');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('artwork_comments');
        Schema::dropIfExists('artworks');
        Schema::dropIfExists('salary_payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('class_student');
        Schema::dropIfExists('art_classes');
        Schema::dropIfExists('art_courses');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'phone', 'avatar', 'bio', 'address', 'hourly_rate', 'is_active',
            ]);
        });
    }
};
