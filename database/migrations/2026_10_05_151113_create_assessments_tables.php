<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('chapter_id')->nullable()->constrained('course_chapters')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['quiz', 'exam', 'assignment']);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->integer('order')->default(0);
            $table->decimal('pass_score', 5, 2)->default(0);
            $table->integer('time_limit')->nullable(); // in minutes
            $table->integer('attempt_limit')->nullable();
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->text('question_text');
            $table->enum('type', ['multiple-choice', 'true-false']);
            $table->integer('points')->default(1);
            $table->json('options')->nullable();
            $table->json('correct_answer')->nullable();
            $table->text('explanation')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('assessment_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->enum('status', ['in-progress', 'submitted', 'graded'])->default('in-progress');
            $table->decimal('score', 5, 2)->nullable();
            $table->boolean('is_passed')->default(false);
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('assessment_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->json('submitted_answer')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->integer('points_awarded')->default(0);
            $table->timestamps();
        });

        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->text('content')->nullable();
            $table->enum('status', ['pending', 'graded'])->default('pending');
            $table->decimal('score', 5, 2)->nullable();
            $table->boolean('is_passed')->default(false);
            $table->text('instructor_feedback')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('attempt_answers');
        Schema::dropIfExists('assessment_attempts');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('assessments');
    }
};
