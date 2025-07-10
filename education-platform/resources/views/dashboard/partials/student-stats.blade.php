<!-- Student Statistics Cards -->
<div class="col-lg-3 col-md-6 mb-3">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-book text-primary fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0">Subjects Interested</h6>
                    <h4 class="mb-0 text-primary">{{ $stats['subjects_interested'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3 col-md-6 mb-3">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-currency-rupee text-success fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0">Budget Range</h6>
                    <h6 class="mb-0 text-success">{{ $stats['budget_range'] ?? 'Not set' }}</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3 col-md-6 mb-3">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-display text-info fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0">Learning Mode</h6>
                    <h6 class="mb-0 text-info">{{ ucfirst($stats['learning_mode'] ?? 'Not set') }}</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3 col-md-6 mb-3">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-percent text-warning fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0">Profile Complete</h6>
                    <h4 class="mb-0 text-warning">{{ $stats['profile_completion'] ?? 0 }}%</h4>
                </div>
            </div>
        </div>
    </div>
</div> 