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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            
            // Inquiry Type and Target
            $table->enum('inquiry_type', ['teacher', 'institute', 'general', 'demo', 'callback']);
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('target_teacher_id')->nullable()->constrained('teacher_profiles')->onDelete('cascade');
            $table->foreignId('target_institute_id')->nullable()->constrained('institutes')->onDelete('cascade');
            $table->foreignId('target_branch_id')->nullable()->constrained('institutes')->onDelete('cascade');
            
            // Inquiry Content
            $table->string('subject');
            $table->text('message');
            $table->json('subjects_interested')->nullable(); // Array of subject IDs
            $table->enum('learning_mode', ['online', 'offline', 'both'])->nullable();
            $table->enum('class_type', ['individual', 'group', 'both'])->nullable();
            
            // Budget and Preferences
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            $table->json('preferred_timing')->nullable(); // Available time slots
            $table->string('preferred_language')->nullable();
            $table->enum('urgency', ['immediate', 'within_week', 'within_month', 'flexible'])->default('flexible');
            
            // Contact Information
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->enum('preferred_contact_method', ['email', 'phone', 'whatsapp', 'any'])->default('any');
            
            // Status and Management
            $table->enum('status', ['sent', 'read', 'replied', 'in_progress', 'resolved', 'closed'])->default('sent');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            
            // Response and Follow-up
            $table->text('teacher_response')->nullable();
            $table->text('institute_response')->nullable();
            $table->json('follow_up_history')->nullable(); // Track all follow-ups
            $table->timestamp('next_follow_up')->nullable();
            $table->integer('follow_up_count')->default(0);
            
            // Lead Conversion
            $table->boolean('is_converted')->default(false);
            $table->timestamp('converted_at')->nullable();
            $table->string('conversion_type')->nullable(); // booking, enrollment, etc.
            $table->decimal('conversion_value', 10, 2)->nullable();
            
            // Rating and Feedback
            $table->integer('student_rating')->nullable(); // 1-5 rating of response
            $table->text('student_feedback')->nullable();
            $table->integer('response_rating')->nullable(); // Teacher/Institute rating of inquiry
            $table->text('response_notes')->nullable();
            
            // Communication Tracking
            $table->json('communication_history')->nullable(); // Track all communications
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(false);
            
            // Technical and Analytics
            $table->string('source')->default('website'); // website, app, referral, etc.
            $table->string('device_type')->nullable(); // mobile, desktop, tablet
            $table->string('ip_address')->nullable();
            $table->json('referrer_data')->nullable();
            
            // Additional Information
            $table->text('special_requirements')->nullable();
            $table->json('student_details')->nullable(); // Additional student info
            $table->json('custom_fields')->nullable(); // Dynamic additional fields
            $table->json('attachments')->nullable(); // File attachments
            
            // Administrative
            $table->boolean('is_spam')->default(false);
            $table->boolean('is_verified')->default(true);
            $table->text('admin_notes')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['inquiry_type', 'status']);
            $table->index(['student_id', 'created_at']);
            $table->index(['target_teacher_id', 'status']);
            $table->index(['target_institute_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['is_converted', 'converted_at']);
            $table->index(['urgency', 'created_at']);
            $table->index(['priority', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
