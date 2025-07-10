@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? '')
@section('meta_keywords', $page->meta_keywords ?? '')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
                </ol>
            </nav>

            <h1 class="mb-4">{{ $page->title }}</h1>
            
            @if($page->featured_image)
                <img src="{{ asset($page->featured_image) }}" alt="{{ $page->title }}" class="img-fluid mb-4">
            @endif
            
            <div class="content">
                {!! $page->content !!}
            </div>
            
            @if($page->updated_at)
                <div class="text-muted mt-5">
                    <small>Last updated: {{ $page->updated_at->format('F d, Y') }}</small>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 