@extends('layouts.app')

@section('title', 'Riwayat Pesanan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-clock-history"></i> Riwayat Pesanan Saya</h3>
</div>

@forelse($reservasis as $reservasi)
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center
            @if($reservasi->status_reservasi == 'Menunggu Konfirmasi') bg-warning text-dark
            @elseif($reservasi->status_reservasi == 'Diterima') bg-info text-white
            @elseif($reservasi->status_reservasi == 'Selesai') bg-success text-white
            @else bg-danger text-white
            @endif">
            <div>
                <strong>#{{ $reservasi->id_reservasi }}</strong> -
                {{ $reservasi->tipe == 'dine-in' ? 'Dine-In' : 'Pick-Up' }}
                | {{ \Carbon\Carbon::parse($reservasi->tanggal)->format('d/m/Y') }} {{ $reservasi->jam }}
            </div>
            <div>
                <span class="badge bg-light text-dark me-1">{{ $reservasi->status_reservasi }}</span>
                @if($reservasi->metode_bayar == 'transfer')
                    <span class="badge bg-{{ $reservasi->status_bayar == 'lunas' ? 'success' : 'secondary' }}">
                        {{ $reservasi->status_bayar == 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                    </span>
                @else
                    <span class="badge bg-info">Bayar di Tempat</span>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @if($reservasi->meja)
                        <p class="mb-1"><strong>Meja:</strong> {{ $reservasi->meja->nomor_meja }} ({{ $reservasi->jumlah_orang }} orang)</p>
                    @endif
                    @if($reservasi->catatan)
                        <p class="mb-1"><strong>Catatan:</strong> {{ $reservasi->catatan }}</p>
                    @endif
                </div>
                <div class="col-md-6 text-md-end">
                    <h5 class="text-primary mb-0">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</h5>
                    @if($reservasi->status_bayar == 'belum_bayar' && $reservasi->metode_bayar == 'transfer')
                        <a href="{{ route('customer.payment.show', $reservasi->id_reservasi) }}" class="btn btn-sm btn-primary mt-2">
                            <i class="bi bi-credit-card"></i> Bayar Sekarang
                        </a>
                    @endif
                    @if($reservasi->status_bayar == 'lunas' && $reservasi->pembayaranTerakhir)
                        <div class="mt-2">
                            <small class="text-muted">
                                @if($reservasi->pembayaranTerakhir->metode_pembayaran)
                                    Dibayar via {{ strtoupper($reservasi->pembayaranTerakhir->metode_pembayaran) }}
                                @endif
                                @if($reservasi->pembayaranTerakhir->waktu_dibayar)
                                    | {{ $reservasi->pembayaranTerakhir->waktu_dibayar->format('d/m/Y H:i') }}
                                @endif
                            </small>
                        </div>
                    @endif
                </div>
            </div>

            @if($reservasi->detailPesanans->count() > 0)
                <hr>
                <table class="table table-sm table-borderless mb-0">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservasi->detailPesanans as $detail)
                            <tr>
                                <td>{{ $detail->menu->nama_menu ?? 'Menu dihapus' }}</td>
                                <td class="text-center">{{ $detail->jumlah_beli }}</td>
                                <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <i class="bi bi-inbox" style="font-size: 4rem; color: #dee2e6;"></i>
        <h5 class="mt-3 text-muted">Belum ada pesanan</h5>
        <a href="{{ route('customer.reservasi.dinein') }}" class="btn btn-dark mt-2">Reservasi Sekarang</a>
    </div>
@endforelse
@endsection
