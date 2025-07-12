<!-- Teacher Statistics Cards -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['total_students'] ?? 0) }}</div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-icon bg-primary">
                <i class="bi bi-people"></i>
            </div>
        </div>
        <div class="mt-3">
            <small class="text-success">
                <i class="bi bi-arrow-up me-1"></i>{{ $stats['new_students_month'] ?? 0 }} this month
            </small>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['total_sessions'] ?? 0) }}</div>
                <div class="stat-label">Total Sessions</div>
            </div>
            <div class="stat-icon bg-success">
                <i class="bi bi-calendar-check"></i>
            </div>
        </div>
        <div class="mt-3">
            <small class="text-success">
                <i class="bi bi-arrow-up me-1"></i>{{ $stats['sessions_this_month'] ?? 0 }} this month
            </small>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">₹{{ number_format($stats['earnings_this_month'] ?? 0) }}</div>
                <div class="stat-label">Monthly Earnings</div>
            </div>
            <div class="stat-icon bg-warning">
                <i class="bi bi-currency-rupee"></i>
            </div>
        </div>
        <div class="mt-3">
            <small class="text-success">
                <i class="bi bi-arrow-up me-1"></i>₹{{ number_format($stats['total_earnings'] ?? 0) }} total
            </small>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['average_rating'] ?? 0, 1) }}</div>
                <div class="stat-label">Average Rating</div>
            </div>
            <div class="stat-icon bg-info">
                <i class="bi bi-star"></i>
            </div>
        </div>
        <div class="mt-3">
            <small class="text-muted">
                <i class="bi bi-star-fill me-1"></i>{{ $stats['completed_sessions'] ?? 0 }} reviews
            </small>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['subjects_taught'] ?? 0) }}</div>
                <div class="stat-label">Subjects Taught</div>
            </div>
            <div class="stat-icon bg-secondary">
                <i class="bi bi-book"></i>
            </div>
        </div>
        <div class="mt-3">
            <small class="text-primary">
                <i class="bi bi-plus-circle me-1"></i>Add more subjects
            </small>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['institutes_associated'] ?? 0) }}</div>
                <div class="stat-label">Associated Institutes</div>
            </div>
            <div class="stat-icon bg-purple">
                <i class="bi bi-building"></i>
            </div>
        </div>
        <div class="mt-3">
            <small class="text-primary">
                <i class="bi bi-plus-circle me-1"></i>Join more institutes
            </small>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['upcoming_sessions'] ?? 0) }}</div>
                <div class="stat-label">Upcoming Sessions</div>
            </div>
            <div class="stat-icon bg-orange">
                <i class="bi bi-clock"></i>
            </div>
        </div>
        <div class="mt-3">
            <small class="text-warning">
                <i class="bi bi-calendar me-1"></i>Next 7 days
            </small>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['completed_sessions'] ?? 0) }}</div>
                <div class="stat-label">Completed Sessions</div>
            </div>
            <div class="stat-icon bg-success">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
        <div class="mt-3">
            <small class="text-success">
                <i class="bi bi-graph-up me-1"></i>{{ number_format((($stats['completed_sessions'] ?? 0) / max($stats['total_sessions'] ?? 1, 1)) * 100, 1) }}% completion
            </small>
        </div>
    </div>
</div> 