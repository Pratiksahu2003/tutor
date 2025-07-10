@extends('layouts.app')

@section('title', $page->meta_title ?? 'Privacy Policy')
@section('meta_description', $page->meta_description ?? 'Learn about how we protect your privacy')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
                </ol>
            </nav>

            <h1 class="mb-4">{{ $page->title ?? 'Privacy Policy' }}</h1>
            
            <div class="content">
                {!! $page->content ?? '' !!}
            </div>
        </div>
    </div>
</div>
@endsection 