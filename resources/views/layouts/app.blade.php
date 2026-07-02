<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DRIP GO.FEE') - {{ config('app.name', 'DRIP GO.FEE') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-dark: #1e3a5f;
            --accent-gold: #d4a853;
            --cream-bg: #fdf8f0;
            --dark-bg: #0f172a;
            --card-shadow: 0 10px 40px rgba(0,0,0,0.08);
            --hover-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--cream-bg);
            color: #1a1a2e;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        /* ===== NAVBAR ===== */
        .navbar-coffee {
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(20px);
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s ease;
            position: relative;
            z-index: 99999 !important;
        }
        .navbar-coffee .nav-item.dropdown {
            position: relative;
            z-index: 1;
        }
        .navbar-coffee .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 1.5rem;
            color: #fff !important;
            letter-spacing: 0.5px;
        }
        .navbar-coffee .navbar-brand i {
            color: var(--accent-gold);
            margin-right: 8px;
        }
        .navbar-coffee .nav-link {
            color: rgba(255,255,255,0.75) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 2px;
        }
        .navbar-coffee .nav-link:hover {
            color: #fff !important;
            background: rgba(255,255,255,0.08);
        }
        .navbar-coffee .nav-link i {
            margin-right: 5px;
        }
        .navbar-coffee {
            overflow: visible !important;
        }
        .navbar-coffee .navbar-collapse {
            overflow: visible !important;
        }
        .navbar-coffee .dropdown-menu {
            background: rgba(15, 23, 42, 0.98) !important;
            backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255,255,255,0.08) !important;
            border-radius: 12px !important;
            padding: 0.5rem !important;
            margin-top: 8px !important;
        }
        .navbar-coffee .dropdown-menu.show {
            z-index: 999999 !important;
        }
        main {
            position: relative;
            z-index: 1;
        }
        .navbar-coffee .dropdown-menu-end {
            right: 0 !important;
            left: auto !important;
        }
        .navbar-coffee .dropdown-menu .dropdown-item {
            color: rgba(255,255,255,0.75);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .navbar-coffee .dropdown-menu .dropdown-item:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }
        .navbar-coffee .dropdown-menu .dropdown-item i {
            margin-right: 8px;
            width: 16px;
        }
        .btn-signin {
            background: var(--primary-blue);
            color: #fff !important;
            border-radius: 8px !important;
            padding: 0.5rem 1.2rem !important;
            font-weight: 600 !important;
        }
        .btn-signin:hover {
            background: #1d4ed8 !important;
            color: #fff !important;
        }
        .search-icon {
            color: rgba(255,255,255,0.6);
            font-size: 1.1rem;
            cursor: pointer;
            padding: 0.5rem 0.8rem;
            transition: color 0.3s;
        }
        .search-icon:hover { color: #fff; }

        /* ===== HERO SECTION ===== */
        .hero-premium {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #2563eb 100%);
            color: white;
            border-radius: 24px;
            padding: 4rem 3rem;
            position: relative;
            overflow: hidden;
            min-height: 500px;
            margin-bottom: 3rem;
        }
        .hero-premium::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(212,168,83,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-premium::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(37,99,235,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-content { position: relative; z-index: 2; }
        .hero-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--accent-gold);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 4.5rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 0.3rem;
        }
        .hero-subtitle {
            font-size: 1.1rem;
            letter-spacing: 6px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.7);
            font-weight: 300;
            margin-bottom: 1.5rem;
        }
        .hero-desc {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.8);
            line-height: 1.7;
            max-width: 520px;
            margin-bottom: 2rem;
        }
        .btn-explore {
            background: var(--primary-blue);
            color: #fff;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-explore:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37,99,235,0.4);
            color: #fff;
        }
        .btn-outline-hero {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 0.8rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-left: 0.8rem;
        }
        .btn-outline-hero:hover {
            border-color: #fff;
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        .hero-image-wrapper {
            position: relative;
            z-index: 2;
            text-align: center;
        }
        .hero-image-wrapper img {
            max-width: 100%;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .hero-badge {
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
            font-size: 0.8rem;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.9);
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* ===== FEATURE CARDS ===== */
        .feature-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            background: rgba(255,255,255,0.12);
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 0.8rem;
        }
        .feature-card h6 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: #fff;
        }
        .feature-card p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.7);
            margin-bottom: 0;
        }

        /* ===== SECTION TITLES ===== */
        .section-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 0.3rem;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-bg);
        }
        .section-link {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        .section-link:hover {
            color: #1d4ed8;
            transform: translateX(3px);
        }

        /* ===== ABOUT SECTION ===== */
        .about-section {
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        .about-image-wrapper {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            position: relative;
        }
        .about-image-wrapper img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        .about-desc {
            color: #4a5568;
            line-height: 1.8;
            font-size: 1rem;
        }
        .stat-item {
            text-align: center;
            padding: 1.2rem;
            background: #fff;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }
        .stat-item:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }
        .stat-icon { font-size: 1.8rem; }
        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark-bg);
        }
        .stat-label {
            font-size: 0.85rem;
            color: #6b7280;
            font-weight: 500;
        }

        /* ===== PRODUCT CARDS ===== */
        .product-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            height: 100%;
            border: none;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--hover-shadow);
        }
        .product-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .product-img-placeholder {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f3e8ff 0%, #e0e7ff 100%);
            font-size: 3rem;
            color: var(--primary-blue);
        }
        .product-body {
            padding: 1.2rem 1.5rem 1.5rem;
        }
        .product-name {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.3rem;
        }
        .product-desc {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 0.8rem;
        }
        .product-price {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary-blue);
        }

        /* ===== NEWSLETTER ===== */
        .newsletter-section {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            border-radius: 24px;
            padding: 3rem;
            margin: 3rem 0;
            position: relative;
            overflow: hidden;
        }
        .newsletter-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }
        .newsletter-content { position: relative; z-index: 2; }
        .newsletter-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
        }
        .newsletter-desc {
            color: rgba(255,255,255,0.8);
            font-size: 0.95rem;
        }
        .newsletter-input {
            border: none;
            padding: 0.8rem 1.2rem;
            border-radius: 12px 0 0 12px;
            font-size: 0.95rem;
            width: 100%;
            outline: none;
        }
        .btn-subscribe {
            background: var(--accent-gold);
            color: #fff;
            border: none;
            padding: 0.8rem 1.8rem;
            border-radius: 0 12px 12px 0;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s;
            white-space: nowrap;
        }
        .btn-subscribe:hover {
            background: #c49a3f;
            color: #fff;
        }

        /* ===== FOOTER ===== */
        .footer-premium {
            background: var(--dark-bg);
            color: rgba(255,255,255,0.7);
            padding: 2.5rem 0 1.5rem;
            margin-top: 3rem;
        }
        .footer-premium .brand {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 1.3rem;
            color: #fff;
        }
        .footer-premium .brand i { color: var(--accent-gold); }
        .footer-premium .social-link {
            color: rgba(255,255,255,0.5);
            font-size: 1.3rem;
            margin: 0 0.6rem;
            transition: all 0.3s;
            text-decoration: none;
        }
        .footer-premium .social-link:hover { color: var(--accent-gold); }
        .footer-premium .footer-link {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 0.9rem;
            margin: 0 0.8rem;
            transition: color 0.3s;
        }
        .footer-premium .footer-link:hover { color: #fff; }
        .footer-premium .divider {
            border-color: rgba(255,255,255,0.08);
            margin: 1.2rem 0;
        }
        .footer-premium .copyright {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.4);
        }

        /* ===== EXISTING STYLES (PRESERVED) ===== */
        .card-menu:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .card-menu { transition: all 0.3s ease; cursor: pointer; }
        .meja-card { border: 2px solid #dee2e6; border-radius: 10px; padding: 1rem; text-align: center; cursor: pointer; transition: all 0.3s ease; }
        .meja-card:hover { transform: scale(1.05); }
        .meja-card.tersedia { border-color: #28a745; background-color: #d4edda; }
        .meja-card.terisi { border-color: #dc3545; background-color: #f8d7da; }
        .meja-card.dibooking { border-color: #ffc107; background-color: #fff3cd; }
        .status-badge { font-size: 0.8rem; padding: 0.25rem 0.5rem; }
        .table-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 1rem; }
        .card-img-top-menu { height: 180px; object-fit: cover; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hero-premium { padding: 2.5rem 1.5rem; min-height: auto; }
            .hero-title { font-size: 2.8rem; }
            .hero-subtitle { font-size: 0.85rem; letter-spacing: 4px; }
            .hero-desc { font-size: 0.95rem; }
            .section-title { font-size: 1.8rem; }
            .newsletter-section { padding: 2rem 1.5rem; }
            .newsletter-input { border-radius: 12px; margin-bottom: 0.5rem; }
            .btn-subscribe { border-radius: 12px; width: 100%; }
            .about-image-wrapper img { height: 280px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg navbar-coffee">
        <div class="container">
            <a class="navbar-brand" href="{{ route('customer.dashboard') }}">
                <i class="bi bi-cup-hot-fill"></i> DRIP GO.FEE
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border-color: rgba(255,255,255,0.2);">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('customer.dashboard') }}"><i class="bi bi-house-door"></i> Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-menu-app"></i> Menu
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('customer.menu') }}"><i class="bi bi-cup-straw"></i> Drinks</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.menu') }}"><i class="bi bi-cake2"></i> Desserts</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.menu') }}"><i class="bi bi-cup-hot"></i> Coffee</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('customer.reservasi.dinein') }}"><i class="bi bi-calendar-check"></i> Reservasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('customer.pickup') }}"><i class="bi bi-bag-plus"></i> Pick-Up</a></li>
                </ul>
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <span class="search-icon"><i class="bi bi-search"></i></span>
                    </li>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-gear"></i> Admin</a></li>
                        @endif
                        @if(auth()->user()->isOwner())
                            <li class="nav-item"><a class="nav-link" href="{{ route('owner.dashboard') }}"><i class="bi bi-bar-chart"></i> Owner</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="{{ route('customer.riwayat') }}"><i class="bi bi-clock-history"></i> Riwayat</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><span class="dropdown-item-text text-muted">{{ auth()->user()->email }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link btn-signin" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Sign In</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @yield('content')
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="footer-premium">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="brand"><i class="bi bi-cup-hot-fill"></i> DRIP GO.FEE</div>
                    <small>Kp. Bakung RT 001/001 No. 16, Balaraja, Kabupaten Tangerang, Banten</small>
                </div>
                <div class="col-md-4 mb-3 mb-md-0 text-center">
                    <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-pinterest"></i></a>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                    <a href="#" class="footer-link">Contact Us</a>
                </div>
            </div>
            <hr class="divider">
            <div class="text-center copyright">
                &copy; {{ date('Y') }} DRIP GO.FEE. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @stack('scripts')
</body>
</html>