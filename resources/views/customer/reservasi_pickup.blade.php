@extends('layouts.app')

@section('title', 'Pick-Up Order')
@section('content')
<div class="card shadow-card card-3d">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-bag-plus"></i> Form Pesanan Pick-Up</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('customer.reservasi.store') }}" id="formPickup">
            @csrf
            <input type="hidden" name="tipe" value="pick-up">

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal Pengambilan</label>
                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="jam" class="form-label">Jam Pengambilan</label>
                        <input type="time" class="form-control @error('jam') is-invalid @enderror" id="jam" name="jam" value="{{ old('jam', date('H:i')) }}" required>
                    </div>
                </div>
            </div>

            <input type="hidden" name="jumlah_orang" value="1">

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
                <button type="submit" class="btn btn-success btn-lg">Buat Pesanan Pick-Up</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
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
        if (!qtyDisplay.classList.contains('d-none') && qty === 0) { qty = 1; updateQty(); }
    });

    qtyDisplay.querySelector('.btn-plus').addEventListener('click', function(e) { e.stopPropagation(); qty++; updateQty(); });
    qtyDisplay.querySelector('.btn-minus').addEventListener('click', function(e) {
        e.stopPropagation(); qty = Math.max(0, qty - 1); updateQty();
        if (qty === 0) qtyDisplay.classList.add('d-none');
    });

    function updateQty() { qtyDisplay.querySelector('.qty-text').textContent = qty; hitungTotal(); }
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

document.getElementById('formPickup').addEventListener('submit', function(e) {
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
