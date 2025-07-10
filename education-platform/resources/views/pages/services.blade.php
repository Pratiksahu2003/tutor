@extends('layouts.app')

@section('title', 'Our Services - Education Platform')
@section('meta_description', 'Discover our comprehensive education services including teacher connections, institute partnerships, and personalized learning solutions.')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-4 fw-bold">Our Services</h1>
            <p class="lead text-muted">Comprehensive educational solutions to meet all your learning needs</p>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Teacher Connection Service -->
        <div class="col-lg-4 col-md-6">
            <div class="service-card h-100 p-4 text-center border rounded shadow-sm">
                <div class="service-icon mb-3">
                    <i class="bi bi-person-check display-4 text-primary"></i>
                </div>
                <h3 class="h4 mb-3">Teacher Connection</h3>
                <p class="text-muted mb-4">Connect with qualified and verified teachers across various subjects and educational levels.</p>
                <ul class="list-unstyled text-start">
                    <li><i class="bi bi-check-circle text-success me-2"></i>Verified professionals</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Background checked</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Rating & reviews</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Direct communication</li>
                </ul>
            </div>
        </div>
        
        <!-- Institute Partnerships -->
        <div class="col-lg-4 col-md-6">
            <div class="service-card h-100 p-4 text-center border rounded shadow-sm">
                <div class="service-icon mb-3">
                    <i class="bi bi-building display-4 text-success"></i>
                </div>
                <h3 class="h4 mb-3">Institute Partnerships</h3>
                <p class="text-muted mb-4">Partner with reputable educational institutes for comprehensive learning programs.</p>
                <ul class="list-unstyled text-start">
                    <li><i class="bi bi-check-circle text-success me-2"></i>Accredited institutions</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Structured curricula</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Group learning</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Certification programs</li>
                </ul>
            </div>
        </div>
        
        <!-- Online Learning -->
        <div class="col-lg-4 col-md-6">
            <div class="service-card h-100 p-4 text-center border rounded shadow-sm">
                <div class="service-icon mb-3">
                    <i class="bi bi-laptop display-4 text-info"></i>
                </div>
                <h3 class="h4 mb-3">Online Learning</h3>
                <p class="text-muted mb-4">Flexible online sessions with interactive learning tools and resources.</p>
                <ul class="list-unstyled text-start">
                    <li><i class="bi bi-check-circle text-success me-2"></i>Virtual classrooms</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Interactive whiteboard</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Recording sessions</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Progress tracking</li>
                </ul>
            </div>
        </div>
        
        <!-- Personalized Learning -->
        <div class="col-lg-4 col-md-6">
            <div class="service-card h-100 p-4 text-center border rounded shadow-sm">
                <div class="service-icon mb-3">
                    <i class="bi bi-graph-up display-4 text-warning"></i>
                </div>
                <h3 class="h4 mb-3">Personalized Learning</h3>
                <p class="text-muted mb-4">Customized learning paths based on individual needs and learning style.</p>
                <ul class="list-unstyled text-start">
                    <li><i class="bi bi-check-circle text-success me-2"></i>Assessment tests</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Custom curriculum</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Progress monitoring</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Regular feedback</li>
                </ul>
            </div>
        </div>
        
        <!-- Exam Preparation -->
        <div class="col-lg-4 col-md-6">
            <div class="service-card h-100 p-4 text-center border rounded shadow-sm">
                <div class="service-icon mb-3">
                    <i class="bi bi-journal-text display-4 text-danger"></i>
                </div>
                <h3 class="h4 mb-3">Exam Preparation</h3>
                <p class="text-muted mb-4">Specialized coaching for competitive exams and board examinations.</p>
                <ul class="list-unstyled text-start">
                    <li><i class="bi bi-check-circle text-success me-2"></i>Mock tests</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Study materials</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Time management</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Strategy planning</li>
                </ul>
            </div>
        </div>
        
        <!-- Career Guidance -->
        <div class="col-lg-4 col-md-6">
            <div class="service-card h-100 p-4 text-center border rounded shadow-sm">
                <div class="service-icon mb-3">
                    <i class="bi bi-compass display-4 text-dark"></i>
                </div>
                <h3 class="h4 mb-3">Career Guidance</h3>
                <p class="text-muted mb-4">Professional guidance to help students choose the right career path.</p>
                <ul class="list-unstyled text-start">
                    <li><i class="bi bi-check-circle text-success me-2"></i>Career assessment</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Industry insights</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Skill development</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Mentorship programs</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- CTA Section -->
    <div class="row mt-5">
        <div class="col-12 text-center">
            <div class="bg-light p-5 rounded">
                <h2 class="h3 mb-3">Ready to Get Started?</h2>
                <p class="text-muted mb-4">Join thousands of students who have found success with our services.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('teachers.index') }}" class="btn btn-primary btn-lg">Find Teachers</a>
                    <a href="{{ route('institutes.index') }}" class="btn btn-outline-primary btn-lg">Browse Institutes</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 