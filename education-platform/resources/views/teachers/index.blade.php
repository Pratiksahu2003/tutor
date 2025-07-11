@extends('layouts.app')

@section('title', 'Find Expert Teachers - Education Platform')
@section('meta_description', 'Browse and connect with verified teachers across all subjects. Find the perfect teacher for your learning needs.')
@section('meta_keywords', 'teachers, tutoring, education, find teachers, online teachers')

@section('content')

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Teachers</li>
                    </ol>
                </nav>
                
                <h1 class="page-title">Find Expert Teachers</h1>
                <p class="page-subtitle">
                    Connect with verified teachers across all subjects. Find the perfect teacher for your learning needs.
                </p>
                
                <div class="page-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($teachers->total()) }}+</span>
                        <span class="stat-label">Teachers</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($subjects->count()) }}+</span>
                        <span class="stat-label">Subjects</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($cities->count()) }}+</span>
                        <span class="stat-label">Cities</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 text-lg-end">
                <div class="header-actions">
                    <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Become a Teacher
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Advanced Search and Filters -->
<section class="search-filters-section">
    <div class="container">
        <div class="filters-card">
            <form action="{{ route('teachers.index') }}" method="GET" class="filters-form">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Subject</label>
                            <select name="subject" class="form-select">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->slug }}" {{ request('subject') == $subject->slug ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Location</label>
                            <select name="location" class="form-select">
                                <option value="">All Locations</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ request('location') == $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Experience</label>
                            <select name="experience" class="form-select">
                                <option value="">Any Experience</option>
                                <option value="0-1" {{ request('experience') == '0-1' ? 'selected' : '' }}>0-1 years</option>
                                <option value="1-3" {{ request('experience') == '1-3' ? 'selected' : '' }}>1-3 years</option>
                                <option value="3-5" {{ request('experience') == '3-5' ? 'selected' : '' }}>3-5 years</option>
                                <option value="5-10" {{ request('experience') == '5-10' ? 'selected' : '' }}>5-10 years</option>
                                <option value="10+" {{ request('experience') == '10+' ? 'selected' : '' }}>10+ years</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Budget</label>
                            <select name="budget" class="form-select">
                                <option value="">Any Budget</option>
                                <option value="0-500" {{ request('budget') == '0-500' ? 'selected' : '' }}>₹0 - ₹500/hr</option>
                                <option value="500-1000" {{ request('budget') == '500-1000' ? 'selected' : '' }}>₹500 - ₹1000/hr</option>
                                <option value="1000-2000" {{ request('budget') == '1000-2000' ? 'selected' : '' }}>₹1000 - ₹2000/hr</option>
                                <option value="2000+" {{ request('budget') == '2000+' ? 'selected' : '' }}>₹2000+/hr</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Mode</label>
                            <select name="mode" class="form-select">
                                <option value="">Any Mode</option>
                                <option value="online" {{ request('mode') == 'online' ? 'selected' : '' }}>Online</option>
                                <option value="offline" {{ request('mode') == 'offline' ? 'selected' : '' }}>Offline</option>
                                <option value="both" {{ request('mode') == 'both' ? 'selected' : '' }}>Both</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row g-3 mt-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Rating</label>
                            <select name="rating" class="form-select">
                                <option value="">Any Rating</option>
                                <option value="4+" {{ request('rating') == '4+' ? 'selected' : '' }}>4+ Stars</option>
                                <option value="4.5+" {{ request('rating') == '4.5+' ? 'selected' : '' }}>4.5+ Stars</option>
                                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Availability</label>
                            <select name="availability" class="form-select">
                                <option value="">Any Time</option>
                                <option value="morning" {{ request('availability') == 'morning' ? 'selected' : '' }}>Morning</option>
                                <option value="afternoon" {{ request('availability') == 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                                <option value="evening" {{ request('availability') == 'evening' ? 'selected' : '' }}>Evening</option>
                                <option value="weekend" {{ request('availability') == 'weekend' ? 'selected' : '' }}>Weekend</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Any Gender</option>
                                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Results Section -->
<section class="results-section">
    <div class="container">
        <div class="results-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="results-info">
                    <h3 class="results-title">
                        {{ $teachers->total() }} Teachers Found
                        @if(request()->has('subject') || request()->has('location'))
                            <span class="results-filters">
                                for 
                                @if(request('subject'))
                                    <span class="filter-tag">{{ $subjects->where('slug', request('subject'))->first()->name ?? request('subject') }}</span>
                                @endif
                                @if(request('location'))
                                    <span class="filter-tag">{{ request('location') }}</span>
                                @endif
                            </span>
                        @endif
                    </h3>
                </div>
                
                <div class="results-actions">
                    <div class="sort-dropdown">
                        <label class="form-label">Sort by:</label>
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="experience" {{ request('sort') == 'experience' ? 'selected' : '' }}>Most Experienced</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Lowest Price</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Highest Price</option>
                            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Recently Added</option>
                        </select>
                    </div>
                    
                    <div class="view-toggle">
                        <button type="button" class="btn btn-outline-secondary active" data-view="grid">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        @if($teachers->count() > 0)
            <div class="teachers-grid" id="teachers-container">
                @foreach($teachers as $teacher)
                    <div class="teacher-card">
                        <div class="teacher-header">
                            <div class="teacher-avatar-container">
                                <img src="{{ $teacher->avatar }}" alt="{{ $teacher->name }}" class="teacher-avatar">
                                @if($teacher->is_online)
                                    <span class="online-badge">
                                        <i class="fas fa-circle"></i> Online
                                    </span>
                                @endif
                            </div>
                            
                            <div class="teacher-actions">
                                <button class="btn btn-sm btn-outline-primary favorite-btn" data-teacher-id="{{ $teacher->id }}">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary share-btn" data-teacher-id="{{ $teacher->id }}">
                                    <i class="fas fa-share"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="teacher-content">
                            <div class="teacher-info">
                                <h5 class="teacher-name">
                                    <a href="{{ route('teachers.show', $teacher->slug) }}">{{ $teacher->name }}</a>
                                </h5>
                                <p class="teacher-subject">{{ $teacher->subject }}</p>
                                
                                <div class="teacher-rating">
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $teacher->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="rating-text">{{ $teacher->rating ?? 4.0 }} ({{ $teacher->total_reviews ?? 0 }} reviews)</span>
                                </div>
                            </div>
                            
                            <div class="teacher-meta">
                                <div class="meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $teacher->experience }}+ years</span>
                                </div>
                                @if($teacher->hourly_rate)
                                    <div class="meta-item">
                                        <i class="fas fa-rupee-sign"></i>
                                        <span>₹{{ number_format($teacher->hourly_rate) }}/hr</span>
                                    </div>
                                @endif
                                <div class="meta-item">
                                    <i class="fas fa-users"></i>
                                    <span>{{ $teacher->total_students }} students</span>
                                </div>
                            </div>
                            
                            @if($teacher->location)
                                <div class="teacher-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $teacher->location }}</span>
                                </div>
                            @endif
                            
                            <div class="teacher-tags">
                                @foreach($teacher->specializations->take(3) as $specialization)
                                    <span class="tag">{{ $specialization->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="teacher-footer">
                            <div class="teacher-actions-main">
                                <a href="{{ route('teachers.show', $teacher->slug) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-user me-2"></i>View Profile
                                </a>
                                <button class="btn btn-outline-primary btn-sm contact-btn" data-teacher-id="{{ $teacher->id }}">
                                    <i class="fas fa-envelope me-2"></i>Contact
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $teachers->links() }}
            </div>
        @else
            <div class="no-results">
                <div class="no-results-content">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>No Teachers Found</h4>
                    <p>Try adjusting your search criteria or browse all teachers.</p>
                    <a href="{{ route('teachers.index') }}" class="btn btn-primary">
                        <i class="fas fa-refresh me-2"></i>Clear Filters
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Featured Subjects -->
<section class="featured-subjects py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Popular Subjects</h2>
            <p class="section-subtitle">Find teachers for the most popular subjects</p>
        </div>
        
        <div class="subjects-grid">
            @foreach($popular_subjects as $subject)
                <a href="{{ route('teachers.by-subject', $subject->slug) }}" class="subject-card">
                    <div class="subject-icon">
                        <i class="fas fa-{{ $subject->icon }}"></i>
                    </div>
                    <div class="subject-content">
                        <h5 class="subject-title">{{ $subject->name }}</h5>
                        <p class="subject-count">{{ $subject->teachers_count }} teachers</p>
                    </div>
                    <div class="subject-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3rem 0;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover;
    opacity: 0.3;
}

.page-header .container {
    position: relative;
    z-index: 2;
}

.breadcrumb {
    background: none;
    padding: 0;
    margin-bottom: 1rem;
}

.breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
}

