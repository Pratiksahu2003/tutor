@extends('layouts.dashboard')

@section('title', 'Unified Dashboard - ' . ucfirst($user->role))
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="container-fluid">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="unified-card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center">
                                <div class="me-4">
                                    <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.png') }}" 
                                         alt="Profile" 
                                         class="rounded-circle border border-3 border-white" 
                                         width="80" height="80">
                                </div>
                                <div>
                                    <h2 class="mb-2">Welcome back, {{ $user->name }}! 👋</h2>
                                    <p class="mb-3 opacity-90 lead">
                                        @if($user->role === 'student')
                                            Ready to continue your learning journey? Let's achieve your goals together!
                                        @elseif($user->role === 'teacher')
                                            Ready to inspire and educate students today? Your impact matters!
                                        @elseif($user->role === 'institute')
                                            Manage your institute efficiently and watch it grow!
                                        @elseif($user->role === 'admin')
                                            Monitor and manage the platform with powerful insights!
                                        @else
                                            Welcome to your personalized dashboard!
                                        @endif
                                    </p>
                                    @if(isset($dashboardData['needs_profile_setup']) && $dashboardData['needs_profile_setup'])
                                        <div class="alert alert-warning d-inline-flex align-items-center">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            <span>Complete your profile to unlock all features</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <div class="d-flex flex-lg-column gap-2">
                                <span class="badge bg-white text-primary fs-6 px-3 py-2">
                                    <i class="bi bi-person-badge me-1"></i>{{ ucfirst($user->role) }}
                                </span>
                                <a href="{{ route('dashboard.profile') }}" class="btn btn-light btn-lg">
                                    <i class="bi bi-person-gear me-2"></i>Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Setup Alert -->
    @if(isset($dashboardData['needs_profile_setup']) && $dashboardData['needs_profile_setup'])
        <div class="row mb-4">
            <div class="col-12">
                <div class="unified-card border-start border-warning border-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="card-title text-warning">
                                    <i class="bi bi-info-circle me-2"></i>Complete Your Profile
                                </h5>
                                <p class="card-text">To get the most out of our platform, please complete your {{ $user->role }} profile setup. This will help us provide you with personalized recommendations and better service.</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <a href="{{ route('dashboard.profile') }}" class="btn btn-warning btn-lg">
                                    <i class="bi bi-person-plus me-2"></i>Complete Profile Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    @if(!isset($dashboardData['needs_profile_setup']) || !$dashboardData['needs_profile_setup'])
        <div class="row mb-4">
            @if($user->role === 'admin')
                @include('dashboard.partials.unified-admin-stats', ['stats' => $dashboardData['stats']])
            @elseif($user->role === 'teacher')
                @include('dashboard.partials.unified-teacher-stats', ['stats' => $dashboardData['stats']])
            @elseif($user->role === 'institute')
                @include('dashboard.partials.unified-institute-stats', ['stats' => $dashboardData['stats']])
            @else
                @include('dashboard.partials.unified-student-stats', ['stats' => $dashboardData['stats']])
            @endif
        </div>

        <!-- Main Dashboard Content -->
        <div class="row">
            <!-- Left Column - Main Content -->
            <div class="col-lg-8 mb-4">
                @if($user->role === 'admin')
                    @include('dashboard.partials.unified-admin-content', $dashboardData)
                @elseif($user->role === 'teacher')
                    @include('dashboard.partials.unified-teacher-content', $dashboardData)
                @elseif($user->role === 'institute')
                    @include('dashboard.partials.unified-institute-content', $dashboardData)
                @else
                    @include('dashboard.partials.unified-student-content', $dashboardData)
                @endif
            </div>

            <!-- Right Column - Sidebar Content -->
            <div class="col-lg-4">
                @include('dashboard.partials.unified-sidebar-widgets', ['user' => $user, 'dashboardData' => $dashboardData])
            </div>
        </div>
    @endif

    <!-- Quick Actions Floating Button -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 999;">
        <div class="btn-group dropup">
            <button type="button" class="btn btn-primary btn-lg rounded-circle" data-bs-toggle="dropdown">
                <i class="bi bi-plus-lg"></i>
            </button>
            <ul class="dropdown-menu">
                @if($user->role === 'student')
                    <li><a class="dropdown-item" href="{{ route('search.teachers') }}">
                        <i class="bi bi-search me-2"></i>Find Teachers
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <i class="bi bi-bookmark-plus me-2"></i>Book Session
                    </a></li>
                @elseif($user->role === 'teacher')
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                        <i class="bi bi-plus-circle me-2"></i>Add Subject
                    </a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#scheduleSessionModal">
                        <i class="bi bi-calendar-plus me-2"></i>Schedule Session
                    </a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#earningsReportModal">
                        <i class="bi bi-graph-up me-2"></i>View Reports
                    </a></li>
                @elseif($user->role === 'institute')
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addBranchModal">
                        <i class="bi bi-building me-2"></i>Add Branch
                    </a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                        <i class="bi bi-person-badge me-2"></i>Add Teacher
                    </a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                        <i class="bi bi-book me-2"></i>Add Subject
                    </a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addExamTypeModal">
                        <i class="bi bi-file-text me-2"></i>Add Exam Type
                    </a></li>
                @else
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#manageUsersModal">
                        <i class="bi bi-people me-2"></i>Manage Users
                    </a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#systemSettingsModal">
                        <i class="bi bi-gear me-2"></i>System Settings
                    </a></li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">
                    <i class="bi bi-question-circle me-2"></i>Help & Support
                </a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Role-Specific Modals -->
