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
        Schema::table('institutes', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('institute_name');
            $table->string('institute_type')->default('School')->after('slug');
            $table->string('affiliation')->nullable()->after('institute_type');
            $table->string('verification_status')->default('pending')->after('verified');
            $table->boolean('is_featured')->default(false)->after('verification_status');
            $table->string('logo')->nullable()->after('is_featured');
            $table->string('contact_email')->nullable()->after('contact_phone');
            $table->text('specialization')->nullable()->after('description');
            $table->json('courses')->nullable()->after('facilities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'institute_type',
                'affiliation',
                'verification_status',
                'is_featured',
                'logo',
                'contact_email',
                'specialization',
                'courses'
            ]);
        });
    }
}; 