<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\Subject;
use App\Models\Institute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; // Added for trend analysis

class TeacherManagementController extends Controller
{
    /**
     * Display a listing of teachers
     */
    public function index(Request $request)
    {
        $query = User::with(['teacherProfile', 'institute'])
            ->where('role', 'teacher');

        // Filter by verification status
        if ($request->filled('verified')) {
            $query->whereHas('teacherProfile', function ($q) use ($request) {
                $q->where('verified', $request->verified === 'verified');
            });
        }

        // Filter by teaching mode
        if ($request->filled('teaching_mode')) {
            $query->whereHas('teacherProfile', function ($q) use ($request) {
                $q->where('teaching_mode', $request->teaching_mode);
            });
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('teacherProfile', function ($tq) use ($search) {
                      $tq->where('specialization', 'like', "%{$search}%");
                  });
            });
        }

        $teachers = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get filter options
        $cities = User::where('role', 'teacher')->distinct()->pluck('city')->filter()->sort();
        $teachingModes = ['online', 'offline', 'both'];

        return view('admin.teachers.index', compact('teachers', 'cities', 'teachingModes'));
    }

    /**
     * Show the form for creating a new teacher
     */
    public function create()
    {
        $subjects = Subject::active()->get();
        $institutes = Institute::where('verified', true)->get();
        return view('admin.teachers.create', compact('subjects', 'institutes'));
    }

    /**
     * Store a newly created teacher
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'qualification' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0|max:50',
            'hourly_rate' => 'required|numeric|min:0',
            'teaching_mode' => 'required|in:online,offline,both',
            'bio' => 'nullable|string',
            'languages' => 'nullable|array',
            'verified' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'country' => 'India',
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => now(),
        ]);

        // Create teacher profile
        TeacherProfile::create([
            'user_id' => $user->id,
            'qualification' => $request->qualification,
            'specialization' => $request->specialization,
            'experience_years' => $request->experience_years,
            'hourly_rate' => $request->hourly_rate,
            'teaching_mode' => $request->teaching_mode,
            'bio' => $request->bio,
            'languages' => json_encode($request->languages ?? ['English', 'Hindi']),
            'verified' => $request->boolean('verified', false),
            'teaching_city' => $request->city,
            'teaching_state' => $request->state,
            'teaching_pincode' => $request->pincode,
            'travel_radius_km' => $request->travel_radius_km ?? 10,
            'home_tuition' => $request->boolean('home_tuition', true),
            'institute_classes' => $request->boolean('institute_classes', true),
            'online_classes' => $request->boolean('online_classes', true),
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified teacher
     */
    public function show(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        $teacher->load(['teacherProfile', 'institute']);
        return view('admin.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher
     */
    public function edit(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        $teacher->load('teacherProfile');
        $subjects = Subject::active()->get();
        $institutes = Institute::where('verified', true)->get();
        
        return view('admin.teachers.edit', compact('teacher', 'subjects', 'institutes'));
    }

    /**
     * Update the specified teacher
     */
    public function update(Request $request, User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'qualification' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0|max:50',
            'hourly_rate' => 'required|numeric|min:0',
            'teaching_mode' => 'required|in:online,offline,both',
            'bio' => 'nullable|string',
            'languages' => 'nullable|array',
            'verified' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update user
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $teacher->update($updateData);

        // Update teacher profile
        $teacher->teacherProfile->update([
            'qualification' => $request->qualification,
            'specialization' => $request->specialization,
            'experience_years' => $request->experience_years,
            'hourly_rate' => $request->hourly_rate,
            'teaching_mode' => $request->teaching_mode,
            'bio' => $request->bio,
            'languages' => json_encode($request->languages ?? ['English', 'Hindi']),
            'verified' => $request->boolean('verified', false),
            'teaching_city' => $request->city,
            'teaching_state' => $request->state,
            'teaching_pincode' => $request->pincode,
            'travel_radius_km' => $request->travel_radius_km ?? 10,
            'home_tuition' => $request->boolean('home_tuition', true),
            'institute_classes' => $request->boolean('institute_classes', true),
            'online_classes' => $request->boolean('online_classes', true),
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified teacher
     */
    public function destroy(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }

    /**
     * Verify teacher account
     */
    public function verify(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        $teacher->teacherProfile->update(['verified' => true]);

        return redirect()->back()
            ->with('success', 'Teacher verified successfully.');
    }

    /**
     * Unverify teacher account
     */
    public function unverify(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        $teacher->teacherProfile->update(['verified' => false]);

        return redirect()->back()
            ->with('success', 'Teacher verification removed.');
    }

    /**
     * Toggle teacher status
     */
    public function toggleStatus(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        $teacher->update(['is_active' => !$teacher->is_active]);

        $status = $teacher->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Teacher {$status} successfully.");
    }

    /**
     * Display teacher statistics page
     */
    public function statisticsPage()
    {
        return view('admin.teachers.statistics');
    }

    /**
     * Get comprehensive teacher statistics with filters
     */
    public function statistics(Request $request)
    {
        // If it's a direct request (not AJAX), return the view
        if (!$request->ajax() && !$request->has('export')) {
            return view('admin.teachers.statistics-data');
        }

        $query = User::with(['teacherProfile', 'roles', 'permissions'])
            ->where('role', 'teacher');

        // Apply advanced filters
        $this->applyAdvancedFilters($query, $request);

        // Get filtered data
        $teachers = $query->get();

        // Calculate comprehensive statistics
        $stats = $this->calculateComprehensiveStats($teachers);
        
        // Get dynamic analytics
        $analytics = $this->getDynamicAnalytics($teachers, $request);
        
        // Get real-time metrics
        $realTimeMetrics = $this->getRealTimeMetrics();
        
        // Get performance insights
        $performanceInsights = $this->getPerformanceInsights($teachers);
        
        // Get geographic analytics
        $geographicAnalytics = $this->getGeographicAnalytics($teachers);
        
        // Get trend analysis
        $trendAnalysis = $this->getTrendAnalysis($request);
        
        // Get quality metrics
        $qualityMetrics = $this->getQualityMetrics($teachers);
        
        // Get engagement analytics
        $engagementAnalytics = $this->getEngagementAnalytics($teachers);
        
        // Get financial insights
        $financialInsights = $this->getFinancialInsights($teachers);
        
        // Get system health data
        $systemHealth = $this->getSystemHealthData();

        $response = [
            'stats' => $stats,
            'analytics' => $analytics,
            'real_time_metrics' => $realTimeMetrics,
            'performance_insights' => $performanceInsights,
            'geographic_analytics' => $geographicAnalytics,
            'trend_analysis' => $trendAnalysis,
            'quality_metrics' => $qualityMetrics,
            'engagement_analytics' => $engagementAnalytics,
            'financial_insights' => $financialInsights,
            'system_health' => $systemHealth,
            'filters_applied' => $request->only(['date_range', 'city', 'verified', 'teaching_mode', 'experience_range', 'rating_range', 'hourly_rate_range', 'subject', 'qualification', 'status']),
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'data_points' => $teachers->count(),
        ];

        // Handle export
        if ($request->has('export')) {
            return $this->exportStatistics($response, $request->export);
        }

        return response()->json($response);
    }

    /**
     * Apply advanced filters to the query
     */
    private function applyAdvancedFilters($query, $request)
    {
        // Date range filter
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0], $dates[1]]);
            }
        }

        // City filter
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Verification status filter
        if ($request->filled('verified')) {
            $query->whereHas('teacherProfile', function ($q) use ($request) {
                $q->where('verified', $request->verified === 'true');
            });
        }

        // Teaching mode filter
        if ($request->filled('teaching_mode')) {
            $query->whereHas('teacherProfile', function ($q) use ($request) {
                $q->where('teaching_mode', $request->teaching_mode);
            });
        }

        // Experience range filter
        if ($request->filled('experience_range')) {
            $range = explode('-', $request->experience_range);
            if (count($range) == 2) {
                $query->whereHas('teacherProfile', function ($q) use ($range) {
                    $q->whereBetween('experience_years', [$range[0], $range[1]]);
                });
            }
        }

        // Rating range filter
        if ($request->filled('rating_range')) {
            $range = explode('-', $request->rating_range);
            if (count($range) == 2) {
                $query->whereHas('teacherProfile', function ($q) use ($range) {
                    $q->whereBetween('rating', [$range[0], $range[1]]);
                });
            }
        }

        // Hourly rate range filter
        if ($request->filled('hourly_rate_range')) {
            $range = explode('-', $request->hourly_rate_range);
            if (count($range) == 2) {
                $query->whereHas('teacherProfile', function ($q) use ($range) {
                    $q->whereBetween('hourly_rate', [$range[0], $range[1]]);
                });
            }
        }

        // Subject filter
        if ($request->filled('subject')) {
            $query->whereHas('teacherProfile.subjects', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->subject . '%');
            });
        }

        // Qualification filter
        if ($request->filled('qualification')) {
            $query->whereHas('teacherProfile', function ($q) use ($request) {
                $q->where('qualifications', 'like', '%' . $request->qualification . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
    }

    /**
     * Calculate comprehensive statistics
     */
    private function calculateComprehensiveStats($teachers)
    {
        return [
            'total_teachers' => $teachers->count(),
            'active_teachers' => $teachers->where('is_active', true)->count(),
            'inactive_teachers' => $teachers->where('is_active', false)->count(),
            'verified_teachers' => $teachers->where('teacherProfile.verified', true)->count(),
            'unverified_teachers' => $teachers->where('teacherProfile.verified', false)->count(),
            'online_teachers' => $teachers->where('teacherProfile.teaching_mode', 'online')->count(),
            'offline_teachers' => $teachers->where('teacherProfile.teaching_mode', 'offline')->count(),
            'both_mode_teachers' => $teachers->where('teacherProfile.teaching_mode', 'both')->count(),
            'avg_experience' => $teachers->where('teacherProfile.experience_years', '>', 0)->avg('teacherProfile.experience_years') ?? 0,
            'avg_hourly_rate' => $teachers->where('teacherProfile.hourly_rate', '>', 0)->avg('teacherProfile.hourly_rate') ?? 0,
            'total_students' => $teachers->sum('teacherProfile.total_students'),
            'avg_rating' => $teachers->where('teacherProfile.rating', '>', 0)->avg('teacherProfile.rating') ?? 0,
            'total_reviews' => $teachers->sum('teacherProfile.total_reviews'),
            'avg_sessions_per_teacher' => $teachers->avg('teacherProfile.total_sessions') ?? 0,
            'completion_rate' => $this->calculateCompletionRate($teachers),
            'response_rate' => $this->calculateResponseRate($teachers),
            'satisfaction_score' => $this->calculateSatisfactionScore($teachers),
        ];
    }

    /**
     * Get dynamic analytics
     */
    private function getDynamicAnalytics($teachers, $request)
    {
        // Monthly registration trend (last 12 months)
        $monthlyTrend = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = User::where('role', 'teacher')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthlyTrend[] = [
                'month' => $date->format('M Y'),
                'count' => $count,
                'growth_rate' => $this->calculateGrowthRate($date)
            ];
        }

        // City-wise distribution
        $cityDistribution = $teachers->groupBy('city')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'verified_count' => $group->where('teacherProfile.verified', true)->count(),
                    'avg_rating' => $group->avg('teacherProfile.rating') ?? 0,
                    'avg_experience' => $group->avg('teacherProfile.experience_years') ?? 0,
                ];
            })
            ->sortByDesc('count')
            ->take(15);

        // Experience level distribution
        $experienceDistribution = [
            '0-2 years' => $teachers->where('teacherProfile.experience_years', '<=', 2)->count(),
            '3-5 years' => $teachers->whereBetween('teacherProfile.experience_years', [3, 5])->count(),
            '6-10 years' => $teachers->whereBetween('teacherProfile.experience_years', [6, 10])->count(),
            '11-15 years' => $teachers->whereBetween('teacherProfile.experience_years', [11, 15])->count(),
            '15+ years' => $teachers->where('teacherProfile.experience_years', '>', 15)->count(),
        ];

        // Hourly rate distribution
        $rateDistribution = [
            '₹0-500' => $teachers->where('teacherProfile.hourly_rate', '<=', 500)->count(),
            '₹501-1000' => $teachers->whereBetween('teacherProfile.hourly_rate', [501, 1000])->count(),
            '₹1001-2000' => $teachers->whereBetween('teacherProfile.hourly_rate', [1001, 2000])->count(),
            '₹2001-5000' => $teachers->whereBetween('teacherProfile.hourly_rate', [2001, 5000])->count(),
            '₹5000+' => $teachers->where('teacherProfile.hourly_rate', '>', 5000)->count(),
        ];

        // Rating distribution
        $ratingDistribution = [
            '5.0' => $teachers->where('teacherProfile.rating', 5.0)->count(),
            '4.5-4.9' => $teachers->whereBetween('teacherProfile.rating', [4.5, 4.9])->count(),
            '4.0-4.4' => $teachers->whereBetween('teacherProfile.rating', [4.0, 4.4])->count(),
            '3.5-3.9' => $teachers->whereBetween('teacherProfile.rating', [3.5, 3.9])->count(),
            '3.0-3.4' => $teachers->whereBetween('teacherProfile.rating', [3.0, 3.4])->count(),
            'Below 3.0' => $teachers->where('teacherProfile.rating', '<', 3.0)->count(),
        ];

        return [
            'monthly_trend' => $monthlyTrend,
            'city_distribution' => $cityDistribution,
            'experience_distribution' => $experienceDistribution,
            'rate_distribution' => $rateDistribution,
            'rating_distribution' => $ratingDistribution,
        ];
    }

    /**
     * Get real-time metrics
     */
    private function getRealTimeMetrics()
    {
        return [
            'online_now' => User::where('role', 'teacher')
                ->where('last_activity_at', '>=', now()->subMinutes(5))
                ->count(),
            'active_today' => User::where('role', 'teacher')
                ->whereDate('last_activity_at', today())
                ->count(),
            'new_this_week' => User::where('role', 'teacher')
                ->where('created_at', '>=', now()->subWeek())
                ->count(),
            'pending_verifications' => TeacherProfile::where('verified', false)
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
        ];
    }

    /**
     * Get performance insights
     */
    private function getPerformanceInsights($teachers)
    {
        // Top performing teachers
        $topTeachers = $teachers->sortByDesc('teacherProfile.rating')
            ->take(10)
            ->map(function ($teacher) {
                return [
                    'name' => $teacher->name,
                    'rating' => $teacher->teacherProfile->rating ?? 0,
                    'students' => $teacher->teacherProfile->total_students ?? 0,
                    'experience' => $teacher->teacherProfile->experience_years ?? 0,
                    'city' => $teacher->city,
                    'hourly_rate' => $teacher->teacherProfile->hourly_rate ?? 0,
                    'verified' => $teacher->teacherProfile->verified ?? false,
                    'completion_rate' => $this->calculateTeacherCompletionRate($teacher),
                ];
            });

        // Most active teachers
        $mostActiveTeachers = $teachers->sortByDesc('teacherProfile.total_sessions')
            ->take(10)
            ->map(function ($teacher) {
                return [
                    'name' => $teacher->name,
                    'sessions' => $teacher->teacherProfile->total_sessions ?? 0,
                    'students' => $teacher->teacherProfile->total_students ?? 0,
                    'rating' => $teacher->teacherProfile->rating ?? 0,
                    'city' => $teacher->city,
                ];
            });

        // Highest earning teachers
        $highestEarningTeachers = $teachers->sortByDesc('teacherProfile.hourly_rate')
            ->take(10)
            ->map(function ($teacher) {
                return [
                    'name' => $teacher->name,
                    'hourly_rate' => $teacher->teacherProfile->hourly_rate ?? 0,
                    'rating' => $teacher->teacherProfile->rating ?? 0,
                    'experience' => $teacher->teacherProfile->experience_years ?? 0,
                    'city' => $teacher->city,
                ];
            });

        return [
            'top_teachers' => $topTeachers,
            'most_active_teachers' => $mostActiveTeachers,
            'highest_earning_teachers' => $highestEarningTeachers,
        ];
    }

    /**
     * Get geographic analytics
     */
    private function getGeographicAnalytics($teachers)
    {
        $geographicData = $teachers->groupBy('city')
            ->map(function ($group) {
                return [
                    'total' => $group->count(),
                    'verified' => $group->where('teacherProfile.verified', true)->count(),
                    'avg_rating' => $group->avg('teacherProfile.rating') ?? 0,
                    'avg_experience' => $group->avg('teacherProfile.experience_years') ?? 0,
                    'avg_hourly_rate' => $group->avg('teacherProfile.hourly_rate') ?? 0,
                    'total_students' => $group->sum('teacherProfile.total_students'),
                ];
            })
            ->sortByDesc('total');

        return [
            'city_breakdown' => $geographicData->take(20),
            'state_breakdown' => $this->getStateBreakdown($teachers),
            'country_breakdown' => $this->getCountryBreakdown($teachers),
        ];
    }

    /**
     * Get trend analysis
     */
    private function getTrendAnalysis($request)
    {
        $startDate = $request->filled('date_range') 
            ? explode(' to ', $request->date_range)[0] 
            : now()->subMonths(6);

        $endDate = $request->filled('date_range') 
            ? explode(' to ', $request->date_range)[1] 
            : now();

        $trends = [];
        $currentDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        while ($currentDate <= $endDate) {
            $date = $currentDate->format('Y-m-d');
            
            $trends[] = [
                'date' => $date,
                'new_registrations' => User::where('role', 'teacher')
                    ->whereDate('created_at', $date)
                    ->count(),
                'verifications' => TeacherProfile::where('verified', true)
                    ->whereDate('updated_at', $date)
                    ->count(),
                'active_teachers' => User::where('role', 'teacher')
                    ->where('is_active', true)
                    ->whereDate('last_activity_at', $date)
                    ->count(),
            ];

            $currentDate->addDay();
        }

        return $trends;
    }

    /**
     * Get quality metrics
     */
    private function getQualityMetrics($teachers)
    {
        return [
            'avg_response_time' => $this->calculateAverageResponseTime($teachers),
            'completion_rate' => $this->calculateCompletionRate($teachers),
            'satisfaction_score' => $this->calculateSatisfactionScore($teachers),
            'verification_rate' => $teachers->where('teacherProfile.verified', true)->count() / max($teachers->count(), 1) * 100,
            'profile_completion_rate' => $this->calculateProfileCompletionRate($teachers),
            'document_upload_rate' => $this->calculateDocumentUploadRate($teachers),
        ];
    }

    /**
     * Get engagement analytics
     */
    private function getEngagementAnalytics($teachers)
    {
        return [
            'active_sessions' => $teachers->sum('teacherProfile.total_sessions'),
            'avg_session_duration' => $this->calculateAverageSessionDuration($teachers),
            'student_retention_rate' => $this->calculateStudentRetentionRate($teachers),
            'review_response_rate' => $this->calculateReviewResponseRate($teachers),
            'platform_usage_frequency' => $this->calculatePlatformUsageFrequency($teachers),
        ];
    }

    /**
     * Get financial insights
     */
    private function getFinancialInsights($teachers)
    {
        return [
            'total_revenue_potential' => $teachers->sum('teacherProfile.hourly_rate') * 160, // Assuming 160 hours per month
            'avg_monthly_earnings' => $teachers->avg('teacherProfile.hourly_rate') * 160,
            'revenue_by_experience' => $this->calculateRevenueByExperience($teachers),
            'revenue_by_location' => $this->calculateRevenueByLocation($teachers),
            'commission_earned' => $this->calculateCommissionEarned($teachers),
        ];
    }

    /**
     * Get system health data
     */
    private function getSystemHealthData()
    {
        return [
            'database_performance' => $this->checkDatabasePerformance(),
            'cache_hit_rate' => $this->getCacheHitRate(),
            'error_rate' => $this->getErrorRate(),
            'uptime' => $this->getUptime(),
            'memory_usage' => $this->getMemoryUsage(),
            'disk_usage' => $this->getDiskUsage(),
        ];
    }

    /**
     * Export statistics
     */
    private function exportStatistics($data, $format = 'json')
    {
        switch ($format) {
            case 'csv':
                return $this->exportToCSV($data);
            case 'pdf':
                return $this->exportToPDF($data);
            case 'excel':
                return $this->exportToExcel($data);
            default:
                return response()->json($data);
        }
    }

    // Helper methods for calculations
    private function calculateGrowthRate($date)
    {
        $currentMonth = User::where('role', 'teacher')
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();

        $previousMonth = User::where('role', 'teacher')
            ->whereYear('created_at', $date->subMonth()->year)
            ->whereMonth('created_at', $date->subMonth()->month)
            ->count();

        return $previousMonth > 0 ? (($currentMonth - $previousMonth) / $previousMonth) * 100 : 0;
    }

    private function calculateCompletionRate($teachers)
    {
        $completedSessions = $teachers->sum('teacherProfile.completed_sessions') ?? 0;
        $totalSessions = $teachers->sum('teacherProfile.total_sessions') ?? 0;
        
        return $totalSessions > 0 ? ($completedSessions / $totalSessions) * 100 : 0;
    }

    private function calculateResponseRate($teachers)
    {
        $responsiveTeachers = $teachers->where('teacherProfile.response_time', '<=', 24)->count();
        return $teachers->count() > 0 ? ($responsiveTeachers / $teachers->count()) * 100 : 0;
    }

    private function calculateSatisfactionScore($teachers)
    {
        return $teachers->avg('teacherProfile.rating') ?? 0;
    }

    private function calculateTeacherCompletionRate($teacher)
    {
        $completed = $teacher->teacherProfile->completed_sessions ?? 0;
        $total = $teacher->teacherProfile->total_sessions ?? 0;
        return $total > 0 ? ($completed / $total) * 100 : 0;
    }

    private function getStateBreakdown($teachers)
    {
        // Implementation for state breakdown
        return collect();
    }

    private function getCountryBreakdown($teachers)
    {
        // Implementation for country breakdown
        return collect();
    }

    private function calculateAverageResponseTime($teachers)
    {
        return $teachers->avg('teacherProfile.response_time') ?? 0;
    }

    private function calculateProfileCompletionRate($teachers)
    {
        $completedProfiles = $teachers->filter(function ($teacher) {
            return $teacher->teacherProfile && 
                   $teacher->teacherProfile->qualifications &&
                   $teacher->teacherProfile->experience_years &&
                   $teacher->teacherProfile->hourly_rate;
        })->count();

        return $teachers->count() > 0 ? ($completedProfiles / $teachers->count()) * 100 : 0;
    }

    private function calculateDocumentUploadRate($teachers)
    {
        // Implementation for document upload rate
        return 85.5; // Placeholder
    }

    private function calculateAverageSessionDuration($teachers)
    {
        // Implementation for average session duration
        return 60; // Placeholder in minutes
    }

    private function calculateStudentRetentionRate($teachers)
    {
        // Implementation for student retention rate
        return 78.3; // Placeholder
    }

    private function calculateReviewResponseRate($teachers)
    {
        // Implementation for review response rate
        return 92.1; // Placeholder
    }

    private function calculatePlatformUsageFrequency($teachers)
    {
        // Implementation for platform usage frequency
        return 4.2; // Placeholder - average sessions per week
    }

    private function calculateRevenueByExperience($teachers)
    {
        // Implementation for revenue by experience
        return [
            '0-2 years' => 15000,
            '3-5 years' => 25000,
            '6-10 years' => 35000,
            '11-15 years' => 45000,
            '15+ years' => 55000,
        ];
    }

    private function calculateRevenueByLocation($teachers)
    {
        // Implementation for revenue by location
        return $teachers->groupBy('city')
            ->map(function ($group) {
                return $group->sum('teacherProfile.hourly_rate') * 160;
            })
            ->sortDesc()
            ->take(10);
    }

    private function calculateCommissionEarned($teachers)
    {
        // Implementation for commission earned
        return $teachers->sum('teacherProfile.hourly_rate') * 160 * 0.15; // 15% commission
    }

    private function checkDatabasePerformance()
    {
        // Implementation for database performance check
        return 'excellent';
    }

    private function getCacheHitRate()
    {
        // Implementation for cache hit rate
        return 95.2;
    }

    private function getErrorRate()
    {
        // Implementation for error rate
        return 0.1;
    }

    private function getUptime()
    {
        // Implementation for uptime
        return 99.9;
    }

    private function getMemoryUsage()
    {
        // Implementation for memory usage
        return 65.3;
    }

    private function getDiskUsage()
    {
        // Implementation for disk usage
        return 45.7;
    }

    private function exportToCSV($data)
    {
        // Implementation for CSV export
        return response()->json(['message' => 'CSV export not implemented yet']);
    }

    private function exportToPDF($data)
    {
        // Implementation for PDF export
        return response()->json(['message' => 'PDF export not implemented yet']);
    }

    private function exportToExcel($data)
    {
        // Implementation for Excel export
        return response()->json(['message' => 'Excel export not implemented yet']);
    }

    /**
     * Bulk verify teachers
     */
    public function bulkVerify(Request $request)
    {
        $request->validate([
            'teacher_ids' => 'required|array',
            'teacher_ids.*' => 'exists:users,id',
        ]);

        $updated = 0;
        foreach ($request->teacher_ids as $teacherId) {
            $teacher = User::where('id', $teacherId)->where('role', 'teacher')->first();
            if ($teacher && $teacher->teacherProfile) {
                $teacher->teacherProfile->update(['verified' => true]);
                $updated++;
            }
        }

        return redirect()->back()
            ->with('success', "Verified {$updated} teacher(s) successfully.");
    }

    /**
     * Bulk activate/deactivate teachers
     */
    public function bulkToggleStatus(Request $request)
    {
        $request->validate([
            'teacher_ids' => 'required|array',
            'teacher_ids.*' => 'exists:users,id',
            'action' => 'required|in:activate,deactivate',
        ]);

        $isActive = $request->action === 'activate';
        $updated = 0;

        foreach ($request->teacher_ids as $teacherId) {
            $teacher = User::where('id', $teacherId)->where('role', 'teacher')->first();
            if ($teacher) {
                $teacher->update(['is_active' => $isActive]);
                $updated++;
            }
        }

        $action = $isActive ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Successfully {$action} {$updated} teacher(s).");
    }
} 