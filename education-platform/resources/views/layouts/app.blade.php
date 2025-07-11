<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Education Platform - Connect with Best Teachers & Institutes')</title>
    <meta name="description" content="@yield('meta_description', 'Find qualified teachers and reputable institutes for personalized learning.')">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    
    @stack('styles')
</head>

<body>
    <!-- Header -->
    <header class="header sticky-top">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <!-- Brand -->
                <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                    EduPlatform
                </a>

                <!-- Mobile menu button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('about') }}">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                        </li>
                    </ul>

                    <!-- Right side menu -->
                    <ul class="navbar-nav">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-primary ms-2" href="{{ route('register') }}">Sign Up</a>
                            </li>
                        @endguest
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <img src="{{ auth()->user()->profile_image ?? 'https://via.placeholder.com/24' }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="rounded-circle me-1" 
                                         width="24" height="24">
                                    {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person me-2"></i>Profile
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="mb-3">EduPlatform</h5>
                    <p class="text-muted mb-3">
                        Connect with the best teachers and institutes for personalized learning. 
                        Start your educational journey with us today.
                    </p>
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-muted text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}" class="text-muted text-decoration-none">Contact</a></li>
                        <li class="mb-2"><a href="{{ route('faq') }}" class="text-muted text-decoration-none">FAQ</a></li>
                        <li class="mb-2"><a href="{{ route('how-it-works') }}" class="text-muted text-decoration-none">How It Works</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">For Teachers</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('register') }}?role=teacher" class="text-muted text-decoration-none">Join as Teacher</a></li>
                        <li class="mb-2"><a href="{{ route('teachers.index') }}" class="text-muted text-decoration-none">Browse Teachers</a></li>
                        <li class="mb-2"><a href="{{ route('institutes.index') }}" class="text-muted text-decoration-none">Browse Institutes</a></li>
                        <li class="mb-2"><a href="{{ route('careers') }}" class="text-muted text-decoration-none">Careers</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">For Students</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('register') }}?role=student" class="text-muted text-decoration-none">Join as Student</a></li>
                        <li class="mb-2"><a href="{{ route('search.index') }}" class="text-muted text-decoration-none">Find Teachers</a></li>
                        <li class="mb-2"><a href="{{ route('search.institutes') }}" class="text-muted text-decoration-none">Find Institutes</a></li>
                        <li class="mb-2"><a href="{{ route('faq') }}" class="text-muted text-decoration-none">Help & Support</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">Legal</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('terms') }}" class="text-muted text-decoration-none">Terms & Conditions</a></li>
                        <li class="mb-2"><a href="{{ route('privacy') }}" class="text-muted text-decoration-none">Privacy Policy</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}" class="text-muted text-decoration-none">Support</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} EduPlatform. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Made with ❤️ for education</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html> 