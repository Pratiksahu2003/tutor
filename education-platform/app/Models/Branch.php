<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'name',
        'code',
        'address',
        'city',
        'state',
        'pincode',
        'phone',
        'email',
        'manager',
        'capacity',
        'status',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    /**
     * Get the institute that owns the branch
     */
    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    /**
     * Get the teachers for this branch
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(TeacherProfile::class, 'branch_teacher');
    }

    /**
     * Get the students for this branch
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'branch_student');
    }

    /**
     * Get the subjects offered at this branch
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'branch_subject');
    }

    /**
     * Get the reviews for this branch
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the payments for this branch
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope a query to only include active branches
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the full address
     */
    public function getFullAddressAttribute(): string
    {
        return "{$this->address}, {$this->city}, {$this->state} - {$this->pincode}";
    }

    /**
     * Get the student count
     */
    public function getStudentsCountAttribute(): int
    {
        return $this->students()->count();
    }

    /**
     * Get the teacher count
     */
    public function getTeachersCountAttribute(): int
    {
        return $this->teachers()->count();
    }

    /**
     * Get the sessions count
     */
    public function getSessionsCountAttribute(): int
    {
        return 0;
    }

    /**
     * Get the revenue
     */
    public function getRevenueAttribute(): float
    {
        return $this->payments()->sum('amount');
    }

    /**
     * Get the average rating
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
} 