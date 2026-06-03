@extends('layouts.app')

@section('title', 'Pengajuan KIP')
@section('header_title', 'Form Pengajuan Data & Nilai Kriteria')

@section('content')

<div class="panel">
    <div class="panel-title">
        <span>Pengisian Berkas & Parameter Kriteria</span>
        <i data-lucide="edit-3" style="color: var(--primary); width: 20px; height: 20px;"></i>
    </div>

    <form action="{{ route('user.profile.update') }}" method="POST">
        @csrf
        <input type="hidden" name="sk" value="{{ $sk }}">

        <!-- Personal Data fields -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="name">Nama Lengkap Siswa</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $alternative->name) }}" required>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="phone">Nomor Telepon / WhatsApp</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $alternative->phone) }}" placeholder="Contoh: 081234567890">
            </div>
        </div>

        <div style="border-top: 1px solid var(--border-glass); padding-top: 20px; margin-bottom: 20px;">
            <h3 style="font-family: var(--font-heading); font-size: 1.1rem; font-weight: 600; margin-bottom: 5px; color: var(--primary);">Kuesioner Parameter Kriteria</h3>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 20px;">Silakan masukkan nilai parameter kriteria pendaftaran Anda di bawah ini secara jujur.</p>
        </div>

        <!-- Dynamic Criteria Inputs -->
        @foreach($criteria as $c)
            @php
                $scoreValue = $scores[$c->id] ?? 0.0;
            @endphp
            <div class="form-group" style="margin-bottom: 25px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <label for="crit-{{ $c->id }}" style="margin-bottom: 0; font-weight: 600; color: #fff;">
                        {{ $c->code }} - {{ $c->name }}
                    </label>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">
                        Tipe: {{ strtoupper($c->type) }}
                    </span>
                </div>

                @if($c->code === 'C1')
                    <!-- Parents Income -->
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="color: var(--text-muted); font-weight: bold;">Rp</span>
                        <input type="number" 
                               id="crit-{{ $c->id }}" 
                               name="scores[{{ $c->id }}]" 
                               class="form-control" 
                               value="{{ $scoreValue }}" 
                               placeholder="Contoh: 1500000" 
                               required>
                    </div>
                    <small style="color: var(--text-muted); display: block; margin-top: 5px;">Nominal total pendapatan gabungan orang tua/wali per bulan (semakin kecil, semakin berhak menerima beasiswa).</small>
                
                @elseif($c->code === 'C2')
                    <!-- Average Grade -->
                    <input type="number" 
                           step="any"
                           id="crit-{{ $c->id }}" 
                           name="scores[{{ $c->id }}]" 
                           class="form-control" 
                           value="{{ $scoreValue }}" 
                           placeholder="Skala 0 - 100" 
                           required>
                    <small style="color: var(--text-muted); display: block; margin-top: 5px;">Nilai rata-rata rapor SMA/SMK sederajat semester terakhir.</small>

                @elseif($c->code === 'C3')
                    <!-- Dependents -->
                    <input type="number" 
                           id="crit-{{ $c->id }}" 
                           name="scores[{{ $c->id }}]" 
                           class="form-control" 
                           value="{{ $scoreValue }}" 
                           placeholder="Jumlah anak yang ditanggung" 
                           required>
                    <small style="color: var(--text-muted); display: block; margin-top: 5px;">Jumlah tanggungan anak orang tua yang masih bersekolah/belum bekerja.</small>
                
                @elseif($c->code === 'C4')
                    <!-- House condition -->
                    <select id="crit-{{ $c->id }}" name="scores[{{ $c->id }}]" class="form-control form-select" required>
                        <option value="1" {{ abs($scoreValue - 1) < 0.1 ? 'selected' : '' }}>1 - Sangat Sederhana / Semi-Permanen / Rusak Parah</option>
                        <option value="2" {{ abs($scoreValue - 2) < 0.1 ? 'selected' : '' }}>2 - Sederhana / Dinding Papan / Lantai Semen</option>
                        <option value="3" {{ abs($scoreValue - 3) < 0.1 ? 'selected' : '' }}>3 - Cukup Layak / Permanen Sederhana</option>
                        <option value="4" {{ abs($scoreValue - 4) < 0.1 ? 'selected' : '' }}>4 - Sangat Layak / Keramik / Kokoh</option>
                        <option value="5" {{ abs($scoreValue - 5) < 0.1 ? 'selected' : '' }}>5 - Mewah / Rumah Tingkat / Luas</option>
                    </select>
                    <small style="color: var(--text-muted); display: block; margin-top: 5px;">Kondisi fisik tempat tinggal saat ini (semakin rendah nilai, semakin prioritas mendapat KIP).</small>
                
                @elseif($c->code === 'C5')
                    <!-- Achievement -->
                    <select id="crit-{{ $c->id }}" name="scores[{{ $c->id }}]" class="form-control form-select" required>
                        <option value="1" {{ abs($scoreValue - 1) < 0.1 ? 'selected' : '' }}>1 - Tidak Ada Prestasi Tambahan</option>
                        <option value="2" {{ abs($scoreValue - 2) < 0.1 ? 'selected' : '' }}>2 - Juara Tingkat Sekolah / Kecamatan</option>
                        <option value="3" {{ abs($scoreValue - 3) < 0.1 ? 'selected' : '' }}>3 - Juara Tingkat Kabupaten / Kota</option>
                        <option value="4" {{ abs($scoreValue - 4) < 0.1 ? 'selected' : '' }}>4 - Juara Tingkat Provinsi</option>
                        <option value="5" {{ abs($scoreValue - 5) < 0.1 ? 'selected' : '' }}>5 - Juara Tingkat Nasional / Internasional</option>
                    </select>
                    <small style="color: var(--text-muted); display: block; margin-top: 5px;">Prestasi akademik atau non-akademik tertinggi yang memiliki sertifikat sah.</small>
                
                @else
                    <!-- Fallback for dynamic added criteria -->
                    <input type="number" 
                           step="any"
                           id="crit-{{ $c->id }}" 
                           name="scores[{{ $c->id }}]" 
                           class="form-control" 
                           value="{{ $scoreValue }}" 
                           required>
                @endif
            </div>
        @endforeach

        <div style="margin-top: 30px;">
            @if($alternative->status !== 'pending')
                <div class="alert alert-warning" style="margin-bottom: 20px; font-size: 0.85rem; padding: 10px 14px;">
                    <i data-lucide="lock" style="width: 16px; height: 16px; flex-shrink: 0;"></i>
                    <span>Hasil seleksi sudah dikunci. Mengubah data pengajuan tidak akan memengaruhi ranking yang sudah diumumkan.</span>
                </div>
            @endif
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 0.95rem; border-radius: 10px;">
                Simpan & Perbarui Pengajuan
                <i data-lucide="save" style="width: 18px; height: 18px;"></i>
            </button>
        </div>

    </form>
</div>

@endsection
