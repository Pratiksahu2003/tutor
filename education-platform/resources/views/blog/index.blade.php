@extends('layouts.app')

@section('title', 'Blog - Education Platform')
@section('meta_description', 'Read our latest articles about education, teaching tips, learning strategies and more educational insights.')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="h2 mb-4">Education Blog</h1>
            <p class="text-muted mb-5">Stay updated with the latest educational insights, teaching tips, and learning strategies.</p>
        </div>
    </div>
    
    <div class="row">
        @if(isset($posts) && $posts->count() > 0)
            @foreach($posts as $post)
                <div class="col-lg-4 col-md-6 mb-4">
                    <article class="card h-100 shadow-sm">
                        @if($post->featured_image)
                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                                </small>
                                @if($post->views ?? 0 > 0)
                                    <small class="text-muted ms-auto">
                                        <i class="bi bi-eye me-1"></i>
                                        {{ $post->views }} views
                                    </small>
                                @endif
                            </div>
                            <h5 class="card-title">
                                <a href="{{ route('blog.show', $post->slug ?? $post->id) }}" class="text-decoration-none">
                                    {{ $post->title }}
                                </a>
                            </h5>
                            <p class="card-text text-muted">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('blog.show', $post->slug ?? $post->id) }}" class="btn btn-outline-primary btn-sm">Read More</a>
                        </div>
                    </article>
                </div>
            @endforeach
            
            @if(method_exists($posts, 'links'))
                <div class="col-12">
                    {{ $posts->links() }}
                </div>
            @endif
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-journal-text display-1 text-muted"></i>
                    <h3 class="mt-3">No blog posts yet</h3>
                    <p class="text-muted">Check back later for educational insights and articles.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 