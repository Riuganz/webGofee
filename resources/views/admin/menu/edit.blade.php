@extends('layouts.admin')

@section('title', 'Edit Menu')
@section('content')
<div class="card shadow-card card-3d">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Menu: {{ $menu->nama_menu }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.menu.update', $menu->id_menu) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_menu" class="form-label">Nama Menu</label>
                    <input type="text" class="form-control @error('nama_menu') is-invalid @enderror" id="nama_menu" name="nama_menu" value="{{ old('nama_menu', $menu->nama_menu) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="harga" class="form-label">Harga (Rp)</label>
                    <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga', $menu->harga) }}" min="0" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="2">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_kategori" class="form-label">Kategori</label>
                    <select class="form-select" id="id_kategori" name="id_kategori">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id_kategori }}" {{ $menu->id_kategori == $kat->id_kategori ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="stok_status" class="form-label">Status Stok</label>
                    <select class="form-select" id="stok_status" name="stok_status">
                        <option value="Tersedia" {{ $menu->stok_status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Habis" {{ $menu->stok_status == 'Habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto Menu</label>
                @if($menu->foto)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $menu->foto) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                    </div>
                @endif
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
