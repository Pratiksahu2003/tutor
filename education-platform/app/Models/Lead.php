<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'source', 'campaign', 'medium', 'referrer_url', 'utm_parameters',
        'lead_type', 'target_teacher_id', 'target_institute_id', 'target_branch_id',
        'first_name', 'last_name', 'email', 'phone', 'whatsapp_number', 'date_of_birth', 'gender',
        'address', 'city', 'state', 'pincode', 'country', 'latitude', 'longitude',
        'current_education_level', 'institution_name', 'subjects_interested', 'learning_mode', 'class_type',
        'message', 'specific_requirements', 'preferred_language', 'preferred_timing', 'budget_min', 'budget_max', 'urgency',
        'status', 'priority', 'assigned_to', 'contacted_at', 'last_follow_up', 'next_follow_up', 'follow_up_count',
        'is_converted', 'converted_at', 'conversion_type', 'conversion_value', 'conversion_notes',
        'lead_score', 'is_verified', 'is_spam', 'verification_data',
        'email_consent', 'sms_consent', 'whatsapp_consent', 'call_consent', 'communication_history',
        'ip_address', 'user_agent', 'device_type', 'browser', 'form_data', 'custom_fields',
        'first_contact', 'last_activity', 'notes', 'tags', 'is_active'
    ];

    protected $casts = [
        'utm_parameters' => 'array',
        'subjects_interested' => 'array',
        'preferred_timing' => 'array',
        'verification_data' => 'array',
        'communication_history' => 'array',
        'form_data' => 'array',
        'custom_fields' => 'array',
        'tags' => 'array',
        'date_of_birth' => 'date',
        'contacted_at' => 'datetime',
        'last_follow_up' => 'datetime',
        'next_follow_up' => 'datetime',
        'converted_at' => 'datetime',
        'first_contact' => 'datetime',
        'last_activity' => 'datetime',
        'email_consent' => 'boolean',
        'sms_consent' => 'boolean',
        'whatsapp_consent' => 'boolean',
        'call_consent' => 'boolean',
        'is_converted' => 'boolean',
        'is_verified' => 'boolean',
        'is_spam' => 'boolean',
        'is_active' => 'boolean',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'conversion_value' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function targetTeacher()
    {
        return $this->belongsTo(TeacherProfile::class, 'target_teacher_id');
    }

    public function targetInstitute()
    {
        return $this->belongsTo(Institute::class, 'target_institute_id');
    }

    public function targetBranch()
    {
        return $this->belongsTo(Institute::class, 'target_branch_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->pincode,
            $this->country
        ]);
        return implode(', ', $parts);
    }

    public function getBudgetRangeAttribute()
    {
        if ($this->budget_min && $this->budget_max) {
            return '₹' . number_format($this->budget_min) . ' - ₹' . number_format($this->budget_max);
        } elseif ($this->budget_min) {
            return '₹' . number_format($this->budget_min) . '+';
        } elseif ($this->budget_max) {
            return 'Up to ₹' . number_format($this->budget_max);
        }
        return 'Not specified';
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'new' => 'primary',
            'contacted' => 'info',
            'qualified' => 'warning',
            'converted' => 'success',
            'lost' => 'danger',
            'invalid' => 'secondary',
            default => 'light'
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'urgent' => 'dark',
            default => 'light'
        };
    }

    // Scopes
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified(Builder $query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeNotSpam(Builder $query)
    {
        return $query->where('is_spam', false);
    }

    public function scopeByStatus(Builder $query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByLeadType(Builder $query, $type)
    {
        return $query->where('lead_type', $type);
    }

    public function scopeByPriority(Builder $query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeConverted(Builder $query)
    {
        return $query->where('is_converted', true);
    }

    public function scopeNotConverted(Builder $query)
    {
        return $query->where('is_converted', false);
    }

    public function scopeAssignedTo(Builder $query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeUnassigned(Builder $query)
    {
        return $query->whereNull('assigned_to');
    }

    public function scopeRequiresFollowUp(Builder $query)
    {
        return $query->where('next_follow_up', '<=', now());
    }

    public function scopeHighValue(Builder $query, $threshold = 5000)
    {
        return $query->where(function($q) use ($threshold) {
            $q->where('budget_max', '>=', $threshold)
              ->orWhere('conversion_value', '>=', $threshold);
        });
    }

    public function scopeRecent(Builder $query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByLocation(Builder $query, $city = null, $state = null)
    {
        if ($city) {
            $query->where('city', 'like', "%{$city}%");
        }
        if ($state) {
            $query->where('state', 'like', "%{$state}%");
        }
        return $query;
    }

    // Methods
    public function calculateLeadScore()
    {
        $score = 0;
        
        // Budget scoring (0-30 points)
        if ($this->budget_max >= 10000) $score += 30;
        elseif ($this->budget_max >= 5000) $score += 20;
        elseif ($this->budget_max >= 2000) $score += 10;
        
        // Urgency scoring (0-20 points)
        $score += match($this->urgency) {
            'immediate' => 20,
            'within_week' => 15,
            'within_month' => 10,
            'flexible' => 5,
            default => 0
        };
        
        // Engagement scoring (0-25 points)
        if ($this->phone) $score += 10;
        if ($this->whatsapp_number) $score += 5;
        if ($this->specific_requirements) $score += 10;
        
        // Source quality (0-15 points)
        $score += match($this->source) {
            'referral' => 15,
            'website' => 10,
            'social' => 8,
            'ads' => 6,
            default => 5
        };
        
        // Completeness (0-10 points)
        $completeness = 0;
        if ($this->address) $completeness += 2;
        if ($this->current_education_level) $completeness += 2;
        if ($this->preferred_timing) $completeness += 2;
        if ($this->learning_mode) $completeness += 2;
        if ($this->class_type) $completeness += 2;
        $score += $completeness;
        
        $this->update(['lead_score' => min(100, $score)]);
        return $score;
    }

    public function markAsContacted()
    {
        $this->update([
            'status' => 'contacted',
            'contacted_at' => now(),
            'first_contact' => $this->first_contact ?: now(),
            'last_activity' => now(),
            'follow_up_count' => $this->follow_up_count + 1
        ]);
    }

    public function convert($type = null, $value = null, $notes = null)
    {
        $this->update([
            'is_converted' => true,
            'converted_at' => now(),
            'conversion_type' => $type,
            'conversion_value' => $value,
            'conversion_notes' => $notes,
            'status' => 'converted',
            'last_activity' => now()
        ]);
    }

    public function addCommunication($type, $content, $direction = 'outbound')
    {
        $history = $this->communication_history ?? [];
        $history[] = [
            'type' => $type, // email, call, sms, whatsapp
            'content' => $content,
            'direction' => $direction, // inbound, outbound
            'timestamp' => now()->toISOString(),
            'user_id' => Auth::id()
        ];
        
        $this->update([
            'communication_history' => $history,
            'last_activity' => now()
        ]);
    }

    public function scheduleFollowUp($date, $notes = null)
    {
        $this->update([
            'next_follow_up' => $date,
            'last_follow_up' => now(),
            'notes' => $this->notes . "\n" . now()->format('Y-m-d H:i') . ": " . $notes
        ]);
    }

    public function assignTo($userId)
    {
        $this->update(['assigned_to' => $userId]);
    }

    public function markAsSpam()
    {
        $this->update([
            'is_spam' => true,
            'status' => 'invalid',
            'is_active' => false
        ]);
    }

    public function getDaysOldAttribute()
    {
        return $this->created_at->diffInDays(now());
    }

    public function getTimeToConversionAttribute()
    {
        if ($this->is_converted && $this->converted_at) {
            return $this->created_at->diffInDays($this->converted_at);
        }
        return null;
    }
}
