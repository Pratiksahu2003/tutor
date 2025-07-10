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
        Schema::create('institute_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained('institutes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->integer('course_duration')->nullable()->comment('Course duration in months');
            $table->decimal('fees', 10, 2)->nullable()->comment('Course fees');
            $table->text('course_description')->nullable();
            $table->enum('teaching_mode', ['online', 'offline', 'both'])->default('both');
            $table->integer('batch_size')->nullable();
            $table->json('timings')->nullable()->comment('Class timings');
            $table->enum('status', ['active', 'inactive', 'upcoming'])->default('active');
            $table->timestamps();
            
            $table->unique(['institute_id', 'subject_id']);
            $table->index('institute_id');
            $table->index('subject_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_subjects');
    }
};
