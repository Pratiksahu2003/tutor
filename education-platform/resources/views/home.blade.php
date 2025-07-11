@extends('layouts.app')

@section('title', $seo_data['title'])
@section('meta_description', $seo_data['description'])
@section('meta_keywords', $seo_data['keywords'])

@section('content')

<!-- Modern Hero Section -->
<section class="modern-hero">
    <div class="container-fluid px-0">
        <div class="hero-content-wrapper">
            <div class="hero-background">
                <div class="hero-gradient"></div>
                <div class="hero-shapes">
                    <div class="shape shape-1"></div>
                    <div class="shape shape-2"></div>
                    <div class="shape shape-3"></div>
                </div>
            </div>
            
            <div class="container">
                <div class="row align-items-center min-vh-100 py-5">
                    <div class="col-lg-6">
                        <div class="hero-text-content">
                            <div class="hero-badge mb-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                    <i class="fas fa-star me-2"></i>Trusted by {{ number_format($stats['total_students']) }}+ Students
                                </span>
                            </div>
                            
                            <h1 class="hero-title">
                                Learn From The
                                <span class="text-gradient">Best Educators</span>
                                Near You
                            </h1>
                            
                            <p class="hero-description">
                                Connect with verified teachers and top-rated institutes. Start your learning journey 
                                with personalized education that fits your schedule and learning style.
                            </p>
                            
                            <div class="hero-search-box">
                                <form action="{{ route('search.index') }}" method="GET" class="search-form-modern">
                                    <div class="search-input-group">
                                        <div class="search-field">
                                            <i class="fas fa-search search-icon"></i>
                                            <input type="text" name="subject" placeholder="What do you want to learn?" 
                                                   class="form-control search-input" autocomplete="off">
                                        </div>
                                        <div class="search-field">
                                            <i class="fas fa-map-marker-alt search-icon"></i>
                                            <input type="text" name="location" placeholder="Your location" 
                                                   class="form-control search-input" autocomplete="off">
                                        </div>
                                        <button type="submit" class="btn btn-primary search-btn">
                                            <i class="fas fa-search me-2"></i>Search
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="hero-stats-mini">
                                <div class="stat-item">
                                    <span class="stat-number">{{ number_format($stats['total_teachers']) }}+</span>
                                    <span class="stat-label">Expert Teachers</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number">{{ number_format($stats['active_institutes']) }}+</span>
                                    <span class="stat-label">Top Institutes</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number">{{ number_format($stats['total_subjects']) }}+</span>
                                    <span class="stat-label">Subjects</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="hero-visual">
                            <div class="floating-cards">
                                @foreach($featured_teachers->take(3) as $index => $teacher)
                                    <div class="floating-card floating-card-{{ $index + 1 }}">
                                        <div class="teacher-mini-card">
                                            <img src="{{ $teacher['avatar'] }}" alt="{{ $teacher['name'] }}" class="teacher-avatar-mini">
                                            <div class="teacher-info-mini">
                                                <h6>{{ $teacher['name'] }}</h6>
                                                <p>{{ $teacher['subject'] }}</p>
                                                <div class="rating-mini">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $teacher['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="hero-image-main">
                                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                     alt="Students Learning" class="img-fluid rounded-4 shadow-lg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Subjects Grid -->
<section class="subjects-modern py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Popular Subjects</h2>
            <p class="section-subtitle">Choose from our most popular learning categories</p>
        </div>
        
        <div class="subjects-grid">
            @foreach($popular_subjects->take(8) as $index => $subject)
                <a href="{{ route('teachers.by-subject', $subject['slug']) }}" class="subject-item-modern">
                    <div class="subject-card-modern">
                        <div class="subject-icon-modern">
                            <i class="fas fa-{{ $subject['icon'] }}"></i>
                        </div>
                        <h5 class="subject-title-modern">{{ $subject['name'] }}</h5>
                        <p class="subject-count-modern">{{ $subject['teachers_count'] }} teachers</p>
                        <div class="subject-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('teachers.index') }}" class="btn btn-outline-primary btn-lg">
                View All Subjects
            </a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="how-it-works py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Get started in 3 simple steps</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="step-card text-center">
                    <div class="step-number">01</div>
                    <div class="step-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4 class="step-title">Search & Discover</h4>
                    <p class="step-description">Find the perfect teacher or institute based on your subject, location, and preferences.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="step-card text-center">
                    <div class="step-number">02</div>
                    <div class="step-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h4 class="step-title">Connect & Discuss</h4>
                    <p class="step-description">Contact teachers directly, discuss your learning goals, and schedule your first session.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="step-card text-center">
                    <div class="step-number">03</div>
                    <div class="step-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h4 class="step-title">Learn & Grow</h4>
                    <p class="step-description">Start learning with personalized lessons and track your progress along the way.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Teachers Modern -->
@if($featured_teachers->count() > 0)
<section class="teachers-modern py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-2">Top Rated Teachers</h2>
                <p class="section-subtitle mb-0">Learn from the best educators on our platform</p>
            </div>
            <a href="{{ route('teachers.index') }}" class="btn btn-primary">View All</a>
        </div>
        
        <div class="teachers-grid-modern">
            @foreach($featured_teachers->take(6) as $teacher)
                <div class="teacher-card-modern">
                    <div class="teacher-header">
                        <img src="{{ $teacher['avatar'] }}" alt="{{ $teacher['name'] }}" class="teacher-avatar-modern">
                        @if($teacher['is_online'])
                            <span class="online-indicator"></span>
                        @endif
                    </div>
                    
                    <div class="teacher-content">
                        <h5 class="teacher-name">{{ $teacher['name'] }}</h5>
                        <p class="teacher-subject">{{ $teacher['subject'] }}</p>
                        
                        <div class="teacher-rating-modern">
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $teacher['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                            <span class="rating-text">{{ $teacher['rating'] }} ({{ $teacher['total_students'] }} students)</span>
                        </div>
                        
                        <div class="teacher-meta">
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ $teacher['experience'] }}+ years</span>
                            </div>
                            @if($teacher['hourly_rate'])
                                <div class="meta-item">
                                    <i class="fas fa-rupee-sign"></i>
                                    <span>₹{{ number_format($teacher['hourly_rate']) }}/hr</span>
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
                        <a href="{{ route('teachers.show', $teacher['slug']) }}" class="btn btn-outline-primary btn-sm w-100">
                            View Profile
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Institutes Modern -->
@if($featured_institutes->count() > 0)
<section class="institutes-modern py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-2">Top Institutes</h2>
                <p class="section-subtitle mb-0">Discover leading educational institutions</p>
            </div>
            <a href="{{ route('institutes.index') }}" class="btn btn-primary">View All</a>
        </div>
        
        <div class="institutes-grid-modern">
            @foreach($featured_institutes->take(6) as $institute)
                <div class="institute-card-modern">
                    <div class="institute-header">
                        <img src="{{ $institute['logo'] }}" alt="{{ $institute['name'] }}" class="institute-logo-modern">
                        <div class="institute-rating">
                            <span class="rating-badge">{{ $institute['rating'] }}</span>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                    </div>
                    
                    <div class="institute-content">
                        <h5 class="institute-name">{{ $institute['name'] }}</h5>
                        <p class="institute-type">{{ $institute['type'] }}</p>
                        
                        @if($institute['description'])
                            <p class="institute-description">{{ Str::limit($institute['description'], 80) }}</p>
                        @endif
                        
                        <div class="institute-stats-modern">
                            <div class="stat-item-modern">
                                <span class="stat-value">{{ number_format($institute['total_students']) }}+</span>
                                <span class="stat-label">Students</span>
                            </div>
                            @if($institute['established_year'])
                                <div class="stat-item-modern">
                                    <span class="stat-value">{{ $institute['established_year'] }}</span>
                                    <span class="stat-label">Established</span>
                                </div>
                            @endif
                        </div>
                        
                        @if($institute['location'])
                            <div class="institute-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $institute['location'] }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="institute-footer">
                        <a href="{{ route('institutes.show', $institute['slug']) }}" class="btn btn-outline-primary btn-sm w-100">
                            View Institute
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Testimonials Modern -->
@if($testimonials->count() > 0)
<section class="testimonials-modern py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">What Our Community Says</h2>
            <p class="section-subtitle">Real stories from students and educators</p>
        </div>
        
        <div class="testimonials-slider">
            <div class="testimonials-track">
                @foreach($testimonials as $testimonial)
                    <div class="testimonial-card-modern">
                        <div class="testimonial-content">
                            <div class="quote-icon">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <p class="testimonial-text">"{{ $testimonial['content'] }}"</p>
                            <div class="testimonial-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $testimonial['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <div class="testimonial-author">
                            <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" class="author-avatar">
                            <div class="author-info">
                                <h6 class="author-name">{{ $testimonial['name'] }}</h6>
                                <p class="author-role">{{ $testimonial['designation'] }}</p>
                                @if($testimonial['subject'] !== 'General')
                                    <span class="author-subject">{{ $testimonial['subject'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- CTA Section Modern -->
<section class="cta-modern">
    <div class="container">
        <div class="cta-content-wrapper">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="cta-content">
                        <h2 class="cta-title">Ready to Start Learning?</h2>
                        <p class="cta-description">Join thousands of students and connect with the best educators today.</p>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="cta-buttons">
                        <a href="{{ route('register') }}" class="btn btn-white btn-lg me-3">
                            Get Started Free
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-white btn-lg">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Modern Hero Section */
.modern-hero {
    position: relative;
    overflow: hidden;
}

.hero-content-wrapper {
    position: relative;
    z-index: 2;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.hero-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0.9;
}

.hero-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
}

.shape-1 {
    width: 200px;
    height: 200px;
    top: 10%;
    right: 10%;
    animation: float 6s ease-in-out infinite;
}

.shape-2 {
    width: 150px;
    height: 150px;
    top: 50%;
    left: 5%;
    animation: float 8s ease-in-out infinite reverse;
}

.shape-3 {
    width: 100px;
    height: 100px;
    bottom: 20%;
    right: 30%;
    animation: float 10s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.hero-text-content {
    color: white;
    position: relative;
    z-index: 3;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
}

.text-gradient {
    background: linear-gradient(45deg, #ffd700, #ffeb3b);
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

/* Hero Search */
.hero-search-box {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.search-input-group {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.search-field {
    position: relative;
    flex: 1;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    z-index: 5;
}

.search-input {
    padding: 1rem 1rem 1rem 3rem;
    border: none;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.9);
    width: 100%;
}

.search-btn {
    padding: 1rem 2rem;
    border-radius: 12px;
    white-space: nowrap;
    border: none;
    background: #3b82f6;
}

/* Hero Stats */
.hero-stats-mini {
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
    display: block;
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Hero Visual */
.hero-visual {
    position: relative;
    height: 500px;
}

.hero-image-main {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
}

.floating-cards {
    position: absolute;
    width: 100%;
    height: 100%;
}

.floating-card {
    position: absolute;
    background: white;
    border-radius: 16px;
    padding: 1rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    animation: floatCard 6s ease-in-out infinite;
}

.floating-card-1 {
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.floating-card-2 {
    top: 60%;
    right: 10%;
    animation-delay: 2s;
}

.floating-card-3 {
    top: 30%;
    right: 25%;
    animation-delay: 4s;
}

@keyframes floatCard {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}

.teacher-mini-card {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 200px;
}

.teacher-avatar-mini {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.teacher-info-mini h6 {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 600;
}

.teacher-info-mini p {
    margin: 0;
    font-size: 0.8rem;
    color: #6b7280;
}

.rating-mini {
    font-size: 0.7rem;
}

/* Subjects Modern */
.subjects-modern {
    background: #f8fafc;
}

.section-header {
    margin-bottom: 3rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #64748b;
}

.subjects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.subject-item-modern {
    text-decoration: none;
    color: inherit;
}

.subject-card-modern {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
}

.subject-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border-color: #3b82f6;
}

.subject-icon-modern {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
}

.subject-title-modern {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #1e293b;
}

.subject-count-modern {
    color: #64748b;
    margin-bottom: 1rem;
}

.subject-arrow {
    position: absolute;
    top: 1rem;
    right: 1rem;
    opacity: 0;
    transition: all 0.3s ease;
    color: #3b82f6;
}

.subject-card-modern:hover .subject-arrow {
    opacity: 1;
    transform: translateX(5px);
}

/* How It Works */
.step-card {
    position: relative;
    padding: 2rem;
}

.step-number {
    position: absolute;
    top: -1rem;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
}

.step-icon {
    width: 80px;
    height: 80px;
    background: #f1f5f9;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 2rem auto 1.5rem;
    color: #3b82f6;
    font-size: 2rem;
}

.step-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #1e293b;
}

.step-description {
    color: #64748b;
    line-height: 1.6;
}

/* Teachers Modern */
.teachers-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
}

.teacher-card-modern {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
}

.teacher-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.teacher-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    position: relative;
}

.teacher-avatar-modern {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

.online-indicator {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 16px;
    height: 16px;
    background: #22c55e;
    border: 3px solid white;
    border-radius: 50%;
}

.teacher-name {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #1e293b;
}

.teacher-subject {
    color: #3b82f6;
    font-weight: 500;
    margin-bottom: 0.75rem;
}

.teacher-rating-modern {
    margin-bottom: 1rem;
}

.rating-text {
    font-size: 0.9rem;
    color: #64748b;
    margin-left: 0.5rem;
}

.teacher-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 0.75rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.9rem;
    color: #64748b;
}

.teacher-location {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.9rem;
    color: #64748b;
    margin-bottom: 1rem;
}

.teacher-footer {
    border-top: 1px solid #e2e8f0;
    padding-top: 1rem;
}

/* Institutes Modern */
.institutes-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.institute-card-modern {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
}

.institute-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.institute-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.institute-logo-modern {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    object-fit: cover;
}

.institute-rating {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.rating-badge {
    background: #f59e0b;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
}

.institute-name {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #1e293b;
}

.institute-type {
    color: #3b82f6;
    font-weight: 500;
    margin-bottom: 0.75rem;
}

.institute-description {
    color: #64748b;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.institute-stats-modern {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1rem;
}

.stat-item-modern {
    text-align: center;
}

.stat-value {
    display: block;
    font-weight: 600;
    color: #1e293b;
}

.stat-label {
    font-size: 0.8rem;
    color: #64748b;
}

.institute-location {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.9rem;
    color: #64748b;
    margin-bottom: 1rem;
}

.institute-footer {
    border-top: 1px solid #e2e8f0;
    padding-top: 1rem;
}

/* Testimonials Modern */
.testimonials-slider {
    overflow: hidden;
    position: relative;
}

.testimonials-track {
    display: flex;
    gap: 2rem;
    animation: scroll 30s linear infinite;
}

@keyframes scroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

.testimonial-card-modern {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    min-width: 400px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
}

.quote-icon {
    color: #3b82f6;
    font-size: 2rem;
    margin-bottom: 1rem;
}

.testimonial-text {
    font-size: 1.1rem;
    line-height: 1.6;
    color: #374151;
    margin-bottom: 1rem;
}

.testimonial-rating {
    margin-bottom: 1.5rem;
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
    color: #1e293b;
}

.author-role {
    color: #64748b;
    margin-bottom: 0.25rem;
}

.author-subject {
    background: #3b82f6;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
}

/* CTA Modern */
.cta-modern {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    color: white;
    padding: 5rem 0;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-description {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

.btn-white {
    background: white;
    color: #1e293b;
    border: none;
}

.btn-outline-white {
    border: 2px solid white;
    color: white;
    background: transparent;
}

.btn-outline-white:hover {
    background: white;
    color: #1e293b;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .search-input-group {
        flex-direction: column;
    }
    
    .hero-stats-mini {
        flex-direction: column;
        gap: 1rem;
    }
    
    .subjects-grid {
        grid-template-columns: 1fr;
    }
    
    .teachers-grid-modern,
    .institutes-grid-modern {
        grid-template-columns: 1fr;
    }
    
    .testimonial-card-modern {
        min-width: 300px;
    }
    
    .cta-title {
        font-size: 2rem;
    }
    
    .cta-buttons {
        margin-top: 2rem;
    }
    
    .cta-buttons .btn {
        display: block;
        width: 100%;
        margin-bottom: 1rem;
    }
}
</style>
@endpush