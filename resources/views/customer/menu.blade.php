@extends('layouts.app')

@section('title', 'Menu Digital')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="section-label">OUR MENU</div>
        <h2 class="section-title" style="font-size: 2rem;">Menu Digital DRIP GO.FEE</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="btn-group flex-wrap" role="group" style="gap: 6px;">
            <a href="{{ route('customer.menu') }}" class="btn {{ !request('kategori') ? 'btn-primary' : 'btn-outline-primary' }}" style="border-radius: 10px !important; {{ !request('kategori') ? 'background: var(--primary-blue); border: none;' : 'border-color: var(--primary-blue); color: var(--primary-blue);' }}">Semua</a>
            @foreach($kategoris as $kat)
                <a href="{{ route('customer.menu', ['kategori' => $kat->id_kategori]) }}" class="btn {{ request('kategori') == $kat->id_kategori ? 'btn-primary' : 'btn-outline-primary' }}" style="border-radius: 10px !important; {{ request('kategori') == $kat->id_kategori ? 'background: var(--primary-blue); border: none;' : 'border-color: var(--primary-blue); color: var(--primary-blue);' }}">{{ $kat->nama_kategori }}</a>
            @endforeach
        </div>
    </div>
</div>

<div class="row">
    @forelse($menus as $menu)
        @if(!request('kategori') || request('kategori') == $menu->id_kategori)
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="product-card card-3d">
                @if($menu->foto)
                    <img src="{{ asset('storage/' . $menu->foto) }}" class="product-img" alt="{{ $menu->nama_menu }}">
                @else
                    <div class="product-img-placeholder">
                        <i class="bi bi-cup-straw"></i>
                    </div>
                @endif
                <div class="product-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5 class="product-name mb-0">{{ $menu->nama_menu }}</h5>
                        <span class="badge" style="background: {{ $menu->stok_status == 'Tersedia' ? '#10b981' : '#ef4444' }}; border-radius: 8px; padding: 0.3rem 0.6rem; font-size: 0.7rem;">
                            {{ $menu->stok_status }}
                        </span>
                    </div>
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
        @endif
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-emoji-frown" style="font-size: 3rem; color: #9ca3af;"></i>
            <p class="mt-2" style="color: #6b7280;">Belum ada menu tersedia.</p>
        </div>
    @endforelse
</div>
@endsection