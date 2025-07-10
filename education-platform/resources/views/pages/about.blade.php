@extends('layouts.app')

@section('title', $page->meta_title ?? 'About Us - Education Platform')
@section('meta_description', $page->meta_description ?? 'Learn about our mission to connect students with qualified teachers and institutes.')

@section('content')
<!-- Page Header -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <h1 class="display-4 fw-bold">{{ $page->title }}</h1>
                <p class="lead">Transforming education through meaningful connections</p>
            </div>
            <div class="col-lg-4 text-end" data-aos="fade-left">
                <i class="bi bi-people display-1 opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container">
        {!! $page->content !!}
    </div>
</section>

<!-- Our Values -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold">Our Core Values</h2>
            <p class="lead text-muted">The principles that guide everything we do</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-heart text-primary fs-1"></i>
                    </div>
                    <h4>Quality First</h4>
                    <p class="text-muted">We prioritize the quality of education and ensure all our teachers meet the highest standards.</p>
                </div>
            </div>
            
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-people text-success fs-1"></i>
                    </div>
                    <h4>Accessibility</h4>
                    <p class="text-muted">Making quality education accessible to everyone, regardless of location or background.</p>
                </div>
            </div>
            
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-lightbulb text-info fs-1"></i>
                    </div>
                    <h4>Innovation</h4>
                    <p class="text-muted">Continuously improving our platform with the latest technology and educational methods.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold">Meet Our Team</h2>
            <p class="lead text-muted">The passionate people behind our platform</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 text-center">
                    <div class="card-body">
                        <img src="{{ asset('images/team/ceo.jpg') }}" alt="CEO" class="rounded-circle mb-3" width="100" height="100">
                        <h5>John Smith</h5>
                        <p class="text-muted">CEO & Founder</p>
                        <div class="social-links">
                            <a href="#" class="text-primary me-2"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="text-info"><i class="bi bi-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 text-center">
                    <div class="card-body">
                        <img src="{{ asset('images/team/cto.jpg') }}" alt="CTO" class="rounded-circle mb-3" width="100" height="100">
                        <h5>Sarah Johnson</h5>
                        <p class="text-muted">CTO</p>
                        <div class="social-links">
                            <a href="#" class="text-primary me-2"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="text-info"><i class="bi bi-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 text-center">
                    <div class="card-body">
                        <img src="{{ asset('images/team/head-education.jpg') }}" alt="Head of Education" class="rounded-circle mb-3" width="100" height="100">
                        <h5>Dr. Mike Wilson</h5>
                        <p class="text-muted">Head of Education</p>
                        <div class="social-links">
                            <a href="#" class="text-primary me-2"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="text-info"><i class="bi bi-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="card border-0 text-center">
                    <div class="card-body">
                        <img src="{{ asset('images/team/head-marketing.jpg') }}" alt="Head of Marketing" class="rounded-circle mb-3" width="100" height="100">
                        <h5>Emily Davis</h5>
                        <p class="text-muted">Head of Marketing</p>
                        <div class="social-links">
                            <a href="#" class="text-primary me-2"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="text-info"><i class="bi bi-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="h2 fw-bold">10,000+</div>
                <p>Happy Students</p>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="h2 fw-bold">2,500+</div>
                <p>Qualified Teachers</p>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="h2 fw-bold">500+</div>
                <p>Partner Institutes</p>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="h2 fw-bold">50+</div>
                <p>Cities Covered</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <h2 class="fw-bold mb-4">Join Our Growing Community</h2>
                <p class="lead mb-4">
                    Whether you're a student looking for a teacher or an educator wanting to share your knowledge, 
                    we have a place for you.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-person-plus me-2"></i>Join as Student
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4">
                        <i class="bi bi-person-workspace me-2"></i>Join as Teacher
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 