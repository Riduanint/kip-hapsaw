@extends('layouts.app')

@section('title', 'Perankingan SAW')
@section('header_title', 'Perhitungan & Perankingan SAW')

@section('content')

@if(!$sawResult)
    <div class="panel">
        <div style="text-align: center; padding: 40px; color: var(--text-muted);">
            <i data-lucide="alert-circle" style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.5;"></i>
            <p>Silakan isi kriteria dan nilai pendaftar terlebih dahulu sebelum melakukan perankingan.</p>
        </div>
    </div>
@else
    <!-- Lock Results / Set Quota Panel -->
    <div class="panel" style="border: 1px dashed var(--primary); background: rgba(99, 102, 241, 0.05);">
        <div class="panel-title" style="color: var(--primary);">
            <span>Kunci & Umumkan Hasil Seleksi</span>
            <i data-lucide="lock" style="width: 20px; height: 20px;"></i>
        </div>
        <div style="display: flex; align-items: flex-end; gap: 20px; flex-wrap: wrap;">
            <div style="flex-grow: 1; max-width: 400px;">
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 10px;">
                    Menyimpan ranking dan nilai akhir preferensi ke database, serta memperbarui status kelulusan berdasarkan kuota beasiswa KIP Kuliah.
                </p>
                <form action="{{ route('admin.saw.lock') }}" method="POST" style="display: flex; gap: 10px; align-items: center;">
                    @csrf
                    <input type="hidden" name="sk" value="{{ $sk }}">
                    <div style="width: 150px;">
                        <label for="quota" style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 5px;">Kuota Penerima</label>
                        <input type="number" id="quota" name="quota" min="1" class="form-control" value="2" required>
                    </div>
                    <div style="margin-top: 22px;">
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin menyimpan dan mengunci data perankingan ini?')">
                            Kunci Hasil Ranking
                            <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Step 1: Decision Matrix -->
    <div class="panel">
        <div class="panel-title">
            <span>Langkah 1: Matriks Keputusan Awal (X)</span>
            <span class="badge badge-primary">Matriks Input</span>
        </div>
        <div class="table-responsive">
            <table class="table" style="text-align: center;">
                <thead>
                    <tr>
                        <th style="text-align: left;">Alternatif / Pendaftar</th>
                        @foreach($criteria as $c)
                            <th style="text-align: center;">{{ $c->code }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternativesRaw as $alt)
                        <tr>
                            <td style="text-align: left; font-weight: 600; color: #fff;">{{ $alt->name }}</td>
                            @foreach($criteria as $c)
                                @php
                                    $score = $alt->criteria->firstWhere('id', $c->id)->pivot->value ?? 0;
                                @endphp
                                <td>{{ number_format($score, 2) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    <!-- Min Max Display Row -->
                    <tr style="background: rgba(255,255,255,0.03); border-top: 2px solid var(--border-glass);">
                        <td style="text-align: left; font-weight: bold; color: var(--text-muted);">Nilai Min Kriteria (Cost)</td>
                        @foreach($criteria as $c)
                            <td>
                                @if($c->type === 'cost')
                                    <strong style="color: var(--danger);">{{ number_format($sawResult['min_max'][$c->id]['min'], 2) }}</strong>
                                @else
                                    <span style="color: var(--text-dim);">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr style="background: rgba(255,255,255,0.03);">
                        <td style="text-align: left; font-weight: bold; color: var(--text-muted);">Nilai Max Kriteria (Benefit)</td>
                        @foreach($criteria as $c)
                            <td>
                                @if($c->type === 'benefit')
                                    <strong style="color: var(--success);">{{ number_format($sawResult['min_max'][$c->id]['max'], 2) }}</strong>
                                @else
                                    <span style="color: var(--text-dim);">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Step 2: Normalized Matrix -->
    <div class="panel">
        <div class="panel-title">
            <span>Langkah 2: Matriks Ternormalisasi (R)</span>
            <span class="badge badge-primary">Matriks R</span>
        </div>
        <div class="table-responsive">
            <table class="table" style="text-align: center;">
                <thead>
                    <tr>
                        <th style="text-align: left;">Alternatif / Pendaftar</th>
                        @foreach($criteria as $c)
                            <th style="text-align: center;">
                                {{ $c->code }}
                                <span style="display: block; font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">
                                    w = {{ number_format($c->weight, 4) }}
                                </span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($sawResult['ranked_results'] as $res)
                        <tr>
                            <td style="text-align: left; font-weight: 600; color: #fff;">{{ $res['name'] }}</td>
                            @foreach($criteria as $c)
                                <td>{{ number_format($res['normalized'][$c->id] ?? 0, 4) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Step 3: Rankings Table -->
    <div class="panel">
        <div class="panel-title">
            <span>Langkah 3: Nilai Preferensi (V) & Perankingan</span>
            <i data-lucide="award" style="color: var(--success); width: 20px; height: 20px;"></i>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px; text-align: center;">Rank</th>
                        <th>NISN</th>
                        <th>Nama Pendaftar</th>
                        <th>Perhitungan Preferensi (V)</th>
                        <th style="width: 200px; text-align: right;">Skor Akhir (V)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sawResult['ranked_results'] as $res)
                        <tr>
                            <td style="text-align: center; font-weight: 700; color: var(--primary); font-size: 1.1rem;">
                                #{{ $res['rank'] }}
                            </td>
                            <td style="font-family: var(--font-heading); font-weight: 500;">{{ $res['nisn'] }}</td>
                            <td style="font-weight: 600; color: #fff;">{{ $res['name'] }}</td>
                            <td style="font-size: 0.85rem; color: var(--text-muted); font-family: monospace;">
                                @php
                                    $parts = [];
                                    foreach($criteria as $c) {
                                        $norm = number_format($res['normalized'][$c->id] ?? 0, 3);
                                        $wt = number_format($c->weight, 3);
                                        $parts[] = "({$norm} &times; {$wt})";
                                    }
                                    echo implode(" + ", $parts);
                                @endphp
                            </td>
                            <td style="text-align: right; font-family: var(--font-heading); font-weight: 700; color: var(--success); font-size: 1.1rem;">
                                {{ number_format($res['final_score'], 5) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@endsection
