@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="row justify-content-center" style="padding: 3rem 0;">
    <div class="col-md-6">
        <div class="card card-3d" style="border: none; border-radius: 20px;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #1e3a5f, #2563eb); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="bi bi-cup-hot-fill" style="font-size: 2rem; color: var(--accent-gold);"></i>
                    </div>
                    <h3 style="font-family: 'Playfair Display', serif; font-weight: 800;">Join Us Today</h3>
                    <p style="color: #6b7280;">Buat akun untuk menikmati layanan reservasi</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label" style="font-weight: 600; font-size: 0.9rem;">Nama Lengkap</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required style="padding: 0.75rem 1rem; border-radius: 12px; border: 2px solid #e5e7eb;">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label" style="font-weight: 600; font-size: 0.9rem;">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required style="padding: 0.75rem 1rem; border-radius: 12px; border: 2px solid #e5e7eb;">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="no_wa" class="form-label" style="font-weight: 600; font-size: 0.9rem;">Nomor WhatsApp</label>
                        <input type="text" class="form-control @error('no_wa') is-invalid @enderror" id="no_wa" name="no_wa" value="{{ old('no_wa') }}" required placeholder="08xxxxxxxxxx" style="padding: 0.75rem 1rem; border-radius: 12px; border: 2px solid #e5e7eb;">
                        @error('no_wa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label" style="font-weight: 600; font-size: 0.9rem;">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required style="padding: 0.75rem 1rem; border-radius: 12px; border: 2px solid #e5e7eb;">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label" style="font-weight: 600; font-size: 0.9rem;">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required style="padding: 0.75rem 1rem; border-radius: 12px; border: 2px solid #e5e7eb;">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-lg" style="background: var(--primary-blue); color: #fff; border: none; padding: 0.8rem; border-radius: 12px; font-weight: 600;">Daftar</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0" style="color: #6b7280;">Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--primary-blue); font-weight: 600; text-decoration: none;">Login di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection