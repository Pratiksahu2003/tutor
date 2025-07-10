@extends('layouts.app')

@section('title', ($institute->institute_name ?? 'Institute') . ' - ' . ($institute->institute_type ?? 'Educational Institute'))
@section('meta_description', 'Explore ' . ($institute->institute_name ?? 'our institute') . '. ' . ($institute->description ? Str::limit($institute->description, 150) : 'Quality education and experienced faculty to help you achieve your goals.'))

@section('content')
<div class="institute-profile-page">
    <!-- Institute Header -->
    <section class="institute-header py-5 bg-gradient-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="institute-info d-flex align-items-start">
                        <div class="institute-logo-wrapper me-4">
                            <img src="{{ $institute->logo ?: 'https://ui-avatars.com/api/?name=' . urlencode($institute->institute_name ?? 'Institute') . '&size=200&background=random' }}" 
                                 alt="{{ $institute->institute_name ?? 'Institute' }}" 
                                 class="institute-logo rounded-3 border border-white border-3">
                            @if($institute->verification_status === 'verified')
                                <span class="verification-badge">
                                    <i class="fas fa-check-circle text-success"></i>
                                </span>
                            @endif
                        </div>
                        
                        <div class="institute-details flex-grow-1">
                            <h1 class="institute-name mb-2">{{ $institute->institute_name ?? 'Institute Profile' }}</h1>
                            <p class="institute-type h5 mb-3 text-white-75">
                                {{ $institute->institute_type ?? 'Educational Institute' }}
                            </p>
                            
                            <div class="institute-meta d-flex flex-wrap align-items-center gap-4 mb-3">
                                <div class="meta-item">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    <span class="fw-semibold">{{ $institute->rating ?? 4.0 }}</span>
                                    <span class="text-white-75"> ({{ $institute->total_reviews ?? 0 }} reviews)</span>
                                </div>
                                
                                @if($institute->established_year)
                                    <div class="meta-item">
                                        <i class="fas fa-calendar me-2"></i>
                                        <span>Established {{ $institute->established_year }}</span>
                                    </div>
                                @endif
                                
                                <div class="meta-item">
                                    <i class="fas fa-users me-2"></i>
                                    <span>{{ $institute->total_students ?? 0 }}+ students</span>
                                </div>
                                
                                @if($institute->city || $institute->state)
                                    <div class="meta-item">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        <span>{{ trim(($institute->city ?? '') . ', ' . ($institute->state ?? ''), ', ') }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            @if($institute->user && ($institute->user->phone || $institute->user->email))
                                <div class="institute-contact d-flex flex-wrap gap-3">
                                    @if($institute->user->phone)
                                        <a href="tel:{{ $institute->user->phone }}" class="btn btn-outline-light btn-sm">
                                            <i class="fas fa-phone me-2"></i>Call Now
                                        </a>
                                    @endif
                                    @if($institute->user->email)
                                        <a href="mailto:{{ $institute->user->email }}" class="btn btn-outline-light btn-sm">
                                            <i class="fas fa-envelope me-2"></i>Email
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="institute-stats-card bg-white bg-opacity-10 backdrop-blur rounded-4 p-4">
                        <h5 class="text-white mb-3">Institute Overview</h5>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="stat-item text-center">
                                    <div class="stat-number h4 mb-1 text-warning">{{ $institute->total_students ?? 0 }}</div>
                                    <div class="stat-label small text-white-75">Students</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item text-center">
                                    <div class="stat-number h4 mb-1 text-warning">{{ $institute->teachers()->count() ?? 0 }}</div>
                                    <div class="stat-label small text-white-75">Teachers</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item text-center">
                                    <div class="stat-number h4 mb-1 text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= ($institute->rating ?? 4) ? 'text-warning' : 'text-white-50' }} small"></i>
                                        @endfor
                                    </div>
                                    <div class="stat-label small text-white-75">Rating</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item text-center">
                                    <div class="stat-number h4 mb-1 text-warning">
                                        @if($institute->established_year)
                                            {{ date('Y') - $institute->established_year }}+
                                        @else
                                            -
                                        @endif
                                    </div>
                                    <div class="stat-label small text-white-75">Years</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Institute Details -->
    <section class="institute-details-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- About Section -->
                    @if($institute->description)
                        <div class="section-card mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h3 class="section-title h5 mb-3">
                                        <i class="fas fa-info-circle text-primary me-2"></i>About {{ $institute->institute_name }}
                                    </h3>
                                    <p class="text-muted mb-0 lh-lg">{{ $institute->description }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Facilities -->
                    @if($institute->facilities)
                        <div class="section-card mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h3 class="section-title h5 mb-3">
                                        <i class="fas fa-building text-primary me-2"></i>Facilities
                                    </h3>
                                    <div class="facilities-grid">
                                        @php
                                            $facilities = is_array($institute->facilities) ? $institute->facilities : json_decode($institute->facilities, true) ?? [];
                                        @endphp
                                        @if(is_array($facilities) && count($facilities) > 0)
                                            <div class="row g-3">
                                                @foreach($facilities as $facility)
                                                    <div class="col-md-6">
                                                        <div class="facility-item d-flex align-items-center p-3 bg-light rounded">
                                                            <i class="fas fa-check-circle text-success me-3"></i>
                                                            <span>{{ $facility }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted mb-0">Facility information not available</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Courses/Subjects -->
                    @if($institute->subjects && $institute->subjects->count() > 0)
                        <div class="section-card mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h3 class="section-title h5 mb-3">
                                        <i class="fas fa-book text-primary me-2"></i>Subjects Offered
                                    </h3>
                                    <div class="subjects-grid">
                                        <div class="row g-2">
                                            @foreach($institute->subjects as $subject)
                                                <div class="col-md-3 col-sm-4 col-6">
                                                    <div class="subject-badge">
                                                        <span class="badge bg-primary-soft text-primary w-100 p-2">{{ $subject->name }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Teachers -->
                    @if(isset($teachers) && $teachers->count() > 0)
                        <div class="section-card mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h3 class="section-title h5 mb-0">
                                            <i class="fas fa-chalkboard-teacher text-primary me-2"></i>Our Teachers
                                        </h3>
                                        @if($teachers->total() > $teachers->count())
                                            <a href="{{ route('institutes.teachers', $institute->slug ?: 'institute-' . $institute->id) }}" 
                                               class="btn btn-outline-primary btn-sm">View All Teachers</a>
                                        @endif
                                    </div>
                                    
                                    <div class="row g-4">
                                        @foreach($teachers as $teacher)
                                            <div class="col-lg-6">
                                                <div class="teacher-mini-card card border-0 bg-light">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $teacher->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($teacher->user->name ?? 'Teacher') . '&size=100&background=random' }}" 
                                                                 alt="{{ $teacher->user->name ?? 'Teacher' }}" 
                                                                 class="rounded-circle me-3" width="50" height="50">
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1">{{ $teacher->user->name ?? 'Teacher' }}</h6>
                                                                <p class="text-primary small mb-1">{{ $teacher->specialization ?? 'Teacher' }}</p>
                                                                <div class="rating">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        <i class="fas fa-star {{ $i <= ($teacher->rating ?? 4) ? 'text-warning' : 'text-muted' }} small"></i>
                                                                    @endfor
                                                                    <span class="ms-1 small text-muted">{{ $teacher->rating ?? 4.0 }}</span>
                                                                </div>
                                                            </div>
                                                            <a href="{{ route('teachers.show', $teacher->slug ?: 'teacher-' . $teacher->id) }}" 
                                                               class="btn btn-primary btn-sm">View</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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
                                <i class="fas fa-phone text-primary me-2"></i>Contact Institute
                            </h5>
                            
                            @if($institute->user)
                                <div class="contact-info mb-4">
                                    @if($institute->user->phone)
                                        <div class="contact-item mb-2">
                                            <i class="fas fa-phone text-muted me-2"></i>
                                            <span>{{ $institute->user->phone }}</span>
                                        </div>
                                    @endif
                                    @if($institute->user->email)
                                        <div class="contact-item mb-2">
                                            <i class="fas fa-envelope text-muted me-2"></i>
                                            <span>{{ $institute->user->email }}</span>
                                        </div>
                                    @endif
                                    @if($institute->address)
                                        <div class="contact-item mb-2">
                                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                            <span>{{ $institute->address }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="contact-buttons d-grid gap-2">
                                @if($institute->user && $institute->user->phone)
                                    <a href="tel:{{ $institute->user->phone }}" class="btn btn-primary">
                                        <i class="fas fa-phone me-2"></i>Call Now
                                    </a>
                                @endif
                                @if($institute->user && $institute->user->email)
                                    <a href="mailto:{{ $institute->user->email }}" class="btn btn-outline-primary">
                                        <i class="fas fa-envelope me-2"></i>Send Email
                                    </a>
                                @endif
                                @if($institute->user && $institute->user->phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $institute->user->phone) }}?text=Hi, I found your institute {{ $institute->institute_name }} on {{ config('app.name') }}. I would like to inquire about your courses." 
                                       class="btn btn-success" target="_blank">
                                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Info -->
                    <div class="quick-info-card card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-info-circle text-primary me-2"></i>Quick Info
                            </h5>
                            
                            <div class="info-list">
                                <div class="info-item d-flex justify-content-between mb-2">
                                    <span class="text-muted">Institute Type:</span>
                                    <span class="fw-semibold">{{ $institute->institute_type ?? 'Educational' }}</span>
                                </div>
                                
                                @if($institute->established_year)
                                    <div class="info-item d-flex justify-content-between mb-2">
                                        <span class="text-muted">Established:</span>
                                        <span class="fw-semibold">{{ $institute->established_year }}</span>
                                    </div>
                                @endif
                                
                                <div class="info-item d-flex justify-content-between mb-2">
                                    <span class="text-muted">Verification:</span>
                                    <span class="badge {{ $institute->verification_status === 'verified' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($institute->verification_status ?? 'Pending') }}
                                    </span>
                                </div>
                                
                                <div class="info-item d-flex justify-content-between mb-2">
                                    <span class="text-muted">Total Students:</span>
                                    <span class="fw-semibold">{{ number_format($institute->total_students ?? 0) }}+</span>
                                </div>
                                
                                @if($institute->subjects)
                                    <div class="info-item d-flex justify-content-between mb-2">
                                        <span class="text-muted">Subjects:</span>
                                        <span class="fw-semibold">{{ $institute->subjects->count() }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Working Hours -->
                    @if($institute->working_hours)
                        <div class="working-hours-card card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-3">
                                    <i class="fas fa-clock text-primary me-2"></i>Working Hours
                                </h5>
                                
                                @php
                                    $workingHours = is_array($institute->working_hours) ? $institute->working_hours : json_decode($institute->working_hours, true) ?? [];
                                @endphp
                                
                                @if(is_array($workingHours) && count($workingHours) > 0)
                                    <div class="hours-list">
                                        @foreach($workingHours as $day => $hours)
                                            <div class="hours-item d-flex justify-content-between mb-1">
                                                <span class="text-muted">{{ ucfirst(is_string($day) ? $day : 'Day') }}:</span>
                                                <span class="fw-semibold">{{ is_string($hours) ? $hours : 'Contact for hours' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted small mb-0">Contact institute for working hours</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Related Institutes -->
    @if(isset($relatedInstitutes) && $relatedInstitutes->count() > 0)
        <section class="related-institutes py-5 bg-light">
            <div class="container">
                <h3 class="section-title mb-4">Other Institutes in {{ $institute->city ?? 'Your Area' }}</h3>
                
                <div class="row g-4">
                    @foreach($relatedInstitutes as $related)
                        <div class="col-lg-3 col-md-6">
                            <div class="institute-card card h-100 border-0 shadow-sm">
                                <div class="card-body p-4 text-center">
                                    <img src="{{ $related->logo ?: 'https://ui-avatars.com/api/?name=' . urlencode($related->institute_name) . '&size=150&background=random' }}" 
                                         alt="{{ $related->institute_name }}" 
                                         class="rounded-3 mb-3" width="80" height="80">
                                    
                                    <h5 class="card-title mb-2">{{ $related->institute_name }}</h5>
                                    <p class="text-primary mb-2">{{ $related->institute_type ?? 'Institute' }}</p>
                                    
                                    <div class="rating mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= ($related->rating ?? 4) ? 'text-warning' : 'text-muted' }} small"></i>
                                        @endfor
                                        <span class="ms-1 small text-muted">{{ $related->rating ?? 4.0 }}</span>
                                    </div>
                                    
                                    <a href="{{ route('institutes.show', $related->slug ?: 'institute-' . $related->id) }}" 
                                       class="btn btn-primary btn-sm">View Institute</a>
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

.institute-logo {
    width: 120px;
    height: 120px;
    object-fit: cover;
}

.institute-logo-wrapper {
    position: relative;
}

.verification-badge {
    position: absolute;
    bottom: 10px;
    right: 10px;
    font-size: 1.5rem;
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

.facility-item {
    transition: all 0.3s ease;
}

.facility-item:hover {
    background-color: #e3f2fd !important;
    transform: translateX(5px);
}

.bg-primary-soft {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.teacher-mini-card {
    transition: all 0.3s ease;
}

.teacher-mini-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.institute-card:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease;
}

.contact-buttons .btn {
    font-weight: 500;
}

@media (max-width: 768px) {
    .institute-info {
        flex-direction: column;
        text-align: center;
    }
    
    .institute-logo-wrapper {
        margin-bottom: 1rem;
        margin-right: 0 !important;
    }
    
    .institute-meta {
        justify-content: center;
    }
    
    .institute-contact {
        justify-content: center;
    }
}
</style>
@endpush 