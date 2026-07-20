@extends('layouts.admin')

@section('title', 'Kategori Menu')
@section('content')
<div class="card shadow-card card-3d">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-tags"></i> Kategori Menu</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kategori.store') }}" class="mb-4">
            @csrf
            <div class="input-group">
                <input type="text" class="form-control" name="nama_kategori" placeholder="Nama kategori baru..." required>
                <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</button>
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $kat)
                    <tr>
                        <td>{{ $kat->id_kategori }}</td>
                        <td>{{ $kat->nama_kategori }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $kat->id_kategori }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.kategori.destroy', $kat->id_kategori) }}" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal{{ $kat->id_kategori }}" tabindex="-1" aria-labelledby="editModalLabel{{ $kat->id_kategori }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.kategori.update', $kat->id_kategori) }}">
                                    @csrf @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel{{ $kat->id_kategori }}">Edit Kategori</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="nama_kategori{{ $kat->id_kategori }}" class="form-label">Nama Kategori</label>
                                            <input type="text" class="form-control" id="nama_kategori{{ $kat->id_kategori }}" name="nama_kategori" value="{{ $kat->nama_kategori }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr><td colspan="3" class="text-center">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection