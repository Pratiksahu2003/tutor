@extends('layouts.app')

@section('title', $page->meta_title ?? 'How It Works')
@section('meta_description', $page->meta_description ?? 'Learn how our platform works')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">How It Works</li>
                </ol>
            </nav>

            <h1 class="mb-4 text-center">{{ $page->title ?? 'How It Works' }}</h1>
            
            <div class="content">
                {!! $page->content ?? '' !!}
            </div>
        </div>
    </div>
</div>
@endsection 