@extends('layouts.admin')

@section('title', 'Kategori Menu')
@section('content')
<div class="card shadow-sm">
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
                            <form method="POST" action="{{ route('admin.kategori.destroy', $kat->id_kategori) }}" class="d-inline" onsubmit="return confirm('Hapus kategori?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
