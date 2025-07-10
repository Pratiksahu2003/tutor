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
        
        return view('admin.dashboard', compact(
            'stats',
            'leadStats', 
            'chartData',
            'recentActivity',
            'recentLeads',
            'pendingLeads'
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
        
        // Recent leads
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
        
        // Recent content
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
}
