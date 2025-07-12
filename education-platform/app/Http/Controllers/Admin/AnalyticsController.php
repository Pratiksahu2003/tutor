<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\Institute;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\ExamCategory;
use App\Models\BlogPost;
use App\Models\Contact;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display the main analytics dashboard
     */
    public function index()
    {
        $data = [
            'overview' => $this->getOverviewStats(),
            'user_growth' => $this->getUserGrowthData(),
            'role_distribution' => $this->getRoleDistribution(),
            'geographic_data' => $this->getGeographicData(),
            'recent_activity' => $this->getRecentActivity(),
            'top_performers' => $this->getTopPerformers(),
            'revenue_metrics' => $this->getRevenueMetrics(),
            'engagement_metrics' => $this->getEngagementMetrics(),
        ];

        return view('admin.analytics.index', compact('data'));
    }

    /**
     * Get overview statistics
     */
    private function getOverviewStats()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $verifiedTeachers = User::where('role', 'teacher')
            ->whereHas('teacherProfile', function($q) {
                $q->where('verified', true);
            })->count();
        $verifiedInstitutes = Institute::where('verified', true)->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalSubjects = Subject::count();
        $totalExams = Exam::count();
        $totalBlogPosts = BlogPost::count();

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'verified_teachers' => $verifiedTeachers,
            'verified_institutes' => $verifiedInstitutes,
            'total_students' => $totalStudents,
            'total_subjects' => $totalSubjects,
            'total_exams' => $totalExams,
            'total_blog_posts' => $totalBlogPosts,
            'user_activity_rate' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0,
            'verification_rate' => $totalUsers > 0 ? round((($verifiedTeachers + $verifiedInstitutes) / $totalUsers) * 100, 2) : 0,
        ];
    }

    /**
     * Get user growth data for charts
     */
    private function getUserGrowthData()
    {
        $months = collect(range(11, 0))->map(function($month) {
            $date = Carbon::now()->subMonths($month);
            return [
                'month' => $date->format('M Y'),
                'total_users' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'teachers' => User::where('role', 'teacher')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'institutes' => User::where('role', 'institute')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'students' => User::where('role', 'student')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        });

        return $months;
    }

    /**
     * Get role distribution data
     */
    private function getRoleDistribution()
    {
        $roles = ['teacher', 'institute', 'student', 'admin'];
        $distribution = [];

        foreach ($roles as $role) {
            $count = User::where('role', $role)->count();
            $activeCount = User::where('role', $role)->where('is_active', true)->count();
            
            $distribution[$role] = [
                'total' => $count,
                'active' => $activeCount,
                'inactive' => $count - $activeCount,
                'percentage' => User::count() > 0 ? round(($count / User::count()) * 100, 2) : 0,
            ];
        }

        return $distribution;
    }

    /**
     * Get geographic data
     */
    private function getGeographicData()
    {
        $cities = User::select('city', DB::raw('count(*) as count'))
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $states = User::select('state', DB::raw('count(*) as count'))
            ->whereNotNull('state')
            ->groupBy('state')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return [
            'cities' => $cities,
            'states' => $states,
        ];
    }

    /**
     * Get recent activity data
     */
    private function getRecentActivity()
    {
        $recentUsers = User::with(['teacherProfile', 'institute'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => ucfirst($user->role),
                    'city' => $user->city,
                    'joined_at' => $user->created_at->format('M d, Y'),
                    'status' => $user->is_active ? 'Active' : 'Inactive',
                ];
            });

        $recentLeads = Lead::latest()->limit(5)->get();
        
        // Try to get recent contacts, but handle if table doesn't exist
        $recentContacts = collect();
        try {
            $recentContacts = Contact::latest()->limit(5)->get();
        } catch (\Exception $e) {
            // Table doesn't exist, use empty collection
        }

        return [
            'recent_users' => $recentUsers,
            'recent_leads' => $recentLeads,
            'recent_contacts' => $recentContacts,
        ];
    }

    /**
     * Get top performers data
     */
    private function getTopPerformers()
    {
        $topTeachers = User::where('role', 'teacher')
            ->whereHas('teacherProfile')
            ->with(['teacherProfile'])
            ->get()
            ->sortByDesc(function($user) {
                return $user->teacherProfile->rating ?? 0;
            })
            ->take(10)
            ->map(function($teacher) {
                return [
                    'name' => $teacher->name,
                    'rating' => $teacher->teacherProfile->rating ?? 0,
                    'students' => $teacher->teacherProfile->total_students ?? 0,
                    'experience' => $teacher->teacherProfile->experience_years ?? 0,
                    'city' => $teacher->city,
                ];
            });

        $topInstitutes = Institute::orderBy('rating', 'desc')
            ->limit(10)
            ->get()
            ->map(function($institute) {
                return [
                    'name' => $institute->name,
                    'rating' => $institute->rating ?? 0,
                    'students' => $institute->total_students ?? 0,
                    'branches' => $institute->branches()->count(),
                    'city' => $institute->city,
                ];
            });

        return [
            'top_teachers' => $topTeachers,
            'top_institutes' => $topInstitutes,
        ];
    }

    /**
     * Get revenue metrics (placeholder for future implementation)
     */
    private function getRevenueMetrics()
    {
        // This would be implemented when payment system is added
        return [
            'total_revenue' => 0,
            'monthly_revenue' => 0,
            'revenue_growth' => 0,
            'top_earning_teachers' => collect(),
            'revenue_by_city' => collect(),
        ];
    }

    /**
     * Get engagement metrics
     */
    private function getEngagementMetrics()
    {
        $totalSearches = 0; // Would be tracked in search logs
        $totalBookings = 0; // Would be tracked in booking system
        $totalReviews = 0; // Would be tracked in review system

        return [
            'total_searches' => $totalSearches,
            'total_bookings' => $totalBookings,
            'total_reviews' => $totalReviews,
            'avg_session_duration' => 0,
            'bounce_rate' => 0,
        ];
    }

    /**
     * Get detailed teacher analytics
     */
    public function teacherAnalytics()
    {
        $teachers = User::where('role', 'teacher')
            ->with(['teacherProfile'])
            ->get();

        $analytics = [
            'total_teachers' => $teachers->count(),
            'verified_teachers' => $teachers->where('teacherProfile.verified', true)->count(),
            'active_teachers' => $teachers->where('is_active', true)->count(),
            'avg_rating' => $teachers->avg('teacherProfile.rating'),
            'avg_experience' => $teachers->avg('teacherProfile.experience_years'),
            'avg_hourly_rate' => $teachers->avg('teacherProfile.hourly_rate'),
            'teaching_modes' => [
                'online' => $teachers->where('teacherProfile.teaching_mode', 'online')->count(),
                'offline' => $teachers->where('teacherProfile.teaching_mode', 'offline')->count(),
                'both' => $teachers->where('teacherProfile.teaching_mode', 'both')->count(),
            ],
            'experience_distribution' => [
                '0-2 years' => $teachers->where('teacherProfile.experience_years', '<=', 2)->count(),
                '3-5 years' => $teachers->whereBetween('teacherProfile.experience_years', [3, 5])->count(),
                '6-10 years' => $teachers->whereBetween('teacherProfile.experience_years', [6, 10])->count(),
                '11-15 years' => $teachers->whereBetween('teacherProfile.experience_years', [11, 15])->count(),
                '15+ years' => $teachers->where('teacherProfile.experience_years', '>', 15)->count(),
            ],
            'rate_distribution' => [
                '₹0-500' => $teachers->where('teacherProfile.hourly_rate', '<=', 500)->count(),
                '₹501-1000' => $teachers->whereBetween('teacherProfile.hourly_rate', [501, 1000])->count(),
                '₹1001-2000' => $teachers->whereBetween('teacherProfile.hourly_rate', [1001, 2000])->count(),
                '₹2001-5000' => $teachers->whereBetween('teacherProfile.hourly_rate', [2001, 5000])->count(),
                '₹5000+' => $teachers->where('teacherProfile.hourly_rate', '>', 5000)->count(),
            ],
        ];

        return view('admin.analytics.teachers', compact('analytics'));
    }

    /**
     * Get detailed institute analytics
     */
    public function instituteAnalytics()
    {
        $institutes = Institute::with(['branches', 'teachers'])->get();

        $analytics = [
            'total_institutes' => $institutes->count(),
            'verified_institutes' => $institutes->where('verified', true)->count(),
            'active_institutes' => $institutes->where('is_active', true)->count(),
            'avg_rating' => $institutes->avg('rating'),
            'avg_students' => $institutes->avg('total_students'),
            'avg_branches' => $institutes->avg(function($institute) {
                return $institute->branches->count();
            }),
            'type_distribution' => [
                'school' => $institutes->where('type', 'school')->count(),
                'college' => $institutes->where('type', 'college')->count(),
                'university' => $institutes->where('type', 'university')->count(),
                'coaching' => $institutes->where('type', 'coaching')->count(),
                'other' => $institutes->whereNotIn('type', ['school', 'college', 'university', 'coaching'])->count(),
            ],
            'rating_distribution' => [
                '5.0' => $institutes->where('rating', 5.0)->count(),
                '4.5-4.9' => $institutes->whereBetween('rating', [4.5, 4.9])->count(),
                '4.0-4.4' => $institutes->whereBetween('rating', [4.0, 4.4])->count(),
                '3.5-3.9' => $institutes->whereBetween('rating', [3.5, 3.9])->count(),
                'Below 3.5' => $institutes->where('rating', '<', 3.5)->count(),
            ],
        ];

        return view('admin.analytics.institutes', compact('analytics'));
    }

    /**
     * Get detailed student analytics
     */
    public function studentAnalytics()
    {
        $students = User::where('role', 'student')->with(['studentProfile'])->get();

        $analytics = [
            'total_students' => $students->count(),
            'active_students' => $students->where('is_active', true)->count(),
            'avg_age' => $students->avg('studentProfile.age'),
            'gender_distribution' => [
                'male' => $students->where('studentProfile.gender', 'male')->count(),
                'female' => $students->where('studentProfile.gender', 'female')->count(),
                'other' => $students->where('studentProfile.gender', 'other')->count(),
            ],
            'education_level' => [
                'primary' => $students->where('studentProfile.education_level', 'primary')->count(),
                'secondary' => $students->where('studentProfile.education_level', 'secondary')->count(),
                'higher_secondary' => $students->where('studentProfile.education_level', 'higher_secondary')->count(),
                'undergraduate' => $students->where('studentProfile.education_level', 'undergraduate')->count(),
                'postgraduate' => $students->where('studentProfile.education_level', 'postgraduate')->count(),
            ],
        ];

        return view('admin.analytics.students', compact('analytics'));
    }

    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'overview');
        
        switch ($type) {
            case 'teachers':
                return $this->exportTeacherData();
            case 'institutes':
                return $this->exportInstituteData();
            case 'students':
                return $this->exportStudentData();
            default:
                return $this->exportOverviewData();
        }
    }

    private function exportOverviewData()
    {
        $data = $this->getOverviewStats();
        
        $filename = 'analytics_overview_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Metric', 'Value']);
            
            foreach ($data as $key => $value) {
                fputcsv($file, [ucwords(str_replace('_', ' ', $key)), $value]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportTeacherData()
    {
        $teachers = User::where('role', 'teacher')
            ->with(['teacherProfile'])
            ->get();

        $filename = 'teacher_analytics_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($teachers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'City', 'Rating', 'Experience', 'Hourly Rate', 'Verified', 'Active']);
            
            foreach ($teachers as $teacher) {
                fputcsv($file, [
                    $teacher->name,
                    $teacher->email,
                    $teacher->city,
                    $teacher->teacherProfile->rating ?? 0,
                    $teacher->teacherProfile->experience_years ?? 0,
                    $teacher->teacherProfile->hourly_rate ?? 0,
                    $teacher->teacherProfile->verified ? 'Yes' : 'No',
                    $teacher->is_active ? 'Yes' : 'No',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportInstituteData()
    {
        $institutes = Institute::with(['branches'])->get();

        $filename = 'institute_analytics_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($institutes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Type', 'City', 'Rating', 'Students', 'Branches', 'Verified', 'Active']);
            
            foreach ($institutes as $institute) {
                fputcsv($file, [
                    $institute->name,
                    $institute->type,
                    $institute->city,
                    $institute->rating ?? 0,
                    $institute->total_students ?? 0,
                    $institute->branches->count(),
                    $institute->verified ? 'Yes' : 'No',
                    $institute->is_active ? 'Yes' : 'No',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportStudentData()
    {
        $students = User::where('role', 'student')
            ->with(['studentProfile'])
            ->get();

        $filename = 'student_analytics_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'City', 'Age', 'Gender', 'Education Level', 'Active']);
            
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->name,
                    $student->email,
                    $student->city,
                    $student->studentProfile->age ?? 0,
                    $student->studentProfile->gender ?? 'N/A',
                    $student->studentProfile->education_level ?? 'N/A',
                    $student->is_active ? 'Yes' : 'No',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 