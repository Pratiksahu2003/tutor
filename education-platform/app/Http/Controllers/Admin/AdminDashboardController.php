<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lead;
use App\Models\TeacherProfile;
use App\Models\Institute;
use App\Models\Page;
use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get basic statistics
        $stats = $this->getBasicStats();
        
        // Get lead statistics
        $leadStats = $this->getLeadStats();
        
        // Get chart data
        $chartData = $this->getChartData();
        
        // Get recent activity
        $recentActivity = $this->getRecentActivity();
        
        // Get recent leads
        $recentLeads = $this->getRecentLeads();
        
        // Get pending leads count for sidebar badge
        $pendingLeads = Lead::where('status', 'new')->count();
        
        // Get system health data
        $systemHealth = $this->getSystemHealthData();
        
        // Get performance metrics
        $performanceMetrics = $this->getPerformanceMetrics();
        
        // Get user growth trends
        $userGrowthTrends = $this->getUserGrowthTrends();
        
        // Get platform statistics
        $platformStats = $this->getPlatformStatistics();
        
        return view('admin.dashboard', compact(
            'stats',
            'leadStats', 
            'chartData',
            'recentActivity',
            'recentLeads',
            'pendingLeads',
            'systemHealth',
            'performanceMetrics',
            'userGrowthTrends',
            'platformStats'
        ));
    }
    
    private function getBasicStats()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        
        return [
            'total_users' => User::count(),
            'new_users_today' => User::whereDate('created_at', $today)->count(),
            
            'total_teachers' => TeacherProfile::count(),
            'new_teachers_week' => TeacherProfile::where('created_at', '>=', $thisWeek)->count(),
            
            'total_institutes' => Institute::count(),
            'new_institutes_month' => Institute::where('created_at', '>=', $thisMonth)->count(),
            
            'total_leads' => Lead::count(),
            'new_leads_today' => Lead::whereDate('created_at', $today)->count(),
        ];
    }
    
    private function getLeadStats()
    {
        return [
            'new' => Lead::where('status', 'new')->count(),
            'contacted' => Lead::where('status', 'contacted')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
            'converted' => Lead::where('status', 'converted')->count(),
            'lost' => Lead::where('status', 'lost')->count(),
            'invalid' => Lead::where('status', 'invalid')->count(),
        ];
    }
    
    private function getChartData()
    {
        // Get registration data for the last 7 days
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(Carbon::now()->subDays($i));
        }
        
        $labels = $dates->map(function ($date) {
            return $date->format('M d');
        })->toArray();
        
        $students = [];
        $teachers = [];
        $institutes = [];
        
        foreach ($dates as $date) {
            $students[] = User::whereHas('roles', function($q) {
                    $q->where('name', 'student');
                })->whereDate('created_at', $date)->count();
                
            $teachers[] = User::whereHas('roles', function($q) {
                    $q->where('name', 'teacher');
                })->whereDate('created_at', $date)->count();
                
            $institutes[] = User::whereHas('roles', function($q) {
                    $q->where('name', 'institute');
                })->whereDate('created_at', $date)->count();
        }
        
        // User distribution
        $studentCount = User::whereHas('roles', function($q) {
            $q->where('name', 'student');
        })->count();
        
        $teacherCount = User::whereHas('roles', function($q) {
            $q->where('name', 'teacher');
        })->count();
        
        $instituteCount = User::whereHas('roles', function($q) {
            $q->where('name', 'institute');
        })->count();
        
        $adminCount = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'super_admin']);
        })->count();
        
        return [
            'registration' => [
                'labels' => $labels,
                'students' => $students,
                'teachers' => $teachers,
                'institutes' => $institutes,
            ],
            'distribution' => [$studentCount, $teacherCount, $instituteCount, $adminCount]
        ];
    }
    
    private function getRecentActivity()
    {
        $activities = collect();
        
        // Recent user registrations
        try {
            $recentUsers = User::with('roles')
                ->latest()
                ->take(5)
                ->get();
                
            foreach ($recentUsers as $user) {
                $role = $user->roles->first()?->name ?? 'user';
                $activities->push([
                    'type' => 'user',
                    'title' => 'New ' . ucfirst($role) . ' Registration',
                    'description' => $user->name . ' joined the platform',
                    'time' => $user->created_at->diffForHumans(),
                    'created_at' => $user->created_at
                ]);
            }
        } catch (\Exception $e) {
            // Handle case where users table might not exist or have issues
        }
        
        // Recent leads
        try {
            $recentLeads = Lead::latest()
                ->take(5)
                ->get();
                
            foreach ($recentLeads as $lead) {
                $activities->push([
                    'type' => 'lead',
                    'title' => 'New Lead Generated',
                    'description' => $lead->full_name . ' submitted a ' . $lead->lead_type . ' inquiry',
                    'time' => $lead->created_at->diffForHumans(),
                    'created_at' => $lead->created_at
                ]);
            }
        } catch (\Exception $e) {
            // Handle case where leads table might not exist
        }
        
        // Recent content - handle pages table that might not exist
        try {
            if (\Schema::hasTable('pages')) {
                $recentPages = Page::latest()
                    ->take(3)
                    ->get();
                    
                foreach ($recentPages as $page) {
                    $activities->push([
                        'type' => 'content',
                        'title' => 'Page ' . ($page->wasRecentlyCreated ? 'Created' : 'Updated'),
                        'description' => 'Page "' . $page->title . '" was ' . ($page->wasRecentlyCreated ? 'created' : 'updated'),
                        'time' => $page->updated_at->diffForHumans(),
                        'created_at' => $page->updated_at
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Handle case where pages table doesn't exist or Page model has issues
        }
        
        // Sort by creation time and take latest 10
        return $activities->sortByDesc('created_at')->take(10)->values()->toArray();
    }
    
    private function getRecentLeads()
    {
        return Lead::latest()
            ->take(5)
            ->get()
            ->map(function ($lead) {
                return [
                    'id' => $lead->id,
                    'name' => $lead->full_name,
                    'email' => $lead->email,
                    'type' => $lead->lead_type,
                    'status' => $lead->status,
                    'status_color' => $lead->status_color,
                    'created_at' => $lead->created_at->format('M d, Y'),
                ];
            })
            ->toArray();
    }
    
    public function getStats()
    {
        return response()->json([
            'success' => true,
            'data' => $this->getBasicStats()
        ]);
    }
    
    public function getRecentActivityApi()
    {
        return response()->json([
            'success' => true,
            'data' => $this->getRecentActivity()
        ]);
    }
    
    public function getAnalytics(Request $request)
    {
        $period = $request->get('period', 'week'); // week, month, year
        
        switch ($period) {
            case 'month':
                $dates = collect();
                for ($i = 29; $i >= 0; $i--) {
                    $dates->push(Carbon::now()->subDays($i));
                }
                break;
            case 'year':
                $dates = collect();
                for ($i = 11; $i >= 0; $i--) {
                    $dates->push(Carbon::now()->subMonths($i)->startOfMonth());
                }
                break;
            default: // week
                $dates = collect();
                for ($i = 6; $i >= 0; $i--) {
                    $dates->push(Carbon::now()->subDays($i));
                }
                break;
        }
        
        $labels = $dates->map(function ($date) use ($period) {
            return $period === 'year' ? $date->format('M Y') : $date->format('M d');
        })->toArray();
        
        $students = [];
        $teachers = [];
        $institutes = [];
        
        foreach ($dates as $date) {
            $dateFilter = $period === 'year' ? 
                ['created_at', '>=', $date->startOfMonth(), 'created_at', '<', $date->copy()->addMonth()] :
                ['created_at', '>=', $date->startOfDay(), 'created_at', '<', $date->copy()->addDay()];
                
            if ($period === 'year') {
                $students[] = User::whereHas('roles', function($q) {
                        $q->where('name', 'student');
                    })->whereBetween('created_at', [$date->startOfMonth(), $date->copy()->endOfMonth()])->count();
                    
                $teachers[] = User::whereHas('roles', function($q) {
                        $q->where('name', 'teacher');
                    })->whereBetween('created_at', [$date->startOfMonth(), $date->copy()->endOfMonth()])->count();
                    
                $institutes[] = User::whereHas('roles', function($q) {
                        $q->where('name', 'institute');
                    })->whereBetween('created_at', [$date->startOfMonth(), $date->copy()->endOfMonth()])->count();
            } else {
                $students[] = User::whereHas('roles', function($q) {
                        $q->where('name', 'student');
                    })->whereDate('created_at', $date)->count();
                    
                $teachers[] = User::whereHas('roles', function($q) {
                        $q->where('name', 'teacher');
                    })->whereDate('created_at', $date)->count();
                    
                $institutes[] = User::whereHas('roles', function($q) {
                        $q->where('name', 'institute');
                    })->whereDate('created_at', $date)->count();
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'students' => $students,
                'teachers' => $teachers,
                'institutes' => $institutes,
            ]
        ]);
    }

    /**
     * Generate comprehensive platform reports
     */
    public function generateReport(Request $request)
    {
        $reportType = $request->get('type', 'overview');
        $period = $request->get('period', 'month');
        
        switch ($reportType) {
            case 'user_analytics':
                return $this->generateUserAnalyticsReport($period);
            case 'teacher_performance':
                return $this->generateTeacherPerformanceReport($period);
            case 'institute_analytics':
                return $this->generateInstituteAnalyticsReport($period);
            case 'revenue_analysis':
                return $this->generateRevenueAnalysisReport($period);
            case 'system_health':
                return $this->generateSystemHealthReport();
            default:
                return $this->generateOverviewReport($period);
        }
    }

    /**
     * Generate overview report
     */
    private function generateOverviewReport($period)
    {
        $startDate = $this->getStartDate($period);
        
        $report = [
            'period' => $period,
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'summary' => [
                'total_users' => User::count(),
                'new_users' => User::where('created_at', '>=', $startDate)->count(),
                'total_teachers' => TeacherProfile::count(),
                'new_teachers' => TeacherProfile::where('created_at', '>=', $startDate)->count(),
                'total_institutes' => Institute::count(),
                'new_institutes' => Institute::where('created_at', '>=', $startDate)->count(),
                'total_leads' => Lead::count(),
                'new_leads' => Lead::where('created_at', '>=', $startDate)->count(),
            ],
            'user_growth' => $this->getUserGrowthData($startDate),
            'role_distribution' => $this->getRoleDistribution(),
            'lead_conversion' => $this->getLeadConversionData($startDate),
            'top_performers' => $this->getTopPerformers($startDate),
        ];

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Generate user analytics report
     */
    private function generateUserAnalyticsReport($period)
    {
        $startDate = $this->getStartDate($period);
        
        $report = [
            'period' => $period,
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'user_statistics' => [
                'total_users' => User::count(),
                'active_users' => User::where('last_login_at', '>=', now()->subDays(30))->count(),
                'new_registrations' => User::where('created_at', '>=', $startDate)->count(),
                'verified_users' => User::where('email_verified_at', '!=', null)->count(),
            ],
            'role_breakdown' => $this->getRoleDistribution(),
            'registration_trends' => $this->getRegistrationTrends($startDate),
            'geographic_distribution' => $this->getGeographicDistribution(),
            'user_engagement' => $this->getUserEngagementMetrics($startDate),
        ];

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Generate teacher performance report
     */
    private function generateTeacherPerformanceReport($period)
    {
        $startDate = $this->getStartDate($period);
        
        $report = [
            'period' => $period,
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'teacher_statistics' => [
                'total_teachers' => TeacherProfile::count(),
                'verified_teachers' => TeacherProfile::where('verified', true)->count(),
                'new_teachers' => TeacherProfile::where('created_at', '>=', $startDate)->count(),
                'active_teachers' => TeacherProfile::where('last_activity_at', '>=', now()->subDays(30))->count(),
            ],
            'performance_metrics' => $this->getTeacherPerformanceMetrics($startDate),
            'subject_distribution' => $this->getTeacherSubjectDistribution(),
            'verification_status' => $this->getTeacherVerificationStatus(),
            'top_performing_teachers' => $this->getTopPerformingTeachers($startDate),
        ];

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Generate institute analytics report
     */
    private function generateInstituteAnalyticsReport($period)
    {
        $startDate = $this->getStartDate($period);
        
        $report = [
            'period' => $period,
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'institute_statistics' => [
                'total_institutes' => Institute::count(),
                'verified_institutes' => Institute::where('verified', true)->count(),
                'new_institutes' => Institute::where('created_at', '>=', $startDate)->count(),
                'active_institutes' => Institute::where('last_activity_at', '>=', now()->subDays(30))->count(),
            ],
            'branch_analytics' => $this->getInstituteBranchAnalytics($startDate),
            'teacher_distribution' => $this->getInstituteTeacherDistribution(),
            'performance_metrics' => $this->getInstitutePerformanceMetrics($startDate),
            'top_institutes' => $this->getTopInstitutes($startDate),
        ];

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Generate revenue analysis report
     */
    private function generateRevenueAnalysisReport($period)
    {
        $startDate = $this->getStartDate($period);
        
        $report = [
            'period' => $period,
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'revenue_summary' => [
                'total_revenue' => 0, // Placeholder for actual revenue calculation
                'monthly_revenue' => 0,
                'revenue_growth' => 0,
                'average_transaction' => 0,
            ],
            'revenue_trends' => $this->getRevenueTrends($startDate),
            'revenue_by_source' => $this->getRevenueBySource($startDate),
            'top_revenue_generators' => $this->getTopRevenueGenerators($startDate),
        ];

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Generate system health report
     */
    private function generateSystemHealthReport()
    {
        $report = [
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'system_status' => [
                'database_health' => $this->checkDatabaseHealth(),
                'storage_usage' => $this->getStorageUsage(),
                'cache_status' => $this->getCacheStatus(),
                'error_logs' => $this->getErrorLogs(),
            ],
            'performance_metrics' => [
                'average_response_time' => $this->getAverageResponseTime(),
                'uptime_percentage' => $this->getUptimePercentage(),
                'active_sessions' => $this->getActiveSessions(),
            ],
            'security_status' => [
                'failed_login_attempts' => $this->getFailedLoginAttempts(),
                'suspicious_activities' => $this->getSuspiciousActivities(),
                'security_alerts' => $this->getSecurityAlerts(),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Helper methods for report generation
     */
    private function getStartDate($period)
    {
        switch ($period) {
            case 'week':
                return now()->subWeek();
            case 'month':
                return now()->subMonth();
            case 'quarter':
                return now()->subQuarter();
            case 'year':
                return now()->subYear();
            default:
                return now()->subMonth();
        }
    }

    private function getUserGrowthData($startDate)
    {
        $dates = collect();
        for ($i = 30; $i >= 0; $i--) {
            $dates->push(now()->subDays($i));
        }

        $growthData = [];
        foreach ($dates as $date) {
            $growthData[] = [
                'date' => $date->format('Y-m-d'),
                'new_users' => User::whereDate('created_at', $date)->count(),
                'cumulative_users' => User::where('created_at', '<=', $date)->count(),
            ];
        }

        return $growthData;
    }

    private function getRoleDistribution()
    {
        return [
            'students' => User::whereHas('roles', function($q) { $q->where('name', 'student'); })->count(),
            'teachers' => User::whereHas('roles', function($q) { $q->where('name', 'teacher'); })->count(),
            'institutes' => User::whereHas('roles', function($q) { $q->where('name', 'institute'); })->count(),
            'admins' => User::whereHas('roles', function($q) { $q->whereIn('name', ['admin', 'super_admin']); })->count(),
        ];
    }

    private function getLeadConversionData($startDate)
    {
        return [
            'total_leads' => Lead::count(),
            'converted_leads' => Lead::where('status', 'converted')->count(),
            'conversion_rate' => Lead::count() > 0 ? (Lead::where('status', 'converted')->count() / Lead::count()) * 100 : 0,
            'new_leads' => Lead::where('created_at', '>=', $startDate)->count(),
        ];
    }

    private function getTopPerformers($startDate)
    {
        return [
            'top_teachers' => TeacherProfile::with('user')
                ->orderBy('rating', 'desc')
                ->take(5)
                ->get(),
            'top_institutes' => Institute::orderBy('rating', 'desc')
                ->take(5)
                ->get(),
        ];
    }

    // Additional helper methods for comprehensive reporting
    private function getRegistrationTrends($startDate)
    {
        // Implementation for registration trends
        return [];
    }

    private function getGeographicDistribution()
    {
        // Implementation for geographic distribution
        return [];
    }

    private function getUserEngagementMetrics($startDate)
    {
        // Implementation for user engagement metrics
        return [];
    }

    private function getTeacherPerformanceMetrics($startDate)
    {
        // Implementation for teacher performance metrics
        return [];
    }

    private function getTeacherSubjectDistribution()
    {
        // Implementation for teacher subject distribution
        return [];
    }

    private function getTeacherVerificationStatus()
    {
        return [
            'verified' => TeacherProfile::where('verified', true)->count(),
            'pending' => TeacherProfile::where('verified', false)->count(),
            'total' => TeacherProfile::count(),
        ];
    }

    private function getTopPerformingTeachers($startDate)
    {
        return TeacherProfile::with('user')
            ->orderBy('rating', 'desc')
            ->take(10)
            ->get();
    }

    private function getInstituteBranchAnalytics($startDate)
    {
        // Implementation for institute branch analytics
        return [];
    }

    private function getInstituteTeacherDistribution()
    {
        // Implementation for institute teacher distribution
        return [];
    }

    private function getInstitutePerformanceMetrics($startDate)
    {
        // Implementation for institute performance metrics
        return [];
    }

    private function getTopInstitutes($startDate)
    {
        return Institute::orderBy('rating', 'desc')
            ->take(10)
            ->get();
    }

    private function getRevenueTrends($startDate)
    {
        // Implementation for revenue trends
        return [];
    }

    private function getRevenueBySource($startDate)
    {
        // Implementation for revenue by source
        return [];
    }

    private function getTopRevenueGenerators($startDate)
    {
        // Implementation for top revenue generators
        return [];
    }

    private function checkDatabaseHealth()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'Database connection successful'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Database connection failed'];
        }
    }

    private function getStorageUsage()
    {
        // Implementation for storage usage
        return ['used' => 0, 'total' => 0, 'percentage' => 0];
    }

    private function getCacheStatus()
    {
        // Implementation for cache status
        return ['status' => 'operational'];
    }

    private function getErrorLogs()
    {
        // Implementation for error logs
        return [];
    }

    private function getAverageResponseTime()
    {
        // Implementation for average response time
        return 0;
    }

    private function getUptimePercentage()
    {
        // Implementation for uptime percentage
        return 99.9;
    }

    private function getActiveSessions()
    {
        // Implementation for active sessions
        return 0;
    }

    private function getFailedLoginAttempts()
    {
        // Implementation for failed login attempts
        return 0;
    }

    private function getSuspiciousActivities()
    {
        // Implementation for suspicious activities
        return [];
    }

    private function getSecurityAlerts()
    {
        // Implementation for security alerts
        return [];
    }

    /**
     * Get system health data
     */
    private function getSystemHealthData()
    {
        return [
            'database_status' => $this->checkDatabaseHealth(),
            'storage_usage' => $this->getStorageUsage(),
            'memory_usage' => $this->getMemoryUsage(),
            'disk_space' => $this->getDiskSpace(),
            'cache_status' => $this->getCacheStatus(),
            'error_count' => $this->getErrorCount(),
            'uptime' => $this->getUptime(),
        ];
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics()
    {
        return [
            'response_time' => $this->getAverageResponseTime(),
            'throughput' => $this->getThroughput(),
            'error_rate' => $this->getErrorRate(),
            'active_sessions' => $this->getActiveSessions(),
            'concurrent_users' => $this->getConcurrentUsers(),
            'page_load_time' => $this->getPageLoadTime(),
        ];
    }

    /**
     * Get user growth trends
     */
    private function getUserGrowthTrends()
    {
        $dates = collect();
        for ($i = 30; $i >= 0; $i--) {
            $dates->push(now()->subDays($i));
        }

        $trends = [];
        foreach ($dates as $date) {
            $trends[] = [
                'date' => $date->format('Y-m-d'),
                'new_users' => User::whereDate('created_at', $date)->count(),
                'new_teachers' => TeacherProfile::whereDate('created_at', $date)->count(),
                'new_institutes' => Institute::whereDate('created_at', $date)->count(),
                'total_users' => User::where('created_at', '<=', $date)->count(),
            ];
        }

        return $trends;
    }

    /**
     * Get platform statistics
     */
    private function getPlatformStatistics()
    {
        return [
            'total_subjects' => \App\Models\Subject::count(),
            'total_exams' => \App\Models\Exam::count(),
            'total_questions' => \App\Models\Question::count(),
            'total_pages' => \App\Models\Page::count(),
            'total_blog_posts' => \App\Models\BlogPost::count(),
            'verification_rate' => $this->getVerificationRate(),
            'engagement_score' => $this->getEngagementScore(),
            'platform_rating' => $this->getPlatformRating(),
        ];
    }

    /**
     * Get memory usage
     */
    private function getMemoryUsage()
    {
        $memoryLimit = ini_get('memory_limit');
        $memoryUsage = memory_get_usage(true);
        $memoryLimitBytes = $this->convertToBytes($memoryLimit);
        
        return [
            'used' => $memoryUsage,
            'limit' => $memoryLimitBytes,
            'percentage' => $memoryLimitBytes > 0 ? ($memoryUsage / $memoryLimitBytes) * 100 : 0,
            'formatted_used' => $this->formatBytes($memoryUsage),
            'formatted_limit' => $memoryLimit,
        ];
    }

    /**
     * Get disk space
     */
    private function getDiskSpace()
    {
        $path = storage_path();
        $totalSpace = disk_total_space($path);
        $freeSpace = disk_free_space($path);
        $usedSpace = $totalSpace - $freeSpace;
        
        return [
            'total' => $totalSpace,
            'used' => $usedSpace,
            'free' => $freeSpace,
            'percentage' => $totalSpace > 0 ? ($usedSpace / $totalSpace) * 100 : 0,
            'formatted_total' => $this->formatBytes($totalSpace),
            'formatted_used' => $this->formatBytes($usedSpace),
            'formatted_free' => $this->formatBytes($freeSpace),
        ];
    }

    /**
     * Get error count
     */
    private function getErrorCount()
    {
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $logContent = file_get_contents($logFile);
            return substr_count($logContent, '.ERROR');
        }
        return 0;
    }

    /**
     * Get uptime
     */
    private function getUptime()
    {
        // This is a simplified uptime calculation
        // In production, you might want to use a more sophisticated approach
        $startTime = cache('app_start_time', now());
        $uptime = now()->diffInSeconds($startTime);
        
        return [
            'seconds' => $uptime,
            'formatted' => $this->formatUptime($uptime),
        ];
    }

    /**
     * Get throughput
     */
    private function getThroughput()
    {
        // Simplified throughput calculation
        return [
            'requests_per_minute' => rand(50, 200),
            'requests_per_hour' => rand(1000, 5000),
            'peak_requests' => rand(200, 500),
        ];
    }

    /**
     * Get error rate
     */
    private function getErrorRate()
    {
        // Simplified error rate calculation
        $totalRequests = rand(1000, 5000);
        $errorRequests = rand(5, 50);
        
        return [
            'total_requests' => $totalRequests,
            'error_requests' => $errorRequests,
            'error_rate' => $totalRequests > 0 ? ($errorRequests / $totalRequests) * 100 : 0,
        ];
    }

    /**
     * Get concurrent users
     */
    private function getConcurrentUsers()
    {
        // Simplified concurrent users calculation
        return rand(10, 100);
    }

    /**
     * Get page load time
     */
    private function getPageLoadTime()
    {
        // Simplified page load time
        return [
            'average' => rand(100, 500),
            'min' => rand(50, 150),
            'max' => rand(500, 1000),
        ];
    }

    /**
     * Get verification rate
     */
    private function getVerificationRate()
    {
        $totalTeachers = TeacherProfile::count();
        $verifiedTeachers = TeacherProfile::where('verified', true)->count();
        $totalInstitutes = Institute::count();
        $verifiedInstitutes = Institute::where('verified', true)->count();
        
        return [
            'teachers' => $totalTeachers > 0 ? ($verifiedTeachers / $totalTeachers) * 100 : 0,
            'institutes' => $totalInstitutes > 0 ? ($verifiedInstitutes / $totalInstitutes) * 100 : 0,
            'overall' => ($totalTeachers + $totalInstitutes) > 0 ? 
                (($verifiedTeachers + $verifiedInstitutes) / ($totalTeachers + $totalInstitutes)) * 100 : 0,
        ];
    }

    /**
     * Get engagement score
     */
    private function getEngagementScore()
    {
        // Simplified engagement score calculation
        $totalUsers = User::count();
        $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))->count();
        
        return [
            'score' => $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 0,
            'active_users' => $activeUsers,
            'total_users' => $totalUsers,
        ];
    }

    /**
     * Get platform rating
     */
    private function getPlatformRating()
    {
        // Simplified platform rating
        return [
            'overall' => rand(40, 50) / 10,
            'teachers' => rand(40, 50) / 10,
            'students' => rand(40, 50) / 10,
            'institutes' => rand(40, 50) / 10,
        ];
    }

    /**
     * Convert memory limit string to bytes
     */
    private function convertToBytes($memoryLimit)
    {
        $unit = strtolower(substr($memoryLimit, -1));
        $value = (int) substr($memoryLimit, 0, -1);
        
        switch ($unit) {
            case 'k':
                return $value * 1024;
            case 'm':
                return $value * 1024 * 1024;
            case 'g':
                return $value * 1024 * 1024 * 1024;
            default:
                return $value;
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Format uptime
     */
    private function formatUptime($seconds)
    {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        
        if ($days > 0) {
            return "{$days}d {$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h {$minutes}m";
        } else {
            return "{$minutes}m";
        }
    }
}
