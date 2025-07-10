@extends('layouts.app')

@section('title', 'Education Platform - Connect with Best Teachers & Institutes')
@section('meta_description', 'Find qualified teachers and reputable institutes for personalized learning. Book sessions, track progress, and achieve your educational goals with our platform.')

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 70vh;
        display: flex;
        align-items: center;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('{{ asset(' images/education-bg.jpg') }}') center/cover;
        opacity: 0.1;
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .feature-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        height: 100%;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        color: #667eea;
    }

    .testimonial-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        text-align: center;
        height: 100%;
    }

    .search-box {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .cta-section {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="hero-content">
                    <h1 class="display-4 fw-bold mb-4">
                        Find the Perfect <span class="text-warning">Teacher</span> for Your Learning Journey
                    </h1>
                    <p class="lead mb-4">
                        Connect with qualified teachers and reputable institutes. Book personalized sessions,
                        track your progress, and achieve your educational goals with confidence.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('teachers.index') }}" class="btn btn-warning btn-lg px-4">
                            <i class="bi bi-search me-2"></i>Find Teachers
                        </a>
                        <a href="{{ route('institutes.index') }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-building me-2"></i>Browse Institutes
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="search-box mt-5 mt-lg-0">
                    <h3 class="text-dark mb-4">Quick Search</h3>
                    <form action="{{ route('search.teachers') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="subject" placeholder="Subject (e.g., Math, Science)">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="location" placeholder="Location">
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" name="level">
                                    <option value="">Select Level</option>
                                    <option value="primary">Primary</option>
                                    <option value="secondary">Secondary</option>
                                    <option value="higher_secondary">Higher Secondary</option>
                                    <option value="university">University</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" name="mode">
                                    <option value="">Teaching Mode</option>
                                    <option value="online">Online</option>
                                    <option value="offline">Offline</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100 btn-lg">
                                    <i class="bi bi-search me-2"></i>Search Now
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold">Why Choose Our Platform?</h2>
            <p class="lead text-muted">Discover the benefits of learning with verified educators</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-shield-check text-primary fs-1"></i>
                        </div>
                        <h4>Verified Teachers</h4>
                        <p class="text-muted">All our teachers are thoroughly verified with proper credentials and background checks.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-calendar-check text-success fs-1"></i>
                        </div>
                        <h4>Easy Booking</h4>
                        <p class="text-muted">Book sessions instantly with our simple and secure booking system. Choose your preferred time and mode.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-graph-up text-info fs-1"></i>
                        </div>
                        <h4>Track Progress</h4>
                        <p class="text-muted">Monitor your learning journey with detailed progress reports and performance analytics.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-headset text-warning fs-1"></i>
                        </div>
                        <h4>24/7 Support</h4>
                        <p class="text-muted">Get help whenever you need it with our round-the-clock customer support team.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-currency-dollar text-danger fs-1"></i>
                        </div>
                        <h4>Affordable Rates</h4>
                        <p class="text-muted">Quality education at competitive prices. Find teachers that fit your budget and learning needs.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-award text-success fs-1"></i>
                        </div>
                        <h4>Quality Assurance</h4>
                        <p class="text-muted">Regular feedback and quality checks ensure you receive the best educational experience possible.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
@if(isset($stats))
<section class="py-5">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-number" data-target="{{ $stats['total_users'] }}">0</div>
                <h5>Active Students</h5>
                <p class="text-muted">Verified learners on our platform</p>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-number" data-target="{{ $stats['total_teachers'] }}">0</div>
                <h5>Expert Teachers</h5>
                <p class="text-muted">Qualified educators ready to help</p>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-number" data-target="{{ $stats['total_institutes'] }}">0</div>
                <h5>Partner Institutes</h5>
                <p class="text-muted">Trusted educational institutions</p>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="stat-number" data-target="{{ $stats['total_sessions'] }}">0</div>
                <h5>Learning Sessions</h5>
                <p class="text-muted">Successful sessions completed</p>
            </div>
        </div>
    </div>
</section>
@endif

