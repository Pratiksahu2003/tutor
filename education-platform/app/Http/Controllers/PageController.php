<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Subject;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    public function about()
    {
        $content = SiteSetting::get('about_us', 'About us content coming soon...');
        return view('pages.about', compact('content'));
    }

    public function terms()
    {
        $content = SiteSetting::get('terms_conditions', 'Terms and conditions content coming soon...');
        return view('pages.terms', compact('content'));
    }

    public function privacy()
    {
        $content = SiteSetting::get('privacy_policy', 'Privacy policy content coming soon...');
        return view('pages.privacy', compact('content'));
    }

    public function faq(Request $request)
    {
        // Check if FAQ is enabled
        $faqEnabled = SiteSetting::get('faq_enabled', true);
        if (!$faqEnabled) {
            abort(404);
        }

        // Get FAQ categories from settings
        $faqCategories = SiteSetting::get('faq_categories', ['General', 'Accounts', 'Payments', 'Technical', 'Learning']);
        if (is_string($faqCategories)) {
            $faqCategories = json_decode($faqCategories, true) ?: ['General'];
        }

        // Cache the FAQ data for better performance
        $cacheKey = 'faq_questions_' . md5(serialize($request->all()));
        
        $data = Cache::remember($cacheKey, 1800, function () use ($request) { // 30 minutes cache
            $query = Question::query()
                ->where(function($q) {
                    $q->where('category', 'faq')
                      ->orWhere('tags', 'like', '%faq%')
                      ->orWhere('category', 'quiz');
                })
                ->where('status', 'published')
                ->orderBy('subject_id')
                ->orderBy('created_at', 'desc');

            // Apply filters
            if ($request->filled('subject')) {
                $query->where('subject_id', $request->subject);
            }

            if ($request->filled('difficulty')) {
                $query->where('difficulty_level', $request->difficulty);
            }

            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('question_text', 'like', "%{$searchTerm}%")
                      ->orWhere('explanation', 'like', "%{$searchTerm}%")
                      ->orWhere('tags', 'like', "%{$searchTerm}%");
                });
            }

            $questions = $query->with('subject')->get();

            // Group questions by subject
            $questionsBySubject = $questions->groupBy(function($question) {
                return $question->subject ? $question->subject->name : 'General';
            });

            // Separate general FAQs (platform-related questions)
            $generalFaqs = $questions->filter(function($question) {
                return $question->category === 'faq' || 
                       (is_array($question->tags) && in_array('faq', $question->tags)) ||
                       (is_string($question->tags) && strpos($question->tags, 'faq') !== false);
            });

            // Subject-specific questions (quiz/educational content)
            $faqQuestions = $questions->filter(function($question) {
                return $question->category === 'quiz' || $question->subject_id !== null;
            })->groupBy(function($question) {
                return $question->subject ? $question->subject->name : 'General';
            });

            return [
                'questions' => $questions,
                'questionsBySubject' => $questionsBySubject,
                'generalFaqs' => $generalFaqs,
                'faqQuestions' => $faqQuestions,
                'totalQuestions' => $questions->count(),
                'subjects' => Subject::where('status', 'active')->orderBy('name')->get(),
                'stats' => [
                    'total_questions' => $questions->count(),
                    'total_subjects' => Subject::where('status', 'active')->count(),
                    'faq_questions' => $generalFaqs->count(),
                ]
            ];
        });

        return view('pages.faq', array_merge($data, [
            'faqCategories' => $faqCategories
        ]));
    }

    public function faqSearch(Request $request)
    {
        $query = Question::query()
            ->where(function($q) {
                $q->where('category', 'faq')
                  ->orWhere('tags', 'like', '%faq%')
                  ->orWhere('category', 'quiz');
            })
            ->where('status', 'published');

        // Apply search filters
        if ($request->filled('subject') && $request->subject !== 'all') {
            $query->where('subject_id', $request->subject);
        }

        if ($request->filled('difficulty') && $request->difficulty !== 'all') {
            $query->where('difficulty_level', $request->difficulty);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('question_text', 'like', "%{$searchTerm}%")
                  ->orWhere('explanation', 'like', "%{$searchTerm}%")
                  ->orWhere('tags', 'like', "%{$searchTerm}%");
            });
        }

        $questions = $query->with('subject')
            ->orderBy('subject_id')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by subject for display
        $questionsBySubject = $questions->groupBy(function($question) {
            return $question->subject ? $question->subject->name : 'General';
        });

        return response()->json([
            'success' => true,
            'questions' => $questions,
            'questionsBySubject' => $questionsBySubject,
            'totalResults' => $questions->count(),
        ]);
    }

    public function howItWorks()
    {
        return view('pages.how-it-works');
    }

    public function careers()
    {
        return view('pages.careers');
    }

    public function dynamic($slug)
    {
        // Here you could implement dynamic page loading from database
        // For now, return 404 for non-existent pages
        abort(404);
    }
} 