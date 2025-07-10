@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-info text-white text-center py-4">
                    <h1 class="mb-0">
                        <i class="fas fa-shield-alt me-3"></i>Privacy Policy
                    </h1>
                </div>
                
                <div class="card-body p-5">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Last Updated:</strong> {{ now()->format('F j, Y') }}
                    </div>
                    
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
                        <div class="alert alert-light">
                            <h5 class="alert-heading">
                                <i class="fas fa-envelope me-2"></i>Questions About This Policy?
                            </h5>
                            <p class="mb-0">
                                If you have any questions about this Privacy Policy, please contact us at 
                                <a href="mailto:{{ App\Models\SiteSetting::get('admin_email', 'privacy@educationplatform.com') }}" class="text-decoration-none">
                                    {{ App\Models\SiteSetting::get('admin_email', 'privacy@educationplatform.com') }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('home') }}" class="btn btn-primary me-2">
                                <i class="fas fa-home me-2"></i>Back to Home
                            </a>
                            <a href="{{ route('contact') }}" class="btn btn-outline-primary">
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
    color: #0891b2;
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
    border-left: 4px solid #0891b2;
    padding-left: 1rem;
    margin: 1.5rem 0;
    background-color: #f0f9ff;
    padding: 1rem;
    border-radius: 0.375rem;
}

.formatted-content {
    font-size: 1.1rem;
    line-height: 1.7;
}
</style>
@endsection 