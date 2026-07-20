@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center" style="padding: 3rem 0;">
    <div class="col-md-5">
        <div class="card card-3d" style="border: none; border-radius: 20px;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #1e3a5f, #2563eb); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="bi bi-cup-hot-fill" style="font-size: 2rem; color: var(--accent-gold);"></i>
                    </div>
                    <h3 style="font-family: 'Playfair Display', serif; font-weight: 800;">Welcome Back</h3>
                    <p style="color: #6b7280;">Masuk untuk memesan tempat dan menu</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label" style="font-weight: 600; font-size: 0.9rem;">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus style="padding: 0.75rem 1rem; border-radius: 12px; border: 2px solid #e5e7eb;">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label" style="font-weight: 600; font-size: 0.9rem;">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required style="padding: 0.75rem 1rem; border-radius: 12px; border: 2px solid #e5e7eb;">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-lg" style="background: var(--primary-blue); color: #fff; border: none; padding: 0.8rem; border-radius: 12px; font-weight: 600;">Login</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0" style="color: #6b7280;">Belum punya akun? <a href="{{ route('register') }}" style="color: var(--primary-blue); font-weight: 600; text-decoration: none;">Daftar di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection