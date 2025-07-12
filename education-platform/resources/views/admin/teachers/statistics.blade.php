@extends('layouts.admin')

@section('title', 'Teacher Statistics')

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
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Teacher Statistics</h1>
            <p class="text-muted">Comprehensive analytics and insights for teacher management</p>
        </div>
        <div>
            <button class="btn btn-primary" onclick="exportData()">
                <i class="fas fa-download"></i> Export Report
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
                        <option value="">All Modes</option>
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
                        <option value="">All Experience</option>
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
                <button class="btn-apply-filters" onclick="applyFilters()">
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

    <!-- Statistics Cards -->
    <div class="row" id="statsCards">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <h3 id="totalTeachers">0</h3>
                <p>Total Teachers</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <h3 id="activeTeachers">0</h3>
                <p>Active Teachers</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <h3 id="verifiedTeachers">0</h3>
                <p>Verified Teachers</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <h3 id="avgRating">0.0</h3>
                <p>Average Rating</p>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="chart-container">
                <h5 class="mb-4">Monthly Registration Trend</h5>
                <canvas id="monthlyTrendChart" height="100"></canvas>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="chart-container">
                <h5 class="mb-4">Teaching Mode Distribution</h5>
                <canvas id="teachingModeChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="chart-container">
                <h5 class="mb-4">City-wise Distribution</h5>
                <canvas id="cityDistributionChart" height="100"></canvas>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="chart-container">
                <h5 class="mb-4">Experience Level Distribution</h5>
                <canvas id="experienceChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Row 3 -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="chart-container">
                <h5 class="mb-4">Hourly Rate Distribution</h5>
                <canvas id="rateChart" height="100"></canvas>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="chart-container">
                <h5 class="mb-4">Rating Distribution</h5>
                <canvas id="ratingChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <h3 id="avgExperience">0</h3>
                <p>Avg Experience (Years)</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <h3 id="avgHourlyRate">₹0</h3>
                <p>Avg Hourly Rate</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <h3 id="totalStudents">0</h3>
                <p>Total Students</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <h3 id="onlineTeachers">0</h3>
                <p>Online Teachers</p>
            </div>
        </div>
    </div>

    <!-- Top Teachers and Recent Activity -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="chart-container">
                <h5 class="mb-4">Top Performing Teachers</h5>
                <div id="topTeachersList">
                    <!-- Top teachers will be loaded here -->
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="chart-container">
                <h5 class="mb-4">Recent Activity</h5>
                <div id="recentActivityList">
                    <!-- Recent activity will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script>
let charts = {};
let currentFilters = {};

// Initialize date range picker
$(document).ready(function() {
    $('#dateRange').daterangepicker({
        startDate: moment().subtract(30, 'days'),
        endDate: moment(),
        ranges: {
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
           'Last 3 Months': [moment().subtract(3, 'months'), moment()],
           'Last 6 Months': [moment().subtract(6, 'months'), moment()],
           'Last Year': [moment().subtract(1, 'year'), moment()]
        }
    });

    // Load initial data
    loadStatistics();
});

