@extends('layouts.app')

@section('title', 'Tambah Operator - SIKANDIS')
@section('topbar_title', 'Kelola Operator')

@section('content')
<div class="form-container">

    {{-- BREADCRUMB & HEADER --}}
    <div style="margin-bottom: 24px;">
        <nav aria-label="breadcrumb" style="margin-bottom: 12px; font-size: 13.5px;">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px;">
                <li>
                    <a href="{{ route('kelola-operator.index') }}" style="color: var(--n-500); text-decoration: none; font-weight: 500;">Kelola Operator</a>
                </li>
                <li style="color: var(--n-400);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </li>
                <li style="color: var(--n-900); font-weight: 600;" aria-current="page">Tambah Operator</li>
            </ol>
        </nav>
        <h2 class="page-heading">Form Tambah Operator</h2>
    </div>

    {{-- FORM CARD --}}
    <div class="card form-page">
        <form method="POST" action="{{ route('kelola-operator.store') }}" novalidate>
            @csrf

            <div class="card-body" style="display: flex; flex-direction: column; gap: 20px;">
                <div class="form-group">
                    <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input" autofocus>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik') }}" class="form-input" style="font-family: monospace; letter-spacing: .5px;" required pattern="\d{16}" minlength="16" maxlength="16" placeholder="16 digit angka">
                    @error('nik')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="nip" class="form-label">NIP (Nomor Induk Pegawai) <span style="color:var(--n-400); font-weight: 400; font-size: 12px; margin-left: 4px;">(Opsional)</span></label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip') }}" class="form-input" style="font-family: monospace; letter-spacing: .5px;" placeholder="Opsional">
                    @error('nip')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="unit_id" class="form-label">Unit / OPD <span class="text-danger">*</span></label>
                    <select id="unit_id" name="unit_id" class="form-select {{ $errors->has('unit_id') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Unit / OPD --</option>
                        <optgroup label="Unit Internal (Pemerintah)">
                            @foreach($units->where('type', 'internal') as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Unit Eksternal">
                            @foreach($units->where('type', 'external') as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                    @error('unit_id')<div class="form-error">{{ $message }}</div>@enderror
                    <div style="font-size: 11.5px; color: var(--n-400); margin-top: 6px;">Tentukan unit/OPD tempat operator ini bertanggung jawab. Operator hanya bisa melihat kendaraan milik unit ini.</div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" id="password" name="password" class="form-input">
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card-footer" style="padding: 20px 24px; border-top: 1px solid var(--n-200); background: var(--surface-page); display: flex; justify-content: flex-end; gap: 12px; border-bottom-left-radius: var(--r-xl); border-bottom-right-radius: var(--r-xl);">
                <a href="{{ route('kelola-operator.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Operator</button>
            </div>
        </form>
    </div>

</div>
@endsection
