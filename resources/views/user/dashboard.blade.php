@extends('layouts.app')

@section('title', 'Status Penerimaan KIP')
@section('header_title', 'Status & Hasil Seleksi KIP Kuliah')

@section('content')

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: start;">
    
    <!-- Status Kelulusan Card -->
    <div class="panel" style="text-align: center; padding: 40px 30px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 20px; min-height: 350px;">
        <div class="panel-title" style="margin-bottom: 0; width: 100%; display: flex; justify-content: center;">
            <span>Status Kelulusan</span>
        </div>
        
        @if($alternative->status === 'selected')
            <div style="background: rgba(16, 185, 129, 0.1); border-radius: 50%; width: 90px; height: 90px; display: flex; justify-content: center; align-items: center; color: var(--success); border: 2px solid var(--success);">
                <i data-lucide="check-check" style="width: 46px; height: 46px;"></i>
            </div>
            <h2 style="color: var(--success); font-family: var(--font-heading); font-size: 1.8rem; font-weight: 700; margin-top: 10px;">Lolos Seleksi KIP</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">
                Selamat, Anda dinyatakan <strong>Lolos</strong> sebagai penerima bantuan KIP Kuliah dengan peringkat <strong>#{{ $alternative->rank }}</strong> (Skor Preferensi: {{ number_format($alternative->final_score, 4) }}). Silakan hubungi bagian kemahasiswaan untuk verifikasi berkas secara luring.
            </p>
        @elseif($alternative->status === 'not_selected')
            <div style="background: rgba(239, 68, 68, 0.1); border-radius: 50%; width: 90px; height: 90px; display: flex; justify-content: center; align-items: center; color: var(--danger); border: 2px solid var(--danger);">
                <i data-lucide="x" style="width: 46px; height: 46px;"></i>
            </div>
            <h2 style="color: var(--danger); font-family: var(--font-heading); font-size: 1.8rem; font-weight: 700; margin-top: 10px;">Belum Lolos Seleksi</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">
                Mohon maaf, Anda dinyatakan <strong>Belum Terpilih</strong> sebagai penerima KIP Kuliah pada periode ini (Peringkat <strong>#{{ $alternative->rank }}</strong> - Skor Preferensi: {{ number_format($alternative->final_score, 4) }}). Tetap semangat dan coba lagi di kesempatan beasiswa lainnya!
            </p>
        @else
            <div style="background: rgba(245, 158, 11, 0.1); border-radius: 50%; width: 90px; height: 90px; display: flex; justify-content: center; align-items: center; color: var(--warning); border: 2px solid var(--warning);">
                <i data-lucide="clock" style="width: 46px; height: 46px;"></i>
            </div>
            <h2 style="color: var(--warning); font-family: var(--font-heading); font-size: 1.8rem; font-weight: 700; margin-top: 10px;">Berkas Sedang Diverifikasi</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">
                Berkas pengajuan pendaftaran Anda sedang dalam proses peninjauan oleh pihak administrator. Hasil kelulusan akan dirilis secara resmi setelah proses perankingan AHP-SAW dikunci oleh admin.
            </p>
        @endif
    </div>

    <!-- Identity Details Card -->
    <div class="panel" style="min-height: 350px; display: flex; flex-direction: column;">
        <div class="panel-title">
            <span>Informasi Akun Pendaftar</span>
            <i data-lucide="shield-check" style="color: var(--primary); width: 20px; height: 20px;"></i>
        </div>
        <div style="display: flex; flex-direction: column; gap: 24px; font-size: 0.95rem; flex-grow: 1; justify-content: center;">
            <div style="border-bottom: 1px solid var(--border-glass); padding-bottom: 12px;">
                <span style="color: var(--text-muted); display: block; font-size: 0.8rem; margin-bottom: 5px; text-transform: uppercase;">NISN Pendaftar</span>
                <strong style="color: #fff; font-family: var(--font-heading); font-size: 1.3rem;">{{ $alternative->nisn }}</strong>
            </div>
            <div style="border-bottom: 1px solid var(--border-glass); padding-bottom: 12px;">
                <span style="color: var(--text-muted); display: block; font-size: 0.8rem; margin-bottom: 5px; text-transform: uppercase;">Nama Lengkap</span>
                <strong style="color: #fff; font-size: 1.1rem;">{{ $alternative->name }}</strong>
            </div>
            <div style="border-bottom: 1px solid var(--border-glass); padding-bottom: 12px;">
                <span style="color: var(--text-muted); display: block; font-size: 0.8rem; margin-bottom: 5px; text-transform: uppercase;">Alamat Email</span>
                <strong style="color: #fff; font-size: 1.1rem;">{{ $alternative->email ?? $user->email }}</strong>
            </div>
            <div>
                <span style="color: var(--text-muted); display: block; font-size: 0.8rem; margin-bottom: 5px; text-transform: uppercase;">Nomor Telepon / WhatsApp</span>
                <strong style="color: #fff; font-size: 1.1rem;">{{ $alternative->phone ?? '-' }}</strong>
            </div>
        </div>
    </div>

</div>

@endsection
