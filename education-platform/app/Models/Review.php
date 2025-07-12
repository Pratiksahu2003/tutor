<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewable_type',
        'reviewable_id',
        'rating',
        'comment',
        'title',
        'tags',
        'is_verified',
        'is_helpful',
        'helpful_count',
        'status',
        'reviewed_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_verified' => 'boolean',
        'is_helpful' => 'boolean',
        'rating' => 'integer',
        'helpful_count' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the user who wrote the review
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reviewable entity (teacher, institute, branch)
     */
    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope for published reviews
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for verified reviews
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for helpful reviews
     */
    public function scopeHelpful($query)
    {
        return $query->where('is_helpful', true);
    }

    /**
     * Get the average rating for a reviewable entity
     */
    public static function getAverageRating($reviewable)
    {
        return static::where('reviewable_type', get_class($reviewable))
            ->where('reviewable_id', $reviewable->id)
            ->where('status', 'published')
            ->avg('rating') ?? 0;
    }

    /**
     * Get the total reviews count for a reviewable entity
     */
    public static function getReviewsCount($reviewable)
    {
        return static::where('reviewable_type', get_class($reviewable))
            ->where('reviewable_id', $reviewable->id)
            ->where('status', 'published')
            ->count();
    }

    /**
     * Check if user has already reviewed this entity
     */
    public static function hasUserReviewed($user, $reviewable)
    {
        return static::where('user_id', $user->id)
            ->where('reviewable_type', get_class($reviewable))
            ->where('reviewable_id', $reviewable->id)
            ->exists();
    }
}
