@extends('layouts.app')

@section('title', 'Tambah Kendaraan ' . (auth()->user()->role->nama_role === 'admin' ? 'Admin' : 'Operator') . ' — SIKANDIS')
@section('topbar_title', 'Data Kendaraan ' . (auth()->user()->role->nama_role === 'admin' ? 'Admin' : 'Operator'))

@section('content')
<div class="form-container">
    {{-- BREADCRUMB --}}
    <nav aria-label="breadcrumb" style="margin-bottom: 24px; font-size: 13.5px;">
        <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px;">
            <li>
                <a href="{{ route('kendaraan.index') }}" style="color: var(--n-500); text-decoration: none; font-weight: 500;">Kendaraan</a>
            </li>
            <li style="color: var(--n-400);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </li>
            <li style="color: var(--n-900); font-weight: 600;" aria-current="page">Tambah Kendaraan</li>
        </ol>
    </nav>

    {{-- PAGE HEADING --}}
    <div class="page-intro" style="margin-bottom: 24px;">
        <h2 class="page-heading">Form Tambah Kendaraan</h2>
    </div>

    {{-- FORM CARD --}}
    <div class="card form-page">
        <form action="{{ route('kendaraan.store') }}" method="POST" novalidate>
            @csrf
            
            <div class="card-body">
                {{-- SECTION 1 — Identitas Kendaraan --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-section-number">01</div>
                        <div>
                            <h4 class="form-section-title">Identitas Kendaraan</h4>
                            <p class="form-section-desc">Informasi dasar tentang kendaraan</p>
                        </div>
                    </div>
                    
                    <div class="form-section-body">
                        <div class="form-grid-2">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label for="nama_kendaraan" class="form-label">Nama Kendaraan <span class="text-danger">*</span></label>
                                <input type="text" id="nama_kendaraan" name="nama_kendaraan" class="form-input {{ $errors->has('nama_kendaraan') ? 'is-invalid' : '' }}" value="{{ old('nama_kendaraan') }}" placeholder="Contoh: Toyota Avanza Veloz" autofocus>
                                @error('nama_kendaraan')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nomor Polisi <span class="text-danger">*</span></label>
                                <div style="display: flex; gap: 12px;">
                                    <div style="flex: 1;">
                                        <input type="text" name="nopol_1" class="form-input" style="text-transform: uppercase; text-align: center;" value="{{ old('nopol_1') }}" placeholder="BD" maxlength="2">
                                    </div>
                                    <div style="flex: 2;">
                                        <input type="text" name="nopol_2" class="form-input" style="text-align: center;" value="{{ old('nopol_2') }}" placeholder="1234" maxlength="4">
                                    </div>
                                    <div style="flex: 1.5;">
                                        <input type="text" name="nopol_3" class="form-input" style="text-transform: uppercase; text-align: center;" value="{{ old('nopol_3') }}" placeholder="XX" maxlength="3">
                                    </div>
                                </div>
                                @if($errors->has('nopol_1') || $errors->has('nopol_2') || $errors->has('nopol_3') || $errors->has('no_polisi'))
                                    <div class="form-error">
                                        {{ $errors->first('nopol_1') ?: ($errors->first('nopol_2') ?: ($errors->first('nopol_3') ?: $errors->first('no_polisi'))) }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="form-group">
                                <label for="tahun" class="form-label">Tahun Pembuatan <span class="text-danger">*</span></label>
                                <select id="tahun" name="tahun" class="form-select {{ $errors->has('tahun') ? 'is-invalid' : '' }}">
                                    <option value="">-- Pilih Tahun --</option>
                                    @php $currentYear = date('Y'); @endphp
                                    @for($i = $currentYear; $i >= 1990; $i--)
                                        <option value="{{ $i }}" {{ old('tahun', $currentYear) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('tahun')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select id="kategori_id" name="kategori_id" class="form-select {{ $errors->has('kategori_id') ? 'is-invalid' : '' }}">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoris as $kat)
                                        <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section-divider"></div>

                {{-- SECTION 2 — Spesifikasi Mesin & Pajak --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-section-number">02</div>
                        <div>
                            <h4 class="form-section-title">Spesifikasi Mesin & Pajak</h4>
                            <p class="form-section-desc">Nomor unik mesin kendaraan & batas akhir pajak</p>
                        </div>
                    </div>
                    
                    <div class="form-section-body">
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="no_rangka" class="form-label">Nomor Rangka <span class="text-danger">*</span></label>
                                <input type="text" id="no_rangka" name="no_rangka" class="form-input {{ $errors->has('no_rangka') ? 'is-invalid' : '' }}" style="text-transform: uppercase;" value="{{ old('no_rangka') }}" placeholder="17 Digit No Rangka">
                                @error('no_rangka')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="no_mesin" class="form-label">Nomor Mesin <span class="text-danger">*</span></label>
                                <input type="text" id="no_mesin" name="no_mesin" class="form-input {{ $errors->has('no_mesin') ? 'is-invalid' : '' }}" style="text-transform: uppercase;" value="{{ old('no_mesin') }}" placeholder="Nomor Mesin">
                                @error('no_mesin')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group" style="grid-column: 1">
                                <label for="pajak" class="form-label">Tanggal Jatuh Tempo Pajak <span class="text-danger">*</span></label>
                                <input type="date" id="pajak" name="pajak" class="form-input {{ $errors->has('pajak') ? 'is-invalid' : '' }}" value="{{ old('pajak') }}">
                                <div class="form-hint" style="font-size: 11.5px; color: var(--n-400); margin-top: 6px;">Sesuai dengan masa berlaku STNK/Pajak tahunan kendaraan.</div>
                                @error('pajak')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section-divider"></div>

                {{-- SECTION 3 — Penggunaan --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-section-number">03</div>
                        <div>
                            <h4 class="form-section-title">Informasi Penggunaan</h4>
                            <p class="form-section-desc">Jabatan pemegang atau keliling operasional</p>
                        </div>
                    </div>
                    
                    <div class="form-section-body">
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="jenis_penggunaan_select" class="form-label">Jenis Penggunaan <span class="text-danger">*</span></label>
                                <select id="jenis_penggunaan_select" name="jenis_penggunaan" class="form-select {{ $errors->has('jenis_penggunaan') ? 'is-invalid' : '' }}">
                                    <option value="jabatan" {{ old('jenis_penggunaan') == 'jabatan' ? 'selected' : '' }}>Jabatan</option>
                                    <option value="operasional" {{ old('jenis_penggunaan') == 'operasional' ? 'selected' : '' }}>Operasional</option>
                                </select>
                                @error('jenis_penggunaan')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group" id="lokasi_operasional_group" style="{{ old('jenis_penggunaan') == 'operasional' ? '' : 'display:none;' }}">
                                <label for="lokasi_operasional_input" class="form-label">Lokasi Operasional <span class="text-danger">*</span></label>
                                <input type="text" id="lokasi_operasional_input" name="lokasi_operasional" class="form-input {{ $errors->has('lokasi_operasional') ? 'is-invalid' : '' }}" value="{{ old('lokasi_operasional') }}" placeholder="Area Keliling / Nama Unit">
                                <div class="form-help">Wajib diisi jika jenis penggunaan operasional.</div>
                                @error('lokasi_operasional')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions" style="padding: 24px; border-top: 1px solid var(--n-200); background: var(--surface-page); display: flex; justify-content: flex-end; gap: 12px; border-bottom-left-radius: var(--r-lg); border-bottom-right-radius: var(--r-lg);">
                <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kendaraan</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jenisSelect = document.getElementById('jenis_penggunaan_select');
        const lokasiGroup = document.getElementById('lokasi_operasional_group');
        const lokasiInput = document.getElementById('lokasi_operasional_input');

        function toggleLokasi() {
            if (jenisSelect.value === 'operasional') {
                lokasiGroup.style.display = 'block';
            } else {
                lokasiGroup.style.display = 'none';
                lokasiInput.value = ''; // Clean up visual hide
            }
        }

        jenisSelect.addEventListener('change', toggleLokasi);
        toggleLokasi(); // Run initial state
    });
</script>
@endsection
