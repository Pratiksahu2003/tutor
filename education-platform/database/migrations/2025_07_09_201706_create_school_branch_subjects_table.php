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
        Schema::create('school_branch_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_branch_id')->constrained('school_branches')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->string('classes_offered')->nullable()->comment('Which classes this subject is offered for');
            $table->decimal('fee_per_month', 8, 2)->nullable();
            $table->integer('batch_size')->nullable();
            $table->string('duration')->nullable()->comment('Duration per class/session');
            $table->json('timings')->nullable()->comment('Class timings');
            $table->text('syllabus_covered')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->unique(['school_branch_id', 'subject_id']);
            $table->index('school_branch_id');
            $table->index('subject_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_branch_subjects');
    }
};
