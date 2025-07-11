<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\Institute;
use App\Models\Question;
use App\Models\Subject;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Display the home page with dynamic content
     */
    public function index()
    {
        // Cache data for better performance
        $cacheKey = 'homepage_data';
        $data = Cache::remember($cacheKey, 1800, function () { // 30 minutes cache
            return [
                'stats' => $this->getHomeStats(),
                'featured_teachers' => $this->getFeaturedTeachers(),
                'featured_institutes' => $this->getFeaturedInstitutes(),
                'popular_subjects' => $this->getPopularSubjects(),
                'recent_questions' => $this->getRecentQuestions(),
                'slider_data' => $this->getSliderData(),
                'testimonials' => $this->getTestimonials(),
            ];
        });

        // Get site settings for meta data
        $seo_data = [
            'title' => SiteSetting::get('site_name', 'Education Platform'),
            'description' => SiteSetting::get('site_description', 'Find qualified teachers and institutes'),
            'keywords' => SiteSetting::get('site_keywords', 'education, tutors, teachers'),
        ];

        return view('home', array_merge($data, ['seo_data' => $seo_data]));
    }

    /**
     * Get homepage statistics
     */
    private function getHomeStats()
    {
        return [
            'total_users' => User::count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_institutes' => User::where('role', 'institute')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'verified_teachers' => TeacherProfile::where('verification_status', 'verified')->count(),
            'active_institutes' => Institute::where('verification_status', 'verified')->count(),
            'total_subjects' => Subject::where('is_active', true)->count(),
            'total_questions' => Question::where('status', 'published')->count(),
        ];
    }

    /**
     * Ensure all commonly used keys are present in teacher data
     */
    private function ensureTeacherKeys($teacher)
    {
        return array_merge([
            'id' => 0,
            'name' => 'Unknown',
            'slug' => '',
            'subject' => 'General',
            'experience' => 0,
            'rating' => 4.0,
            'total_students' => 0,
            'students_count' => 0,
            'hourly_rate' => 0,
            'avatar' => '',
            'location' => '',
            'qualifications' => '',
            'is_online' => false,
            'specialization' => '',
            'total_reviews' => 0,
        ], $teacher);
    }

    /**
     * Ensure all commonly used keys are present in institute data
     */
    private function ensureInstituteKeys($institute)
    {
        return array_merge([
            'id' => 0,
            'name' => 'Unknown Institute',
            'slug' => '',
            'type' => 'Institute',
            'rating' => 4.0,
            'total_students' => 0,
            'students_count' => 0,
            'logo' => '',
            'location' => '',
            'description' => '',
            'established_year' => null,
            'contact_phone' => '',
            'website' => '',
            'total_reviews' => 0,
            'subjects' => collect(),
        ], $institute);
    }

    /**
     * Get featured teachers for homepage
     */
    private function getFeaturedTeachers()
    {
        return TeacherProfile::with(['user', 'subject', 'reviews'])
            ->where('verification_status', 'verified')
            ->where('is_featured', true)
            ->whereHas('user', function($query) {
                $query->where('is_active', true);
            })
            ->whereNotNull('subject_id')
            ->orderBy('rating', 'desc')
            ->orderBy('total_students', 'desc')
            ->take(8)
            ->get()
            ->map(function($teacher) {
                $teacherData = [
                    'id' => $teacher->id,
                    'name' => $teacher->user->name ?? 'Unknown',
                    'slug' => $teacher->slug ?: 'teacher-' . $teacher->id,
                    'subject' => $teacher->subject->name ?? $teacher->specialization ?? 'General',
                    'experience' => $teacher->experience_years ?? 0,
                    'rating' => $teacher->rating ?? 4.0,
                    'total_students' => $teacher->total_students ?? 0,
                    'hourly_rate' => $teacher->hourly_rate ?? 0,
                    'avatar' => $teacher->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($teacher->user->name ?? 'Teacher') . '&size=200&background=random',
                    'location' => trim(($teacher->city ?? '') . ', ' . ($teacher->state ?? ''), ', ') ?: null,
                    'qualifications' => $teacher->qualifications ?: $teacher->qualification,
                    'is_online' => $teacher->availability_status === 'available',
                    'specialization' => $teacher->specialization,
                    'total_reviews' => $teacher->reviews->count() ?? 0,
                ];
                
                return $this->ensureTeacherKeys($teacherData);
            });
    }

    /**
     * Get featured institutes for homepage
     */
    private function getFeaturedInstitutes()
    {
        return Institute::with(['user', 'reviews', 'subjects'])
            ->where('verification_status', 'verified')
            ->where('is_featured', true)
            ->whereHas('user', function($query) {
                $query->where('is_active', true);
            })
            ->orderBy('rating', 'desc')
            ->orderBy('total_students', 'desc')
            ->take(6)
            ->get()
            ->map(function($institute) {
                $instituteData = [
                    'id' => $institute->id,
                    'name' => $institute->institute_name,
                    'slug' => $institute->slug,
                    'type' => $institute->institute_type,
                    'rating' => $institute->rating ?? 4.0,
                    'total_students' => $institute->total_students ?? 0,
                    'students_count' => $institute->total_students ?? 0, // Alias for compatibility
                    'logo' => $institute->logo ?: asset('images/default-institute.png'),
                    'location' => trim(($institute->city ?? '') . ', ' . ($institute->state ?? ''), ', '),
                    'description' => $institute->description,
                    'established_year' => $institute->established_year,
                    'contact_phone' => $institute->contact_phone,
                    'website' => $institute->website,
                    'total_reviews' => $institute->reviews->count() ?? 0,
                    'subjects' => $institute->subjects ?? collect(),
                ];
                
                return $this->ensureInstituteKeys($instituteData);
            });
    }

    /**
     * Get popular subjects with teacher counts
     */
    private function getPopularSubjects()
    {
        return Subject::select('subjects.*')
            ->selectSub(function ($query) {
                $query->from('teacher_profiles')
                    ->join('users', 'teacher_profiles.user_id', '=', 'users.id')
                    ->whereColumn('teacher_profiles.subject_id', 'subjects.id')
                    ->where('teacher_profiles.verification_status', 'verified')
                    ->where('users.is_active', true)
                    ->selectRaw('count(*)');
            }, 'teachers_count')
            ->where('is_active', true)
            ->orderByDesc('teachers_count')
            ->take(12)
            ->get()
            ->filter(function($subject) {
                return $subject->teachers_count > 0;
            })
            ->map(function($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'slug' => $subject->slug,
                    'teachers_count' => $subject->teachers_count,
                    'icon' => $this->getSubjectIcon($subject->name),
                    'color' => $this->getSubjectColor($subject->name),
                ];
            })
            ->values();
    }

    /**
     * Get recent questions for community section
     */
    private function getRecentQuestions()
    {
        return Question::with(['subject'])
            ->where('status', 'published')
            ->where('category', 'quiz')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get()
            ->map(function($question) {
                return [
                    'id' => $question->id,
                    'title' => $question->title ?: Str::limit($question->question_text, 50),
                    'subject' => $question->subject->name ?? 'General',
                    'difficulty' => $question->difficulty,
                    'usage_count' => $question->usage_count,
                    'created_at' => $question->created_at->diffForHumans(),
                ];
            });
    }

    /**
     * Get slider data for homepage
     */
    private function getSliderData()
    {
        $siteName = SiteSetting::get('site_name', 'Education Platform');
        $siteDescription = SiteSetting::get('site_description', 'Connect with qualified educators');
        
        return collect([
            [
                'id' => 1,
                'title' => "Welcome to {$siteName}",
                'subtitle' => $siteDescription,
                'description' => 'Find qualified teachers, join reputable institutes, and accelerate your learning journey',
                'image' => asset('images/slider/education-hero-1.jpg'),
                'cta_text' => 'Find Teachers',
                'cta_url' => route('teachers.index'),
                'secondary_cta_text' => 'Browse Institutes',
                'secondary_cta_url' => route('institutes.index'),
            ],
            [
                'id' => 2,
                'title' => 'Learn from the Best',
                'subtitle' => 'Verified Teachers & Institutes',
                'description' => 'All our educators are verified and rated by students for quality assurance',
                'image' => asset('images/slider/education-hero-2.jpg'),
                'cta_text' => 'View Teachers',
                'cta_url' => route('teachers.index'),
                'secondary_cta_text' => 'Learn More',
                'secondary_cta_url' => route('about'),
            ],
            [
                'id' => 3,
                'title' => 'Start Teaching Today',
                'subtitle' => 'Share Your Knowledge',
                'description' => 'Join our community of educators and make a difference in students\' lives',
                'image' => asset('images/slider/education-hero-3.jpg'),
                'cta_text' => 'Join as Teacher',
                'cta_url' => route('register'),
                'secondary_cta_text' => 'Contact Us',
                'secondary_cta_url' => route('contact'),
            ],
        ]);
    }

    /**
     * Get testimonials data
     */
    private function getTestimonials()
    {
        // This could come from a testimonials table in the future
        return collect([
            [
                'id' => 1,
                'name' => 'Sarah Johnson',
                'designation' => 'Student',
                'content' => 'Found an amazing math teacher through this platform. My grades improved significantly within just 2 months!',
                'rating' => 5,
                'avatar' => asset('images/testimonials/student-1.jpg'),
                'subject' => 'Mathematics',
            ],
            [
                'id' => 2,
                'name' => 'Michael Chen',
                'designation' => 'Parent',
                'content' => 'Excellent platform for finding qualified teachers. The verification process gives me confidence in the quality.',
                'rating' => 5,
                'avatar' => asset('images/testimonials/parent-1.jpg'),
                'subject' => 'Science',
            ],
            [
                'id' => 3,
                'name' => 'Dr. Emily Davis',
                'designation' => 'Teacher',
                'content' => 'Great platform for educators. Easy to connect with serious students and manage my teaching schedule.',
                'rating' => 5,
                'avatar' => asset('images/testimonials/teacher-1.jpg'),
                'subject' => 'Physics',
            ],
            [
                'id' => 4,
                'name' => 'Raj Patel',
                'designation' => 'Institute Director',
                'content' => 'This platform has helped us reach more students and manage our courses more effectively.',
                'rating' => 5,
                'avatar' => asset('images/testimonials/director-1.jpg'),
                'subject' => 'General',
            ],
        ]);
    }

    /**
     * Get search suggestions (AJAX)
     */
    public function searchSuggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = Cache::remember("search.suggestions.{$query}", 1800, function () use ($query) {
            // Teachers
            $teachers = TeacherProfile::with(['user', 'subject'])
                                ->whereHas('user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->where('is_active', true);
                                })
                ->where('verification_status', 'verified')
                ->whereNotNull('subject_id')
                                ->take(5)
                                ->get()
                                ->map(function($teacher) {
                                    return [
                                        'type' => 'teacher',
                                        'title' => $teacher->user->name ?? 'N/A',
                        'subtitle' => ($teacher->subject->name ?? $teacher->specialization ?? 'General') . ' Teacher',
                        'url' => route('teachers.show', $teacher->slug ?: 'teacher-' . $teacher->id),
                        'avatar' => $teacher->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($teacher->user->name ?? 'Teacher') . '&size=200&background=random',
                        'rating' => $teacher->rating ?? 4.0,
                        'location' => trim(($teacher->city ?? '') . ', ' . ($teacher->state ?? ''), ', ') ?: null,
                                    ];
                                });

            // Institutes
            $institutes = Institute::with('user')
                ->where(function($q) use ($query) {
                    $q->where('institute_name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->where('verification_status', 'verified')
                ->whereHas('user', function($q) {
                    $q->where('is_active', true);
                })
                               ->take(5)
                               ->get()
                               ->map(function($institute) {
                                   return [
                                       'type' => 'institute',
                        'title' => $institute->institute_name,
                        'subtitle' => ($institute->institute_type ?? 'Institute') . ' • ' . trim(($institute->city ?? '') . ', ' . ($institute->state ?? ''), ', '),
                        'url' => route('institutes.show', $institute->slug ?: 'institute-' . $institute->id),
                        'avatar' => $institute->logo ?: 'https://ui-avatars.com/api/?name=' . urlencode($institute->institute_name) . '&size=200&background=random',
                        'rating' => $institute->rating ?? 4.0,
                        'students' => $institute->total_students ?? 0,
                    ];
                });

            // Subjects
            $subjects = Subject::where('name', 'like', "%{$query}%")
                ->where('is_active', true)
                ->take(3)
                ->get()
                ->map(function($subject) {
                    return [
                        'type' => 'subject',
                        'title' => $subject->name,
                        'subtitle' => 'View all teachers',
                        'url' => route('teachers.index', ['subject' => $subject->slug]),
                        'avatar' => null,
                        'icon' => $this->getSubjectIcon($subject->name),
                                   ];
                               });

            return $teachers->concat($institutes)->concat($subjects)->take(10);
        });

        return response()->json($suggestions);
    }

    /**
     * Get subject icon based on subject name
     */
    private function getSubjectIcon($subjectName)
    {
        $icons = [
            'mathematics' => 'calculator',
            'math' => 'calculator',
            'science' => 'flask',
            'physics' => 'atom',
            'chemistry' => 'vial',
            'biology' => 'leaf',
            'english' => 'book',
            'computer science' => 'laptop',
            'programming' => 'code',
            'history' => 'clock',
            'geography' => 'globe',
            'economics' => 'chart-line',
            'business' => 'briefcase',
            'art' => 'palette',
            'music' => 'music',
            'language' => 'language',
        ];

        $subject = strtolower($subjectName);
        foreach ($icons as $key => $icon) {
            if (strpos($subject, $key) !== false) {
                return $icon;
            }
        }

        return 'book'; // default icon
    }

    /**
     * Get subject color based on subject name
     */
    private function getSubjectColor($subjectName)
    {
        $colors = [
            'mathematics' => '#3b82f6',
            'science' => '#10b981',
            'physics' => '#8b5cf6',
            'chemistry' => '#f59e0b',
            'biology' => '#22c55e',
            'english' => '#ef4444',
            'computer science' => '#6366f1',
            'history' => '#a855f7',
            'geography' => '#14b8a6',
            'economics' => '#f97316',
        ];

        $subject = strtolower($subjectName);
        foreach ($colors as $key => $color) {
            if (strpos($subject, $key) !== false) {
                return $color;
            }
        }

        return '#6b7280'; // default color
    }

    /**
     * Clear homepage cache (for admin use)
     */
    public function clearCache()
    {
        Cache::forget('homepage_data');
        return response()->json(['success' => true, 'message' => 'Homepage cache cleared']);
    }
}
