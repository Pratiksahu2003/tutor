@extends('layouts.dashboard')

@section('title', 'Notification Settings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Notification Settings</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Notification Preferences</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.settings.notifications.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="email_notifications" value="1" 
                                           {{ ($teacherProfile->email_notifications ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label">Email Notifications</label>
                                </div>
                                <small class="text-muted">Receive notifications via email</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="sms_notifications" value="1" 
                                           {{ ($teacherProfile->sms_notifications ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label">SMS Notifications</label>
                                </div>
                                <small class="text-muted">Receive notifications via SMS</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="push_notifications" value="1" 
                                           {{ ($teacherProfile->push_notifications ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label">Push Notifications</label>
                                </div>
                                <small class="text-muted">Receive push notifications</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="booking_notifications" value="1" 
                                           {{ ($teacherProfile->booking_notifications ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label">Booking Notifications</label>
                                </div>
                                <small class="text-muted">Get notified about new bookings</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="payment_notifications" value="1" 
                                           {{ ($teacherProfile->payment_notifications ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label">Payment Notifications</label>
                                </div>
                                <small class="text-muted">Get notified about payments</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="marketing_emails" value="1" 
                                           {{ ($teacherProfile->marketing_emails ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label">Marketing Emails</label>
                                </div>
                                <small class="text-muted">Receive promotional emails</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Notification Settings</button>
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
                        <a href="{{ route('teacher.settings.preferences') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-sliders me-1"></i>Preferences
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 