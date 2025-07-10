<!-- Modern Admin Statistics -->
<div class="col-lg-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-people"></i>
            </div>
            <span class="stats-change positive">
                <i class="bi bi-arrow-up"></i> +12%
            </span>
        </div>
        <div class="stats-value text-primary">{{ number_format($stats['total_users']) }}</div>
        <div class="stats-label">Total Users</div>
        <div class="progress mt-2" style="height: 4px;">
            <div class="progress-bar bg-primary" style="width: 85%"></div>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="stats-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-person-check"></i>
            </div>
            <span class="stats-change positive">
                <i class="bi bi-arrow-up"></i> +8%
            </span>
        </div>
        <div class="stats-value text-success">{{ number_format($stats['total_teachers']) }}</div>
        <div class="stats-label">Active Teachers</div>
        <div class="progress mt-2" style="height: 4px;">
            <div class="progress-bar bg-success" style="width: 78%"></div>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="stats-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-building"></i>
            </div>
            <span class="stats-change positive">
                <i class="bi bi-arrow-up"></i> +5%
            </span>
        </div>
        <div class="stats-value text-info">{{ number_format($stats['total_institutes']) }}</div>
        <div class="stats-label">Institutes</div>
        <div class="progress mt-2" style="height: 4px;">
            <div class="progress-bar bg-info" style="width: 65%"></div>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <span class="stats-change negative">
                <i class="bi bi-arrow-down"></i> -3%
            </span>
        </div>
        <div class="stats-value text-warning">{{ number_format($stats['pending_verifications']) }}</div>
        <div class="stats-label">Pending Reviews</div>
        <div class="progress mt-2" style="height: 4px;">
            <div class="progress-bar bg-warning" style="width: 35%"></div>
        </div>
    </div>
</div> 