<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Institute;
use App\Models\TeacherProfile;
use App\Models\Subject;
use App\Models\User;

class InstituteDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:institute']);
    }

    /**
     * Display the institute dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $institute = $user->institute;

        // Create institute profile if it doesn't exist
        if (!$institute) {
            return redirect()->route('institute.profile')->with('info', 'Please complete your institute profile first.');
        }

        $stats = [
            'profile_completion' => $this->calculateProfileCompletion($institute),
            'total_teachers' => $institute->total_teachers,
            'verified_teachers' => $institute->verified_teachers_count,
            'pending_teachers' => $institute->teachers()->where('is_institute_verified', false)->count(),
            'total_subjects' => $institute->teacherSubjects()->count(),
            'verification_status' => $institute->verified,
            'rating' => $institute->rating,
            'total_students' => $institute->total_students,
        ];

        $recentTeachers = $institute->teachers()->with('user')->latest()->take(5)->get();
        $pendingApplications = $institute->pendingTeachers()->with('user')->get();

        return view('institute.dashboard', compact('institute', 'stats', 'recentTeachers', 'pendingApplications'));
    }

    /**
     * Show the institute profile form
     */
    public function profile()
    {
        $user = Auth::user();
        $institute = $user->institute ?? new Institute(['user_id' => $user->id]);
        $subjects = Subject::active()->get();

        return view('institute.profile', compact('institute', 'subjects'));
    }

    /**
     * Update institute profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'institute_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'registration_number' => 'nullable|string|max:255|unique:institutes,registration_number,' . ($user->institute?->id ?? 'NULL'),
            'website' => 'nullable|url|max:255',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'facilities' => 'nullable|array',
            'established_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('institute-profiles', 'public');
            $user->update(['profile_image' => $imagePath]);
        }

        // Handle gallery images upload
        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $imagePath = $image->store('institute-gallery', 'public');
                $galleryImages[] = $imagePath;
            }
            $validated['gallery_images'] = $galleryImages;
        }

        // Update or create institute profile
        $institute = $user->institute ?? new Institute(['user_id' => $user->id]);
        $institute->fill($validated);
        $institute->save();

        return redirect()->route('institute.profile')->with('success', 'Institute profile updated successfully!');
    }

    /**
     * Show teachers management page
     */
    public function teachers()
    {
        $user = Auth::user();
        $institute = $user->institute;

        if (!$institute) {
            return redirect()->route('institute.profile')->with('error', 'Please complete your institute profile first.');
        }

        $teachers = $institute->teachers()->with(['user', 'subjects'])->paginate(10);
        $pendingTeachers = $institute->pendingTeachers()->with('user')->get();
        $subjects = Subject::active()->get();

        return view('institute.teachers', compact('institute', 'teachers', 'pendingTeachers', 'subjects'));
    }

    /**
     * Verify a teacher
     */
    public function verifyTeacher(TeacherProfile $teacher)
    {
        $user = Auth::user();
        $institute = $user->institute;

        if (!$institute || !$institute->canBeManaged($user)) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($institute->verifyTeacher($teacher)) {
            return redirect()->back()->with('success', 'Teacher verified successfully!');
        }

        return redirect()->back()->with('error', 'Unable to verify teacher.');
    }

    /**
     * Remove teacher verification
     */
    public function unverifyTeacher(TeacherProfile $teacher)
    {
        $user = Auth::user();
        $institute = $user->institute;

        if (!$institute || !$institute->canBeManaged($user)) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($institute->unverifyTeacher($teacher)) {
            return redirect()->back()->with('success', 'Teacher verification removed successfully!');
        }

        return redirect()->back()->with('error', 'Unable to remove teacher verification.');
    }

    /**
     * Add a new teacher to the institute
     */
    public function addTeacher(Request $request)
    {
        $user = Auth::user();
        $institute = $user->institute;

        if (!$institute) {
            return redirect()->back()->with('error', 'Institute profile not found.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'qualification' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0|max:50',
            'specialization' => 'nullable|string|max:255',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        // Create user account for teacher
        $teacherUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'role' => 'teacher',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create teacher profile
        $teacherProfile = TeacherProfile::create([
            'user_id' => $teacherUser->id,
            'institute_id' => $institute->id,
            'qualification' => $validated['qualification'],
            'experience_years' => $validated['experience_years'],
            'specialization' => $validated['specialization'],
            'employment_type' => 'institute',
            'is_institute_verified' => true, // Auto-verified since added by institute
            'verified' => false, // Admin verification still pending
        ]);

        // Attach subjects if provided
        if (!empty($validated['subjects'])) {
            $teacherProfile->subjects()->attach($validated['subjects']);
        }

        return redirect()->back()->with('success', 'Teacher added successfully! Login credentials have been created.');
    }

    /**
     * Remove teacher from institute
     */
    public function removeTeacher(TeacherProfile $teacher)
    {
        $user = Auth::user();
        $institute = $user->institute;

        if (!$institute || !$institute->canBeManaged($user)) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($teacher->institute_id === $institute->id) {
            $teacher->update([
                'institute_id' => null,
                'employment_type' => 'freelance',
                'institute_subjects' => null,
                'institute_experience' => null,
                'is_institute_verified' => false,
            ]);

            return redirect()->back()->with('success', 'Teacher removed from institute successfully.');
        }

        return redirect()->back()->with('error', 'Teacher not found in your institute.');
    }

    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion(Institute $institute): int
    {
        $fields = [
            'institute_name' => $institute->institute_name,
            'description' => $institute->description,
            'contact_person' => $institute->contact_person,
            'contact_phone' => $institute->contact_phone,
            'address' => $institute->address,
            'city' => $institute->city,
            'state' => $institute->state,
            'pincode' => $institute->pincode,
            'facilities' => !empty($institute->facilities),
            'profile_image' => $institute->user->profile_image,
            'established_year' => $institute->established_year,
            'website' => $institute->website,
        ];

        $completed = count(array_filter($fields));
        $total = count($fields);

        return round(($completed / $total) * 100);
    }

    /**
     * Get institute statistics for dashboard
     */
    public function getStats()
    {
        $user = Auth::user();
        $institute = $user->institute;

        if (!$institute) {
            return response()->json(['error' => 'Institute profile not found'], 404);
        }

        $stats = [
            'profile_completion' => $this->calculateProfileCompletion($institute),
            'total_teachers' => $institute->total_teachers,
            'verified_teachers' => $institute->verified_teachers_count,
            'pending_teachers' => $institute->pendingTeachers()->count(),
            'total_subjects' => $institute->teacherSubjects()->count(),
            'verification_status' => $institute->verified,
            'rating' => $institute->rating,
            'total_students' => $institute->total_students,
        ];

        return response()->json($stats);
    }
}
