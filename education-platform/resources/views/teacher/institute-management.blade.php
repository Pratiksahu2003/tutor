@extends('layouts.dashboard')

@section('title', 'Institute Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Institute Management</h1>
    </div>

    <!-- Current Institute Status -->
    @if($currentInstitute)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Current Institute</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="mb-1">{{ $currentInstitute->institute_name }}</h6>
                        <p class="text-muted mb-2">{{ $currentInstitute->description }}</p>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Location</small>
                                <div>{{ $currentInstitute->city }}, {{ $currentInstitute->state }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Verification Status</small>
                                <div>
                                    @if($teacherProfile->is_institute_verified)
                                        <span class="badge bg-success">Verified</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <form action="{{ route('teacher.institute.leave') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to leave this institute?')">
                                <i class="bi bi-box-arrow-right me-1"></i>Leave Institute
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            You are currently working as a freelance teacher. You can join an institute to expand your opportunities.
        </div>
    @endif

    <!-- Available Institutes -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent">
            <h5 class="mb-0">Available Institutes</h5>
        </div>
        <div class="card-body">
            @if($institutes->count() > 0)
                <div class="row">
                    @foreach($institutes as $institute)
                        <div class="col-lg-6 mb-4">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="card-title mb-1">{{ $institute->institute_name }}</h6>
                                            <p class="text-muted small mb-0">{{ $institute->city }}, {{ $institute->state }}</p>
                                        </div>
                                        <span class="badge bg-success">Verified</span>
                                    </div>
                                    
                                    @if($institute->description)
                                        <p class="card-text small">{{ Str::limit($institute->description, 100) }}</p>
                                    @endif
                                    
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">Teachers</small>
                                            <div class="fw-bold">{{ $institute->teachers()->count() }}</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Students</small>
                                            <div class="fw-bold">{{ $institute->students()->count() }}</div>
                                        </div>
                                    </div>
                                    
                                    @if($currentInstitute && $currentInstitute->id == $institute->id)
                                        <button class="btn btn-secondary btn-sm w-100" disabled>
                                            <i class="bi bi-check-circle me-1"></i>Already Joined
                                        </button>
                                    @else
                                        <button class="btn btn-primary btn-sm w-100" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#applyModal" 
                                                data-institute-id="{{ $institute->id }}"
                                                data-institute-name="{{ $institute->institute_name }}">
                                            <i class="bi bi-plus-circle me-1"></i>Apply to Join
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">No Institutes Available</h5>
                    <p class="text-muted">There are currently no verified institutes available for joining.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Institute Benefits -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-transparent">
            <h5 class="mb-0">Benefits of Joining an Institute</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <i class="bi bi-people text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6>Access to More Students</h6>
                            <p class="text-muted small mb-0">Connect with students who are specifically looking for institute-affiliated teachers.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <i class="bi bi-shield-check text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6>Enhanced Credibility</h6>
                            <p class="text-muted small mb-0">Being associated with a verified institute increases your professional credibility.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <i class="bi bi-calendar-event text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6>Structured Schedule</h6>
                            <p class="text-muted small mb-0">Benefit from organized schedules and better time management.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <i class="bi bi-graph-up text-info" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6>Growth Opportunities</h6>
                            <p class="text-muted small mb-0">Access to professional development and career advancement opportunities.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Apply to Institute Modal -->
<div class="modal fade" id="applyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apply to Join Institute</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('teacher.institute.apply') }}" method="POST">
                @csrf
                <input type="hidden" name="institute_id" id="instituteId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Institute</label>
                        <input type="text" class="form-control" id="instituteName" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Experience with Institutes</label>
                        <textarea class="form-control" name="institute_experience" rows="3" 
                                  placeholder="Describe your experience working with educational institutes..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Subjects You Can Teach</label>
                        <textarea class="form-control" name="institute_subjects" rows="2" 
                                  placeholder="List the subjects you can teach at this institute..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Your application will be reviewed by the institute. You'll be notified once they make a decision.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle apply modal
    const applyModal = document.getElementById('applyModal');
    if (applyModal) {
        applyModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const instituteId = button.getAttribute('data-institute-id');
            const instituteName = button.getAttribute('data-institute-name');
            
            document.getElementById('instituteId').value = instituteId;
            document.getElementById('instituteName').value = instituteName;
        });
    }
});
</script>
@endpush
@endsection 