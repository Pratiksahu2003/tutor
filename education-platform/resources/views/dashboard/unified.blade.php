@extends('layouts.app')

@section('title', 'Dashboard - ' . ucfirst($user->role))

@section('content')
<div class="container-fluid py-4">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.png') }}" 
                                         alt="Profile" 
                                         class="rounded-circle border border-2 border-white" 
                                         width="60" height="60">
                                </div>
                                <div>
                                    <h2 class="mb-1">Welcome back, {{ $user->name }}! 👋</h2>
                                    <p class="mb-0 opacity-75">
                                        @if($user->role === 'student')
                                            Ready to continue your learning journey?
                                        @elseif($user->role === 'teacher')
                                            Ready to inspire students today?
                                        @elseif($user->role === 'institute')
                                            Manage your institute efficiently.
                                        @elseif($user->role === 'admin')
                                            Monitor and manage the platform.
                                        @else
                                            Welcome to your dashboard!
                                        @endif
                                    </p>
                                    @if(isset($dashboardData['needs_profile_setup']) && $dashboardData['needs_profile_setup'])
                                        <small class="text-warning">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Please complete your profile to access full features
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="badge bg-light text-dark fs-6 px-3 py-2 mb-2">
                                <i class="bi bi-person-badge me-1"></i>
                                {{ ucfirst($user->role) }}
                            </div>
                            <div class="d-block">
                                <a href="{{ route('dashboard.profile') }}" class="btn btn-light btn-sm me-2">
                                    <i class="bi bi-person-gear me-1"></i>Edit Profile
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
                <div class="alert alert-warning" role="alert">
                    <h5 class="alert-heading">
                        <i class="bi bi-info-circle me-2"></i>Complete Your Profile
                    </h5>
                    <p class="mb-3">To get the most out of our platform, please complete your {{ $user->role }} profile.</p>
                    <a href="{{ route('dashboard.profile') }}" class="btn btn-warning">
                        <i class="bi bi-person-plus me-1"></i>Complete Profile Now
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Stats -->
    @if(!isset($dashboardData['needs_profile_setup']) || !$dashboardData['needs_profile_setup'])
        <div class="row mb-4">
            @if($user->role === 'admin')
                @include('dashboard.partials.admin-stats', ['stats' => $dashboardData['stats']])
            @elseif($user->role === 'teacher')
                @include('dashboard.partials.teacher-stats', ['stats' => $dashboardData['stats']])
            @elseif($user->role === 'institute')
                @include('dashboard.partials.institute-stats', ['stats' => $dashboardData['stats']])
            @else
                @include('dashboard.partials.student-stats', ['stats' => $dashboardData['stats']])
            @endif
        </div>
    @endif

    <!-- Main Content Row -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8 mb-4">
            @if(auth()->user()->isStudent() || auth()->user()->role === 'parent')
                @include('dashboard.partials.student-content')
            @elseif(auth()->user()->isTeacher())
                @include('dashboard.partials.teacher-content')
            @elseif(auth()->user()->isInstitute())
                @include('dashboard.partials.institute-content')
            @elseif(auth()->user()->isAdmin())
                @include('dashboard.partials.admin-content')
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            @include('dashboard.partials.sidebar')
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .dashboard-card {
        transition: all 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .stat-card {
        border-left: 4px solid var(--bs-primary);
    }

    .activity-item {
        border-left: 3px solid #e9ecef;
        padding-left: 1rem;
        margin-bottom: 1rem;
        position: relative;
    }

    .activity-item::before {
        content: '';
        width: 10px;
        height: 10px;
        background: var(--bs-primary);
        border-radius: 50%;
        position: absolute;
        left: -6.5px;
        top: 0.5rem;
    }

    .progress-ring {
        width: 60px;
        height: 60px;
    }

    .quick-action-btn {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        border-color: var(--bs-primary);
    }
</style>
@endpush

@push('scripts')
<script>
    // Dashboard initialization
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auto-refresh stats every 5 minutes
        setInterval(function() {
            // You can implement AJAX calls to refresh stats here
            console.log('Auto-refreshing dashboard stats...');
        }, 300000);
    });

    // Quick action functions
    function quickAction(action, data = {}) {
        switch(action) {
            case 'find-teachers':
                window.location.href = "{{ route('search.teachers') }}";
                break;
            case 'schedule-session':
                // Implement scheduling modal or redirect
                console.log('Schedule session', data);
                break;
            case 'view-profile':
                window.location.href = "{{ route('profile.edit') }}";
                break;
            case 'contact-support':
                window.location.href = "{{ route('contact') }}";
                break;
            default:
                console.log('Unknown action:', action);
        }
    }
</script>
@endpush 