<!-- Modern Admin Content -->
<div class="row">
    <!-- Recent Registrations -->
    <div class="col-12 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-plus me-2"></i>Recent Registrations
                </h5>
            </div>
            <div class="card-body">
                @if(isset($recent_registrations) && $recent_registrations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_registrations->take(10) as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.png') }}" 
                                                     class="rounded-circle me-2" width="32" height="32">
                                                <span>{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at->diffForHumans() }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->is_active ? 'success' : 'warning' }}">
                                                {{ $user->is_active ? 'Active' : 'Pending' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-people text-muted display-6"></i>
                        <p class="text-muted mt-2">No recent registrations</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Pending Verifications -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-patch-check me-2"></i>Pending Teacher Verifications
                </h5>
            </div>
            <div class="card-body">
                @if(isset($pending_teachers) && $pending_teachers->count() > 0)
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
                                    <i class="bi bi-check"></i>
                                </button>
                                <button class="btn btn-danger btn-sm">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-check-circle text-success display-6"></i>
                        <p class="text-muted mt-2">No pending verifications</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-building me-2"></i>Pending Institute Verifications
                </h5>
            </div>
            <div class="card-body">
                @if(isset($pending_institutes) && $pending_institutes->count() > 0)
                    @foreach($pending_institutes as $institute)
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                            <div>
                                <h6 class="mb-0">{{ $institute->institute_name }}</h6>
                                <small class="text-muted">{{ $institute->user->name }}</small>
                            </div>
                            <div>
                                <button class="btn btn-success btn-sm me-1">
                                    <i class="bi bi-check"></i>
                                </button>
                                <button class="btn btn-danger btn-sm">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-check-circle text-success display-6"></i>
                        <p class="text-muted mt-2">No pending verifications</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> 