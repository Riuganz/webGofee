@extends('layouts.app')

@section('title', 'Beranda')
@section('content')

<!-- ===== HERO SECTION ===== -->
<div class="hero-premium">
    <div class="row align-items-center">
        <div class="hero-content col-md-7">
            <div class="hero-label">☕ Premium Coffee Shop</div>
            <h1 class="hero-title">Coffee Shop</h1>
            <div class="hero-subtitle">CRAFTED FOR YOUR SENSES</div>
            <p class="hero-desc">
                Experience the rich, authentic taste of premium roasted coffee beans. Every cup is freshly brewed by our expert baristas to give you the perfect start to your day.
            </p>
            <div class="mb-4">
                <a href="{{ route('customer.menu') }}" class="btn-explore">Explore Menu →</a>
                <a href="#about" class="btn-outline-hero">About Us</a>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">🗄️</div>
                        <h6>Premium Beans</h6>
                        <p>We use only the finest 100% Arabica beans.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">🍃</div>
                        <h6>Freshly Brewed</h6>
                        <p>Brewed fresh for every order, every time.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">💙</div>
                        <h6>Made with Love</h6>
                        <p>Passion, care and perfection in every cup.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="hero-image-wrapper">
                <img src="https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=500&auto=format&fit=crop" alt="Iced Coffee Latte" class="img-fluid">
                <div class="hero-badge">GLÈSE ET BRED</div>
            </div>
        </div>
    </div>
</div>

