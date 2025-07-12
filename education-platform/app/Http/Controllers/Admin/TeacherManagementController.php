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

        $query = User::with(['teacherProfile'])
            ->where('role', 'teacher');

        // Apply filters
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0], $dates[1]]);
            }
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('verified')) {
            $query->whereHas('teacherProfile', function ($q) use ($request) {
                $q->where('verified', $request->verified === 'true');
            });
        }

        if ($request->filled('teaching_mode')) {
            $query->whereHas('teacherProfile', function ($q) use ($request) {
                $q->where('teaching_mode', $request->teaching_mode);
            });
        }

        if ($request->filled('experience_range')) {
            $range = explode('-', $request->experience_range);
            if (count($range) == 2) {
                $query->whereHas('teacherProfile', function ($q) use ($range) {
                    $q->whereBetween('experience_years', [$range[0], $range[1]]);
                });
            }
        }

        // Get filtered data
        $teachers = $query->get();

        // Calculate statistics
        $stats = [
            'total_teachers' => $teachers->count(),
            'active_teachers' => $teachers->where('is_active', true)->count(),
            'verified_teachers' => $teachers->where('teacherProfile.verified', true)->count(),
            'unverified_teachers' => $teachers->where('teacherProfile.verified', false)->count(),
            'online_teachers' => $teachers->where('teacherProfile.teaching_mode', 'online')->count(),
            'offline_teachers' => $teachers->where('teacherProfile.teaching_mode', 'offline')->count(),
            'both_mode_teachers' => $teachers->where('teacherProfile.teaching_mode', 'both')->count(),
            'avg_experience' => $teachers->where('teacherProfile.experience_years', '>', 0)->avg('teacherProfile.experience_years') ?? 0,
            'avg_hourly_rate' => $teachers->where('teacherProfile.hourly_rate', '>', 0)->avg('teacherProfile.hourly_rate') ?? 0,
            'total_students' => $teachers->sum('teacherProfile.total_students'),
            'avg_rating' => $teachers->where('teacherProfile.rating', '>', 0)->avg('teacherProfile.rating') ?? 0,
        ];

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
                'count' => $count
            ];
        }

        // City-wise distribution
        $cityDistribution = $teachers->groupBy('city')
            ->map(function ($group) {
                return $group->count();
            })
            ->sortDesc()
            ->take(10);

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
                ];
            });

        // Recent activity
        $recentActivity = $teachers->sortByDesc('created_at')
            ->take(10)
            ->map(function ($teacher) {
                return [
                    'name' => $teacher->name,
                    'action' => 'Registered',
                    'date' => $teacher->created_at->format('M d, Y'),
                    'verified' => $teacher->teacherProfile->verified ?? false,
                ];
            });

        return response()->json([
            'stats' => $stats,
            'monthly_trend' => $monthlyTrend,
            'city_distribution' => $cityDistribution,
            'experience_distribution' => $experienceDistribution,
            'rate_distribution' => $rateDistribution,
            'rating_distribution' => $ratingDistribution,
            'top_teachers' => $topTeachers,
            'recent_activity' => $recentActivity,
            'filters_applied' => $request->only(['date_range', 'city', 'verified', 'teaching_mode', 'experience_range']),
        ]);
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