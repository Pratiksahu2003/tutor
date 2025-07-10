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
        Schema::table('subjects', function (Blueprint $table) {
            // Add new fields for competitive exam support
            $table->string('short_name')->nullable()->after('slug');
            $table->enum('category', [
                'science', 'mathematics', 'language', 'social_science', 'computer_science', 
                'engineering', 'medical', 'commerce', 'arts', 'law', 'management',
                'general_knowledge', 'current_affairs', 'reasoning', 'quantitative_aptitude'
            ])->default('general_knowledge')->change();
            $table->enum('level', [
                'primary', 'secondary', 'higher_secondary', 'undergraduate', 
                'postgraduate', 'competitive', 'professional'
            ])->default('competitive')->after('category');
            $table->string('color', 7)->default('#28a745')->after('icon');
            $table->integer('sort_order')->default(0)->after('color');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('sort_order');
            $table->string('meta_title')->nullable()->after('status');
            $table->text('meta_description')->nullable()->after('meta_title');
            
            // Rename and update existing fields
            $table->renameColumn('grade_level', 'level_old');
            $table->renameColumn('is_active', 'status_old');
            
            // Add indexes
            $table->index(['status', 'category']);
            $table->index(['level', 'status']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Drop new fields
            $table->dropColumn([
                'short_name', 'level', 'color', 'sort_order', 'status', 
                'meta_title', 'meta_description'
            ]);
            
            // Restore old fields
            $table->renameColumn('level_old', 'grade_level');
            $table->renameColumn('status_old', 'is_active');
            
            // Drop indexes
            $table->dropIndex(['status', 'category']);
            $table->dropIndex(['level', 'status']);
            $table->dropIndex(['sort_order']);
        });
    }
};
