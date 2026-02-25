@extends('layouts.admin')

@section('title', 'Tambah Pegawai')
@section('topbar_title', 'Kelola Pegawai')

@section('content')
    <section class="form-container">
        <div class="card">

            <nav style="font-size:13px;color:#94a3b8;margin-bottom:20px;display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                <a href="{{ route('admin.pegawai.index') }}" style="color:#3b82f6;text-decoration:none;font-weight:500;">Kelola Pegawai</a>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                <span style="color:#334155;">Tambah Pegawai</span>
            </nav>

            <h3 style="margin:0 0 20px;font-size:16px;font-weight:600;color:#0f172a;">Form Tambah Pegawai</h3>

            <form method="POST" action="{{ route('admin.pegawai.store') }}" novalidate>
                @csrf

                <div class="form-field">
                    <label for="nama" style="display:block;margin-bottom:6px;font-size:13px;font-weight:500;color:#475569;">Nama Lengkap</label>
                    <input id="nama" name="nama" value="{{ old('nama') }}" style="width:100%;box-sizing:border-box;"
                           placeholder="Contoh: Budi Santoso">
                    @error('nama')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label for="nip" style="display:block;margin-bottom:6px;font-size:13px;font-weight:500;color:#475569;">
                        NIP <span style="font-size:11px;color:#94a3b8;font-weight:400;">(18 digit angka)</span>
                    </label>
                    <input id="nip" name="nip" value="{{ old('nip') }}" style="width:100%;box-sizing:border-box;font-family:monospace;letter-spacing:.5px;"
                           placeholder="000000000000000000">
                    @error('nip')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label for="jabatan" style="display:block;margin-bottom:6px;font-size:13px;font-weight:500;color:#475569;">Jabatan</label>
                    <input id="jabatan" name="jabatan" value="{{ old('jabatan') }}" style="width:100%;box-sizing:border-box;"
                           placeholder="Contoh: Kepala Seksi">
                    @error('jabatan')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label for="unit_id" style="display:block;margin-bottom:6px;font-size:13px;font-weight:500;color:#475569;">Unit</label>
                    <select id="unit_id" name="unit_id" style="width:100%;box-sizing:border-box;">
                        <option value="">-- Pilih Unit --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama_unit }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label for="sub_unit_id" style="display:block;margin-bottom:6px;font-size:13px;font-weight:500;color:#475569;">Sub Unit</label>
                    <select id="sub_unit_id" name="sub_unit_id" style="width:100%;box-sizing:border-box;">
                        <option value="">-- Pilih Sub Unit --</option>
                    </select>
                    @error('sub_unit_id')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:24px;">
                    <a class="btn" href="{{ route('admin.pegawai.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </section>

    <script>
    (function () {
        const apiBase = '{{ rtrim(route("admin.api.units.sub-units", ["unit" => "__UNIT__"]), "") }}';
        const unitSel = document.getElementById('unit_id');
        const subSel  = document.getElementById('sub_unit_id');
        const oldUnit = '{{ old("unit_id") }}';
        const oldSub  = '{{ old("sub_unit_id") }}';

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

        if (oldUnit) { loadSubUnits(oldUnit, oldSub); }
    })();
    </script>
@endsection
