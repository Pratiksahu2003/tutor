<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\Institute;
use App\Models\BlogPost;
use App\Models\Lead;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        // Start with minimal data to identify the issue
        $stats = [
            'total_users' => 0,
            'total_teachers' => 0,
            'total_institutes' => 0,
            'total_leads' => 0,
            'total_sessions' => 0,
        ];

        $featured_teachers = collect([]);
        $recent_posts = collect([]);
        $testimonials = collect([]);
        $featured_institutes = collect([]);
        $popular_subjects = collect([]);
        $recent_activity = null;

        return view('home', compact(
            'stats',
            'featured_teachers',
            'recent_posts',
            'testimonials',
            'featured_institutes',
            'popular_subjects',
            'recent_activity'
        ));
    }

    /**
     * Get total sessions count (placeholder for now)
     */
    private function getTotalSessions()
    {
        // This would be from a sessions/bookings table
        // For now, return a reasonable estimate
        return Lead::where('status', 'converted')->count() * 5;
    }

    /**
     * Get testimonials data
     */
    private function getTestimonials()
    {
        // This could come from a testimonials table
        // For now, return sample data
        return collect([
            [
                'id' => 1,
                'name' => 'Sarah Johnson',
                'designation' => 'Student',
                'content' => 'Found an amazing math teacher through this platform. My grades improved significantly!',
                'rating' => 5,
                'avatar' => asset('images/testimonials/student1.jpg'),
            ],
            [
                'id' => 2,
                'name' => 'Michael Chen',
                'designation' => 'Parent',
                'content' => 'Excellent platform for finding qualified teachers. Very easy to use and reliable.',
                'rating' => 5,
                'avatar' => asset('images/testimonials/parent1.jpg'),
            ],
            [
                'id' => 3,
                'name' => 'Dr. Emily Davis',
                'designation' => 'Teacher',
                'content' => 'Great platform for educators. Easy to connect with serious students.',
                'rating' => 5,
                'avatar' => asset('images/testimonials/teacher1.jpg'),
            ],
        ]);
    }

    /**
     * Get popular subjects
     */
    private function getPopularSubjects()
    {
        return collect([
            ['name' => 'Mathematics', 'icon' => 'calculator', 'teachers_count' => 450],
            ['name' => 'Science', 'icon' => 'flask', 'teachers_count' => 380],
            ['name' => 'English', 'icon' => 'book', 'teachers_count' => 320],
            ['name' => 'Physics', 'icon' => 'atom', 'teachers_count' => 280],
            ['name' => 'Chemistry', 'icon' => 'beaker', 'teachers_count' => 250],
            ['name' => 'Computer Science', 'icon' => 'laptop', 'teachers_count' => 220],
        ]);
    }

    /**
     * Get user's recent activity
     */
    private function getUserRecentActivity()
    {
        // This would come from an activity log
        // For now, return sample data
        return collect([
            [
                'type' => 'session',
                'description' => 'Completed math session with John Doe',
                'timestamp' => now()->subHours(2),
            ],
            [
                'type' => 'booking',
                'description' => 'Booked physics session for tomorrow',
                'timestamp' => now()->subDays(1),
            ],
        ]);
    }

    /**
     * Get notifications for authenticated user (AJAX)
     */
    public function notifications(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // This would come from a notifications table
        $notifications = collect([
            [
                'id' => 1,
                'title' => 'Session Reminder',
                'message' => 'You have a math session in 1 hour',
                'type' => 'reminder',
                'read' => false,
                'created_at' => now()->subMinutes(30),
            ],
            [
                'id' => 2,
                'title' => 'New Message',
                'message' => 'You have a new message from John Doe',
                'type' => 'message',
                'read' => false,
                'created_at' => now()->subHours(2),
            ],
            [
                'id' => 3,
                'title' => 'Session Completed',
                'message' => 'Your physics session has been completed',
                'type' => 'success',
                'read' => true,
                'created_at' => now()->subDays(1),
            ],
        ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $notifications->where('read', false)->count(),
        ]);
    }

    /**
     * Mark notifications as read (AJAX)
     */
    public function markNotificationsRead(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // This would update notifications in database
        // For now, just return success
        return response()->json(['success' => true]);
    }

    /**
     * Get search suggestions (AJAX)
     */
    public function searchSuggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = Cache::remember("search.suggestions.{$query}", 1800, function () use ($query) {
            $teachers = TeacherProfile::with('user')
                                ->whereHas('user', function($q) use ($query) {
                                    $q->where('name', 'like', "%{$query}%");
                                })
                                ->where('status', 'active')
                                ->take(5)
                                ->get()
                                ->map(function($teacher) {
                                    return [
                                        'type' => 'teacher',
                                        'title' => $teacher->user->name ?? 'N/A',
                                        'subtitle' => $teacher->specialization ?? 'Teacher',
                                        'url' => route('teachers.show', $teacher->slug),
                                        'avatar' => $teacher->avatar,
                                    ];
                                });

            $institutes = Institute::where('name', 'like', "%{$query}%")
                               ->where('status', 'active')
                               ->take(5)
                               ->get()
                               ->map(function($institute) {
                                   return [
                                       'type' => 'institute',
                                       'title' => $institute->name,
                                       'subtitle' => ($institute->city ?? 'Unknown City') . ', ' . ($institute->state ?? 'Unknown State'),
                                       'url' => route('institutes.show', $institute->slug),
                                       'avatar' => $institute->logo,
                                   ];
                               });

            return $teachers->concat($institutes)->take(10);
        });

        return response()->json($suggestions);
    }

    /**
     * Get slider data for homepage
     */
    public function getSliderData()
    {
        // This could come from a sliders table
        return collect([
            [
                'id' => 1,
                'title' => 'Find Your Perfect Teacher',
                'subtitle' => 'Connect with qualified educators for personalized learning',
                'image' => asset('images/slider/slide1.jpg'),
                'cta_text' => 'Find Teachers',
                'cta_url' => route('search.teachers'),
            ],
            [
                'id' => 2,
                'title' => 'Join Top Educational Institutes',
                'subtitle' => 'Discover excellence in education with our partner institutes',
                'image' => asset('images/slider/slide2.jpg'),
                'cta_text' => 'Browse Institutes',
                'cta_url' => route('institutes.index'),
            ],
            [
                'id' => 3,
                'title' => 'Start Your Teaching Journey',
                'subtitle' => 'Share your knowledge and inspire the next generation',
                'image' => asset('images/slider/slide3.jpg'),
                'cta_text' => 'Become a Teacher',
                'cta_url' => route('register'),
            ],
        ]);
    }
}
