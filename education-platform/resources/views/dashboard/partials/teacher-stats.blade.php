<!-- Teacher Statistics Cards -->
<div class="col-lg-3 col-md-6 mb-3">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-people text-primary fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0">Total Students</h6>
                    <h4 class="mb-0 text-primary">{{ number_format($stats['total_students'] ?? 0) }}</h4>
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
                        <i class="bi bi-clock text-success fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0">Sessions This Month</h6>
                    <h4 class="mb-0 text-success">{{ number_format($stats['sessions_this_month'] ?? 0) }}</h4>
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
                        <i class="bi bi-currency-rupee text-info fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0">Hourly Rate</h6>
                    <h4 class="mb-0 text-info">₹{{ number_format($stats['hourly_rate'] ?? 0) }}</h4>
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
                        <i class="bi bi-star-fill text-warning fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0">Rating</h6>
                    <h4 class="mb-0 text-warning">{{ number_format($stats['rating'] ?? 0, 1) }}</h4>
                </div>
            </div>
        </div>
    </div>
</div> 