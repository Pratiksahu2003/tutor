@extends('admin.layout')

@section('title', 'Menu Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">CMS</a></li>
    <li class="breadcrumb-item active">Menus</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-bars me-2"></i>
                    Menu Management
                </h1>
                <p class="text-muted">Manage website navigation menus</p>
            </div>
            <div>
                <a href="{{ route('admin.cms.menus.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create New Menu
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Menus</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus ?? [] as $menu)
                            <tr>
                                <td>
                                    <strong>{{ $menu->name }}</strong>
                                    @if($menu->is_active)
                                        <span class="badge bg-success ms-1">Active</span>
                                    @endif
                                </td>
                                <td>{{ $menu->location ?? 'Not assigned' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $menu->items_count ?? 0 }} items</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $menu->is_active ? 'success' : 'secondary' }}">
                                        {{ $menu->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $menu->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.cms.menus.edit', $menu) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.cms.menus.destroy', $menu) }}" class="d-inline">
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
                                <td colspan="6" class="text-center">
                                    <p class="text-muted">No menus found. <a href="{{ route('admin.cms.menus.create') }}">Create one now</a></p>
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

@push('scripts')
<script>
$(document).ready(function() {
    // Delete confirmation
    $('.delete-btn').click(function(e) {
        if (!confirm('Are you sure you want to delete this menu?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush 