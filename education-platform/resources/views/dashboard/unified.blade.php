@extends('layouts.app')

@section('title', 'Dashboard - Education Platform')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1">Welcome back, {{ auth()->user()->name }}! 👋</h2>
                            <p class="mb-0 opacity-75">
                                @if(auth()->user()->isStudent())
                                    Ready to continue your learning journey?
                                @elseif(auth()->user()->isTeacher())
                                    Ready to inspire students today?
                                @elseif(auth()->user()->isInstitute())
                                    Manage your institute efficiently.
                                @elseif(auth()->user()->isAdmin())
                                    Monitor and manage the platform.
                                @else
                                    Welcome to your dashboard!
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="badge bg-light text-dark fs-6 px-3 py-2">
                                <i class="bi bi-person-badge me-1"></i>
                                {{ ucfirst(auth()->user()->role) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        @if(auth()->user()->isStudent() || auth()->user()->role === 'parent')
            <!-- Student/Parent Stats -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-book text-primary fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Active Sessions</h6>
                                <h4 class="mb-0 text-primary">3</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-person-check text-success fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Teachers Found</h6>
                                <h4 class="mb-0 text-success">12</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-clock-history text-info fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Hours Learned</h6>
                                <h4 class="mb-0 text-info">45</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-star text-warning fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Avg Rating Given</h6>
                                <h4 class="mb-0 text-warning">4.8</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->isTeacher())
            <!-- Teacher Stats -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-people text-primary fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Total Students</h6>
                                <h4 class="mb-0 text-primary">28</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-clock text-success fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Hours Taught</h6>
                                <h4 class="mb-0 text-success">156</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-currency-dollar text-info fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Monthly Earnings</h6>
                                <h4 class="mb-0 text-info">$2,340</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-star-fill text-warning fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Rating</h6>
                                <h4 class="mb-0 text-warning">4.9</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->isInstitute())
            <!-- Institute Stats -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-person-badge text-primary fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Total Teachers</h6>
                                <h4 class="mb-0 text-primary">45</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-people text-success fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Total Students</h6>
                                <h4 class="mb-0 text-success">1,245</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-book text-info fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Active Courses</h6>
                                <h4 class="mb-0 text-info">23</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-star-fill text-warning fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Rating</h6>
                                <h4 class="mb-0 text-warning">4.7</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->isAdmin())
            <!-- Admin Stats -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-people text-primary fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Total Users</h6>
                                <h4 class="mb-0 text-primary">2,847</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-person-check text-success fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Active Teachers</h6>
                                <h4 class="mb-0 text-success">189</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-building text-info fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Institutes</h6>
                                <h4 class="mb-0 text-info">67</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-exclamation-triangle text-warning fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Pending Reviews</h6>
                                <h4 class="mb-0 text-warning">15</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

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