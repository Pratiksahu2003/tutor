@extends('admin.layout')

@section('title', 'Blog Posts Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">CMS</a></li>
    <li class="breadcrumb-item active">Blog</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-blog me-2"></i>
                    Blog Posts Management
                </h1>
                <p class="text-muted">Manage website blog posts and articles</p>
            </div>
            <div>
                <a href="{{ route('admin.cms.blog.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create New Post
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Blog Posts List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Blog Posts</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts ?? [] as $post)
                            <tr>
                                <td><strong>{{ $post->title }}</strong></td>
                                <td>{{ $post->author->name ?? 'System' }}</td>
                                <td>
                                    @switch($post->status)
                                        @case('published')
                                            <span class="badge bg-success">Published</span>
                                            @break
                                        @case('draft')
                                            <span class="badge bg-warning">Draft</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($post->status) }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $post->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.cms.blog.show', $post) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.cms.blog.edit', $post) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.cms.blog.destroy', $post) }}" class="d-inline">
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
                                <td colspan="5" class="text-center">
                                    <p class="text-muted">No blog posts found. <a href="{{ route('admin.cms.blog.create') }}">Create one now</a></p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if(isset($posts) && $posts instanceof \Illuminate\Pagination\LengthAwarePaginator && $posts->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $posts->appends(request()->query())->links() }}
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
        if (!confirm('Are you sure you want to delete this blog post?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush 