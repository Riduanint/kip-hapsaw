@extends('layouts.app')

@section('title', 'Status Penerimaan KIP')
@section('header_title', 'Status & Hasil Seleksi KIP Kuliah')

@section('content')

@php
    $isPending = $alternative->status === 'pending';
    $isSelected = $alternative->status === 'selected';
    $isNotSelected = $alternative->status === 'not_selected';
    $isFinished = $isSelected || $isNotSelected;
@endphp

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: start;">
    
    <!-- Progress Tracker -->
    <div class="panel" style="grid-column: 1 / -1;">
        <div class="panel-title" style="margin-bottom: 30px;">
            <span>Progres Pendaftaran</span>
        </div>
        
        <div class="progress-tracker">
            <!-- Step 1: Registrasi -->
            <div class="step completed line-active">
                <div class="step-icon"><i data-lucide="check" stroke-width="3"></i></div>
                <div class="step-label">
                    <strong>Registrasi Akun</strong>
                    <span>Selesai</span>
                </div>
            </div>

            <!-- Step 2: Isi Form -->
            <div class="step completed {{ $isFinished ? 'line-active' : '' }}">
                <div class="step-icon"><i data-lucide="check" stroke-width="3"></i></div>
                <div class="step-label">
                    <strong>Isi Form & Upload</strong>
                    <span>Selesai</span>
                </div>
            </div>

            <!-- Step 3: Verifikasi -->
            <div class="step {{ $isFinished ? 'completed line-active' : 'in-progress' }}">
                <div class="step-icon">
                    <i data-lucide="{{ $isFinished ? 'check' : 'hourglass' }}" stroke-width="{{ $isFinished ? '3' : '2' }}"></i>
                </div>
                <div class="step-label">
                    <strong>Verifikasi Berkas</strong>
                    <span>{{ $isFinished ? 'Selesai' : 'Sedang Diproses' }}</span>
                </div>
            </div>

            <!-- Step 4: Pengumuman -->
            <div class="step {{ $isFinished ? 'completed' : 'upcoming' }}">
                <div class="step-icon" style="{{ $isSelected ? 'background: var(--success); border-color: var(--success); color: white; box-shadow: 0 0 15px rgba(16, 185, 129, 0.3);' : ($isNotSelected ? 'background: var(--danger); border-color: var(--danger); color: white; box-shadow: 0 0 15px rgba(239, 68, 68, 0.3);' : '') }}">
                    <i data-lucide="{{ $isSelected ? 'check-circle' : ($isNotSelected ? 'x-circle' : 'megaphone') }}"></i>
                </div>
                <div class="step-label">
                    <strong style="{{ $isFinished ? 'color: var(--text-main);' : '' }}">Pengumuman Hasil</strong>
                    <span style="{{ $isSelected ? 'color: var(--success);' : ($isNotSelected ? 'color: var(--danger);' : '') }}">
                        @if($isSelected) Lolos Seleksi 
                        @elseif($isNotSelected) Tidak Lolos 
                        @else Mendatang 
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Kelulusan Card -->
    <div class="panel" style="text-align: center; padding: 40px 30px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 20px; min-height: 350px;">
        <div class="panel-title" style="margin-bottom: 0; width: 100%; display: flex; justify-content: center;">
            <span>Status Kelulusan</span>
        </div>
        
        @if($isSelected)
            <div style="background: rgba(16, 185, 129, 0.1); border-radius: 50%; width: 90px; height: 90px; display: flex; justify-content: center; align-items: center; color: var(--success); border: 2px solid var(--success);">
                <i data-lucide="check-check" style="width: 46px; height: 46px;"></i>
            </div>
            <h2 style="color: var(--success); font-family: var(--font-heading); font-size: 1.8rem; font-weight: 700; margin-top: 10px;">Lolos Seleksi KIP</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">
                Selamat, Anda dinyatakan <strong>Lolos</strong> sebagai penerima bantuan KIP Kuliah dengan peringkat <strong>#{{ $alternative->rank }}</strong> (Skor Preferensi: {{ number_format($alternative->final_score, 4) }}). Silakan hubungi bagian kemahasiswaan untuk verifikasi berkas secara luring.
            </p>
        @elseif($isNotSelected)
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

<style>
.progress-tracker {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    position: relative;
    padding: 0 10px;
    margin-top: 10px;
    margin-bottom: 10px;
}

.step {
    flex: 1;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 25px;
    left: 50%;
    width: 100%;
    height: 3px;
    background: var(--border-glass);
    z-index: 0;
    transition: background 0.3s;
}

.step.line-active:not(:last-child)::after {
    background: var(--success);
}

.step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: var(--bg-app);
    border: 2px solid var(--border-glass);
    color: var(--text-muted);
    margin-bottom: 15px;
    position: relative;
    z-index: 1;
    transition: all 0.3s ease;
}

.step-label strong {
    display: block;
    font-family: var(--font-heading);
    font-size: 1.05rem;
    color: var(--text-muted);
    margin-bottom: 6px;
    transition: color 0.3s ease;
}

.step-label span {
    font-size: 0.85rem;
    color: var(--text-dim);
    font-weight: 500;
}

/* Completed */
.step.completed .step-icon {
    background: var(--success);
    border-color: var(--success);
    color: #fff;
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.3);
}
.step.completed .step-label strong {
    color: var(--text-main);
}
.step.completed .step-label span {
    color: var(--success);
}

/* In Progress */
.step.in-progress .step-icon {
    background: var(--success-bg);
    border-color: var(--success);
    color: var(--success);
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
}
.step.in-progress .step-icon i {
    animation: pulse 2s infinite;
}
.step.in-progress .step-label strong {
    color: var(--success);
}
.step.in-progress .step-label span {
    color: var(--success);
}

/* Upcoming */
.step.upcoming .step-icon {
    background: rgba(255, 255, 255, 0.03);
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.15); }
    100% { transform: scale(1); }
}

@media (max-width: 768px) {
    .progress-tracker {
        flex-direction: column;
        align-items: flex-start;
        gap: 30px;
        padding-left: 30px;
    }
    .step {
        flex-direction: row;
        width: 100%;
        text-align: left;
    }
    .step-icon {
        margin-bottom: 0;
        margin-right: 20px;
    }
    .step:not(:last-child)::after {
        width: 3px;
        height: 100%;
        left: 24px;
        top: 50px;
    }
}
</style>

@endsection
