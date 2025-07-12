@extends('layouts.admin')

@section('title', 'Teacher Statistics Report')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css">
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
    .filters-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }
    .filter-group {
        margin-bottom: 20px;
    }
    .filter-group label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }
    .filter-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    .filter-control:focus {
        border-color: #667eea;
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .btn-apply-filters {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    .btn-apply-filters:hover {
        transform: translateY(-2px);
    }
    .btn-reset-filters {
        background: #6b7280;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        margin-left: 10px;
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
    .top-teacher-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin-bottom: 15px;
        transition: transform 0.3s ease;
    }
    .top-teacher-card:hover {
        transform: translateY(-3px);
    }
    .teacher-rank {
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
    .loading-spinner {
        display: none;
        text-align: center;
        padding: 50px;
    }
    .spinner {
        border: 4px solid #f3f4f6;
        border-top: 4px solid #667eea;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Teacher Statistics Report</h1>
            <p class="text-muted">Comprehensive analytics and insights for teacher management</p>
        </div>
        <div>
            <button class="btn btn-primary" onclick="exportReport()">
                <i class="fas fa-download"></i> Export Report
            </button>
            <button class="btn btn-outline-secondary ms-2" onclick="printReport()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <h4 class="mb-4">Filters</h4>
        <div class="row">
            <div class="col-md-3">
                <div class="filter-group">
                    <label>Date Range</label>
                    <input type="text" id="dateRange" class="filter-control" placeholder="Select date range">
                </div>
            </div>
            <div class="col-md-3">
                <div class="filter-group">
                    <label>City</label>
                    <select id="cityFilter" class="filter-control">
                        <option value="">All Cities</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="filter-group">
                    <label>Verification Status</label>
                    <select id="verifiedFilter" class="filter-control">
                        <option value="">All</option>
                        <option value="true">Verified</option>
                        <option value="false">Unverified</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="filter-group">
                    <label>Teaching Mode</label>
                    <select id="teachingModeFilter" class="filter-control">
                        <option value="">All</option>
                        <option value="online">Online</option>
                        <option value="offline">Offline</option>
                        <option value="both">Both</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="filter-group">
                    <label>Experience Range</label>
                    <select id="experienceFilter" class="filter-control">
                        <option value="">All</option>
                        <option value="0-2">0-2 years</option>
                        <option value="3-5">3-5 years</option>
                        <option value="6-10">6-10 years</option>
                        <option value="11-15">11-15 years</option>
                        <option value="15-50">15+ years</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <button class="btn-apply-filters" onclick="loadStatistics()">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                <button class="btn-reset-filters" onclick="resetFilters()">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loadingSpinner">
        <div class="spinner"></div>
        <p class="mt-3">Loading statistics...</p>
    </div>

    <!-- Statistics Content -->
    <div id="statisticsContent">
        <!-- Key Metrics Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 id="totalTeachers">-</h3>
                            <p>Total Teachers</p>
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
                            <h3 id="activeTeachers">-</h3>
                            <p>Active Teachers</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 id="verifiedTeachers">-</h3>
                            <p>Verified Teachers</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 id="avgRating">-</h3>
                            <p>Avg Rating</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="mb-3">Monthly Registration Trend</h5>
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="mb-3">City Distribution</h5>
                    <canvas id="cityDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="mb-3">Experience Level Distribution</h5>
                    <canvas id="experienceDistributionChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="mb-3">Hourly Rate Distribution</h5>
                    <canvas id="rateDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Additional Metrics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="metric-card">
                    <div class="metric-value" id="avgExperience">-</div>
                    <div class="metric-label">Avg Experience (Years)</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <div class="metric-value" id="avgHourlyRate">-</div>
                    <div class="metric-label">Avg Hourly Rate (₹)</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <div class="metric-value" id="totalStudents">-</div>
                    <div class="metric-label">Total Students</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <div class="metric-value" id="onlineTeachers">-</div>
                    <div class="metric-label">Online Teachers</div>
                </div>
            </div>
        </div>

        <!-- Top Teachers and Recent Activity -->
        <div class="row">
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="mb-3">Top Performing Teachers</h5>
                    <div id="topTeachersList">
                        <!-- Top teachers will be loaded here -->
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="mb-3">Recent Activity</h5>
                    <div id="recentActivityList">
                        <!-- Recent activity will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
let charts = {};

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    initializeDateRangePicker();
    loadStatistics();
});

function initializeDateRangePicker() {
    $('#dateRange').daterangepicker({
        startDate: moment().subtract(30, 'days'),
        endDate: moment(),
        ranges: {
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });
}

function loadStatistics() {
    showLoading();
    
    const filters = {
        date_range: $('#dateRange').val(),
        city: $('#cityFilter').val(),
        verified: $('#verifiedFilter').val(),
        teaching_mode: $('#teachingModeFilter').val(),
        experience_range: $('#experienceFilter').val()
    };

    fetch(`/admin/teachers/statistics/data?${new URLSearchParams(filters)}`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            updateStatistics(data);
        })
        .catch(error => {
            hideLoading();
            console.error('Error loading statistics:', error);
            alert('Error loading statistics. Please try again.');
        });
}

function updateStatistics(data) {
    // Update key metrics
    document.getElementById('totalTeachers').textContent = data.stats.total_teachers;
    document.getElementById('activeTeachers').textContent = data.stats.active_teachers;
    document.getElementById('verifiedTeachers').textContent = data.stats.verified_teachers;
    document.getElementById('avgRating').textContent = data.stats.avg_rating.toFixed(1);
    document.getElementById('avgExperience').textContent = data.stats.avg_experience.toFixed(1);
    document.getElementById('avgHourlyRate').textContent = '₹' + data.stats.avg_hourly_rate.toFixed(0);
    document.getElementById('totalStudents').textContent = data.stats.total_students;
    document.getElementById('onlineTeachers').textContent = data.stats.online_teachers;

    // Update charts
    updateMonthlyTrendChart(data.monthly_trend);
    updateCityDistributionChart(data.city_distribution);
    updateExperienceDistributionChart(data.experience_distribution);
    updateRateDistributionChart(data.rate_distribution);

    // Update lists
    updateTopTeachersList(data.top_teachers);
    updateRecentActivityList(data.recent_activity);
}

function updateMonthlyTrendChart(monthlyTrend) {
    const ctx = document.getElementById('monthlyTrendChart').getContext('2d');
    
    if (charts.monthlyTrend) {
        charts.monthlyTrend.destroy();
    }

    charts.monthlyTrend = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyTrend.map(item => item.month),
            datasets: [{
                label: 'New Registrations',
                data: monthlyTrend.map(item => item.count),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
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

function updateCityDistributionChart(cityDistribution) {
    const ctx = document.getElementById('cityDistributionChart').getContext('2d');
    
    if (charts.cityDistribution) {
        charts.cityDistribution.destroy();
    }

    const cities = Object.keys(cityDistribution);
    const counts = Object.values(cityDistribution);

    charts.cityDistribution = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: cities,
            datasets: [{
                data: counts,
                backgroundColor: [
                    '#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe',
                    '#00f2fe', '#43e97b', '#38f9d7', '#fa709a', '#fee140'
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
}

function updateExperienceDistributionChart(experienceDistribution) {
    const ctx = document.getElementById('experienceDistributionChart').getContext('2d');
    
    if (charts.experienceDistribution) {
        charts.experienceDistribution.destroy();
    }

    const labels = Object.keys(experienceDistribution);
    const data = Object.values(experienceDistribution);

    charts.experienceDistribution = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Teachers',
                data: data,
                backgroundColor: '#667eea',
                borderRadius: 5
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

function updateRateDistributionChart(rateDistribution) {
    const ctx = document.getElementById('rateDistributionChart').getContext('2d');
    
    if (charts.rateDistribution) {
        charts.rateDistribution.destroy();
    }

    const labels = Object.keys(rateDistribution);
    const data = Object.values(rateDistribution);

    charts.rateDistribution = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Teachers',
                data: data,
                backgroundColor: '#764ba2',
                borderRadius: 5
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

function updateTopTeachersList(topTeachers) {
    const container = document.getElementById('topTeachersList');
    container.innerHTML = '';

    topTeachers.forEach((teacher, index) => {
        const card = document.createElement('div');
        card.className = 'top-teacher-card';
        card.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="teacher-rank">${index + 1}</div>
                <div class="flex-grow-1">
                    <div class="activity-name">${teacher.name}</div>
                    <div class="activity-details">
                        Rating: ${teacher.rating} ⭐ | Students: ${teacher.students} | Experience: ${teacher.experience} years
                    </div>
                </div>
                <div class="text-muted">${teacher.city}</div>
            </div>
        `;
        container.appendChild(card);
    });
}

function updateRecentActivityList(recentActivity) {
    const container = document.getElementById('recentActivityList');
    container.innerHTML = '';

    recentActivity.forEach(activity => {
        const item = document.createElement('div');
        item.className = 'activity-item';
        item.innerHTML = `
            <div class="activity-icon ${activity.verified ? 'bg-success' : 'bg-warning'}">
                <i class="fas ${activity.verified ? 'fa-check' : 'fa-clock'}"></i>
            </div>
            <div class="activity-content">
                <div class="activity-name">${activity.name}</div>
                <div class="activity-details">${activity.action} on ${activity.date}</div>
            </div>
        `;
        container.appendChild(item);
    });
}

function resetFilters() {
    $('#dateRange').data('daterangepicker').setStartDate(moment().subtract(30, 'days'));
    $('#dateRange').data('daterangepicker').setEndDate(moment());
    $('#cityFilter').val('');
    $('#verifiedFilter').val('');
    $('#teachingModeFilter').val('');
    $('#experienceFilter').val('');
    loadStatistics();
}

function showLoading() {
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('statisticsContent').style.display = 'none';
}

function hideLoading() {
    document.getElementById('loadingSpinner').style.display = 'none';
    document.getElementById('statisticsContent').style.display = 'block';
}

function exportReport() {
    const filters = {
        date_range: $('#dateRange').val(),
        city: $('#cityFilter').val(),
        verified: $('#verifiedFilter').val(),
        teaching_mode: $('#teachingModeFilter').val(),
        experience_range: $('#experienceFilter').val()
    };

    const url = `/admin/teachers/statistics/data?${new URLSearchParams(filters)}&export=true`;
    window.open(url, '_blank');
}

function printReport() {
    window.print();
}
</script>
@endsection 