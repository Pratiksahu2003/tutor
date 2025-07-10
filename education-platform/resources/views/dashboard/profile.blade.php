@extends('layouts.app')

@section('title', 'Edit Profile - Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Breadcrumb -->
        <div class="col-12 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profile Settings</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Profile Form -->
        <div class="col-lg-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5>Please fix the following errors:</h5>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person me-2"></i>Basic Information
                    </h5>
                </div>
                <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Profile Image -->
                            <div class="col-md-4 text-center mb-3">
                                <div class="position-relative d-inline-block">
                                    <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.png') }}" 
                                         alt="Profile" 
                                         class="rounded-circle border border-3"
                                         width="150" height="150"
                                         id="profilePreview">
                                    <label for="profile_image" class="position-absolute bottom-0 end-0 btn btn-primary btn-sm rounded-circle">
                                        <i class="bi bi-camera"></i>
                                    </label>
                                </div>
                                <input type="file" class="form-control d-none" id="profile_image" name="profile_image" accept="image/*">
                                <small class="text-muted d-block mt-2">Click camera icon to change photo</small>
                            </div>

                            <!-- Basic Fields -->
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                               id="country" name="country" value="{{ old('country', $user->country ?? 'India') }}">
                                        @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <hr>
                        <h6 class="mb-3">Address Information</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="address" class="form-label">Street Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $user->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                       id="state" name="state" value="{{ old('state', $user->state) }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="pincode" class="form-label">Pincode</label>
                                <input type="text" class="form-control @error('pincode') is-invalid @enderror" 
                                       id="pincode" name="pincode" value="{{ old('pincode', $user->pincode) }}">
                                @error('pincode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Role-specific Fields -->
                        @if($user->role === 'teacher')
                            @include('dashboard.partials.teacher-profile-fields', ['profile' => $profileData['profile'] ?? null, 'subjects' => $subjects])
                        @elseif($user->role === 'institute')
                            @include('dashboard.partials.institute-profile-fields', ['profile' => $profileData['profile'] ?? null])
                        @elseif($user->role === 'student')
                            @include('dashboard.partials.student-profile-fields', ['profile' => $profileData['profile'] ?? null, 'subjects' => $subjects])
                        @endif
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Update Profile
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Password Update -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lock me-2"></i>Change Password
                    </h5>
                </div>
                <form method="POST" action="{{ route('dashboard.password.update') }}">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-key me-1"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Profile Completeness -->
            @if($user->role === 'student' && isset($profileData['profile']))
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check me-2"></i>Profile Completeness
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                            $completion = 0;
                            $fields = ['name', 'email', 'phone', 'city', 'state'];
                            $completed = 0;
                            foreach($fields as $field) {
                                if(!empty($user->$field)) $completed++;
                            }
                            if($profileData['profile']) {
                                if(!empty($profileData['profile']->current_class)) $completed++;
                                if(!empty($profileData['profile']->subjects_interested)) $completed++;
                                $fields[] = 'current_class';
                                $fields[] = 'subjects_interested';
                            }
                            $completion = round(($completed / count($fields)) * 100);
                        @endphp
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" style="width: {{ $completion }}%"></div>
                                </div>
                            </div>
                            <span class="ms-3 fw-bold">{{ $completion }}%</span>
                        </div>
                        
                        <small class="text-muted">
                            Complete your profile to get better teacher recommendations and improved search results.
                        </small>
                    </div>
                </div>
            @endif

            <!-- Account Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Account Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-2 text-sm">
                        <div class="col-6">
                            <strong>Role:</strong>
                        </div>
                        <div class="col-6">
                            <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                        </div>
                        <div class="col-6">
                            <strong>Member Since:</strong>
                        </div>
                        <div class="col-6">
                            {{ $user->created_at->format('M Y') }}
                        </div>
                        <div class="col-6">
                            <strong>Last Updated:</strong>
                        </div>
                        <div class="col-6">
                            {{ $user->updated_at->diffForHumans() }}
                        </div>
                        <div class="col-6">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-6">
                            <span class="badge bg-success">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile image preview
    document.getElementById('profile_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            // You can add password strength visualization here
        });
    }
});
</script>
@endpush 