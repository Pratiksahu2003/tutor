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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Teachers</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover data-table">
                        <thead>
                            <tr>
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
                            @forelse($teachers ?? \App\Models\TeacherProfile::with('user')->get() as $teacher)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <span class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                  style="width: 40px; height: 40px;">
                                                {{ substr($teacher->user->name ?? 'T', 0, 2) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $teacher->user->name ?? 'Unknown' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $teacher->user->email ?? 'No email' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $teacher->specialization ?? 'Not specified' }}</td>
                                <td>{{ $teacher->experience_years ?? 0 }} years</td>
                                <td>₹{{ $teacher->hourly_rate ?? 0 }}/hr</td>
                                <td>{{ $teacher->total_students ?? 0 }}</td>
                                <td>
                                    <span class="badge bg-{{ $teacher->verified ? 'success' : 'warning' }}">
                                        {{ $teacher->verified ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $teacher->user->is_active ?? false ? 'success' : 'secondary' }}">
                                        {{ $teacher->user->is_active ?? false ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if(!$teacher->verified)
                                        <form method="POST" action="{{ route('admin.teachers.verify', $teacher) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger delete-btn">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <p class="text-muted">No teachers found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 