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
            $table->foreignId('branch_id')->nullable()->constrained('institutes')->onDelete('set null');
            $table->enum('branch_role', ['teacher', 'coordinator', 'head_teacher', 'branch_admin'])->default('teacher');
            $table->boolean('is_branch_verified')->default(false); // Verified by branch manager
            $table->json('branch_permissions')->nullable(); // Specific permissions in this branch
            $table->text('branch_notes')->nullable(); // Notes about teacher in this branch
            $table->timestamp('branch_joined_date')->nullable();
            
            // Add index for performance
            $table->index(['branch_id', 'branch_role']);
            $table->index(['is_branch_verified']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropIndex(['branch_id', 'branch_role']);
            $table->dropIndex(['is_branch_verified']);
            
            $table->dropColumn([
                'branch_id', 'branch_role', 'is_branch_verified', 
                'branch_permissions', 'branch_notes', 'branch_joined_date'
            ]);
        });
    }
};
