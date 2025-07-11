<!-- Admin Dashboard Main Content -->
<div class="management-section mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5><i class="bi bi-people me-2"></i>User Management</h5>
        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-arrow-right"></i> Manage Users
        </a>
    </div>
    <p class="text-muted">View, add, or manage all users on the platform including students, teachers, and admins.</p>
</div>

<div class="management-section mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5><i class="bi bi-person-badge me-2"></i>Teacher Management</h5>
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-success btn-sm">
            <i class="bi bi-arrow-right"></i> Manage Teachers
        </a>
    </div>
    <p class="text-muted">Approve, verify, or remove teachers. View teacher statistics and performance.</p>
</div>

<div class="management-section mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5><i class="bi bi-building me-2"></i>Institute Management</h5>
        <a href="{{ route('admin.institutes.index') }}" class="btn btn-warning btn-sm">
            <i class="bi bi-arrow-right"></i> Manage Institutes
        </a>
    </div>
    <p class="text-muted">Approve, verify, or remove institutes. View institute statistics and performance.</p>
</div>

<div class="management-section mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5><i class="bi bi-bar-chart-line me-2"></i>Reports & Analytics</h5>
        <a href="{{ route('admin.analytics.index') }}" class="btn btn-info btn-sm">
            <i class="bi bi-graph-up"></i> View Analytics
        </a>
    </div>
    <p class="text-muted">Access platform-wide analytics, user activity, and performance reports.</p>
</div>

<div class="management-section mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5><i class="bi bi-gear me-2"></i>System Settings</h5>
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-gear"></i> Settings
        </a>
    </div>
    <p class="text-muted">Configure platform settings, permissions, and roles.</p>
</div> 