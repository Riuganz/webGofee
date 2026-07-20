@extends('layouts.admin')

@section('title', 'Reservasi')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-calendar-check"></i> Daftar Reservasi</h3>
</div>

{{-- Form Filter --}}
<div class="card shadow-card card-3d mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reservasi.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="status" class="form-label fw-semibold">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="tanggal_mulai" class="form-label fw-semibold">Tanggal Mulai</label>
                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-3">
                <label for="tanggal_akhir" class="form-label fw-semibold">Tanggal Akhir</label>
                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.reservasi.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-card card-3d">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pelanggan</th>
                        <th>No. WA</th>
                        <th>Tipe</th>
                        <th>Meja</th>
                        <th>Tanggal/Jam</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasis as $r)
                        <tr>
                            <td>{{ $r->id_reservasi }}</td>
                            <td>{{ $r->user->name ?? 'Walk-in' }}</td>
                            <td>{{ $r->user->no_wa ?? '-' }}</td>
                            <td><span class="badge bg-{{ $r->tipe == 'dine-in' ? 'primary' : 'success' }}">{{ $r->tipe }}</span></td>
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
                        <tr><td colspan="9" class="text-center">Belum ada reservasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection