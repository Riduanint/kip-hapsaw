@extends('layouts.app')

@section('title', 'Manajemen Kriteria')
@section('header_title', 'Kriteria Penilaian')

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; align-items: start;">
    <!-- Criteria List Panel -->
    <div class="panel">
        <div class="panel-title">
            <span>Daftar Kriteria</span>
            <i data-lucide="list-checks" style="color: var(--primary); width: 20px; height: 20px;"></i>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Kode</th>
                        <th>Nama Kriteria</th>
                        <th style="width: 120px; text-align: center;">Tipe</th>
                        <th style="width: 140px; text-align: right;">Bobot AHP</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criteria as $c)
                        <tr>
                            <td style="font-weight: 700; color: var(--primary);">{{ $c->code }}</td>
                            <td style="font-weight: 500;">{{ $c->name }}</td>
                            <td style="text-align: center;">
                                @if($c->type === 'benefit')
                                    <span class="badge badge-success" style="font-size: 0.75rem;">Benefit</span>
                                @else
                                    <span class="badge badge-danger" style="font-size: 0.75rem;">Cost</span>
                                @endif
                            </td>
                            <td style="text-align: right; font-family: var(--font-heading); font-weight: 600; color: #fff;">
                                {{ number_format($c->weight, 4) }}
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; justify-content: center; gap: 8px;">
                                    <button class="btn btn-secondary btn-sm edit-btn" 
                                            style="padding: 6px 10px; font-size: 0.8rem;"
                                            data-id="{{ $c->id }}"
                                            data-code="{{ $c->code }}"
                                            data-name="{{ $c->name }}"
                                            data-type="{{ $c->type }}">
                                        <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                                    </button>
                                    <form action="{{ route('admin.criteria.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kriteria ini?')">
                                        @csrf
                                        <input type="hidden" name="sk" value="{{ $sk }}">
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="padding: 6px 10px; font-size: 0.8rem; background: var(--danger-bg); color: var(--danger);">
                                            <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actions Panel (Add/Edit Form) -->
    <div class="panel" id="form-panel">
        <div class="panel-title">
            <span id="form-title">Tambah Kriteria Baru</span>
            <i data-lucide="plus-circle" style="color: var(--primary); width: 20px; height: 20px;"></i>
        </div>

        <form id="criteria-form" action="{{ route('admin.criteria.store') }}" method="POST">
            @csrf
            <input type="hidden" name="sk" value="{{ $sk }}">
            <input type="hidden" id="method-field" name="_method" value="POST" disabled>
            
            <div class="form-group">
                <label for="code">Kode Kriteria</label>
                <input type="text" id="code" name="code" class="form-control" placeholder="Contoh: C6" required>
            </div>

            <div class="form-group">
                <label for="name">Nama Kriteria</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Contoh: Indeks Prestasi" required>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="type">Tipe Kriteria</label>
                <select id="type" name="type" class="form-control form-select" required>
                    <option value="benefit">Benefit (Lebih tinggi, lebih baik)</option>
                    <option value="cost">Cost (Lebih rendah, lebih baik)</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex-grow: 1;">
                    <span id="btn-text">Simpan Kriteria</span>
                    <i data-lucide="save" style="width: 16px; height: 16px;"></i>
                </button>
                <button type="button" id="cancel-btn" class="btn btn-secondary" style="display: none;">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-btn');
        const formPanel = document.getElementById('form-panel');
        const formTitle = document.getElementById('form-title');
        const criteriaForm = document.getElementById('criteria-form');
        const methodField = document.getElementById('method-field');
        const codeInput = document.getElementById('code');
        const nameInput = document.getElementById('name');
        const typeInput = document.getElementById('type');
        const btnText = document.getElementById('btn-text');
        const cancelBtn = document.getElementById('cancel-btn');

        // Original action URL for reset
        const originalAction = criteriaForm.action;

        editButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const code = this.getAttribute('data-code');
                const name = this.getAttribute('data-name');
                const type = this.getAttribute('data-type');

                formTitle.textContent = 'Edit Kriteria: ' + code;
                codeInput.value = code;
                nameInput.value = name;
                typeInput.value = type;
                btnText.textContent = 'Perbarui Kriteria';
                
                // Change action route to update route
                criteriaForm.action = originalAction + '/' + id;
                methodField.removeAttribute('disabled');
                methodField.value = 'PUT';
                
                cancelBtn.style.display = 'inline-flex';
                codeInput.focus();
            });
        });

        cancelBtn.addEventListener('click', function () {
            formTitle.textContent = 'Tambah Kriteria Baru';
            criteriaForm.reset();
            criteriaForm.action = originalAction;
            methodField.setAttribute('disabled', 'true');
            btnText.textContent = 'Simpan Kriteria';
            cancelBtn.style.display = 'none';
        });
    });
</script>
@endsection
