<?php

namespace App\Http\Controllers;

use App\Models\TeacherProfile;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TeacherListingController extends Controller
{
    /**
     * Display teacher listing page with filters
     */
    public function index(Request $request)
    {
        $query = TeacherProfile::with(['user', 'subject'])
            ->where('verification_status', 'verified')
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            });

        // Apply filters
        $filters = $this->applyFilters($query, $request);
        
        // Sorting
        $sortBy = $request->get('sort', 'rating');
        $sortOrder = $request->get('order', 'desc');
        
        switch ($sortBy) {
            case 'name':
                $query->join('users', 'teacher_profiles.user_id', '=', 'users.id')
                      ->orderBy('users.name', $sortOrder);
                break;
            case 'experience':
                $query->orderBy('experience_years', $sortOrder);
                break;
            case 'rate':
                $query->orderBy('hourly_rate', $sortOrder);
                break;
            case 'students':
                $query->orderBy('total_students', $sortOrder);
                break;
            case 'rating':
            default:
                $query->orderBy('rating', $sortOrder)
                      ->orderBy('total_students', 'desc');
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $teachers = $query->paginate($perPage)->withQueryString();

        // Get filter options
        $filterOptions = $this->getFilterOptions();
        
        // Get popular searches
        $popularSearches = $this->getPopularSearches();

        return view('teachers.index', compact(
            'teachers', 
            'filterOptions', 
            'filters', 
            'popularSearches'
        ));
    }

    /**
     * Show individual teacher profile
     */
    public function show($slug)
    {
        $teacher = TeacherProfile::with(['user', 'subject'])
            ->where('slug', $slug)
            ->where('verification_status', 'verified')
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            })
            ->firstOrFail();

        // Get related teachers (same subject)
        $relatedTeachers = TeacherProfile::with(['user', 'subject'])
            ->where('subject_id', $teacher->subject_id)
            ->where('id', '!=', $teacher->id)
            ->where('verification_status', 'verified')
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            })
            ->orderBy('rating', 'desc')
            ->take(4)
            ->get();

        // Get teacher's recent activity/reviews (placeholder)
        $reviews = $this->getTeacherReviews($teacher->id);
        
        // Get teacher's availability (placeholder)
        $availability = $this->getTeacherAvailability($teacher->id);

        return view('teachers.show', compact(
            'teacher', 
            'relatedTeachers', 
            'reviews', 
            'availability'
        ));
    }

    /**
     * Filter teachers by subject
     */
    public function bySubject($slug, Request $request)
    {
        $subject = Subject::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $query = TeacherProfile::with(['user', 'subject'])
            ->where('subject_id', $subject->id)
            ->where('verification_status', 'verified')
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            });

        // Apply additional filters
        $filters = $this->applyFilters($query, $request);
        $filters['subject'] = $subject->name;

        // Sorting
        $sortBy = $request->get('sort', 'rating');
        $sortOrder = $request->get('order', 'desc');
        
        switch ($sortBy) {
            case 'name':
                $query->join('users', 'teacher_profiles.user_id', '=', 'users.id')
                      ->orderBy('users.name', $sortOrder);
                break;
            case 'experience':
                $query->orderBy('experience_years', $sortOrder);
                break;
            case 'rate':
                $query->orderBy('hourly_rate', $sortOrder);
                break;
            case 'students':
                $query->orderBy('total_students', $sortOrder);
                break;
            case 'rating':
            default:
                $query->orderBy('rating', $sortOrder)
                      ->orderBy('total_students', 'desc');
                break;
        }

        $perPage = $request->get('per_page', 12);
        $teachers = $query->paginate($perPage)->withQueryString();

        $filterOptions = $this->getFilterOptions();
        
        return view('teachers.by-subject', compact(
            'teachers', 
            'subject', 
            'filterOptions', 
            'filters'
        ));
    }

    /**
     * Filter teachers by city
     */
    public function byCity($city, Request $request)
    {
        $query = TeacherProfile::with(['user', 'subject'])
            ->where('city', $city)
            ->where('verification_status', 'verified')
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            });

        $filters = $this->applyFilters($query, $request);
        $filters['city'] = $city;

        $sortBy = $request->get('sort', 'rating');
        $sortOrder = $request->get('order', 'desc');
        
        switch ($sortBy) {
            case 'name':
                $query->join('users', 'teacher_profiles.user_id', '=', 'users.id')
                      ->orderBy('users.name', $sortOrder);
                break;
            case 'experience':
                $query->orderBy('experience_years', $sortOrder);
                break;
            case 'rate':
                $query->orderBy('hourly_rate', $sortOrder);
                break;
            case 'students':
                $query->orderBy('total_students', $sortOrder);
                break;
            case 'rating':
            default:
                $query->orderBy('rating', $sortOrder)
                      ->orderBy('total_students', 'desc');
                break;
        }

        $perPage = $request->get('per_page', 12);
        $teachers = $query->paginate($perPage)->withQueryString();

        $filterOptions = $this->getFilterOptions();
        
        return view('teachers.by-city', compact(
            'teachers', 
            'city', 
            'filterOptions', 
            'filters'
        ));
    }

    /**
     * Apply filters to teacher query
     */
    private function applyFilters($query, Request $request)
    {
        $filters = [];

        // Search by name or specialization
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('specialization', 'like', "%{$search}%")
                ->orWhere('qualifications', 'like', "%{$search}%");
            });
            $filters['search'] = $search;
        }

        // Filter by subject
        if ($request->filled('subject')) {
            $subjectId = $request->get('subject');
            $query->where('subject_id', $subjectId);
            $subject = Subject::find($subjectId);
            $filters['subject'] = $subject ? $subject->name : '';
        }

        // Filter by experience
        if ($request->filled('experience')) {
            $experience = $request->get('experience');
            switch ($experience) {
                case '0-2':
                    $query->whereBetween('experience_years', [0, 2]);
                    break;
                case '3-5':
                    $query->whereBetween('experience_years', [3, 5]);
                    break;
                case '6-10':
                    $query->whereBetween('experience_years', [6, 10]);
                    break;
                case '10+':
                    $query->where('experience_years', '>', 10);
                    break;
            }
            $filters['experience'] = $experience;
        }

        // Filter by hourly rate
        if ($request->filled('rate_min') || $request->filled('rate_max')) {
            $rateMin = $request->get('rate_min', 0);
            $rateMax = $request->get('rate_max', 10000);
            $query->whereBetween('hourly_rate', [$rateMin, $rateMax]);
            $filters['rate_min'] = $rateMin;
            $filters['rate_max'] = $rateMax;
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $rating = $request->get('rating');
            $query->where('rating', '>=', $rating);
            $filters['rating'] = $rating;
        }

        // Filter by city
        if ($request->filled('city')) {
            $city = $request->get('city');
            $query->where('city', $city);
            $filters['city'] = $city;
        }

        // Filter by availability
        if ($request->filled('availability')) {
            $availability = $request->get('availability');
            if ($availability === 'available') {
                $query->where('availability_status', 'available');
            } elseif ($availability === 'online') {
                $query->where('teaching_mode', 'like', '%online%');
            } elseif ($availability === 'offline') {
                $query->where('teaching_mode', 'like', '%offline%');
            }
            $filters['availability'] = $availability;
        }

        // Filter by teaching mode
        if ($request->filled('teaching_mode')) {
            $mode = $request->get('teaching_mode');
            $query->where('teaching_mode', 'like', "%{$mode}%");
            $filters['teaching_mode'] = $mode;
        }

        return $filters;
    }

    /**
     * Get filter options for the listing page
     */
    private function getFilterOptions()
    {
        return Cache::remember('teacher_filter_options', 3600, function() {
            return [
                'subjects' => Subject::where('status', 'active')
                    ->withCount(['teacherProfiles' => function($query) {
                        $query->where('verification_status', 'verified')
                              ->whereHas('user', function($q) {
                                  $q->where('is_active', true);
                              });
                    }])
                    ->orderBy('name')
                    ->get()
                    ->filter(function($subject) {
                        return $subject->teacher_profiles_count > 0;
                    }),
                
                'cities' => TeacherProfile::where('verification_status', 'verified')
                    ->whereHas('user', function($q) {
                        $q->where('is_active', true);
                    })
                    ->whereNotNull('city')
                    ->select('city', DB::raw('count(*) as count'))
                    ->groupBy('city')
                    ->orderBy('count', 'desc')
                    ->take(20)
                    ->get(),
                
                'experience_ranges' => [
                    '0-2' => '0-2 years',
                    '3-5' => '3-5 years', 
                    '6-10' => '6-10 years',
                    '10+' => '10+ years'
                ],
                
                'rate_ranges' => [
                    ['min' => 0, 'max' => 500, 'label' => 'Under ₹500'],
                    ['min' => 500, 'max' => 1000, 'label' => '₹500-₹1000'],
                    ['min' => 1000, 'max' => 2000, 'label' => '₹1000-₹2000'],
                    ['min' => 2000, 'max' => 10000, 'label' => 'Above ₹2000'],
                ],
                
                'ratings' => [5, 4, 3, 2, 1],
                
                'teaching_modes' => [
                    'online' => 'Online Only',
                    'offline' => 'In-Person Only',
                    'both' => 'Both Online & Offline'
                ]
            ];
        });
    }

    /**
     * Get popular searches for suggestions
     */
    private function getPopularSearches()
    {
        return Cache::remember('popular_teacher_searches', 3600, function() {
            return [
                'Mathematics Teacher',
                'Science Tutor',
                'English Teacher',
                'Physics Expert',
                'Chemistry Tutor',
                'Online Math Teacher',
                'CBSE Teacher',
                'IIT JEE Preparation'
            ];
        });
    }

    /**
     * Get teacher reviews (placeholder)
     */
    private function getTeacherReviews($teacherId)
    {
        // This would come from a reviews table
        return collect([
            [
                'id' => 1,
                'student_name' => 'John Doe',
                'rating' => 5,
                'comment' => 'Excellent teacher! Very clear explanations and patient with doubts.',
                'subject' => 'Mathematics',
                'created_at' => now()->subDays(5)
            ],
            [
                'id' => 2,
                'student_name' => 'Jane Smith',
                'rating' => 4,
                'comment' => 'Good teaching methods and very supportive.',
                'subject' => 'Mathematics',
                'created_at' => now()->subDays(10)
            ]
        ]);
    }

    /**
     * Get teacher availability (placeholder)
     */
    private function getTeacherAvailability($teacherId)
    {
        // This would come from availability/schedule tables
        return [
            'monday' => ['9:00 AM - 12:00 PM', '2:00 PM - 6:00 PM'],
            'tuesday' => ['9:00 AM - 12:00 PM', '2:00 PM - 6:00 PM'],
            'wednesday' => ['9:00 AM - 12:00 PM'],
            'thursday' => ['9:00 AM - 12:00 PM', '2:00 PM - 6:00 PM'],
            'friday' => ['9:00 AM - 12:00 PM', '2:00 PM - 6:00 PM'],
            'saturday' => ['10:00 AM - 2:00 PM'],
            'sunday' => ['Rest Day']
        ];
    }

    /**
     * AJAX endpoint for teacher card data
     */
    public function getTeacherCard(Request $request)
    {
        $teacherId = $request->get('teacher_id');
        
        $teacher = TeacherProfile::with(['user', 'subject'])
            ->where('id', $teacherId)
            ->where('verification_status', 'verified')
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            })
            ->first();

        if (!$teacher) {
            return response()->json(['error' => 'Teacher not found'], 404);
        }

        return response()->json([
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->user->name,
                'subject' => $teacher->subject->name ?? $teacher->specialization,
                'rating' => $teacher->rating,
                'experience' => $teacher->experience_years,
                'hourly_rate' => $teacher->hourly_rate,
                'avatar' => $teacher->avatar ?: asset('images/default-avatar.png'),
                'location' => trim(($teacher->city ?? '') . ', ' . ($teacher->state ?? ''), ', '),
                'availability' => $teacher->availability_status,
                'teaching_mode' => $teacher->teaching_mode,
                'qualifications' => $teacher->qualifications,
                'bio' => $teacher->bio,
                'total_students' => $teacher->total_students,
                'profile_url' => route('teachers.show', $teacher->slug)
            ]
        ]);
    }
} 