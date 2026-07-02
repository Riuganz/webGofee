@extends('layouts.admin')

@section('title', 'Detail Reservasi')
@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center
        @if($reservasi->status_reservasi == 'Menunggu Konfirmasi') bg-warning text-dark
        @elseif($reservasi->status_reservasi == 'Diterima') bg-info text-white
        @elseif($reservasi->status_reservasi == 'Selesai') bg-success text-white
        @else bg-danger text-white
        @endif">
        <h5 class="mb-0"><i class="bi bi-receipt"></i> Detail Reservasi #{{ $reservasi->id_reservasi }}</h5>
        <span class="badge bg-light text-dark fs-6">{{ $reservasi->status_reservasi }}</span>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Informasi Pelanggan</h6>
                <table class="table table-borderless">
                    <tr><td>Nama</td><td><strong>{{ $reservasi->user->name ?? '-' }}</strong></td></tr>
                    <tr><td>Email</td><td>{{ $reservasi->user->email ?? '-' }}</td></tr>
                    <tr><td>No. WA</td><td>{{ $reservasi->user->no_wa ?? '-' }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Informasi Reservasi</h6>
                <table class="table table-borderless">
                    <tr><td>Tipe</td><td><span class="badge bg-{{ $reservasi->tipe == 'dine-in' ? 'primary' : 'success' }}">{{ $reservasi->tipe }}</span></td></tr>
                    <tr><td>Tanggal</td><td>{{ \Carbon\Carbon::parse($reservasi->tanggal)->format('d/m/Y') }}</td></tr>
                    <tr><td>Jam</td><td>{{ $reservasi->jam }}</td></tr>
                    <tr><td>Meja</td><td>{{ $reservasi->meja->nomor_meja ?? '-' }} ({{ $reservasi->jumlah_orang }} orang)</td></tr>
                    <tr><td>Catatan</td><td>{{ $reservasi->catatan ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        <h6>Pesanan Menu</h6>
        <table class="table table-bordered">
            <thead>
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
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th class="text-end">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        @if($reservasi->status_reservasi == 'Menunggu Konfirmasi')
            <div class="d-flex gap-2 mt-4">
                <form method="POST" action="{{ route('admin.reservasi.terima', $reservasi->id_reservasi) }}">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Terima reservasi ini?')"><i class="bi bi-check-lg"></i> Terima</button>
                </form>
                <form method="POST" action="{{ route('admin.reservasi.tolak', $reservasi->id_reservasi) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak reservasi ini?')"><i class="bi bi-x-lg"></i> Tolak</button>
                </form>
            </div>
        @elseif($reservasi->status_reservasi == 'Diterima')
            <div class="mt-4">
                <form method="POST" action="{{ route('admin.reservasi.selesai', $reservasi->id_reservasi) }}">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Tandai selesai?')"><i class="bi bi-check-all"></i> Tandai Selesai</button>
                </form>
            </div>
        @endif

        <a href="{{ route('admin.reservasi.index') }}" class="btn btn-secondary mt-3"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>
@endsection
