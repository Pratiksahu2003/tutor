<!-- Admin Modals for Unified Dashboard -->

<!-- Manage Users Modal -->
<div class="modal fade" id="manageUsersModal" tabindex="-1" aria-labelledby="manageUsersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageUsersModalLabel">
                    <i class="bi bi-people me-2"></i>Manage Users
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-person-plus text-primary" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">Add New User</h6>
                                <p class="text-muted small">Create new student, teacher, or admin accounts</p>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>Add User
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-gear text-success" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">User Settings</h6>
                                <p class="text-muted small">Manage roles, permissions, and account status</p>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-gear me-1"></i>Manage Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Settings Modal -->
<div class="modal fade" id="systemSettingsModal" tabindex="-1" aria-labelledby="systemSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="systemSettingsModalLabel">
                    <i class="bi bi-gear me-2"></i>System Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-shield-check text-warning" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">Roles & Permissions</h6>
                                <p class="text-muted small">Manage user roles and access permissions</p>
                                <a href="{{ route('admin.roles.index') }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-shield me-1"></i>Manage Roles
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-sliders text-info" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">Platform Settings</h6>
                                <p class="text-muted small">Configure platform-wide settings and preferences</p>
                                <a href="{{ route('admin.settings.index') }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-sliders me-1"></i>Settings
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Reports Modal -->
<div class="modal fade" id="analyticsReportsModal" tabindex="-1" aria-labelledby="analyticsReportsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="analyticsReportsModalLabel">
                    <i class="bi bi-graph-up me-2"></i>Analytics & Reports
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-bar-chart text-primary" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">User Analytics</h6>
                                <p class="text-muted small">View user activity and engagement metrics</p>
                                <a href="{{ route('admin.analytics.index') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-bar-chart me-1"></i>View Analytics
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-file-earmark-text text-success" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">Generate Reports</h6>
                                <p class="text-muted small">Create and download detailed reports</p>
                                <button class="btn btn-success btn-sm" onclick="generateReport()">
                                    <i class="bi bi-download me-1"></i>Generate Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Teacher Management Modal -->
<div class="modal fade" id="teacherManagementModal" tabindex="-1" aria-labelledby="teacherManagementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teacherManagementModalLabel">
                    <i class="bi bi-person-badge me-2"></i>Teacher Management
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-patch-check text-success" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">Teacher Verifications</h6>
                                <p class="text-muted small">Review and approve teacher applications</p>
                                <a href="{{ route('admin.teachers.index', ['verified' => 'unverified']) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-patch-check me-1"></i>Review Teachers
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-graph-up text-info" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">Teacher Statistics</h6>
                                <p class="text-muted small">View teacher performance and statistics</p>
                                <a href="{{ route('admin.teachers.statistics') }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-graph-up me-1"></i>View Stats
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Institute Management Modal -->
<div class="modal fade" id="instituteManagementModal" tabindex="-1" aria-labelledby="instituteManagementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="instituteManagementModalLabel">
                    <i class="bi bi-building me-2"></i>Institute Management
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-building-check text-warning" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">Institute Verifications</h6>
                                <p class="text-muted small">Review and approve institute applications</p>
                                <a href="{{ route('admin.institutes.index', ['verified' => 'unverified']) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-building-check me-1"></i>Review Institutes
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-bar-chart text-primary" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">Institute Analytics</h6>
                                <p class="text-muted small">View institute performance and statistics</p>
                                <a href="{{ route('admin.institutes.index') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-bar-chart me-1"></i>View Institutes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateReport() {
    // Placeholder for report generation functionality
    alert('Report generation feature coming soon!');
}
</script> 