@extends('layouts.admin')

@section('title', 'Edit Kendaraan - SIKANDIS')
@section('topbar_title', 'Kendaraan')

@section('content')
<section class="form-container">
    <div class="card" style="padding: 28px;">
        <nav style="font-size:13px;color:#94a3b8;margin-bottom:24px;display:flex;align-items:center;gap:6px;">
            <a href="{{ route('admin.kendaraan.index') }}" style="color:#3b82f6;text-decoration:none;font-weight:500;">Kendaraan</a>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            <span style="color:#334155;">Edit Kendaraan</span>
        </nav>

        <h3 style="margin:0 0 28px;font-size:18px;font-weight:600;color:#0f172a;">Form Edit Kendaraan</h3>

        <form action="{{ route('admin.kendaraan.update', $kendaraan->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')
            
            <div class="form-group-section" style="margin-bottom: 32px; background: #f8fafc; padding: 28px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <h4 style="font-size: 15px; font-weight:600; color: var(--gray-700); margin-bottom: 24px; border-bottom: 1px solid var(--gray-200); padding-bottom: 12px; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--primary-color);"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Identitas Kendaraan
                </h4>
                
                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 28px;">
                    <div class="form-field">
                        <label for="nama_kendaraan" style="display:block;margin-bottom:8px;font-size:13px;font-weight:500;color:#475569;">Nama Kendaraan <span style="color:var(--danger-color);">*</span></label>
                        <input id="nama_kendaraan" name="nama_kendaraan" value="{{ old('nama_kendaraan', $kendaraan->nama_kendaraan) }}"
                               style="width:100%;box-sizing:border-box; padding:10px 14px; font-size:14px; border-radius:8px;" placeholder="Contoh: Toyota Avanza Veloz" autofocus>
                        @error('nama_kendaraan')<div class="error-text" style="color:#dc2626; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-field">
                        <label style="display:block;margin-bottom:8px;font-size:13px;font-weight:500;color:#475569;">Nomor Polisi <span style="color:var(--danger-color);">*</span></label>
                        @php
                            preg_match('/^([A-Z]{1,2})\s*(\d{1,4})\s*([A-Z]{0,3})$/i', $kendaraan->no_polisi, $matches);
                            $def_nopol_1 = $matches[1] ?? '';
                            $def_nopol_2 = $matches[2] ?? '';
                            $def_nopol_3 = $matches[3] ?? '';
                        @endphp
                        <div style="display: flex; gap: 12px;">
                            <div style="flex: 1;">
                                <input name="nopol_1" value="{{ old('nopol_1', $def_nopol_1) }}" style="width:100%;box-sizing:border-box;text-transform:uppercase;text-align:center; padding:10px; font-size:14px; border-radius:8px;" placeholder="BD" maxlength="2">
                            </div>
                            <div style="flex: 2;">
                                <input name="nopol_2" value="{{ old('nopol_2', $def_nopol_2) }}" style="width:100%;box-sizing:border-box;text-align:center; padding:10px; font-size:14px; border-radius:8px;" placeholder="1234" maxlength="4">
                            </div>
                            <div style="flex: 1.5;">
                                <input name="nopol_3" value="{{ old('nopol_3', $def_nopol_3) }}" style="width:100%;box-sizing:border-box;text-transform:uppercase;text-align:center; padding:10px; font-size:14px; border-radius:8px;" placeholder="XX" maxlength="3">
                            </div>
                        </div>
                        @if($errors->has('nopol_1') || $errors->has('nopol_2') || $errors->has('nopol_3') || $errors->has('no_polisi'))
                            <div class="error-text" style="color:#dc2626; font-size:12px; margin-top:6px;">
                                {{ $errors->first('nopol_1') ?: ($errors->first('nopol_2') ?: ($errors->first('nopol_3') ?: $errors->first('no_polisi'))) }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="form-field">
                        <label for="tahun" style="display:block;margin-bottom:8px;font-size:13px;font-weight:500;color:#475569;">Tahun Pembuatan <span style="color:var(--danger-color);">*</span></label>
                        <select id="tahun" name="tahun" style="width:100%;box-sizing:border-box; background:#fff; padding: 10px 14px; font-size:14px; border: 1px solid var(--gray-300); border-radius: 8px; outline:none;">
                            <option value="">-- Pilih Tahun --</option>
                            @php $currentYear = date('Y'); @endphp
                            @for($i = $currentYear; $i >= 1990; $i--)
                                <option value="{{ $i }}" {{ old('tahun', $kendaraan->tahun) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('tahun')<div class="error-text" style="color:#dc2626; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-field">
                        <label for="kategori_id" style="display:block;margin-bottom:8px;font-size:13px;font-weight:500;color:#475569;">Kategori <span style="color:var(--danger-color);">*</span></label>
                        <select id="kategori_id" name="kategori_id" style="width:100%;box-sizing:border-box; background:#fff; padding: 10px 14px; font-size:14px; border: 1px solid var(--gray-300); border-radius: 8px; outline:none;">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_id', $kendaraan->kategori_id) == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')<div class="error-text" style="color:#dc2626; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-group-section" style="margin-bottom: 32px; background: #f8fafc; padding: 28px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <h4 style="font-size: 15px; font-weight:600; color: var(--gray-700); margin-bottom: 24px; border-bottom: 1px solid var(--gray-200); padding-bottom: 12px; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--primary-color);"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    Spesifikasi Mesin & Pajak
                </h4>
                
                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 28px;">
                    <div class="form-field">
                        <label for="no_rangka" style="display:block;margin-bottom:8px;font-size:13px;font-weight:500;color:#475569;">Nomor Rangka <span style="color:var(--danger-color);">*</span></label>
                        <input id="no_rangka" name="no_rangka" value="{{ old('no_rangka', $kendaraan->no_rangka) }}" style="width:100%;box-sizing:border-box;text-transform:uppercase; padding:10px 14px; font-size:14px; border-radius:8px;" placeholder="17 Digit No Rangka">
                        @error('no_rangka')<div class="error-text" style="color:#dc2626; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-field">
                        <label for="no_mesin" style="display:block;margin-bottom:8px;font-size:13px;font-weight:500;color:#475569;">Nomor Mesin <span style="color:var(--danger-color);">*</span></label>
                        <input id="no_mesin" name="no_mesin" value="{{ old('no_mesin', $kendaraan->no_mesin) }}" style="width:100%;box-sizing:border-box;text-transform:uppercase; padding:10px 14px; font-size:14px; border-radius:8px;" placeholder="Nomor Mesin">
                        @error('no_mesin')<div class="error-text" style="color:#dc2626; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-field" style="grid-column: 1 / -1; max-width: 50%;">
                        <label for="pajak" style="display:block;margin-bottom:8px;font-size:13px;font-weight:500;color:#475569;">Tanggal Aktif Pajak <span style="color:var(--danger-color);">*</span></label>
                        <input type="date" id="pajak" name="pajak" value="{{ old('pajak', $kendaraan->pajak) }}" style="width:100%;box-sizing:border-box; padding: 10px 14px; font-size:14px; border: 1px solid var(--gray-300); border-radius: 8px; outline:none;">
                        @error('pajak')<div class="error-text" style="color:#dc2626; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-group-section" style="margin-bottom: 32px; background: #f8fafc; padding: 28px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <h4 style="font-size: 15px; font-weight:600; color: var(--gray-700); margin-bottom: 24px; border-bottom: 1px solid var(--gray-200); padding-bottom: 12px; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 16 12 12 12 8"></polyline><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    Informasi Penggunaan
                </h4>
                
                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 28px;">
                    <div class="form-field">
                        <label for="jenis_penggunaan_select" style="display:block;margin-bottom:8px;font-size:13px;font-weight:500;color:#475569;">Jenis Penggunaan <span style="color:var(--danger-color);">*</span></label>
                        <select id="jenis_penggunaan_select" name="jenis_penggunaan" style="width:100%;box-sizing:border-box; background:#fff; padding: 10px 14px; font-size:14px; border: 1px solid var(--gray-300); border-radius: 8px; outline:none;">
                            <option value="jabatan" {{ old('jenis_penggunaan', $kendaraan->jenis_penggunaan) == 'jabatan' ? 'selected' : '' }}>Jabatan</option>
                            <option value="operasional" {{ old('jenis_penggunaan', $kendaraan->jenis_penggunaan) == 'operasional' ? 'selected' : '' }}>Operasional</option>
                        </select>
                        @error('jenis_penggunaan')<div class="error-text" style="color:#dc2626; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-field" id="lokasi_operasional_group" style="{{ old('jenis_penggunaan', $kendaraan->jenis_penggunaan) == 'operasional' ? '' : 'display:none;' }}">
                        <label for="lokasi_operasional_input" style="display:block;margin-bottom:8px;font-size:13px;font-weight:500;color:#475569;">Lokasi Operasional <span style="color:var(--danger-color);">*</span></label>
                        <input type="text" id="lokasi_operasional_input" name="lokasi_operasional" value="{{ old('lokasi_operasional', $kendaraan->lokasi_operasional) }}" style="width:100%;box-sizing:border-box; padding:10px 14px; font-size:14px; border:1px solid var(--gray-300); border-radius:8px;" placeholder="Area Keliling / Nama Unit">
                        <span style="display:block; margin-top:6px; font-size:11px; color:var(--gray-500);">Wajib diisi khusus operasional.</span>
                        @error('lokasi_operasional')<div class="error-text" style="color:#dc2626; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Menampilkan QR Code Token info -->
            <div class="form-group-section" style="margin-bottom: 24px; padding: 0 10px;">
                <h4 style="font-size: 13px; text-transform: uppercase; color: var(--gray-500); letter-spacing: 0.5px; margin-bottom: 12px; display:flex; align-items:center; gap:8px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    QR Token Publik Tertaut
                </h4>
                <div style="display:inline-flex; align-items:center; background:#e0e7ff; padding:12px 20px; border-radius:8px; border:1px solid #c7d2fe;">
                    <strong style="font-size:18px; letter-spacing:2px; color:#4338ca;">{{ $kendaraan->qrKendaraan->token ?? 'Belum ada token' }}</strong>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px;padding-top:20px;">
                <a class="btn" href="{{ route('admin.kendaraan.index') }}" style="padding:10px 24px;">Batal</a>
                <button class="btn btn-primary" type="submit" style="padding:10px 24px;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</section>

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
        toggleLokasi();
    });
</script>
@endsection
