<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Panel Admin DRIP GO.FEE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style nonce="{{ $cspNonce }}">
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; }
        .sidebar { background: #2c3e50; min-height: 100vh; padding-top: 1rem; }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 0.8rem 1rem; border-radius: 5px; margin: 2px 8px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); }
        .sidebar .nav-link i { margin-right: 8px; }
        .sidebar .brand { color: #fff; font-weight: 800; font-size: 1.2rem; padding: 1rem; text-align: center; }
        .content { padding: 2rem; }

        /* ===== 3D BOX EFFECTS FOR ADMIN ===== */
        .stat-card {
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.06);
            box-shadow: 
                0 2px 4px rgba(0,0,0,0.06),
                0 4px 8px rgba(0,0,0,0.08),
                0 8px 16px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 
                0 4px 8px rgba(0,0,0,0.1),
                0 8px 16px rgba(0,0,0,0.12),
                0 16px 32px rgba(0,0,0,0.15),
                0 24px 48px rgba(0,0,0,0.12);
        }

        .card {
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.06);
            box-shadow: 
                0 2px 4px rgba(0,0,0,0.06),
                0 4px 8px rgba(0,0,0,0.08),
                0 8px 16px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 
                0 4px 8px rgba(0,0,0,0.1),
                0 8px 16px rgba(0,0,0,0.12),
                0 16px 32px rgba(0,0,0,0.15);
        }

        .card-header {
            border-radius: 16px 16px 0 0 !important;
        }

        .table th { background: #f8f9fa; }
        @media (max-width: 768px) { .sidebar { min-height: auto; } }
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
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i>Dashboard</a>
                    <a class="nav-link {{ request()->routeIs('admin.meja*') ? 'active' : '' }}" href="{{ route('admin.meja.index') }}"><i class="bi bi-grid-3x3-gap"></i>Data Meja</a>
                    <a class="nav-link {{ request()->routeIs('admin.kategori*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}"><i class="bi bi-tags"></i>Kategori Menu</a>
                    <a class="nav-link {{ request()->routeIs('admin.menu*') ? 'active' : '' }}" href="{{ route('admin.menu.index') }}"><i class="bi bi-menu-app"></i>Data Menu</a>
                    <a class="nav-link {{ request()->routeIs('admin.reservasi*') ? 'active' : '' }}" href="{{ route('admin.reservasi.index') }}"><i class="bi bi-calendar-check"></i>Reservasi</a>
                    <a class="nav-link {{ request()->routeIs('admin.kasir*') ? 'active' : '' }}" href="{{ route('admin.kasir.create') }}"><i class="bi bi-cash-register"></i>Kasir Walk-in</a>
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
                    <a class="navbar-brand" href="{{ route('admin.dashboard') }}"><i class="bi bi-cup-hot-fill"></i> Admin Panel</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="mobileNav">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.meja.index') }}">Data Meja</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.menu.index') }}">Data Menu</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.reservasi.index') }}">Reservasi</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.kasir.create') }}">Kasir Walk-in</a></li>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" nonce="{{ $cspNonce }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" nonce="{{ $cspNonce }}"></script>
    @stack('scripts')
</body>
</html>