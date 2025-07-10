@extends('layouts.app')

@section('title', 'Login - Education Platform')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="card-title mb-0">Welcome Back</h3>
                    <p class="mb-0 small">Sign in to your account</p>
                </div>
                <div class="card-body p-4">
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input id="email" class="form-control @error('email') is-invalid @enderror" 
                                       type="email" name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input id="password" class="form-control @error('password') is-invalid @enderror"
                                       type="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="form-check">
                                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                    <label class="form-check-label" for="remember_me">
                                        Remember me
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small" href="{{ route('password.request') }}">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </button>
                        </div>

                        <!-- Divider -->
                        <div class="text-center mb-3">
                            <span class="text-muted">or</span>
                        </div>

                        <!-- Quick Role Access -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-success btn-sm w-100" onclick="fillDemoCredentials('teacher')">
                                    <i class="bi bi-person-badge me-1"></i>Demo Teacher
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-info btn-sm w-100" onclick="fillDemoCredentials('student')">
                                    <i class="bi bi-person me-1"></i>Demo Student
                                </button>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-warning btn-sm w-100" onclick="fillDemoCredentials('institute')">
                                    <i class="bi bi-building me-1"></i>Demo Institute
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="fillDemoCredentials('admin')">
                                    <i class="bi bi-shield me-1"></i>Demo Admin
                                </button>
                            </div>
                        </div>

                        <!-- Registration Link -->
                        <div class="text-center">
                            <p class="mb-0">Don't have an account? 
                                <a href="{{ route('register') }}" class="text-decoration-none">Sign up</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Role Information Cards -->
            <div class="row g-3 mt-4">
                <div class="col-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body text-center p-3">
                            <i class="bi bi-person-circle text-primary fs-2"></i>
                            <h6 class="mt-2 mb-1">Students</h6>
                            <p class="small text-muted mb-0">Find and connect with qualified teachers</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body text-center p-3">
                            <i class="bi bi-person-badge text-success fs-2"></i>
                            <h6 class="mt-2 mb-1">Teachers</h6>
                            <p class="small text-muted mb-0">Share knowledge and teach students</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }

    function fillDemoCredentials(role) {
        const credentials = {
            'admin': {
                email: 'admin@educationplatform.com',
                password: 'password123'
            },
            'teacher': {
                email: 'teacher@educationplatform.com',
                password: 'password123'
            },
            'institute': {
                email: 'institute@educationplatform.com',
                password: 'password123'
            },
            'student': {
                email: 'student@educationplatform.com',
                password: 'password123'
            }
        };

        if (credentials[role]) {
            document.getElementById('email').value = credentials[role].email;
            document.getElementById('password').value = credentials[role].password;
            
            // Show a tooltip or notification
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check me-1"></i>Filled!';
            button.classList.add('btn-success');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
            }, 2000);
        }
    }
</script>
@endpush
