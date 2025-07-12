@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="page-title">
            <i class="fas fa-tachometer-alt me-2"></i>
            Admin Dashboard
        </h1>
        <p class="text-muted mb-4">Welcome to the comprehensive education platform management panel.</p>
    </div>
</div>

<!-- System Health Alert -->
@if(isset($systemHealth) && $systemHealth['database_status']['status'] === 'error')
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>System Alert:</strong> Database connection issues detected. Please check system health.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
@endif

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card primary">
            <div class="d-flex align-items-center">
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-muted mb-1">Total Users</h6>
                    <h3 class="mb-0">{{ \App\Models\User::count() }}</h3>
                    @if(isset($userGrowthTrends) && count($userGrowthTrends) > 0)
                        <small class="text-success">
                            <i class="fas fa-arrow-up"></i> 
                            +{{ $userGrowthTrends[count($userGrowthTrends)-1]['new_users'] }} today
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card success">
            <div class="d-flex align-items-center">
                <div class="stats-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-muted mb-1">Teachers</h6>
                    <h3 class="mb-0">{{ \App\Models\User::where('role', 'teacher')->count() }}</h3>
                    @if(isset($userGrowthTrends) && count($userGrowthTrends) > 0)
                        <small class="text-success">
                            <i class="fas fa-arrow-up"></i> 
                            +{{ $userGrowthTrends[count($userGrowthTrends)-1]['new_teachers'] }} today
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card warning">
            <div class="d-flex align-items-center">
                <div class="stats-icon">
                    <i class="fas fa-university"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-muted mb-1">Institutes</h6>
                    <h3 class="mb-0">{{ \App\Models\Institute::count() }}</h3>
                    @if(isset($userGrowthTrends) && count($userGrowthTrends) > 0)
                        <small class="text-success">
                            <i class="fas fa-arrow-up"></i> 
                            +{{ $userGrowthTrends[count($userGrowthTrends)-1]['new_institutes'] }} today
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card danger">
            <div class="d-flex align-items-center">
                <div class="stats-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-muted mb-1">Students</h6>
                    <h3 class="mb-0">{{ \App\Models\User::where('role', 'student')->count() }}</h3>
                    @if(isset($performanceMetrics) && isset($performanceMetrics['concurrent_users']))
                        <small class="text-info">
                            <i class="fas fa-users"></i> 
                            {{ $performanceMetrics['concurrent_users'] }} active
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Health & Performance Row -->
<div class="row mb-4">
    <!-- System Health -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-heartbeat me-2"></i>
                    System Health
                </h5>
            </div>
            <div class="card-body">
                @if(isset($systemHealth))
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Database</span>
                            <span class="badge bg-{{ $systemHealth['database_status']['status'] === 'healthy' ? 'success' : 'danger' }}">
                                {{ ucfirst($systemHealth['database_status']['status']) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Memory Usage</span>
                            <span>{{ $systemHealth['memory_usage']['formatted_used'] }} / {{ $systemHealth['memory_usage']['formatted_limit'] }}</span>
                        </div>
                        <div class="progress mt-1" style="height: 5px;">
                            <div class="progress-bar bg-{{ $systemHealth['memory_usage']['percentage'] > 80 ? 'danger' : ($systemHealth['memory_usage']['percentage'] > 60 ? 'warning' : 'success') }}" 
                                 style="width: {{ $systemHealth['memory_usage']['percentage'] }}%"></div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Disk Space</span>
                            <span>{{ $systemHealth['disk_space']['formatted_used'] }} / {{ $systemHealth['disk_space']['formatted_total'] }}</span>
                        </div>
                        <div class="progress mt-1" style="height: 5px;">
                            <div class="progress-bar bg-{{ $systemHealth['disk_space']['percentage'] > 80 ? 'danger' : ($systemHealth['disk_space']['percentage'] > 60 ? 'warning' : 'success') }}" 
                                 style="width: {{ $systemHealth['disk_space']['percentage'] }}%"></div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Uptime</span>
                            <span>{{ $systemHealth['uptime']['formatted'] }}</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Error Count</span>
                            <span class="badge bg-{{ $systemHealth['error_count'] > 10 ? 'danger' : ($systemHealth['error_count'] > 5 ? 'warning' : 'success') }}">
                                {{ $systemHealth['error_count'] }}
                            </span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Cache Status</span>
                            <span class="badge bg-success">{{ ucfirst($systemHealth['cache_status']['status']) }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Performance Metrics
                </h5>
            </div>
            <div class="card-body">
                @if(isset($performanceMetrics))
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Response Time</span>
                            <span>{{ $performanceMetrics['response_time'] }}ms</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Error Rate</span>
                            <span class="badge bg-{{ $performanceMetrics['error_rate']['error_rate'] > 5 ? 'danger' : ($performanceMetrics['error_rate']['error_rate'] > 2 ? 'warning' : 'success') }}">
                                {{ number_format($performanceMetrics['error_rate']['error_rate'], 2) }}%
                            </span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Active Sessions</span>
                            <span>{{ $performanceMetrics['active_sessions'] }}</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Concurrent Users</span>
                            <span>{{ $performanceMetrics['concurrent_users'] }}</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Avg Page Load</span>
                            <span>{{ $performanceMetrics['page_load_time']['average'] }}ms</span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Throughput</span>
                            <span>{{ $performanceMetrics['throughput']['requests_per_minute'] }}/min</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Platform Statistics Row -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Platform Statistics
                </h5>
            </div>
            <div class="card-body">
                @if(isset($platformStats))
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <h4 class="text-primary">{{ $platformStats['total_subjects'] }}</h4>
                            <small class="text-muted">Subjects</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <h4 class="text-success">{{ $platformStats['total_exams'] }}</h4>
                            <small class="text-muted">Exams</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <h4 class="text-warning">{{ $platformStats['total_questions'] }}</h4>
                            <small class="text-muted">Questions</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <h4 class="text-info">{{ $platformStats['total_pages'] }}</h4>
                            <small class="text-muted">Pages</small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <h5 class="text-success">{{ number_format($platformStats['verification_rate']['overall'], 1) }}%</h5>
                            <small class="text-muted">Verification Rate</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <h5 class="text-primary">{{ number_format($platformStats['engagement_score']['score'], 1) }}%</h5>
                            <small class="text-muted">Engagement Score</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <h5 class="text-warning">{{ number_format($platformStats['platform_rating']['overall'], 1) }}/5.0</h5>
                            <small class="text-muted">Platform Rating</small>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row">
    <!-- Recent Users -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    Recent Registrations
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\User::latest()->take(10)->get() as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <span class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 12px;">
                                                {{ substr($user->name, 0, 2) }}
                                            </span>
                                        </div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'teacher' ? 'info' : ($user->role === 'institute' ? 'warning' : 'success')) }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats & Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Quick Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Verified Teachers</span>
                        <span class="badge bg-success">{{ \App\Models\TeacherProfile::where('verification_status', 'verified')->count() }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Pending Teachers</span>
                        <span class="badge bg-warning">{{ \App\Models\TeacherProfile::where('verification_status', 'unverified')->count() }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Verified Institutes</span>
                        <span class="badge bg-success">{{ \App\Models\Institute::where('verification_status', 'verified')->count() }}</span>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span>Pending Institutes</span>
                        <span class="badge bg-warning">{{ \App\Models\Institute::where('verified', false)->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-user-plus me-2"></i>Add New User
                    </a>
                    <a href="{{ route('admin.teachers.index', ['verified' => 'unverified']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-check-circle me-2"></i>Verify Teachers
                    </a>
                    <a href="{{ route('admin.institutes.index', ['verified' => 'unverified']) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-building-check me-2"></i>Verify Institutes
                    </a>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-user-shield me-2"></i>Manage Roles
                    </a>
                </div>
            </div>
        </div>

        <!-- Comprehensive Reports -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Comprehensive Reports
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.analytics.index') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-chart-bar me-2"></i>Analytics Dashboard
                    </a>
                    <a href="{{ route('admin.reports.overview') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-file-alt me-2"></i>Overview Report
                    </a>
                    <a href="{{ route('admin.reports.user-analytics') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-users me-2"></i>User Analytics
                    </a>
                    <a href="{{ route('admin.reports.teacher-performance') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Teacher Performance
                    </a>
                    <a href="{{ route('admin.reports.institute-analytics') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-university me-2"></i>Institute Analytics
                    </a>
                    <a href="{{ route('admin.reports.revenue-analysis') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-dollar-sign me-2"></i>Revenue Analysis
                    </a>
                    <a href="{{ route('admin.reports.system-health') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-heartbeat me-2"></i>System Health
                    </a>
                </div>
            </div>
        </div>

        <!-- System Tools -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>
                    System Tools
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-cog me-2"></i>System Settings
                    </a>
                    <a href="{{ route('admin.settings.backup') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-download me-2"></i>Database Backup
                    </a>
                    <a href="{{ route('admin.settings.system-info') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-info-circle me-2"></i>System Info
                    </a>
                    <button class="btn btn-success btn-sm" onclick="clearCache()">
                        <i class="fas fa-broom me-2"></i>Clear Cache
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Growth Chart -->
@if(isset($userGrowthTrends) && count($userGrowthTrends) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-area me-2"></i>
                    User Growth (Last 30 Days)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="userGrowthChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
@if(isset($userGrowthTrends) && count($userGrowthTrends) > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('userGrowthChart').getContext('2d');
    const chartData = @json($userGrowthTrends);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(item => item.date),
            datasets: [{
                label: 'Total Users',
                data: chartData.map(item => item.total_users),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }, {
                label: 'New Users',
                data: chartData.map(item => item.new_users),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endif

<script>
function clearCache() {
    if (confirm('Are you sure you want to clear all cache?')) {
        fetch('{{ route("admin.settings.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cache cleared successfully!');
                location.reload();
            } else {
                alert('Error clearing cache: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error clearing cache');
        });
    }
}
</script>
@endsection 