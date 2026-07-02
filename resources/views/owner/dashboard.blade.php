@extends('layouts.owner')

@section('title', 'Dashboard Owner')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-speedometer2"></i> Dashboard Owner</h3>
    <small class="text-muted">{{ now()->format('d F Y H:i') }}</small>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <h6>Reservasi Hari Ini</h6>
                <h2 class="mb-0">{{ $todayReservasi }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <h6>Pendapatan Hari Ini</h6>
                <h2 class="mb-0">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <h6>Pendapatan Minggu Ini</h6>
                <h2 class="mb-0">Rp {{ number_format($weeklyRevenue, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-warning text-dark">
            <div class="card-body">
                <h6>Pendapatan Bulan Ini</h6>
                <h2 class="mb-0">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Grafik Pendapatan 7 Hari Terakhir</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyRevenue as $dr)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($dr->date)->format('d/m/Y') }}</td>
                                    <td>Rp {{ number_format($dr->revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center">Belum ada data pendapatan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReservasis as $r)
                        <tr>
                            <td>{{ $r->id_reservasi }}</td>
                            <td>{{ $r->user->name ?? '-' }}</td>
                            <td><span class="badge bg-{{ $r->tipe == 'dine-in' ? 'primary' : 'success' }}">{{ $r->tipe }}</span></td>
                            <td>{{ $r->meja->nomor_meja ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }} {{ $r->jam }}</td>
                            <td>Rp {{ number_format($r->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $r->status_reservasi == 'Menunggu Konfirmasi' ? 'warning' : ($r->status_reservasi == 'Diterima' ? 'info' : ($r->status_reservasi == 'Selesai' ? 'success' : 'danger')) }}">
                                    {{ $r->status_reservasi }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Belum ada reservasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