function loadStatistics() {
    showLoading();
    
    // Build query parameters
    const params = new URLSearchParams();
    if (currentFilters.date_range) params.append('date_range', currentFilters.date_range);
    if (currentFilters.city) params.append('city', currentFilters.city);
    if (currentFilters.verified) params.append('verified', currentFilters.verified);
    if (currentFilters.teaching_mode) params.append('teaching_mode', currentFilters.teaching_mode);
    if (currentFilters.experience_range) params.append('experience_range', currentFilters.experience_range);

    fetch(`/admin/teachers/statistics/data?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            updateStatistics(data);
            hideLoading();
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
            hideLoading();
        });
}

function updateStatistics(data) {
    // Update stats cards
    document.getElementById('totalTeachers').textContent = data.stats.total_teachers;
    document.getElementById('activeTeachers').textContent = data.stats.active_teachers;
    document.getElementById('verifiedTeachers').textContent = data.stats.verified_teachers;
    document.getElementById('avgRating').textContent = data.stats.avg_rating.toFixed(1);
    document.getElementById('avgExperience').textContent = data.stats.avg_experience.toFixed(1);
    document.getElementById('avgHourlyRate').textContent = '₹' + Math.round(data.stats.avg_hourly_rate);
    document.getElementById('totalStudents').textContent = data.stats.total_students;
    document.getElementById('onlineTeachers').textContent = data.stats.online_teachers;

    // Update charts
    updateMonthlyTrendChart(data.monthly_trend);
    updateTeachingModeChart(data.stats);
    updateCityDistributionChart(data.city_distribution);
    updateExperienceChart(data.experience_distribution);
    updateRateChart(data.rate_distribution);
    updateRatingChart(data.rating_distribution);

    // Update lists
    updateTopTeachersList(data.top_teachers);
    updateRecentActivityList(data.recent_activity);

    // Update city filter options
    updateCityFilter(data.city_distribution);
}

function updateMonthlyTrendChart(data) {
    const ctx = document.getElementById('monthlyTrendChart').getContext('2d');
    
    if (charts.monthlyTrend) {
        charts.monthlyTrend.destroy();
    }

    charts.monthlyTrend = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(item => item.month),
            datasets: [{
                label: 'New Registrations',
                data: data.map(item => item.count),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
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

function updateTeachingModeChart(data) {
    const ctx = document.getElementById('teachingModeChart').getContext('2d');
    
    if (charts.teachingMode) {
        charts.teachingMode.destroy();
    }

    charts.teachingMode = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Online', 'Offline', 'Both'],
            datasets: [{
                data: [data.online_teachers, data.offline_teachers, data.both_mode_teachers],
                backgroundColor: ['#667eea', '#764ba2', '#f093fb'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function updateCityDistributionChart(data) {
    const ctx = document.getElementById('cityDistributionChart').getContext('2d');
    
    if (charts.cityDistribution) {
        charts.cityDistribution.destroy();
    }

    const labels = Object.keys(data);
    const values = Object.values(data);

    charts.cityDistribution = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Teachers',
                data: values,
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderColor: '#667eea',
                borderWidth: 1
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

function updateExperienceChart(data) {
    const ctx = document.getElementById('experienceChart').getContext('2d');
    
    if (charts.experience) {
        charts.experience.destroy();
    }

    const labels = Object.keys(data);
    const values = Object.values(data);

    charts.experience = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Teachers',
                data: values,
                backgroundColor: 'rgba(118, 75, 162, 0.8)',
                borderColor: '#764ba2',
                borderWidth: 1
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

function updateRateChart(data) {
    const ctx = document.getElementById('rateChart').getContext('2d');
    
    if (charts.rate) {
        charts.rate.destroy();
    }

    const labels = Object.keys(data);
    const values = Object.values(data);

    charts.rate = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Teachers',
                data: values,
                backgroundColor: 'rgba(240, 147, 251, 0.8)',
                borderColor: '#f093fb',
                borderWidth: 1
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

function updateRatingChart(data) {
    const ctx = document.getElementById('ratingChart').getContext('2d');
    
    if (charts.rating) {
        charts.rating.destroy();
    }

    const labels = Object.keys(data);
    const values = Object.values(data);

    charts.rating = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Teachers',
                data: values,
                backgroundColor: 'rgba(255, 193, 7, 0.8)',
                borderColor: '#ffc107',
                borderWidth: 1
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

function updateTopTeachersList(teachers) {
    const container = document.getElementById('topTeachersList');
    container.innerHTML = '';

    teachers.forEach((teacher, index) => {
        const card = document.createElement('div');
        card.className = 'top-teacher-card d-flex align-items-center';
        card.innerHTML = `
            <div class="teacher-rank">${index + 1}</div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-1">${teacher.name}</h6>
                    <span class="badge bg-primary">${teacher.rating.toFixed(1)} ⭐</span>
                </div>
                <div class="text-muted small">
                    ${teacher.students} students • ${teacher.experience} years exp • ${teacher.city}
                </div>
            </div>
        `;
        container.appendChild(card);
    });
}

function updateRecentActivityList(activities) {
    const container = document.getElementById('recentActivityList');
    container.innerHTML = '';

    activities.forEach(activity => {
        const item = document.createElement('div');
        item.className = 'activity-item';
        item.innerHTML = `
            <div class="activity-icon ${activity.verified ? 'bg-success' : 'bg-warning'}">
                <i class="fas ${activity.verified ? 'fa-check' : 'fa-clock'}"></i>
            </div>
            <div class="activity-content">
                <div class="activity-name">${activity.name}</div>
                <div class="activity-details">
                    ${activity.action} • ${activity.date}
                </div>
            </div>
        `;
        container.appendChild(item);
    });
}

function updateCityFilter(cityData) {
    const select = document.getElementById('cityFilter');
    const currentValue = select.value;
    
    // Clear existing options except "All Cities"
    select.innerHTML = '<option value="">All Cities</option>';
    
    // Add city options
    Object.keys(cityData).forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        option.textContent = `${city} (${cityData[city]})`;
        select.appendChild(option);
    });
    
    // Restore selected value if it still exists
    if (currentValue) {
        select.value = currentValue;
    }
}

function applyFilters() {
    currentFilters = {
        date_range: $('#dateRange').val(),
        city: $('#cityFilter').val(),
        verified: $('#verifiedFilter').val(),
        teaching_mode: $('#teachingModeFilter').val(),
        experience_range: $('#experienceFilter').val()
    };
    
    loadStatistics();
}

function resetFilters() {
    $('#dateRange').data('daterangepicker').setStartDate(moment().subtract(30, 'days'));
    $('#dateRange').data('daterangepicker').setEndDate(moment());
    $('#cityFilter').val('');
    $('#verifiedFilter').val('');
    $('#teachingModeFilter').val('');
    $('#experienceFilter').val('');
    
    currentFilters = {};
    loadStatistics();
}

function showLoading() {
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('statsCards').style.opacity = '0.5';
}

function hideLoading() {
    document.getElementById('loadingSpinner').style.display = 'none';
    document.getElementById('statsCards').style.opacity = '1';
}

function exportData() {
    // Implementation for exporting data
    alert('Export functionality will be implemented here');
}
</script>
@endsection 