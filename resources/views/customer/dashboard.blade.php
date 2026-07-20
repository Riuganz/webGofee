@extends('layouts.app')

@section('title', 'Beranda')
@section('content')

<!-- ===== HERO SECTION ===== -->
<div class="hero-premium">
    <div class="row align-items-center">
        <div class="hero-content col-md-7">
            <div class="hero-label">☕ Kedai Kopi Premium</div>
            <h1 class="hero-title">Kedai Kopi</h1>
            <div class="hero-subtitle">DIBUAT UNTUK RASA ANDA</div>
            <p class="hero-desc">
                Rasakan cita rasa kopi premium yang kaya dan otentik. Setiap cangkir diseduh segar oleh barista ahli kami untuk memberikan awal yang sempurna untuk hari Anda.
            </p>
            <div class="mb-4">
                <a href="{{ route('customer.menu') }}" class="btn-explore">Jelajahi Menu →</a>
                <a href="#about" class="btn-outline-hero">Tentang Kami</a>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-4">
                        <div class="feature-card box-3d-bevel">
                            <div class="feature-icon">🗄️</div>
                            <h6>Biji Kopi Premium</h6>
                            <p>Kami hanya menggunakan biji Arabica 100% terbaik.</p>
                        </div>
                </div>
                <div class="col-md-4">
                        <div class="feature-card box-3d-bevel">
                            <div class="feature-icon">🍃</div>
                            <h6>Diseduh Segar</h6>
                            <p>Diseduh segar untuk setiap pesanan, setiap saat.</p>
                        </div>
                </div>
                <div class="col-md-4">
                        <div class="feature-card box-3d-bevel">
                            <div class="feature-icon">💙</div>
                            <h6>Dibuat dengan Cinta</h6>
                            <p>Gairah, perhatian dan kesempurnaan dalam setiap cangkir.</p>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="hero-image-wrapper">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQOVmo33QvrfhSBAZv6GGsoAD3RGVTGh3aqYH8jF3WpolSHH9MBmcojy5bn&s=10" alt="Latte Kopi Dingin" class="img-fluid">
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
                <img src="{{ asset('storage/image/latecoffe.jpeg') }}" alt="Seni Latte Kopi">
            </div>
        </div>
        <div class="col-md-6">
            <div class="section-label">TENTANG KAMI</div>
            <h2 class="section-title">Lebih Dari Sekadar Kopi</h2>
            <p class="about-desc mt-3">
                Kami bersemangat tentang kopi dan berkomitmen untuk memberikan pengalaman terbaik. Dari biji yang dipilih dengan hati-hati hingga seduhan yang dibuat ahli, kami gabungkan kualitas dan kenyamanan.
            </p>
            <div class="row g-3 mt-4">
                <div class="col-4">
                        <div class="stat-item box-3d-elevated">
                            <div class="stat-icon">☕</div>
                            <div class="stat-number">100+</div>
                            <div class="stat-label">Pilihan Minuman</div>
                        </div>
                </div>
                <div class="col-4">
                        <div class="stat-item box-3d-elevated">
                            <div class="stat-icon">👥</div>
                            <div class="stat-number">50K+</div>
                            <div class="stat-label">Pelanggan Puas</div>
                        </div>
                </div>
                <div class="col-4">
                        <div class="stat-item box-3d-elevated">
                            <div class="stat-icon">🏆</div>
                            <div class="stat-number">8+</div>
                            <div class="stat-label">Penghargaan</div>
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
            <div class="section-label">PILIHAN POPULER</div>
            <h2 class="section-title">Favorit Pelanggan</h2>
        </div>
                <a href="{{ route('customer.menu') }}" class="section-link">Lihat Semua →</a>
    </div>
    <div class="row g-4">
        @foreach($popularMenus as $menu)
        <div class="col-md-3 col-6">
            <div class="product-card card-3d">
                @if($menu->foto)
                    <img src="{{ asset('storage/' . $menu->foto) }}" class="product-img" alt="{{ $menu->nama_menu }}">
                @else
                    <div class="product-img-placeholder">
                        <i class="bi bi-cup-straw"></i>
                    </div>
                @endif
                <div class="product-body">
                    <h5 class="product-name">{{ $menu->nama_menu }}</h5>
                    @if($menu->deskripsi)
                        <p class="product-desc">{{ $menu->deskripsi }}</p>
                    @endif
                    <div class="product-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                    @if($menu->kategori)
                        <small style="color: #9ca3af;"><i class="bi bi-tag"></i> {{ $menu->kategori->nama_kategori }}</small>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- ===== NEWSLETTER ===== -->
    <div class="newsletter-section box-3d-dark">
    <div class="newsletter-content">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
            <h3 class="newsletter-title">Bergabung dengan Komunitas Kopi Kami</h3>
            <p class="newsletter-desc mb-0">Dapatkan penawaran eksklusif, pembaruan baru dan tips kopi langsung ke inbox Anda.</p>
            </div>
            <div class="col-md-6">
                <form class="d-flex flex-column flex-md-row">
                    <input type="email" class="newsletter-input" placeholder="Masukkan email Anda" required>
                    <button type="submit" class="btn-subscribe">Berlangganan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ===== EXISTING CONTENT (Tables & Menu) ===== -->
<div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-card card-3d" style="border: none; border-radius: 16px;">
                <div class="card-header" style="background: var(--dark-bg); color: #fff; border-radius: 16px 16px 0 0;">
                    <h5 class="mb-0"><i class="bi bi-grid-3x3-gap"></i> Daftar Meja</h5>
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
            <div class="card shadow-card card-3d" style="border: none; border-radius: 16px;">
                <div class="card-header" style="background: var(--dark-bg); color: #fff; border-radius: 16px 16px 0 0;">
                    <h5 class="mb-0"><i class="bi bi-menu-app"></i> Menu yang Tersedia</h5>
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
        <div class="card stat-card text-center p-4 shadow-card card-3d" style="border: none; border-radius: 16px;">
            <i class="bi bi-calendar-check" style="font-size: 2rem; color: var(--primary-blue);"></i>
            <h5 class="mt-2" style="font-family: 'Playfair Display', serif;">Reservasi Meja</h5>
            <p class="text-muted">Pesan meja untuk makan di tempat</p>
            <a href="{{ route('customer.reservasi.dinein') }}" class="btn" style="background: var(--primary-blue); color: #fff; border-radius: 10px;">Reservasi</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-center p-4 shadow-card card-3d" style="border: none; border-radius: 16px;">
            <i class="bi bi-bag-plus" style="font-size: 2rem; color: #10b981;"></i>
            <h5 class="mt-2" style="font-family: 'Playfair Display', serif;">Ambil Sendiri</h5>
            <p class="text-muted">Pesan dan ambil di tempat</p>
            <a href="{{ route('customer.pickup') }}" class="btn" style="background: #10b981; color: #fff; border-radius: 10px;">Pesan Sekarang</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-center p-4 shadow-card card-3d" style="border: none; border-radius: 16px;">
            <i class="bi bi-clock-history" style="font-size: 2rem; color: #f59e0b;"></i>
            <h5 class="mt-2" style="font-family: 'Playfair Display', serif;">Riwayat Pesanan</h5>
            <p class="text-muted">Cek status pesanan Anda</p>
            <a href="{{ route('customer.riwayat') }}" class="btn" style="background: #f59e0b; color: #fff; border-radius: 10px;">Lihat Riwayat</a>
        </div>
    </div>
</div>
@endsection