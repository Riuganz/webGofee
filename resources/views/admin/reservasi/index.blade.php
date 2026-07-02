@extends('layouts.admin')

@section('title', 'Reservasi')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-calendar-check"></i> Daftar Reservasi</h3>
</div>

<div class="card shadow-sm">
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
