@extends('layouts.dashboard')

@section('title', 'Preferences')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Preferences</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Account Preferences</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.settings.preferences.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Language</label>
                                <select class="form-select" name="language">
                                    <option value="en" {{ ($teacherProfile->language ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="hi" {{ ($teacherProfile->language ?? 'en') == 'hi' ? 'selected' : '' }}>Hindi</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Timezone</label>
                                <select class="form-select" name="timezone">
                                    <option value="Asia/Kolkata" {{ ($teacherProfile->timezone ?? 'Asia/Kolkata') == 'Asia/Kolkata' ? 'selected' : '' }}>India (IST)</option>
                                    <option value="UTC" {{ ($teacherProfile->timezone ?? 'Asia/Kolkata') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Date Format</label>
                                <select class="form-select" name="date_format">
                                    <option value="Y-m-d" {{ ($teacherProfile->date_format ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                    <option value="d/m/Y" {{ ($teacherProfile->date_format ?? 'Y-m-d') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                    <option value="m/d/Y" {{ ($teacherProfile->date_format ?? 'Y-m-d') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Time Format</label>
                                <select class="form-select" name="time_format">
                                    <option value="12" {{ ($teacherProfile->time_format ?? '12') == '12' ? 'selected' : '' }}>12 Hour</option>
                                    <option value="24" {{ ($teacherProfile->time_format ?? '12') == '24' ? 'selected' : '' }}>24 Hour</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Preferences</button>
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
                        <a href="{{ route('teacher.profile.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-person me-1"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 