<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\TeacherProfile;
use App\Models\Subject;
use App\Models\Institute;
use App\Models\User;

class TeacherDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:teacher']);
    }

    /**
     * Display the teacher dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        // Create profile if it doesn't exist
        if (!$teacherProfile) {
            $teacherProfile = TeacherProfile::create([
                'user_id' => $user->id,
                'experience_years' => 0,
                'rating' => 0,
                'total_students' => 0,
                'verified' => false,
            ]);
        }

        $stats = [
            'profile_completion' => $this->calculateProfileCompletion($teacherProfile),
            'total_subjects' => $teacherProfile->subjects()->count(),
            'verification_status' => $teacherProfile->verified,
            'institute_verification' => $teacherProfile->is_institute_verified,
            'rating' => $teacherProfile->rating,
            'total_students' => $teacherProfile->total_students,
        ];

        $subjects = Subject::active()->get();
        $institutes = Institute::where('verified', true)->get();

        return view('teacher.dashboard', compact('teacherProfile', 'stats', 'subjects', 'institutes'));
    }

    /**
     * Show the teacher profile form
     */
    public function profile()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile ?? new TeacherProfile(['user_id' => $user->id]);
        $subjects = Subject::active()->get();
        $institutes = Institute::where('verified', true)->get();
        $teacherSubjects = $teacherProfile->subjects()->pluck('subjects.id')->toArray();

        return view('teacher.profile', compact('teacherProfile', 'subjects', 'institutes', 'teacherSubjects'));
    }

    /**
     * Update teacher profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'qualification' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'experience_years' => 'required|integer|min:0|max:50',
            'hourly_rate' => 'nullable|numeric|min:0',
            'specialization' => 'nullable|string|max:255',
            'languages' => 'nullable|array',
            'languages.*' => 'string|max:50',
            'availability' => 'nullable|string|max:255',
            'teaching_mode' => 'required|in:online,offline,both',
            'institute_id' => 'nullable|exists:institutes,id',
            'employment_type' => 'required|in:freelance,institute,both',
            'institute_experience' => 'nullable|string|max:500',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'subject_rates' => 'nullable|array',
            'subject_proficiency' => 'nullable|array',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('teacher-profiles', 'public');
            $user->update(['profile_image' => $imagePath]);
        }

        // Update or create teacher profile
        $teacherProfile = $user->teacherProfile ?? new TeacherProfile(['user_id' => $user->id]);
        
        $teacherProfile->fill($validated);
        $teacherProfile->save();

        // Sync subjects with rates and proficiency
        if ($request->has('subjects')) {
            $subjectData = [];
            foreach ($request->subjects as $index => $subjectId) {
                $subjectData[$subjectId] = [
                    'subject_rate' => $request->subject_rates[$index] ?? null,
                    'proficiency_level' => $request->subject_proficiency[$index] ?? 'intermediate',
                ];
            }
            $teacherProfile->subjects()->sync($subjectData);
        }

        return redirect()->route('teacher.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Apply to join an institute
     */
    public function applyToInstitute(Request $request)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Please complete your profile first.');
        }

        $validated = $request->validate([
            'institute_id' => 'required|exists:institutes,id',
            'institute_experience' => 'nullable|string|max:500',
            'institute_subjects' => 'nullable|array',
        ]);

        $teacherProfile->update([
            'institute_id' => $validated['institute_id'],
            'institute_experience' => $validated['institute_experience'] ?? null,
            'institute_subjects' => $validated['institute_subjects'] ?? null,
            'employment_type' => 'institute',
            'is_institute_verified' => false, // Pending verification
        ]);

        return redirect()->back()->with('success', 'Application sent to institute successfully! Awaiting verification.');
    }

    /**
     * Leave institute
     */
    public function leaveInstitute()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if ($teacherProfile && $teacherProfile->institute_id) {
            $teacherProfile->update([
                'institute_id' => null,
                'employment_type' => 'freelance',
                'institute_subjects' => null,
                'institute_experience' => null,
                'is_institute_verified' => false,
            ]);

            return redirect()->back()->with('success', 'You have left the institute successfully.');
        }

        return redirect()->back()->with('error', 'You are not associated with any institute.');
    }

    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion(TeacherProfile $profile): int
    {
        $fields = [
            'qualification' => $profile->qualification,
            'bio' => $profile->bio,
            'experience_years' => $profile->experience_years > 0,
            'specialization' => $profile->specialization,
            'languages' => !empty($profile->languages),
            'teaching_mode' => $profile->teaching_mode,
            'subjects' => $profile->subjects()->count() > 0,
            'profile_image' => $profile->user->profile_image,
        ];

        $completed = count(array_filter($fields));
        $total = count($fields);

        return round(($completed / $total) * 100);
    }

    /**
     * Get teacher statistics for dashboard
     */
    public function getStats()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        $stats = [
            'profile_completion' => $this->calculateProfileCompletion($teacherProfile),
            'total_subjects' => $teacherProfile->subjects()->count(),
            'verification_status' => $teacherProfile->verified,
            'institute_verification' => $teacherProfile->is_institute_verified,
            'rating' => $teacherProfile->rating,
            'total_students' => $teacherProfile->total_students,
            'employment_status' => $teacherProfile->employment_status,
            'institute_name' => $teacherProfile->institute?->institute_name,
        ];

        return response()->json($stats);
    }
}
