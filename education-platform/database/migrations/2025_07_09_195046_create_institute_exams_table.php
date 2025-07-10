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
        Schema::create('institute_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained('institutes')->onDelete('cascade');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->string('course_name')->nullable();
            $table->integer('course_duration_months')->nullable();
            $table->integer('batch_size')->nullable()->comment('Maximum students per batch');
            $table->decimal('success_rate', 5, 2)->nullable()->comment('Success rate percentage');
            $table->integer('students_cleared')->default(0)->comment('Total students cleared this exam');
            $table->decimal('fee_range_min', 10, 2)->nullable();
            $table->decimal('fee_range_max', 10, 2)->nullable();
            $table->text('course_description')->nullable();
            $table->json('course_features')->nullable()->comment('Course features and highlights');
            $table->json('study_materials')->nullable()->comment('Books, notes, online resources');
            $table->json('faculty_details')->nullable()->comment('Faculty specializing in this exam');
            $table->enum('teaching_mode', ['online', 'offline', 'both'])->default('both');
            $table->text('schedule_details')->nullable();
            $table->json('facilities')->nullable()->comment('Facilities provided for this course');
            $table->text('admission_requirements')->nullable();
            $table->date('course_start_date')->nullable();
            $table->date('course_end_date')->nullable();
            $table->date('admission_deadline')->nullable();
            $table->boolean('scholarship_available')->default(false);
            $table->text('scholarship_details')->nullable();
            $table->text('placement_assistance')->nullable();
            $table->json('achievements')->nullable()->comment('Awards, recognitions, etc.');
            $table->enum('status', ['active', 'inactive', 'suspended', 'upcoming'])->default('active');
            $table->timestamps();
            
            $table->unique(['institute_id', 'exam_id']);
            $table->index('institute_id');
            $table->index('exam_id');
            $table->index(['success_rate', 'students_cleared']);
            $table->index('status');
            $table->index('course_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_exams');
    }
};
