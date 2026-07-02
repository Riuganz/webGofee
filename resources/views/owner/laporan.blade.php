@extends('layouts.owner')

@section('title', 'Laporan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-file-earmark-bar-graph"></i> Laporan Transaksi</h3>
    <div class="btn-group" role="group">
        <a href="{{ route('owner.laporan.export.csv', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet"></i> Download CSV
        </a>
        <a href="{{ route('owner.laporan.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-danger btn-sm" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Print PDF
        </a>
        <a href="{{ route('owner.laporan.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-download"></i> Download PDF
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('owner.laporan') }}" class="row g-3">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Dari Tanggal</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">Sampai Tanggal</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('owner.laporan') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <h6>Total Pendapatan</h6>
                <h2 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <h6>Total Transaksi Sukses</h6>
                <h2 class="mb-0">{{ $totalTransactions }}</h2>
            </div>
        </div>
    </div>
</div>

@if($dailyBreakdown->count() > 0)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Rekapitulasi Harian</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Total Transaksi</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyBreakdown as $db)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($db->date)->format('d/m/Y') }}</td>
                            <td>{{ $db->total_transaksi }}</td>
                            <td>Rp {{ number_format($db->pendapatan, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-list"></i> Detail Transaksi</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="tableTransaksi">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pelanggan</th>
                        <th>Tipe</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasis as $r)
                        <tr>
                            <td>{{ $r->id_reservasi }}</td>
                            <td>{{ $r->user->name ?? 'Walk-in' }}</td>
                            <td>{{ $r->tipe }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }} {{ $r->jam }}</td>
                            <td>Rp {{ number_format($r->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $r->status_reservasi == 'Selesai' ? 'success' : ($r->status_reservasi == 'Diterima' ? 'info' : ($r->status_reservasi == 'Menunggu Konfirmasi' ? 'warning' : 'danger')) }}">
                                    {{ $r->status_reservasi }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Tidak ada data transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Simple export to CSV
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'p') {
        window.print();
    }
});
</script>
@endpush
@endsection
