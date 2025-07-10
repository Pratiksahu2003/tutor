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
        Schema::create('school_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_profile_id')->constrained('teacher_profiles')->onDelete('cascade');
            $table->string('school_name');
            $table->string('branch_name')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('pincode', 10);
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->year('established_year')->nullable();
            $table->enum('affiliation', [
                'government', 'government_aided', 'private_unaided', 'private_aided',
                'central_government', 'autonomous', 'international'
            ])->nullable();
            $table->enum('board', [
                'cbse', 'icse', 'state_board', 'ib', 'cambridge', 'nios', 'other'
            ])->nullable();
            $table->json('medium_of_instruction')->nullable()->comment('Array of languages');
            $table->json('facilities')->nullable()->comment('Available facilities');
            $table->json('timings')->nullable()->comment('Operating hours');
            $table->string('contact_person')->nullable();
            $table->string('contact_designation')->nullable();
            $table->boolean('is_primary')->default(false)->comment('Primary branch for this teacher');
            $table->enum('status', ['active', 'inactive', 'pending_verification'])->default('pending_verification');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('google_maps_link')->nullable();
            $table->json('photos')->nullable()->comment('Branch photos');
            $table->json('documents')->nullable()->comment('Verification documents');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('verification_notes')->nullable();
            $table->timestamps();
            
            $table->index('teacher_profile_id');
            $table->index(['city', 'state']);
            $table->index('status');
            $table->index('is_primary');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_branches');
    }
};
