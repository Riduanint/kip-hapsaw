@extends('layouts.app')

@section('title', 'Perbandingan Kriteria AHP')
@section('header_title', 'Perbandingan Berpasangan AHP')

@section('content')
<!-- Informational Alert -->
<div class="panel">
    <div class="panel-title">
        <span>Petunjuk Skala Perbandingan (Saaty)</span>
        <i data-lucide="info" style="color: var(--primary); width: 20px; height: 20px;"></i>
    </div>
    <div style="line-height: 1.5; font-size: 0.9rem; color: var(--text-muted); display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <ul style="margin-left: 20px;">
            <li><strong>1:</strong> Kedua kriteria sama penting.</li>
            <li><strong>3:</strong> Kriteria A sedikit lebih penting dari Kriteria B.</li>
            <li><strong>5:</strong> Kriteria A lebih penting dari Kriteria B.</li>
        </ul>
        <ul style="margin-left: 20px;">
            <li><strong>7:</strong> Kriteria A sangat lebih penting dari Kriteria B.</li>
            <li><strong>9:</strong> Kriteria A mutlak lebih penting dari Kriteria B.</li>
            <li><strong>2, 4, 6, 8:</strong> Nilai antara / kompromi.</li>
        </ul>
    </div>
</div>

<!-- Comparison Matrix Input -->
<div class="panel">
    <div class="panel-title">
        <span>Input Matriks Perbandingan Berpasangan</span>
        <i data-lucide="grid" style="color: var(--primary); width: 20px; height: 20px;"></i>
    </div>

    <form action="{{ route('admin.ahp.update') }}" method="POST">
        @csrf
        <input type="hidden" name="sk" value="{{ $sk }}">
        <div class="table-responsive" style="margin-bottom: 20px;">
            <table class="table" style="text-align: center;">
                <thead>
                    <tr>
                        <th style="width: 150px; text-align: left;">Kriteria</th>
                        @foreach($criteria as $c)
                            <th style="text-align: center;">{{ $c->code }} - {{ $c->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($criteria as $c1)
                        <tr>
                            <td style="text-align: left; font-weight: 700; color: var(--primary);">
                                {{ $c1->code }}
                            </td>
                            @foreach($criteria as $c2)
                                <td>
                                    @if($c1->id == $c2->id)
                                        <!-- Self comparison is always 1 -->
                                        <span style="font-weight: 600; color: var(--text-dim);">1.00</span>
                                        <input type="hidden" name="matrix[{{ $c1->id }}][{{ $c2->id }}]" value="1">
                                    @elseif($c1->id < $c2->id)
                                        <!-- User Input for Upper Triangle -->
                                        <select name="matrix[{{ $c1->id }}][{{ $c2->id }}]" 
                                                class="matrix-select"
                                                id="cell-{{ $c1->id }}-{{ $c2->id }}"
                                                onchange="updateReciprocal({{ $c1->id }}, {{ $c2->id }}, this.value)">
                                            @php
                                                $currVal = $comparisons[$c1->id][$c2->id] ?? 1.0;
                                            @endphp
                                            <option value="1" {{ abs($currVal - 1) < 0.01 ? 'selected' : '' }}>1</option>
                                            
                                            <!-- Standard AHP scale (1 to 9) -->
                                            <option value="2" {{ abs($currVal - 2) < 0.01 ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ abs($currVal - 3) < 0.01 ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ abs($currVal - 4) < 0.01 ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ abs($currVal - 5) < 0.01 ? 'selected' : '' }}>5</option>
                                            <option value="6" {{ abs($currVal - 6) < 0.01 ? 'selected' : '' }}>6</option>
                                            <option value="7" {{ abs($currVal - 7) < 0.01 ? 'selected' : '' }}>7</option>
                                            <option value="8" {{ abs($currVal - 8) < 0.01 ? 'selected' : '' }}>8</option>
                                            <option value="9" {{ abs($currVal - 9) < 0.01 ? 'selected' : '' }}>9</option>
                                            
                                            <!-- Fractional values (1/2 to 1/9) -->
                                            <option value="0.5" {{ abs($currVal - 0.5) < 0.01 ? 'selected' : '' }}>1/2</option>
                                            <option value="0.3333" {{ abs($currVal - 0.3333) < 0.05 ? 'selected' : '' }}>1/3</option>
                                            <option value="0.25" {{ abs($currVal - 0.25) < 0.01 ? 'selected' : '' }}>1/4</option>
                                            <option value="0.2" {{ abs($currVal - 0.2) < 0.01 ? 'selected' : '' }}>1/5</option>
                                            <option value="0.1667" {{ abs($currVal - 0.1667) < 0.01 ? 'selected' : '' }}>1/6</option>
                                            <option value="0.1428" {{ abs($currVal - 0.1428) < 0.01 ? 'selected' : '' }}>1/7</option>
                                            <option value="0.125" {{ abs($currVal - 0.125) < 0.01 ? 'selected' : '' }}>1/8</option>
                                            <option value="0.1111" {{ abs($currVal - 0.1111) < 0.01 ? 'selected' : '' }}>1/9</option>
                                        </select>
                                    @else
                                        <!-- Display Reciprocal (Calculated automatically by JS) -->
                                        @php
                                            $recipVal = $comparisons[$c1->id][$c2->id] ?? 1.0;
                                        @endphp
                                        <span id="recip-{{ $c1->id }}-{{ $c2->id }}" style="font-weight: 500; color: var(--text-muted);">
                                            {{ $recipVal >= 1 ? number_format($recipVal, 0) : '1/' . round(1 / $recipVal) }}
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-primary">
            Simpan Perbandingan & Hitung Bobot
            <i data-lucide="calculator" style="width: 18px; height: 18px;"></i>
        </button>
    </form>
</div>

<!-- AHP Calculations Outputs -->
@if(isset($ahpResult) && !empty($ahpResult['weights']))
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    <!-- Weights Panel -->
    <div class="panel">
        <div class="panel-title">
            <span>Bobot Prioritas Hasil Perhitungan</span>
            <i data-lucide="pie-chart" style="color: var(--primary); width: 20px; height: 20px;"></i>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Kode</th>
                        <th>Nama Kriteria</th>
                        <th style="text-align: right;">Bobot (Eigenvector)</th>
                        <th style="text-align: right;">Bobot (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criteria as $c)
                        @php
                            $w = $ahpResult['weights'][$c->id] ?? 0.0;
                        @endphp
                        <tr>
                            <td style="font-weight: 700; color: var(--primary);">{{ $c->code }}</td>
                            <td>{{ $c->name }}</td>
                            <td style="text-align: right; font-family: var(--font-heading); font-weight: 600; color: #fff;">
                                {{ number_format($w, 4) }}
                            </td>
                            <td style="text-align: right; font-weight: 500; color: var(--success);">
                                {{ number_format($w * 100, 2) }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Consistency Validation Panel -->
    <div class="panel">
        <div class="panel-title">
            <span>Uji Konsistensi Matriks</span>
            @if($ahpResult['is_consistent'])
                <span class="badge badge-success">KONSISTEN</span>
            @else
                <span class="badge badge-danger">TIDAK KONSISTEN</span>
            @endif
        </div>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-glass); padding-bottom: 10px;">
                <span style="color: var(--text-muted);">Maksimum Eigenvalue (&lambda;<sub>max</sub>)</span>
                <span style="font-family: var(--font-heading); font-weight: 600;">{{ number_format($ahpResult['lambda_max'], 4) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-glass); padding-bottom: 10px;">
                <span style="color: var(--text-muted);">Consistency Index (CI)</span>
                <span style="font-family: var(--font-heading); font-weight: 600;">{{ number_format($ahpResult['ci'], 4) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-glass); padding-bottom: 10px;">
                <span style="color: var(--text-muted);">Consistency Ratio (CR)</span>
                <span style="font-family: var(--font-heading); font-weight: 700; color: {{ $ahpResult['is_consistent'] ? 'var(--success)' : 'var(--danger)' }}">
                    {{ number_format($ahpResult['cr'], 4) }}
                </span>
            </div>
        </div>
        
        <div style="margin-top: 20px; font-size: 0.85rem; line-height: 1.5; color: var(--text-muted);">
            @if($ahpResult['is_consistent'])
                <p style="color: var(--success); font-weight: 500;">Matriks dinilai konsisten karena nilai CR &lt; 0.1. Nilai bobot di atas sah untuk digunakan dalam perhitungan perankingan SAW.</p>
            @else
                <p style="color: var(--danger); font-weight: 500;">Matriks dinilai TIDAK konsisten karena nilai CR &ge; 0.1. Silakan atur kembali nilai perbandingan berpasangan agar mencapai tingkat konsistensi yang ideal.</p>
            @endif
        </div>
    </div>
</div>
@endif

<script>
    function updateReciprocal(c1, c2, val) {
        // Find the matching reciprocal output label
        const cell = document.getElementById('recip-' + c2 + '-' + c1);
        if (cell) {
            const num = parseFloat(val);
            if (num >= 1) {
                cell.textContent = '1/' + Math.round(num);
            } else {
                cell.textContent = Math.round(1 / num);
            }
        }
    }
</script>
@endsection
