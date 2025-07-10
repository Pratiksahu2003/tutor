<!-- Modern Institute Content -->
<div class="row">
    <!-- Institute Overview -->
    <div class="col-12 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-building me-2"></i>Institute Overview
                </h5>
            </div>
            <div class="card-body">
                @if(isset($institute))
                    <div class="row">
                        <div class="col-md-8">
                            <h4>{{ $institute->institute_name }}</h4>
                            <p class="text-muted">{{ $institute->description }}</p>
                            <div class="row mt-3">
                                <div class="col-sm-6">
                                    <strong>Established:</strong> {{ $institute->established_year }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Registration:</strong> {{ $institute->registration_number }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="bg-primary bg-opacity-10 rounded p-4">
                                <i class="bi bi-star-fill text-warning display-5"></i>
                                <h3 class="mt-2 mb-0">{{ number_format($institute->rating ?? 0, 1) }}</h3>
                                <small class="text-muted">Institute Rating</small>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-building text-muted display-6"></i>
                        <p class="text-muted mt-2">Institute profile not set up</p>
                        <a href="{{ route('dashboard.profile') }}" class="btn btn-primary">
                            <i class="bi bi-plus me-1"></i>Complete Profile
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Teachers Management -->
@if(isset($teachers) && $teachers->count() > 0)
<div class="row">
    <div class="col-12 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-badge me-2"></i>Institute Teachers
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Teacher</th>
                                <th>Subjects</th>
                                <th>Experience</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers->take(5) as $teacher)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $teacher->user->profile_image ? asset('storage/' . $teacher->user->profile_image) : asset('images/default-avatar.png') }}" 
                                                 class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <h6 class="mb-0">{{ $teacher->user->name }}</h6>
                                                <small class="text-muted">{{ $teacher->qualification }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($teacher->subjects->take(2) as $subject)
                                            <span class="badge bg-primary me-1">{{ $subject->name }}</span>
                                        @endforeach
                                        @if($teacher->subjects->count() > 2)
                                            <span class="text-muted">+{{ $teacher->subjects->count() - 2 }} more</span>
                                        @endif
                                    </td>
                                    <td>{{ $teacher->experience_years ?? 0 }} years</td>
                                    <td>
                                        <span class="badge bg-warning">
                                            <i class="bi bi-star-fill me-1"></i>{{ number_format($teacher->rating ?? 0, 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $teacher->verified ? 'success' : 'warning' }}">
                                            {{ $teacher->verified ? 'Verified' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Pending Teacher Applications -->
@if(isset($pending_teachers) && $pending_teachers->count() > 0)
<div class="row">
    <div class="col-12 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock me-2"></i>Pending Teacher Applications
                </h5>
            </div>
            <div class="card-body">
                @foreach($pending_teachers as $teacher)
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <img src="{{ $teacher->user->profile_image ? asset('storage/' . $teacher->user->profile_image) : asset('images/default-avatar.png') }}" 
                                 class="rounded-circle me-3" width="40" height="40">
                            <div>
                                <h6 class="mb-0">{{ $teacher->user->name }}</h6>
                                <small class="text-muted">{{ $teacher->qualification }}</small>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-success btn-sm me-1">
                                <i class="bi bi-check me-1"></i>Approve
                            </button>
                            <button class="btn btn-danger btn-sm">
                                <i class="bi bi-x me-1"></i>Reject
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif 