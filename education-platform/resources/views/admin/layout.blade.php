<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Custom Admin CSS -->
    <style>
        :root {
            --admin-primary: #2563eb;
            --admin-secondary: #64748b;
            --admin-success: #16a34a;
            --admin-danger: #dc2626;
            --admin-warning: #d97706;
            --admin-info: #0891b2;
            --admin-light: #f8fafc;
            --admin-dark: #1e293b;
        }

        body {
            background-color: var(--admin-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-sidebar {
            background: linear-gradient(135deg, var(--admin-dark) 0%, #334155 100%);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .admin-sidebar.collapsed {
            width: 70px;
        }

        .admin-sidebar .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid #475569;
        }

        .admin-sidebar .sidebar-header h4 {
            color: white;
            margin: 0;
            font-weight: 600;
        }

        .admin-sidebar .sidebar-menu {
            padding: 1rem 0;
            max-height: calc(100vh - 100px);
            overflow-y: auto;
        }

        .admin-sidebar .menu-section {
            margin: 1rem 0;
            padding: 0 1rem;
        }

        .admin-sidebar .menu-section-title {
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0 1rem;
            margin-bottom: 0.5rem;
            border-bottom: 1px solid #475569;
            padding-bottom: 0.5rem;
        }

        .admin-sidebar .menu-item {
            margin: 0.25rem 1rem;
        }

        .admin-sidebar .menu-item a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .admin-sidebar .menu-item a:hover,
        .admin-sidebar .menu-item a.active {
            background-color: var(--admin-primary);
            color: white;
            transform: translateX(4px);
        }

        .admin-sidebar .menu-item i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .admin-sidebar .badge {
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
            margin-left: auto;
        }

        .admin-content {
            margin-left: 280px;
            transition: all 0.3s ease;
        }

        .admin-content.expanded {
            margin-left: 70px;
        }

        .admin-header {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-main {
            padding: 2rem;
        }

        .stats-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--admin-primary);
        }

        .stats-card.success {
            border-left-color: var(--admin-success);
        }

        .stats-card.warning {
            border-left-color: var(--admin-warning);
        }

        .stats-card.danger {
            border-left-color: var(--admin-danger);
        }

        .stats-card .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .stats-card.primary .stats-icon {
            background-color: var(--admin-primary);
        }

        .stats-card.success .stats-icon {
            background-color: var(--admin-success);
        }

        .stats-card.warning .stats-icon {
            background-color: var(--admin-warning);
        }

        .stats-card.danger .stats-icon {
            background-color: var(--admin-danger);
        }

        .page-title {
            color: var(--admin-dark);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin-bottom: 1rem;
        }

        .breadcrumb-item a {
            color: var(--admin-secondary);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--admin-dark);
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .table {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table th {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            font-weight: 600;
            color: var(--admin-dark);
        }

        .badge {
            font-weight: 500;
            padding: 0.375rem 0.75rem;
        }

        .alert {
            border: none;
            border-radius: 0.5rem;
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                width: 70px;
            }
            
            .admin-content {
                margin-left: 70px;
            }
            
            .admin-sidebar .menu-item span {
                display: none;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Admin Sidebar -->
    <div class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-cogs me-2"></i> Admin Panel</h4>
        </div>
        
        <div class="sidebar-menu">
            <!-- Main Dashboard -->
            <div class="menu-section">
                <div class="menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- User Management Section -->
            <div class="menu-section">
                <div class="menu-section-title">User Management</div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>All Users</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="{{ route('admin.teachers.index') }}" class="{{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Teachers</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="{{ route('admin.institutes.index') }}" class="{{ request()->routeIs('admin.institutes.*') ? 'active' : '' }}">
                        <i class="fas fa-university"></i>
                        <span>Institutes</span>
                    </a>
                </div>
            </div>

            <!-- Question Management Section -->
            <div class="menu-section">
                <div class="menu-section-title">Question Management</div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.questions.index') }}" class="{{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i>
                        <span>Questions</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="{{ route('admin.questions.statistics') }}" class="{{ request()->routeIs('admin.questions.statistics') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Statistics</span>
                    </a>
                </div>
            </div>

            <!-- Access Control -->
            <div class="menu-section">
                <div class="menu-section-title">Access Control</div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tag"></i>
                        <span>Roles</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="{{ route('admin.permissions.index') }}" class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                        <i class="fas fa-key"></i>
                        <span>Permissions</span>
                    </a>
                </div>
            </div>

            <!-- Account -->
            <div class="menu-section">
                <div class="menu-section-title">Account</div>
                
                <div class="menu-item">
                    <a href="{{ route('dashboard.modern') }}" class="">
                        <i class="fas fa-home"></i>
                        <span>User Dashboard</span>
                    </a>
                </div>

                <div class="menu-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="admin-content" id="adminContent">
        <!-- Header -->
        <div class="admin-header">
            <div class="d-flex align-items-center">
                <button class="btn btn-link p-0 me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('dashboard.modern') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>User Dashboard
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-edit me-2"></i>Profile
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="admin-main">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Admin JS -->
    <script>
        $(document).ready(function() {
            // Sidebar toggle
            $('#sidebarToggle').on('click', function() {
                $('#adminSidebar').toggleClass('collapsed');
                $('#adminContent').toggleClass('expanded');
            });

            // Initialize DataTables
            $('.data-table').DataTable({
                responsive: true,
                pageLength: 15,
                order: [[0, 'desc']]
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html> 