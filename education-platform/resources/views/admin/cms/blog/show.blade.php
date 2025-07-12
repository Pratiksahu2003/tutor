@extends('admin.layout')

@section('title', $post->title)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.cms.blog.index') }}">Blog Posts</a></li>
    <li class="breadcrumb-item active">{{ $post->title }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-blog me-2"></i>
                    {{ $post->title }}
                </h1>
                <p class="text-muted">Blog Post Details</p>
            </div>
            <div>
                <a href="{{ route('admin.cms.blog.edit', $post) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-2"></i>Edit Post
                </a>
                <a href="{{ route('admin.cms.blog.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Blog Posts
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Blog Post Content -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Post Content</h5>
            </div>
            <div class="card-body">
                @if($post->featured_image)
                    <div class="mb-3">
                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="img-fluid rounded">
                    </div>
                @endif

                @if($post->excerpt)
                    <div class="mb-3">
                        <h6>Excerpt:</h6>
                        <p class="text-muted">{{ $post->excerpt }}</p>
                    </div>
                @endif

                <div class="mb-3">
                    <h6>Content:</h6>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Post Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Post Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @switch($post->status)
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
                                    <span class="badge bg-secondary">{{ ucfirst($post->status) }}</span>
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Author:</strong></td>
                        <td>{{ $post->author->name ?? 'System' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Slug:</strong></td>
                        <td><code>{{ $post->slug }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $post->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated:</strong></td>
                        <td>{{ $post->updated_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @if($post->published_at)
                        <tr>
                            <td><strong>Published:</strong></td>
                            <td>{{ $post->published_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @endif
                    @if($post->reading_time)
                        <tr>
                            <td><strong>Reading Time:</strong></td>
                            <td>{{ $post->reading_time }} min read</td>
                        </tr>
                    @endif
                    @if($post->views)
                        <tr>
                            <td><strong>Views:</strong></td>
                            <td>{{ $post->views }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Post Options -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Post Options</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    @if($post->featured)
                        <span class="badge bg-info me-1">Featured</span>
                    @endif
                    @if($post->allow_comments)
                        <span class="badge bg-success me-1">Comments</span>
                    @endif
                    @if($post->allow_ratings)
                        <span class="badge bg-success me-1">Ratings</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- SEO Information -->
        @if($post->meta_title || $post->meta_description || $post->meta_keywords)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">SEO Information</h5>
                </div>
                <div class="card-body">
                    @if($post->meta_title)
                        <div class="mb-2">
                            <strong>Meta Title:</strong><br>
                            <small class="text-muted">{{ $post->meta_title }}</small>
                        </div>
                    @endif
                    @if($post->meta_description)
                        <div class="mb-2">
                            <strong>Meta Description:</strong><br>
                            <small class="text-muted">{{ $post->meta_description }}</small>
                        </div>
                    @endif
                    @if($post->meta_keywords)
                        <div class="mb-2">
                            <strong>Meta Keywords:</strong><br>
                            <small class="text-muted">{{ $post->meta_keywords }}</small>
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
                    @if($post->status !== 'published')
                        <form method="POST" action="{{ route('admin.cms.blog.publish', $post) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i>Publish Post
                            </button>
                        </form>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.cms.blog.destroy', $post) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100 delete-btn">
                            <i class="fas fa-trash me-2"></i>Delete Post
                        </button>
                    </form>
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
        if (!confirm('Are you sure you want to delete this blog post?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush 