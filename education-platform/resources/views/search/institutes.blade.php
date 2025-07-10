@extends('layouts.app')

@section('title', 'Find Institutes - Education Platform')
@section('meta_description', 'Browse and search for reputable educational institutes. Find the best institutes near you for quality education.')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="h2 mb-4">Find Institutes</h1>
            <p class="text-muted mb-4">Discover reputable educational institutes that can provide quality education.</p>
            
            @if(isset($totalResults))
                <p class="text-muted">Found {{ $totalResults }} institutes</p>
            @endif
        </div>
    </div>
    
    <div class="row">
        @if(isset($institutes) && $institutes->count() > 0)
            @foreach($institutes as $institute)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <img src="{{ $institute->logo ?? asset('images/default-institute.png') }}" 
                                     alt="{{ $institute->name }}" 
                                     class="rounded mb-3" 
                                     width="80" height="80">
                                <h5 class="card-title">{{ $institute->name }}</h5>
                                <p class="text-muted">{{ $institute->city ?? 'Location not specified' }}</p>
                            </div>
                            
                            @if($institute->subjects && $institute->subjects->count() > 0)
                                <div class="mb-3">
                                    <small class="text-muted">Subjects offered:</small><br>
                                    @foreach($institute->subjects->take(3) as $subject)
                                        <span class="badge bg-light text-dark me-1">{{ $subject->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= ($institute->rating ?? 5) ? '-fill' : '' }} text-warning"></i>
                                @endfor
                                <span class="text-muted ms-2">({{ $institute->reviews_count ?? 0 }} reviews)</span>
                            </div>
                            
                            @if($institute->teachers_count ?? 0 > 0)
                                <p class="text-muted mb-3">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $institute->teachers_count }} teachers
                                </p>
                            @endif
                            
                            <a href="{{ route('institutes.show', $institute->slug ?? $institute->id) }}" class="btn btn-primary w-100">View Institute</a>
                        </div>
                    </div>
                </div>
            @endforeach
            
            @if(method_exists($institutes, 'links'))
                <div class="col-12">
                    {{ $institutes->links() }}
                </div>
            @endif
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-building display-1 text-muted"></i>
                    <h3 class="mt-3">No institutes found</h3>
                    <p class="text-muted">Try adjusting your search criteria or browse all institutes.</p>
                    <a href="{{ route('institutes.index') }}" class="btn btn-primary">Browse All Institutes</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 