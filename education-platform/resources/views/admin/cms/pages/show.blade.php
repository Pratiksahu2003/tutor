@extends('admin.layout')

@section('title', $page->title)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.cms.pages.index') }}">CMS Pages</a></li>
    <li class="breadcrumb-item active">{{ $page->title }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-file-alt me-2"></i>
                    {{ $page->title }}
                </h1>
                <p class="text-muted">Page Details</p>
            </div>
            <div>
                <a href="{{ route('admin.cms.pages.edit', $page) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-2"></i>Edit Page
                </a>
                <a href="{{ route('admin.cms.pages.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Pages
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Page Content -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Page Content</h5>
            </div>
            <div class="card-body">
                @if($page->featured_image)
                    <div class="mb-3">
                        <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="img-fluid rounded">
                    </div>
                @endif

                @if($page->excerpt)
                    <div class="mb-3">
                        <h6>Excerpt:</h6>
                        <p class="text-muted">{{ $page->excerpt }}</p>
                    </div>
                @endif

                <div class="mb-3">
                    <h6>Content:</h6>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($page->content)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Page Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Page Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Status:</strong></td>
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
                    </tr>
                    <tr>
                        <td><strong>Type:</strong></td>
                        <td><span class="badge bg-info">{{ ucfirst($page->page_type) }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Slug:</strong></td>
                        <td><code>{{ $page->slug }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Author:</strong></td>
                        <td>{{ $page->author->name ?? 'System' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $page->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated:</strong></td>
                        <td>{{ $page->updated_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @if($page->parent)
                        <tr>
                            <td><strong>Parent:</strong></td>
                            <td><a href="{{ route('admin.cms.pages.show', $page->parent) }}">{{ $page->parent->title }}</a></td>
                        </tr>
                    @endif
                    @if($page->children->count() > 0)
                        <tr>
                            <td><strong>Children:</strong></td>
                            <td>{{ $page->children->count() }} pages</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Page Options -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Page Options</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    @if($page->featured)
                        <span class="badge bg-info me-1">Featured</span>
                    @endif
                    @if($page->is_homepage)
                        <span class="badge bg-warning me-1">Homepage</span>
                    @endif
                    @if($page->show_in_menu)
                        <span class="badge bg-primary me-1">In Menu</span>
                    @endif
                    @if($page->allow_comments)
                        <span class="badge bg-success me-1">Comments</span>
                    @endif
                    @if($page->allow_ratings)
                        <span class="badge bg-success me-1">Ratings</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- SEO Information -->
        @if($page->meta_title || $page->meta_description || $page->meta_keywords)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">SEO Information</h5>
                </div>
                <div class="card-body">
                    @if($page->meta_title)
                        <div class="mb-2">
                            <strong>Meta Title:</strong><br>
                            <small class="text-muted">{{ $page->meta_title }}</small>
                        </div>
                    @endif
                    @if($page->meta_description)
                        <div class="mb-2">
                            <strong>Meta Description:</strong><br>
                            <small class="text-muted">{{ $page->meta_description }}</small>
                        </div>
                    @endif
                    @if($page->meta_keywords)
                        <div class="mb-2">
                            <strong>Meta Keywords:</strong><br>
                            <small class="text-muted">{{ $page->meta_keywords }}</small>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($page->status !== 'published')
                        <form method="POST" action="{{ route('admin.cms.pages.publish', $page) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i>Publish Page
                            </button>
                        </form>
                    @endif
                    
                    @if(!$page->is_homepage)
                        <form method="POST" action="{{ route('admin.cms.pages.destroy', $page) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100 delete-btn">
                                <i class="fas fa-trash me-2"></i>Delete Page
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($page->children->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Child Pages</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($page->children as $child)
                            <tr>
                                <td>{{ $child->title }}</td>
                                <td>
                                    @switch($child->status)
                                        @case('published')
                                            <span class="badge bg-success">Published</span>
                                            @break
                                        @case('draft')
                                            <span class="badge bg-warning">Draft</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($child->status) }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $child->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.cms.pages.show', $child) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.cms.pages.edit', $child) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
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