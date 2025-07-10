<!-- Modern Institute Statistics -->
<div class="col-lg-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-person-badge"></i>
            </div>
            <span class="stats-change positive">
                <i class="bi bi-arrow-up"></i> +4%
            </span>
        </div>
        <div class="stats-value text-primary">{{ number_format($stats['total_teachers'] ?? 0) }}</div>
        <div class="stats-label">Total Teachers</div>
        <div class="progress mt-2" style="height: 4px;">
            <div class="progress-bar bg-primary" style="width: 75%"></div>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="stats-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-people"></i>
            </div>
            <span class="stats-change positive">
                <i class="bi bi-arrow-up"></i> +18%
            </span>
        </div>
        <div class="stats-value text-success">{{ number_format($stats['total_students'] ?? 0) }}</div>
        <div class="stats-label">Total Students</div>
        <div class="progress mt-2" style="height: 4px;">
            <div class="progress-bar bg-success" style="width: 88%"></div>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="stats-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-patch-check"></i>
            </div>
            <span class="stats-change positive">
                <i class="bi bi-arrow-up"></i> +2
            </span>
        </div>
        <div class="stats-value text-info">{{ number_format($stats['verified_teachers'] ?? 0) }}</div>
        <div class="stats-label">Verified Teachers</div>
        <div class="progress mt-2" style="height: 4px;">
            <div class="progress-bar bg-info" style="width: 80%"></div>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-star-fill"></i>
            </div>
            <span class="stats-change positive">
                <i class="bi bi-arrow-up"></i> +0.1
            </span>
        </div>
        <div class="stats-value text-warning">{{ number_format($stats['rating'] ?? 0, 1) }}</div>
        <div class="stats-label">Institute Rating</div>
        <div class="progress mt-2" style="height: 4px;">
            <div class="progress-bar bg-warning" style="width: {{ ($stats['rating'] ?? 0) * 20 }}%"></div>
        </div>
    </div>
</div> 