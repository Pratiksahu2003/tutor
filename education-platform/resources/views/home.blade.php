@extends('layouts.app')

@section('title', 'Find the Best Teachers & Institutes Near You')
@section('meta_description', 'Connect with verified teachers and top-rated institutes. Start your learning journey with personalized education.')
@section('meta_keywords', 'teachers, institutes, education, tutoring, online learning, home tuition')

@section('content')

<!-- Hero Section with Advanced Search -->
<section class="hero-section">
    <div class="hero-background">
        <div class="hero-overlay"></div>
        <div class="hero-pattern"></div>
    </div>
    
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <div class="hero-badge">
                        <span class="badge bg-success bg-opacity-20 text-success px-3 py-2 rounded-pill">
                            <i class="fas fa-shield-alt me-2"></i>Verified Teachers & Institutes
                        </span>
                    </div>
                    
                    <h1 class="hero-title">
                        Find the Perfect
                        <span class="text-gradient">Teacher</span>
                        for Your Success
                    </h1>
                    
                    <p class="hero-description">
                        Connect with expert teachers and top-rated institutes. Get personalized learning 
                        that matches your goals, schedule, and budget. Start your journey to excellence today.
                    </p>
                    
                    <!-- Advanced Search Form -->
                    <div class="advanced-search-form">
                        <form action="{{ route('search.index') }}" method="GET" class="search-form">
                            <div class="search-tabs">
                                <button type="button" class="tab-btn active" data-tab="teachers">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>Find Teachers
                                </button>
                                <button type="button" class="tab-btn" data-tab="institutes">
                                    <i class="fas fa-university me-2"></i>Find Institutes
                                </button>
                            </div>
                            
                            <div class="search-fields">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Subject</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-book"></i>
                                                </span>
                                                <select name="subject" class="form-select">
                                                    <option value="">Select Subject</option>
                                                    <option value="mathematics">Mathematics</option>
                                                    <option value="science">Science</option>
                                                    <option value="english">English</option>
                                                    <option value="hindi">Hindi</option>
                                                    <option value="physics">Physics</option>
                                                    <option value="chemistry">Chemistry</option>
                                                    <option value="biology">Biology</option>
                                                    <option value="computer-science">Computer Science</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Location</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </span>
                                                <input type="text" name="location" placeholder="Enter your location" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Budget</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-rupee-sign"></i>
                                                </span>
                                                <select name="budget" class="form-select">
                                                    <option value="">Any Budget</option>
                                                    <option value="0-500">₹0 - ₹500/hr</option>
                                                    <option value="500-1000">₹500 - ₹1000/hr</option>
                                                    <option value="1000-2000">₹1000 - ₹2000/hr</option>
                                                    <option value="2000+">₹2000+/hr</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row g-3 mt-2">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Class/Grade</label>
                                            <select name="grade" class="form-select">
                                                <option value="">Any Grade</option>
                                                <option value="1-5">Class 1-5</option>
                                                <option value="6-8">Class 6-8</option>
                                                <option value="9-10">Class 9-10</option>
                                                <option value="11-12">Class 11-12</option>
                                                <option value="college">College</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Mode</label>
                                            <select name="mode" class="form-select">
                                                <option value="">Any Mode</option>
                                                <option value="online">Online</option>
                                                <option value="offline">Offline</option>
                                                <option value="both">Both</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Experience</label>
                                            <select name="experience" class="form-select">
                                                <option value="">Any Experience</option>
                                                <option value="1-3">1-3 years</option>
                                                <option value="3-5">3-5 years</option>
                                                <option value="5-10">5-10 years</option>
                                                <option value="10+">10+ years</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 search-btn">
                                            <i class="fas fa-search me-2"></i>Search Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ number_format($stats['total_teachers'] ?? 0) }}+</span>
                            <span class="stat-label">Expert Teachers</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ number_format($stats['active_institutes'] ?? 0) }}+</span>
                            <span class="stat-label">Top Institutes</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ number_format($stats['total_students'] ?? 0) }}+</span>
                            <span class="stat-label">Happy Students</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ number_format($stats['total_subjects'] ?? 0) }}+</span>
                            <span class="stat-label">Subjects</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="floating-cards">
                        @foreach($featured_teachers->take(3) as $index => $teacher)
                            <div class="floating-card card-{{ $index + 1 }}">
                                <div class="teacher-preview">
                                    <img src="{{ $teacher['avatar'] }}" alt="{{ $teacher['name'] }}" class="teacher-avatar">
                                    <div class="teacher-info">
                                        <h6>{{ $teacher['name'] }}</h6>
                                        <p>{{ $teacher['subject'] }}</p>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $teacher['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            <span class="rating-text">{{ $teacher['rating'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="hero-image">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="Students Learning" class="img-fluid rounded-4 shadow-lg">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Categories -->
<section class="categories-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Popular Learning Categories</h2>
            <p class="section-subtitle">Choose from our most popular subjects and courses</p>
        </div>
        
        <div class="categories-grid">
            @foreach($popular_subjects->take(8) as $index => $subject)
                <a href="{{ route('teachers.by-subject', $subject['slug']) }}" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-{{ $subject['icon'] }}"></i>
                    </div>
                    <div class="category-content">
                        <h5 class="category-title">{{ $subject['name'] }}</h5>
                        <p class="category-count">{{ $subject['teachers_count'] }} teachers available</p>
                    </div>
                    <div class="category-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('teachers.index') }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-th-large me-2"></i>View All Categories
            </a>
        </div>
    </div>
</section>

<!-- Featured Teachers -->
@if($featured_teachers->count() > 0)
<section class="featured-teachers py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-2">Top Rated Teachers</h2>
                <p class="section-subtitle mb-0">Learn from the best educators on our platform</p>
            </div>
            <a href="{{ route('teachers.index') }}" class="btn btn-primary">
                <i class="fas fa-eye me-2"></i>View All Teachers
            </a>
        </div>
        
        <div class="teachers-grid">
            @foreach($featured_teachers->take(6) as $teacher)
                <div class="teacher-card">
                    <div class="teacher-header">
                        <img src="{{ $teacher['avatar'] }}" alt="{{ $teacher['name'] }}" class="teacher-avatar">
                        @if($teacher['is_online'])
                            <span class="online-badge">
                                <i class="fas fa-circle"></i> Online
                            </span>
                        @endif
                        <div class="teacher-actions">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="teacher-content">
                        <h5 class="teacher-name">{{ $teacher['name'] }}</h5>
                        <p class="teacher-subject">{{ $teacher['subject'] }}</p>
                        
                        <div class="teacher-rating">
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $teacher['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                            <span class="rating-text">{{ $teacher['rating'] ?? 4.0 }} ({{ $teacher['total_students'] ?? 0 }} students)</span>
                        </div>
                        
                        <div class="teacher-meta">
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ $teacher['experience'] ?? 0 }}+ years</span>
                            </div>
                            @if($teacher['hourly_rate'])
                                <div class="meta-item">
                                    <i class="fas fa-rupee-sign"></i>
                                    <span>₹{{ number_format($teacher['hourly_rate'] ?? 0) }}/hr</span>
                                </div>
                            @endif
                            @if($teacher['location'])
                                <div class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $teacher['location'] ?? 'Location not specified' }}</span>
                                </div>
                            @endif
                        </div>
                        
                        @if($teacher['location'])
                            <div class="teacher-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $teacher['location'] }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="teacher-footer">
                        <a href="{{ route('teachers.show', $teacher['slug']) }}" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-user me-2"></i>View Profile
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Institutes -->
@if($featured_institutes->count() > 0)
<section class="featured-institutes py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-2">Top Rated Institutes</h2>
                <p class="section-subtitle mb-0">Discover the best educational institutes</p>
            </div>
            <a href="{{ route('institutes.index') }}" class="btn btn-primary">
                <i class="fas fa-eye me-2"></i>View All Institutes
            </a>
        </div>
        
        <div class="institutes-grid">
            @foreach($featured_institutes->take(4) as $institute)
                <div class="institute-card">
                    <div class="institute-header">
                        <img src="{{ $institute['logo'] }}" alt="{{ $institute['name'] }}" class="institute-logo">
                        <div class="institute-badge">
                            <span class="badge bg-success">Verified</span>
                        </div>
                    </div>
                    
                    <div class="institute-content">
                        <h5 class="institute-name">{{ $institute['name'] }}</h5>
                        <p class="institute-type">{{ $institute['type'] }}</p>
                        
                        <div class="institute-rating">
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $institute['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                            <span class="rating-text">{{ $institute['rating'] }} ({{ $institute['total_reviews'] ?? 0 }} reviews)</span>
                        </div>
                        
                        <div class="institute-meta">
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $institute['location'] ?? 'Location not specified' }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-users"></i>
                                <span>{{ $institute['students_count'] ?? 0 }} students</span>
                            </div>
                        </div>
                        
                        <div class="institute-subjects">
                            @foreach(($institute['subjects'] ?? collect())->take(3) as $subject)
                                <span class="subject-tag">{{ $subject['name'] ?? $subject->name ?? 'Subject' }}</span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="institute-footer">
                        <a href="{{ route('institutes.show', $institute['slug']) }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-building me-2"></i>View Institute
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- How It Works -->
<section class="how-it-works py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Get started in 3 simple steps</p>
        </div>
        
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">01</div>
                <div class="step-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h4 class="step-title">Search & Discover</h4>
                <p class="step-description">Find the perfect teacher or institute based on your subject, location, and preferences.</p>
            </div>
            
            <div class="step-card">
                <div class="step-number">02</div>
                <div class="step-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h4 class="step-title">Connect & Discuss</h4>
                <p class="step-description">Contact teachers directly, discuss your learning goals, and schedule your first session.</p>
            </div>
            
            <div class="step-card">
                <div class="step-number">03</div>
                <div class="step-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h4 class="step-title">Learn & Grow</h4>
                <p class="step-description">Start learning with personalized lessons and track your progress along the way.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">What Our Students Say</h2>
            <p class="section-subtitle">Real stories from real students</p>
        </div>
        
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="rating">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-warning"></i>
                        @endfor
                    </div>
                    <p class="testimonial-text">
                        "Found an amazing math teacher who helped me improve my grades from C to A+. 
                        The personalized approach made all the difference!"
                    </p>
                    <div class="testimonial-author">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" 
                             alt="Student" class="author-avatar">
                        <div class="author-info">
                            <h6 class="author-name">Priya Sharma</h6>
                            <p class="author-details">Class 12 Student</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="rating">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-warning"></i>
                        @endfor
                    </div>
                    <p class="testimonial-text">
                        "The institute I found here has excellent teachers and a great learning environment. 
                        My child's confidence has improved significantly."
                    </p>
                    <div class="testimonial-author">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" 
                             alt="Parent" class="author-avatar">
                        <div class="author-info">
                            <h6 class="author-name">Rajesh Kumar</h6>
                            <p class="author-details">Parent</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="rating">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-warning"></i>
                        @endfor
                    </div>
                    <p class="testimonial-text">
                        "Online tutoring has been a game-changer for me. Flexible schedule, 
                        great teachers, and I can study from anywhere!"
                    </p>
                    <div class="testimonial-author">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" 
                             alt="Student" class="author-avatar">
                        <div class="author-info">
                            <h6 class="author-name">Aisha Patel</h6>
                            <p class="author-details">College Student</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="cta-title">Ready to Start Your Learning Journey?</h2>
                <p class="cta-description">
                    Join thousands of students who have found their perfect teacher or institute. 
                    Start your path to success today!
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">
                    <i class="fas fa-user-plus me-2"></i>Join Now
                </a>
                <a href="{{ route('teachers.index') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-search me-2"></i>Find Teachers
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Hero Section */
.hero-section {
    position: relative;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
}

