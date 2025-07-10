<!-- Modern Student Statistics -->
<div class="col-lg-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-book"></i>
            </div>
            <span class="stats-change positive">
                <i class="bi bi-arrow-up"></i> +2
            </span>
        </div>
        <div class="stats-value text-primary">{{ $stats['subjects_interested'] ?? 0 }}</div>
        <div class="stats-label">Subjects Interested</div>
        <div class="progress mt-2" style="height: 4px;">
            <div class="progress-bar bg-primary" style="width: {{ min(($stats['subjects_interested'] ?? 0) * 10, 100) }}%"></div>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="stats-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-currency-rupee"></i>
            </div>
            <span class="badge bg-success bg-opacity-10 text-success">Budget</span>
        </div>
        <div class="stats-value text-success" style="font-size: 1.5rem;">
            {{ $stats['budget_range'] ?? 'Not set' }}
        </div>
        <div class="stats-label">Budget Range</div>
        <div class="progress mt-2" style="height: 4px;">
            <div class="progress-bar bg-success" style="width: 60%"></div>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="stats-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-display"></i>
            </div>
            <span class="badge bg-info bg-opacity-10 text-info">Mode</span>
        </div>
        <div class="stats-value text-info" style="font-size: 1.5rem;">
            {{ ucfirst($stats['learning_mode'] ?? 'Not set') }}
        </div>
        <div class="stats-label">Learning Mode</div>
        <div class="progress mt-2" style="height: 4px;">
            <div class="progress-bar bg-info" style="width: 70%"></div>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6 mb-4">
    <div class="stats-widget">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-percent"></i>
            </div>
            <span class="stats-change {{ ($stats['profile_completion'] ?? 0) > 50 ? 'positive' : 'negative' }}">
                <i class="bi bi-arrow-{{ ($stats['profile_completion'] ?? 0) > 50 ? 'up' : 'down' }}"></i> 
                {{ ($stats['profile_completion'] ?? 0) > 50 ? 'Good' : 'Needs Work' }}
            </span>
        </div>
        <div class="stats-value text-warning">{{ $stats['profile_completion'] ?? 0 }}%</div>
        <div class="stats-label">Profile Complete</div>
        <div class="progress mt-2" style="height: 4px;">
            <div class="progress-bar bg-warning" style="width: {{ $stats['profile_completion'] ?? 0 }}%"></div>
        </div>
    </div>
</div> 