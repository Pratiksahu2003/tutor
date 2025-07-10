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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            
            // Question Basic Information
            $table->string('title');
            $table->longText('question_text');
            $table->enum('type', ['multiple_choice', 'true_false', 'short_answer', 'long_answer', 'fill_blank', 'matching'])->default('multiple_choice');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->integer('marks')->default(1);
            $table->integer('time_limit')->nullable(); // in seconds
            
            // Question Content
            $table->json('options')->nullable(); // For MCQ, matching, etc.
            $table->json('correct_answers')->nullable(); // Can be multiple for checkbox type
            $table->text('explanation')->nullable();
            $table->text('hint')->nullable();
            $table->string('image')->nullable();
            $table->text('solution_steps')->nullable();
            
            // Academic Information
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->string('topic')->nullable();
            $table->string('subtopic')->nullable();
            $table->string('chapter')->nullable();
            $table->enum('class_level', ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', 'undergraduate', 'postgraduate'])->nullable();
            $table->string('board')->nullable(); // CBSE, ICSE, State boards, etc.
            
            // Question Classification
            $table->json('tags')->nullable(); // For better categorization
            $table->enum('category', ['practice', 'exam', 'quiz', 'assignment', 'competition'])->default('practice');
            $table->string('source')->nullable(); // Textbook, previous year paper, etc.
            $table->string('reference')->nullable();
            
            // Question Stats and Management
            $table->enum('status', ['draft', 'published', 'archived', 'under_review'])->default('draft');
            $table->integer('usage_count')->default(0);
            $table->decimal('success_rate', 5, 2)->nullable(); // Percentage of correct answers
            $table->decimal('average_time', 8, 2)->nullable(); // Average time taken to answer
            $table->integer('total_attempts')->default(0);
            $table->integer('correct_attempts')->default(0);
            
            // Author and Review
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            
            // Question Banks and Sets
            $table->boolean('is_public')->default(true);
            $table->boolean('allow_comments')->default(true);
            $table->integer('likes')->default(0);
            $table->integer('dislikes')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index(['subject_id', 'status']);
            $table->index(['type', 'difficulty']);
            $table->index(['class_level', 'board']);
            $table->index(['category', 'status']);
            $table->index(['created_by']);
            $table->index(['topic', 'subtopic']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
