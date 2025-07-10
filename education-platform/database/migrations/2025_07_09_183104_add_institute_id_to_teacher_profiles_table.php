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
            $table->foreignId('institute_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('employment_type', ['freelance', 'institute', 'both'])->default('freelance');
            $table->json('institute_subjects')->nullable(); // Subjects taught at institute
            $table->text('institute_experience')->nullable(); // Experience at current institute
            $table->boolean('is_institute_verified')->default(false); // Verified by institute admin
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropForeign(['institute_id']);
            $table->dropColumn(['institute_id', 'employment_type', 'institute_subjects', 'institute_experience', 'is_institute_verified']);
        });
    }
};
