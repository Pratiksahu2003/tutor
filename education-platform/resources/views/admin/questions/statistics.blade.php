@extends('admin.layout')

@section('title', 'Question Statistics')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Questions</a></li>
    <li class="breadcrumb-item active">Statistics</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-chart-bar me-2"></i>
                    Question Statistics
                </h1>
                <p class="text-muted">Overview of question bank analytics and performance</p>
            </div>
            <div>
                <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Questions
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Overview Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card primary">
            <div class="d-flex align-items-center">
                <div class="stats-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-muted mb-1">Total Questions</h6>
                    <h3 class="mb-0">{{ $stats['total_questions'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card success">
            <div class="d-flex align-items-center">
                <div class="stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-muted mb-1">Published</h6>
                    <h3 class="mb-0">{{ $stats['published_questions'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card warning">
            <div class="d-flex align-items-center">
                <div class="stats-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-muted mb-1">Draft</h6>
                    <h3 class="mb-0">{{ $stats['draft_questions'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card danger">
            <div class="d-flex align-items-center">
                <div class="stats-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-muted mb-1">Under Review</h6>
                    <h3 class="mb-0">{{ $stats['under_review'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Question Distribution by Difficulty -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Distribution by Difficulty</h5>
            </div>
            <div class="card-body">
                @if(isset($stats['by_difficulty']) && count($stats['by_difficulty']) > 0)
                    <canvas id="difficultyChart" height="200"></canvas>
                @else
                    <p class="text-muted text-center">No data available</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Question Distribution by Type -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Distribution by Type</h5>
            </div>
            <div class="card-body">
                @if(isset($stats['by_type']) && count($stats['by_type']) > 0)
                    <canvas id="typeChart" height="200"></canvas>
                @else
                    <p class="text-muted text-center">No data available</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Questions by Subject -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Questions by Subject</h5>
            </div>
            <div class="card-body">
                @if(isset($stats['by_subject']) && count($stats['by_subject']) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Questions</th>
                                    <th>Percentage</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['by_subject'] as $subject => $count)
                                <tr>
                                    <td>{{ $subject }}</td>
                                    <td>{{ $count }}</td>
                                    <td>{{ $stats['total_questions'] > 0 ? number_format(($count / $stats['total_questions']) * 100, 1) : 0 }}%</td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $stats['total_questions'] > 0 ? ($count / $stats['total_questions']) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">No questions available by subject</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Insights</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Completion Rate</span>
                        <span class="badge bg-primary">
                            {{ $stats['total_questions'] > 0 ? number_format(($stats['published_questions'] / $stats['total_questions']) * 100, 1) : 0 }}%
                        </span>
                    </div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ $stats['total_questions'] > 0 ? ($stats['published_questions'] / $stats['total_questions']) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Review Pending</span>
                        <span class="badge bg-warning">{{ $stats['under_review'] ?? 0 }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Most Popular Type</span>
                        <span class="badge bg-info">
                            @if(isset($stats['by_type']) && count($stats['by_type']) > 0)
                                {{ ucfirst(str_replace('_', ' ', array_keys($stats['by_type']->toArray())[0])) }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Question
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Difficulty Chart
    @if(isset($stats['by_difficulty']) && count($stats['by_difficulty']) > 0)
    const difficultyCtx = document.getElementById('difficultyChart').getContext('2d');
    new Chart(difficultyCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($stats['by_difficulty']->toArray())) !!}.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
            datasets: [{
                data: {!! json_encode(array_values($stats['by_difficulty']->toArray())) !!},
                backgroundColor: [
                    '#16a34a', // Easy - Green
                    '#d97706', // Medium - Orange
                    '#dc2626'  // Hard - Red
                ]
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
    @endif

    // Type Chart
    @if(isset($stats['by_type']) && count($stats['by_type']) > 0)
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($stats['by_type']->toArray())) !!}.map(label => 
                label.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
            ),
            datasets: [{
                label: 'Questions',
                data: {!! json_encode(array_values($stats['by_type']->toArray())) !!},
                backgroundColor: '#2563eb'
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
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endpush 