@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@push('styles')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Welcome back! Here's what's happening with your platform.</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-value" data-stat="total_users">{{ $stats['total_users'] ?? 0 }}</div>
            <div class="stat-label">Total Users</div>
            <i class="bi bi-people stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="stat-value" data-stat="total_teachers">{{ $stats['total_teachers'] ?? 0 }}</div>
            <div class="stat-label">Active Teachers</div>
            <i class="bi bi-person-badge stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="stat-value" data-stat="total_institutes">{{ $stats['total_institutes'] ?? 0 }}</div>
            <div class="stat-label">Institutes</div>
            <i class="bi bi-building stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="stat-value" data-stat="total_leads">{{ $stats['total_leads'] ?? 0 }}</div>
            <div class="stat-label">New Leads</div>
            <i class="bi bi-graph-up stat-icon"></i>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">User Registration Trends</h5>
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="chartPeriod" id="week" checked>
                    <label class="btn btn-outline-primary" for="week">Week</label>
                    
                    <input type="radio" class="btn-check" name="chartPeriod" id="month">
                    <label class="btn btn-outline-primary" for="month">Month</label>
                    
                    <input type="radio" class="btn-check" name="chartPeriod" id="year">
                    <label class="btn btn-outline-primary" for="year">Year</label>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="userRegistrationChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">User Distribution</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="userDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lead Management & Activity -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Recent Leads</h5>
                <a href="{{ route('admin.leads.index') }}" class="btn btn-primary btn-sm">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="recent-leads-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Contact</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_leads ?? [] as $lead)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title bg-light text-dark rounded-circle">
                                                    {{ substr($lead->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $lead->name }}</h6>
                                                <small class="text-muted">{{ $lead->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark">{{ ucfirst($lead->type) }}</span></td>
                                    <td>
                                        <span class="badge bg-{{ $lead->status === 'new' ? 'warning' : ($lead->status === 'contacted' ? 'info' : 'success') }}">
                                            {{ ucfirst($lead->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $lead->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.leads.show', $lead) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button class="btn btn-outline-success btn-sm" onclick="contactLead({{ $lead->id }})">
                                                <i class="bi bi-telephone"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No recent leads
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Recent Activity</h5>
            </div>
            <div class="card-body">
                @forelse($recent_activity ?? [] as $activity)
                <div class="activity-item">
                    <div class="d-flex align-items-start">
                        <div class="activity-icon bg-primary text-white me-3">
                            <i class="bi bi-{{ $activity['icon'] }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0">{{ $activity['description'] }}</p>
                            <small class="text-muted">{{ $activity['time'] }}</small>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="bi bi-clock-history fs-1 mb-3 d-block"></i>
                    <p>No recent activity</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title">Quick Actions</h5>
            </div>
            <div class="card-body">
                <button class="quick-action-btn" onclick="location.href='{{ route('admin.users.create') }}'">
                    <i class="bi bi-person-plus me-2"></i>
                    Add New User
                </button>
                <button class="quick-action-btn" onclick="location.href='{{ route('admin.cms.pages.create') }}'">
                    <i class="bi bi-file-text me-2"></i>
                    Create Page
                </button>
                <button class="quick-action-btn" onclick="location.href='{{ route('admin.cms.blog.create') }}'">
                    <i class="bi bi-pencil-square me-2"></i>
                    Write Blog Post
                </button>
                <button class="quick-action-btn" data-action="refresh-dashboard">
                    <i class="bi bi-arrow-clockwise me-2"></i>
                    Refresh Dashboard
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Chart configuration data - making it available globally for dashboard.js
    window.chartData = {
        registration: {
            labels: {!! json_encode($chartData['registration']['labels'] ?? []) !!},
            students: {!! json_encode($chartData['registration']['students'] ?? []) !!},
            teachers: {!! json_encode($chartData['registration']['teachers'] ?? []) !!},
            institutes: {!! json_encode($chartData['registration']['institutes'] ?? []) !!}
        },
        distribution: {!! json_encode($chartData['distribution'] ?? [50, 30, 15, 5]) !!}
    };
</script>
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush 