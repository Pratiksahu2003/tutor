<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'EduPlatform') }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Admin Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-mortarboard-fill me-2"></i>EduAdmin</h4>
        </div>
        
        <ul class="navbar-nav sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                   href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#cmsSubmenu">
                    <i class="bi bi-file-earmark-text"></i>
                    Content Management
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse nav-submenu" id="cmsSubmenu">
                    <a class="nav-link" href="{{ route('admin.cms.pages.index') }}">
                        <i class="bi bi-file-text"></i>Pages
                    </a>
                    <a class="nav-link" href="{{ route('admin.cms.blog.index') }}">
                        <i class="bi bi-journal-text"></i>Blog Posts
                    </a>
                    <a class="nav-link" href="{{ route('admin.cms.media.index') }}">
                        <i class="bi bi-images"></i>Media Library
                    </a>
                    <a class="nav-link" href="{{ route('admin.cms.menus.index') }}">
                        <i class="bi bi-list"></i>Menus
                    </a>
                    <a class="nav-link" href="{{ route('admin.cms.sliders.index') }}">
                        <i class="bi bi-collection-play"></i>Sliders
                    </a>
                </div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#usersSubmenu">
                    <i class="bi bi-people"></i>
                    User Management
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse nav-submenu" id="usersSubmenu">
                    <a class="nav-link" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-person"></i>All Users
                    </a>
                    <a class="nav-link" href="{{ route('admin.roles.index') }}">
                        <i class="bi bi-shield-check"></i>Roles
                    </a>
                    <a class="nav-link" href="{{ route('admin.permissions.index') }}">
                        <i class="bi bi-key"></i>Permissions
                    </a>
                </div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}" 
                   href="{{ route('admin.leads.index') }}">
                    <i class="bi bi-person-plus"></i>
                    Lead Management
                    @if($pendingLeads ?? 0 > 0)
                        <span class="badge bg-danger ms-auto">{{ $pendingLeads ?? 0 }}</span>
                    @endif
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}" 
                   href="{{ route('admin.analytics.index') }}">
                    <i class="bi bi-graph-up"></i>
                    Analytics & Reports
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" 
                   href="{{ route('admin.settings.index') }}">
                    <i class="bi bi-gear"></i>
                    Settings
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#systemSubmenu">
                    <i class="bi bi-server"></i>
                    System
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse nav-submenu" id="systemSubmenu">
                    <a class="nav-link" href="{{ route('admin.system.backup') }}">
                        <i class="bi bi-archive"></i>Backup
                    </a>
                    <a class="nav-link" href="{{ route('admin.system.logs') }}">
                        <i class="bi bi-file-text"></i>Logs
                    </a>
                    <a class="nav-link" href="{{ route('admin.system.cache') }}">
                        <i class="bi bi-arrow-clockwise"></i>Cache
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <button class="btn btn-link d-md-none" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            
            <div class="topbar-search">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" placeholder="Search anything...">
            </div>
            
            <div class="topbar-nav">
                <button class="btn btn-outline-secondary" id="themeToggle" title="Toggle Theme">
                    <i class="bi bi-sun"></i>
                </button>
                
                <button class="btn btn-outline-secondary" id="notificationBtn" title="Notifications">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                            data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2"></i>Profile
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                            <i class="bi bi-gear me-2"></i>Settings
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Admin Scripts -->
    <script src="{{ asset('js/admin.js') }}"></script>
    
    @stack('scripts')
</body>
</html> 