@if($user->role === 'teacher')
    @include('dashboard.modals.teacher-modals')
@elseif($user->role === 'institute')
    @include('dashboard.modals.institute-modals')
@elseif($user->role === 'admin')
    @include('dashboard.modals.admin-modals')
@endif

@endsection

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }

    .unified-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .unified-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .stats-widget {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stats-widget::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    }

    .stats-widget:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin-bottom: 1rem;
    }

    .management-section {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: none;
        margin-bottom: 1.5rem;
    }

    .management-section h5 {
        color: var(--primary-color);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .report-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: none;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .chart-container {
        position: relative;
        height: 300px;
        margin: 1rem 0;
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1rem;
        color: white;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .activity-time {
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .quick-action-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        margin: 0.25rem;
    }

    .quick-action-btn:hover {
        background: var(--secondary-color);
        color: white;
        transform: translateY(-1px);
    }

    .period-toggle {
        background: var(--light-bg);
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        padding: 0.25rem;
        display: inline-flex;
    }

    .period-toggle .btn {
        border: none;
        border-radius: 0.25rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .period-toggle .btn.active {
        background: var(--primary-color);
        color: white;
    }

    .period-toggle .btn:not(.active) {
        background: transparent;
        color: var(--text-color);
    }

    .period-toggle .btn:not(.active):hover {
        background: var(--light-bg);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .unified-card {
            padding: 1rem;
        }

        .stats-widget {
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .management-section {
            padding: 1rem;
        }

        .chart-container {
            height: 250px;
        }
    }

    @media (max-width: 576px) {
        .stat-number {
            font-size: 1.25rem;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.25rem;
        }

        .quick-action-btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
    }

    /* Animation Classes */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    .slide-up {
        animation: slideUp 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(20px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Loading States */
    .loading {
        position: relative;
        overflow: hidden;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    /* Accessibility */
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    /* Focus States */
    .quick-action-btn:focus,
    .period-toggle .btn:focus {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }

    /* High Contrast Mode */
    @media (prefers-contrast: high) {
        .unified-card {
            border: 2px solid var(--border-color);
        }

        .stats-widget {
            border: 2px solid var(--border-color);
        }
    }

    /* Reduced Motion */
    @media (prefers-reduced-motion: reduce) {
        .unified-card,
        .stats-widget,
        .report-card,
        .quick-action-btn {
            transition: none;
        }

        .fade-in,
        .slide-up {
            animation: none;
        }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/unified-dashboard.js') }}"></script>
@endpush 