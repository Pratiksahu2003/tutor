<?php

namespace App\Http\Controllers;

use App\Models\Institute;
use App\Models\TeacherProfile;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class InstituteListingController extends Controller
{
    /**
     * Display institute listing page with filters
     */
    public function index(Request $request)
    {
        $query = Institute::with('user')
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
                $query->orderBy('institute_name', $sortOrder);
                break;
            case 'students':
                $query->orderBy('total_students', $sortOrder);
                break;
            case 'established':
                $query->orderBy('established_year', $sortOrder);
                break;
            case 'rating':
            default:
                $query->orderBy('rating', $sortOrder)
                      ->orderBy('total_students', 'desc');
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $institutes = $query->paginate($perPage)->withQueryString();

        // Get filter options
        $filterOptions = $this->getFilterOptions();
        
        // Get popular searches
        $popularSearches = $this->getPopularSearches();

        return view('institutes.index', compact(
            'institutes', 
            'filterOptions', 
            'filters', 
            'popularSearches'
        ));
    }

    /**
     * Show individual institute profile
     */
    public function show($slug)
    {
        $institute = Institute::with('user')
            ->where('slug', $slug)
            ->where('verification_status', 'verified')
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            })
            ->firstOrFail();

        // Get institute's teachers
        $teachers = TeacherProfile::with(['user', 'subject'])
            ->where('institute_id', $institute->id)
            ->where('verification_status', 'verified')
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            })
            ->orderBy('rating', 'desc')
            ->take(8)
            ->get();

        // Get related institutes (same city or type)
        $relatedInstitutes = Institute::with('user')
            ->where(function($q) use ($institute) {
                $q->where('city', $institute->city)
                  ->orWhere('institute_type', $institute->institute_type);
            })
            ->where('id', '!=', $institute->id)
            ->where('verification_status', 'verified')
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            })
            ->orderBy('rating', 'desc')
            ->take(4)
            ->get();

        // Get institute's courses/programs (placeholder)
        $courses = $this->getInstituteCourses($institute->id);
        
        // Get institute's reviews (placeholder)
        $reviews = $this->getInstituteReviews($institute->id);
        
        // Get institute's facilities (placeholder)
        $facilities = $this->getInstituteFacilities($institute->id);

        return view('institutes.show', compact(
            'institute', 
            'teachers',
            'relatedInstitutes', 
            'courses',
            'reviews', 
            'facilities'
        ));
    }

    /**
     * Show institute's teachers
     */
    public function teachers($slug, Request $request)
    {
        $institute = Institute::where('slug', $slug)
            ->where('verification_status', 'verified')
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            })
            ->firstOrFail();

        $query = TeacherProfile::with(['user', 'subject'])
            ->where('institute_id', $institute->id)
            ->where('verification_status', 'verified')
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            });

        // Apply filters
        $filters = $this->applyTeacherFilters($query, $request);

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

        return view('institutes.teachers', compact(
            'institute', 
            'teachers', 
            'filters'
        ));
    }

    /**
     * Filter institutes by city
     */
    public function byCity($city, Request $request)
    {
        $query = Institute::with('user')
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
                $query->orderBy('institute_name', $sortOrder);
                break;
            case 'students':
                $query->orderBy('total_students', $sortOrder);
                break;
            case 'established':
                $query->orderBy('established_year', $sortOrder);
                break;
            case 'rating':
            default:
                $query->orderBy('rating', $sortOrder)
                      ->orderBy('total_students', 'desc');
                break;
        }

        $perPage = $request->get('per_page', 12);
        $institutes = $query->paginate($perPage)->withQueryString();

        $filterOptions = $this->getFilterOptions();
        
        return view('institutes.by-city', compact(
            'institutes', 
            'city', 
            'filterOptions', 
            'filters'
        ));
    }

    /**
     * Apply filters to institute query
     */
    private function applyFilters($query, Request $request)
    {
        $filters = [];

        // Search by name or description
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('institute_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
            $filters['search'] = $search;
        }

        // Filter by institute type
        if ($request->filled('type')) {
            $type = $request->get('type');
            $query->where('institute_type', $type);
            $filters['type'] = $type;
        }

        // Filter by city
        if ($request->filled('city')) {
            $city = $request->get('city');
            $query->where('city', $city);
            $filters['city'] = $city;
        }

        // Filter by state
        if ($request->filled('state')) {
            $state = $request->get('state');
            $query->where('state', $state);
            $filters['state'] = $state;
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $rating = $request->get('rating');
            $query->where('rating', '>=', $rating);
            $filters['rating'] = $rating;
        }

        // Filter by establishment year
        if ($request->filled('established_after')) {
            $year = $request->get('established_after');
            $query->where('established_year', '>=', $year);
            $filters['established_after'] = $year;
        }

        // Filter by student count
        if ($request->filled('students_min')) {
            $minStudents = $request->get('students_min');
            $query->where('total_students', '>=', $minStudents);
            $filters['students_min'] = $minStudents;
        }

        // Filter by affiliation/board
        if ($request->filled('affiliation')) {
            $affiliation = $request->get('affiliation');
            $query->where('affiliation', 'like', "%{$affiliation}%");
            $filters['affiliation'] = $affiliation;
        }

        return $filters;
    }

    /**
     * Apply filters to teacher query within institute
     */
    private function applyTeacherFilters($query, Request $request)
    {
        $filters = [];

        // Search by teacher name or specialization
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('specialization', 'like', "%{$search}%");
            });
            $filters['search'] = $search;
        }

        // Filter by subject
        if ($request->filled('subject')) {
            $subjectId = $request->get('subject');
            $query->where('subject_id', $subjectId);
            $filters['subject'] = $subjectId;
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

        return $filters;
    }

    /**
     * Get filter options for the listing page
     */
    private function getFilterOptions()
    {
        return Cache::remember('institute_filter_options', 3600, function() {
            return [
                'types' => Institute::where('verification_status', 'verified')
                    ->whereHas('user', function($q) {
                        $q->where('is_active', true);
                    })
                    ->whereNotNull('institute_type')
                    ->select('institute_type', DB::raw('count(*) as count'))
                    ->groupBy('institute_type')
                    ->orderBy('count', 'desc')
                    ->get(),
                
                'cities' => Institute::where('verification_status', 'verified')
                    ->whereHas('user', function($q) {
                        $q->where('is_active', true);
                    })
                    ->whereNotNull('city')
                    ->select('city', DB::raw('count(*) as count'))
                    ->groupBy('city')
                    ->orderBy('count', 'desc')
                    ->take(20)
                    ->get(),
                
                'states' => Institute::where('verification_status', 'verified')
                    ->whereHas('user', function($q) {
                        $q->where('is_active', true);
                    })
                    ->whereNotNull('state')
                    ->select('state', DB::raw('count(*) as count'))
                    ->groupBy('state')
                    ->orderBy('count', 'desc')
                    ->get(),
                
                'ratings' => [5, 4, 3, 2, 1],
                
                'establishment_years' => [
                    '2020' => '2020 & Later',
                    '2010' => '2010 & Later',
                    '2000' => '2000 & Later',
                    '1990' => '1990 & Later',
                    '1980' => 'Before 1980'
                ],
                
                'student_ranges' => [
                    ['min' => 100, 'label' => '100+ Students'],
                    ['min' => 500, 'label' => '500+ Students'],
                    ['min' => 1000, 'label' => '1000+ Students'],
                    ['min' => 2000, 'label' => '2000+ Students'],
                ],
                
                'affiliations' => [
                    'CBSE' => 'CBSE',
                    'ICSE' => 'ICSE',
                    'State Board' => 'State Board',
                    'IB' => 'International Baccalaureate',
                    'Cambridge' => 'Cambridge',
                    'University' => 'University Affiliated'
                ]
            ];
        });
    }

    /**
     * Get popular searches for suggestions
     */
    private function getPopularSearches()
    {
        return Cache::remember('popular_institute_searches', 3600, function() {
            return [
                'CBSE Schools',
                'Engineering Colleges',
                'Coaching Institutes',
                'Medical Colleges',
                'Management Institutes',
                'Arts Colleges',
                'Technical Institutes',
                'Distance Learning'
            ];
        });
    }

    /**
     * Get institute courses (placeholder)
     */
    private function getInstituteCourses($instituteId)
    {
        // This would come from a courses table
        return collect([
            [
                'id' => 1,
                'name' => 'B.Tech Computer Science',
                'duration' => '4 Years',
                'type' => 'Undergraduate',
                'fees' => 150000,
                'seats' => 60
            ],
            [
                'id' => 2,
                'name' => 'M.Tech Software Engineering',
                'duration' => '2 Years',
                'type' => 'Postgraduate',
                'fees' => 80000,
                'seats' => 30
            ]
        ]);
    }

    /**
     * Get institute reviews (placeholder)
     */
    private function getInstituteReviews($instituteId)
    {
        // This would come from a reviews table
        return collect([
            [
                'id' => 1,
                'student_name' => 'Rahul Kumar',
                'rating' => 5,
                'comment' => 'Excellent faculty and infrastructure. Great placement record.',
                'course' => 'B.Tech CSE',
                'graduation_year' => 2023,
                'created_at' => now()->subDays(15)
            ],
            [
                'id' => 2,
                'student_name' => 'Priya Sharma',
                'rating' => 4,
                'comment' => 'Good academic environment and supportive faculty.',
                'course' => 'MBA',
                'graduation_year' => 2022,
                'created_at' => now()->subDays(30)
            ]
        ]);
    }

    /**
     * Get institute facilities (placeholder)
     */
    private function getInstituteFacilities($instituteId)
    {
        // This would come from a facilities table
        return [
            'academic' => [
                'Well-equipped Laboratories',
                'Digital Library',
                'Smart Classrooms',
                'Computer Labs',
                'Research Centers'
            ],
            'infrastructure' => [
                'WiFi Campus',
                'Air Conditioned Rooms',
                'Auditorium',
                'Conference Halls',
                'Parking Facility'
            ],
            'student_life' => [
                'Sports Complex',
                'Gymnasium',
                'Cafeteria',
                'Hostel Facility',
                'Medical Center'
            ],
            'support' => [
                'Placement Cell',
                'Career Counseling',
                'Student Mentoring',
                'Alumni Network',
                'Industry Partnerships'
            ]
        ];
    }

    /**
     * AJAX endpoint for institute card data
     */
    public function getInstituteCard(Request $request)
    {
        $instituteId = $request->get('institute_id');
        
        $institute = Institute::with('user')
            ->where('id', $instituteId)
            ->where('verification_status', 'verified')
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            })
            ->first();

        if (!$institute) {
            return response()->json(['error' => 'Institute not found'], 404);
        }

        return response()->json([
            'institute' => [
                'id' => $institute->id,
                'name' => $institute->institute_name,
                'type' => $institute->institute_type,
                'rating' => $institute->rating,
                'total_students' => $institute->total_students,
                'logo' => $institute->logo ?: asset('images/default-institute.png'),
                'location' => trim(($institute->city ?? '') . ', ' . ($institute->state ?? ''), ', '),
                'established_year' => $institute->established_year,
                'description' => $institute->description,
                'website' => $institute->website,
                'contact_phone' => $institute->contact_phone,
                'profile_url' => route('institutes.show', $institute->slug)
            ]
        ]);
    }
} 