@extends('admin.layout')

@section('title', 'Permissions Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Permissions</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-key me-2"></i>
                    Permissions Management
                </h1>
                <p class="text-muted">Manage system permissions</p>
            </div>
            <div>
                <a href="{{ route('admin.permissions.initialize') }}" class="btn btn-warning me-2">
                    <i class="fas fa-sync me-2"></i>Initialize Default Permissions
                </a>
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Permission
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Permissions</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Category</th>
                                <th>Module</th>
                                <th>Description</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permissions ?? \App\Models\Permission::all() as $permission)
                            <tr>
                                <td>{{ $permission->id }}</td>
                                <td>
                                    <strong>{{ $permission->name }}</strong>
                                </td>
                                <td>
                                    <code>{{ $permission->slug }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $permission->category ?? 'General' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $permission->module ?? 'System' }}</span>
                                </td>
                                <td>{{ Str::limit($permission->description ?? '', 50) }}</td>
                                <td>{{ $permission->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" class="d-inline">
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
                                    <p class="text-muted">No permissions found. <a href="{{ route('admin.permissions.initialize') }}">Initialize default permissions</a></p>
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