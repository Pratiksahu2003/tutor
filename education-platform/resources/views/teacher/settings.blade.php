@extends('layouts.dashboard')

@section('title', 'Settings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Settings</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Profile Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Profile Settings</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Profile Visibility</label>
                                <select class="form-select" name="profile_visibility">
                                    <option value="public" {{ $teacherProfile->profile_visibility === 'public' ? 'selected' : '' }}>Public</option>
                                    <option value="private" {{ $teacherProfile->profile_visibility === 'private' ? 'selected' : '' }}>Private</option>
                                </select>
                                <small class="text-muted">Control who can see your profile</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Availability Status</label>
                                <select class="form-select" name="availability_status">
                                    <option value="available" {{ $teacherProfile->availability_status === 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="busy" {{ $teacherProfile->availability_status === 'busy' ? 'selected' : '' }}>Busy</option>
                                    <option value="unavailable" {{ $teacherProfile->availability_status === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                                </select>
                                <small class="text-muted">Your current availability status</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="auto_accept_bookings" value="1" 
                                           {{ $teacherProfile->auto_accept_bookings ? 'checked' : '' }}>
                                    <label class="form-check-label">Auto-accept bookings</label>
                                </div>
                                <small class="text-muted">Automatically accept session requests</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Profile Settings</button>
                    </form>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Notification Settings</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="notification_email" value="1" 
                                           {{ $teacherProfile->notification_email ? 'checked' : '' }}>
                                    <label class="form-check-label">Email Notifications</label>
                                </div>
                                <small class="text-muted">Receive notifications via email</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="notification_sms" value="1" 
                                           {{ $teacherProfile->notification_sms ? 'checked' : '' }}>
                                    <label class="form-check-label">SMS Notifications</label>
                                </div>
                                <small class="text-muted">Receive notifications via SMS</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Notification Settings</button>
                    </form>
                </div>
            </div>

            <!-- Account Security -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Account Security</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Change Password</h6>
                            <p class="text-muted small">Update your account password</p>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                Change Password
                            </button>
                        </div>
                        <div class="col-md-6">
                            <h6>Two-Factor Authentication</h6>
                            <p class="text-muted small">Add an extra layer of security</p>
                            <button class="btn btn-outline-secondary btn-sm" disabled>
                                Coming Soon
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data & Privacy -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Data & Privacy</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Download My Data</h6>
                            <p class="text-muted small">Get a copy of your personal data</p>
                            <button class="btn btn-outline-info btn-sm">
                                Download Data
                            </button>
                        </div>
                        <div class="col-md-6">
                            <h6>Delete Account</h6>
                            <p class="text-muted small">Permanently delete your account</p>
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.profile.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-person me-1"></i>Edit Profile
                        </a>
                        <a href="{{ route('teacher.publicProfile') }}" class="btn btn-outline-info btn-sm" target="_blank">
                            <i class="bi bi-eye me-1"></i>View Public Profile
                        </a>
                        <a href="{{ route('teacher.earnings') }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-graph-up me-1"></i>View Earnings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Account Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0">Account Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Member Since</small>
                        <div>{{ $teacherProfile->user->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Last Login</small>
                        <div>{{ $teacherProfile->user->updated_at->format('M d, Y H:i') }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Account Status</small>
                        <div>
                            @if($teacherProfile->user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help & Support -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0">Help & Support</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('faq') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-question-circle me-1"></i>FAQ
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-envelope me-1"></i>Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. All your data will be permanently deleted.
                </div>
                <p>Are you sure you want to delete your account? This will:</p>
                <ul>
                    <li>Delete your profile and all associated data</li>
                    <li>Cancel all upcoming sessions</li>
                    <li>Remove all reviews and ratings</li>
                    <li>Delete your earnings history</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">Delete Account</button>
            </div>
        </div>
    </div>
</div>
@endsection 