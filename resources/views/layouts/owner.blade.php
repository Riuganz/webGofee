<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Panel Owner DRIP GO.FEE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; }
        .sidebar { background: #1a252f; min-height: 100vh; padding-top: 1rem; }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 0.8rem 1rem; border-radius: 5px; margin: 2px 8px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); }
        .sidebar .brand { color: #f1c40f; font-weight: 800; font-size: 1.2rem; padding: 1rem; text-align: center; }
        .content { padding: 2rem; }
        .stat-card { border-radius: 10px; border: none; box-shadow: 0 2px 6px rgba(0,0,0,0.05); transition: all 0.3s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        @media (max-width: 768px) { .sidebar { min-height: auto; } }
        @media print {
            .sidebar, .navbar, .btn-group, .card.shadow-sm.mb-4:first-of-type { display: none !important; }
            .content { padding: 0; }
            body { background: #fff; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar d-none d-md-block">
                <div class="brand"><i class="bi bi-cup-hot-fill"></i> DRIP GO.FEE</div>
                <hr class="text-white-50">
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}" href="{{ route('owner.dashboard') }}"><i class="bi bi-speedometer2"></i>Dashboard</a>
                    <a class="nav-link {{ request()->routeIs('owner.laporan') ? 'active' : '' }}" href="{{ route('owner.laporan') }}"><i class="bi bi-file-earmark-bar-graph"></i>Laporan</a>
                    <hr class="text-white-50">
                    <a class="nav-link" href="{{ route('customer.dashboard') }}"><i class="bi bi-arrow-left"></i> ke Website</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start"><i class="bi bi-box-arrow-right"></i> Logout</button>
                    </form>
                </nav>
            </div>

            <!-- Mobile Nav -->
            <nav class="navbar navbar-dark bg-dark d-md-none">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('owner.dashboard') }}"><i class="bi bi-cup-hot-fill"></i> Owner Panel</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="mobileNav">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="{{ route('owner.dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('owner.laporan') }}">Laporan</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('customer.dashboard') }}">Ke Website</a></li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="nav-link btn">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="col-md-10 content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @stack('scripts')
</body>
</html>
