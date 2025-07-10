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
        Schema::create('school_branch_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_branch_id')->constrained('school_branches')->onDelete('cascade');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->string('course_name')->nullable();
            $table->integer('course_duration')->nullable()->comment('Course duration in months');
            $table->string('fee_range')->nullable();
            $table->decimal('success_rate', 5, 2)->nullable()->comment('Success rate percentage');
            $table->integer('batch_size')->nullable();
            $table->text('course_description')->nullable();
            $table->json('study_materials')->nullable();
            $table->enum('teaching_mode', ['online', 'offline', 'both'])->default('both');
            $table->json('timings')->nullable()->comment('Class timings');
            $table->text('achievements')->nullable();
            $table->enum('status', ['active', 'inactive', 'upcoming'])->default('active');
            $table->timestamps();
            
            $table->unique(['school_branch_id', 'exam_id']);
            $table->index('school_branch_id');
            $table->index('exam_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_branch_exams');
    }
};
