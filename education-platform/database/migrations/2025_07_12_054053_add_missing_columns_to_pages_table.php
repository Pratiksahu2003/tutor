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
        Schema::table('pages', function (Blueprint $table) {
            // Add missing columns that the Page model expects
            $table->string('page_type')->nullable()->after('content');
            $table->text('excerpt')->nullable()->after('page_type');
            $table->string('meta_title')->nullable()->after('excerpt');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('featured_image')->nullable()->after('meta_keywords');
            $table->enum('status', ['draft', 'published', 'private', 'password_protected'])->default('draft')->after('featured_image');
            $table->string('password')->nullable()->after('status');
            $table->boolean('featured')->default(false)->after('password');
            $table->boolean('show_in_menu')->default(false)->after('featured');
            $table->integer('menu_order')->default(0)->after('show_in_menu');
            $table->string('template')->nullable()->after('menu_order');
            $table->timestamp('published_at')->nullable()->after('template');
            $table->timestamp('expires_at')->nullable()->after('published_at');
            $table->boolean('allow_comments')->default(false)->after('expires_at');
            $table->boolean('allow_ratings')->default(false)->after('allow_comments');
            $table->boolean('is_homepage')->default(false)->after('allow_ratings');
            $table->text('custom_css')->nullable()->after('is_homepage');
            $table->text('custom_js')->nullable()->after('custom_css');
            $table->unsignedBigInteger('author_id')->nullable()->after('custom_js');
            $table->unsignedBigInteger('updated_by')->nullable()->after('author_id');
            $table->json('seo_data')->nullable()->after('updated_by');
            
            // Add foreign key constraints
            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['author_id']);
            $table->dropForeign(['updated_by']);
            
            // Drop columns
            $table->dropColumn([
                'page_type', 'excerpt', 'meta_title', 'meta_description', 'meta_keywords',
                'featured_image', 'status', 'password', 'featured', 'show_in_menu', 'menu_order',
                'template', 'published_at', 'expires_at', 'allow_comments', 'allow_ratings',
                'is_homepage', 'custom_css', 'custom_js', 'author_id', 'updated_by', 'seo_data'
            ]);
        });
    }
};
