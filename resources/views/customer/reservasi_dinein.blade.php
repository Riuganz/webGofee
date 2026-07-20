@extends('layouts.app')

@section('title', 'Reservasi Dine-In')
@section('content')
<div class="card shadow-card card-3d">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Form Reservasi Dine-In</h5>
    </div>
    <div class="card-body">
        {{-- BAGIAN 1: FORM FILTER (GET) --}}
        <form method="GET" action="{{ route('customer.reservasi.dinein') }}" class="mb-4 p-3 bg-light rounded border box-3d-bevel">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <div class="mb-3 mb-md-0">
                        <label for="filter_tanggal" class="form-label fw-semibold">Tanggal Kunjungan</label>
                        <input type="date" class="form-control" id="filter_tanggal" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" onchange="this.form.submit()" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3 mb-md-0">
                        <label for="filter_jam" class="form-label fw-semibold">Jam Kedatangan</label>
                        <input type="time" class="form-control" id="filter_jam" name="jam" value="{{ request('jam', date('H:i')) }}" onchange="this.form.submit()" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3 mb-md-0">
                        <label for="filter_jumlah_orang" class="form-label fw-semibold">Jumlah Orang</label>
                        <input type="number" class="form-control" id="filter_jumlah_orang" name="jumlah_orang" value="{{ request('jumlah_orang', 1) }}" min="1" onchange="this.form.submit()" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Cari Meja
                    </button>
                </div>
            </div>
        </form>

        {{-- BAGIAN 2: GRID MEJA (hanya muncul setelah filter diklik) --}}
        @if(request()->has('tanggal'))
        <form method="POST" action="{{ route('customer.reservasi.store') }}" id="formReservasi">
            @csrf
            <input type="hidden" name="tipe" value="dine-in">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            <input type="hidden" name="jam" value="{{ $jam }}">
            <input type="hidden" name="jumlah_orang" value="{{ $jumlahOrang }}">

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label fw-bold mb-0">Pilih Meja (Tersedia)</label>
                    <small class="text-muted">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM YYYY') }} | Jam: {{ $jam }} | {{ $jumlahOrang }} orang</small>
                </div>
                <div class="table-grid" id="mejaGrid">
                    @forelse($mejas as $meja)
                        @php
                            $isBooked = in_array($meja->id_meja, $bookedMejaIds);
                            $statusClass = $isBooked ? 'terisi' : 'tersedia';
                            $statusBadge = $isBooked ? 'Terisi' : 'Tersedia';
                            $badgeClass = $isBooked ? 'bg-danger' : 'bg-success';
                        @endphp
                        <div class="meja-card {{ $statusClass }} @if(!$isBooked) cursor-pointer @endif"
                             data-id="{{ $meja->id_meja }}"
                             data-nomor="{{ $meja->nomor_meja }}"
                             data-kapasitas="{{ $meja->kapasitas }}"
                             onclick="{{ $isBooked ? '' : "pilihMeja(this)" }}"
                             @if($isBooked) style="opacity: 0.6; cursor: not-allowed;" @endif>
                            <strong>{{ $meja->nomor_meja }}</strong>
                            <small class="d-block">{{ $meja->kapasitas }} kursi</small>
                            <span class="badge {{ $badgeClass }}">{{ $statusBadge }}</span>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4">
                            <p class="text-muted mb-0">Tidak ada meja yang tersedia untuk kriteria ini.</p>
                        </div>
                    @endforelse
                </div>
                <input type="hidden" name="id_meja" id="id_meja" value="{{ old('id_meja') }}">
                @error('id_meja')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Pilih Menu</label>
                <div class="row" id="menuList">
                    @foreach($menus as $menu)
                        <div class="col-md-4 col-lg-3 mb-3">
                            <div class="card card-menu shadow-card card-3d" data-menu-id="{{ $menu->id_menu }}" data-harga="{{ $menu->harga }}">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="mb-0">{{ $menu->nama_menu }}</h6>
                                        <span class="badge bg-{{ $menu->stok_status == 'Tersedia' ? 'success' : 'danger' }}">{{ $menu->stok_status }}</span>
                                    </div>
                                    <p class="text-primary mb-1">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Metode Pembayaran</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metode_bayar" id="metodeTransfer" value="transfer" checked>
                        <label class="form-check-label" for="metodeTransfer">
                            <i class="bi bi-credit-card"></i> Bayar Langsung (Online)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metode_bayar" id="metodeDitempat" value="bayar_ditempat">
                        <label class="form-check-label" for="metodeDitempat">
                            <i class="bi bi-cash"></i> Bayar di Tempat
                        </label>
                    </div>
                </div>
                <small class="text-muted">Bayar langsung menggunakan Virtual Account, GoPay, OVO, QRIS, dll.</small>
            </div>

            <div class="mb-3">
                <label for="catatan" class="form-label">Catatan (opsional)</label>
                <textarea class="form-control" id="catatan" name="catatan" rows="2">{{ old('catatan') }}</textarea>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>Total: Rp <span id="totalHarga">0</span></strong>
                </div>
                <button type="submit" class="btn btn-dark btn-lg">Buat Pesanan</button>
            </div>
        </form>
        @else
        {{-- Tampilkan pesan jika belum filter --}}
        <div class="text-center py-5">
            <i class="bi bi-calendar-range" style="font-size: 3rem; color: #dee2e6;"></i>
            <h5 class="mt-3 text-muted">Silakan pilih tanggal, jam, dan jumlah orang</h5>
            <p class="text-muted">Klik tombol <strong>"Cari Meja"</strong> untuk melihat ketersediaan meja.</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
