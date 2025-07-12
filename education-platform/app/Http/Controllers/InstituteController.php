<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Branch;
use App\Models\Subject;
use App\Models\ExamType;
use App\Models\User;
use App\Models\Institute;
use App\Models\TeacherProfile;
use Carbon\Carbon;

class InstituteController extends Controller
{
    /**
     * Store a new branch
     */
    public function storeBranch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'manager' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $institute = Institute::where('user_id', Auth::id())->first();
            
            if (!$institute) {
                return response()->json([
                    'success' => false,
                    'message' => 'Institute profile not found'
                ], 404);
            }

            $branch = Branch::create([
                'institute_id' => $institute->id,
                'name' => $request->name,
                'code' => $request->code,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'phone' => $request->phone,
                'email' => $request->email,
                'manager' => $request->manager,
                'capacity' => $request->capacity,
                'status' => 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Branch added successfully',
                'branch' => $branch
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add branch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new teacher
     */
    public function storeTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'qualification' => 'nullable|string|max:255',
            'hourly_rate' => 'nullable|numeric|min:100',
            'bio' => 'nullable|string|max:1000',
            'branches' => 'nullable|array',
            'branches.*' => 'exists:branches,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create user account for teacher
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => 'teacher',
                'password' => bcrypt('password123'), // Temporary password
            ]);

            // Create teacher profile
            $teacherProfile = TeacherProfile::create([
                'user_id' => $user->id,
                'experience_years' => $request->experience_years,
                'qualification' => $request->qualification,
                'hourly_rate' => $request->hourly_rate,
                'bio' => $request->bio,
                'verified' => true, // Auto-verify for institute-added teachers
            ]);

            // Attach subjects
            $teacherProfile->subjects()->attach($request->subjects);

            // Attach teacher to institute
            $institute = Institute::where('user_id', Auth::id())->first();
            if ($institute) {
                $institute->teachers()->attach($user->id);
            }

            // Attach to branches if specified
            if ($request->branches) {
                $teacherProfile->branches()->attach($request->branches);
            }

            return response()->json([
                'success' => true,
                'message' => 'Teacher added successfully',
                'teacher' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add teacher: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new subject
     */
    public function storeSubject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'category' => 'required|string|in:academic,competitive,language,technical,arts,sports',
            'level' => 'required|string|in:beginner,intermediate,advanced,expert',
            'description' => 'nullable|string|max:1000',
            'duration' => 'nullable|integer|min:1',
            'fee' => 'nullable|numeric|min:0',
            'branches' => 'nullable|array',
            'branches.*' => 'exists:branches,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $institute = Institute::where('user_id', Auth::id())->first();
            
            if (!$institute) {
                return response()->json([
                    'success' => false,
                    'message' => 'Institute profile not found'
                ], 404);
            }

            $subject = Subject::create([
                'name' => $request->name,
                'code' => $request->code,
                'category' => $request->category,
                'level' => $request->level,
                'description' => $request->description,
                'duration' => $request->duration,
                'fee' => $request->fee,
                'institute_id' => $institute->id,
            ]);

            // Attach to branches if specified
            if ($request->branches) {
                $subject->branches()->attach($request->branches);
            }

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
     * Store a new exam type
     */
    public function storeExamType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:exam_types,code',
            'duration' => 'required|integer|min:15',
            'questions_count' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'max_attempts' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:1000',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $institute = Institute::where('user_id', Auth::id())->first();
            
            if (!$institute) {
                return response()->json([
                    'success' => false,
                    'message' => 'Institute profile not found'
                ], 404);
            }

            $examType = ExamType::create([
                'institute_id' => $institute->id,
                'name' => $request->name,
                'code' => $request->code,
                'duration' => $request->duration,
                'questions_count' => $request->questions_count,
                'passing_score' => $request->passing_score,
                'max_attempts' => $request->max_attempts,
                'description' => $request->description,
                'randomize_questions' => $request->has('randomize_questions'),
            ]);

            // Attach subjects if specified
            if ($request->subjects) {
                $examType->subjects()->attach($request->subjects);
            }

            return response()->json([
                'success' => true,
                'message' => 'Exam type added successfully',
                'exam_type' => $examType
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add exam type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get institute statistics
     */
    public function getStats(Request $request)
    {
        $institute = Institute::where('user_id', Auth::id())->first();
        
        if (!$institute) {
            return response()->json(['error' => 'Institute profile not found'], 404);
        }

        $period = $request->get('period', 'month');
        $thisMonth = Carbon::now()->startOfMonth();

        $stats = [
            'total_branches' => $institute->branches()->count(),
            'total_teachers' => $institute->teachers()->count(),
            'total_students' => $institute->students()->count(),
            'new_students_month' => $institute->students()->wherePivot('created_at', '>=', $thisMonth)->count(),
            // 'total_sessions' => $institute->sessions()->count(),
            // 'sessions_this_month' => $institute->sessions()->whereMonth('scheduled_at', $thisMonth->month)->count(),
            'total_revenue' => $institute->payments()->sum('amount'),
            'monthly_revenue' => $institute->payments()->whereMonth('created_at', $thisMonth->month)->sum('amount'),
            'average_rating' => $institute->reviews()->avg('rating') ?? 0,
            'active_courses' => $institute->courses()->where('status', 'active')->count(),
        ];

        return response()->json(['stats' => $stats]);
    }

    /**
     * Get real-time updates
     */
    public function getUpdates()
    {
        $institute = Institute::where('user_id', Auth::id())->first();
        
        if (!$institute) {
            return response()->json(['error' => 'Institute profile not found'], 404);
        }

        $updates = [
            'type' => 'stats_update',
            'stats' => $this->getStats(request())->getData()->stats,
            'notifications' => $this->getRecentNotifications($institute),
            'activities' => $this->getRecentActivities($institute),
        ];

        return response()->json($updates);
    }

    /**
     * Get recent notifications
     */
    private function getRecentNotifications($institute)
    {
        // Implementation for recent notifications
        return [];
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities($institute)
    {
        // Implementation for recent activities
        return [];
    }
} 