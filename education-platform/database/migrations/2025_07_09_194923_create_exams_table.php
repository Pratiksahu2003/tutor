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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_name')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('exam_category_id')->constrained('exam_categories')->onDelete('cascade');
            $table->string('conducting_body')->nullable();
            $table->enum('exam_type', ['national', 'state', 'government', 'private', 'school', 'university'])->default('government');
            $table->enum('frequency', ['yearly', 'twice_yearly', 'quarterly', 'monthly', 'ongoing'])->default('yearly');
            $table->text('eligibility')->nullable();
            $table->text('exam_pattern')->nullable();
            $table->text('syllabus')->nullable();
            $table->text('preparation_tips')->nullable();
            $table->string('official_website')->nullable();
            $table->decimal('application_fee', 10, 2)->nullable();
            $table->date('exam_date')->nullable();
            $table->date('result_date')->nullable();
            $table->date('application_start')->nullable();
            $table->date('application_end')->nullable();
            $table->string('age_limit')->nullable();
            $table->text('educational_qualification')->nullable();
            $table->integer('total_marks')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->boolean('negative_marking')->default(false);
            $table->decimal('cutoff_marks', 8, 2)->nullable();
            $table->enum('status', ['active', 'inactive', 'upcoming', 'completed'])->default('active');
            $table->boolean('featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'featured', 'sort_order']);
            $table->index(['exam_type', 'status']);
            $table->index(['exam_category_id', 'status']);
            $table->index('slug');
            $table->index('exam_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
