@extends('layouts.admin')

@section('title', 'Data Meja')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-grid-3x3-gap"></i> Data Meja</h3>
    <a href="{{ route('admin.meja.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Meja</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nomor Meja</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mejas as $meja)
                        <tr>
                            <td>{{ $meja->id_meja }}</td>
                            <td><strong>{{ $meja->nomor_meja }}</strong></td>
                            <td>{{ $meja->kapasitas }} kursi</td>
                            <td>
                                <span class="badge bg-{{ $meja->status_meja == 'Tersedia' ? 'success' : ($meja->status_meja == 'Terisi' ? 'danger' : 'warning') }}">
                                    {{ $meja->status_meja }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.meja.edit', $meja->id_meja) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="{{ route('admin.meja.toggle', $meja->id_meja) }}" class="btn btn-sm btn-info"><i class="bi bi-arrow-repeat"></i></a>
                                <form method="POST" action="{{ route('admin.meja.destroy', $meja->id_meja) }}" class="d-inline" onsubmit="return confirm('Hapus meja ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Belum ada data meja.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
