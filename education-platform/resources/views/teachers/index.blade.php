@extends('layouts.app')

@section('title', 'Find Qualified Teachers')
@section('meta_description', 'Browse verified teachers and tutors. Find the perfect educator for your learning needs.')

@section('content')
<div class="teachers-listing-page">
    <!-- Search Header -->
    <section class="search-header py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="h3 mb-3">Find Teachers</h1>
                    <form action="{{ route('teachers.index') }}" method="GET" class="search-form">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Search teachers...">
                            </div>
                            <div class="col-md-3">
                                <select name="subject" class="form-select">
                                    <option value="">All Subjects</option>
                                    @foreach($filterOptions['subjects'] as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="city" class="form-select">
                                    <option value="">All Cities</option>
                                    @foreach($filterOptions['cities'] as $city)
                                        <option value="{{ $city->city }}" {{ request('city') == $city->city ? 'selected' : '' }}>
                                            {{ $city->city }} ({{ $city->count }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Teachers Grid -->
    <section class="teachers-grid py-5">
        <div class="container">
            @if($teachers->count() > 0)
                <div class="row g-4">
                    @foreach($teachers as $teacher)
                        <div class="col-lg-4 col-md-6">
                            <div class="teacher-card card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="teacher-header d-flex align-items-start mb-3">
                                        <img src="{{ $teacher->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($teacher->user->name ?? 'Teacher') . '&size=120&background=random' }}" 
                                             alt="{{ $teacher->user->name ?? 'Teacher' }}" 
                                             class="teacher-avatar rounded-circle me-3" width="60" height="60">
                                        <div class="flex-grow-1">
                                            <h5 class="teacher-name mb-1">{{ $teacher->user->name ?? 'Teacher' }}</h5>
                                            <p class="teacher-subject text-primary mb-1">{{ $teacher->subject->name ?? $teacher->specialization ?? 'Teacher' }}</p>
                                            @if($teacher->city)
                                                <p class="teacher-location text-muted small mb-0">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $teacher->city }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="teacher-stats mb-3">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= ($teacher->rating ?? 4) ? 'text-warning' : 'text-muted' }} small"></i>
                                                    @endfor
                                                    <div class="mt-1">
                                                        <small class="text-muted">{{ $teacher->rating ?? 4.0 }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <strong class="d-block">{{ $teacher->experience_years ?? 0 }}+</strong>
                                                <small class="text-muted">Years</small>
                                            </div>
                                            <div class="col-4">
                                                @if($teacher->hourly_rate)
                                                    <strong class="d-block">₹{{ number_format($teacher->hourly_rate) }}</strong>
                                                    <small class="text-muted">Per Hour</small>
                                                @else
                                                    <strong class="d-block">-</strong>
                                                    <small class="text-muted">Rate</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($teacher->bio)
                                        <p class="teacher-bio text-muted small mb-3">{{ Str::limit($teacher->bio, 80) }}</p>
                                    @endif
                                    
                                    <div class="teacher-actions d-grid gap-2">
                                        <a href="{{ route('teachers.show', $teacher->slug ?: 'teacher-' . $teacher->id) }}" 
                                           class="btn btn-primary btn-sm">
                                            View Profile
                                        </a>
                                        @if($teacher->user->phone)
                                            <a href="tel:{{ $teacher->user->phone }}" class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-phone me-1"></i>Call
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="row mt-5">
                    <div class="col-12 d-flex justify-content-center">
                        {{ $teachers->links() }}
                    </div>
                </div>
            @else
                <div class="no-results text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h3>No teachers found</h3>
                    <p class="text-muted">Try adjusting your search criteria</p>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.teacher-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    transition: all 0.3s ease;
}

.teacher-avatar {
    object-fit: cover;
}

.rating .fas.fa-star {
    font-size: 0.8rem;
}

@media (max-width: 768px) {
    .search-form .col-md-2,
    .search-form .col-md-3,
    .search-form .col-md-4 {
        margin-bottom: 1rem;
    }
}
</style>
@endpush 