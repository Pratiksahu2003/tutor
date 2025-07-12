@extends('admin.layout')

@section('title', 'CMS Pages Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">CMS</a></li>
    <li class="breadcrumb-item active">Pages</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-file-alt me-2"></i>
                    CMS Pages Management
                </h1>
                <p class="text-muted">Manage website pages and content</p>
            </div>
            <div>
                <a href="{{ route('admin.cms.pages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create New Page
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.cms.pages.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search pages..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="private" {{ request('status') == 'private' ? 'selected' : '' }}>Private</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="page_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="page" {{ request('page_type') == 'page' ? 'selected' : '' }}>Page</option>
                            <option value="landing" {{ request('page_type') == 'landing' ? 'selected' : '' }}>Landing</option>
                            <option value="about" {{ request('page_type') == 'about' ? 'selected' : '' }}>About</option>
                            <option value="contact" {{ request('page_type') == 'contact' ? 'selected' : '' }}>Contact</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.cms.pages.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Pages List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Pages</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Author</th>
                                <th>Children</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pages as $page)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($page->featured_image)
                                            <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-file-alt text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $page->title }}</strong>
                                            @if($page->is_homepage)
                                                <span class="badge bg-warning ms-1">Homepage</span>
                                            @endif
                                            @if($page->featured)
                                                <span class="badge bg-info ms-1">Featured</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code>{{ $page->slug }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($page->page_type) }}</span>
                                </td>
                                <td>
                                    @switch($page->status)
                                        @case('published')
                                            <span class="badge bg-success">Published</span>
                                            @break
                                        @case('draft')
                                            <span class="badge bg-warning">Draft</span>
                                            @break
                                        @case('private')
                                            <span class="badge bg-danger">Private</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($page->status) }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    {{ $page->author->name ?? 'System' }}
                                </td>
                                <td>
                                    @if($page->children_count > 0)
                                        <span class="badge bg-primary">{{ $page->children_count }} children</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $page->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.cms.pages.show', $page) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.cms.pages.edit', $page) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($page->status !== 'published')
                                            <form method="POST" action="{{ route('admin.cms.pages.publish', $page) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if(!$page->is_homepage)
                                            <form method="POST" action="{{ route('admin.cms.pages.destroy', $page) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <p class="text-muted">No pages found. <a href="{{ route('admin.cms.pages.create') }}">Create one now</a></p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($pages->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $pages->appends(request()->query())->links() }}
                    </div>
                @endif
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
        if (!confirm('Are you sure you want to delete this page?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush 