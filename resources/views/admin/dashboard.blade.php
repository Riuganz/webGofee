@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-speedometer2"></i> Dashboard Admin</h3>
    <small class="text-muted">{{ now()->format('d F Y H:i') }}</small>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Meja</h6>
                        <h2 class="mb-0">{{ $totalMeja }}</h2>
                    </div>
                    <i class="bi bi-grid-3x3-gap" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Menu</h6>
                        <h2 class="mb-0">{{ $totalMenu }}</h2>
                    </div>
                    <i class="bi bi-menu-app" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Menunggu Konfirmasi</h6>
                        <h2 class="mb-0">{{ $pendingReservasi }}</h2>
                    </div>
                    <i class="bi bi-clock" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Reservasi Hari Ini</h6>
                        <h2 class="mb-0">{{ $todayReservasi }}</h2>
                    </div>
                    <i class="bi bi-calendar-check" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-list"></i> Reservasi Terbaru</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pelanggan</th>
                        <th>Tipe</th>
                        <th>Meja</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasiTerbaru as $r)
                        <tr>
                            <td>{{ $r->id_reservasi }}</td>
                            <td>{{ $r->user->name ?? '-' }}</td>
                            <td>{{ $r->tipe }}</td>
                            <td>{{ $r->meja->nomor_meja ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }} {{ $r->jam }}</td>
                            <td>Rp {{ number_format($r->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $r->status_reservasi == 'Menunggu Konfirmasi' ? 'warning' : ($r->status_reservasi == 'Diterima' ? 'info' : ($r->status_reservasi == 'Selesai' ? 'success' : 'danger')) }}">
                                    {{ $r->status_reservasi }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.reservasi.show', $r->id_reservasi) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">Belum ada reservasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
