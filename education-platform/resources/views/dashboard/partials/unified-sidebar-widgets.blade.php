<!-- Unified Dashboard Sidebar Widgets -->
<div class="row">
    <!-- Quick Actions Widget -->
    <div class="col-12 mb-4">
        <div class="stats-widget">
            <h6 class="mb-3"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
            <div class="d-grid gap-2">
                @if($user->role === 'student')
                    <a href="{{ route('search.teachers') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-search me-1"></i>Find Teachers
                    </a>
                    <a href="{{ route('search.institutes') }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-building me-1"></i>Find Institutes
                    </a>
                @elseif($user->role === 'teacher')
                    <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                        <i class="bi bi-plus-circle me-1"></i>Add Subject
                    </a>
                    <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#scheduleSessionModal">
                        <i class="bi bi-calendar-plus me-1"></i>Schedule Session
                    </a>
                    <hr>
                    <a href="{{ route('teacher.earnings') }}" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-currency-rupee me-1"></i>Earnings & Reports
                    </a>
                    <a href="{{ route('teacher.publicProfile', $user->teacherProfile->slug ?? $user->id) }}" class="btn btn-outline-info btn-sm" target="_blank">
                        <i class="bi bi-person-lines-fill me-1"></i>Public Profile
                    </a>
                    <a href="{{ route('teacher.reviews') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-star me-1"></i>Reviews
                    </a>
                    <a href="{{ route('teacher.institute') }}" class="btn btn-outline-purple btn-sm">
                        <i class="bi bi-building me-1"></i>Institute Management
                    </a>
                    <a href="{{ route('teacher.settings') }}" class="btn btn-outline-dark btn-sm">
                        <i class="bi bi-gear me-1"></i>Settings
                    </a>
                @elseif($user->role === 'institute')
                    <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBranchModal">
                        <i class="bi bi-building me-1"></i>Add Branch
                    </a>
                    <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                        <i class="bi bi-person-badge me-1"></i>Add Teacher
                    </a>
                @elseif($user->role === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-people me-1"></i>Manage Users
                    </a>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-person-badge me-1"></i>Manage Teachers
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Profile Completion Widget -->
    <div class="col-12 mb-4">
        <div class="stats-widget">
            <h6 class="mb-3"><i class="bi bi-person-check me-2"></i>Profile Completion</h6>
            @php
                $completion = $dashboardData['profile_completion'] ?? 0;
                $color = $completion >= 80 ? 'success' : ($completion >= 60 ? 'warning' : 'danger');
            @endphp
            <div class="progress mb-2" style="height: 8px;">
                <div class="progress-bar bg-{{ $color }}" style="width: {{ $completion }}%"></div>
            </div>
            <small class="text-muted">{{ $completion }}% Complete</small>
            @if($completion < 100)
                <div class="mt-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>Complete Profile
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Activity Widget -->
    <div class="col-12 mb-4">
        <div class="stats-widget">
            <h6 class="mb-3"><i class="bi bi-clock-history me-2"></i>Recent Activity</h6>
            @if(isset($dashboardData['recent_activities']) && $dashboardData['recent_activities']->count() > 0)
                @foreach($dashboardData['recent_activities']->take(3) as $activity)
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <i class="bi bi-circle-fill text-primary" style="font-size: 0.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <small class="text-muted">{{ $activity->description ?? 'Activity' }}</small>
                            <br>
                            <small class="text-muted">{{ $activity->created_at ?? now() }}</small>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted small">No recent activity</p>
            @endif
        </div>
    </div>

    <!-- Notifications Widget -->
    <div class="col-12 mb-4">
        <div class="stats-widget">
            <h6 class="mb-3"><i class="bi bi-bell me-2"></i>Notifications</h6>
            @if(isset($dashboardData['notifications']) && $dashboardData['notifications']->count() > 0)
                @foreach($dashboardData['notifications']->take(3) as $notification)
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <i class="bi bi-info-circle text-info" style="font-size: 0.75rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <small class="text-muted">{{ $notification->message ?? 'Notification' }}</small>
                            <br>
                            <small class="text-muted">{{ $notification->created_at ?? now() }}</small>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted small">No new notifications</p>
            @endif
        </div>
    </div>

    <!-- Quick Links Widget -->
    <div class="col-12 mb-4">
        <div class="stats-widget">
            <h6 class="mb-3"><i class="bi bi-link-45deg me-2"></i>Quick Links</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('search.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-search me-1"></i>Search
                </a>
                <a href="{{ route('faq') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-question-circle me-1"></i>Help & FAQ
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-envelope me-1"></i>Contact Support
                </a>
            </div>
        </div>
    </div>
</div> 