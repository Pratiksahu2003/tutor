<!-- Institute Statistics Cards -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['total_branches'] ?? 0) }}</div>
                <div class="stat-label">Total Branches</div>
            </div>
            <div class="stat-icon bg-primary">
                <i class="bi bi-building"></i>
            </div>
        </div>
        <div class="mt-3">
            <small class="text-primary">
                <i class="bi bi-plus-circle me-1"></i>Add new branch
            </small>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['total_teachers'] ?? 0) }}</div>
                <div class="stat-label">Total Teachers</div>
            </div>
            <div class="stat-icon bg-success">
                <i class="bi bi-person-badge"></i>
            </div>
        </div>
        <div class="mt-3">
            <small class="text-success">
                <i class="bi bi-arrow-up me-1"></i>{{ $stats['total_teachers'] ?? 0 }} active
            </small>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['total_students'] ?? 0) }}</div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-icon bg-info">
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
                <div class="stat-number">₹{{ number_format($stats['monthly_revenue'] ?? 0) }}</div>
                <div class="stat-label">Monthly Revenue</div>
            </div>
            <div class="stat-icon bg-warning">
                <i class="bi bi-currency-rupee"></i>
            </div>
        </div>
        <div class="mt-3">
            <small class="text-success">
                <i class="bi bi-arrow-up me-1"></i>₹{{ number_format($stats['total_revenue'] ?? 0) }} total
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
            <div class="stat-icon bg-secondary">
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
                <div class="stat-number">{{ number_format($stats['average_rating'] ?? 0, 1) }}</div>
                <div class="stat-label">Institute Rating</div>
            </div>
            <div class="stat-icon bg-purple">
                <i class="bi bi-star"></i>
            </div>
        </div>
        <div class="mt-3">
            <small class="text-muted">
                <i class="bi bi-star-fill me-1"></i>Based on reviews
            </small>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['active_courses'] ?? 0) }}</div>
                <div class="stat-label">Active Courses</div>
            </div>
            <div class="stat-icon bg-orange">
                <i class="bi bi-book"></i>
            </div>
        </div>
        <div class="mt-3">
            <small class="text-primary">
                <i class="bi bi-plus-circle me-1"></i>Add new course
            </small>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['sessions_this_month'] ?? 0) }}</div>
                <div class="stat-label">Sessions This Month</div>
            </div>
            <div class="stat-icon bg-teal">
                <i class="bi bi-clock"></i>
            </div>
        </div>
        <div class="mt-3">
            <small class="text-success">
                <i class="bi bi-graph-up me-1"></i>{{ number_format((($stats['sessions_this_month'] ?? 0) / max($stats['total_sessions'] ?? 1, 1)) * 100, 1) }}% of total
            </small>
        </div>
    </div>
</div> 