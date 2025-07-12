@extends('layouts.dashboard')

@section('title', 'Privacy Settings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Privacy Settings</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Privacy Preferences</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.settings.privacy.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Profile Visibility</label>
                                <select class="form-select" name="profile_visibility">
                                    <option value="public" {{ ($teacherProfile->profile_visibility ?? 'public') == 'public' ? 'selected' : '' }}>Public</option>
                                    <option value="private" {{ ($teacherProfile->profile_visibility ?? 'public') == 'private' ? 'selected' : '' }}>Private</option>
                                </select>
                                <small class="text-muted">Control who can see your profile</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="show_contact_info" value="1" 
                                           {{ ($teacherProfile->show_contact_info ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label">Show Contact Information</label>
                                </div>
                                <small class="text-muted">Display your contact details publicly</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="show_earnings" value="1" 
                                           {{ ($teacherProfile->show_earnings ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label">Show Earnings</label>
                                </div>
                                <small class="text-muted">Display your earnings publicly</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="show_schedule" value="1" 
                                           {{ ($teacherProfile->show_schedule ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label">Show Schedule</label>
                                </div>
                                <small class="text-muted">Display your availability schedule</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="allow_messages" value="1" 
                                           {{ ($teacherProfile->allow_messages ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label">Allow Messages</label>
                                </div>
                                <small class="text-muted">Allow students to send you messages</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Privacy Settings</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.settings') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-gear me-1"></i>Back to Settings
                        </a>
                        <a href="{{ route('teacher.settings.notifications') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-bell me-1"></i>Notification Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 