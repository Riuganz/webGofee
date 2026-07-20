@extends('layouts.admin')

@section('title', 'Tambah Menu')
@section('content')
<div class="card shadow-card card-3d">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-plus-lg"></i> Tambah Menu Baru</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_menu" class="form-label">Nama Menu</label>
                    <input type="text" class="form-control @error('nama_menu') is-invalid @enderror" id="nama_menu" name="nama_menu" value="{{ old('nama_menu') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="harga" class="form-label">Harga (Rp)</label>
                    <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga') }}" min="0" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="2">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_kategori" class="form-label">Kategori</label>
                    <select class="form-select" id="id_kategori" name="id_kategori">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="stok_status" class="form-label">Status Stok</label>
                    <select class="form-select" id="stok_status" name="stok_status">
                        <option value="Tersedia">Tersedia</option>
                        <option value="Habis">Habis</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto Menu</label>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
