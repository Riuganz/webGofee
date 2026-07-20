@extends('layouts.admin')

@section('title', 'Edit Meja')
@section('content')
<div class="card shadow-card card-3d">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Meja {{ $meja->nomor_meja }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.meja.update', $meja->id_meja) }}">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nomor_meja" class="form-label">Nomor Meja</label>
                    <input type="text" class="form-control @error('nomor_meja') is-invalid @enderror" id="nomor_meja" name="nomor_meja" value="{{ old('nomor_meja', $meja->nomor_meja) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kapasitas" class="form-label">Kapasitas Kursi</label>
                    <input type="number" class="form-control @error('kapasitas') is-invalid @enderror" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $meja->kapasitas) }}" min="1" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="status_meja" class="form-label">Status</label>
                <select class="form-select" id="status_meja" name="status_meja">
                    <option value="Tersedia" {{ $meja->status_meja == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Terisi" {{ $meja->status_meja == 'Terisi' ? 'selected' : '' }}>Terisi</option>
                    <option value="Dibooking" {{ $meja->status_meja == 'Dibooking' ? 'selected' : '' }}>Dibooking</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.meja.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
