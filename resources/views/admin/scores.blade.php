@extends('layouts.app')

@section('title', 'Input Nilai Alternatif')
@section('header_title', 'Pengisian Nilai Kriteria Alternatif')

@section('content')
<!-- Informational Alert -->
<div class="panel">
    <div class="panel-title">
        <span>Informasi Pengisian Parameter</span>
        <i data-lucide="info" style="color: var(--primary); width: 20px; height: 20px;"></i>
    </div>
    <div style="line-height: 1.6; font-size: 0.9rem; color: var(--text-muted);">
        <p>Silakan isi nilai untuk masing-masing pendaftar sesuai dengan kriteria yang ditentukan:</p>
        <ul style="margin-left: 20px; margin-top: 10px; display: flex; flex-direction: column; gap: 6px;">
            <li><strong>C1 (Penghasilan Orang Tua):</strong> Diisi nilai rupiah nominal penghasilan (Cost - semakin kecil nominal, semakin prioritas).</li>
            <li><strong>C2 (Rata-rata Rapor):</strong> Diisi nilai akademis rata-rata rapor skala 0-100 (Benefit - semakin tinggi, semakin baik).</li>
            <li><strong>C3 (Jumlah Tanggungan):</strong> Diisi jumlah tanggungan orang tua (Benefit - semakin banyak, semakin baik).</li>
            <li><strong>C4 (Kondisi Rumah):</strong> Diisi skala kondisi rumah 1-5 (Cost - 1: Sangat Sederhana/Rusak, 5: Sangat Layak/Mewah. Semakin rendah nilai, semakin prioritas).</li>
            <li><strong>C5 (Prestasi Tambahan):</strong> Diisi nilai prestasi skala 1-5 (Benefit - 1: Tidak Ada Prestasi, 5: Prestasi Tingkat Nasional).</li>
        </ul>
    </div>
</div>

<!-- Scores Form Grid -->
<div class="panel">
    <div class="panel-title">
        <span>Matriks Penilaian Alternatif</span>
        <i data-lucide="clipboard-list" style="color: var(--primary); width: 20px; height: 20px;"></i>
    </div>

    @if($alternatives->isEmpty() || $criteria->isEmpty())
        <div style="text-align: center; padding: 40px; color: var(--text-muted);">
            <i data-lucide="alert-circle" style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.5;"></i>
            <p>Silakan isi data <strong>Kriteria</strong> dan data <strong>Pendaftar</strong> terlebih dahulu sebelum melakukan pengisian nilai.</p>
        </div>
    @else
        <form action="{{ route('admin.scores.update') }}" method="POST">
            @csrf
            <input type="hidden" name="sk" value="{{ $sk }}">
            <div class="table-responsive" style="margin-bottom: 25px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Alternatif</th>
                            @foreach($criteria as $c)
                                <th style="text-align: center; width: 160px;">
                                    {{ $c->code }}
                                    <span style="display: block; font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">
                                        ({{ $c->type == 'benefit' ? 'Benefit' : 'Cost' }})
                                    </span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatives as $alt)
                            @php
                                // Map current scores for this alternative
                                $scoresMap = [];
                                foreach($alt->criteria as $c) {
                                    $scoresMap[$c->id] = $c->pivot->value;
                                }
                            @endphp
                            <tr>
                                <td style="font-weight: 600; color: #fff;">
                                    {{ $alt->name }}
                                    <span style="display: block; font-size: 0.8rem; color: var(--text-muted); font-weight: normal;">
                                        NISN: {{ $alt->nisn }}
                                    </span>
                                </td>
                                @foreach($criteria as $c)
                                    <td>
                                        <input type="number" 
                                               step="any"
                                               name="scores[{{ $alt->id }}][{{ $c->id }}]" 
                                               class="form-control"
                                               style="text-align: center; padding: 8px;"
                                               value="{{ $scoresMap[$c->id] ?? 0.0 }}"
                                               required>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan Semua Nilai Alternatif
                <i data-lucide="save" style="width: 18px; height: 18px;"></i>
            </button>
        </form>
    @endif
</div>
@endsection
