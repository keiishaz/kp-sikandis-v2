@extends('layouts.admin')

@section('title', 'Edit Pegawai - SIKANDIS')
@section('topbar_title', 'Master Data')

@section('content')
<div class="form-container">

    {{-- BREADCRUMB & HEADER --}}
    <div style="margin-bottom: 24px;">
        <nav aria-label="breadcrumb" style="margin-bottom: 12px; font-size: 13.5px;">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px;">
                <li>
                    <a href="{{ route('admin.pegawai.index') }}" style="color: var(--n-500); text-decoration: none; font-weight: 500;">Kelola Pegawai</a>
                </li>
                <li style="color: var(--n-400);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </li>
                <li style="color: var(--n-900); font-weight: 600;" aria-current="page">{{ $pegawai->nama }}</li>
            </ol>
        </nav>
        <h2 class="page-heading">Ubah Data Pegawai</h2>
    </div>

    {{-- FORM CARD --}}
    <div class="card form-page">
        <form method="POST" action="{{ route('admin.pegawai.update', $pegawai) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="card-body" style="display: flex; flex-direction: column; gap: 20px;">
                
                <div class="form-group">
                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $pegawai->nama) }}" class="form-input">
                    @error('nama')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="nip" class="form-label">
                        NIP <span class="text-danger">*</span>
                        <span style="font-size:11px; color:var(--n-400); font-weight:400; margin-left: 4px;">(18 digit angka)</span>
                    </label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip', $pegawai->nip) }}" class="form-input" style="font-family: monospace; letter-spacing: .5px;">
                    @error('nip')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan', $pegawai->jabatan) }}" class="form-input">
                    @error('jabatan')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="unit_id" class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                        <select id="unit_id" name="unit_id" class="form-select">
                            <option value="">-- Pilih Unit --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id', $pegawai->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama_unit }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="sub_unit_id" class="form-label">Sub Unit <span class="text-danger">*</span></label>
                        <select id="sub_unit_id" name="sub_unit_id" class="form-select">
                            <option value="">-- Pilih Sub Unit --</option>
                            @foreach($subUnits as $su)
                                <option value="{{ $su->id }}" {{ old('sub_unit_id', $pegawai->sub_unit_id) == $su->id ? 'selected' : '' }}>
                                    {{ $su->nama_sub_unit }}
                                </option>
                            @endforeach
                        </select>
                        @error('sub_unit_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

            </div>

            <div class="card-footer" style="padding: 20px 24px; border-top: 1px solid var(--n-200); background: var(--surface-page); display: flex; justify-content: flex-end; gap: 12px; border-bottom-left-radius: var(--r-xl); border-bottom-right-radius: var(--r-xl);">
                <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const apiBase    = '{{ rtrim(route("admin.api.units.sub-units", ["unit" => "__UNIT__"]), "") }}';
    const unitSel    = document.getElementById('unit_id');
    const subSel     = document.getElementById('sub_unit_id');
    const currentSub = '{{ old("sub_unit_id", $pegawai->sub_unit_id) }}';

    function loadSubUnits(unitId, selectedId) {
        subSel.innerHTML = '<option value="">-- Pilih Sub Unit --</option>';
        if (!unitId) return;
        fetch(apiBase.replace('__UNIT__', unitId), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                data.forEach(function (su) {
                    const opt = document.createElement('option');
                    opt.value = su.id;
                    opt.textContent = su.nama_sub_unit;
                    if (String(su.id) === String(selectedId)) opt.selected = true;
                    subSel.appendChild(opt);
                });
            });
    }

    unitSel.addEventListener('change', function () { loadSubUnits(this.value, ''); });

    const initialUnit = unitSel.value;
    if (initialUnit) { loadSubUnits(initialUnit, currentSub); }
});
</script>
@endsection
