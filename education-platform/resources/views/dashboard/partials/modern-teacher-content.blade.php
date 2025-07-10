<!-- Modern Teacher Content -->
<div class="row">
    <!-- Teaching Overview -->
    <div class="col-12 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-week me-2"></i>Teaching Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-primary bg-opacity-10 rounded">
                            <i class="bi bi-calendar-event text-primary display-6"></i>
                            <h4 class="mt-2 mb-0 text-primary">{{ $stats['sessions_this_month'] ?? 0 }}</h4>
                            <small class="text-muted">Sessions This Month</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-success bg-opacity-10 rounded">
                            <i class="bi bi-people text-success display-6"></i>
                            <h4 class="mt-2 mb-0 text-success">{{ $stats['total_students'] ?? 0 }}</h4>
                            <small class="text-muted">Total Students</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-info bg-opacity-10 rounded">
                            <i class="bi bi-currency-rupee text-info display-6"></i>
                            <h4 class="mt-2 mb-0 text-info">₹{{ number_format($stats['hourly_rate'] ?? 0) }}</h4>
                            <small class="text-muted">Hourly Rate</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-warning bg-opacity-10 rounded">
                            <i class="bi bi-star-fill text-warning display-6"></i>
                            <h4 class="mt-2 mb-0 text-warning">{{ number_format($stats['rating'] ?? 0, 1) }}</h4>
                            <small class="text-muted">Rating</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Teaching Subjects -->
@if(isset($subjects) && $subjects->count() > 0)
<div class="row">
    <div class="col-12 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-book me-2"></i>Teaching Subjects
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($subjects as $subject)
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-bookmark text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $subject->name }}</h6>
                                    <small class="text-muted">{{ $subject->description ?? 'Subject' }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Verification Status -->
@if(isset($verification_status))
<div class="row">
    <div class="col-12 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-shield-check me-2"></i>Verification Status
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-{{ $verification_status['admin_verified'] ? 'check-circle text-success' : 'clock text-warning' }} me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0">Admin Verification</h6>
                                <small class="text-muted">
                                    {{ $verification_status['admin_verified'] ? 'Verified' : 'Pending' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-{{ $verification_status['institute_verified'] ? 'check-circle text-success' : 'clock text-warning' }} me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0">Institute Verification</h6>
                                <small class="text-muted">
                                    {{ $verification_status['institute_verified'] ? 'Verified' : 'Pending' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-{{ $verification_status['documents_submitted'] ? 'check-circle text-success' : 'x-circle text-danger' }} me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0">Documents</h6>
                                <small class="text-muted">
                                    {{ $verification_status['documents_submitted'] ? 'Submitted' : 'Not Submitted' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif 