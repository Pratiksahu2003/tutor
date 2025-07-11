@extends('admin.layout')

@section('title', 'Teacher Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.teachers.index') }}">Teachers</a></li>
    <li class="breadcrumb-item active">{{ $teacher->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Teacher Details
                </h1>
                <p class="text-muted">View and manage teacher profile information</p>
            </div>
            <div>
                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-2"></i>Edit Teacher
                </a>
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Teacher Profile Card -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Basic Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <p class="form-control-plaintext">{{ $teacher->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext">{{ $teacher->email }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone</label>
                            <p class="form-control-plaintext">{{ $teacher->phone ?? 'Not provided' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Address</label>
                            <p class="form-control-plaintext">{{ $teacher->address ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">City</label>
                            <p class="form-control-plaintext">{{ $teacher->city ?? 'Not provided' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">State</label>
                            <p class="form-control-plaintext">{{ $teacher->state ?? 'Not provided' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pincode</label>
                            <p class="form-control-plaintext">{{ $teacher->pincode ?? 'Not provided' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Account Status</label>
                            <span class="badge bg-{{ $teacher->is_active ? 'success' : 'secondary' }}">
                                {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teaching Profile -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>Teaching Profile
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Qualification</label>
                            <p class="form-control-plaintext">{{ $teacher->teacherProfile->qualification ?? 'Not specified' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Specialization</label>
                            <p class="form-control-plaintext">{{ $teacher->teacherProfile->specialization ?? 'Not specified' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Experience</label>
                            <p class="form-control-plaintext">{{ $teacher->teacherProfile->experience_years ?? 0 }} years</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hourly Rate</label>
                            <p class="form-control-plaintext">₹{{ $teacher->teacherProfile->hourly_rate ?? 0 }}/hr</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Teaching Mode</label>
                            <p class="form-control-plaintext">{{ ucfirst($teacher->teacherProfile->teaching_mode ?? 'Not specified') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Verification Status</label>
                            <span class="badge bg-{{ $teacher->teacherProfile->verified ? 'success' : 'warning' }}">
                                {{ $teacher->teacherProfile->verified ? 'Verified' : 'Pending' }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Total Students</label>
                            <p class="form-control-plaintext">{{ $teacher->teacherProfile->total_students ?? 0 }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Rating</label>
                            <p class="form-control-plaintext">{{ number_format($teacher->teacherProfile->rating ?? 0, 1) }}/5.0</p>
                        </div>
                    </div>
                </div>
                
                @if($teacher->teacherProfile->bio)
                <div class="mt-3">
                    <label class="form-label fw-bold">Bio</label>
                    <p class="form-control-plaintext">{{ $teacher->teacherProfile->bio }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(!$teacher->teacherProfile->verified)
                        <form method="POST" action="{{ route('admin.teachers.verify', $teacher) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i>Verify Teacher
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.teachers.unverify', $teacher) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-times me-2"></i>Unverify Teacher
                            </button>
                        </form>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.teachers.toggle-status', $teacher) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $teacher->is_active ? 'warning' : 'success' }} w-100">
                            <i class="fas fa-toggle-{{ $teacher->is_active ? 'off' : 'on' }} me-2"></i>
                            {{ $teacher->is_active ? 'Deactivate' : 'Activate' }} Account
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-primary w-100">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary">{{ $teacher->teacherProfile->total_students ?? 0 }}</h4>
                            <small class="text-muted">Students</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $teacher->teacherProfile->total_reviews ?? 0 }}</h4>
                        <small class="text-muted">Reviews</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-info">{{ number_format($teacher->teacherProfile->rating ?? 0, 1) }}</h4>
                            <small class="text-muted">Rating</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ $teacher->teacherProfile->experience_years ?? 0 }}</h4>
                        <small class="text-muted">Years Exp.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Account Information
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted">Member Since</small>
                    <p class="mb-0">{{ $teacher->created_at->format('M d, Y') }}</p>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Last Updated</small>
                    <p class="mb-0">{{ $teacher->updated_at->format('M d, Y') }}</p>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Email Verified</small>
                    <p class="mb-0">
                        @if($teacher->email_verified_at)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-warning">No</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 