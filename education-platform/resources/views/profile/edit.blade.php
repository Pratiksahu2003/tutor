@extends('layouts.dashboard')

@section('title', 'Profile Settings')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Profile Navigation Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Profile Settings</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="#profile-info" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                        <i class="bi bi-person me-2"></i>Profile Information
                    </a>
                    <a href="#password" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-shield-lock me-2"></i>Password & Security
                    </a>
                    <a href="#avatar" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-image me-2"></i>Profile Picture
                    </a>
                    <a href="#verification" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-check-circle me-2"></i>Verification
                    </a>
                    <a href="#preferences" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-gear me-2"></i>Preferences
                    </a>
                    <a href="#notifications" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-bell me-2"></i>Notifications
                    </a>
                    <a href="#sessions" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="bi bi-devices me-2"></i>Active Sessions
                    </a>
                    <a href="#delete-account" class="list-group-item list-group-item-action text-danger" data-bs-toggle="list">
                        <i class="bi bi-trash me-2"></i>Delete Account
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="col-md-9">
            <div class="tab-content">
                <!-- Profile Information -->
                <div class="tab-pane fade show active" id="profile-info">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Profile Information</h5>
                        </div>
                        <div class="card-body">
                            @if (session('status') === 'profile-updated')
                                <div class="alert alert-success">
                                    Profile updated successfully!
                                </div>
                            @endif

                            <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('put')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}">
                                            @error('city')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="state" class="form-label">State</label>
                                            <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $user->state) }}">
                                            @error('state')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="pincode" class="form-label">Pincode</label>
                                            <input type="text" class="form-control" id="pincode" name="pincode" value="{{ old('pincode', $user->pincode) }}">
                                            @error('pincode')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Password & Security -->
                <div class="tab-pane fade" id="password">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Password & Security</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('profile.password.update') }}">
                                @csrf
                                @method('put')

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    @error('current_password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Profile Picture -->
                <div class="tab-pane fade" id="avatar">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Profile Picture</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        @if($user->profile_image)
                                            <img src="{{ asset('storage/' . $user->profile_image) }}" 
                                                 alt="Profile Picture" 
                                                 class="img-fluid rounded-circle mb-3" 
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto" 
                                                 style="width: 150px; height: 150px;">
                                                <i class="bi bi-person" style="font-size: 4rem; color: #6c757d;"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <form method="post" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('post')

                                        <div class="mb-3">
                                            <label for="avatar" class="form-label">Upload New Picture</label>
                                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" required>
                                            <div class="form-text">Maximum file size: 2MB. Supported formats: JPG, PNG, GIF</div>
                                            @error('avatar')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Upload Picture</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification -->
                <div class="tab-pane fade" id="verification">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Account Verification</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Verification helps build trust with other users. Please upload valid documents for verification.
                            </div>

                            <form method="post" action="{{ route('profile.verification.submit') }}" enctype="multipart/form-data">
                                @csrf
                                @method('post')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="document_type" class="form-label">Document Type</label>
                                            <select class="form-control" id="document_type" name="document_type" required>
                                                <option value="">Select Document Type</option>
                                                <option value="id_proof">ID Proof (Aadhar/PAN)</option>
                                                <option value="address_proof">Address Proof</option>
                                                <option value="qualification">Qualification Certificate</option>
                                                <option value="experience">Experience Certificate</option>
                                            </select>
                                            @error('document_type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="document_number" class="form-label">Document Number</label>
                                            <input type="text" class="form-control" id="document_number" name="document_number" required>
                                            @error('document_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="document_file" class="form-label">Upload Document</label>
                                            <input type="file" class="form-control" id="document_file" name="document_file" accept=".pdf,.jpg,.jpeg,.png" required>
                                            <div class="form-text">Maximum file size: 5MB. Supported formats: PDF, JPG, PNG</div>
                                            @error('document_file')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="expiry_date" class="form-label">Expiry Date (if applicable)</label>
                                            <input type="date" class="form-control" id="expiry_date" name="expiry_date">
                                            @error('expiry_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Submit for Verification</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Preferences -->
                <div class="tab-pane fade" id="preferences">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Account Preferences</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('profile.preferences.update') }}">
                                @csrf
                                @method('put')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="language" class="form-label">Language</label>
                                            <select class="form-control" id="language" name="language">
                                                <option value="en" {{ ($user->preferences['language'] ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                                <option value="hi" {{ ($user->preferences['language'] ?? 'en') == 'hi' ? 'selected' : '' }}>Hindi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="timezone" class="form-label">Timezone</label>
                                            <select class="form-control" id="timezone" name="timezone">
                                                <option value="Asia/Kolkata" {{ ($user->preferences['timezone'] ?? 'Asia/Kolkata') == 'Asia/Kolkata' ? 'selected' : '' }}>India (IST)</option>
                                                <option value="UTC" {{ ($user->preferences['timezone'] ?? 'Asia/Kolkata') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date_format" class="form-label">Date Format</label>
                                            <select class="form-control" id="date_format" name="date_format">
                                                <option value="Y-m-d" {{ ($user->preferences['date_format'] ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                                <option value="d/m/Y" {{ ($user->preferences['date_format'] ?? 'Y-m-d') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                                <option value="m/d/Y" {{ ($user->preferences['date_format'] ?? 'Y-m-d') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="time_format" class="form-label">Time Format</label>
                                            <select class="form-control" id="time_format" name="time_format">
                                                <option value="12" {{ ($user->preferences['time_format'] ?? '12') == '12' ? 'selected' : '' }}>12 Hour</option>
                                                <option value="24" {{ ($user->preferences['time_format'] ?? '12') == '24' ? 'selected' : '' }}>24 Hour</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Save Preferences</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="tab-pane fade" id="notifications">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Notification Settings</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('profile.notifications.update') }}">
                                @csrf
                                @method('put')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" value="1" 
                                                   {{ ($user->preferences['email_notifications'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="email_notifications">
                                                Email Notifications
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="sms_notifications" name="sms_notifications" value="1"
                                                   {{ ($user->preferences['sms_notifications'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sms_notifications">
                                                SMS Notifications
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="push_notifications" name="push_notifications" value="1"
                                                   {{ ($user->preferences['push_notifications'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="push_notifications">
                                                Push Notifications
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="booking_notifications" name="booking_notifications" value="1"
                                                   {{ ($user->preferences['booking_notifications'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="booking_notifications">
                                                Booking Notifications
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="payment_notifications" name="payment_notifications" value="1"
                                                   {{ ($user->preferences['payment_notifications'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="payment_notifications">
                                                Payment Notifications
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="marketing_emails" name="marketing_emails" value="1"
                                                   {{ ($user->preferences['marketing_emails'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="marketing_emails">
                                                Marketing Emails
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Save Notification Settings</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Active Sessions -->
                <div class="tab-pane fade" id="sessions">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Active Sessions</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                These are the devices currently logged into your account.
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Device</th>
                                            <th>Location</th>
                                            <th>Last Activity</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <i class="bi bi-laptop me-2"></i>
                                                Windows 10 - Chrome
                                            </td>
                                            <td>Delhi, India</td>
                                            <td>Just now</td>
                                            <td>
                                                <span class="badge bg-success">Current Session</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="tab-pane fade" id="delete-account">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">Delete Account</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Warning:</strong> This action cannot be undone. All your data will be permanently deleted.
                            </div>

                            <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                                @csrf
                                @method('delete')

                                <div class="mb-3">
                                    <label for="delete_password" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="delete_password" name="password" required>
                                    <div class="form-text">Please enter your password to confirm account deletion.</div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-danger">Delete My Account</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tab-content {
    min-height: 400px;
}
.list-group-item.active {
    background-color: #667eea;
    border-color: #667eea;
}
</style>

<script>
// Initialize Bootstrap tabs
document.addEventListener('DOMContentLoaded', function() {
    var triggerTabList = [].slice.call(document.querySelectorAll('#profile-tabs a'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
});
</script>
@endsection
