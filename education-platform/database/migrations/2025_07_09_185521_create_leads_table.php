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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            
            // Lead Source Information
            $table->string('source')->default('website'); // website, referral, social, ads, etc.
            $table->string('campaign')->nullable(); // marketing campaign identifier
            $table->string('medium')->nullable(); // organic, paid, email, etc.
            $table->string('referrer_url')->nullable();
            $table->json('utm_parameters')->nullable(); // UTM tracking data
            
            // Lead Type and Target
            $table->enum('lead_type', ['teacher_inquiry', 'institute_inquiry', 'general_inquiry', 'demo_request', 'callback_request']);
            $table->foreignId('target_teacher_id')->nullable()->constrained('teacher_profiles')->onDelete('set null');
            $table->foreignId('target_institute_id')->nullable()->constrained('institutes')->onDelete('set null');
            $table->foreignId('target_branch_id')->nullable()->constrained('institutes')->onDelete('set null');
            
            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            
            // Address Information
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('country')->default('India');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Educational Information
            $table->string('current_education_level')->nullable(); // school, college, graduation, etc.
            $table->string('institution_name')->nullable();
            $table->json('subjects_interested')->nullable(); // Array of subject IDs
            $table->enum('learning_mode', ['online', 'offline', 'both'])->nullable();
            $table->enum('class_type', ['individual', 'group', 'both'])->nullable();
            
            // Requirements and Preferences
            $table->text('message')->nullable();
            $table->text('specific_requirements')->nullable();
            $table->string('preferred_language')->nullable();
            $table->json('preferred_timing')->nullable(); // Available time slots
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            $table->enum('urgency', ['immediate', 'within_week', 'within_month', 'flexible'])->default('flexible');
            
            // Lead Status and Management
            $table->enum('status', ['new', 'contacted', 'qualified', 'converted', 'lost', 'invalid'])->default('new');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('last_follow_up')->nullable();
            $table->timestamp('next_follow_up')->nullable();
            $table->integer('follow_up_count')->default(0);
            
            // Conversion Tracking
            $table->boolean('is_converted')->default(false);
            $table->timestamp('converted_at')->nullable();
            $table->string('conversion_type')->nullable(); // enrollment, booking, registration
            $table->decimal('conversion_value', 10, 2)->nullable();
            $table->text('conversion_notes')->nullable();
            
            // Quality and Source Verification
            $table->integer('lead_score')->default(0); // 0-100 lead scoring
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_spam')->default(false);
            $table->json('verification_data')->nullable(); // Additional verification info
            
            // Communication Preferences
            $table->boolean('email_consent')->default(true);
            $table->boolean('sms_consent')->default(true);
            $table->boolean('whatsapp_consent')->default(true);
            $table->boolean('call_consent')->default(true);
            $table->json('communication_history')->nullable(); // Track all communications
            
            // Technical and Analytics
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_type')->nullable(); // mobile, desktop, tablet
            $table->string('browser')->nullable();
            $table->json('form_data')->nullable(); // Original form submission data
            $table->json('custom_fields')->nullable(); // Additional dynamic fields
            
            // Timestamps and Meta
            $table->timestamp('first_contact')->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->text('notes')->nullable();
            $table->json('tags')->nullable(); // For categorization
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['status', 'created_at']);
            $table->index(['lead_type', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index(['target_teacher_id', 'status']);
            $table->index(['target_institute_id', 'status']);
            $table->index(['email']);
            $table->index(['phone']);
            $table->index(['source', 'created_at']);
            $table->index(['is_converted', 'converted_at']);
            $table->index(['lead_score']);
            $table->index(['city', 'state']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
