@extends('layouts.app')

@section('title', $teacherProfile->user->name . ' - Teacher Profile')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Teacher Profile Card -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="{{ $teacherProfile->user->profile_image ? asset('storage/' . $teacherProfile->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($teacherProfile->user->name) . '&size=200&background=random' }}" 
                                 alt="{{ $teacherProfile->user->name }}" 
                                 class="rounded-circle mb-3" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                            
                            @if($teacherProfile->availability_status === 'available')
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-secondary">Busy</span>
                            @endif
                        </div>
                        
                        <div class="col-md-8">
                            <h2 class="mb-2">{{ $teacherProfile->user->name }}</h2>
                            <p class="text-muted mb-3">{{ $teacherProfile->qualification }}</p>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Experience</small>
                                    <div class="fw-bold">{{ $teacherProfile->experience_years }} years</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Rating</small>
                                    <div class="fw-bold">
                                        {{ number_format($teacherProfile->rating, 1) }} 
                                        <i class="bi bi-star-fill text-warning"></i>
                                    </div>
                                </div>
                            </div>
                            
                            @if($teacherProfile->bio)
                                <p class="mb-3">{{ $teacherProfile->bio }}</p>
                            @endif
                            
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contactModal">
                                    <i class="bi bi-envelope me-1"></i>Contact Teacher
                                </button>
                                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bookingModal">
                                    <i class="bi bi-calendar-plus me-1"></i>Book Session
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subjects Taught -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Subjects Taught</h5>
                </div>
                <div class="card-body">
                    @if($subjects->count() > 0)
                        <div class="row">
                            @foreach($subjects as $subject)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                                        <div>
                                            <h6 class="mb-1">{{ $subject->name }}</h6>
                                            <small class="text-muted">{{ $subject->pivot->proficiency_level ?? 'Intermediate' }}</small>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold">₹{{ number_format($subject->pivot->subject_rate ?? 0) }}/hr</div>
                                            <small class="text-muted">{{ $subject->pivot->proficiency_level ?? 'Intermediate' }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">No subjects added yet.</p>
                    @endif
                </div>
            </div>

            <!-- Reviews -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Reviews</h5>
                    <a href="{{ route('teacher.reviews') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($reviews->count() > 0)
                        @foreach($reviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $review->user->profile_image ? asset('storage/' . $review->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&size=40&background=random' }}" 
                                             alt="{{ $review->user->name }}" 
                                             class="rounded-circle me-2" 
                                             width="40" height="40">
                                        <div>
                                            <div class="fw-bold">{{ $review->user->name }}</div>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                </div>
                                <p class="mb-2">{{ $review->comment }}</p>
                                @if($review->teacher_reply)
                                    <div class="bg-light p-3 rounded">
                                        <small class="text-muted">Teacher's reply:</small>
                                        <p class="mb-0">{{ $review->teacher_reply }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No reviews yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contact Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0">Contact Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Email</small>
                        <div>{{ $teacherProfile->user->email }}</div>
                    </div>
                    
                    @if($teacherProfile->languages)
                        <div class="mb-3">
                            <small class="text-muted">Languages</small>
                            <div>
                                @foreach($teacherProfile->languages as $language)
                                    <span class="badge bg-light text-dark me-1">{{ $language }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    @if($teacherProfile->teaching_mode)
                        <div class="mb-3">
                            <small class="text-muted">Teaching Mode</small>
                            <div class="text-capitalize">{{ $teacherProfile->teaching_mode }}</div>
                        </div>
                    @endif
                    
                    @if($teacherProfile->institute)
                        <div class="mb-3">
                            <small class="text-muted">Institute</small>
                            <div>{{ $teacherProfile->institute->institute_name }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h4 mb-1">{{ $teacherProfile->total_students }}</div>
                            <small class="text-muted">Students</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-1">{{ $subjects->count() }}</div>
                            <small class="text-muted">Subjects</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contact {{ $teacherProfile->user->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" class="form-control" placeholder="What would you like to discuss?">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" rows="4" placeholder="Your message..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Send Message</button>
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Book a Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <select class="form-select">
                            <option>Select a subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }} - ₹{{ number_format($subject->pivot->subject_rate ?? 0) }}/hr</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date & Time</label>
                        <input type="datetime-local" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration</label>
                        <select class="form-select">
                            <option>1 hour</option>
                            <option>1.5 hours</option>
                            <option>2 hours</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Book Session</button>
            </div>
        </div>
    </div>
</div>
@endsection 