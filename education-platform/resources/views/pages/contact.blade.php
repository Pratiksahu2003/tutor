@extends('layouts.app')

@section('title', 'Contact Us - Education Platform')
@section('meta_description', 'Get in touch with our team. We are here to help you with any questions about our education platform.')

@section('content')
<!-- Page Header -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <h1 class="display-4 fw-bold">Contact Us</h1>
                <p class="lead">We're here to help. Get in touch with our team.</p>
            </div>
            <div class="col-lg-4 text-end" data-aos="fade-left">
                <i class="bi bi-chat-dots display-1 opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Alert Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show m-0" role="alert">
    <div class="container">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
    <div class="container">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-8" data-aos="fade-right">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4">Send us a Message</h3>
                        
                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="inquiry_type" class="form-label">Inquiry Type</label>
                                    <select class="form-select @error('inquiry_type') is-invalid @enderror" 
                                            id="inquiry_type" 
                                            name="inquiry_type">
                                        <option value="general" {{ old('inquiry_type') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                        <option value="teacher" {{ old('inquiry_type') == 'teacher' ? 'selected' : '' }}>Teacher Related</option>
                                        <option value="institute" {{ old('inquiry_type') == 'institute' ? 'selected' : '' }}>Institute Partnership</option>
                                        <option value="support" {{ old('inquiry_type') == 'support' ? 'selected' : '' }}>Technical Support</option>
                                        <option value="partnership" {{ old('inquiry_type') == 'partnership' ? 'selected' : '' }}>Business Partnership</option>
                                    </select>
                                    @error('inquiry_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('subject') is-invalid @enderror" 
                                           id="subject" 
                                           name="subject" 
                                           value="{{ old('subject') }}" 
                                           required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" 
                                              name="message" 
                                              rows="6" 
                                              required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Hidden UTM fields for tracking -->
                                <input type="hidden" name="utm_source" value="{{ request('utm_source') }}">
                                <input type="hidden" name="utm_medium" value="{{ request('utm_medium') }}">
                                <input type="hidden" name="utm_campaign" value="{{ request('utm_campaign') }}">
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg px-4">
                                        <i class="bi bi-send me-2"></i>Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="col-lg-4" data-aos="fade-left">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4">Get in Touch</h3>
                        
                        <div class="contact-info">
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-geo-alt text-primary fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Address</h6>
                                    <p class="text-muted mb-0">
                                        123 Education Street<br>
                                        Learning City, LC 12345<br>
                                        United States
                                    </p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-telephone text-success fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Phone</h6>
                                    <p class="text-muted mb-0">
                                        <a href="tel:+15551234567" class="text-decoration-none">+1 (555) 123-4567</a><br>
                                        <small>Mon - Fri: 9AM - 6PM</small>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-envelope text-info fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Email</h6>
                                    <p class="text-muted mb-0">
                                        <a href="mailto:info@eduplatform.com" class="text-decoration-none">info@eduplatform.com</a><br>
                                        <a href="mailto:support@eduplatform.com" class="text-decoration-none">support@eduplatform.com</a>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-clock text-warning fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Business Hours</h6>
                                    <p class="text-muted mb-0">
                                        Monday - Friday: 9:00 AM - 6:00 PM<br>
                                        Saturday: 10:00 AM - 4:00 PM<br>
                                        Sunday: Closed
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="social-links">
                            <h6 class="fw-bold mb-3">Follow Us</h6>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-linkedin"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold">Find Us</h2>
            <p class="lead text-muted">Visit our office or reach out to us online</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10" data-aos="fade-up">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <!-- Replace with actual map embed -->
                        <div class="ratio ratio-16x9" style="min-height: 300px;">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.835!2d-122.4194!3d37.7749!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzfCsDQ2JzI5LjYiTiAxMjLCsDI1JzA5LjgiVw!5e0!3m2!1sen!2sus!4v1234567890"
                                loading="lazy"
                                allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold">Frequently Asked Questions</h2>
            <p class="lead text-muted">Quick answers to common questions</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="accordion" id="contactFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How quickly will I receive a response?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#contactFAQ">
                            <div class="accordion-body">
                                We typically respond to all inquiries within 24 hours during business days. For urgent matters, 
                                please call us directly at +1 (555) 123-4567.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                What information should I include in my message?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#contactFAQ">
                            <div class="accordion-body">
                                Please provide as much detail as possible about your inquiry, including your specific needs, 
                                location, and any relevant background information. This helps us provide you with the most 
                                accurate and helpful response.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Do you offer support in multiple languages?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#contactFAQ">
                            <div class="accordion-body">
                                Yes, we offer support in English, Spanish, and French. Please mention your preferred language 
                                in your message, and we'll assign a team member who can assist you in that language.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <h2 class="fw-bold mb-4">Still Have Questions?</h2>
                <p class="lead mb-4">
                    Our support team is always ready to help. Don't hesitate to reach out for any assistance.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="tel:+15551234567" class="btn btn-light btn-lg px-4">
                        <i class="bi bi-telephone me-2"></i>Call Us Now
                    </a>
                    <a href="{{ route('faq') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-question-circle me-2"></i>View FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Auto-populate subject based on inquiry type
document.getElementById('inquiry_type').addEventListener('change', function() {
    const subjectField = document.getElementById('subject');
    const inquiryType = this.value;
    
    if (!subjectField.value) {
        const subjects = {
            'general': 'General Inquiry',
            'teacher': 'Teacher Related Question',
            'institute': 'Institute Partnership Inquiry',
            'support': 'Technical Support Request',
            'partnership': 'Business Partnership Proposal'
        };
        
        subjectField.value = subjects[inquiryType] || '';
    }
});

// Form validation enhancement
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['name', 'email', 'subject', 'message'];
    let isValid = true;
    
    requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        document.querySelector('.is-invalid').focus();
    }
});
</script>
@endpush 