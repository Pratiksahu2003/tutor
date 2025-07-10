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
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->foreignId('subject_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            $table->string('slug')->nullable()->after('specialization');
            $table->string('verification_status')->default('pending')->after('verified');
            $table->string('availability_status')->default('available')->after('availability');
            $table->boolean('is_featured')->default(false)->after('verified');
            $table->string('city')->nullable()->after('teaching_city');
            $table->string('state')->nullable()->after('teaching_state');
            $table->text('qualifications')->nullable()->after('qualification');
            $table->string('avatar')->nullable()->after('bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn([
                'subject_id',
                'slug',
                'verification_status', 
                'availability_status',
                'is_featured',
                'city',
                'state',
                'qualifications',
                'avatar'
            ]);
        });
    }
}; 