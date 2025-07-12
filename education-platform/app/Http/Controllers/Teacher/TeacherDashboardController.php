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
use App\Models\Review; // Added missing import
use App\Models\ExamPackage;

class TeacherDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher');
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
        $examPackages = ExamPackage::where('status', 'active')->get();
        $teacherExamPackageIds = $teacherProfile->examPackages()->pluck('exam_packages.id')->toArray();

        return view('teacher.profile', compact('teacherProfile', 'subjects', 'institutes', 'teacherSubjects', 'examPackages', 'teacherExamPackageIds'));
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
            'exam_packages' => 'nullable|array',
            'exam_packages.*' => 'exists:exam_packages,id',
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

        // Sync exam packages with duration and price
        if ($request->has('exam_packages')) {
            $examPackageData = [];
            $durations = $request->input('exam_package_durations', []);
            $prices = $request->input('exam_package_prices', []);
            foreach ($request->exam_packages as $index => $packageId) {
                $examPackageData[$packageId] = [
                    'duration' => $durations[$index] ?? null,
                    'price' => $prices[$index] ?? null,
                ];
            }
            $teacherProfile->examPackages()->sync($examPackageData);
        } else {
            $teacherProfile->examPackages()->detach();
        }

        return redirect()->route('teacher.profile.index')->with('success', 'Profile updated successfully!');
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

    /**
     * Show earnings and reports page
     */
    public function earnings()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile.index')->with('error', 'Please complete your profile first.');
        }

        // Mock earnings data (replace with actual data when payment system is implemented)
        $earnings = [
            'this_month' => 0,
            'last_month' => 0,
            'total' => 0,
            'monthly_trend' => collect(range(1, 12))->map(function($month) {
                return [
                    'month' => date('M', mktime(0, 0, 0, $month, 1)),
                    'amount' => rand(5000, 25000)
                ];
            }),
            'recent_transactions' => collect()
        ];

        return view('teacher.earnings', compact('teacherProfile', 'earnings'));
    }

    /**
     * Download earnings report
     */
    public function downloadEarningsReport(Request $request)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        // Mock report generation (replace with actual implementation)
        $filename = 'earnings_report_' . $user->name . '_' . date('Y-m-d') . '.pdf';
        
        return response()->download(storage_path('app/temp/' . $filename), $filename)
            ->deleteFileAfterSend();
    }

    /**
     * Show public profile
     */
    public function publicProfile($slug = null)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile.index')->with('error', 'Please complete your profile first.');
        }

        // If slug is provided, show that teacher's profile, otherwise show current user's
        if ($slug && $slug != $teacherProfile->slug) {
            $teacherProfile = TeacherProfile::where('slug', $slug)->firstOrFail();
        }

        $reviews = $teacherProfile->reviews()->with('user')->latest()->take(5)->get();
        $subjects = $teacherProfile->subjects;

        return view('teacher.public-profile', compact('teacherProfile', 'reviews', 'subjects'));
    }

    /**
     * Show reviews page
     */
    public function reviews()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile.index')->with('error', 'Please complete your profile first.');
        }

        $reviews = $teacherProfile->reviews()->with('user')->latest()->paginate(10);

        return view('teacher.reviews', compact('teacherProfile', 'reviews'));
    }

    /**
     * Reply to a review
     */
    public function replyToReview(Request $request, $reviewId)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        $review = Review::findOrFail($reviewId);
        
        // Ensure the review belongs to this teacher
        if ($review->reviewable_id != $teacherProfile->id || $review->reviewable_type != TeacherProfile::class) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'reply' => 'required|string|max:500'
        ]);

        $review->update(['teacher_reply' => $validated['reply']]);

        return redirect()->back()->with('success', 'Reply added successfully!');
    }

    /**
     * Show institute management page
     */
    public function instituteManagement()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile.index')->with('error', 'Please complete your profile first.');
        }

        $institutes = Institute::where('verified', true)->get();
        $currentInstitute = $teacherProfile->institute;

        return view('teacher.institute-management', compact('teacherProfile', 'institutes', 'currentInstitute'));
    }

    /**
     * Show settings page
     */
    public function settings()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        return view('teacher.settings', compact('teacherProfile'));
    }

    /**
     * Update settings
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        $validated = $request->validate([
            'notification_email' => 'boolean',
            'notification_sms' => 'boolean',
            'profile_visibility' => 'in:public,private',
            'availability_status' => 'in:available,busy,unavailable',
            'auto_accept_bookings' => 'boolean',
        ]);

        $teacherProfile->update($validated);

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    /**
     * Show preferences page
     */
    public function preferences()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        return view('teacher.preferences', compact('teacherProfile'));
    }

    /**
     * Update preferences
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        $validated = $request->validate([
            'language' => 'in:en,hi',
            'timezone' => 'string',
            'date_format' => 'in:Y-m-d,d/m/Y,m/d/Y',
            'time_format' => 'in:12,24',
            'teaching_preferences' => 'nullable|array',
        ]);

        $teacherProfile->update($validated);

        return redirect()->back()->with('success', 'Preferences updated successfully!');
    }

    /**
     * Show notification settings page
     */
    public function notificationSettings()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        return view('teacher.notification-settings', compact('teacherProfile'));
    }

    /**
     * Update notification settings
     */
    public function updateNotificationSettings(Request $request)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'booking_notifications' => 'boolean',
            'payment_notifications' => 'boolean',
            'marketing_emails' => 'boolean',
        ]);

        $teacherProfile->update($validated);

        return redirect()->back()->with('success', 'Notification settings updated successfully!');
    }

    /**
     * Show privacy settings page
     */
    public function privacySettings()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        return view('teacher.privacy-settings', compact('teacherProfile'));
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacySettings(Request $request)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        $validated = $request->validate([
            'profile_visibility' => 'in:public,private',
            'show_contact_info' => 'boolean',
            'show_earnings' => 'boolean',
            'show_schedule' => 'boolean',
            'allow_messages' => 'boolean',
        ]);

        $teacherProfile->update($validated);

        return redirect()->back()->with('success', 'Privacy settings updated successfully!');
    }

    /**
     * Get recent activity for API
     */
    public function getRecentActivity()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        // Mock recent activity data
        $activities = [
            [
                'type' => 'session_completed',
                'title' => 'Session completed with Student A',
                'description' => 'Mathematics session completed successfully',
                'time' => now()->subHours(2)->diffForHumans(),
                'icon' => 'bi-check-circle'
            ],
            [
                'type' => 'new_booking',
                'title' => 'New booking received',
                'description' => 'Physics session scheduled for tomorrow',
                'time' => now()->subHours(4)->diffForHumans(),
                'icon' => 'bi-calendar-plus'
            ],
            [
                'type' => 'payment_received',
                'title' => 'Payment received',
                'description' => '₹500 received for completed session',
                'time' => now()->subHours(6)->diffForHumans(),
                'icon' => 'bi-credit-card'
            ]
        ];

        return response()->json($activities);
    }

    /**
     * Update avatar
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('avatar')) {
            $imagePath = $request->file('avatar')->store('avatars', 'public');
            $user->update(['profile_image' => $imagePath]);
        }

        return redirect()->back()->with('success', 'Avatar updated successfully!');
    }

    /**
     * Remove avatar
     */
    public function removeAvatar()
    {
        $user = Auth::user();
        
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
            $user->update(['profile_image' => null]);
        }

        return redirect()->back()->with('success', 'Avatar removed successfully!');
    }

    /**
     * Upload documents
     */
    public function uploadDocuments(Request $request)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        $request->validate([
            'documents.*' => 'required|file|mimes:pdf,doc,docx|max:5120'
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('teacher-documents', 'public');
                // Store document info in database if needed
            }
        }

        return redirect()->back()->with('success', 'Documents uploaded successfully!');
    }

    /**
     * Delete document
     */
    public function deleteDocument($documentId)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        // Delete document logic here
        return redirect()->back()->with('success', 'Document deleted successfully!');
    }

    /**
     * Show institutes page
     */
    public function institutes()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        $institutes = Institute::where('verified', true)->paginate(10);
        $currentInstitute = $teacherProfile->institute;

        return view('teacher.institutes', compact('institutes', 'currentInstitute'));
    }

    /**
     * Search institutes
     */
    public function searchInstitutes(Request $request)
    {
        $query = $request->get('q');
        $institutes = Institute::where('verified', true)
            ->where('institute_name', 'like', "%{$query}%")
            ->orWhere('location', 'like', "%{$query}%")
            ->paginate(10);

        return response()->json($institutes);
    }

    /**
     * View applications
     */
    public function viewApplications()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock applications data
        $applications = collect();

        return view('teacher.applications', compact('applications'));
    }

    /**
     * Cancel application
     */
    public function cancelApplication($applicationId)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        // Cancel application logic here
        return redirect()->back()->with('success', 'Application cancelled successfully!');
    }

    /**
     * Show branches page
     */
    public function branches()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock branches data
        $branches = collect();

        return view('teacher.branches', compact('branches'));
    }

    /**
     * Show current branch
     */
    public function currentBranch()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        $currentBranch = null; // Get current branch logic

        return view('teacher.current-branch', compact('currentBranch'));
    }

    /**
     * Show branch colleagues
     */
    public function branchColleagues()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock colleagues data
        $colleagues = collect();

        return view('teacher.colleagues', compact('colleagues'));
    }

    /**
     * Show branch schedule
     */
    public function branchSchedule()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock schedule data
        $schedule = collect();

        return view('teacher.branch-schedule', compact('schedule'));
    }

    /**
     * Request transfer
     */
    public function requestTransfer(Request $request)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        $validated = $request->validate([
            'branch_id' => 'required|exists:school_branches,id',
            'reason' => 'required|string|max:500'
        ]);

        // Transfer request logic here

        return redirect()->back()->with('success', 'Transfer request submitted successfully!');
    }

    /**
     * Show sessions page
     */
    public function sessions()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock sessions data
        $sessions = collect();

        return view('teacher.sessions', compact('sessions'));
    }

    /**
     * Show upcoming sessions
     */
    public function upcomingSessions()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock upcoming sessions data
        $sessions = collect();

        return view('teacher.upcoming-sessions', compact('sessions'));
    }

    /**
     * Show completed sessions
     */
    public function completedSessions()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock completed sessions data
        $sessions = collect();

        return view('teacher.completed-sessions', compact('sessions'));
    }

    /**
     * Show cancelled sessions
     */
    public function cancelledSessions()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock cancelled sessions data
        $sessions = collect();

        return view('teacher.cancelled-sessions', compact('sessions'));
    }

    /**
     * Show create session form
     */
    public function createSession()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        return view('teacher.create-session');
    }

    /**
     * Store session
     */
    public function storeSession(Request $request)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date|after:today',
            'time' => 'required',
            'duration' => 'required|integer|min:30|max:480',
            'subject_id' => 'required|exists:subjects,id',
            'max_students' => 'required|integer|min:1|max:50',
            'price' => 'required|numeric|min:0'
        ]);

        // Store session logic here

        return redirect()->route('teacher.sessions')->with('success', 'Session created successfully!');
    }

    /**
     * Show session details
     */
    public function showSession($sessionId)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock session data
        $session = null;

        return view('teacher.show-session', compact('session'));
    }

    /**
     * Show edit session form
     */
    public function editSession($sessionId)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock session data
        $session = null;

        return view('teacher.edit-session', compact('session'));
    }

    /**
     * Update session
     */
    public function updateSession(Request $request, $sessionId)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required',
            'duration' => 'required|integer|min:30|max:480',
            'subject_id' => 'required|exists:subjects,id',
            'max_students' => 'required|integer|min:1|max:50',
            'price' => 'required|numeric|min:0'
        ]);

        // Update session logic here

        return redirect()->route('teacher.sessions')->with('success', 'Session updated successfully!');
    }

    /**
     * Cancel session
     */
    public function cancelSession($sessionId)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        // Cancel session logic here

        return redirect()->back()->with('success', 'Session cancelled successfully!');
    }

    /**
     * Complete session
     */
    public function completeSession($sessionId)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        // Complete session logic here

        return redirect()->back()->with('success', 'Session completed successfully!');
    }

    /**
     * Show students page
     */
    public function students()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock students data
        $students = collect();

        return view('teacher.students', compact('students'));
    }

    /**
     * Show student details
     */
    public function showStudent($studentId)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock student data
        $student = null;

        return view('teacher.show-student', compact('student'));
    }

    /**
     * Show student progress
     */
    public function studentProgress($studentId)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock progress data
        $progress = collect();

        return view('teacher.student-progress', compact('progress'));
    }

    /**
     * Add student note
     */
    public function addStudentNote(Request $request, $studentId)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        $validated = $request->validate([
            'note' => 'required|string|max:1000'
        ]);

        // Add note logic here

        return redirect()->back()->with('success', 'Note added successfully!');
    }

    /**
     * Delete student note
     */
    public function deleteStudentNote($studentId, $noteId)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        // Delete note logic here

        return redirect()->back()->with('success', 'Note deleted successfully!');
    }

    /**
     * Show messages page
     */
    public function messages()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock messages data
        $messages = collect();

        return view('teacher.messages', compact('messages'));
    }

    /**
     * Send message
     */
    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        // Send message logic here

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    /**
     * Show notifications page
     */
    public function notifications()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.profile')->with('error', 'Please complete your profile first.');
        }

        // Mock notifications data
        $notifications = collect();

        return view('teacher.notifications', compact('notifications'));
    }

    /**
     * Mark notifications as read
     */
    public function markNotificationsRead(Request $request)
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        $validated = $request->validate([
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'integer'
        ]);

        // Mark as read logic here

        return redirect()->back()->with('success', 'Notifications marked as read!');
    }
}
