<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'featured_image',
        'author_id',
        'published_at',
        'featured',
        'categories',
        'tags',
        'views',
        'reading_time',
        'allow_comments',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'allow_comments' => 'boolean',
        'categories' => 'array',
        'tags' => 'array',
        'views' => 'integer',
        'reading_time' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            
            if (empty($post->meta_title)) {
                $post->meta_title = $post->title;
            }
            
            if (empty($post->reading_time)) {
                $post->reading_time = $post->calculateReadingTime();
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            
            if ($post->isDirty('content')) {
                $post->reading_time = $post->calculateReadingTime();
            }
        });
    }

    /**
     * Get the author of the post
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get comments for the post
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    /**
     * Scope for published posts
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope for draft posts
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for featured posts
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope for posts by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->whereJsonContains('categories', $category);
    }

    /**
     * Scope for posts by tag
     */
    public function scopeByTag($query, $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'published' => 'success',
            'draft' => 'warning',
            'scheduled' => 'info',
            'private' => 'secondary',
            'trash' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'published' => 'Published',
            'draft' => 'Draft',
            'scheduled' => 'Scheduled',
            'private' => 'Private',
            'trash' => 'Trash',
            default => 'Unknown'
        };
    }

    /**
     * Get full URL for the post
     */
    public function getUrlAttribute()
    {
        return route('blog.show', $this->slug);
    }

    /**
     * Get excerpt or truncated content
     */
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return Str::limit(strip_tags($this->content), 160);
    }

    /**
     * Calculate reading time based on content
     */
    public function calculateReadingTime()
    {
        $words = str_word_count(strip_tags($this->content));
        return ceil($words / 200); // Average reading speed: 200 words per minute
    }

    /**
     * Get reading time display
     */
    public function getReadingTimeDisplayAttribute()
    {
        $time = $this->reading_time ?: $this->calculateReadingTime();
        return $time . ' min read';
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views');
        return $this;
    }

    /**
     * Check if post is editable by user
     */
    public function isEditableBy($user)
    {
        if (!$user) {
            return false;
        }

        // Super admin can edit everything
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Admin can edit most posts
        if ($user->hasRole('admin')) {
            return true;
        }

        // Author can edit their own posts
        if ($this->author_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Publish the post
     */
    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return $this;
    }

    /**
     * Schedule the post for later
     */
    public function schedule($publishAt)
    {
        $this->update([
            'status' => 'scheduled',
            'published_at' => $publishAt,
        ]);

        return $this;
    }

    /**
     * Unpublish the post
     */
    public function unpublish()
    {
        $this->update([
            'status' => 'draft',
        ]);

        return $this;
    }

    /**
     * Feature the post
     */
    public function feature()
    {
        $this->update(['featured' => true]);
        return $this;
    }

    /**
     * Unfeature the post
     */
    public function unfeature()
    {
        $this->update(['featured' => false]);
        return $this;
    }

    /**
     * Duplicate the post
     */
    public function duplicate()
    {
        $newPost = $this->replicate();
        $newPost->title = $this->title . ' (Copy)';
        $newPost->slug = Str::slug($newPost->title);
        $newPost->status = 'draft';
        $newPost->published_at = null;
        $newPost->featured = false;
        $newPost->views = 0;
        $newPost->save();

        return $newPost;
    }

    /**
     * Get related posts
     */
    public function getRelatedPosts($limit = 3)
    {
        return static::published()
                    ->where('id', '!=', $this->id)
                    ->where(function ($query) {
                        // Match by categories
                        if ($this->categories) {
                            foreach ($this->categories as $category) {
                                $query->orWhereJsonContains('categories', $category);
                            }
                        }
                        
                        // Match by tags
                        if ($this->tags) {
                            foreach ($this->tags as $tag) {
                                $query->orWhereJsonContains('tags', $tag);
                            }
                        }
                    })
                    ->orderBy('published_at', 'desc')
                    ->take($limit)
                    ->get();
    }

    /**
     * Get next post
     */
    public function getNextPost()
    {
        return static::published()
                    ->where('published_at', '>', $this->published_at)
                    ->orderBy('published_at', 'asc')
                    ->first();
    }

    /**
     * Get previous post
     */
    public function getPreviousPost()
    {
        return static::published()
                    ->where('published_at', '<', $this->published_at)
                    ->orderBy('published_at', 'desc')
                    ->first();
    }

    /**
     * Get all unique categories
     */
    public static function getAllCategories()
    {
        return static::published()
                    ->whereNotNull('categories')
                    ->get()
                    ->pluck('categories')
                    ->flatten()
                    ->unique()
                    ->sort()
                    ->values();
    }

    /**
     * Get all unique tags
     */
    public static function getAllTags()
    {
        return static::published()
                    ->whereNotNull('tags')
                    ->get()
                    ->pluck('tags')
                    ->flatten()
                    ->unique()
                    ->sort()
                    ->values();
    }
}
