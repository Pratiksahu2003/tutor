<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Subject;
use App\Models\Session;
use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\StudentProfile;
use Carbon\Carbon;

class TeacherController extends Controller
{
    /**
     * Store a new subject for the teacher
     */
    public function storeSubject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:academic,competitive,language,technical,arts,sports',
            'level' => 'required|string|in:beginner,intermediate,advanced,expert',
            'hourly_rate' => 'required|numeric|min:100',
            'description' => 'nullable|string|max:1000',
            'specializations' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $teacherProfile = TeacherProfile::where('user_id', Auth::id())->first();
            
            if (!$teacherProfile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher profile not found'
                ], 404);
            }

            $subject = Subject::create([
                'name' => $request->name,
                'category' => $request->category,
                'level' => $request->level,
                'description' => $request->description,
                'specializations' => $request->specializations,
                'teacher_id' => $teacherProfile->id,
            ]);

            // Attach subject to teacher
            $teacherProfile->subjects()->attach($subject->id);

            return response()->json([
                'success' => true,
                'message' => 'Subject added successfully',
                'subject' => $subject
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add subject: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new session
     */
    public function storeSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'student_id' => 'nullable|exists:users,id',
            'date' => 'required|date|after:today',
            'time' => 'required|date_format:H:i',
            'duration' => 'required|integer|in:30,60,90,120',
            'type' => 'required|string|in:online,offline,hybrid',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $teacherProfile = TeacherProfile::where('user_id', Auth::id())->first();
            
            if (!$teacherProfile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher profile not found'
                ], 404);
            }

            $scheduledAt = Carbon::parse($request->date . ' ' . $request->time);

            $session = Session::create([
                'teacher_id' => $teacherProfile->id,
                'student_id' => $request->student_id,
                'subject_id' => $request->subject_id,
                'scheduled_at' => $scheduledAt,
                'duration' => $request->duration,
                'type' => $request->type,
                'notes' => $request->notes,
                'status' => 'scheduled',
                'amount' => $this->calculateSessionAmount($request->subject_id, $request->duration),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Session scheduled successfully',
                'session' => $session
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule session: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new student
     */
    public function storeStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'age' => 'nullable|integer|min:5|max:100',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'goals' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create user account for student
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => 'student',
                'password' => bcrypt('password123'), // Temporary password
            ]);

            // Create student profile
            $studentProfile = StudentProfile::create([
                'user_id' => $user->id,
                'age' => $request->age,
                'goals' => $request->goals,
            ]);

            // Attach subjects if provided
            if ($request->subjects) {
                $studentProfile->subjects()->attach($request->subjects);
            }

            // Attach student to teacher
            $teacherProfile = TeacherProfile::where('user_id', Auth::id())->first();
            if ($teacherProfile) {
                // $teacherProfile->students()->attach($user->id);
            }

            return response()->json([
                'success' => true,
                'message' => 'Student added successfully',
                'student' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add student: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get teacher statistics
     */
    public function getStats(Request $request)
    {
        $teacherProfile = TeacherProfile::where('user_id', Auth::id())->first();
        
        if (!$teacherProfile) {
            return response()->json(['error' => 'Teacher profile not found'], 404);
        }

        $period = $request->get('period', 'month');
        $thisMonth = Carbon::now()->startOfMonth();

        $stats = [
            'total_students' => 0, // $teacherProfile->students()->count(),
            'new_students_month' => 0, // $teacherProfile->students()->wherePivot('created_at', '>=', $thisMonth)->count(),
            // 'total_sessions' => $teacherProfile->sessions()->count(),
            // 'sessions_this_month' => $teacherProfile->sessions()->whereMonth('scheduled_at', $thisMonth->month)->count(),
            // 'earnings_this_month' => $teacherProfile->payments()->whereMonth('created_at', $thisMonth->month)->sum('amount'),
            // 'total_earnings' => $teacherProfile->payments()->sum('amount'),
            'average_rating' => $teacherProfile->reviews()->avg('rating') ?? 0,
            // 'completed_sessions' => $teacherProfile->sessions()->where('status', 'completed')->count(),
            // 'upcoming_sessions' => $teacherProfile->sessions()->where('status', 'scheduled')->where('scheduled_at', '>=', now())->count(),
            'subjects_taught' => $teacherProfile->subjects()->count(),
            'institutes_associated' => $teacherProfile->institutes()->count(),
        ];

        return response()->json(['stats' => $stats]);
    }

    /**
     * Get real-time updates
     */
    public function getUpdates()
    {
        $teacherProfile = TeacherProfile::where('user_id', Auth::id())->first();
        
        if (!$teacherProfile) {
            return response()->json(['error' => 'Teacher profile not found'], 404);
        }

        $updates = [
            'type' => 'stats_update',
            'stats' => $this->getStats(request())->getData()->stats,
            'notifications' => $this->getRecentNotifications($teacherProfile),
            'activities' => $this->getRecentActivities($teacherProfile),
        ];

        return response()->json($updates);
    }

    /**
     * Calculate session amount based on subject and duration
     */
    private function calculateSessionAmount($subjectId, $duration)
    {
        $subject = Subject::find($subjectId);
        $hourlyRate = $subject->hourly_rate ?? 500; // Default rate
        return ($hourlyRate * $duration) / 60;
    }

    /**
     * Get recent notifications
     */
    private function getRecentNotifications($teacherProfile)
    {
        // Implementation for recent notifications
        return [];
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities($teacherProfile)
    {
        // Implementation for recent activities
        return [];
    }
} 