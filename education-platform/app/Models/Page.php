<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'meta_title',
        'meta_description',
        'featured_image',
        'status',
        'type',
        'sort_order',
        'show_in_menu',
        'seo_data',
        'author_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'seo_data' => 'array',
        'show_in_menu' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
            
            if (empty($page->meta_title)) {
                $page->meta_title = $page->title;
            }
        });

        static::updating(function ($page) {
            if ($page->isDirty('title') && empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    /**
     * Get the author of the page
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get parent page
     */
    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    /**
     * Get child pages
     */
    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Scope for published pages
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope for draft pages
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for pages that show in menu
     */
    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true);
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
            'private' => 'info',
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
            'private' => 'Private',
            'trash' => 'Trash',
            default => 'Unknown'
        };
    }

    /**
     * Get full URL for the page
     */
    public function getUrlAttribute()
    {
        if ($this->is_homepage) {
            return route('home');
        }

        return route('page.show', $this->slug);
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
     * Get reading time estimate
     */
    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200); // Average reading speed
        
        return $minutes . ' min read';
    }

    /**
     * Check if page is editable by user
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

        // Admin can edit most pages
        if ($user->hasRole('admin')) {
            return true;
        }

        // Author can edit their own pages
        if ($this->author_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Publish the page
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
     * Unpublish the page
     */
    public function unpublish()
    {
        $this->update([
            'status' => 'draft',
        ]);

        return $this;
    }

    /**
     * Duplicate the page
     */
    public function duplicate()
    {
        $newPage = $this->replicate();
        $newPage->title = $this->title . ' (Copy)';
        $newPage->slug = Str::slug($newPage->title);
        $newPage->status = 'draft';
        $newPage->published_at = null;
        $newPage->is_homepage = false;
        $newPage->save();

        return $newPage;
    }

    /**
     * Get breadcrumb trail
     */
    public function getBreadcrumbAttribute()
    {
        $breadcrumb = collect([$this]);
        $parent = $this->parent;

        while ($parent) {
            $breadcrumb->prepend($parent);
            $parent = $parent->parent;
        }

        return $breadcrumb;
    }
}
