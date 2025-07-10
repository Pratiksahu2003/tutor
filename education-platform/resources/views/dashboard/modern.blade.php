@extends('layouts.dashboard')

@section('title', 'Modern Dashboard - ' . ucfirst($user->role))
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
            <div class="modern-card bg-gradient-primary text-white">
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
                <div class="modern-card border-start border-warning border-4">
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
                @include('dashboard.partials.modern-admin-stats', ['stats' => $dashboardData['stats']])
            @elseif($user->role === 'teacher')
                @include('dashboard.partials.modern-teacher-stats', ['stats' => $dashboardData['stats']])
            @elseif($user->role === 'institute')
                @include('dashboard.partials.modern-institute-stats', ['stats' => $dashboardData['stats']])
            @else
                @include('dashboard.partials.modern-student-stats', ['stats' => $dashboardData['stats']])
            @endif
        </div>

        <!-- Main Dashboard Content -->
        <div class="row">
            <!-- Left Column - Main Content -->
            <div class="col-lg-8 mb-4">
                @if($user->role === 'admin')
                    @include('dashboard.partials.modern-admin-content', $dashboardData)
                @elseif($user->role === 'teacher')
                    @include('dashboard.partials.modern-teacher-content', $dashboardData)
                @elseif($user->role === 'institute')
                    @include('dashboard.partials.modern-institute-content', $dashboardData)
                @else
                    @include('dashboard.partials.modern-student-content', $dashboardData)
                @endif
            </div>

            <!-- Right Column - Sidebar Content -->
            <div class="col-lg-4">
                @include('dashboard.partials.modern-sidebar-widgets', ['user' => $user, 'dashboardData' => $dashboardData])
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
                    <li><a class="dropdown-item" href="#">
                        <i class="bi bi-person-plus me-2"></i>Add Student
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <i class="bi bi-calendar-plus me-2"></i>Schedule Class
                    </a></li>
                @elseif($user->role === 'institute')
                    <li><a class="dropdown-item" href="#">
                        <i class="bi bi-person-badge me-2"></i>Add Teacher
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <i class="bi bi-building me-2"></i>Add Branch
                    </a></li>
                @else
                    <li><a class="dropdown-item" href="#">
                        <i class="bi bi-people me-2"></i>Manage Users
                    </a></li>
                    <li><a class="dropdown-item" href="#">
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
@endsection

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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
        transform: translateY(-5px);
        box-shadow: var(--card-shadow-lg);
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stats-value {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stats-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .stats-change {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 1rem;
        font-weight: 600;
    }

    .stats-change.positive {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
    }

    .stats-change.negative {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger-color);
    }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    .activity-timeline {
        position: relative;
        padding-left: 2rem;
    }

    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border-color);
    }

    .activity-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .activity-item::before {
        content: '';
        position: absolute;
        left: -1.75rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary-color);
        border: 3px solid white;
        box-shadow: 0 0 0 2px var(--primary-color);
    }

    .progress-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(var(--primary-color) 0deg, var(--primary-color) calc(var(--progress) * 3.6deg), var(--border-color) calc(var(--progress) * 3.6deg), var(--border-color) 360deg);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .progress-circle::before {
        content: '';
        position: absolute;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: white;
    }

    .progress-value {
        position: relative;
        z-index: 1;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Counter animation for stats
    function animateCounter(element, target, duration = 2000) {
        let start = 0;
        const increment = target / (duration / 16);
        
        function updateCounter() {
            start += increment;
            if (start < target) {
                element.textContent = Math.floor(start).toLocaleString();
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target.toLocaleString();
            }
        }
        
        updateCounter();
    }

    // Animate all counter elements
    document.querySelectorAll('.stats-value').forEach(function(element) {
        const target = parseInt(element.textContent.replace(/,/g, ''));
        if (!isNaN(target)) {
            element.textContent = '0';
            setTimeout(() => animateCounter(element, target), 500);
        }
    });

    // Initialize charts if Chart.js is available
    if (typeof Chart !== 'undefined') {
        // Sample chart for demonstration
        const chartCanvas = document.getElementById('dashboardChart');
        if (chartCanvas) {
            new Chart(chartCanvas, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Growth',
                        data: [12, 19, 3, 5, 2, 3],
                        borderColor: 'rgb(79, 70, 229)',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    }

    // Auto-refresh dashboard data every 5 minutes
    setInterval(function() {
        console.log('Auto-refreshing dashboard data...');
        // You can implement AJAX calls to refresh data here
    }, 300000);
});
</script>
@endpush 