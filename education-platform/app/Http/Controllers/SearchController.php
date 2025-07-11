<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherProfile;
use App\Models\Institute;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Display the main search page
     */
    public function index(Request $request)
    {
        $searchTerm = $request->get('q', '');
        $type = $request->get('type', 'all');
        $location = $request->get('location', '');
        
        $results = [
            'teachers' => collect(),
            'institutes' => collect(),
            'subjects' => collect(),
        ];
        
        if ($searchTerm) {
            // Search teachers
            if ($type === 'all' || $type === 'teachers') {
                $results['teachers'] = TeacherProfile::where('verification_status', 'verified')
                    ->whereHas('user', function($q) use ($searchTerm, $location) {
                        $q->where('name', 'like', "%{$searchTerm}%")
                          ->where('is_active', true);
                        if ($location) {
                            $q->where(function($locQ) use ($location) {
                                $locQ->where('city', 'like', "%{$location}%")
                                     ->orWhere('state', 'like', "%{$location}%");
                            });
                        }
                    })
                    ->orWhere('specialization', 'like', "%{$searchTerm}%")
                    ->orWhere('bio', 'like', "%{$searchTerm}%")
                    ->with(['user', 'subject'])
                    ->take(6)
                    ->get()
                    ->map(function($teacher) {
                        return [
                            'id' => $teacher->id,
                            'type' => 'teacher',
                            'name' => $teacher->user->name ?? 'Unknown',
                            'title' => $teacher->specialization ?? 'Teacher',
                            'location' => trim(($teacher->city ?? '') . ', ' . ($teacher->state ?? ''), ', '),
                            'rating' => $teacher->rating ?? 4.0,
                            'experience' => $teacher->experience_years ?? 0,
                            'hourly_rate' => $teacher->hourly_rate,
                            'avatar' => $teacher->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($teacher->user->name ?? 'Teacher') . '&size=200&background=random',
                            'url' => route('teachers.show', $teacher->slug ?: 'teacher-' . $teacher->id),
                        ];
                    });
            }
            
            // Search institutes
            if ($type === 'all' || $type === 'institutes') {
                $results['institutes'] = Institute::where('verification_status', 'verified')
                    ->whereHas('user', function($q) {
                        $q->where('is_active', true);
                    })
                    ->where(function($q) use ($searchTerm, $location) {
                        $q->where('institute_name', 'like', "%{$searchTerm}%")
                          ->orWhere('description', 'like', "%{$searchTerm}%");
                        if ($location) {
                            $q->where('city', 'like', "%{$location}%")
                              ->orWhere('state', 'like', "%{$location}%");
                        }
                    })
                    ->with('user')
                    ->take(6)
                    ->get()
                    ->map(function($institute) {
                        return [
                            'id' => $institute->id,
                            'type' => 'institute',
                            'name' => $institute->institute_name,
                            'title' => $institute->institute_type ?? 'Institute',
                            'location' => trim(($institute->city ?? '') . ', ' . ($institute->state ?? ''), ', '),
                            'rating' => $institute->rating ?? 4.0,
                            'total_students' => $institute->total_students ?? 0,
                            'established_year' => $institute->established_year,
                            'logo' => $institute->logo ?: 'https://ui-avatars.com/api/?name=' . urlencode($institute->institute_name) . '&size=200&background=random',
                            'url' => route('institutes.show', $institute->slug ?: 'institute-' . $institute->id),
                        ];
                    });
            }
            
            // Search subjects
            if ($type === 'all' || $type === 'subjects') {
                $results['subjects'] = Subject::where('is_active', true)
                    ->where('name', 'like', "%{$searchTerm}%")
                    ->take(6)
                    ->get()
                    ->map(function($subject) {
                        return [
                            'id' => $subject->id,
                            'type' => 'subject',
                            'name' => $subject->name,
                            'title' => 'Subject',
                            'teachers_count' => $subject->teacherProfiles()->count(),
                            'url' => route('teachers.index', ['subject' => $subject->slug]),
                        ];
                    });
            }
        }
        
        $totalResults = $results['teachers']->count() + $results['institutes']->count() + $results['subjects']->count();
        
        return view('search.index', [
            'results' => $results,
            'searchTerm' => $searchTerm,
            'type' => $type,
            'location' => $location,
            'totalResults' => $totalResults,
            'popularSubjects' => Subject::where('is_active', true)->take(12)->get(),
            'popularCities' => $this->getPopularCities(),
        ]);
    }

    // =======================
    // TEACHER LISTINGS
    // =======================
    
    public function teachers(Request $request)
    {
        $query = TeacherProfile::where('verified', true)
            ->where('is_active', true)
            ->with(['user', 'subjects', 'institute']);
        
        // Apply filters
        $this->applyTeacherFilters($query, $request);
        
        // Apply sorting
        $this->applyTeacherSorting($query, $request);
        
        $teachers = $query->paginate(12)->appends($request->query());
        
        // Get filter options
        $filterOptions = $this->getTeacherFilterOptions();
        
        return view('search.teachers', [
            'teachers' => $teachers,
            'filters' => $filterOptions,
            'currentFilters' => $request->all(),
            'totalResults' => $teachers->total(),
        ]);
    }
    
    public function teacherListing()
    {
        return $this->teachers(request());
    }
    
    public function teacherProfile($slug)
    {
        $teacher = TeacherProfile::where('slug', $slug)
            ->orWhere('id', $slug)
            ->where('verified', true)
            ->where('is_active', true)
            ->with(['user', 'subjects', 'institute', 'reviews'])
            ->firstOrFail();
        
        // Increment view count
        $teacher->increment('profile_views');
        
        // Get related teachers
        $relatedTeachers = $this->getRelatedTeachers($teacher);
        
        return view('search.teacher-profile', [
            'teacher' => $teacher,
            'relatedTeachers' => $relatedTeachers,
        ]);
    }
    
    public function teachersBySubject($slug)
    {
        $subject = Subject::where('slug', $slug)->firstOrFail();
        
        $query = TeacherProfile::where('verified', true)
            ->where('is_active', true)
            ->whereHas('subjects', function($q) use ($subject) {
                $q->where('subjects.id', $subject->id);
            })
            ->with(['user', 'subjects', 'institute']);
        
        $this->applyTeacherSorting($query, request());
        
        $teachers = $query->paginate(12);
        
        return view('search.teachers-by-subject', [
            'teachers' => $teachers,
            'subject' => $subject,
            'totalResults' => $teachers->total(),
        ]);
    }
    
    public function teachersByCity($city)
    {
        $query = TeacherProfile::where('verified', true)
            ->where('is_active', true)
            ->whereHas('user', function($q) use ($city) {
                $q->where('city', 'like', "%{$city}%");
            })
            ->with(['user', 'subjects', 'institute']);
        
        $this->applyTeacherSorting($query, request());
        
        $teachers = $query->paginate(12);
        
        return view('search.teachers-by-city', [
            'teachers' => $teachers,
            'city' => $city,
            'totalResults' => $teachers->total(),
        ]);
    }
    
    // =======================
    // INSTITUTE LISTINGS
    // =======================
    
    public function institutes(Request $request)
    {
        $query = Institute::where('verified', true)
            ->where('is_active', true)
            ->with(['user', 'subjects', 'teachers']);
        
        // Apply filters
        $this->applyInstituteFilters($query, $request);
        
        // Apply sorting
        $this->applyInstituteSorting($query, $request);
        
        $institutes = $query->paginate(12)->appends($request->query());
        
        // Get filter options
        $filterOptions = $this->getInstituteFilterOptions();
        
        return view('search.institutes', [
            'institutes' => $institutes,
            'filters' => $filterOptions,
            'currentFilters' => $request->all(),
            'totalResults' => $institutes->total(),
        ]);
    }
    
    public function instituteListing()
    {
        return $this->institutes(request());
    }
    
    public function instituteProfile($slug)
    {
        $institute = Institute::where('slug', $slug)
            ->orWhere('id', $slug)
            ->where('verified', true)
            ->where('is_active', true)
            ->with(['user', 'subjects', 'teachers.user', 'reviews', 'branches'])
            ->firstOrFail();
        
        // Increment view count
        $institute->increment('profile_views');
        
        // Get institute teachers
        $teachers = $institute->teachers()
            ->where('verified', true)
            ->where('is_active', true)
            ->with(['user', 'subjects'])
            ->paginate(8);
        
        // Get related institutes
        $relatedInstitutes = $this->getRelatedInstitutes($institute);
        
        return view('search.institute-profile', [
            'institute' => $institute,
            'teachers' => $teachers,
            'relatedInstitutes' => $relatedInstitutes,
        ]);
    }
    
    public function instituteTeachers($slug)
    {
        $institute = Institute::where('slug', $slug)
            ->orWhere('id', $slug)
            ->where('verified', true)
            ->where('is_active', true)
            ->firstOrFail();
        
        $teachers = $institute->teachers()
            ->where('verified', true)
            ->where('is_active', true)
            ->with(['user', 'subjects'])
            ->paginate(12);
        
        return view('search.institute-teachers', [
            'institute' => $institute,
            'teachers' => $teachers,
        ]);
    }
    
    public function institutesByCity($city)
    {
        $query = Institute::where('verified', true)
            ->where('is_active', true)
            ->where('city', 'like', "%{$city}%")
            ->with(['user', 'subjects', 'teachers']);
        
        $this->applyInstituteSorting($query, request());
        
        $institutes = $query->paginate(12);
        
        return view('search.institutes-by-city', [
            'institutes' => $institutes,
            'city' => $city,
            'totalResults' => $institutes->total(),
        ]);
    }
    
    // =======================
    // SUBJECT AND SEARCH
    // =======================
    
    public function subjects(Request $request)
    {
        $subjects = Subject::where('is_active', true)
            ->orderBy('name')
            ->paginate(24);
        
        return view('search.subjects', [
            'subjects' => $subjects,
        ]);
    }
    
    public function advanced(Request $request)
    {
        $teacherQuery = TeacherProfile::where('verified', true)
            ->where('is_active', true)
            ->with(['user', 'subjects', 'institute']);
        
        $instituteQuery = Institute::where('verified', true)
            ->where('is_active', true)
            ->with(['user', 'subjects', 'teachers']);
        
        // Apply advanced filters
        $this->applyAdvancedFilters($teacherQuery, $instituteQuery, $request);
        
        $teachers = $teacherQuery->take(6)->get();
        $institutes = $instituteQuery->take(6)->get();
        
        return view('search.advanced', [
            'teachers' => $teachers,
            'institutes' => $institutes,
            'currentFilters' => $request->all(),
            'filterOptions' => [
                'subjects' => Subject::where('is_active', true)->get(),
                'cities' => $this->getPopularCities(),
                'experience_levels' => ['0-2 years', '2-5 years', '5-10 years', '10+ years'],
                'teaching_modes' => ['online', 'offline', 'both'],
            ],
        ]);
    }
    
    public function nearby(Request $request)
    {
        $latitude = $request->get('lat');
        $longitude = $request->get('lng');
        $radius = $request->get('radius', 10); // km
        
        if (!$latitude || !$longitude) {
            return redirect()->route('search.teachers')
                ->with('error', 'Location coordinates required for nearby search.');
        }
        
        // For SQLite compatibility, we'll use a simpler approach
        $teachers = TeacherProfile::where('verified', true)
            ->where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['user', 'subjects', 'institute'])
            ->paginate(12);
        
        return view('search.nearby', [
            'teachers' => $teachers,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radius' => $radius,
        ]);
    }
    
    public function filter(Request $request)
    {
        $type = $request->get('type', 'teachers');
        
        if ($type === 'institutes') {
            return $this->institutes($request);
        }
        
        return $this->teachers($request);
    }
    
    // =======================
    // HELPER METHODS
    // =======================
    
    private function applyTeacherFilters($query, Request $request)
    {
        // Search term
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })->orWhere('bio', 'like', "%{$search}%")
                  ->orWhere('qualification', 'like', "%{$search}%");
            });
        }
        
        // Location filters
        if ($request->filled('city')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('city', 'like', "%{$request->city}%");
            });
        }
        
        if ($request->filled('state')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('state', 'like', "%{$request->state}%");
            });
        }
        
        // Subject filter
        if ($request->filled('subject')) {
            $query->whereHas('subjects', function($q) use ($request) {
                $q->where('subjects.id', $request->subject);
            });
        }
        
        // Experience filter
        if ($request->filled('experience')) {
            $exp = $request->experience;
            switch ($exp) {
                case '0-2':
                    $query->where('experience', '<=', 2);
                    break;
                case '2-5':
                    $query->whereBetween('experience', [2, 5]);
                    break;
                case '5-10':
                    $query->whereBetween('experience', [5, 10]);
                    break;
                case '10+':
                    $query->where('experience', '>=', 10);
                    break;
            }
        }
        
        // Teaching mode filter
        if ($request->filled('teaching_mode')) {
            $query->where('teaching_mode', $request->teaching_mode);
        }
        
        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('hourly_rate', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('hourly_rate', '<=', $request->max_price);
        }
        
        // Rating filter
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }
        
        // Availability filter
        if ($request->filled('available_now')) {
            $query->where('is_available', true);
        }
        
        // Verified only
        if ($request->filled('verified_only')) {
            $query->where('verified', true);
        }
    }
    
    private function applyTeacherSorting($query, Request $request)
    {
        $sort = $request->get('sort', 'rating');
        
        switch ($sort) {
            case 'name':
                $query->join('users', 'teacher_profiles.user_id', '=', 'users.id')
                      ->orderBy('users.name', 'asc');
                break;
            case 'price_low':
                $query->orderBy('hourly_rate', 'asc');
                break;
            case 'price_high':
                $query->orderBy('hourly_rate', 'desc');
                break;
            case 'experience':
                $query->orderBy('experience', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'rating':
            default:
                $query->orderBy('rating', 'desc')
                      ->orderBy('total_reviews', 'desc');
                break;
        }
    }
    
    private function applyInstituteFilters($query, Request $request)
    {
        // Search term
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('institute_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Location filters
        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }
        
        if ($request->filled('state')) {
            $query->where('state', 'like', "%{$request->state}%");
        }
        
        // Institute type filter
        if ($request->filled('institute_type')) {
            $query->where('institute_type', $request->institute_type);
        }
        
        // Establishment year filter
        if ($request->filled('min_year')) {
            $query->where('established_year', '>=', $request->min_year);
        }
        
        // Rating filter
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }
        
        // Facilities filter
        if ($request->filled('facilities')) {
            $facilities = is_array($request->facilities) ? $request->facilities : [$request->facilities];
            foreach ($facilities as $facility) {
                $query->whereJsonContains('facilities', $facility);
            }
        }
    }
    
    private function applyInstituteSorting($query, Request $request)
    {
        $sort = $request->get('sort', 'rating');
        
        switch ($sort) {
            case 'name':
                $query->orderBy('institute_name', 'asc');
                break;
            case 'established':
                $query->orderBy('established_year', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'rating':
            default:
                $query->orderBy('rating', 'desc')
                      ->orderBy('total_reviews', 'desc');
                break;
        }
    }
    
    private function applyAdvancedFilters($teacherQuery, $instituteQuery, Request $request)
    {
        // Apply common filters to both queries
        if ($request->filled('search')) {
            $search = $request->search;
            
            $teacherQuery->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })->orWhere('bio', 'like', "%{$search}%");
            });
            
            $instituteQuery->where(function($q) use ($search) {
                $q->where('institute_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('city')) {
            $teacherQuery->whereHas('user', function($q) use ($request) {
                $q->where('city', 'like', "%{$request->city}%");
            });
            
            $instituteQuery->where('city', 'like', "%{$request->city}%");
        }
        
        if ($request->filled('subject')) {
            $teacherQuery->whereHas('subjects', function($q) use ($request) {
                $q->where('subjects.id', $request->subject);
            });
            
            $instituteQuery->whereHas('subjects', function($q) use ($request) {
                $q->where('subjects.id', $request->subject);
            });
        }
        
        // Teacher-specific filters
        if ($request->filled('experience') && $request->experience !== 'all') {
            $exp = $request->experience;
            switch ($exp) {
                case '0-2':
                    $teacherQuery->where('experience', '<=', 2);
                    break;
                case '2-5':
                    $teacherQuery->whereBetween('experience', [2, 5]);
                    break;
                case '5-10':
                    $teacherQuery->whereBetween('experience', [5, 10]);
                    break;
                case '10+':
                    $teacherQuery->where('experience', '>=', 10);
                    break;
            }
        }
        
        if ($request->filled('teaching_mode') && $request->teaching_mode !== 'all') {
            $teacherQuery->where('teaching_mode', $request->teaching_mode);
        }
        
        // Institute-specific filters
        if ($request->filled('institute_type') && $request->institute_type !== 'all') {
            $instituteQuery->where('institute_type', $request->institute_type);
        }
        
        if ($request->filled('min_year')) {
            $instituteQuery->where('established_year', '>=', $request->min_year);
        }
        
        // Common rating filter
        if ($request->filled('min_rating')) {
            $teacherQuery->where('rating', '>=', $request->min_rating);
            $instituteQuery->where('rating', '>=', $request->min_rating);
        }
    }
    
    private function getTeacherFilterOptions()
    {
        return Cache::remember('teacher_filter_options', 3600, function () {
            return [
                'subjects' => Subject::where('is_active', true)
                    ->whereHas('teachers')
                    ->orderBy('name')
                    ->get(),
                'cities' => $this->getPopularCities(),
                'experience_levels' => [
                    '0-2' => '0-2 years',
                    '2-5' => '2-5 years',
                    '5-10' => '5-10 years',
                    '10+' => '10+ years'
                ],
                'teaching_modes' => [
                    'online' => 'Online Only',
                    'offline' => 'Offline Only',
                    'both' => 'Both Online & Offline'
                ],
                'price_ranges' => [
                    '0-500' => '₹0 - ₹500',
                    '500-1000' => '₹500 - ₹1000',
                    '1000-2000' => '₹1000 - ₹2000',
                    '2000+' => '₹2000+'
                ]
            ];
        });
    }
    
    private function getInstituteFilterOptions()
    {
        return Cache::remember('institute_filter_options', 3600, function () {
            return [
                'cities' => $this->getPopularCities(),
                'institute_types' => [
                    'school' => 'School',
                    'college' => 'College',
                    'coaching' => 'Coaching Institute',
                    'university' => 'University',
                    'training_center' => 'Training Center'
                ],
                'facilities' => [
                    'library' => 'Library',
                    'computer_lab' => 'Computer Lab',
                    'sports' => 'Sports Facilities',
                    'canteen' => 'Canteen',
                    'hostel' => 'Hostel',
                    'transport' => 'Transport',
                    'playground' => 'Playground',
                    'auditorium' => 'Auditorium'
                ]
            ];
        });
    }
    
    private function getPopularCities()
    {
        return Cache::remember('popular_cities', 86400, function () {
            return User::select('city')
                ->whereNotNull('city')
                ->groupBy('city')
                ->orderByRaw('COUNT(*) DESC')
                ->take(20)
                ->pluck('city')
                ->toArray();
        });
    }
    
    private function getRelatedTeachers($teacher)
    {
        $subjectIds = $teacher->subjects->pluck('id');
        
        return TeacherProfile::where('id', '!=', $teacher->id)
            ->where('verified', true)
            ->where('is_active', true)
            ->whereHas('subjects', function($q) use ($subjectIds) {
                $q->whereIn('subjects.id', $subjectIds);
            })
            ->with(['user', 'subjects'])
            ->orderBy('rating', 'desc')
            ->take(4)
            ->get();
    }
    
    private function getRelatedInstitutes($institute)
    {
        $query = Institute::where('id', '!=', $institute->id)
            ->where('verified', true)
            ->where('is_active', true);
        
        // Only filter by city if it exists
        if (!empty($institute->city)) {
            $query->where('city', $institute->city);
        }
        
        return $query->with(['user', 'subjects'])
            ->orderBy('rating', 'desc')
            ->take(4)
            ->get();
    }
    
    // API methods for AJAX
    public function suggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $suggestions = [];
        
        // Teacher suggestions
        $teachers = TeacherProfile::whereHas('user', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->where('verified', true)
            ->take(5)
            ->with('user')
            ->get();
        
        foreach ($teachers as $teacher) {
            $suggestions[] = [
                'type' => 'teacher',
                'title' => $teacher->user->name,
                'subtitle' => 'Teacher',
                'url' => route('teachers.show', $teacher->slug ?? $teacher->id)
            ];
        }
        
        // Institute suggestions
        $institutes = Institute::where('institute_name', 'like', "%{$query}%")
            ->where('verified', true)
            ->take(5)
            ->get();
        
        foreach ($institutes as $institute) {
            $suggestions[] = [
                'type' => 'institute',
                'title' => $institute->institute_name,
                'subtitle' => 'Institute',
                'url' => route('institutes.show', $institute->slug ?? $institute->id)
            ];
        }
        
        // Subject suggestions
        $subjects = Subject::where('name', 'like', "%{$query}%")
            ->where('is_active', true)
            ->take(3)
            ->get();
        
        foreach ($subjects as $subject) {
            $suggestions[] = [
                'type' => 'subject',
                'title' => $subject->name,
                'subtitle' => 'Subject',
                'url' => route('teachers.by-subject', $subject->slug)
            ];
        }
        
        return response()->json($suggestions);
    }
    
    public function cities()
    {
        return response()->json($this->getPopularCities());
    }
    
    public function subjectsApi()
    {
        $subjects = Subject::where('is_active', true)
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();
        
        return response()->json($subjects);
    }
    
    public function toggleFavorite(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }
        
        $type = $request->get('type'); // teacher or institute
        $id = $request->get('id');
        
        // Implement favorite toggle logic here
        // This would typically involve a favorites table
        
        return response()->json([
            'success' => true,
            'is_favorite' => true, // or false based on toggle
            'message' => 'Added to favorites'
        ]);
    }
}
