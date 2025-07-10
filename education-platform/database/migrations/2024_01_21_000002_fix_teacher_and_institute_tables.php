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
        // Add fields to teacher_profiles table
        Schema::table('teacher_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('teacher_profiles', 'subject_id')) {
                $table->foreignId('subject_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('teacher_profiles', 'slug')) {
                $table->string('slug')->nullable()->after('specialization');
            }
            if (!Schema::hasColumn('teacher_profiles', 'verification_status')) {
                $table->string('verification_status')->default('pending')->after('verified');
            }
            if (!Schema::hasColumn('teacher_profiles', 'availability_status')) {
                $table->string('availability_status')->default('available')->after('availability');
            }
            if (!Schema::hasColumn('teacher_profiles', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('verified');
            }
            if (!Schema::hasColumn('teacher_profiles', 'city')) {
                $table->string('city')->nullable()->after('teaching_city');
            }
            if (!Schema::hasColumn('teacher_profiles', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
            if (!Schema::hasColumn('teacher_profiles', 'qualifications')) {
                $table->text('qualifications')->nullable()->after('qualification');
            }
            if (!Schema::hasColumn('teacher_profiles', 'avatar')) {
                $table->string('avatar')->nullable()->after('bio');
            }
        });

        // Add fields to institutes table
        Schema::table('institutes', function (Blueprint $table) {
            if (!Schema::hasColumn('institutes', 'slug')) {
                $table->string('slug')->nullable()->after('institute_name');
            }
            if (!Schema::hasColumn('institutes', 'verification_status')) {
                $table->string('verification_status')->default('pending')->after('verified');
            }
            if (!Schema::hasColumn('institutes', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('verification_status');
            }
            if (!Schema::hasColumn('institutes', 'logo')) {
                $table->string('logo')->nullable()->after('is_featured');
            }
            if (!Schema::hasColumn('institutes', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('contact_phone');
            }
            if (!Schema::hasColumn('institutes', 'specialization')) {
                $table->text('specialization')->nullable()->after('description');
            }
            if (!Schema::hasColumn('institutes', 'affiliation')) {
                $table->string('affiliation')->nullable()->after('specialization');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove fields from teacher_profiles table
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $teacherColumns = ['subject_id', 'slug', 'verification_status', 'availability_status', 'is_featured', 'city', 'state', 'qualifications', 'avatar'];
            foreach ($teacherColumns as $column) {
                if (Schema::hasColumn('teacher_profiles', $column)) {
                    if ($column === 'subject_id') {
                        $table->dropForeign(['subject_id']);
                    }
                    $table->dropColumn($column);
                }
            }
        });

        // Remove fields from institutes table
        Schema::table('institutes', function (Blueprint $table) {
            $instituteColumns = ['slug', 'verification_status', 'is_featured', 'logo', 'contact_email', 'specialization', 'affiliation'];
            foreach ($instituteColumns as $column) {
                if (Schema::hasColumn('institutes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 