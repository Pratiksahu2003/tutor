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
        Schema::create('exam_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->decimal('weightage', 5, 2)->nullable()->comment('Weightage percentage in exam');
            $table->integer('marks')->nullable()->comment('Maximum marks for this subject');
            $table->boolean('is_optional')->default(false);
            $table->text('syllabus_topics')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['exam_id', 'subject_id']);
            $table->index('exam_id');
            $table->index('subject_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_subjects');
    }
};
