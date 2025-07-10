@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h1 class="mb-0">
                        <i class="fas fa-info-circle me-3"></i>About Us
                    </h1>
                </div>
                
                <div class="card-body p-5">
                    <div class="content-area">
                        @if(Str::contains($content, '#') || Str::contains($content, '**'))
                            {!! \Illuminate\Support\Str::markdown($content) !!}
                        @else
                            <div class="formatted-content">
                                {!! nl2br(e($content)) !!}
                            </div>
                        @endif
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <h4 class="text-primary mb-3">Get in Touch</h4>
                        <div class="row">
                            @if(App\Models\SiteSetting::get('admin_email'))
                                <div class="col-md-4 mb-3">
                                    <div class="contact-item">
                                        <i class="fas fa-envelope text-primary fa-2x mb-2"></i>
                                        <p class="mb-0">
                                            <strong>Email</strong><br>
                                            <a href="mailto:{{ App\Models\SiteSetting::get('admin_email') }}" class="text-decoration-none">
                                                {{ App\Models\SiteSetting::get('admin_email') }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            @endif
                            
                            @if(App\Models\SiteSetting::get('contact_phone'))
                                <div class="col-md-4 mb-3">
                                    <div class="contact-item">
                                        <i class="fas fa-phone text-primary fa-2x mb-2"></i>
                                        <p class="mb-0">
                                            <strong>Phone</strong><br>
                                            <a href="tel:{{ App\Models\SiteSetting::get('contact_phone') }}" class="text-decoration-none">
                                                {{ App\Models\SiteSetting::get('contact_phone') }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            @endif
                            
                            @if(App\Models\SiteSetting::get('contact_address'))
                                <div class="col-md-4 mb-3">
                                    <div class="contact-item">
                                        <i class="fas fa-map-marker-alt text-primary fa-2x mb-2"></i>
                                        <p class="mb-0">
                                            <strong>Address</strong><br>
                                            {{ App\Models\SiteSetting::get('contact_address') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content-area h1, .content-area h2, .content-area h3 {
    color: #2563eb;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.content-area h1:first-child, .content-area h2:first-child, .content-area h3:first-child {
    margin-top: 0;
}

.content-area p {
    line-height: 1.7;
    margin-bottom: 1.2rem;
}

.content-area ul, .content-area ol {
    margin-bottom: 1.2rem;
    padding-left: 2rem;
}

.content-area li {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

.content-area blockquote {
    border-left: 4px solid #2563eb;
    padding-left: 1rem;
    margin: 1.5rem 0;
    background-color: #f8fafc;
    padding: 1rem;
    border-radius: 0.375rem;
}

.contact-item {
    text-align: center;
    padding: 1rem;
}

.contact-item i {
    display: block;
    margin: 0 auto 0.5rem;
}

.formatted-content {
    font-size: 1.1rem;
    line-height: 1.7;
}
</style>
@endsection 