<!-- ===== ABOUT US SECTION ===== -->
<div class="about-section" id="about">
    <div class="row align-items-center g-5">
        <div class="col-md-6">
            <div class="about-image-wrapper">
                <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=600&auto=format&fit=crop" alt="Latte Art Coffee">
            </div>
        </div>
        <div class="col-md-6">
            <div class="section-label">ABOUT US</div>
            <h2 class="section-title">More Than Just Coffee</h2>
            <p class="about-desc mt-3">
                We are passionate about coffee and committed to serving you the best experience. From carefully selected beans to expertly crafted brews, we bring quality and comfort together.
            </p>
            <div class="row g-3 mt-4">
                <div class="col-4">
                    <div class="stat-item">
                        <div class="stat-icon">☕</div>
                        <div class="stat-number">100+</div>
                        <div class="stat-label">Drink Options</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-item">
                        <div class="stat-icon">👥</div>
                        <div class="stat-number">50K+</div>
                        <div class="stat-label">Happy Customers</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-item">
                        <div class="stat-icon">🏆</div>
                        <div class="stat-number">8+</div>
                        <div class="stat-label">Awards Won</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== POPULAR PICKS ===== -->
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="section-label">POPULAR PICKS</div>
            <h2 class="section-title">Customer Favorites</h2>
        </div>
        <a href="{{ route('customer.menu') }}" class="section-link">View All →</a>
    </div>
    <div class="row g-4">
        @php
            $popularItems = [
                ['name' => 'Espresso', 'price' => 25000, 'desc' => 'Bold and intense dark roast espresso shot.', 'img' => 'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=300&h=300&fit=crop'],
                ['name' => 'Cookie', 'price' => 35000, 'desc' => 'Freshly baked chocochip cookies.', 'img' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=300&h=300&fit=crop'],
                ['name' => 'Cappuccino', 'price' => 35000, 'desc' => 'Perfect balance of espresso and milk.', 'img' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=300&h=300&fit=crop'],
                ['name' => 'Chocolate Cake', 'price' => 40000, 'desc' => 'Rich, moist and irresistibly good.', 'img' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=300&h=300&fit=crop'],
            ];
        @endphp
        @foreach($popularItems as $item)
        <div class="col-md-3 col-6">
            <div class="product-card">
                <img src="{{ $item['img'] }}" class="product-img" alt="{{ $item['name'] }}">
                <div class="product-body">
                    <h5 class="product-name">{{ $item['name'] }}</h5>
                    <p class="product-desc">{{ $item['desc'] }}</p>
                    <div class="product-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- ===== NEWSLETTER ===== -->
<div class="newsletter-section">
    <div class="newsletter-content">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h3 class="newsletter-title">Join Our Coffee Community</h3>
                <p class="newsletter-desc mb-0">Get exclusive offers, new updates and coffee tips straight to your inbox.</p>
            </div>
            <div class="col-md-6">
                <form class="d-flex flex-column flex-md-row">
                    <input type="email" class="newsletter-input" placeholder="Enter your email" required>
                    <button type="submit" class="btn-subscribe">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ===== EXISTING CONTENT (Tables & Menu) ===== -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm" style="border: none; border-radius: 16px;">
            <div class="card-header" style="background: var(--dark-bg); color: #fff; border-radius: 16px 16px 0 0;">
                <h5 class="mb-0"><i class="bi bi-grid-3x3-gap"></i> Layout Meja</h5>
            </div>
            <div class="card-body">
                <div class="table-grid">
                    @forelse($mejas as $meja)
                        <div class="meja-card {{ strtolower($meja->status_meja) }}">
                            <strong>{{ $meja->nomor_meja }}</strong>
                            <small class="d-block">{{ $meja->kapasitas }} kursi</small>
                            <span class="badge status-badge bg-{{ $meja->status_meja == 'Tersedia' ? 'success' : ($meja->status_meja == 'Terisi' ? 'danger' : 'warning') }}">
                                {{ $meja->status_meja }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada data meja.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm" style="border: none; border-radius: 16px;">
            <div class="card-header" style="background: var(--dark-bg); color: #fff; border-radius: 16px 16px 0 0;">
                <h5 class="mb-0"><i class="bi bi-menu-app"></i> Menu Tersedia</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($menus->take(6) as $menu)
                        <div class="col-6 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-2 text-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-cup-straw" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <strong>{{ $menu->nama_menu }}</strong><br>
                                    <small class="text-muted">Rp {{ number_format($menu->harga, 0, ',', '.') }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada menu tersedia.</p>
                    @endforelse
                </div>
                @if(count($menus) > 6)
                    <div class="text-center mt-2">
                        <a href="{{ route('customer.menu') }}" class="btn btn-sm" style="background: var(--primary-blue); color: #fff; border-radius: 10px;">Lihat Semua Menu</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card stat-card text-center p-4" style="border: none; border-radius: 16px; box-shadow: var(--card-shadow);">
            <i class="bi bi-calendar-check" style="font-size: 2rem; color: var(--primary-blue);"></i>
            <h5 class="mt-2" style="font-family: 'Playfair Display', serif;">Reservasi Meja</h5>
            <p class="text-muted">Pesan meja untuk dine-in</p>
            <a href="{{ route('customer.reservasi.dinein') }}" class="btn" style="background: var(--primary-blue); color: #fff; border-radius: 10px;">Reservasi</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-center p-4" style="border: none; border-radius: 16px; box-shadow: var(--card-shadow);">
            <i class="bi bi-bag-plus" style="font-size: 2rem; color: #10b981;"></i>
            <h5 class="mt-2" style="font-family: 'Playfair Display', serif;">Pick-Up</h5>
            <p class="text-muted">Pesan dan ambil sendiri</p>
            <a href="{{ route('customer.pickup') }}" class="btn" style="background: #10b981; color: #fff; border-radius: 10px;">Pesan Sekarang</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-center p-4" style="border: none; border-radius: 16px; box-shadow: var(--card-shadow);">
            <i class="bi bi-clock-history" style="font-size: 2rem; color: #f59e0b;"></i>
            <h5 class="mt-2" style="font-family: 'Playfair Display', serif;">Riwayat Pesanan</h5>
            <p class="text-muted">Cek status pesanan Anda</p>
            <a href="{{ route('customer.riwayat') }}" class="btn" style="background: #f59e0b; color: #fff; border-radius: 10px;">Lihat Riwayat</a>
        </div>
    </div>
</div>
@endsection