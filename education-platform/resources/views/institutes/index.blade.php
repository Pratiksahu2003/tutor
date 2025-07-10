@extends('layouts.app')

@section('title', 'Find Educational Institutes')
@section('meta_description', 'Browse verified educational institutes and schools. Find the perfect institution for your learning needs.')

@section('content')
<div class="institutes-listing-page">
    <!-- Search Header -->
    <section class="search-header py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="h3 mb-3">Find Institutes</h1>
                    <form action="{{ route('institutes.index') }}" method="GET" class="search-form">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Search institutes...">
                            </div>
                            <div class="col-md-3">
                                <select name="city" class="form-select">
                                    <option value="">All Cities</option>
                                    @if(isset($filterOptions['cities']))
                                        @foreach($filterOptions['cities'] as $city)
                                            <option value="{{ $city->city }}" {{ request('city') == $city->city ? 'selected' : '' }}>
                                                {{ $city->city }} ({{ $city->count }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="institute_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="school" {{ request('institute_type') == 'school' ? 'selected' : '' }}>School</option>
                                    <option value="college" {{ request('institute_type') == 'college' ? 'selected' : '' }}>College</option>
                                    <option value="coaching" {{ request('institute_type') == 'coaching' ? 'selected' : '' }}>Coaching Center</option>
                                    <option value="university" {{ request('institute_type') == 'university' ? 'selected' : '' }}>University</option>
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

    <!-- Institutes Grid -->
    <section class="institutes-grid py-5">
        <div class="container">
            @if(isset($institutes) && $institutes->count() > 0)
                <div class="row g-4">
                    @foreach($institutes as $institute)
                        <div class="col-lg-4 col-md-6">
                            <div class="institute-card card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="institute-header d-flex align-items-start mb-3">
                                        <img src="{{ $institute->logo ?: 'https://ui-avatars.com/api/?name=' . urlencode($institute->institute_name ?? 'Institute') . '&size=120&background=random' }}" 
                                             alt="{{ $institute->institute_name ?? 'Institute' }}" 
                                             class="institute-logo rounded me-3" width="60" height="60">
                                        <div class="flex-grow-1">
                                            <h5 class="institute-name mb-1">{{ $institute->institute_name ?? 'Institute' }}</h5>
                                            <p class="institute-type text-primary mb-1">{{ ucfirst($institute->institute_type ?? 'Educational Institute') }}</p>
                                            @if($institute->city)
                                                <p class="institute-location text-muted small mb-0">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $institute->city }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="institute-stats mb-3">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= ($institute->rating ?? 4) ? 'text-warning' : 'text-muted' }} small"></i>
                                                    @endfor
                                                    <div class="mt-1">
                                                        <small class="text-muted">{{ $institute->rating ?? 4.0 }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <strong class="d-block">{{ number_format($institute->total_students ?? 0) }}</strong>
                                                <small class="text-muted">Students</small>
                                            </div>
                                            <div class="col-4">
                                                @if($institute->established_year)
                                                    <strong class="d-block">{{ $institute->established_year }}</strong>
                                                    <small class="text-muted">Est.</small>
                                                @else
                                                    <strong class="d-block">-</strong>
                                                    <small class="text-muted">Year</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($institute->description)
                                        <p class="institute-description text-muted small mb-3">{{ Str::limit($institute->description, 80) }}</p>
                                    @endif
                                    
                                    <div class="institute-actions d-grid gap-2">
                                        <a href="{{ route('institutes.show', $institute->slug ?: 'institute-' . $institute->id) }}" 
                                           class="btn btn-primary btn-sm">
                                            View Institute
                                        </a>
                                        @if($institute->user && $institute->user->phone)
                                            <a href="tel:{{ $institute->user->phone }}" class="btn btn-outline-success btn-sm">
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
                        {{ $institutes->links() }}
                    </div>
                </div>
            @else
                <div class="no-results text-center py-5">
                    <i class="fas fa-university fa-3x text-muted mb-3"></i>
                    <h3>No institutes found</h3>
                    <p class="text-muted">Try adjusting your search criteria</p>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.institute-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    transition: all 0.3s ease;
}

.institute-logo {
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