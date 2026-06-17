@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('auth_content')
<div class="split-auth">
    <!-- Left Panel: Branding -->
    <div class="split-left">
        <div class="split-left-content">
            <a href="/" class="split-logo">
                <i data-lucide="graduation-cap" style="width: 36px; height: 36px;"></i>
                <span>SPK <strong>KIP</strong></span>
            </a>

            <div class="split-hero">
                <h1>Raih Beasiswa<br>KIP Kuliah!</h1>
                <p>Daftarkan diri Anda untuk mengikuti proses seleksi penerima beasiswa KIP Kuliah. Seleksi dilakukan secara objektif dan transparan menggunakan sistem pendukung keputusan.</p>
            </div>

            <div class="split-features">
                <div class="split-feature-item">
                    <div class="split-feature-icon" style="background: var(--success-bg); color: var(--success);">
                        <i data-lucide="user-plus" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <strong>Registrasi Mudah</strong>
                        <span>Cukup isi data dasar untuk mulai mendaftar</span>
                    </div>
                </div>
                <div class="split-feature-item">
                    <div class="split-feature-icon" style="background: var(--warning-bg); color: var(--warning);">
                        <i data-lucide="file-edit" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <strong>Lengkapi Berkas</strong>
                        <span>Upload dokumen persyaratan melalui dashboard</span>
                    </div>
                </div>
                <div class="split-feature-item">
                    <div class="split-feature-icon">
                        <i data-lucide="award" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <strong>Pantau Hasil</strong>
                        <span>Lihat status dan ranking seleksi secara real-time</span>
                    </div>
                </div>
            </div>

            <div class="split-left-footer">
                &copy; {{ date('Y') }} SPK KIP Kuliah &mdash; AHP & SAW
            </div>
        </div>

        <!-- Decorative blobs -->
        <div class="split-blob split-blob-1"></div>
        <div class="split-blob split-blob-2"></div>
    </div>

    <!-- Right Panel: Form -->
    <div class="split-right">
        <div class="split-form-wrapper">
            <div class="split-form-header">
                <h2>Buat Akun Baru</h2>
                <p>Lengkapi informasi di bawah untuk mendaftar sebagai calon penerima KIP</p>
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
                    <div class="input-icon-wrapper">
                        <i data-lucide="user" class="input-icon"></i>
                        <input type="text" id="name" name="name" class="form-control" style="padding-left: 44px;" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <div class="input-icon-wrapper">
                        <i data-lucide="mail" class="input-icon"></i>
                        <input type="email" id="email" name="email" class="form-control" style="padding-left: 44px;" placeholder="nama@email.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-icon-wrapper">
                        <i data-lucide="key-round" class="input-icon"></i>
                        <input type="password" id="password" name="password" class="form-control" style="padding-left: 44px;" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 28px;">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <div class="input-icon-wrapper">
                        <i data-lucide="lock" class="input-icon"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" style="padding-left: 44px;" placeholder="Ulangi kata sandi" required>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; margin-bottom: 24px;">
                    <a href="/" class="btn btn-secondary" style="flex: 1; padding: 13px; font-size: 0.95rem; border-radius: 12px; text-align: center;">
                        <i data-lucide="home" style="width: 18px; height: 18px;"></i>
                        Beranda
                    </a>
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 13px; font-size: 0.95rem; border-radius: 12px;">
                        Daftar Sekarang
                        <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
                    </button>
                </div>

                <p style="text-align: center; font-size: 0.9rem; color: var(--text-muted);">
                    Sudah memiliki akun? <a href="{{ route('login') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Login disini</a>
                </p>
            </form>
        </div>
    </div>
</div>

<style>
    /* ============================================ */
    /*  SPLIT-SCREEN AUTH LAYOUT                   */
    /* ============================================ */
    body { overflow-x: hidden; }

    .split-auth {
        display: flex;
        width: 100%;
        min-height: 100vh;
    }

    /* --- Left Panel --- */
    .split-left {
        flex: 1;
        background: linear-gradient(160deg, hsl(245, 60%, 12%) 0%, hsl(260, 50%, 8%) 50%, var(--bg-app) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px;
        position: relative;
        overflow: hidden;
    }

    .split-left-content {
        position: relative;
        z-index: 2;
        max-width: 480px;
        display: flex;
        flex-direction: column;
        height: 100%;
        justify-content: center;
    }

    .split-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--primary);
        text-decoration: none;
        font-family: var(--font-heading);
        font-size: 1.4rem;
        margin-bottom: 50px;
        letter-spacing: 0.5px;
        transition: var(--transition);
    }

    .split-logo:hover { opacity: 0.85; }

    .split-logo strong { color: #fff; }

    .split-hero h1 {
        font-family: var(--font-heading);
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 18px;
        background: linear-gradient(135deg, #fff 30%, var(--primary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .split-hero p {
        color: var(--text-muted);
        font-size: 1.02rem;
        line-height: 1.75;
        margin-bottom: 40px;
    }

    .split-features {
        display: flex;
        flex-direction: column;
        gap: 18px;
        margin-bottom: 40px;
    }

    .split-feature-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 14px 18px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 14px;
        transition: var(--transition);
    }

    .split-feature-item:hover {
        background: rgba(255, 255, 255, 0.07);
        transform: translateX(4px);
    }

    .split-feature-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: var(--primary-glow);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .split-feature-item strong {
        display: block;
        font-size: 0.92rem;
        color: var(--text-main);
        margin-bottom: 2px;
    }

    .split-feature-item span {
        font-size: 0.82rem;
        color: var(--text-dim);
    }

    .split-left-footer {
        font-size: 0.8rem;
        color: var(--text-dim);
    }

    /* Decorative Blobs */
    .split-blob {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        filter: blur(80px);
    }

    .split-blob-1 {
        width: 350px;
        height: 350px;
        background: rgba(99, 102, 241, 0.2);
        top: -80px;
        left: -80px;
    }

    .split-blob-2 {
        width: 280px;
        height: 280px;
        background: rgba(139, 92, 246, 0.15);
        bottom: -60px;
        right: -60px;
    }

    /* --- Right Panel --- */
    .split-right {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px 60px;
        background: var(--bg-app);
        position: relative;
    }

    .split-right::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 1px;
        height: 100%;
        background: linear-gradient(180deg, transparent 0%, var(--border-glass) 20%, var(--primary) 50%, var(--border-glass) 80%, transparent 100%);
    }

    .split-form-wrapper {
        width: 100%;
        max-width: 440px;
        animation: fadeIn 0.6s ease-out;
    }

    .split-form-header {
        margin-bottom: 32px;
    }

    .split-form-header h2 {
        font-family: var(--font-heading);
        font-size: 1.9rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text-main);
    }

    .split-form-header p {
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: var(--text-dim);
        pointer-events: none;
        transition: color 0.3s;
    }

    .input-icon-wrapper .form-control:focus ~ .input-icon,
    .input-icon-wrapper:focus-within .input-icon {
        color: var(--primary);
    }

    /* --- Responsive --- */
    @media (max-width: 992px) {
        .split-auth {
            flex-direction: column;
        }

        .split-left {
            padding: 40px 30px;
            min-height: auto;
        }

        .split-hero h1 { font-size: 2rem; }

        .split-features { display: none; }
        .split-left-footer { display: none; }

        .split-right {
            padding: 40px 30px;
        }

        .split-right::before { display: none; }

        .split-form-wrapper {
            max-width: 100%;
        }
    }
</style>
@endsection
