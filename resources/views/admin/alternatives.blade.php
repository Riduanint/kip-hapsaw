@extends('layouts.app')

@section('title', 'Manajemen Pendaftar')
@section('header_title', 'Data Alternatif / Pendaftar')

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; align-items: start;">
    <!-- Alternatives Table -->
    <div class="panel">
        <div class="panel-title">
            <span>Daftar Alternatif (Pendaftar KIP)</span>
            <i data-lucide="users" style="color: var(--primary); width: 20px; height: 20px;"></i>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 140px;">NISN</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatives as $alt)
                        <tr>
                            <td style="font-family: var(--font-heading); font-weight: 600; color: var(--primary);">
                                {{ $alt->nisn }}
                            </td>
                            <td style="font-weight: 500;">{{ $alt->name }}</td>
                            <td>{{ $alt->email ?? '-' }}</td>
                            <td>{{ $alt->phone ?? '-' }}</td>
                            <td style="text-align: center;">
                                <div style="display: flex; justify-content: center; gap: 8px;">
                                    <button class="btn btn-secondary btn-sm edit-btn"
                                            style="padding: 6px 10px; font-size: 0.8rem;"
                                            data-id="{{ $alt->id }}"
                                            data-name="{{ $alt->name }}"
                                            data-nisn="{{ $alt->nisn }}"
                                            data-email="{{ $alt->email }}"
                                            data-phone="{{ $alt->phone }}">
                                        <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                                    </button>
                                    <form action="{{ url('/admin/alternatives/' . $alt->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alternatif ini?')">
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
            <span id="form-title">Tambah Alternatif</span>
            <i data-lucide="user-plus" style="color: var(--primary); width: 20px; height: 20px;"></i>
        </div>

        <form id="alt-form" action="{{ route('admin.alternatives.store') }}" method="POST">
            @csrf
            <input type="hidden" name="sk" value="{{ $sk }}">
            <input type="hidden" id="method-field" name="_method" value="POST" disabled>

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="form-group">
                <label for="nisn">Nomor Induk Siswa Nasional (NISN)</label>
                <input type="text" id="nisn" name="nisn" class="form-control" placeholder="10 digit NISN" required>
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="nama@email.com">
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="phone">Nomor Telepon / WA</label>
                <input type="text" id="phone" name="phone" class="form-control" placeholder="Contoh: 081234567890">
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex-grow: 1;">
                    <span id="btn-text">Simpan Alternatif</span>
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
        const altForm = document.getElementById('alt-form');
        const methodField = document.getElementById('method-field');
        const nameInput = document.getElementById('name');
        const nisnInput = document.getElementById('nisn');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const btnText = document.getElementById('btn-text');
        const cancelBtn = document.getElementById('cancel-btn');

        const originalAction = altForm.action;

        editButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const nisn = this.getAttribute('data-nisn');
                const email = this.getAttribute('data-email');
                const phone = this.getAttribute('data-phone');

                formTitle.textContent = 'Edit Alternatif';
                nameInput.value = name;
                nisnInput.value = nisn;
                emailInput.value = email || '';
                phoneInput.value = phone || '';
                btnText.textContent = 'Perbarui Alternatif';

                altForm.action = originalAction + '/' + id;
                methodField.removeAttribute('disabled');
                methodField.value = 'PUT';

                cancelBtn.style.display = 'inline-flex';
                nameInput.focus();
            });
        });

        cancelBtn.addEventListener('click', function () {
            formTitle.textContent = 'Tambah Alternatif';
            altForm.reset();
            altForm.action = originalAction;
            methodField.setAttribute('disabled', 'true');
            btnText.textContent = 'Simpan Alternatif';
            cancelBtn.style.display = 'none';
        });
    });
</script>
@endsection
