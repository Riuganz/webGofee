@extends('layouts.admin')

@section('title', 'Data Menu')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-menu-app"></i> Data Menu</h3>
    <a href="{{ route('admin.menu.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Menu</a>
</div>

<div class="card shadow-card card-3d">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                        <tr>
                            <td>{{ $menu->id_menu }}</td>
                            <td>
                                @if($menu->foto)
                                    <img src="{{ asset('storage/' . $menu->foto) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                @else
                                    <i class="bi bi-cup-straw" style="font-size: 2rem;"></i>
                                @endif
                            </td>
                            <td><strong>{{ $menu->nama_menu }}</strong></td>
                            <td>{{ $menu->kategori->nama_kategori ?? '-' }}</td>
                            <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $menu->stok_status == 'Tersedia' ? 'success' : 'danger' }}">{{ $menu->stok_status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.menu.edit', $menu->id_menu) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="{{ route('admin.menu.toggle', $menu->id_menu) }}" class="btn btn-sm btn-info"><i class="bi bi-arrow-repeat"></i></a>
                                <form method="POST" action="{{ route('admin.menu.destroy', $menu->id_menu) }}" class="d-inline" onsubmit="return confirm('Hapus menu ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Belum ada data menu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
