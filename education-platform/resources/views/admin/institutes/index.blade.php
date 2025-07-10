@extends('admin.layout')

@section('title', 'Institutes Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Institutes</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-university me-2"></i>
                    Institutes Management
                </h1>
                <p class="text-muted">Manage educational institutes</p>
            </div>
            <div>
                <a href="{{ route('admin.institutes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Institute
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Institutes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover data-table">
                        <thead>
                            <tr>
                                <th>Institute</th>
                                <th>Location</th>
                                <th>Type</th>
                                <th>Students</th>
                                <th>Rating</th>
                                <th>Verification</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($institutes ?? \App\Models\Institute::with('user')->get() as $institute)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <span class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                  style="width: 40px; height: 40px;">
                                                {{ substr($institute->institute_name ?? 'I', 0, 2) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $institute->institute_name ?? 'Unknown' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $institute->user->email ?? 'No email' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $institute->city ?? 'N/A' }}
                                    @if($institute->state)
                                        , {{ $institute->state }}
                                    @endif
                                </td>
                                <td>{{ $institute->type ?? 'Institute' }}</td>
                                <td>{{ $institute->total_students ?? 0 }}</td>
                                <td>
                                    @if($institute->rating)
                                        <span class="badge bg-success">{{ $institute->rating }}/5</span>
                                    @else
                                        <span class="text-muted">No rating</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $institute->verified ? 'success' : 'warning' }}">
                                        {{ $institute->verified ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $institute->user->is_active ?? false ? 'success' : 'secondary' }}">
                                        {{ $institute->user->is_active ?? false ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if(!$institute->verified)
                                        <form method="POST" action="{{ route('admin.institutes.verify', $institute) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <a href="{{ route('admin.institutes.edit', $institute) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.institutes.destroy', $institute) }}" class="d-inline">
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
                                    <p class="text-muted">No institutes found.</p>
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