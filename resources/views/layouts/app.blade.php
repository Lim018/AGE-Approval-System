<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengajuan Kegiatan</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --info-color: #4895ef;
            --warning-color: #f72585;
            --danger-color: #e63946;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            transition: all 0.3s ease;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar .logo {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .logo h4 {
            color: white;
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar a {
            color: rgba(255, 255, 255, 0.85);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px 10px;
            transition: all 0.3s ease;
        }
        
        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 500;
        }
        
        .sidebar a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .notification-badge {
            position: relative;
            top: -8px;
            right: 5px;
            padding: 3px 6px;
            border-radius: 50%;
            background-color: var(--warning-color);
            color: white;
            font-size: 0.7em;
            animation: pulse 1.5s infinite;
        }
        
        .main-content {
            transition: all 0.3s ease;
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
        }
        
        .btn {
            border-radius: 5px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .table {
            border-radius: 5px;
            overflow: hidden;
        }
        
        .table thead th {
            background-color: rgba(67, 97, 238, 0.05);
            border-bottom: none;
            font-weight: 600;
        }
        
        .badge {
            padding: 5px 10px;
            border-radius: 30px;
            font-weight: 500;
        }
        
        .form-control, .form-select {
            border-radius: 5px;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .alert {
            border-radius: 5px;
            border: none;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        
        .content-wrapper {
            animation: fadeIn 0.5s ease forwards;
        }
        
        /* Status badges */
        .badge-pending {
            background-color: #ffd166;
            color: #333;
        }
        
        .badge-approved {
            background-color: #06d6a0;
            color: white;
        }
        
        .badge-rejected {
            background-color: #ef476f;
            color: white;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -250px;
                width: 250px;
                z-index: 1030;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            .toggle-sidebar {
                display: block !important;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <h4>Pengajuan AGE</h4>
            </div>
            <div class="d-flex flex-column py-3">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('pengajuan.index') }}" class="{{ request()->routeIs('pengajuan.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Pengajuan
                </a>
                <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i> Notifikasi
                    @if(Auth::user()->notifications()->where('dibaca', false)->count() > 0)
                        <span class="notification-badge">
                            {{ Auth::user()->notifications()->where('dibaca', false)->count() }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light sticky-top">
                <div class="container-fluid">
                    <button class="btn btn-sm btn-link text-dark toggle-sidebar d-md-none" type="button" id="toggleSidebar">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                    
                    <div class="d-flex align-items-center">
                        <span class="fs-5 fw-semibold text-primary">
                            @yield('title', 'Dashboard')
                        </span>
                    </div>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                    <span class="text-white fw-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                                <li><span class="dropdown-item-text text-muted small">{{ Auth::user()->email }}</span></li>
                                <li><span class="dropdown-item-text text-muted small">Role: {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Content -->
            <div class="container-fluid p-4 content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-down">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-down">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- AOS Animation Library -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <script>
        // Initialize AOS with faster animations
        AOS.init({
            duration: 400, // Reduced from 800 to 400ms
            easing: 'ease-out',
            once: true,
            offset: 50, // Smaller offset to trigger animations sooner
            delay: 0 // Remove default delay
        });
        
        // Rest of your JavaScript remains the same
        // Mobile sidebar toggle
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            
            if (window.innerWidth <= 768 && 
                sidebar.classList.contains('show') && 
                !sidebar.contains(event.target) && 
                event.target !== toggleBtn) {
                sidebar.classList.remove('show');
            }
        });
        
        // Add animation to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.05}s`; // Reduced delay between cards
                card.classList.add('fade-in');
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>