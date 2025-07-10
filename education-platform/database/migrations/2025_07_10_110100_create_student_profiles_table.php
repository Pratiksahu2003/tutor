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
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Basic Information
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('current_class')->nullable();
            $table->string('school_name')->nullable();
            $table->string('board', 50)->nullable()->comment('CBSE, ICSE, State Board, etc.');
            
            // Location Information
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('area')->nullable()->comment('Specific area or locality');
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Learning Preferences
            $table->json('subjects_interested')->nullable()->comment('Array of subject IDs');
            $table->json('learning_goals')->nullable()->comment('What student wants to achieve');
            $table->enum('preferred_learning_mode', ['online', 'offline', 'both'])->default('both');
            $table->json('preferred_timings')->nullable()->comment('When student prefers to study');
            $table->decimal('budget_min', 8, 2)->nullable();
            $table->decimal('budget_max', 8, 2)->nullable();
            $table->string('urgency', 50)->default('flexible')->comment('How soon they need to start');
            
            // Additional Information
            $table->text('learning_challenges')->nullable()->comment('Any specific learning difficulties');
            $table->text('special_requirements')->nullable();
            $table->json('extracurricular_interests')->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('emergency_contact')->nullable();
            
            // Progress Tracking
            $table->json('current_grades')->nullable()->comment('Current performance in subjects');
            $table->json('target_grades')->nullable()->comment('Desired grades/scores');
            $table->text('previous_tutoring_experience')->nullable();
            
            // Status and Verification
            $table->boolean('is_active')->default(true);
            $table->boolean('profile_completed')->default(false);
            $table->timestamp('last_activity')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['city', 'state']);
            $table->index('preferred_learning_mode');
            $table->index('is_active');
            $table->index(['budget_min', 'budget_max']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
}; 