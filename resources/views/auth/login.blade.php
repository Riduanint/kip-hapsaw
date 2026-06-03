@extends('layouts.app')

@section('title', 'Login')

@section('auth_content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>SPK KIP Kuliah</h1>
            <p>Sistem Pendukung Keputusan Penerima KIP Kuliah</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px; padding: 12px 16px;">
                <i data-lucide="x-circle" style="width: 18px; height: 18px; flex-shrink: 0;"></i>
                <ul style="list-style: none; margin: 0; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 20px; padding: 12px 16px;">
                <i data-lucide="check-circle" style="width: 18px; height: 18px; flex-shrink: 0;"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 8px; margin-bottom: 25px;">
                <input type="checkbox" id="remember" name="remember" style="accent-color: var(--primary); cursor: pointer; width: 16px; height: 16px;">
                <label for="remember" style="margin-bottom: 0; cursor: pointer; font-size: 0.9rem;">Ingat Saya</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1rem; border-radius: 12px; margin-bottom: 20px;">
                Masuk
                <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
            </button>

            <p style="text-align: center; font-size: 0.9rem; color: var(--text-muted);">
                Belum punya akun? <a href="{{ route('register') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Daftar Mandiri</a>
            </p>
        </form>
    </div>
</div>
@endsection
