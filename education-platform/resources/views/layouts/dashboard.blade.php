<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Education Platform') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom Dashboard Styles -->
    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --secondary-color: #6366f1;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --border-color: #e5e7eb;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --sidebar-bg: #ffffff;
            --sidebar-hover: #f3f4f6;
            --card-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --card-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: var(--card-shadow-lg);
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            height: var(--header-height);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            background: var(--primary-color);
            color: white;
        }

        .sidebar-header .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-header .logo i {
            font-size: 2rem;
        }

        .sidebar-toggle {
            position: absolute;
            right: -15px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            background: var(--primary-color);
            border: none;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: var(--primary-dark);
            transform: translateY(-50%) scale(1.1);
        }

        .sidebar-nav {
            padding: 1rem 0;
            height: calc(100vh - var(--header-height));
            overflow-y: auto;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 3px;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            padding: 0 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-secondary);
            letter-spacing: 0.05em;
        }

        .sidebar.collapsed .nav-section-title {
            display: none;
        }

        .nav-item {
            margin: 0 0.75rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
            font-weight: 500;
        }

        .nav-link:hover {
            background: var(--sidebar-hover);
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: var(--card-shadow);
        }

        .nav-link.active:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateX(0);
        }

        .nav-link i {
            width: 20px;
            font-size: 1.25rem;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        /* Badge Styles */
        .nav-badge {
            background: var(--primary-color);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 0.8rem;
            margin-left: auto;
            font-weight: 600;
        }

        .sidebar.collapsed .nav-badge {
            display: none;
        }

        /* Main Content Area */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
        }

        .main-wrapper.expanded {
            margin-left: 80px;
        }

        /* Header */
        .main-header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--card-shadow);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .search-box {
            position: relative;
            width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            background: var(--light-color);
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            padding: 0.5rem;
            border-radius: 0.5rem;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-btn:hover {
            background: var(--sidebar-hover);
            color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            width: 8px;
            height: 8px;
            background: var(--danger-color);
            border-radius: 50%;
        }

        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-dropdown:hover {
            background: var(--sidebar-hover);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border-color);
        }

        .user-info h6 {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .user-info p {
            margin: 0;
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Main Content */
        .main-content {
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }

        /* Card Styles */
        .modern-card {
            background: white;
            border-radius: 1rem;
            border: none;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .modern-card:hover {
            box-shadow: var(--card-shadow-lg);
            transform: translateY(-2px);
        }

        .modern-card .card-header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
            font-weight: 600;
        }

        .modern-card .card-body {
            padding: 1.5rem;
        }

        /* Stat Cards */
        .stat-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 1rem;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .stat-card .stat-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-card .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .search-box {
                display: none;
            }

            .main-content {
                padding: 1rem;
            }
        }

        /* Animation */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slide-in {
            animation: slideInRight 0.5s ease-out;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-color);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }

        .nav-badge {
            background: var(--primary-color);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 0.8rem;
            margin-left: auto;
            font-weight: 600;
        }

        .nav-logout-form {
            margin: 0;
            padding: 0;
        }

        .nav-logout-form .nav-link {
            color: var(--danger-color) !important;
        }

        .nav-logout-form .nav-link:hover {
            background: rgba(239, 68, 68, 0.1) !important;
            color: var(--danger-color) !important;
        }

        /* Badge variations */
        .nav-badge.bg-success {
            background: var(--success-color) !important;
        }

        .nav-badge.bg-warning {
            background: var(--warning-color) !important;
        }

        .nav-badge.bg-danger {
            background: var(--danger-color) !important;
        }

        .nav-badge.bg-info {
            background: var(--info-color) !important;
        }

        /* External link indicator */
        .nav-link .bi-box-arrow-up-right {
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        .nav-link:hover .bi-box-arrow-up-right {
            opacity: 1;
        }

        /* Section dividers */
        .nav-section {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .nav-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        /* Hover effects */
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 3px;
            height: 0;
            background: var(--primary-color);
            transform: translateY(-50%);
            transition: height 0.3s ease;
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            height: 60%;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="logo">
                <i class="bi bi-mortarboard-fill"></i>
                <span>EduPlatform</span>
            </a>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-chevron-left"></i>
            </button>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="sidebar-nav">
            <!-- Main Navigation -->
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                
                <div class="nav-item">
                    <a href="{{ route('dashboard.modern') }}" class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('dashboard.profile') }}" class="nav-link {{ request()->routeIs('dashboard.profile') ? 'active' : '' }}">
                        <i class="bi bi-person-gear"></i>
                        <span>Profile Settings</span>
                    </a>
                </div>

                @if(auth()->user()->isAdmin())
                    <div class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link" target="_blank">
                            <i class="bi bi-shield-check"></i>
                            <span>Admin Panel</span>
                            <i class="bi bi-box-arrow-up-right ms-auto" style="font-size: 0.8rem;"></i>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Role-specific Navigation -->
            @if(auth()->user()->role === 'admin')
                <div class="nav-section">
                    <div class="nav-section-title">Administration</div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link">
                            <i class="bi bi-people"></i>
                            <span>User Management</span>
                            <span class="nav-badge">{{ \App\Models\User::count() }}</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.teachers.index', ['verified' => 'unverified']) }}" class="nav-link">
                            <i class="bi bi-patch-check"></i>
                            <span>Teacher Verifications</span>
                            @if(\App\Models\TeacherProfile::where('verified', false)->count() > 0)
                                <span class="nav-badge">{{ \App\Models\TeacherProfile::where('verified', false)->count() }}</span>
                            @endif
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.institutes.index', ['verified' => 'unverified']) }}" class="nav-link">
                            <i class="bi bi-building-check"></i>
                            <span>Institute Verifications</span>
                            @if(\App\Models\Institute::where('verified', false)->count() > 0)
                                <span class="nav-badge">{{ \App\Models\Institute::where('verified', false)->count() }}</span>
                            @endif
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.teachers.statistics') }}" class="nav-link">
                            <i class="bi bi-bar-chart"></i>
                            <span>Analytics</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('admin.roles.index') }}" class="nav-link">
                            <i class="bi bi-person-badge"></i>
                            <span>Roles & Permissions</span>
                        </a>
                    </div>
                </div>

            @elseif(auth()->user()->role === 'teacher')
                <div class="nav-section">
                    <div class="nav-section-title">Teaching</div>
                    
                    <div class="nav-item">
                        <a href="{{ route('teacher.dashboard') }}" class="nav-link {{ request()->routeIs('teacher.*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>
                            <span>Teacher Dashboard</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-calendar-check"></i>
                            <span>Schedule</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-book"></i>
                            <span>My Subjects</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-currency-dollar"></i>
                            <span>Earnings</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-chat-square-dots"></i>
                            <span>Student Inquiries</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="#" class="nav-link" target="_blank">
                            <i class="bi bi-eye"></i>
                            <span>View Public Profile</span>
                            <i class="bi bi-box-arrow-up-right ms-auto" style="font-size: 0.8rem;"></i>
                        </a>
                    </div>
                </div>

            @elseif(auth()->user()->role === 'institute')
                <div class="nav-section">
                    <div class="nav-section-title">Management</div>
                    
                    <div class="nav-item">
                        <a href="{{ route('institute.dashboard') }}" class="nav-link {{ request()->routeIs('institute.*') ? 'active' : '' }}">
                            <i class="bi bi-person-badge"></i>
                            <span>Institute Dashboard</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-people"></i>
                            <span>Students</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-building"></i>
                            <span>Branches</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-graph-up"></i>
                            <span>Analytics</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-chat-square-dots"></i>
                            <span>Inquiries</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="#" class="nav-link" target="_blank">
                            <i class="bi bi-eye"></i>
                            <span>View Public Profile</span>
                            <i class="bi bi-box-arrow-up-right ms-auto" style="font-size: 0.8rem;"></i>
                        </a>
                    </div>
                </div>

            @else
                <div class="nav-section">
                    <div class="nav-section-title">Learning</div>
                    
                    <div class="nav-item">
                        <a href="{{ route('search.teachers') }}" class="nav-link {{ request()->routeIs('search.teachers') ? 'active' : '' }}">
                            <i class="bi bi-search"></i>
                            <span>Find Teachers</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('institutes.index') }}" class="nav-link {{ request()->routeIs('institutes.*') ? 'active' : '' }}">
                            <i class="bi bi-building"></i>
                            <span>Find Institutes</span>
                        </a>
                    </div>
                    
                    <!-- Student learning routes - use working student routes -->
                    @if(auth()->user()->role === 'student')
                        <div class="nav-item">
                            <a href="{{ route('student.bookings.index') }}" class="nav-link {{ request()->routeIs('student.bookings.*') ? 'active' : '' }}">
                                <i class="bi bi-bookmark"></i>
                                <span>My Bookings</span>
                            </a>
                        </div>
                        
                        <div class="nav-item">
                            <a href="{{ route('student.communication.messages') }}" class="nav-link {{ request()->routeIs('student.communication.*') ? 'active' : '' }}">
                                <i class="bi bi-chat-dots"></i>
                                <span>Messages</span>
                            </a>
                        </div>
                        
                        <div class="nav-item">
                            <a href="{{ route('student.reviews.index') }}" class="nav-link {{ request()->routeIs('student.reviews.*') ? 'active' : '' }}">
                                <i class="bi bi-star"></i>
                                <span>My Reviews</span>
                            </a>
                        </div>

                        <div class="nav-item">
                            <a href="{{ route('student.favorites.teachers') }}" class="nav-link {{ request()->routeIs('student.favorites.*') ? 'active' : '' }}">
                                <i class="bi bi-heart"></i>
                                <span>Favorites</span>
                            </a>
                        </div>
                    @else
                        <!-- Non-student users get basic search options -->
                        <div class="nav-item">
                            <a href="{{ route('student.bookings.index') }}" class="nav-link">
                                <i class="bi bi-bookmark"></i>
                                <span>Bookings</span>
                            </a>
                        </div>
                        
                        <div class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bi bi-chat-dots"></i>
                                <span>Messages</span>
                            </a>
                        </div>
                        
                        <div class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bi bi-star"></i>
                                <span>Reviews</span>
                            </a>
                        </div>

                        <div class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bi bi-heart"></i>
                                <span>Favorites</span>
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Browse & Discover -->
            <div class="nav-section">
                <div class="nav-section-title">Browse & Discover</div>
                
                <div class="nav-item">
                    <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->routeIs('teachers.index') ? 'active' : '' }}">
                        <i class="bi bi-person-video2"></i>
                        <span>All Teachers</span>
                        <span class="nav-badge">{{ \App\Models\User::where('role', 'teacher')->count() }}</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('institutes.index') }}" class="nav-link {{ request()->routeIs('institutes.index') ? 'active' : '' }}">
                        <i class="bi bi-buildings"></i>
                        <span>All Institutes</span>
                        <span class="nav-badge">{{ \App\Models\Institute::count() }}</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('search') }}" class="nav-link {{ request()->routeIs('search') ? 'active' : '' }}">
                        <i class="bi bi-search-heart"></i>
                        <span>Advanced Search</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i>
                        <span>Blog & Articles</span>
                    </a>
                </div>
            </div>

            <!-- General Navigation -->
            <div class="nav-section">
                <div class="nav-section-title">General</div>
                
                <div class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="bi bi-house"></i>
                        <span>Home</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-bell"></i>
                        <span>Notifications</span>
                        <span class="nav-badge">12</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                        <i class="bi bi-info-circle"></i>
                        <span>About Us</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                        <i class="bi bi-telephone"></i>
                        <span>Contact Support</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('faq') }}" class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}">
                        <i class="bi bi-question-circle"></i>
                        <span>Help & FAQ</span>
                    </a>
                </div>
            </div>

            <!-- Account Actions -->
            <div class="nav-section">
                <div class="nav-section-title">Account</div>
                
                <div class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Account Settings</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-shield-lock"></i>
                        <span>Privacy & Security</span>
                    </a>
                </div>

                <div class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
                        @csrf
                        <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </a>
                    </form>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper" id="mainWrapper">
        <!-- Header -->
        <header class="main-header">
            <div class="header-title">
                @yield('page-title', 'Dashboard')
            </div>

            <div class="header-actions">
                <!-- Search Box -->
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search...">
                </div>

                <!-- Notifications -->
                <button class="notification-btn">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge"></span>
                </button>

                <!-- User Dropdown -->
                <div class="user-dropdown" data-bs-toggle="dropdown">
                    <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-avatar.png') }}" 
                         alt="User" class="user-avatar">
                    <div class="user-info">
                        <h6>{{ auth()->user()->name }}</h6>
                        <p>{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </div>

                <!-- Dropdown Menu -->
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('dashboard.profile') }}">
                        <i class="bi bi-person me-2"></i>Profile
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <i class="bi bi-gear me-2"></i>Settings
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content animate-slide-in">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Dashboard JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('mainWrapper');
            const sidebarToggle = document.getElementById('sidebarToggle');

            // Sidebar toggle
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainWrapper.classList.toggle('expanded');
                
                const icon = sidebarToggle.querySelector('i');
                icon.className = sidebar.classList.contains('collapsed') 
                    ? 'bi bi-chevron-right' 
                    : 'bi bi-chevron-left';
            });

            // Mobile sidebar toggle
            const mobileToggle = document.createElement('button');
            mobileToggle.innerHTML = '<i class="bi bi-list"></i>';
            mobileToggle.className = 'btn btn-primary d-md-none position-fixed';
            mobileToggle.style.cssText = 'top: 1rem; left: 1rem; z-index: 1001;';
            
            if (window.innerWidth <= 768) {
                document.body.appendChild(mobileToggle);
                
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });

                // Close sidebar when clicking outside
                document.addEventListener('click', function(e) {
                    if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                });
            }

            // Auto-hide notifications after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html> 