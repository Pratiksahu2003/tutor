<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'city',
        'state',
        'pincode',
        'country',
        'latitude',
        'longitude',
        'profile_image',
        'is_active',
        'preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'array',
            'is_active' => 'boolean',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * Get the teacher profile associated with the user
     */
    public function teacherProfile()
    {
        return $this->hasOne(TeacherProfile::class);
    }

    /**
     * Get the institute profile associated with the user
     */
    public function institute()
    {
        return $this->hasOne(Institute::class);
    }

    /**
     * Get the student profile associated with the user
     */
    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    /**
     * Get the roles for the user
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')->withTimestamps();
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('slug', $roleName)->exists();
    }

    /**
     * Check if user is a student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student' || $this->hasRole('student');
    }

    /**
     * Check if user is a teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher' || $this->hasRole('teacher');
    }

    /**
     * Check if user is an institute
     */
    public function isInstitute(): bool
    {
        return $this->role === 'institute' || $this->hasRole('institute');
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin') || $this->hasRole('super-admin');
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // Super admins have all permissions
        if ($this->hasRole('super-admin')) {
            return true;
        }

        return $this->getAllPermissions()->contains('slug', $permissionSlug);
    }

    /**
     * Get all permissions for the user through their roles
     */
    public function getAllPermissions()
    {
        return $this->roles->load('permissions')
            ->pluck('permissions')
            ->flatten()
            ->unique('id');
    }

    /**
     * Assign a role to the user
     */
    public function assignRole(Role $role): bool
    {
        if (!$this->hasRole($role->slug)) {
            $this->roles()->attach($role->id, [
                'assigned_at' => now(),
                'assigned_by' => auth()->id(),
            ]);
            return true;
        }
        return false;
    }

    /**
     * Remove a role from the user
     */
    public function removeRole(Role $role): bool
    {
        if ($this->hasRole($role->slug)) {
            $this->roles()->detach($role->id);
            return true;
        }
        return false;
    }

    /**
     * Sync user roles
     */
    public function syncRoles(array $roleIds): void
    {
        $syncData = [];
        foreach ($roleIds as $roleId) {
            $syncData[$roleId] = [
                'assigned_at' => now(),
                'assigned_by' => auth()->id(),
            ];
        }
        $this->roles()->sync($syncData);
    }

    /**
     * Get user's primary role for display
     */
    public function getPrimaryRoleAttribute(): string
    {
        return ucfirst($this->role);
    }

    /**
     * Scope to filter active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}