.breadcrumb-item.active {
    color: white;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.page-subtitle {
    font-size: 1.125rem;
    opacity: 0.9;
    margin-bottom: 2rem;
}

.page-stats {
    display: flex;
    gap: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffd700;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.8;
}

.header-actions {
    margin-top: 1rem;
}

/* Search Filters */
.search-filters-section {
    background: white;
    padding: 2rem 0;
    border-bottom: 1px solid #e9ecef;
}

.filters-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.filters-form {
    margin-bottom: 0;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.filter-actions {
    display: flex;
    align-items: end;
    height: 100%;
}

/* Results Section */
.results-section {
    padding: 3rem 0;
}

.results-header {
    margin-bottom: 2rem;
}

.results-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0;
}

.results-filters {
    font-weight: normal;
    color: #6c757d;
}

.filter-tag {
    background: #e9ecef;
    color: #495057;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    margin-left: 0.5rem;
}

.results-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.sort-dropdown {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sort-dropdown .form-label {
    margin-bottom: 0;
    font-size: 0.875rem;
}

.sort-dropdown .form-select {
    width: auto;
    min-width: 150px;
}

.view-toggle {
    display: flex;
    gap: 0.25rem;
}

.view-toggle .btn {
    padding: 0.5rem 0.75rem;
}

/* Teachers Grid */
.teachers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.teacher-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.teacher-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.teacher-header {
    position: relative;
    padding: 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.teacher-avatar-container {
    position: relative;
    display: inline-block;
}

.teacher-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 4px solid rgba(255, 255, 255, 0.3);
    object-fit: cover;
}

.online-badge {
    position: absolute;
    bottom: 0;
    right: 0;
    background: rgba(40, 167, 69, 0.9);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.online-badge i {
    font-size: 0.5rem;
    margin-right: 0.25rem;
}

.teacher-actions {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    gap: 0.5rem;
}

.teacher-content {
    padding: 1.5rem;
}

.teacher-name a {
    color: #2c3e50;
    text-decoration: none;
    font-weight: 600;
}

.teacher-name a:hover {
    color: #007bff;
}

.teacher-subject {
    color: #6c757d;
    margin-bottom: 1rem;
}

.teacher-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.rating-stars {
    color: #ffc107;
}

.rating-text {
    font-size: 0.875rem;
    color: #6c757d;
}

.teacher-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6c757d;
}

.teacher-location {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

.teacher-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.tag {
    background: #e9ecef;
    color: #495057;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
}

.teacher-footer {
    padding: 1rem 1.5rem 1.5rem;
    border-top: 1px solid #e9ecef;
}

.teacher-actions-main {
    display: flex;
    gap: 0.5rem;
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    font-weight: 600;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
}

/* No Results */
.no-results {
    text-align: center;
    padding: 4rem 0;
}

.no-results-content {
    max-width: 400px;
    margin: 0 auto;
}

.no-results-content h4 {
    color: #2c3e50;
    margin-bottom: 1rem;
}

.no-results-content p {
    color: #6c757d;
    margin-bottom: 2rem;
}

/* Featured Subjects */
.featured-subjects {
    background: #f8f9fa;
}

.section-header {
    margin-bottom: 3rem;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.section-subtitle {
    font-size: 1.125rem;
    color: #6c757d;
    margin: 0;
}

.subjects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.subject-card {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: white;
    border: 2px solid #f8f9fa;
    border-radius: 15px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.subject-card:hover {
    border-color: #007bff;
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 123, 255, 0.1);
}

.subject-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(45deg, #007bff, #0056b3);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    margin-right: 1rem;
}

.subject-content {
    flex: 1;
}

.subject-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #2c3e50;
}

.subject-count {
    font-size: 0.875rem;
    color: #6c757d;
    margin: 0;
}

.subject-arrow {
    color: #007bff;
    font-size: 1.25rem;
    opacity: 0;
    transition: all 0.3s ease;
}

.subject-card:hover .subject-arrow {
    opacity: 1;
    transform: translateX(5px);
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
}

.pagination {
    display: flex;
    gap: 0.5rem;
}

.page-link {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    color: #495057;
    padding: 0.75rem 1rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.page-link:hover {
    border-color: #007bff;
    color: #007bff;
}

.page-item.active .page-link {
    background: #007bff;
    border-color: #007bff;
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .page-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .filters-card {
        padding: 1.5rem;
    }
    
    .results-actions {
        flex-direction: column;
        gap: 1rem;
    }
    
    .teachers-grid {
        grid-template-columns: 1fr;
    }
    
    .subjects-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const viewButtons = document.querySelectorAll('.view-toggle .btn');
    const teachersContainer = document.getElementById('teachers-container');
    
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            viewButtons.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            // Change view
            const view = this.dataset.view;
            if (view === 'list') {
                teachersContainer.classList.add('list-view');
            } else {
                teachersContainer.classList.remove('list-view');
            }
        });
    });
    
    // Favorite functionality
    const favoriteButtons = document.querySelectorAll('.favorite-btn');
    
    favoriteButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const teacherId = this.dataset.teacherId;
            const icon = this.querySelector('i');
            
            // Toggle favorite state
            if (icon.classList.contains('text-danger')) {
                icon.classList.remove('text-danger');
                this.classList.remove('btn-danger');
                this.classList.add('btn-outline-primary');
            } else {
                icon.classList.add('text-danger');
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-danger');
            }
            
            // Send AJAX request to toggle favorite
            fetch(`/teacher/${teacherId}/favorite`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            });
        });
    });
    
    // Share functionality
    const shareButtons = document.querySelectorAll('.share-btn');
    
    shareButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const teacherId = this.dataset.teacherId;
            const url = `${window.location.origin}/teachers/${teacherId}`;
            
            if (navigator.share) {
                navigator.share({
                    title: 'Check out this teacher',
                    url: url
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(url).then(() => {
                    alert('Link copied to clipboard!');
                });
            }
        });
    });
    
    // Contact functionality
    const contactButtons = document.querySelectorAll('.contact-btn');
    
    contactButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const teacherId = this.dataset.teacherId;
            // Open contact modal or redirect to contact page
            window.location.href = `/teachers/${teacherId}/contact`;
        });
    });
    
    // Auto-submit form on filter change
    const filterSelects = document.querySelectorAll('.filters-form select');
    
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endpush 