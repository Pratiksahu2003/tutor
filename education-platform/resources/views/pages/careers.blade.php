@extends('layouts.app')

@section('title', $page->meta_title ?? 'Careers')
@section('meta_description', $page->meta_description ?? 'Join our team and help transform education')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Careers</li>
                </ol>
            </nav>

            <h1 class="mb-4">{{ $page->title ?? 'Careers' }}</h1>
            
            <div class="content">
                {!! $page->content ?? '' !!}
            </div>
        </div>
    </div>
</div>
@endsection 