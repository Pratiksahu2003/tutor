<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class QuestionManagementController extends Controller
{
    /**
     * Display a listing of questions
     */
    public function index(Request $request)
    {
        $query = Question::with(['subject', 'creator']);

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // Filter by class level
        if ($request->filled('class_level')) {
            $query->where('class_level', $request->class_level);
        }

        // Filter by board
        if ($request->filled('board')) {
            $query->where('board', $request->board);
        }

        // Filter by creator
        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('question_text', 'like', "%{$search}%")
                  ->orWhere('topic', 'like', "%{$search}%")
                  ->orWhere('chapter', 'like', "%{$search}%");
            });
        }

        $questions = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $subjects = Subject::active()->get();
        $creators = User::whereIn('role', ['admin', 'teacher'])->get();
        $boards = Question::distinct()->pluck('board')->filter()->sort();
        $classLevels = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', 'undergraduate', 'postgraduate'];

        return view('admin.questions.index', compact(
            'questions',
            'subjects',
            'creators',
            'boards',
            'classLevels'
        ));
    }

    /**
     * Show the form for creating a new question
     */
    public function create()
    {
        $subjects = Subject::active()->get();
        $questionTypes = [
            'multiple_choice' => 'Multiple Choice',
            'true_false' => 'True/False',
            'short_answer' => 'Short Answer',
            'long_answer' => 'Long Answer',
            'fill_blank' => 'Fill in the Blank',
            'matching' => 'Matching'
        ];
        $difficulties = ['easy', 'medium', 'hard'];
        $categories = ['practice', 'exam', 'quiz', 'assignment', 'competition'];
        $classLevels = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', 'undergraduate', 'postgraduate'];
        $boards = ['CBSE', 'ICSE', 'State Board', 'IB', 'Cambridge', 'Other'];

        return view('admin.questions.create', compact(
            'subjects',
            'questionTypes',
            'difficulties',
            'categories',
            'classLevels',
            'boards'
        ));
    }

    /**
     * Store a newly created question
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer,long_answer,fill_blank,matching',
            'difficulty' => 'required|in:easy,medium,hard',
            'marks' => 'required|integer|min:1|max:100',
            'time_limit' => 'nullable|integer|min:1',
            'subject_id' => 'required|exists:subjects,id',
            'topic' => 'nullable|string|max:255',
            'subtopic' => 'nullable|string|max:255',
            'chapter' => 'nullable|string|max:255',
            'class_level' => 'nullable|string',
            'board' => 'nullable|string|max:100',
            'category' => 'required|in:practice,exam,quiz,assignment,competition',
            'source' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'explanation' => 'nullable|string',
            'hint' => 'nullable|string',
            'solution_steps' => 'nullable|string',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published,under_review',
            'is_public' => 'boolean',
            'allow_comments' => 'boolean',
            'image' => 'nullable|image|max:2048',
            
            // Options and answers for different question types
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'correct_answers' => 'nullable|array',
            'correct_answers.*' => 'nullable|string',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('questions/images', 'public');
        }

        // Process tags
        if ($request->filled('tags')) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        }

        // Set creator
        $validated['created_by'] = Auth::id();

        // Clean up options and correct answers
        if (isset($validated['options'])) {
            $validated['options'] = array_filter($validated['options'], function($option) {
                return !empty(trim($option));
            });
            $validated['options'] = array_values($validated['options']); // Re-index
        }

        if (isset($validated['correct_answers'])) {
            $validated['correct_answers'] = array_filter($validated['correct_answers'], function($answer) {
                return !empty(trim($answer));
            });
            $validated['correct_answers'] = array_values($validated['correct_answers']); // Re-index
        }

        $question = Question::create($validated);

        return redirect()->route('admin.questions.show', $question)
            ->with('success', 'Question created successfully!');
    }

    /**
     * Display the specified question
     */
    public function show(Question $question)
    {
        $question->load(['subject', 'creator', 'reviewer']);
        return view('admin.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified question
     */
    public function edit(Question $question)
    {
        $subjects = Subject::active()->get();
        $questionTypes = [
            'multiple_choice' => 'Multiple Choice',
            'true_false' => 'True/False',
            'short_answer' => 'Short Answer',
            'long_answer' => 'Long Answer',
            'fill_blank' => 'Fill in the Blank',
            'matching' => 'Matching'
        ];
        $difficulties = ['easy', 'medium', 'hard'];
        $categories = ['practice', 'exam', 'quiz', 'assignment', 'competition'];
        $classLevels = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', 'undergraduate', 'postgraduate'];
        $boards = ['CBSE', 'ICSE', 'State Board', 'IB', 'Cambridge', 'Other'];

        return view('admin.questions.edit', compact(
            'question',
            'subjects',
            'questionTypes',
            'difficulties',
            'categories',
            'classLevels',
            'boards'
        ));
    }

    /**
     * Update the specified question
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer,long_answer,fill_blank,matching',
            'difficulty' => 'required|in:easy,medium,hard',
            'marks' => 'required|integer|min:1|max:100',
            'time_limit' => 'nullable|integer|min:1',
            'subject_id' => 'required|exists:subjects,id',
            'topic' => 'nullable|string|max:255',
            'subtopic' => 'nullable|string|max:255',
            'chapter' => 'nullable|string|max:255',
            'class_level' => 'nullable|string',
            'board' => 'nullable|string|max:100',
            'category' => 'required|in:practice,exam,quiz,assignment,competition',
            'source' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'explanation' => 'nullable|string',
            'hint' => 'nullable|string',
            'solution_steps' => 'nullable|string',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published,under_review',
            'is_public' => 'boolean',
            'allow_comments' => 'boolean',
            'image' => 'nullable|image|max:2048',
            
            // Options and answers
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'correct_answers' => 'nullable|array',
            'correct_answers.*' => 'nullable|string',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($question->image) {
                Storage::disk('public')->delete($question->image);
            }
            $validated['image'] = $request->file('image')->store('questions/images', 'public');
        }

        // Process tags
        if ($request->filled('tags')) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        }

        // Clean up options and correct answers
        if (isset($validated['options'])) {
            $validated['options'] = array_filter($validated['options'], function($option) {
                return !empty(trim($option));
            });
            $validated['options'] = array_values($validated['options']);
        }

        if (isset($validated['correct_answers'])) {
            $validated['correct_answers'] = array_filter($validated['correct_answers'], function($answer) {
                return !empty(trim($answer));
            });
            $validated['correct_answers'] = array_values($validated['correct_answers']);
        }

        $question->update($validated);

        return redirect()->route('admin.questions.show', $question)
            ->with('success', 'Question updated successfully!');
    }

    /**
     * Remove the specified question
     */
    public function destroy(Question $question)
    {
        // Delete image if exists
        if ($question->image) {
            Storage::disk('public')->delete($question->image);
        }

        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question deleted successfully!');
    }

    /**
     * Publish a question
     */
    public function publish(Question $question)
    {
        $question->publish();
        
        return back()->with('success', 'Question published successfully!');
    }

    /**
     * Archive a question
     */
    public function archive(Question $question)
    {
        $question->archive();
        
        return back()->with('success', 'Question archived successfully!');
    }

    /**
     * Mark question for review
     */
    public function markForReview(Request $request, Question $question)
    {
        $request->validate([
            'review_notes' => 'nullable|string|max:1000'
        ]);

        $question->markForReview($request->review_notes);
        
        return back()->with('success', 'Question marked for review!');
    }

    /**
     * Complete review of a question
     */
    public function completeReview(Request $request, Question $question)
    {
        $request->validate([
            'approved' => 'required|boolean',
            'review_notes' => 'nullable|string|max:1000'
        ]);

        $question->completeReview(
            Auth::id(),
            $request->boolean('approved'),
            $request->review_notes
        );

        $message = $request->boolean('approved') ? 'Question approved and published!' : 'Question rejected and moved to draft!';
        
        return back()->with('success', $message);
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,archive,delete,mark_review',
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id'
        ]);

        $questions = Question::whereIn('id', $request->question_ids);

        switch ($request->action) {
            case 'publish':
                $questions->update(['status' => 'published']);
                $message = 'Questions published successfully!';
                break;
            case 'archive':
                $questions->update(['status' => 'archived']);
                $message = 'Questions archived successfully!';
                break;
            case 'mark_review':
                $questions->update(['status' => 'under_review']);
                $message = 'Questions marked for review!';
                break;
            case 'delete':
                $questions->delete();
                $message = 'Questions deleted successfully!';
                break;
        }

        return back()->with('success', $message);
    }

    /**
     * Import questions from CSV/Excel
     */
    public function import()
    {
        return view('admin.questions.import');
    }

    /**
     * Export questions to CSV/Excel
     */
    public function export(Request $request)
    {
        // This would typically use Laravel Excel or similar
        // For now, just redirect back
        return back()->with('info', 'Export functionality coming soon!');
    }

    /**
     * Get question statistics
     */
    public function statistics()
    {
        $stats = [
            'total_questions' => Question::count(),
            'published_questions' => Question::where('status', 'published')->count(),
            'draft_questions' => Question::where('status', 'draft')->count(),
            'under_review' => Question::where('status', 'under_review')->count(),
            'by_difficulty' => Question::selectRaw('difficulty, count(*) as count')
                ->groupBy('difficulty')->pluck('count', 'difficulty'),
            'by_type' => Question::selectRaw('type, count(*) as count')
                ->groupBy('type')->pluck('count', 'type'),
            'by_subject' => Question::with('subject')
                ->selectRaw('subject_id, count(*) as count')
                ->groupBy('subject_id')
                ->get()
                ->pluck('count', 'subject.name'),
        ];

        return view('admin.questions.statistics', compact('stats'));
    }
}
