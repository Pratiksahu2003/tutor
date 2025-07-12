<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TeacherProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'institute_id',
        'branch_id',
        'slug',
        'qualification',
        'qualifications',
        'bio',
        'experience_years',
        'hourly_rate',
        'monthly_rate',
        'specialization',
        'languages',
        'availability',
        'teaching_mode',
        'online_classes',
        'home_tuition',
        'institute_classes',
        'travel_radius_km',
        'city',
        'state',
        'teaching_city',
        'rating',
        'total_students',
        'total_reviews',
        'verified',
        'verification_status',
        'availability_status',
        'is_featured',
        'avatar',
        'teaching_preferences',
        'certifications',
    ];

    protected $casts = [
        'languages' => 'array',
        'teaching_preferences' => 'array',
        'certifications' => 'array',
        'rating' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
        'verified' => 'boolean',
        'is_featured' => 'boolean',
        'online_classes' => 'boolean',
        'home_tuition' => 'boolean',
        'institute_classes' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($teacher) {
            if (empty($teacher->slug) && $teacher->user) {
                $teacher->slug = \Illuminate\Support\Str::slug($teacher->user->name);
            }
        });

        static::updating(function ($teacher) {
            if ($teacher->isDirty('user_id') && empty($teacher->slug) && $teacher->user) {
                $teacher->slug = \Illuminate\Support\Str::slug($teacher->user->name);
            }
        });
    }

    /**
     * Get the user that owns the teacher profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the primary subject that the teacher teaches
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the institute that the teacher belongs to
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    /**
     * Get the specific branch that the teacher belongs to
     */
    public function branch()
    {
        return $this->belongsTo(Institute::class, 'branch_id');
    }

    /**
     * Get the subjects that the teacher teaches
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects', 'teacher_id', 'subject_id')
                    ->withPivot(['subject_rate', 'proficiency_level'])
                    ->withTimestamps();
    }

    /**
     * Get the reviews for this teacher
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Get the exams that the teacher prepares students for
     */
    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'teacher_exams')
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
     * Get the school branches that the teacher manages
     */
    public function schoolBranches()
    {
        return $this->hasMany(SchoolBranch::class, 'teacher_profile_id');
    }

    /**
     * Get the primary school branch
     */
    public function primarySchoolBranch()
    {
        return $this->hasOne(SchoolBranch::class, 'teacher_profile_id')
                    ->where('is_primary', true);
    }

    /**
     * Get active school branches
     */
    public function activeSchoolBranches()
    {
        return $this->hasMany(SchoolBranch::class, 'teacher_profile_id')
                    ->where('status', 'active');
    }

    /**
     * Get teacher's average rating
     */
    public function getAverageRatingAttribute()
    {
        return $this->rating;
    }

    /**
     * Check if teacher is verified by admin
     */
    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function isInstituteVerified()
    {
        return $this->verification_status === 'verified' && $this->institute_id !== null;
    }

    public function isBranchVerified()
    {
        return $this->verification_status === 'verified' && $this->branch_id !== null;
    }

    /**
     * Check if teacher works at an institute
     */
    public function hasInstitute(): bool
    {
        return !is_null($this->institute_id);
    }

    /**
     * Check if teacher works at a specific branch
     */
    public function hasBranch(): bool
    {
        return !is_null($this->branch_id);
    }

    /**
     * Check if teacher is freelance
     */
    public function isFreelance(): bool
    {
        return $this->employment_type === 'freelance' || (is_null($this->institute_id) && is_null($this->branch_id));
    }

    /**
     * Check if teacher works at institute level
     */
    public function isInstituteTeacher(): bool
    {
        return $this->hasInstitute() && !$this->hasBranch();
    }

    /**
     * Check if teacher works at branch level
     */
    public function isBranchTeacher(): bool
    {
        return $this->hasBranch();
    }

    /**
     * Get full employment status
     */
    public function getEmploymentStatusAttribute()
    {
        if ($this->hasBranch()) {
            $status = 'Branch Teacher';
            if ($this->branch_role !== 'teacher') {
                $status = ucwords(str_replace('_', ' ', $this->branch_role));
            }
            return $status;
        }
        
        if ($this->hasInstitute()) {
            return $this->employment_type === 'both' ? 'Institute & Freelance' : 'Institute';
        }
        
        return 'Freelance';
    }

    /**
     * Get workplace name (institute or branch)
     */
    public function getWorkplaceNameAttribute()
    {
        if ($this->hasBranch()) {
            return $this->branch->display_name;
        }
        
        if ($this->hasInstitute()) {
            return $this->institute->institute_name;
        }
        
        return 'Freelance';
    }

    /**
     * Get workplace hierarchy
     */
    public function getWorkplaceHierarchyAttribute()
    {
        if ($this->hasBranch()) {
            return $this->branch->hierarchy_breadcrumb;
        }
        
        if ($this->hasInstitute()) {
            return [
                [
                    'id' => $this->institute->id,
                    'name' => $this->institute->institute_name,
                    'type' => 'main'
                ]
            ];
        }
        
        return [];
    }

    /**
     * Check if teacher has specific permission at branch level
     */
    public function hasBranchPermission(string $permission): bool
    {
        if (!$this->hasBranch() || !$this->branch_permissions) {
            return false;
        }
        
        return in_array($permission, $this->branch_permissions);
    }

    /**
     * Check if teacher can manage other teachers in their branch
     */
    public function canManageBranchTeachers(): bool
    {
        return in_array($this->branch_role, ['head_teacher', 'branch_admin']) && $this->isBranchVerified();
    }

    /**
     * Check if teacher is a branch administrator
     */
    public function isBranchAdmin(): bool
    {
        return $this->branch_role === 'branch_admin' && $this->isBranchVerified();
    }

    /**
     * Check if teacher is head teacher of branch
     */
    public function isHeadTeacher(): bool
    {
        return $this->branch_role === 'head_teacher' && $this->isBranchVerified();
    }

    /**
     * Check if teacher is coordinator at branch
     */
    public function isCoordinator(): bool
    {
        return $this->branch_role === 'coordinator' && $this->isBranchVerified();
    }

    public function getVerificationStatusAttribute()
    {
        return [
            'verified' => $this->verified,
            'verification_status' => $this->verification_status,
            'fully_verified' => $this->verified && $this->verification_status === 'verified',
        ];
    }

    /**
     * Assign teacher to a branch
     */
    public function assignToBranch(Institute $branch, string $role = 'teacher', array $permissions = [])
    {
        if (!$branch->isBranch() && !$branch->isSubBranch()) {
            return false;
        }

        $this->update([
            'branch_id' => $branch->id,
            'institute_id' => $branch->getRootInstitute()->id,
            'branch_role' => $role,
            'branch_permissions' => $permissions,
            'employment_type' => $this->employment_type === 'freelance' ? 'institute' : $this->employment_type,
            'is_branch_verified' => false, // Requires verification
            'branch_joined_date' => now(),
        ]);

        return true;
    }

    /**
     * Remove teacher from branch
     */
    public function removeFromBranch()
    {
        $this->update([
            'branch_id' => null,
            'branch_role' => 'teacher',
            'is_branch_verified' => false,
            'branch_permissions' => null,
            'branch_notes' => null,
            'branch_joined_date' => null,
        ]);

        // If not associated with main institute, become freelance
        if (!$this->hasInstitute()) {
            $this->update(['employment_type' => 'freelance']);
        }
    }

    /**
     * Transfer teacher to another branch
     */
    public function transferToBranch(Institute $newBranch, string $role = 'teacher', array $permissions = [])
    {
        if (!$newBranch->isBranch() && !$newBranch->isSubBranch()) {
            return false;
        }

        $this->update([
            'branch_id' => $newBranch->id,
            'institute_id' => $newBranch->getRootInstitute()->id,
            'branch_role' => $role,
            'branch_permissions' => $permissions,
            'is_branch_verified' => false, // Requires re-verification
            'branch_joined_date' => now(),
        ]);

        return true;
    }

    /**
     * Get teachers that this teacher can manage (if they have management role)
     */
    public function getManagedTeachers()
    {
        if (!$this->canManageBranchTeachers()) {
            return collect();
        }

        $query = TeacherProfile::where('branch_id', $this->branch_id);
        
        // Branch admin can manage all teachers in branch
        if ($this->isBranchAdmin()) {
            return $query->where('id', '!=', $this->id)->get();
        }
        
        // Head teacher can manage teachers and coordinators
        if ($this->isHeadTeacher()) {
            return $query->whereIn('branch_role', ['teacher', 'coordinator'])
                        ->where('id', '!=', $this->id)->get();
        }
        
        // Coordinator can manage only teachers
        if ($this->isCoordinator()) {
            return $query->where('branch_role', 'teacher')
                        ->where('id', '!=', $this->id)->get();
        }

        return collect();
    }

    /**
     * Scope to filter by institute
     */
    public function scopeByInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    /**
     * Scope to filter by branch
     */
    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope to filter verified teachers
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    /**
     * Scope to filter institute verified teachers
     */
    public function scopeInstituteVerified($query)
    {
        return $query->where('verification_status', 'verified')->whereNotNull('institute_id');
    }

    /**
     * Scope to filter branch verified teachers
     */
    public function scopeBranchVerified($query)
    {
        return $query->where('verification_status', 'verified')->whereNotNull('branch_id');
    }

    /**
     * Scope to filter by branch role
     */
    public function scopeByBranchRole($query, $role)
    {
        return $query->where('branch_role', $role);
    }

    /**
     * Scope to filter freelance teachers
     */
    public function scopeFreelance($query)
    {
        return $query->whereNull('institute_id')->whereNull('branch_id');
    }
}
