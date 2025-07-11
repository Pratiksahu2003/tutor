@extends('admin.layout')

@section('title', 'Teachers Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Teachers</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Teachers Management
                </h1>
                <p class="text-muted">Manage teacher profiles and verifications</p>
            </div>
            <div>
                <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Teacher
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.teachers.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search teachers..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="verified" class="form-select">
                            <option value="">All Status</option>
                            <option value="verified" {{ request('verified') === 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="unverified" {{ request('verified') === 'unverified' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="teaching_mode" class="form-select">
                            <option value="">All Modes</option>
                            <option value="online" {{ request('teaching_mode') === 'online' ? 'selected' : '' }}>Online</option>
                            <option value="offline" {{ request('teaching_mode') === 'offline' ? 'selected' : '' }}>Offline</option>
                            <option value="both" {{ request('teaching_mode') === 'both' ? 'selected' : '' }}>Both</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="city" class="form-select">
                            <option value="">All Cities</option>
                            @foreach($cities ?? [] as $city)
                                <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Teachers ({{ $teachers->total() }})</h5>
                <div>
                    <button class="btn btn-success btn-sm" onclick="bulkVerify()">
                        <i class="fas fa-check me-1"></i>Bulk Verify
                    </button>
                    <button class="btn btn-warning btn-sm" onclick="bulkToggleStatus()">
                        <i class="fas fa-toggle-on me-1"></i>Toggle Status
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover data-table">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>Teacher</th>
                                <th>Specialization</th>
                                <th>Experience</th>
                                <th>Rate</th>
                                <th>Students</th>
                                <th>Verification</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_teachers[]" value="{{ $teacher->id }}" 
                                           class="form-check-input teacher-checkbox">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <span class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                  style="width: 40px; height: 40px;">
                                                {{ substr($teacher->name ?? 'T', 0, 2) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $teacher->name ?? 'Unknown' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $teacher->email ?? 'No email' }}</small>
                                            <br>
                                            <small class="text-muted">{{ $teacher->phone ?? 'No phone' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $teacher->teacherProfile->specialization ?? 'Not specified' }}</td>
                                <td>{{ $teacher->teacherProfile->experience_years ?? 0 }} years</td>
                                <td>₹{{ $teacher->teacherProfile->hourly_rate ?? 0 }}/hr</td>
                                <td>{{ $teacher->teacherProfile->total_students ?? 0 }}</td>
                                <td>
                                    <span class="badge bg-{{ $teacher->teacherProfile->verified ? 'success' : 'warning' }}">
                                        {{ $teacher->teacherProfile->verified ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $teacher->is_active ? 'success' : 'secondary' }}">
                                        {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if(!$teacher->teacherProfile->verified)
                                        <form method="POST" action="{{ route('admin.teachers.verify', $teacher) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Verify Teacher">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.teachers.show', $teacher) }}" class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger delete-btn" title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this teacher?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <p class="text-muted">No teachers found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $teachers->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Form -->
<form id="bulkActionsForm" method="POST" action="">
    @csrf
    <input type="hidden" name="action" id="bulkAction">
    <input type="hidden" name="teacher_ids" id="teacherIds">
</form>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select all functionality
    $('#selectAll').change(function() {
        $('.teacher-checkbox').prop('checked', $(this).is(':checked'));
    });

    // Update select all when individual checkboxes change
    $('.teacher-checkbox').change(function() {
        if (!$(this).is(':checked')) {
            $('#selectAll').prop('checked', false);
        } else {
            var total = $('.teacher-checkbox').length;
            var checked = $('.teacher-checkbox:checked').length;
            $('#selectAll').prop('checked', total === checked);
        }
    });
});

function bulkVerify() {
    var selected = $('.teacher-checkbox:checked').map(function() {
        return this.value;
    }).get();
    
    if (selected.length === 0) {
        alert('Please select teachers to verify.');
        return;
    }
    
    if (confirm('Are you sure you want to verify ' + selected.length + ' teacher(s)?')) {
        $('#bulkAction').val('verify');
        $('#teacherIds').val(selected.join(','));
        $('#bulkActionsForm').attr('action', '{{ route("admin.teachers.bulk-verify") }}');
        $('#bulkActionsForm').submit();
    }
}

function bulkToggleStatus() {
    var selected = $('.teacher-checkbox:checked').map(function() {
        return this.value;
    }).get();
    
    if (selected.length === 0) {
        alert('Please select teachers to toggle status.');
        return;
    }
    
    if (confirm('Are you sure you want to toggle status for ' + selected.length + ' teacher(s)?')) {
        $('#bulkAction').val('toggle_status');
        $('#teacherIds').val(selected.join(','));
        $('#bulkActionsForm').attr('action', '{{ route("admin.teachers.bulk-toggle-status") }}');
        $('#bulkActionsForm').submit();
    }
}
</script>
@endpush 