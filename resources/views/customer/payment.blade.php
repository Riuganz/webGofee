@extends('layouts.app')

@section('title', 'Pembayaran')
@section('content')
@php
    $snapToken = $snapToken ?? null;
@endphp
<meta name="snap-token" content="{{ $snapToken }}">
<meta name="reservasi-id" content="{{ $reservasi->id_reservasi }}">
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-credit-card"></i> Pembayaran Pesanan #{{ $reservasi->id_reservasi }}</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Ringkasan Pesanan -->
                <div class="mb-4">
                    <h6 class="fw-bold">Ringkasan Pesanan</h6>
                    <table class="table table-sm">
                        <tr>
                            <td>Tipe</td>
                            <td>: {{ $reservasi->tipe == 'dine-in' ? 'Dine-In' : 'Pick-Up' }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>: {{ \Carbon\Carbon::parse($reservasi->tanggal)->format('d/m/Y') }} {{ $reservasi->jam }}</td>
                        </tr>
                        @if($reservasi->meja)
                        <tr>
                            <td>Meja</td>
                            <td>: {{ $reservasi->meja->nomor_meja }} ({{ $reservasi->jumlah_orang }} orang)</td>
                        </tr>
                        @endif
                        <tr>
                            <td>Total Harga</td>
                            <td>: <strong>Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:
                                <span class="badge bg-warning text-dark">{{ $reservasi->status_reservasi }}</span>
                                <span class="badge bg-{{ $reservasi->status_bayar == 'lunas' ? 'success' : 'danger' }}">
                                    {{ $reservasi->status_bayar == 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Detail Menu -->
                <div class="mb-4">
                    <h6 class="fw-bold">Detail Menu</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservasi->detailPesanans as $detail)
                            <tr>
                                <td>{{ $detail->menu->nama_menu ?? 'Menu dihapus' }}</td>
                                <td class="text-center">{{ $detail->jumlah_beli }}</td>
                                <td class="text-end">Rp {{ number_format($detail->menu->harga ?? 0, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="3" class="text-end">Total</td>
                                <td class="text-end">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Metode Pembayaran -->
                <div class="mb-4">
                    <h6 class="fw-bold">Pilih Metode Pembayaran</h6>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Klik tombol di bawah untuk membuka halaman pembayaran Midtrans.
                        Anda dapat membayar menggunakan:
                        <ul class="mb-0 mt-1">
                            <li><strong>Virtual Account</strong> (BCA, BNI, Mandiri, Permata)</li>
                            <li><strong>E-Wallet</strong> (GoPay, OVO, Dana, ShopeePay)</li>
                            <li><strong>QRIS</strong> (Scan QR via aplikasi pembayaran)</li>
                            <li><strong>Kartu Kredit/Debit</strong></li>
                        </ul>
                    </div>
                </div>

                <!-- Tombol Bayar -->
                <div class="text-center">
                    <div id="payment-loading" class="d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Menyiapkan pembayaran...</p>
                    </div>
                    <button id="pay-button" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-credit-card"></i> Bayar Sekarang
                    </button>
                    <a href="{{ route('customer.riwayat') }}" class="btn btn-outline-secondary btn-lg ms-2">
                        Kembali ke Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}" nonce="{{ $cspNonce }}"></script>
<script src="{{ asset('js/payment.js') }}" nonce="{{ $cspNonce }}"></script>
@endpush
