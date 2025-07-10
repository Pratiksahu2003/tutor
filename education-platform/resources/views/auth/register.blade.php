@extends('layouts.app')

@section('title', 'Register - Education Platform')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Create Your Account</h3>
                    <p class="mb-0 small">Join our educational community</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}" id="registrationForm">
                        @csrf

                        <!-- Role Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">I want to register as:</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card role-card h-100" onclick="selectRole('student')">
                                        <div class="card-body text-center">
                                            <i class="bi bi-person-circle text-primary fs-1"></i>
                                            <h5 class="mt-2">Student</h5>
                                            <p class="text-muted small mb-0">Find teachers and institutes to learn</p>
                                            <input type="radio" name="role" value="student" class="form-check-input mt-2" checked>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card role-card h-100" onclick="selectRole('teacher')">
                                        <div class="card-body text-center">
                                            <i class="bi bi-person-badge text-success fs-1"></i>
                                            <h5 class="mt-2">Teacher</h5>
                                            <p class="text-muted small mb-0">Share knowledge and teach students</p>
                                            <input type="radio" name="role" value="teacher" class="form-check-input mt-2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <div class="card role-card h-100" onclick="selectRole('institute')">
                                        <div class="card-body text-center">
                                            <i class="bi bi-building text-warning fs-1"></i>
                                            <h5 class="mt-2">Institute</h5>
                                            <p class="text-muted small mb-0">Manage educational institution</p>
                                            <input type="radio" name="role" value="institute" class="form-check-input mt-2">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card role-card h-100" onclick="selectRole('parent')">
                                        <div class="card-body text-center">
                                            <i class="bi bi-people text-info fs-1"></i>
                                            <h5 class="mt-2">Parent</h5>
                                            <p class="text-muted small mb-0">Find education for your child</p>
                                            <input type="radio" name="role" value="parent" class="form-check-input mt-2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input id="name" class="form-control @error('name') is-invalid @enderror" 
                                       type="text" name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input id="email" class="form-control @error('email') is-invalid @enderror" 
                                       type="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       type="tel" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="city" class="form-label">City</label>
                                <input id="city" class="form-control @error('city') is-invalid @enderror" 
                                       type="text" name="city" value="{{ old('city') }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="state" class="form-label">State</label>
                                <input id="state" class="form-control @error('state') is-invalid @enderror" 
                                       type="text" name="state" value="{{ old('state') }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Role-specific fields -->
                        <div id="roleSpecificFields">
                            <!-- Student specific fields -->
                            <div id="studentFields" class="role-specific-fields">
                                <h6 class="text-primary mb-3">Student Information</h6>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="grade_level" class="form-label">Current Grade/Level</label>
                                        <select id="grade_level" class="form-select" name="grade_level">
                                            <option value="">Select Grade</option>
                                            <option value="primary">Primary (1-5)</option>
                                            <option value="middle">Middle School (6-8)</option>
                                            <option value="high">High School (9-12)</option>
                                            <option value="college">College</option>
                                            <option value="university">University</option>
                                            <option value="adult">Adult Learning</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="learning_goals" class="form-label">Learning Goals</label>
                                        <input id="learning_goals" class="form-control" type="text" 
                                               name="learning_goals" placeholder="What do you want to learn?">
                                    </div>
                                </div>
                            </div>

                            <!-- Teacher specific fields -->
                            <div id="teacherFields" class="role-specific-fields" style="display: none;">
                                <h6 class="text-success mb-3">Teacher Information</h6>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="qualification" class="form-label">Highest Qualification</label>
                                        <input id="qualification" class="form-control" type="text" 
                                               name="qualification" placeholder="e.g., Master's in Mathematics">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="experience" class="form-label">Years of Experience</label>
                                        <select id="experience" class="form-select" name="experience">
                                            <option value="">Select Experience</option>
                                            <option value="0-1">0-1 years</option>
                                            <option value="1-3">1-3 years</option>
                                            <option value="3-5">3-5 years</option>
                                            <option value="5-10">5-10 years</option>
                                            <option value="10+">10+ years</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="specialization" class="form-label">Subject Specialization</label>
                                    <input id="specialization" class="form-control" type="text" 
                                           name="specialization" placeholder="e.g., Mathematics, Science, English">
                                </div>
                            </div>

                            <!-- Institute specific fields -->
                            <div id="instituteFields" class="role-specific-fields" style="display: none;">
                                <h6 class="text-warning mb-3">Institute Information</h6>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="institute_name" class="form-label">Institute Name</label>
                                        <input id="institute_name" class="form-control" type="text" 
                                               name="institute_name" placeholder="Your institute name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="institute_type" class="form-label">Institute Type</label>
                                        <select id="institute_type" class="form-select" name="institute_type">
                                            <option value="">Select Type</option>
                                            <option value="school">School</option>
                                            <option value="college">College</option>
                                            <option value="university">University</option>
                                            <option value="coaching">Coaching Center</option>
                                            <option value="tutoring">Tutoring Center</option>
                                            <option value="online">Online Platform</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="institute_address" class="form-label">Institute Address</label>
                                    <textarea id="institute_address" class="form-control" name="institute_address" 
                                              rows="2" placeholder="Full address of your institute"></textarea>
                                </div>
                            </div>

                            <!-- Parent specific fields -->
                            <div id="parentFields" class="role-specific-fields" style="display: none;">
                                <h6 class="text-info mb-3">Parent Information</h6>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="children_count" class="form-label">Number of Children</label>
                                        <select id="children_count" class="form-select" name="children_count">
                                            <option value="">Select</option>
                                            <option value="1">1 child</option>
                                            <option value="2">2 children</option>
                                            <option value="3">3 children</option>
                                            <option value="4+">4+ children</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="child_grades" class="form-label">Children's Grades/Levels</label>
                                        <input id="child_grades" class="form-control" type="text" 
                                               name="child_grades" placeholder="e.g., Grade 5, Grade 10">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Fields -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input id="password" class="form-control @error('password') is-invalid @enderror"
                                       type="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Must be at least 8 characters long</div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input id="password_confirmation" class="form-control" 
                                       type="password" name="password_confirmation" required>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror" 
                                       type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="{{ route('terms') }}" target="_blank">Terms and Conditions</a> 
                                    and <a href="{{ route('privacy') }}" target="_blank">Privacy Policy</a>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Create Account
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center mt-3">
                            <p class="mb-0">Already have an account? 
                                <a href="{{ route('login') }}" class="text-decoration-none">Sign in</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .role-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
    }

    .role-card:hover {
        border-color: var(--bs-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .role-card.selected {
        border-color: var(--bs-primary);
        background-color: rgba(var(--bs-primary-rgb), 0.1);
    }

    .role-specific-fields {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
    function selectRole(role) {
        // Update radio buttons
        document.querySelectorAll('input[name="role"]').forEach(radio => {
            radio.checked = radio.value === role;
        });

        // Update card styling
        document.querySelectorAll('.role-card').forEach(card => {
            card.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');

        // Show/hide role-specific fields
        document.querySelectorAll('.role-specific-fields').forEach(field => {
            field.style.display = 'none';
        });
        
        const roleFields = document.getElementById(role + 'Fields');
        if (roleFields) {
            roleFields.style.display = 'block';
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        selectRole('student'); // Default to student
    });

    // Form validation
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }
    });
</script>
@endpush
