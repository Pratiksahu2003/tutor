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
                </div>
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
    
    <!-- Quick Stats -->
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
                        <span class="badge bg-success">{{ \App\Models\TeacherProfile::where('verified', true)->count() }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Pending Teachers</span>
                        <span class="badge bg-warning">{{ \App\Models\TeacherProfile::where('verified', false)->count() }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Verified Institutes</span>
                        <span class="badge bg-success">{{ \App\Models\Institute::where('verified', true)->count() }}</span>
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
    </div>
</div>
@endsection 