let selectedMeja = null;

function pilihMeja(el) {
    document.querySelectorAll('.meja-card').forEach(c => c.classList.remove('border-primary', 'border-3'));
    el.classList.add('border-primary', 'border-3');
    document.getElementById('id_meja').value = el.dataset.id;
    selectedMeja = el.dataset.id;
}

document.querySelectorAll('.card-menu').forEach(card => {
    const menuId = card.dataset.menuId;
    const harga = parseInt(card.dataset.harga);
    let qty = 0;
    const qtyDisplay = document.createElement('div');
    qtyDisplay.className = 'd-flex align-items-center gap-2 mt-2 qty-control d-none';
    qtyDisplay.innerHTML = `<button type="button" class="btn btn-sm btn-outline-secondary btn-minus" data-id="${menuId}">-</button>
        <span class="qty-text" data-id="${menuId}">0</span>
        <button type="button" class="btn btn-sm btn-outline-secondary btn-plus" data-id="${menuId}">+</button>`;
    card.querySelector('.card-body').appendChild(qtyDisplay);

    card.addEventListener('click', function(e) {
        if (e.target.closest('.btn-minus') || e.target.closest('.btn-plus') || e.target.closest('.qty-text')) return;
        qtyDisplay.classList.toggle('d-none');
        if (!qtyDisplay.classList.contains('d-none') && qty === 0) {
            qty = 1;
            updateQty();
        }
    });

    qtyDisplay.querySelector('.btn-plus').addEventListener('click', function(e) {
        e.stopPropagation();
        qty++;
        updateQty();
    });

    qtyDisplay.querySelector('.btn-minus').addEventListener('click', function(e) {
        e.stopPropagation();
        qty = Math.max(0, qty - 1);
        updateQty();
        if (qty === 0) qtyDisplay.classList.add('d-none');
    });

    function updateQty() {
        qtyDisplay.querySelector('.qty-text').textContent = qty;
        hitungTotal();
    }
});

function hitungTotal() {
    let total = 0;
    document.querySelectorAll('.qty-text').forEach(el => {
        const id = el.dataset.id;
        const qty = parseInt(el.textContent) || 0;
        const card = document.querySelector(`.card-menu[data-menu-id="${id}"]`);
        if (card) total += qty * parseInt(card.dataset.harga);
    });
    document.getElementById('totalHarga').textContent = total.toLocaleString('id-ID');
}

document.getElementById('formReservasi')?.addEventListener('submit', function(e) {
    const idMeja = document.getElementById('id_meja').value;
    if (!idMeja) { e.preventDefault(); alert('Silakan pilih meja terlebih dahulu.'); return; }
    let hasItem = false;
    document.querySelectorAll('.qty-text').forEach(el => {
        const qty = parseInt(el.textContent) || 0;
        if (qty > 0) hasItem = true;
    });
    if (!hasItem) { e.preventDefault(); alert('Silakan pilih minimal 1 menu.'); return; }

    document.querySelectorAll('.qty-text').forEach(el => {
        const qty = parseInt(el.textContent) || 0;
        if (qty > 0) {
            const id = el.dataset.id;
            const h1 = document.createElement('input');
            h1.type = 'hidden'; h1.name = `items[${id}][id_menu]`; h1.value = id;
            this.appendChild(h1);
            const h2 = document.createElement('input');
            h2.type = 'hidden'; h2.name = `items[${id}][jumlah]`; h2.value = qty;
            this.appendChild(h2);
        }
    });
});
</script>
@endpush
@endsection