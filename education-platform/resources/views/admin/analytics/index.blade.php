@extends('layouts.admin')

@section('title', 'Analytics & Reports')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 25px;
        color: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        margin-bottom: 20px;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .stats-card h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }
    .stats-card p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 10px 0 0 0;
    }
    .stats-card .icon {
        font-size: 3rem;
        opacity: 0.8;
    }
    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }
    .metric-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        border-left: 5px solid #667eea;
    }
    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 5px;
    }
    .metric-label {
        color: #6b7280;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .trend-indicator {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .trend-up {
        background: #dcfce7;
        color: #166534;
    }
    .trend-down {
        background: #fef2f2;
        color: #dc2626;
    }
    .export-section {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }
    .activity-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.3s ease;
    }
    .activity-item:hover {
        background-color: #f9fafb;
    }
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 18px;
    }
    .activity-content {
        flex: 1;
    }
    .activity-name {
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
    }
    .activity-details {
        color: #6b7280;
        font-size: 14px;
    }
    .top-performer-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin-bottom: 15px;
        transition: transform 0.3s ease;
    }
    .top-performer-card:hover {
        transform: translateY(-3px);
    }
    .performer-rank {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-right: 15px;
    }
    .nav-pills .nav-link {
        color: #6b7280;
        border-radius: 25px;
        padding: 10px 20px;
        margin-right: 10px;
        transition: all 0.3s ease;
    }
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Analytics & Reports</h1>
            <p class="text-muted">Comprehensive insights and analytics for your education platform</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> Export Reports
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.analytics.export', ['type' => 'overview']) }}">Overview Report</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.analytics.export', ['type' => 'teachers']) }}">Teacher Analytics</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.analytics.export', ['type' => 'institutes']) }}">Institute Analytics</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.analytics.export', ['type' => 'students']) }}">Student Analytics</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Navigation Pills -->
    <ul class="nav nav-pills mb-4" id="analyticsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab">
                <i class="fas fa-chart-line me-2"></i>Overview
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="teachers-tab" data-bs-toggle="pill" data-bs-target="#teachers" type="button" role="tab">
                <i class="fas fa-chalkboard-teacher me-2"></i>Teachers
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="institutes-tab" data-bs-toggle="pill" data-bs-target="#institutes" type="button" role="tab">
                <i class="fas fa-university me-2"></i>Institutes
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="students-tab" data-bs-toggle="pill" data-bs-target="#students" type="button" role="tab">
                <i class="fas fa-user-graduate me-2"></i>Students
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="geographic-tab" data-bs-toggle="pill" data-bs-target="#geographic" type="button" role="tab">
                <i class="fas fa-map-marker-alt me-2"></i>Geographic
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="analyticsTabContent">
        <!-- Overview Tab -->
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            <!-- Key Metrics Row -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3>{{ number_format($data['overview']['total_users']) }}</h3>
                                <p>Total Users</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3>{{ number_format($data['overview']['verified_teachers']) }}</h3>
                                <p>Verified Teachers</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3>{{ number_format($data['overview']['verified_institutes']) }}</h3>
                                <p>Verified Institutes</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-university"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3>{{ number_format($data['overview']['total_students']) }}</h3>
                                <p>Total Students</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="chart-container">
                        <h5 class="mb-3">User Growth Trend</h5>
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="chart-container">
                        <h5 class="mb-3">Role Distribution</h5>
                        <canvas id="roleDistributionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Additional Metrics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-value">{{ $data['overview']['user_activity_rate'] }}%</div>
                        <div class="metric-label">User Activity Rate</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-value">{{ $data['overview']['verification_rate'] }}%</div>
                        <div class="metric-label">Verification Rate</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-value">{{ number_format($data['overview']['total_subjects']) }}</div>
                        <div class="metric-label">Total Subjects</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-value">{{ number_format($data['overview']['total_exams']) }}</div>
                        <div class="metric-label">Total Exams</div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Top Performers -->
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5 class="mb-3">Recent Activity</h5>
                        <div id="recentActivityList">
                            @foreach($data['recent_activity']['recent_users'] as $user)
                            <div class="activity-item">
                                <div class="activity-icon bg-primary">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-name">{{ $user['name'] }}</div>
                                    <div class="activity-details">{{ $user['role'] }} from {{ $user['city'] }} - {{ $user['joined_at'] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5 class="mb-3">Top Performing Teachers</h5>
                        <div id="topTeachersList">
                            @foreach($data['top_performers']['top_teachers'] as $index => $teacher)
                            <div class="top-performer-card">
                                <div class="d-flex align-items-center">
                                    <div class="performer-rank">{{ $index + 1 }}</div>
                                    <div class="flex-grow-1">
                                        <div class="activity-name">{{ $teacher['name'] }}</div>
                                        <div class="activity-details">
                                            Rating: {{ $teacher['rating'] }} ⭐ | Students: {{ $teacher['students'] }} | Experience: {{ $teacher['experience'] }} years
                                        </div>
                                    </div>
                                    <div class="text-muted">{{ $teacher['city'] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teachers Tab -->
        <div class="tab-pane fade" id="teachers" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5 class="mb-3">Teaching Mode Distribution</h5>
                        <canvas id="teachingModeChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5 class="mb-3">Experience Level Distribution</h5>
                        <canvas id="experienceDistributionChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5 class="mb-3">Hourly Rate Distribution</h5>
                        <canvas id="hourlyRateChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5 class="mb-3">Rating Distribution</h5>
                        <canvas id="ratingDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Institutes Tab -->
        <div class="tab-pane fade" id="institutes" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5 class="mb-3">Institute Type Distribution</h5>
                        <canvas id="instituteTypeChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5 class="mb-3">Institute Rating Distribution</h5>
                        <canvas id="instituteRatingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Tab -->
        <div class="tab-pane fade" id="students" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5 class="mb-3">Gender Distribution</h5>
                        <canvas id="genderDistributionChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5 class="mb-3">Education Level Distribution</h5>
                        <canvas id="educationLevelChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Geographic Tab -->
        <div class="tab-pane fade" id="geographic" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5 class="mb-3">Top Cities</h5>
                        <canvas id="topCitiesChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5 class="mb-3">Top States</h5>
                        <canvas id="topStatesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize charts when the page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: @json($data['user_growth']->pluck('month')),
            datasets: [{
                label: 'Total Users',
                data: @json($data['user_growth']->pluck('total_users')),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Teachers',
                data: @json($data['user_growth']->pluck('teachers')),
                borderColor: '#764ba2',
                backgroundColor: 'rgba(118, 75, 162, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Institutes',
                data: @json($data['user_growth']->pluck('institutes')),
                borderColor: '#f093fb',
                backgroundColor: 'rgba(240, 147, 251, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Students',
                data: @json($data['user_growth']->pluck('students')),
                borderColor: '#4facfe',
                backgroundColor: 'rgba(79, 172, 254, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Role Distribution Chart
    const roleDistributionCtx = document.getElementById('roleDistributionChart').getContext('2d');
    new Chart(roleDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Teachers', 'Institutes', 'Students', 'Admins'],
            datasets: [{
                data: [
                    @json($data['role_distribution']['teacher']['total']),
                    @json($data['role_distribution']['institute']['total']),
                    @json($data['role_distribution']['student']['total']),
                    @json($data['role_distribution']['admin']['total'])
                ],
                backgroundColor: [
                    '#667eea',
                    '#764ba2',
                    '#f093fb',
                    '#4facfe'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Teaching Mode Chart
    const teachingModeCtx = document.getElementById('teachingModeChart');
    if (teachingModeCtx) {
        new Chart(teachingModeCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Online', 'Offline', 'Both'],
                datasets: [{
                    label: 'Teachers',
                    data: [@json($data['role_distribution']['teacher']['total'] * 0.4), @json($data['role_distribution']['teacher']['total'] * 0.3), @json($data['role_distribution']['teacher']['total'] * 0.3)],
                    backgroundColor: '#667eea'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Experience Distribution Chart
    const experienceCtx = document.getElementById('experienceDistributionChart');
    if (experienceCtx) {
        new Chart(experienceCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['0-2 years', '3-5 years', '6-10 years', '11-15 years', '15+ years'],
                datasets: [{
                    label: 'Teachers',
                    data: [20, 35, 25, 15, 5],
                    backgroundColor: '#764ba2'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Top Cities Chart
    const topCitiesCtx = document.getElementById('topCitiesChart');
    if (topCitiesCtx) {
        new Chart(topCitiesCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($data['geographic_data']['cities']->pluck('city')),
                datasets: [{
                    label: 'Users',
                    data: @json($data['geographic_data']['cities']->pluck('count')),
                    backgroundColor: '#f093fb'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Top States Chart
    const topStatesCtx = document.getElementById('topStatesChart');
    if (topStatesCtx) {
        new Chart(topStatesCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($data['geographic_data']['states']->pluck('state')),
                datasets: [{
                    label: 'Users',
                    data: @json($data['geographic_data']['states']->pluck('count')),
                    backgroundColor: '#4facfe'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}
</script>
@endsection 