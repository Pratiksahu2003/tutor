<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_name',
        'description',
        'exam_category_id',
        'conducting_body',
        'exam_type',
        'frequency',
        'eligibility',
        'exam_pattern',
        'syllabus',
        'preparation_tips',
        'official_website',
        'application_fee',
        'exam_date',
        'result_date',
        'application_start',
        'application_end',
        'age_limit',
        'educational_qualification',
        'total_marks',
        'duration_minutes',
        'negative_marking',
        'cutoff_marks',
        'status',
        'featured',
        'sort_order',
        'meta_title',
        'meta_description',
        'logo',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'result_date' => 'date',
        'application_start' => 'date',
        'application_end' => 'date',
        'featured' => 'boolean',
        'negative_marking' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($exam) {
            if (empty($exam->slug)) {
                $exam->slug = Str::slug($exam->name);
            }
        });

        static::updating(function ($exam) {
            if ($exam->isDirty('name') && empty($exam->slug)) {
                $exam->slug = Str::slug($exam->name);
            }
        });
    }

    /**
     * Get the exam category
     */
    public function category()
    {
        return $this->belongsTo(ExamCategory::class, 'exam_category_id');
    }

    /**
     * Get exam subjects
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'exam_subjects')
                   ->withPivot(['weightage', 'marks', 'is_optional'])
                   ->withTimestamps();
    }

    /**
     * Get teachers who prepare for this exam
     */
    public function teachers()
    {
        return $this->belongsToMany(TeacherProfile::class, 'teacher_exams', 'exam_id', 'teacher_profile_id')
                   ->withPivot([
                       'experience_years', 'success_rate', 'students_cleared', 
                       'average_score', 'teaching_methodology', 'study_materials',
                       'specialization_subjects', 'course_fee', 'course_duration_months',
                       'batch_size', 'teaching_mode', 'achievements', 'is_certified',
                       'certification_details', 'status'
                   ])
                   ->withTimestamps();
    }

    /**
     * Get institutes that offer preparation for this exam
     */
    public function institutes()
    {
        return $this->belongsToMany(Institute::class, 'institute_exams')
                   ->withPivot([
                       'course_name', 'course_duration_months', 'batch_size', 'success_rate',
                       'students_cleared', 'fee_range_min', 'fee_range_max', 'course_description',
                       'course_features', 'study_materials', 'faculty_details', 'teaching_mode',
                       'schedule_details', 'facilities', 'admission_requirements', 'course_start_date',
                       'course_end_date', 'admission_deadline', 'scholarship_available',
                       'scholarship_details', 'placement_assistance', 'achievements', 'status'
                   ])
                   ->withTimestamps();
    }

    /**
     * Get exam notifications
     */
    public function notifications()
    {
        return $this->hasMany(ExamNotification::class);
    }

    /**
     * Scope for active exams
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for featured exams
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope for upcoming exams
     */
    public function scopeUpcoming($query)
    {
        return $query->where('exam_date', '>=', now())
                    ->orderBy('exam_date', 'asc');
    }

    /**
     * Scope for exams by category
     */
    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    /**
     * Scope for exams by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('exam_type', $type);
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get exam type color for UI
     */
    public function getTypeColorAttribute()
    {
        return match($this->exam_type) {
            'national' => 'primary',
            'state' => 'success',
            'government' => 'info',
            'private' => 'warning',
            'school' => 'secondary',
            'university' => 'dark',
            default => 'secondary'
        };
    }

    /**
     * Get exam type display name
     */
    public function getTypeDisplayAttribute()
    {
        return match($this->exam_type) {
            'national' => 'National Level',
            'state' => 'State Level',
            'government' => 'Government',
            'private' => 'Private',
            'school' => 'School Level',
            'university' => 'University',
            default => 'Other'
        };
    }

    /**
     * Get exam frequency display
     */
    public function getFrequencyDisplayAttribute()
    {
        return match($this->frequency) {
            'yearly' => 'Once a Year',
            'twice_yearly' => 'Twice a Year',
            'quarterly' => 'Quarterly',
            'monthly' => 'Monthly',
            'ongoing' => 'Ongoing',
            default => 'As Scheduled'
        };
    }

    /**
     * Check if exam is upcoming
     */
    public function getIsUpcomingAttribute()
    {
        return $this->exam_date && $this->exam_date->isFuture();
    }

    /**
     * Check if application is open
     */
    public function getIsApplicationOpenAttribute()
    {
        if (!$this->application_start || !$this->application_end) {
            return false;
        }
        
        $now = now();
        return $now->between($this->application_start, $this->application_end);
    }

    /**
     * Get days until exam
     */
    public function getDaysUntilExamAttribute()
    {
        if (!$this->exam_date) {
            return null;
        }
        
        return now()->diffInDays($this->exam_date, false);
    }

    /**
     * Get exam URL
     */
    public function getUrlAttribute()
    {
        return route('exams.show', $this->slug);
    }

    /**
     * Get exam difficulty level
     */
    public function getDifficultyLevelAttribute()
    {
        // Calculate based on cutoff marks, competition ratio, etc.
        if ($this->cutoff_marks) {
            $percentage = ($this->cutoff_marks / $this->total_marks) * 100;
            
            if ($percentage >= 90) return 'Very Hard';
            if ($percentage >= 75) return 'Hard';
            if ($percentage >= 60) return 'Moderate';
            if ($percentage >= 45) return 'Easy';
            return 'Very Easy';
        }
        
        return 'Not Available';
    }

    /**
     * Get total teachers count for this exam
     */
    public function getTotalTeachersAttribute()
    {
        return $this->teachers()->count();
    }

    /**
     * Get total institutes count for this exam
     */
    public function getTotalInstitutesAttribute()
    {
        return $this->institutes()->count();
    }

    /**
     * Get related exams
     */
    public function getRelatedExams($limit = 5)
    {
        return static::where('id', '!=', $this->id)
                    ->where('exam_category_id', $this->exam_category_id)
                    ->orWhere('exam_type', $this->exam_type)
                    ->active()
                    ->orderBy('featured', 'desc')
                    ->orderBy('sort_order')
                    ->take($limit)
                    ->get();
    }

    /**
     * Get top teachers for this exam
     */
    public function getTopTeachers($limit = 5)
    {
        return $this->teachers()
                   ->where('status', 'active')
                   ->orderBy('teacher_exams.success_rate', 'desc')
                   ->orderBy('teacher_exams.students_cleared', 'desc')
                   ->take($limit)
                   ->get();
    }

    /**
     * Get top institutes for this exam
     */
    public function getTopInstitutes($limit = 5)
    {
        return $this->institutes()
                   ->where('status', 'active')
                   ->orderBy('institute_exams.success_rate', 'desc')
                   ->take($limit)
                   ->get();
    }

    /**
     * Get exam statistics
     */
    public function getStatsAttribute()
    {
        return [
            'total_teachers' => $this->total_teachers,
            'total_institutes' => $this->total_institutes,
            'avg_success_rate' => $this->teachers()->avg('teacher_exams.success_rate') ?: 0,
            'total_students_cleared' => $this->teachers()->sum('teacher_exams.students_cleared') ?: 0,
        ];
    }
} 