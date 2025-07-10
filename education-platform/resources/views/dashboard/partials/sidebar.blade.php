<!-- Quick Actions -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-lightning me-2"></i>Quick Actions
        </h6>
    </div>
    <div class="card-body">
        @if(auth()->user()->isStudent() || auth()->user()->role === 'parent')
            <div class="d-grid gap-2">
                <button class="btn btn-outline-primary quick-action-btn" onclick="quickAction('find-teachers')">
                    <i class="bi bi-search me-2"></i>Find Teachers
                </button>
                <button class="btn btn-outline-success quick-action-btn" onclick="quickAction('schedule-session')">
                    <i class="bi bi-calendar-plus me-2"></i>Schedule Session
                </button>
                <button class="btn btn-outline-info quick-action-btn" onclick="quickAction('view-profile')">
                    <i class="bi bi-person me-2"></i>Update Profile
                </button>
                <button class="btn btn-outline-warning quick-action-btn" onclick="quickAction('contact-support')">
                    <i class="bi bi-headset me-2"></i>Contact Support
                </button>
            </div>
        @elseif(auth()->user()->isTeacher())
            <div class="d-grid gap-2">
                <a href="#" class="btn btn-outline-primary quick-action-btn">
                    <i class="bi bi-people me-2"></i>My Students
                </a>
                <a href="#" class="btn btn-outline-success quick-action-btn">
                    <i class="bi bi-calendar-event me-2"></i>Schedule
                </a>
                <a href="#" class="btn btn-outline-info quick-action-btn">
                    <i class="bi bi-person-gear me-2"></i>Profile Settings
                </a>
                <a href="#" class="btn btn-outline-warning quick-action-btn">
                    <i class="bi bi-chat-dots me-2"></i>Messages
                </a>
            </div>
        @elseif(auth()->user()->isInstitute())
            <div class="d-grid gap-2">
                <a href="#" class="btn btn-outline-primary quick-action-btn">
                    <i class="bi bi-person-badge me-2"></i>Manage Teachers
                </a>
                <a href="#" class="btn btn-outline-success quick-action-btn">
                    <i class="bi bi-graph-up me-2"></i>Analytics
                </a>
                <a href="#" class="btn btn-outline-info quick-action-btn">
                    <i class="bi bi-building-gear me-2"></i>Institute Settings
                </a>
                <a href="#" class="btn btn-outline-warning quick-action-btn">
                    <i class="bi bi-megaphone me-2"></i>Announcements
                </a>
            </div>
        @elseif(auth()->user()->isAdmin())
            <div class="d-grid gap-2">
                <a href="#" class="btn btn-outline-primary quick-action-btn">
                    <i class="bi bi-people me-2"></i>User Management
                </a>
                <a href="#" class="btn btn-outline-success quick-action-btn">
                    <i class="bi bi-shield-check me-2"></i>Verify Teachers
                </a>
                <a href="#" class="btn btn-outline-info quick-action-btn">
                    <i class="bi bi-gear me-2"></i>System Settings
                </a>
                <a href="#" class="btn btn-outline-danger quick-action-btn">
                    <i class="bi bi-exclamation-triangle me-2"></i>Reports
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Notifications -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="bi bi-bell me-2"></i>Recent Notifications
        </h6>
        <span class="badge bg-primary">3</span>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            <div class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">New message from teacher</h6>
                    <small>5 min ago</small>
                </div>
                <p class="mb-1 small text-muted">John Doe sent you a message about tomorrow's session.</p>
            </div>
            <div class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">Session reminder</h6>
                    <small>1 hour ago</small>
                </div>
                <p class="mb-1 small text-muted">Physics session starts in 2 hours.</p>
            </div>
            <div class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">Payment confirmed</h6>
                    <small>2 hours ago</small>
                </div>
                <p class="mb-1 small text-muted">Payment for last session has been processed.</p>
            </div>
        </div>
        <div class="card-footer text-center">
            <a href="#" class="text-decoration-none small">View all notifications</a>
        </div>
    </div>
</div>

<!-- Profile Overview -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-person-circle me-2"></i>Profile Overview
        </h6>
    </div>
    <div class="card-body text-center">
        <img src="{{ auth()->user()->profile_image ?? 'https://via.placeholder.com/80' }}" 
             alt="Profile" class="rounded-circle mb-3" width="80" height="80">
        <h6 class="mb-1">{{ auth()->user()->name }}</h6>
        <p class="text-muted small mb-2">{{ ucfirst(auth()->user()->role) }}</p>
        
        @if(auth()->user()->isTeacher())
            <div class="mb-2">
                <span class="text-warning">
                    <i class="bi bi-star-fill"></i> 4.9
                </span>
                <small class="text-muted">(156 reviews)</small>
            </div>
            <div class="text-success small">
                <i class="bi bi-check-circle me-1"></i>Verified Teacher
            </div>
        @elseif(auth()->user()->isInstitute())
            <div class="mb-2">
                <span class="text-warning">
                    <i class="bi bi-star-fill"></i> 4.7
                </span>
                <small class="text-muted">(89 reviews)</small>
            </div>
            <div class="text-success small">
                <i class="bi bi-shield-check me-1"></i>Verified Institute
            </div>
        @elseif(auth()->user()->isStudent())
            <div class="text-primary small">
                <i class="bi bi-book me-1"></i>Active Learner
            </div>
        @endif
        
        <div class="d-grid mt-3">
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil me-1"></i>Edit Profile
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-activity me-2"></i>Recent Activity
        </h6>
    </div>
    <div class="card-body">
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-marker bg-primary"></div>
                <div class="timeline-content">
                    <h6 class="mb-1 small">Profile Updated</h6>
                    <p class="mb-0 text-muted small">Updated contact information</p>
                    <small class="text-muted">2 hours ago</small>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-marker bg-success"></div>
                <div class="timeline-content">
                    <h6 class="mb-1 small">Session Completed</h6>
                    <p class="mb-0 text-muted small">Mathematics with John Doe</p>
                    <small class="text-muted">1 day ago</small>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-marker bg-info"></div>
                <div class="timeline-content">
                    <h6 class="mb-1 small">New Teacher Found</h6>
                    <p class="mb-0 text-muted small">Added Sarah Wilson to favorites</p>
                    <small class="text-muted">3 days ago</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
    margin-bottom: 1rem;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 2px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -21px;
    top: 12px;
    width: 2px;
    height: calc(100% + 0.5rem);
    background: #e9ecef;
}
</style> 