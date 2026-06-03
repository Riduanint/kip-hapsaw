@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('header_title', 'Dashboard SPK')

@section('content')
<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <h3>Total Kriteria</h3>
            <p>{{ $totalCriteria }}</p>
        </div>
        <div class="stat-icon">
            <i data-lucide="list-checks" style="width: 24px; height: 24px;"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <h3>Total Pendaftar</h3>
            <p>{{ $totalAlternatives }}</p>
        </div>
        <div class="stat-icon">
            <i data-lucide="users" style="width: 24px; height: 24px;"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <h3>Akun Terdaftar</h3>
            <p>{{ $totalUsers }}</p>
        </div>
        <div class="stat-icon">
            <i data-lucide="user-check" style="width: 24px; height: 24px;"></i>
        </div>
    </div>
</div>

<!-- Info Panel -->
<div class="panel">
    <div class="panel-title">
        <span>Informasi Sistem Hybrid AHP - SAW</span>
        <i data-lucide="info" style="color: var(--primary); width: 20px; height: 20px;"></i>
    </div>
    <div style="line-height: 1.6; color: var(--text-muted);">
        <p style="margin-bottom: 12px;">Sistem Pendukung Keputusan (SPK) ini menggunakan kombinasi dua metode:</p>
        <ol style="margin-left: 20px; display: flex; flex-direction: column; gap: 8px;">
            <li><strong>Analytic Hierarchy Process (AHP):</strong> Digunakan untuk menentukan bobot kepentingan dari masing-masing kriteria. Perbandingan dilakukan secara berpasangan antar kriteria untuk mendapatkan bobot objektif yang konsisten (Consistency Ratio < 0.1).</li>
            <li><strong>Simple Additive Weighting (SAW):</strong> Digunakan untuk menentukan peringkat dari para alternatif (pendaftar beasiswa) dengan cara menormalisasi skor berdasarkan jenis kriteria (benefit/cost) lalu dikalikan dengan bobot yang dihasilkan dari metode AHP.</li>
        </ol>
    </div>
</div>

<!-- Top Alternatives Panel -->
<div class="panel">
    <div class="panel-title">
        <span>Peringkat 5 Teratas Saat Ini</span>
        <a href="{{ route('admin.saw', ['sk' => $sk]) }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 0.8rem;">
            Lihat Semua Ranking
            <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
        </a>
    </div>
    
    @if($topAlternatives->isEmpty())
        <div style="text-align: center; padding: 30px; color: var(--text-muted);">
            <i data-lucide="award" style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.5;"></i>
            <p>Belum ada ranking yang dikunci. Silakan hitung & kunci hasil di menu <strong>Perankingan SAW</strong>.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px; text-align: center;">Rank</th>
                        <th>NISN</th>
                        <th>Nama Pendaftar</th>
                        <th>Skor Akhir (V)</th>
                        <th style="width: 150px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topAlternatives as $alt)
                        <tr>
                            <td style="text-align: center; font-weight: 700; color: var(--primary);">
                                #{{ $alt->rank }}
                            </td>
                            <td>{{ $alt->nisn }}</td>
                            <td style="font-weight: 500;">{{ $alt->name }}</td>
                            <td style="font-family: var(--font-heading); font-weight: 600; color: #fff;">
                                {{ number_format($alt->final_score, 4) }}
                            </td>
                            <td style="text-align: center;">
                                @if($alt->status === 'selected')
                                    <span class="badge badge-success">Penerima KIP</span>
                                @elseif($alt->status === 'not_selected')
                                    <span class="badge badge-danger">Tidak Terpilih</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
