<!-- Modern Sidebar Widgets -->

<!-- Quick Actions Card -->
<div class="modern-card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-lightning me-2"></i>Quick Actions
        </h5>
    </div>
    <div class="card-body">
        @if($user->role === 'student')
            <div class="d-grid gap-2">
                <a href="{{ route('search.teachers') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-search me-2"></i>Find Teachers
                </a>
                <a href="#" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-bookmark me-2"></i>My Bookings
                </a>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-chat-dots me-2"></i>Messages
                </a>
            </div>
        @elseif($user->role === 'teacher')
            <div class="d-grid gap-2">
                <a href="#" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-plus me-2"></i>Add Student
                </a>
                <a href="#" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-calendar-plus me-2"></i>Schedule Class
                </a>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-graph-up me-2"></i>View Analytics
                </a>
            </div>
        @elseif($user->role === 'institute')
            <div class="d-grid gap-2">
                <a href="#" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-badge me-2"></i>Manage Teachers
                </a>
                <a href="#" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-people me-2"></i>View Students
                </a>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-building me-2"></i>Branches
                </a>
            </div>
        @else
            <div class="d-grid gap-2">
                <a href="#" class="btn btn-primary btn-sm">
                    <i class="bi bi-people me-2"></i>Manage Users
                </a>
                <a href="#" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-patch-check me-2"></i>Verifications
                </a>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-gear me-2"></i>Settings
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Notifications Card -->
<div class="modern-card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-bell me-2"></i>Recent Notifications
        </h5>
    </div>
    <div class="card-body">
        @if(isset($dashboardData['notifications']) && $dashboardData['notifications']->count() > 0)
            @foreach($dashboardData['notifications']->take(5) as $notification)
                <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                    <div class="me-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                            <i class="bi bi-{{ $notification['type'] === 'info' ? 'info-circle' : 'check-circle' }} text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $notification['title'] }}</h6>
                        <p class="mb-1 text-muted small">{{ $notification['message'] }}</p>
                        <small class="text-muted">{{ $notification['created_at']->diffForHumans() }}</small>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-3">
                <i class="bi bi-bell-slash text-muted display-6"></i>
                <p class="text-muted mt-2">No notifications yet</p>
            </div>
        @endif
    </div>
</div>

<!-- Activity Timeline -->
<div class="modern-card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-clock-history me-2"></i>Recent Activity
        </h5>
    </div>
    <div class="card-body">
        @if(isset($dashboardData['recent_activities']) && $dashboardData['recent_activities']->count() > 0)
            <div class="activity-timeline">
                @foreach($dashboardData['recent_activities']->take(5) as $activity)
                    <div class="activity-item">
                        <div class="fw-medium">{{ $activity['description'] }}</div>
                        <small class="text-muted">{{ $activity['timestamp']->diffForHumans() }}</small>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-3">
                <i class="bi bi-activity text-muted display-6"></i>
                <p class="text-muted mt-2">No recent activity</p>
            </div>
        @endif
    </div>
</div>

<!-- Profile Completion (For Students) -->
@if($user->role === 'student' && isset($dashboardData['stats']['profile_completion']))
    <div class="modern-card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-person-check me-2"></i>Profile Status
            </h5>
        </div>
        <div class="card-body text-center">
            <div class="progress-circle mx-auto mb-3" style="--progress: {{ $dashboardData['stats']['profile_completion'] }}">
                <div class="progress-value">{{ $dashboardData['stats']['profile_completion'] }}%</div>
            </div>
            <h6>Profile Completion</h6>
            <p class="text-muted small">Complete your profile to get better recommendations</p>
            @if($dashboardData['stats']['profile_completion'] < 100)
                <a href="{{ route('dashboard.profile') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-gear me-1"></i>Complete Profile
                </a>
            @endif
        </div>
    </div>
@endif

<!-- Help & Support -->
<div class="modern-card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-question-circle me-2"></i>Need Help?
        </h5>
    </div>
    <div class="card-body">
        <p class="small text-muted mb-3">Get assistance with your account and platform features.</p>
        <div class="d-grid gap-2">
            <a href="#" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-book me-2"></i>Documentation
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-headset me-2"></i>Contact Support
            </a>
        </div>
    </div>
</div> 