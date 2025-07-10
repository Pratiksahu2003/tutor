@extends('layouts.app')

@section('title', 'Find Teachers - Education Platform')
@section('meta_description', 'Browse and search for qualified teachers. Filter by subject, location, experience and find the perfect educator for your learning needs.')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="h2 mb-4">Find Teachers</h1>
            <p class="text-muted mb-4">Discover qualified educators who can help you achieve your learning goals.</p>
            
            @if(isset($totalResults))
                <p class="text-muted">Found {{ $totalResults }} teachers</p>
            @endif
        </div>
    </div>
    
    <div class="row">
        @if(isset($teachers) && $teachers->count() > 0)
            @foreach($teachers as $teacher)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <img src="{{ $teacher->user->avatar ?? asset('images/default-avatar.png') }}" 
                                 alt="{{ $teacher->user->name }}" 
                                 class="rounded-circle mb-3" 
                                 width="80" height="80">
                            <h5 class="card-title">{{ $teacher->user->name }}</h5>
                            <p class="text-muted">{{ $teacher->specialization ?? 'General Teacher' }}</p>
                            @if($teacher->subjects && $teacher->subjects->count() > 0)
                                <div class="mb-3">
                                    @foreach($teacher->subjects->take(3) as $subject)
                                        <span class="badge bg-light text-dark me-1">{{ $subject->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= ($teacher->rating ?? 5) ? '-fill' : '' }} text-warning"></i>
                                @endfor
                                <span class="text-muted ms-2">({{ $teacher->reviews_count ?? 0 }} reviews)</span>
                            </div>
                            <a href="{{ route('teachers.show', $teacher->slug ?? $teacher->id) }}" class="btn btn-primary">View Profile</a>
                        </div>
                    </div>
                </div>
            @endforeach
            
            @if(method_exists($teachers, 'links'))
                <div class="col-12">
                    {{ $teachers->links() }}
                </div>
            @endif
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h3 class="mt-3">No teachers found</h3>
                    <p class="text-muted">Try adjusting your search criteria or browse all teachers.</p>
                    <a href="{{ route('teachers.index') }}" class="btn btn-primary">Browse All Teachers</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 