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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            
            // Basic Post Information
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('post_type')->default('post'); // post, article, news, tutorial, etc.
            
            // SEO and Meta
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            
            // Post Settings
            $table->enum('status', ['draft', 'published', 'private', 'scheduled'])->default('draft');
            $table->boolean('featured')->default(false);
            $table->boolean('sticky')->default(false);
            $table->integer('reading_time')->nullable(); // in minutes
            
            // Media and Assets
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->string('video_url')->nullable();
            $table->json('attachments')->nullable();
            
            // Content Structure
            $table->json('table_of_contents')->nullable();
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            
            // Categorization and Tagging
            $table->json('categories')->nullable(); // Array of category IDs
            $table->json('tags')->nullable(); // Array of tags
            $table->string('difficulty_level')->nullable(); // beginner, intermediate, advanced
            $table->json('subjects_related')->nullable(); // Related to education subjects
            
            // Scheduling and Visibility
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('visibility_rules')->nullable(); // User role based visibility
            
            // Engagement and Analytics
            $table->integer('view_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->integer('share_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->decimal('average_rating', 3, 2)->nullable();
            $table->integer('rating_count')->default(0);
            
            // Social and Sharing
            $table->boolean('allow_comments')->default(true);
            $table->boolean('allow_ratings')->default(true);
            $table->boolean('allow_sharing')->default(true);
            $table->json('social_sharing_data')->nullable();
            
            // Author and Management
            $table->foreignId('author_id')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->json('co_authors')->nullable(); // Additional authors
            
            // Educational Content Specific
            $table->string('target_audience')->nullable(); // students, teachers, parents, etc.
            $table->string('education_level')->nullable(); // primary, secondary, higher, etc.
            $table->boolean('is_premium')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            
            // Newsletter and Email
            $table->boolean('send_newsletter')->default(false);
            $table->boolean('newsletter_sent')->default(false);
            $table->timestamp('newsletter_sent_at')->nullable();
            
            // Related Content
            $table->json('related_posts')->nullable(); // Related post IDs
            $table->json('related_teachers')->nullable(); // Related teacher IDs
            $table->json('related_institutes')->nullable(); // Related institute IDs
            
            // Performance and SEO
            $table->integer('word_count')->nullable();
            $table->decimal('seo_score', 3, 1)->nullable(); // SEO optimization score
            $table->json('seo_analysis')->nullable(); // SEO analysis data
            
            // Technical Settings
            $table->boolean('is_active')->default(true);
            $table->string('language')->default('en');
            $table->json('translations')->nullable(); // Multi-language support
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['status', 'published_at']);
            $table->index(['slug']);
            $table->index(['post_type', 'status']);
            $table->index(['featured', 'published_at']);
            $table->index(['author_id', 'status']);
            $table->index(['view_count']);
            $table->index(['like_count']);
            $table->index(['sticky', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
