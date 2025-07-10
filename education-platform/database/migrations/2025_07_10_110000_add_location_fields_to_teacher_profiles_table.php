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
            // Teaching location (can be different from home address)
            $table->string('teaching_city')->nullable()->after('verified');
            $table->string('teaching_state')->nullable()->after('teaching_city');
            $table->string('teaching_pincode')->nullable()->after('teaching_state');
            $table->string('teaching_area')->nullable()->after('teaching_pincode')->comment('Specific area or locality for teaching');
            $table->decimal('teaching_latitude', 10, 8)->nullable()->after('teaching_area');
            $table->decimal('teaching_longitude', 11, 8)->nullable()->after('teaching_latitude');
            $table->decimal('travel_radius_km', 5, 2)->default(10.00)->after('teaching_longitude')->comment('Willing to travel radius in km');
            $table->json('preferred_areas')->nullable()->after('travel_radius_km')->comment('List of preferred teaching areas');
            
            // Additional location preferences
            $table->boolean('home_tuition')->default(true)->after('preferred_areas');
            $table->boolean('institute_classes')->default(true)->after('home_tuition');
            $table->boolean('online_classes')->default(true)->after('institute_classes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'teaching_city', 'teaching_state', 'teaching_pincode', 'teaching_area',
                'teaching_latitude', 'teaching_longitude', 'travel_radius_km', 'preferred_areas',
                'home_tuition', 'institute_classes', 'online_classes'
            ]);
        });
    }
}; 