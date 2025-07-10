@extends('layouts.app')

@section('title', 'Search Results' . ($searchTerm ? ' for "' . $searchTerm . '"' : ''))
@section('meta_description', 'Search for teachers, institutes, and subjects. Find the perfect learning partner.')

@section('content')
<div class="search-page-wrapper">
    <!-- Search Header -->
    <section class="search-header py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="search-form-container">
                        <form action="{{ route('search.index') }}" method="GET" class="advanced-search-form">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Search</label>
                                    <input type="text" name="q" class="form-control form-control-lg" 
                                           value="{{ $searchTerm }}" placeholder="What are you looking for?">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Location</label>
                                    <input type="text" name="location" class="form-control form-control-lg" 
                                           value="{{ $location }}" placeholder="City or State">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Type</label>
                                    <select name="type" class="form-select form-select-lg">
                                        <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All</option>
                                        <option value="teachers" {{ $type === 'teachers' ? 'selected' : '' }}>Teachers</option>
                                        <option value="institutes" {{ $type === 'institutes' ? 'selected' : '' }}>Institutes</option>
                                        <option value="subjects" {{ $type === 'subjects' ? 'selected' : '' }}>Subjects</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-search me-2"></i>Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Results -->
    <section class="search-results py-5">
        <div class="container">
            @if($searchTerm)
                <div class="results-header mb-4">
                    <h2 class="h3 mb-2">Search Results for "{{ $searchTerm }}"</h2>
                    <p class="text-muted">Found {{ $totalResults }} results</p>
                </div>

                @if($totalResults > 0)
                    <!-- Teachers Results -->
                    @if($results['teachers']->count() > 0)
                        <div class="results-section mb-5">
                            <div class="section-header d-flex justify-content-between align-items-center mb-4">
                                <h3 class="h4 mb-0">Teachers ({{ $results['teachers']->count() }})</h3>
                                <a href="{{ route('teachers.index', ['search' => $searchTerm, 'location' => $location]) }}" 
                                   class="btn btn-outline-primary">View All Teachers</a>
                            </div>
                            
                            <div class="row g-4">
                                @foreach($results['teachers'] as $teacher)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="teacher-card card h-100 border-0 shadow-sm">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-start mb-3">
                                                    <img src="{{ $teacher['avatar'] }}" alt="{{ $teacher['name'] }}" 
                                                         class="rounded-circle me-3" width="60" height="60" style="object-fit: cover;">
                                                    <div class="flex-grow-1">
                                                        <h5 class="card-title mb-1">{{ $teacher['name'] }}</h5>
                                                        <p class="text-primary mb-1">{{ $teacher['title'] }}</p>
                                                        @if($teacher['location'])
                                                            <p class="text-muted small mb-0">
                                                                <i class="fas fa-map-marker-alt me-1"></i>{{ $teacher['location'] }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="teacher-stats mb-3">
                                                    <div class="row text-center">
                                                        <div class="col-4">
                                                            <div class="rating">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <i class="fas fa-star {{ $i <= $teacher['rating'] ? 'text-warning' : 'text-muted' }} small"></i>
                                                                @endfor
                                                                <div class="mt-1">
                                                                    <small class="text-muted">{{ $teacher['rating'] }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <strong class="d-block">{{ $teacher['experience'] }}+</strong>
                                                            <small class="text-muted">Years</small>
                                                        </div>
                                                        <div class="col-4">
                                                            @if($teacher['hourly_rate'])
                                                                <strong class="d-block">₹{{ number_format($teacher['hourly_rate']) }}</strong>
                                                                <small class="text-muted">Per Hour</small>
                                                            @else
                                                                <strong class="d-block">-</strong>
                                                                <small class="text-muted">Rate</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <a href="{{ $teacher['url'] }}" class="btn btn-primary btn-sm w-100">
                                                    View Profile
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Institutes Results -->
                    @if($results['institutes']->count() > 0)
                        <div class="results-section mb-5">
                            <div class="section-header d-flex justify-content-between align-items-center mb-4">
                                <h3 class="h4 mb-0">Institutes ({{ $results['institutes']->count() }})</h3>
                                <a href="{{ route('institutes.index', ['search' => $searchTerm, 'location' => $location]) }}" 
                                   class="btn btn-outline-primary">View All Institutes</a>
                            </div>
                            
                            <div class="row g-4">
                                @foreach($results['institutes'] as $institute)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="institute-card card h-100 border-0 shadow-sm">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-start mb-3">
                                                    <img src="{{ $institute['logo'] }}" alt="{{ $institute['name'] }}" 
                                                         class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                                    <div class="flex-grow-1">
                                                        <h5 class="card-title mb-1">{{ $institute['name'] }}</h5>
                                                        <p class="text-primary mb-1">{{ $institute['title'] }}</p>
                                                        @if($institute['location'])
                                                            <p class="text-muted small mb-0">
                                                                <i class="fas fa-map-marker-alt me-1"></i>{{ $institute['location'] }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="institute-stats mb-3">
                                                    <div class="row text-center">
                                                        <div class="col-4">
                                                            <div class="rating">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <i class="fas fa-star {{ $i <= $institute['rating'] ? 'text-warning' : 'text-muted' }} small"></i>
                                                                @endfor
                                                                <div class="mt-1">
                                                                    <small class="text-muted">{{ $institute['rating'] }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <strong class="d-block">{{ number_format($institute['total_students']) }}+</strong>
                                                            <small class="text-muted">Students</small>
                                                        </div>
                                                        <div class="col-4">
                                                            @if($institute['established_year'])
                                                                <strong class="d-block">{{ $institute['established_year'] }}</strong>
                                                                <small class="text-muted">Est.</small>
                                                            @else
                                                                <strong class="d-block">-</strong>
                                                                <small class="text-muted">Year</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <a href="{{ $institute['url'] }}" class="btn btn-primary btn-sm w-100">
                                                    View Institute
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Subjects Results -->
                    @if($results['subjects']->count() > 0)
                        <div class="results-section mb-5">
                            <div class="section-header mb-4">
                                <h3 class="h4 mb-0">Subjects ({{ $results['subjects']->count() }})</h3>
                            </div>
                            
                            <div class="row g-3">
                                @foreach($results['subjects'] as $subject)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <a href="{{ $subject['url'] }}" class="subject-link text-decoration-none">
                                            <div class="subject-card card h-100 border-0 bg-light">
                                                <div class="card-body text-center p-3">
                                                    <h6 class="card-title mb-2">{{ $subject['name'] }}</h6>
                                                    <p class="text-muted small mb-0">{{ $subject['teachers_count'] }} teachers</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <!-- No Results -->
                    <div class="no-results text-center py-5">
                        <div class="no-results-icon mb-4">
                            <i class="fas fa-search fa-3x text-muted"></i>
                        </div>
                        <h3 class="h4 mb-3">No results found for "{{ $searchTerm }}"</h3>
                        <p class="text-muted mb-4">Try adjusting your search terms or browse popular categories below.</p>
                        
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="suggestions">
                                    <h5 class="mb-3">Popular Subjects</h5>
                                    <div class="row g-2">
                                        @foreach($popularSubjects as $subject)
                                            <div class="col-md-3 col-sm-4 col-6">
                                                <a href="{{ route('teachers.index', ['subject' => $subject->slug]) }}" 
                                                   class="btn btn-outline-secondary btn-sm w-100">{{ $subject->name }}</a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- Initial Search Page -->
                <div class="search-welcome text-center py-5">
                    <div class="search-welcome-icon mb-4">
                        <i class="fas fa-search fa-4x text-primary"></i>
                    </div>
                    <h2 class="h3 mb-3">Find Your Perfect Learning Partner</h2>
                    <p class="text-muted mb-5">Search for qualified teachers, reputable institutes, or explore subjects that interest you.</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="quick-search-card card border-0 shadow-sm h-100">
                                <div class="card-body text-center p-4">
                                    <div class="quick-search-icon mb-3">
                                        <i class="fas fa-chalkboard-teacher fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Find Teachers</h5>
                                    <p class="card-text text-muted">Connect with verified educators in your area</p>
                                    <a href="{{ route('teachers.index') }}" class="btn btn-primary">Browse Teachers</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="quick-search-card card border-0 shadow-sm h-100">
                                <div class="card-body text-center p-4">
                                    <div class="quick-search-icon mb-3">
                                        <i class="fas fa-university fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Find Institutes</h5>
                                    <p class="card-text text-muted">Discover top-rated educational institutions</p>
                                    <a href="{{ route('institutes.index') }}" class="btn btn-primary">Browse Institutes</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Popular Subjects -->
                    <div class="popular-subjects mt-5">
                        <h4 class="mb-4">Popular Subjects</h4>
                        <div class="row g-3">
                            @foreach($popularSubjects as $subject)
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                    <a href="{{ route('teachers.index', ['subject' => $subject->slug]) }}" 
                                       class="subject-link text-decoration-none">
                                        <div class="subject-card card border-0 bg-light text-center">
                                            <div class="card-body p-3">
                                                <h6 class="card-title mb-1">{{ $subject->name }}</h6>
                                                <small class="text-muted">{{ $subject->teacherProfiles()->count() }} teachers</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.search-form-container {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.teacher-card:hover,
.institute-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    transition: all 0.3s ease;
}

.subject-link:hover .subject-card {
    background-color: #e3f2fd !important;
}

.quick-search-card:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease;
}

.search-welcome-icon,
.quick-search-icon {
    margin-bottom: 1rem;
}

.no-results-icon {
    opacity: 0.5;
}

.rating .fas.fa-star {
    font-size: 0.8rem;
}

@media (max-width: 768px) {
    .search-form-container {
        padding: 1rem;
    }
    
    .advanced-search-form .col-md-3 {
        margin-bottom: 1rem;
    }
}
</style>
@endpush 