.hero-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                      radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-badge {
    margin-bottom: 2rem;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
}

.text-gradient {
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-description {
    font-size: 1.25rem;
    line-height: 1.6;
    margin-bottom: 2rem;
    opacity: 0.9;
}

/* Advanced Search Form */
.advanced-search-form {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    margin-bottom: 2rem;
}

.search-tabs {
    display: flex;
    margin-bottom: 1.5rem;
    background: #f8f9fa;
    border-radius: 10px;
    padding: 0.25rem;
}

.tab-btn {
    flex: 1;
    border: none;
    background: transparent;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    color: #6c757d;
    transition: all 0.3s ease;
}

.tab-btn.active {
    background: white;
    color: #007bff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.search-fields {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.input-group-text {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    color: #6c757d;
}

.search-btn {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    font-weight: 600;
    padding: 0.75rem 2rem;
}

/* Hero Stats */
.hero-stats {
    display: flex;
    gap: 2rem;
    margin-top: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #ffd700;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.8;
}

/* Hero Visual */
.hero-visual {
    position: relative;
    height: 100%;
}

.floating-cards {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.floating-card {
    position: absolute;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 1rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    animation: float 6s ease-in-out infinite;
}

.card-1 {
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.card-2 {
    top: 30%;
    right: 15%;
    animation-delay: 2s;
}

.card-3 {
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.teacher-preview {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.teacher-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.teacher-info h6 {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 600;
}

.teacher-info p {
    margin: 0;
    font-size: 0.75rem;
    color: #6c757d;
}

.rating {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.rating-text {
    font-size: 0.75rem;
    color: #ffc107;
}

.hero-image {
    position: relative;
    z-index: 2;
}

.hero-image img {
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

/* Categories Section */
.categories-section {
    background: white;
}

.section-header {
    margin-bottom: 3rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.section-subtitle {
    font-size: 1.125rem;
    color: #6c757d;
    margin: 0;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.category-card {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: white;
    border: 2px solid #f8f9fa;
    border-radius: 15px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.category-card:hover {
    border-color: #007bff;
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 123, 255, 0.1);
}

.category-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(45deg, #007bff, #0056b3);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin-right: 1rem;
}

.category-content {
    flex: 1;
}

.category-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #2c3e50;
}

.category-count {
    font-size: 0.875rem;
    color: #6c757d;
    margin: 0;
}

.category-arrow {
    color: #007bff;
    font-size: 1.25rem;
    opacity: 0;
    transition: all 0.3s ease;
}

.category-card:hover .category-arrow {
    opacity: 1;
    transform: translateX(5px);
}

/* Featured Teachers */
.featured-teachers {
    background: #f8f9fa;
}

.teachers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
}

.teacher-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
}

.teacher-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.teacher-header {
    position: relative;
    padding: 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
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
    top: 1rem;
    right: 1rem;
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
}

.teacher-content {
    padding: 1.5rem;
}

.teacher-name {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2c3e50;
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
}

.teacher-footer {
    padding: 1rem 1.5rem 1.5rem;
}

/* Featured Institutes */
.institutes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.institute-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.institute-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.institute-header {
    position: relative;
    padding: 1.5rem;
    background: #f8f9fa;
}

.institute-logo {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    object-fit: cover;
}

.institute-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.institute-content {
    padding: 1.5rem;
}

.institute-name {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.institute-type {
    color: #6c757d;
    margin-bottom: 1rem;
}

.institute-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.institute-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.institute-subjects {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.subject-tag {
    background: #e9ecef;
    color: #495057;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
}

.institute-footer {
    padding: 1rem 1.5rem 1.5rem;
}

/* How It Works */
.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.step-card {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.step-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.step-number {
    width: 60px;
    height: 60px;
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 auto 1.5rem;
}

.step-icon {
    width: 80px;
    height: 80px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #007bff;
    margin: 0 auto 1.5rem;
}

.step-title {
    font-weight: 600;
    margin-bottom: 1rem;
    color: #2c3e50;
}

.step-description {
    color: #6c757d;
    line-height: 1.6;
}

/* Testimonials */
.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.testimonial-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.testimonial-content {
    position: relative;
}

.rating {
    margin-bottom: 1rem;
}

.testimonial-text {
    font-size: 1.125rem;
    line-height: 1.6;
    color: #495057;
    margin-bottom: 1.5rem;
    font-style: italic;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.author-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.author-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #2c3e50;
}

.author-details {
    font-size: 0.875rem;
    color: #6c757d;
    margin: 0;
}

/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-description {
    font-size: 1.125rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .advanced-search-form {
        padding: 1.5rem;
    }
    
    .search-tabs {
        flex-direction: column;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
    }
    
    .teachers-grid {
        grid-template-columns: 1fr;
    }
    
    .institutes-grid {
        grid-template-columns: 1fr;
    }
    
    .steps-grid {
        grid-template-columns: 1fr;
    }
    
    .testimonials-grid {
        grid-template-columns: 1fr;
    }
    
    .cta-title {
        font-size: 2rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabBtns = document.querySelectorAll('.tab-btn');
    const searchFields = document.querySelector('.search-fields');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all tabs
            tabBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Update form action based on tab
            const form = document.querySelector('.search-form');
            if (this.dataset.tab === 'teachers') {
                form.action = "{{ route('search.teachers') }}";
            } else {
                form.action = "{{ route('search.institutes') }}";
            }
        });
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe all cards and sections
    document.querySelectorAll('.category-card, .teacher-card, .institute-card, .step-card, .testimonial-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
});
</script>
@endpush