<!-- How It Works Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold">How It Works</h2>
            <p class="lead text-muted">Get started in just 3 simple steps</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="position-relative mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <span class="h2 mb-0">1</span>
                    </div>
                </div>
                <h4>Search & Find</h4>
                <p class="text-muted">Browse through our database of qualified teachers and institutes. Filter by subject, location, and your specific requirements.</p>
            </div>

            <div class="col-lg-4 text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="position-relative mb-4">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <span class="h2 mb-0">2</span>
                    </div>
                </div>
                <h4>Connect & Book</h4>
                <p class="text-muted">Contact your chosen educator directly. Schedule sessions that fit your time and learning preferences.</p>
            </div>

            <div class="col-lg-4 text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="position-relative mb-4">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <span class="h2 mb-0">3</span>
                    </div>
                </div>
                <h4>Learn & Grow</h4>
                <p class="text-muted">Attend your sessions, track your progress, and achieve your educational goals with personalized learning.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Teachers Section -->
@if(isset($featured_teachers) && $featured_teachers->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold">Featured Teachers</h2>
            <p class="lead text-muted">Meet some of our top-rated educators</p>
        </div>

        <div class="row g-4">
            @foreach($featured_teachers as $teacher)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <img src="{{ $teacher['avatar'] ?? asset('images/default-avatar.png') }}"
                            alt="{{ $teacher['name'] }}"
                            class="rounded-circle mb-3"
                            width="80" height="80">
                        <h5>{{ $teacher['name'] }}</h5>
                        <p class="text-muted">{{ $teacher['specialization'] }}</p>
                        <div class="mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $teacher['rating'] ? '-fill' : '' }} text-warning"></i>
                                @endfor
                                <span class="text-muted ms-2">({{ $teacher['reviews_count'] }} reviews)</span>
                        </div>
                        <a href="{{ route('teachers.show', $teacher['slug']) }}" class="btn btn-outline-primary">View Profile</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Testimonials Section -->
@if(isset($testimonials) && $testimonials->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold">What Our Students Say</h2>
            <p class="lead text-muted">Real feedback from our learning community</p>
        </div>

        <div class="row g-4">
            @foreach($testimonials as $testimonial)
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="testimonial-card">
                    <div class="mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $testimonial['rating'] ? '-fill' : '' }} text-warning"></i>
                            @endfor
                    </div>
                    <blockquote class="mb-4">
                        "{{ $testimonial['content'] }}"
                    </blockquote>
                    <div class="d-flex align-items-center justify-content-center">
                        <img src="{{ $testimonial['avatar'] ?? asset('images/default-avatar.png') }}"
                            alt="{{ $testimonial['name'] }}"
                            class="rounded-circle me-3"
                            width="50" height="50">
                        <div>
                            <h6 class="mb-0">{{ $testimonial['name'] }}</h6>
                            <small class="text-muted">{{ $testimonial['designation'] }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="cta-section py-5">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <h2 class="fw-bold mb-4">Ready to Start Your Learning Journey?</h2>
                <p class="lead mb-4">
                    Join thousands of students who have found their perfect teachers and achieved their educational goals.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4">
                        <i class="bi bi-person-plus me-2"></i>Sign Up Now
                    </a>
                    <a href="{{ route('teachers.index') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-search me-2"></i>Browse Teachers
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Section -->
@if(isset($recent_posts) && $recent_posts->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div data-aos="fade-right">
                <h2 class="fw-bold">Latest from Our Blog</h2>
                <p class="lead text-muted">Educational insights and tips</p>
            </div>
            <a href="{{ route('blog.index') }}" class="btn btn-outline-primary" data-aos="fade-left">
                View All Posts <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($recent_posts as $post)
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <article class="card h-100 border-0 shadow-sm">
                    @if($post->featured_image)
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $post->published_at->format('M d, Y') }}
                            </small>
                            <small class="text-muted ms-auto">
                                <i class="bi bi-eye me-1"></i>
                                {{ $post->views }} views
                            </small>
                        </div>
                        <h5><a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none">{{ $post->title }}</a></h5>
                        <p class="text-muted">{{ $post->excerpt }}</p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-primary btn-sm">Read More</a>
                    </div>
                </article>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
    // Animate counter numbers
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number');

        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                counter.textContent = Math.floor(current).toLocaleString();
            }, 16);
        });
    }

    // Trigger animation when stats section is visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.unobserve(entry.target);
            }
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const statsSection = document.querySelector('.stat-number').closest('section');
        if (statsSection) {
            observer.observe(statsSection);
        }
    });
</script>
@endpush