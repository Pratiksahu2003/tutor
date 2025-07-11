@extends('admin.layout')

@section('title', 'Analytics & Reports')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Analytics & Reports</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="page-title">
            <i class="bi bi-graph-up me-2"></i>
            Analytics & Reports
        </h1>
        <p class="text-muted mb-4">Platform-wide analytics, user activity, and performance reports will be shown here.</p>
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Analytics dashboard coming soon. You can customize this page with charts and reports as needed.
        </div>
    </div>
</div>
@endsection 