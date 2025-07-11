@extends('layouts.guest')

@section('title', 'Join Education Platform - Find Your Perfect Teacher')

@section('content')
<div class="register-container">
    <div class="register-background">
        <div class="register-overlay"></div>
        <div class="register-pattern"></div>
    </div>
    
    <div class="container">
        <div class="row min-vh-100 align-items-center">
            <div class="col-lg-6">
                <div class="register-content">
                    <div class="register-header">
                        <div class="logo-section">
                            <h1 class="logo-text">Education Platform</h1>
                            <p class="logo-subtitle">Your Gateway to Quality Education</p>
                        </div>
                        
                        <div class="welcome-section">
                            <h2 class="welcome-title">Join Our Community</h2>
                            <p class="welcome-description">
                                Connect with expert teachers and top-rated institutes. Start your learning journey 
                                with personalized education that fits your goals and schedule.
                            </p>
                        </div>
                        
                        <div class="features-list">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="feature-content">
                                    <h5>Verified Teachers</h5>
                                    <p>All teachers are verified and background-checked</p>
                                </div>
                            </div>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="feature-content">
                                    <h5>Flexible Schedule</h5>
                                    <p>Learn at your own pace and convenience</p>
                                </div>
                            </div>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="feature-content">
                                    <h5>Top Rated</h5>
                                    <p>Choose from highly rated teachers and institutes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="register-form-container">
                    <div class="register-form-card">
                        <div class="form-header">
                            <h3 class="form-title">Create Your Account</h3>
                            <p class="form-subtitle">Choose your role and get started</p>
                        </div>
                        
                        <form method="POST" action="{{ route('register') }}" class="register-form">
                            @csrf
                            
                            <!-- Role Selection -->
                            <div class="role-selection mb-4">
                                <label class="form-label">I am a:</label>
                                <div class="role-options">
                                    <div class="role-option">
                                        <input type="radio" name="role" id="role-student" value="student" class="role-input" checked>
                                        <label for="role-student" class="role-label">
                                            <div class="role-icon">
                                                <i class="fas fa-user-graduate"></i>
                                            </div>
                                            <div class="role-content">
                                                <h6>Student</h6>
                                                <p>Looking for teachers or institutes</p>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div class="role-option">
                                        <input type="radio" name="role" id="role-teacher" value="teacher" class="role-input">
                                        <label for="role-teacher" class="role-label">
                                            <div class="role-icon">
                                                <i class="fas fa-chalkboard-teacher"></i>
                                            </div>
                                            <div class="role-content">
                                                <h6>Teacher</h6>
                                                <p>Offer your teaching services</p>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div class="role-option">
                                        <input type="radio" name="role" id="role-institute" value="institute" class="role-input">
                                        <label for="role-institute" class="role-label">
                                            <div class="role-icon">
                                                <i class="fas fa-university"></i>
                                            </div>
                                            <div class="role-content">
                                                <h6>Institute</h6>
                                                <p>Manage your educational institute</p>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div class="role-option">
                                        <input type="radio" name="role" id="role-parent" value="parent" class="role-input">
                                        <label for="role-parent" class="role-label">
                                            <div class="role-icon">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <div class="role-content">
                                                <h6>Parent</h6>
                                                <p>Find teachers for your children</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Basic Information -->
                            <div class="form-section">
                                <h5 class="section-title">Basic Information</h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Full Name *</label>
                                            <input type="text" name="name" value="{{ old('name') }}" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   placeholder="Enter your full name" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email Address *</label>
                                            <input type="email" name="email" value="{{ old('email') }}" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   placeholder="Enter your email" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Phone Number</label>
                                            <input type="tel" name="phone" value="{{ old('phone') }}" 
                                                   class="form-control @error('phone') is-invalid @enderror" 
                                                   placeholder="Enter your phone number">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" 
                                                   class="form-control @error('date_of_birth') is-invalid @enderror">
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">City</label>
                                            <input type="text" name="city" value="{{ old('city') }}" 
                                                   class="form-control @error('city') is-invalid @enderror" 
                                                   placeholder="Enter your city">
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">State</label>
                                            <input type="text" name="state" value="{{ old('state') }}" 
                                                   class="form-control @error('state') is-invalid @enderror" 
                                                   placeholder="Enter your state">
                                            @error('state')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Role-Specific Information -->
                            <div class="form-section" id="teacher-fields" style="display: none;">
                                <h5 class="section-title">Teaching Information</h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Qualification *</label>
                                            <input type="text" name="qualification" value="{{ old('qualification') }}" 
                                                   class="form-control @error('qualification') is-invalid @enderror" 
                                                   placeholder="e.g., B.Tech, M.Sc, Ph.D">
                                            @error('qualification')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Experience *</label>
                                            <select name="experience" class="form-select @error('experience') is-invalid @enderror">
                                                <option value="">Select Experience</option>
                                                <option value="0-1" {{ old('experience') == '0-1' ? 'selected' : '' }}>0-1 years</option>
                                                <option value="1-3" {{ old('experience') == '1-3' ? 'selected' : '' }}>1-3 years</option>
                                                <option value="3-5" {{ old('experience') == '3-5' ? 'selected' : '' }}>3-5 years</option>
                                                <option value="5-10" {{ old('experience') == '5-10' ? 'selected' : '' }}>5-10 years</option>
                                                <option value="10+" {{ old('experience') == '10+' ? 'selected' : '' }}>10+ years</option>
                                            </select>
                                            @error('experience')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Specialization *</label>
                                            <input type="text" name="specialization" value="{{ old('specialization') }}" 
                                                   class="form-control @error('specialization') is-invalid @enderror" 
                                                   placeholder="e.g., Mathematics, Physics, English">
                                            @error('specialization')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section" id="institute-fields" style="display: none;">
                                <h5 class="section-title">Institute Information</h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Institute Name *</label>
                                            <input type="text" name="institute_name" value="{{ old('institute_name') }}" 
                                                   class="form-control @error('institute_name') is-invalid @enderror" 
                                                   placeholder="Enter institute name">
                                            @error('institute_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Institute Type *</label>
                                            <select name="institute_type" class="form-select @error('institute_type') is-invalid @enderror">
                                                <option value="">Select Type</option>
                                                <option value="school" {{ old('institute_type') == 'school' ? 'selected' : '' }}>School</option>
                                                <option value="college" {{ old('institute_type') == 'college' ? 'selected' : '' }}>College</option>
                                                <option value="university" {{ old('institute_type') == 'university' ? 'selected' : '' }}>University</option>
                                                <option value="coaching" {{ old('institute_type') == 'coaching' ? 'selected' : '' }}>Coaching Center</option>
                                                <option value="training" {{ old('institute_type') == 'training' ? 'selected' : '' }}>Training Institute</option>
                                            </select>
                                            @error('institute_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Institute Address *</label>
                                            <textarea name="institute_address" rows="3" 
                                                      class="form-control @error('institute_address') is-invalid @enderror" 
                                                      placeholder="Enter complete address">{{ old('institute_address') }}</textarea>
                                            @error('institute_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section" id="student-fields">
                                <h5 class="section-title">Learning Information</h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Current Class/Grade</label>
                                            <select name="grade_level" class="form-select @error('grade_level') is-invalid @enderror">
                                                <option value="">Select Grade</option>
                                                <option value="1-5" {{ old('grade_level') == '1-5' ? 'selected' : '' }}>Class 1-5</option>
                                                <option value="6-8" {{ old('grade_level') == '6-8' ? 'selected' : '' }}>Class 6-8</option>
                                                <option value="9-10" {{ old('grade_level') == '9-10' ? 'selected' : '' }}>Class 9-10</option>
                                                <option value="11-12" {{ old('grade_level') == '11-12' ? 'selected' : '' }}>Class 11-12</option>
                                                <option value="college" {{ old('grade_level') == 'college' ? 'selected' : '' }}>College</option>
                                            </select>
                                            @error('grade_level')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Learning Goals</label>
                                            <select name="learning_goals" class="form-select @error('learning_goals') is-invalid @enderror">
                                                <option value="">Select Goal</option>
                                                <option value="improve_grades" {{ old('learning_goals') == 'improve_grades' ? 'selected' : '' }}>Improve Grades</option>
                                                <option value="exam_preparation" {{ old('learning_goals') == 'exam_preparation' ? 'selected' : '' }}>Exam Preparation</option>
                                                <option value="skill_development" {{ old('learning_goals') == 'skill_development' ? 'selected' : '' }}>Skill Development</option>
                                                <option value="hobby_learning" {{ old('learning_goals') == 'hobby_learning' ? 'selected' : '' }}>Hobby Learning</option>
                                            </select>
                                            @error('learning_goals')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section" id="parent-fields" style="display: none;">
                                <h5 class="section-title">Parent Information</h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Number of Children</label>
                                            <select name="children_count" class="form-select @error('children_count') is-invalid @enderror">
                                                <option value="">Select Count</option>
                                                <option value="1" {{ old('children_count') == '1' ? 'selected' : '' }}>1 Child</option>
                                                <option value="2" {{ old('children_count') == '2' ? 'selected' : '' }}>2 Children</option>
                                                <option value="3" {{ old('children_count') == '3' ? 'selected' : '' }}>3 Children</option>
                                                <option value="4+" {{ old('children_count') == '4+' ? 'selected' : '' }}>4+ Children</option>
                                            </select>
                                            @error('children_count')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Children's Grades</label>
                                            <input type="text" name="child_grades" value="{{ old('child_grades') }}" 
                                                   class="form-control @error('child_grades') is-invalid @enderror" 
                                                   placeholder="e.g., Class 5, Class 8">
                                            @error('child_grades')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Password Section -->
                            <div class="form-section">
                                <h5 class="section-title">Security</h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Password *</label>
                                            <div class="password-input-group">
                                                <input type="password" name="password" 
                                                       class="form-control @error('password') is-invalid @enderror" 
                                                       placeholder="Create a strong password" required>
                                                <button type="button" class="password-toggle">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Confirm Password *</label>
                                            <div class="password-input-group">
                                                <input type="password" name="password_confirmation" 
                                                       class="form-control" 
                                                       placeholder="Confirm your password" required>
                                                <button type="button" class="password-toggle">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Terms and Conditions -->
                            <div class="form-section">
                                <div class="form-check">
                                    <input type="checkbox" name="terms" id="terms" 
                                           class="form-check-input @error('terms') is-invalid @enderror" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="{{ route('terms') }}" target="_blank">Terms of Service</a> 
                                        and <a href="{{ route('privacy') }}" target="_blank">Privacy Policy</a> *
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-check">
                                    <input type="checkbox" name="newsletter" id="newsletter" 
                                           class="form-check-input" {{ old('newsletter') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="newsletter">
                                        I want to receive updates and offers via email
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
                            </div>
                        </form>
                        
                        <div class="login-link">
                            <p class="text-center mb-0">
                                Already have an account? 
                                <a href="{{ route('login') }}" class="text-primary">Sign In</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Register Container */
.register-container {
    position: relative;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    overflow: hidden;
}

.register-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover;
}

.register-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
}

.register-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
}

/* Register Content */
.register-content {
    position: relative;
    z-index: 2;
    color: white;
    padding: 2rem 0;
}

.logo-section {
    margin-bottom: 3rem;
}

.logo-text {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.logo-subtitle {
    font-size: 1.125rem;
    opacity: 0.9;
    margin: 0;
}

.welcome-section {
    margin-bottom: 3rem;
}

.welcome-title {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.welcome-description {
    font-size: 1.125rem;
    line-height: 1.6;
    opacity: 0.9;
    margin: 0;
}

.features-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.feature-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #ffd700;
}

.feature-content h5 {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.feature-content p {
    font-size: 0.875rem;
    opacity: 0.8;
    margin: 0;
}

/* Register Form */
.register-form-container {
    position: relative;
    z-index: 2;
    padding: 2rem 0;
}

.register-form-card {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
}

.form-header {
    text-align: center;
    margin-bottom: 2rem;
}

.form-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.form-subtitle {
    color: #6c757d;
    margin: 0;
}

/* Role Selection */
.role-selection {
    margin-bottom: 2rem;
}

.role-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.role-option {
    position: relative;
}

.role-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.role-label {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.role-input:checked + .role-label {
    border-color: #007bff;
    background: #f8f9ff;
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.1);
}

.role-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(45deg, #007bff, #0056b3);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.role-content h6 {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #2c3e50;
}

.role-content p {
    font-size: 0.875rem;
    color: #6c757d;
    margin: 0;
}

/* Form Sections */
.form-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 15px;
}

.section-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
    font-size: 1.125rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.password-input-group {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
}

/* Form Check */
.form-check {
    margin-bottom: 1rem;
}

.form-check-input {
    width: 1.25rem;
    height: 1.25rem;
    margin-right: 0.75rem;
}

.form-check-label {
    font-size: 0.875rem;
    color: #495057;
}

.form-check-label a {
    color: #007bff;
    text-decoration: none;
}

.form-check-label a:hover {
    text-decoration: underline;
}

/* Form Actions */
.form-actions {
    margin-top: 2rem;
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    font-weight: 600;
    padding: 1rem 2rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 123, 255, 0.3);
}

/* Login Link */
.login-link {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e9ecef;
}

.login-link a {
    font-weight: 600;
    text-decoration: none;
}

.login-link a:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
    .register-form-card {
        padding: 1.5rem;
    }
    
    .role-options {
        grid-template-columns: 1fr;
    }
    
    .logo-text {
        font-size: 2rem;
    }
    
    .welcome-title {
        font-size: 1.5rem;
    }
    
    .form-title {
        font-size: 1.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Role selection functionality
    const roleInputs = document.querySelectorAll('.role-input');
    const teacherFields = document.getElementById('teacher-fields');
    const instituteFields = document.getElementById('institute-fields');
    const studentFields = document.getElementById('student-fields');
    const parentFields = document.getElementById('parent-fields');
    
    function toggleFields() {
        const selectedRole = document.querySelector('.role-input:checked').value;
        
        // Hide all role-specific fields
        teacherFields.style.display = 'none';
        instituteFields.style.display = 'none';
        studentFields.style.display = 'none';
        parentFields.style.display = 'none';
        
        // Show relevant fields based on selected role
        switch(selectedRole) {
            case 'teacher':
                teacherFields.style.display = 'block';
                break;
            case 'institute':
                instituteFields.style.display = 'block';
                break;
            case 'student':
                studentFields.style.display = 'block';
                break;
            case 'parent':
                parentFields.style.display = 'block';
                break;
        }
    }
    
    roleInputs.forEach(input => {
        input.addEventListener('change', toggleFields);
    });
    
    // Password toggle functionality
    const passwordToggles = document.querySelectorAll('.password-toggle');
    
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Form validation
    const form = document.querySelector('.register-form');
    
    form.addEventListener('submit', function(e) {
        const selectedRole = document.querySelector('.role-input:checked');
        
        if (!selectedRole) {
            e.preventDefault();
            alert('Please select a role');
            return;
        }
        
        // Role-specific validation
        const role = selectedRole.value;
        
        if (role === 'teacher') {
            const qualification = document.querySelector('input[name="qualification"]').value;
            const experience = document.querySelector('select[name="experience"]').value;
            const specialization = document.querySelector('input[name="specialization"]').value;
            
            if (!qualification || !experience || !specialization) {
                e.preventDefault();
                alert('Please fill in all required teacher fields');
                return;
            }
        }
        
        if (role === 'institute') {
            const instituteName = document.querySelector('input[name="institute_name"]').value;
            const instituteType = document.querySelector('select[name="institute_type"]').value;
            const instituteAddress = document.querySelector('textarea[name="institute_address"]').value;
            
            if (!instituteName || !instituteType || !instituteAddress) {
                e.preventDefault();
                alert('Please fill in all required institute fields');
                return;
            }
        }
    });
    
    // Initialize fields on page load
    toggleFields();
});
</script>
@endpush
