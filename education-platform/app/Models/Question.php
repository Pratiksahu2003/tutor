<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'question_text',
        'type',
        'difficulty',
        'marks',
        'time_limit',
        'options',
        'correct_answers',
        'explanation',
        'hint',
        'image',
        'solution_steps',
        'subject_id',
        'topic',
        'subtopic',
        'chapter',
        'class_level',
        'tags',
        'category',
        'source',
        'reference',
        'status',
        'usage_count',
        'success_rate',
        'average_time',
        'total_attempts',
        'correct_attempts',
        'created_by',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'is_public',
        'allow_comments',
        'likes',
        'dislikes',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
        'tags' => 'array',
        'reviewed_at' => 'datetime',
        'is_public' => 'boolean',
        'allow_comments' => 'boolean',
        'success_rate' => 'decimal:2',
        'average_time' => 'decimal:2',
    ];

    /**
     * Get the subject that owns the question
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the user who created the question
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who reviewed the question
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope for published questions
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for draft questions
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for public questions
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for questions by difficulty
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    /**
     * Scope for questions by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for questions by class level
     */
    public function scopeByClassLevel($query, $classLevel)
    {
        return $query->where('class_level', $classLevel);
    }

    /**
     * Scope for FAQ questions
     */
    public function scopeFaq($query)
    {
        return $query->where(function($q) {
            $q->where('category', 'quiz')
              ->orWhereJsonContains('tags', 'faq')
              ->orWhere('topic', 'like', '%faq%');
        });
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'published' => 'success',
            'draft' => 'warning',
            'under_review' => 'info',
            'archived' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get difficulty color for UI
     */
    public function getDifficultyColorAttribute()
    {
        return match($this->difficulty) {
            'easy' => 'success',
            'medium' => 'warning',
            'hard' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get type display name
     */
    public function getTypeDisplayAttribute()
    {
        return match($this->type) {
            'multiple_choice' => 'Multiple Choice',
            'true_false' => 'True/False',
            'short_answer' => 'Short Answer',
            'long_answer' => 'Long Answer',
            'fill_blank' => 'Fill in the Blank',
            'matching' => 'Matching',
            default => ucfirst($this->type)
        };
    }

    /**
     * Calculate success rate based on attempts
     */
    public function calculateSuccessRate()
    {
        if ($this->total_attempts > 0) {
            $this->success_rate = ($this->correct_attempts / $this->total_attempts) * 100;
            $this->save();
        }
        return $this->success_rate;
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    /**
     * Record an attempt
     */
    public function recordAttempt($isCorrect = false, $timeTaken = null)
    {
        $this->increment('total_attempts');
        
        if ($isCorrect) {
            $this->increment('correct_attempts');
        }
        
        if ($timeTaken) {
            // Calculate new average time
            $totalTime = ($this->average_time * ($this->total_attempts - 1)) + $timeTaken;
            $this->average_time = $totalTime / $this->total_attempts;
        }
        
        $this->calculateSuccessRate();
        $this->save();
    }

    /**
     * Publish the question
     */
    public function publish()
    {
        $this->update(['status' => 'published']);
        return $this;
    }

    /**
     * Archive the question
     */
    public function archive()
    {
        $this->update(['status' => 'archived']);
        return $this;
    }

    /**
     * Mark for review
     */
    public function markForReview($notes = null)
    {
        $this->update([
            'status' => 'under_review',
            'review_notes' => $notes
        ]);
        return $this;
    }

    /**
     * Complete review
     */
    public function completeReview($reviewerId, $approved = true, $notes = null)
    {
        $this->update([
            'status' => $approved ? 'published' : 'draft',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'review_notes' => $notes
        ]);
        return $this;
    }

    /**
     * Check if question is answerable (has correct answers)
     */
    public function isAnswerable()
    {
        return !empty($this->correct_answers) && 
               in_array($this->type, ['multiple_choice', 'true_false', 'fill_blank']);
    }

    /**
     * Check if given answer is correct
     */
    public function isCorrectAnswer($userAnswer)
    {
        if (!$this->isAnswerable()) {
            return null; // Cannot auto-grade
        }

        $correctAnswers = $this->correct_answers;
        
        if (is_array($userAnswer)) {
            // For multiple selection questions
            sort($userAnswer);
            sort($correctAnswers);
            return $userAnswer === $correctAnswers;
        }
        
        // For single answer questions
        return in_array($userAnswer, $correctAnswers);
    }

    /**
     * Get random questions for a quiz
     */
    public static function getRandomQuestions($filters = [], $limit = 10)
    {
        $query = self::published()->public();
        
        if (isset($filters['subject_id'])) {
            $query->where('subject_id', $filters['subject_id']);
        }
        
        if (isset($filters['difficulty'])) {
            $query->where('difficulty', $filters['difficulty']);
        }
        
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        if (isset($filters['class_level'])) {
            $query->where('class_level', $filters['class_level']);
        }
        
        if (isset($filters['board'])) {
            $query->where('board', $filters['board']);
        }
        
        return $query->inRandomOrder()->limit($limit)->get();
    }
}
