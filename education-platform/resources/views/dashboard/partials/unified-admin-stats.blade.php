<!-- Admin Statistics Cards -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['total_users'] ?? 0) }}</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-icon bg-primary">
                <i class="bi bi-people"></i>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['total_teachers'] ?? 0) }}</div>
                <div class="stat-label">Teachers</div>
            </div>
            <div class="stat-icon bg-success">
                <i class="bi bi-person-badge"></i>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['total_institutes'] ?? 0) }}</div>
                <div class="stat-label">Institutes</div>
            </div>
            <div class="stat-icon bg-warning">
                <i class="bi bi-building"></i>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-number">{{ number_format($stats['total_students'] ?? 0) }}</div>
                <div class="stat-label">Students</div>
            </div>
            <div class="stat-icon bg-danger">
                <i class="bi bi-mortarboard"></i>
            </div>
        </div>
    </div>
</div> 