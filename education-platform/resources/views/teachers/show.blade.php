@extends('layouts.app')

@section('title', ($teacher->user->name ?? 'Teacher') . ' - ' . ($teacher->subject->name ?? $teacher->specialization ?? 'Teacher Profile'))
@section('meta_description', 'Learn from ' . ($teacher->user->name ?? 'an experienced teacher') . '. ' . ($teacher->bio ? Str::limit($teacher->bio, 150) : 'Qualified educator ready to help you achieve your learning goals.'))

@section('content')
<div class="teacher-profile-page">
    <!-- Teacher Header -->
    <section class="teacher-header py-5 bg-gradient-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="teacher-info d-flex align-items-start">
                        <div class="teacher-avatar-wrapper me-4">
                            <img src="{{ $teacher->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($teacher->user->name ?? 'Teacher') . '&size=200&background=random' }}" 
                                 alt="{{ $teacher->user->name ?? 'Teacher' }}" 
                                 class="teacher-avatar rounded-circle border border-white border-3">
                            @if($teacher->availability_status === 'available')
                                <span class="status-indicator online"></span>
                            @endif
                        </div>
                        
                        <div class="teacher-details flex-grow-1">
                            <h1 class="teacher-name mb-2">{{ $teacher->user->name ?? 'Teacher Profile' }}</h1>
                            <p class="teacher-title h5 mb-3 text-white-75">
                                {{ $teacher->subject->name ?? $teacher->specialization ?? 'Subject Expert' }} Teacher
                            </p>
                            
                            <div class="teacher-meta d-flex flex-wrap align-items-center gap-4 mb-3">
                                <div class="meta-item">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    <span class="fw-semibold">{{ $teacher->rating ?? 4.0 }}</span>
                                    <span class="text-white-75"> ({{ $teacher->total_students ?? 0 }} students)</span>
                                </div>
                                
                                <div class="meta-item">
                                    <i class="fas fa-clock me-2"></i>
                                    <span>{{ $teacher->experience_years ?? 0 }}+ years experience</span>
                                </div>
                                
                                @if($teacher->hourly_rate)
                                    <div class="meta-item">
                                        <i class="fas fa-rupee-sign me-2"></i>
                                        <span class="fw-semibold">₹{{ number_format($teacher->hourly_rate) }}/hour</span>
                                    </div>
                                @endif
                                
                                @if($teacher->city || $teacher->state)
                                    <div class="meta-item">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        <span>{{ trim(($teacher->city ?? '') . ', ' . ($teacher->state ?? ''), ', ') }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            @if($teacher->user->phone || $teacher->user->email)
                                <div class="teacher-contact d-flex flex-wrap gap-3">
                                    @if($teacher->user->phone)
                                        <a href="tel:{{ $teacher->user->phone }}" class="btn btn-outline-light btn-sm">
                                            <i class="fas fa-phone me-2"></i>Call Now
                                        </a>
                                    @endif
                                    @if($teacher->user->email)
                                        <a href="mailto:{{ $teacher->user->email }}" class="btn btn-outline-light btn-sm">
                                            <i class="fas fa-envelope me-2"></i>Email
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="teacher-stats-card bg-white bg-opacity-10 backdrop-blur rounded-4 p-4">
                        <h5 class="text-white mb-3">Quick Stats</h5>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="stat-item text-center">
                                    <div class="stat-number h4 mb-1 text-warning">{{ $teacher->total_students ?? 0 }}</div>
                                    <div class="stat-label small text-white-75">Students Taught</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item text-center">
                                    <div class="stat-number h4 mb-1 text-warning">{{ $teacher->experience_years ?? 0 }}+</div>
                                    <div class="stat-label small text-white-75">Years Experience</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item text-center">
                                    <div class="stat-number h4 mb-1 text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= ($teacher->rating ?? 4) ? 'text-warning' : 'text-white-50' }} small"></i>
                                        @endfor
                                    </div>
                                    <div class="stat-label small text-white-75">Rating</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item text-center">
                                    <div class="stat-number h4 mb-1 text-warning">
                                        {{ $teacher->availability_status === 'available' ? 'Available' : 'Busy' }}
                                    </div>
                                    <div class="stat-label small text-white-75">Status</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Teacher Details -->
    <section class="teacher-details-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- About Section -->
                    @if($teacher->bio)
                        <div class="section-card mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h3 class="section-title h5 mb-3">
                                        <i class="fas fa-user-circle text-primary me-2"></i>About
                                    </h3>
                                    <p class="text-muted mb-0 lh-lg">{{ $teacher->bio }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Qualifications -->
                    @if($teacher->qualification || $teacher->qualifications)
                        <div class="section-card mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h3 class="section-title h5 mb-3">
                                        <i class="fas fa-graduation-cap text-primary me-2"></i>Qualifications
                                    </h3>
                                    <div class="qualifications-list">
                                        @if($teacher->qualifications)
                                            <p class="mb-0">{{ $teacher->qualifications }}</p>
                                        @else
                                            <p class="mb-0">{{ $teacher->qualification }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Teaching Modes -->
                    <div class="section-card mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h3 class="section-title h5 mb-3">
                                    <i class="fas fa-chalkboard-teacher text-primary me-2"></i>Teaching Options
                                </h3>
                                <div class="teaching-modes">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="mode-card text-center p-3 {{ $teacher->online_classes ? 'bg-success bg-opacity-10 border-success' : 'bg-light' }} rounded">
                                                <i class="fas fa-laptop fa-2x {{ $teacher->online_classes ? 'text-success' : 'text-muted' }} mb-2"></i>
                                                <h6>Online Classes</h6>
                                                <span class="badge {{ $teacher->online_classes ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $teacher->online_classes ? 'Available' : 'Not Available' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mode-card text-center p-3 {{ $teacher->home_tuition ? 'bg-success bg-opacity-10 border-success' : 'bg-light' }} rounded">
                                                <i class="fas fa-home fa-2x {{ $teacher->home_tuition ? 'text-success' : 'text-muted' }} mb-2"></i>
                                                <h6>Home Tuition</h6>
                                                <span class="badge {{ $teacher->home_tuition ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $teacher->home_tuition ? 'Available' : 'Not Available' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mode-card text-center p-3 {{ $teacher->institute_classes ? 'bg-success bg-opacity-10 border-success' : 'bg-light' }} rounded">
                                                <i class="fas fa-building fa-2x {{ $teacher->institute_classes ? 'text-success' : 'text-muted' }} mb-2"></i>
                                                <h6>At Institute</h6>
                                                <span class="badge {{ $teacher->institute_classes ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $teacher->institute_classes ? 'Available' : 'Not Available' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Languages -->
                    @if($teacher->languages)
                        <div class="section-card mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h3 class="section-title h5 mb-3">
                                        <i class="fas fa-language text-primary me-2"></i>Languages
                                    </h3>
                                    <div class="languages-list">
                                        @php
                                            $languages = is_array($teacher->languages) ? $teacher->languages : json_decode($teacher->languages, true) ?? [];
                                        @endphp
                                        @if(is_array($languages) && count($languages) > 0)
                                            @foreach($languages as $language)
                                                <span class="badge bg-primary me-2 mb-2">{{ $language }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Contact Card -->
                    <div class="contact-card card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-phone text-primary me-2"></i>Contact Teacher
                            </h5>
                            
                            @if($teacher->hourly_rate)
                                <div class="price-info text-center mb-4">
                                    <div class="price h3 text-primary mb-1">₹{{ number_format($teacher->hourly_rate) }}</div>
                                    <div class="price-label text-muted">Per Hour</div>
                                </div>
                            @endif
                            
                            <div class="contact-buttons d-grid gap-2">
                                @if($teacher->user->phone)
                                    <a href="tel:{{ $teacher->user->phone }}" class="btn btn-primary">
                                        <i class="fas fa-phone me-2"></i>Call Now
                                    </a>
                                @endif
                                @if($teacher->user->email)
                                    <a href="mailto:{{ $teacher->user->email }}" class="btn btn-outline-primary">
                                        <i class="fas fa-envelope me-2"></i>Send Message
                                    </a>
                                @endif
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $teacher->user->phone ?? '') }}?text=Hi, I found your profile on {{ config('app.name') }}. I would like to inquire about your teaching services." 
                                   class="btn btn-success" target="_blank">
                                    <i class="fab fa-whatsapp me-2"></i>WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Institute Info -->
                    @if($teacher->institute)
                        <div class="institute-card card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-3">
                                    <i class="fas fa-university text-primary me-2"></i>Associated Institute
                                </h5>
                                
                                <div class="institute-info">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ $teacher->institute->logo ?: 'https://ui-avatars.com/api/?name=' . urlencode($teacher->institute->institute_name) . '&size=100&background=random' }}" 
                                             alt="{{ $teacher->institute->institute_name }}" 
                                             class="institute-logo rounded me-3" width="50" height="50">
                                        <div>
                                            <h6 class="mb-1">{{ $teacher->institute->institute_name }}</h6>
                                            <small class="text-muted">{{ $teacher->institute->institute_type ?? 'Institute' }}</small>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('institutes.show', $teacher->institute->slug ?: 'institute-' . $teacher->institute->id) }}" 
                                       class="btn btn-outline-primary btn-sm w-100">
                                        View Institute
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Quick Info -->
                    <div class="quick-info-card card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-info-circle text-primary me-2"></i>Quick Info
                            </h5>
                            
                            <div class="info-list">
                                <div class="info-item d-flex justify-content-between mb-2">
                                    <span class="text-muted">Teaching Mode:</span>
                                    <span class="fw-semibold">{{ ucfirst(is_string($teacher->teaching_mode) ? ($teacher->teaching_mode ?? 'Both') : 'Both') }}</span>
                                </div>
                                
                                <div class="info-item d-flex justify-content-between mb-2">
                                    <span class="text-muted">Experience:</span>
                                    <span class="fw-semibold">{{ $teacher->experience_years ?? 0 }}+ years</span>
                                </div>
                                
                                <div class="info-item d-flex justify-content-between mb-2">
                                    <span class="text-muted">Verification:</span>
                                    <span class="badge {{ $teacher->verification_status === 'verified' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst(is_string($teacher->verification_status) ? ($teacher->verification_status ?? 'Pending') : 'Pending') }}
                                    </span>
                                </div>
                                
                                @if($teacher->travel_radius_km)
                                    <div class="info-item d-flex justify-content-between mb-2">
                                        <span class="text-muted">Travel Radius:</span>
                                        <span class="fw-semibold">{{ $teacher->travel_radius_km }} km</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Teachers -->
    @if(isset($relatedTeachers) && $relatedTeachers->count() > 0)
        <section class="related-teachers py-5 bg-light">
            <div class="container">
                <h3 class="section-title mb-4">Other {{ $teacher->subject->name ?? $teacher->specialization ?? 'Subject' }} Teachers</h3>
                
                <div class="row g-4">
                    @foreach($relatedTeachers as $related)
                        <div class="col-lg-3 col-md-6">
                            <div class="teacher-card card h-100 border-0 shadow-sm">
                                <div class="card-body p-4 text-center">
                                    <img src="{{ $related->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($related->user->name ?? 'Teacher') . '&size=150&background=random' }}" 
                                         alt="{{ $related->user->name ?? 'Teacher' }}" 
                                         class="rounded-circle mb-3" width="80" height="80">
                                    
                                    <h5 class="card-title mb-2">{{ $related->user->name ?? 'Teacher' }}</h5>
                                    <p class="text-primary mb-2">{{ $related->subject->name ?? $related->specialization ?? 'Teacher' }}</p>
                                    
                                    <div class="rating mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= ($related->rating ?? 4) ? 'text-warning' : 'text-muted' }} small"></i>
                                        @endfor
                                        <span class="ms-1 small text-muted">{{ $related->rating ?? 4.0 }}</span>
                                    </div>
                                    
                                    <a href="{{ route('teachers.show', $related->slug ?: 'teacher-' . $related->id) }}" 
                                       class="btn btn-primary btn-sm">View Profile</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
@endsection

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.teacher-avatar {
    width: 120px;
    height: 120px;
    object-fit: cover;
}

.teacher-avatar-wrapper {
    position: relative;
}

.status-indicator {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
}

.status-indicator.online {
    background-color: #22c55e;
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75) !important;
}

.backdrop-blur {
    backdrop-filter: blur(10px);
}

.section-card .card {
    transition: all 0.3s ease;
}

.section-card .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}

.mode-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.mode-card:hover {
    transform: translateY(-2px);
}

.teacher-card:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease;
}

.contact-buttons .btn {
    font-weight: 500;
}

.institute-logo {
    object-fit: cover;
}

@media (max-width: 768px) {
    .teacher-info {
        flex-direction: column;
        text-align: center;
    }
    
    .teacher-avatar-wrapper {
        margin-bottom: 1rem;
        margin-right: 0 !important;
    }
    
    .teacher-meta {
        justify-content: center;
    }
    
    .teacher-contact {
        justify-content: center;
    }
}
</style>
@endpush 