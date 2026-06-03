@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('auth_content')
<div class="auth-container">
    <div class="auth-card" style="max-width: 550px; margin-top: 30px; margin-bottom: 30px;">
        <div class="auth-header">
            <h1>Daftar Akun Baru</h1>
            <p>Registrasi Mandiri Pendaftar Beasiswa KIP Kuliah</p>
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

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1rem; border-radius: 12px; margin-bottom: 20px;">
                Daftar Sekarang
                <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
            </button>

            <p style="text-align: center; font-size: 0.9rem; color: var(--text-muted);">
                Sudah memiliki akun? <a href="{{ route('login') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Login disini</a>
            </p>
        </form>
    </div>
</div>
@endsection
