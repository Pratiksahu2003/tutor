<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\Institute;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\Lead;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $dashboardData = $this->getDashboardData($user);
        
        return view('dashboard.unified', compact('user', 'dashboardData'));
    }

    /**
     * Display the modern dashboard
     */
    public function modern()
    {
        $user = Auth::user();
        $dashboardData = $this->getDashboardData($user);
        
        return view('dashboard.modern', compact('user', 'dashboardData'));
    }

    /**
     * Show profile edit form
     */
    public function profile()
    {
        $user = Auth::user();
        $subjects = Subject::active()->get();
        $institutes = Institute::where('verified', true)->get();
        
        // Get role-specific profile data
        $profileData = $this->getProfileData($user);
        
        return view('dashboard.profile', compact('user', 'subjects', 'institutes', 'profileData'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Base validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        // Add role-specific validation
        $rules = array_merge($rules, $this->getRoleSpecificValidationRules($user->role, $request));

        $validatedData = $request->validate($rules);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            $validatedData['profile_image'] = $imagePath;
        }

        // Update user basic information
        $user->update($validatedData);

        // Update role-specific profile
        $this->updateRoleSpecificProfile($user, $request);

        return redirect()->route('dashboard.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('dashboard.profile')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Get dashboard data based on user role
     */
    public function getDashboardData($user)
    {
        $baseData = [
            'user' => $user,
            'notifications' => $this->getNotifications($user),
            'recent_activities' => $this->getRecentActivities($user),
        ];

        switch ($user->role) {
            case 'admin':
                return array_merge($baseData, $this->getAdminDashboardData($user));
            case 'teacher':
                return array_merge($baseData, $this->getTeacherDashboardData($user));
            case 'institute':
                return array_merge($baseData, $this->getInstituteDashboardData($user));
            case 'student':
                return array_merge($baseData, $this->getStudentDashboardData($user));
            default:
                return $baseData;
        }
    }

    /**
     * Get admin dashboard data
     */
    private function getAdminDashboardData($user)
    {
        return [
            'stats' => [
                'total_users' => User::count(),
                'total_teachers' => User::where('role', 'teacher')->count(),
                'total_institutes' => User::where('role', 'institute')->count(),
                'total_students' => User::where('role', 'student')->count(),
                'pending_verifications' => TeacherProfile::where('verified', false)->count() + 
                                          Institute::where('verified', false)->count(),
                'total_leads' => Lead::count(),
                'leads_this_month' => Lead::whereMonth('created_at', now()->month)->count(),
            ],
            'pending_teachers' => TeacherProfile::with('user')->where('verified', false)->limit(5)->get(),
            'pending_institutes' => Institute::with('user')->where('verified', false)->limit(5)->get(),
            'recent_registrations' => User::latest()->limit(10)->get(),
        ];
    }

    /**
     * Get teacher dashboard data
     */
    private function getTeacherDashboardData($user)
    {
        $teacherProfile = $user->teacherProfile;
        
        if (!$teacherProfile) {
            return ['needs_profile_setup' => true];
        }

        return [
            'teacher_profile' => $teacherProfile,
            'stats' => [
                'total_students' => $teacherProfile->total_students,
                'rating' => $teacherProfile->rating,
                'hourly_rate' => $teacherProfile->hourly_rate,
                'subjects_count' => $teacherProfile->subjects()->count(),
                'sessions_this_month' => 0, // TODO: Implement sessions tracking
                'earnings_this_month' => 0, // TODO: Implement earnings tracking
            ],
            'subjects' => $teacherProfile->subjects,
            'institute' => $teacherProfile->institute,
            'verification_status' => [
                'admin_verified' => $teacherProfile->verified,
                'institute_verified' => $teacherProfile->is_institute_verified,
                'documents_submitted' => true, // TODO: Implement document tracking
            ],
        ];
    }

    /**
     * Get institute dashboard data
     */
    private function getInstituteDashboardData($user)
    {
        $institute = $user->institute;
        
        if (!$institute) {
            return ['needs_profile_setup' => true];
        }

        return [
            'institute' => $institute,
            'stats' => [
                'total_teachers' => $institute->teachers()->count(),
                'verified_teachers' => $institute->verifiedTeachers()->count(),
                'pending_teachers' => $institute->pendingTeachers()->count(),
                'total_students' => $institute->total_students,
                'rating' => $institute->rating,
                'established_year' => $institute->established_year,
            ],
            'teachers' => $institute->teachers()->with('user')->limit(5)->get(),
            'pending_teachers' => $institute->pendingTeachers()->with('user')->limit(5)->get(),
            'verification_status' => [
                'admin_verified' => $institute->verified,
                'documents_submitted' => true, // TODO: Implement document tracking
            ],
        ];
    }

    /**
     * Get student dashboard data
     */
    private function getStudentDashboardData($user)
    {
        $studentProfile = $user->studentProfile;
        
        if (!$studentProfile) {
            return ['needs_profile_setup' => true];
        }

        return [
            'student_profile' => $studentProfile,
            'stats' => [
                'subjects_interested' => is_array($studentProfile->subjects_interested) ? count($studentProfile->subjects_interested) : 0,
                'budget_range' => $studentProfile->budget_min && $studentProfile->budget_max ? 
                    '₹' . $studentProfile->budget_min . ' - ₹' . $studentProfile->budget_max : 'Not set',
                'learning_mode' => $studentProfile->preferred_learning_mode,
                'profile_completion' => $this->calculateProfileCompletion($studentProfile),
            ],
            'recommended_teachers' => $this->getRecommendedTeachers($studentProfile),
            'recent_inquiries' => Lead::where('email', $user->email)->latest()->limit(5)->get(),
        ];
    }

    /**
     * Get profile data for editing
     */
    private function getProfileData($user)
    {
        switch ($user->role) {
            case 'teacher':
                return [
                    'profile' => $user->teacherProfile,
                    'subjects' => $user->teacherProfile ? $user->teacherProfile->subjects->pluck('id')->toArray() : [],
                ];
            case 'institute':
                return [
                    'profile' => $user->institute,
                ];
            case 'student':
                return [
                    'profile' => $user->studentProfile,
                    'subjects_interested' => $user->studentProfile ? $user->studentProfile->subjects_interested : [],
                ];
            default:
                return [];
        }
    }

    /**
     * Get role-specific validation rules
     */
    private function getRoleSpecificValidationRules($role, $request)
    {
        switch ($role) {
            case 'teacher':
                return [
                    'qualification' => 'nullable|string|max:255',
                    'bio' => 'nullable|string|max:1000',
                    'experience_years' => 'nullable|integer|min:0|max:50',
                    'hourly_rate' => 'nullable|numeric|min:0',
                    'specialization' => 'nullable|string|max:255',
                    'teaching_mode' => 'nullable|in:online,offline,both',
                    'teaching_city' => 'nullable|string|max:100',
                    'teaching_state' => 'nullable|string|max:100',
                    'teaching_area' => 'nullable|string|max:255',
                    'travel_radius_km' => 'nullable|numeric|min:0|max:100',
                    'subjects' => 'nullable|array',
                    'subjects.*' => 'exists:subjects,id',
                ];
            case 'institute':
                return [
                    'institute_name' => 'nullable|string|max:255',
                    'description' => 'nullable|string|max:2000',
                    'registration_number' => 'nullable|string|max:255',
                    'website' => 'nullable|url|max:255',
                    'contact_person' => 'nullable|string|max:255',
                    'contact_phone' => 'nullable|string|max:20',
                    'established_year' => 'nullable|integer|min:1800|max:' . date('Y'),
                    'facilities' => 'nullable|array',
                ];
            case 'student':
                return [
                    'date_of_birth' => 'nullable|date|before:today',
                    'gender' => 'nullable|in:male,female,other',
                    'current_class' => 'nullable|string|max:50',
                    'school_name' => 'nullable|string|max:255',
                    'board' => 'nullable|string|max:50',
                    'area' => 'nullable|string|max:255',
                    'preferred_learning_mode' => 'nullable|in:online,offline,both',
                    'budget_min' => 'nullable|numeric|min:0',
                    'budget_max' => 'nullable|numeric|min:0',
                    'parent_phone' => 'nullable|string|max:20',
                    'subjects_interested' => 'nullable|array',
                    'subjects_interested.*' => 'exists:subjects,id',
                ];
            default:
                return [];
        }
    }

    /**
     * Update role-specific profile
     */
    private function updateRoleSpecificProfile($user, $request)
    {
        switch ($user->role) {
            case 'teacher':
                $this->updateTeacherProfile($user, $request);
                break;
            case 'institute':
                $this->updateInstituteProfile($user, $request);
                break;
            case 'student':
                $this->updateStudentProfile($user, $request);
                break;
        }
    }

    /**
     * Update teacher profile
     */
    private function updateTeacherProfile($user, $request)
    {
        $profileData = $request->only([
            'qualification', 'bio', 'experience_years', 'hourly_rate', 'specialization',
            'teaching_mode', 'teaching_city', 'teaching_state', 'teaching_area', 'travel_radius_km'
        ]);

        if ($user->teacherProfile) {
            $user->teacherProfile->update($profileData);
        } else {
            $user->teacherProfile()->create($profileData);
        }

        // Update subjects
        if ($request->has('subjects')) {
            $user->teacherProfile->subjects()->sync($request->subjects);
        }
    }

    /**
     * Update institute profile
     */
    private function updateInstituteProfile($user, $request)
    {
        $profileData = $request->only([
            'institute_name', 'description', 'registration_number', 'website',
            'contact_person', 'contact_phone', 'established_year', 'facilities'
        ]);

        if ($user->institute) {
            $user->institute->update($profileData);
        } else {
            $profileData['address'] = $user->address;
            $profileData['city'] = $user->city;
            $profileData['state'] = $user->state;
            $profileData['pincode'] = $user->pincode;
            $user->institute()->create($profileData);
        }
    }

    /**
     * Update student profile
     */
    private function updateStudentProfile($user, $request)
    {
        $profileData = $request->only([
            'date_of_birth', 'gender', 'current_class', 'school_name', 'board',
            'area', 'preferred_learning_mode', 'budget_min', 'budget_max', 'parent_phone'
        ]);

        // Add location data
        $profileData['city'] = $user->city;
        $profileData['state'] = $user->state;
        $profileData['pincode'] = $user->pincode;
        $profileData['address'] = $user->address;

        // Handle subjects interested
        if ($request->has('subjects_interested')) {
            $profileData['subjects_interested'] = $request->subjects_interested;
        }

        if ($user->studentProfile) {
            $user->studentProfile->update($profileData);
        } else {
            $user->studentProfile()->create($profileData);
        }
    }

    /**
     * Get notifications for user
     */
    private function getNotifications($user)
    {
        // TODO: Implement proper notifications system
        return collect([
            [
                'id' => 1,
                'title' => 'Welcome to Education Platform',
                'message' => 'Complete your profile to get better recommendations',
                'type' => 'info',
                'read' => false,
                'created_at' => now()->subMinutes(30),
            ]
        ]);
    }

    /**
     * Get recent activities for user
     */
    private function getRecentActivities($user)
    {
        // TODO: Implement proper activity tracking
        return collect([
            [
                'type' => 'profile_update',
                'description' => 'Profile information updated',
                'timestamp' => now()->subHours(2),
            ]
        ]);
    }

    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion($profile)
    {
        if (!$profile) return 0;

        $fields = [
            'date_of_birth', 'current_class', 'school_name', 'city', 'state',
            'preferred_learning_mode', 'subjects_interested'
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($profile->$field)) {
                $completed++;
            }
        }

        return round(($completed / count($fields)) * 100);
    }

    /**
     * Get recommended teachers for student
     */
    private function getRecommendedTeachers($studentProfile)
    {
        if (!$studentProfile || !$studentProfile->subjects_interested) {
            return collect();
        }

        return TeacherProfile::where('verified', true)
            ->whereHas('subjects', function($query) use ($studentProfile) {
                $query->whereIn('subjects.id', $studentProfile->subjects_interested);
            })
            ->when($studentProfile->city, function($query) use ($studentProfile) {
                $query->whereHas('user', function($userQuery) use ($studentProfile) {
                    $userQuery->where('city', $studentProfile->city);
                });
            })
            ->when($studentProfile->budget_max, function($query) use ($studentProfile) {
                $query->where('hourly_rate', '<=', $studentProfile->budget_max);
            })
            ->with(['user', 'subjects'])
            ->orderBy('rating', 'desc')
            ->limit(5)
            ->get();
    }
}
