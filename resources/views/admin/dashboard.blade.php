@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-speedometer2"></i> Dashboard Admin</h3>
    <small class="text-muted">{{ now()->format('d F Y H:i') }}</small>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-primary text-white card-3d">
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
        <div class="card stat-card bg-success text-white card-3d">
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
        <div class="card stat-card bg-warning text-dark card-3d">
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
        <div class="card stat-card bg-info text-white card-3d">
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

{{-- Baris ke-2: Pendapatan + Grafik --}}
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card stat-card bg-danger text-white card-3d">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Pendapatan Hari Ini</h6>
                        <h2 class="mb-0">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h2>
                    </div>
                    <i class="bi bi-cash-stack" style="font-size: 2rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 mb-3">
        <div class="card shadow-card card-3d h-100">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart-line"></i> Reservasi 7 Hari Terakhir</h5>
            </div>
            <div class="card-body">
                <canvas id="chartReservasi" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-card card-3d">
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" nonce="{{ $cspNonce ?? '' }}"></script>
<script nonce="{{ $cspNonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartReservasi').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Jumlah Reservasi',
                data: @json($chartData),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
});
</script>
@endpush