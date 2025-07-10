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
        Schema::create('teacher_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_profile_id')->constrained('teacher_profiles')->onDelete('cascade');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->integer('experience_years')->default(0)->comment('Years of experience in this exam preparation');
            $table->decimal('success_rate', 5, 2)->nullable()->comment('Success rate percentage');
            $table->integer('students_cleared')->default(0)->comment('Number of students cleared this exam');
            $table->decimal('average_score', 8, 2)->nullable()->comment('Average score of students');
            $table->text('teaching_methodology')->nullable();
            $table->text('study_materials')->nullable();
            $table->json('specialization_subjects')->nullable()->comment('Subject-wise specialization within exam');
            $table->decimal('course_fee', 10, 2)->nullable()->comment('Course fee for this exam preparation');
            $table->integer('course_duration_months')->nullable();
            $table->integer('batch_size')->nullable();
            $table->enum('teaching_mode', ['online', 'offline', 'both'])->default('both');
            $table->text('achievements')->nullable()->comment('Notable achievements, awards, etc.');
            $table->boolean('is_certified')->default(false)->comment('Certified trainer for this exam');
            $table->string('certification_details')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
            
            $table->unique(['teacher_profile_id', 'exam_id']);
            $table->index('teacher_profile_id');
            $table->index('exam_id');
            $table->index(['success_rate', 'students_cleared']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_exams');
    }
};
