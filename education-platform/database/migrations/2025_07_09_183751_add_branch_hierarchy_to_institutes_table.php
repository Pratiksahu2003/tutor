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
            // Branch hierarchy fields
            $table->foreignId('parent_institute_id')->nullable()->constrained('institutes')->onDelete('cascade');
            $table->enum('institute_type', ['main', 'branch', 'sub_branch'])->default('main');
            $table->string('branch_name')->nullable(); // Specific branch name
            $table->string('branch_code')->nullable()->unique(); // Unique branch identifier
            $table->text('branch_description')->nullable();
            
            // Management and operational fields
            $table->foreignId('branch_manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('operating_hours')->nullable(); // Store operating hours for each day
            $table->decimal('branch_area_sqft', 10, 2)->nullable(); // Branch area in square feet
            $table->integer('max_students_capacity')->nullable();
            $table->boolean('is_active_branch')->default(true);
            
            // Location and contact specific to branch
            $table->string('branch_phone')->nullable();
            $table->string('branch_email')->nullable();
            $table->text('branch_address')->nullable(); // Can be different from main address
            $table->string('branch_city')->nullable();
            $table->string('branch_state')->nullable();
            $table->string('branch_pincode')->nullable();
            $table->decimal('branch_latitude', 10, 8)->nullable();
            $table->decimal('branch_longitude', 11, 8)->nullable();
            
            // Hierarchical data
            $table->integer('level')->default(0); // 0=main, 1=branch, 2=sub_branch
            $table->string('hierarchy_path')->nullable(); // Store full path like "/1/5/12"
            $table->integer('total_branches')->default(0); // Number of direct child branches
            $table->integer('total_sub_branches')->default(0); // Number of all descendant branches
            
            // Performance and tracking
            $table->integer('branch_order')->default(0); // Display order within parent
            $table->timestamp('branch_established_date')->nullable();
            $table->json('branch_facilities')->nullable(); // Branch-specific facilities
            $table->json('branch_gallery_images')->nullable(); // Branch-specific gallery
            
            // Add indexes for performance
            $table->index(['parent_institute_id', 'institute_type']);
            $table->index(['hierarchy_path']);
            $table->index(['is_active_branch']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->dropForeign(['parent_institute_id']);
            $table->dropForeign(['branch_manager_id']);
            $table->dropIndex(['parent_institute_id', 'institute_type']);
            $table->dropIndex(['hierarchy_path']);
            $table->dropIndex(['is_active_branch']);
            
            $table->dropColumn([
                'parent_institute_id', 'institute_type', 'branch_name', 'branch_code', 'branch_description',
                'branch_manager_id', 'operating_hours', 'branch_area_sqft', 'max_students_capacity', 'is_active_branch',
                'branch_phone', 'branch_email', 'branch_address', 'branch_city', 'branch_state', 'branch_pincode',
                'branch_latitude', 'branch_longitude', 'level', 'hierarchy_path', 'total_branches', 'total_sub_branches',
                'branch_order', 'branch_established_date', 'branch_facilities', 'branch_gallery_images'
            ]);
        });
    }
};
