@extends('layouts.admin')

@section('title', 'Detail Kendaraan - SIKANDIS')
@section('topbar_title', 'Kendaraan')

@section('content')
<section class="form-container">
    <div class="card" style="padding: 0;">
        <div style="padding: var(--spacing-lg);">
            <nav style="font-size:13px;color:#94a3b8;margin-bottom:20px;display:flex;align-items:center;gap:6px;">
                <a href="{{ route('admin.kendaraan.index') }}" style="color:#3b82f6;text-decoration:none;font-weight:500;">Kendaraan</a>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                <span style="color:#334155;">Detail Kendaraan</span>
            </nav>

            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h3 style="margin:0 0 8px;font-size:20px;font-weight:600;color:#0f172a;">{{ $kendaraan->nama_kendaraan }}</h3>
                    <div style="display: flex; gap: 12px; align-items: center; font-size: 13px; color: var(--gray-500);">
                        <span style="display:inline-flex;align-items:center;background:var(--gray-100);padding:4px 8px;border-radius:4px;font-weight:600;color:var(--gray-700);letter-spacing:1px;">{{ $kendaraan->no_polisi }}</span>
                        <span>•</span>
                        <span>{{ $kendaraan->kategori->nama_kategori ?? '-' }}</span>
                        <span>•</span>
                        <span>Tahun {{ $kendaraan->tahun }}</span>
                    </div>
                </div>
                <div style="display: flex; gap:12px; align-items: center;">
                    @php
                        // Kalkulasi manual pajak untuk Show view tanpa query ulang di controller untuk kemudahan refactor logic lokal
                        $now = \Carbon\Carbon::now();
                        $statusPajak = 'Belum Diatur';
                        $colorPajakBadgeLine = '#e2e8f0';
                        $colorPajakBadgeText = '#64748b';
                        $colorPajakBadgeBg = '#f8fafc';
                        
                        if ($kendaraan->pajak) {
                            $pDate = \Carbon\Carbon::parse($kendaraan->pajak);
                            if ($pDate->isPast()) {
                                $statusPajak = 'Telah Jatuh Tempo';
                                $colorPajakBadgeLine = '#fecaca'; $colorPajakBadgeText = '#ef4444'; $colorPajakBadgeBg = '#fef2f2';
                            } else {
                                if ($now->diffInMonths($pDate, false) <= 6) {
                                    $statusPajak = 'Hampir Jatuh Tempo';
                                    $colorPajakBadgeLine = '#fef08a'; $colorPajakBadgeText = '#ca8a04'; $colorPajakBadgeBg = '#fefce8';
                                } else {
                                    $statusPajak = 'Pajak Aktif';
                                    $colorPajakBadgeLine = '#bbf7d0'; $colorPajakBadgeText = '#16a34a'; $colorPajakBadgeBg = '#f0fdf4';
                                }
                            }
                        }
                    @endphp
                    <span style="display:inline-flex;align-items:center;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:600;background-color:{{ $colorPajakBadgeBg }};color:{{ $colorPajakBadgeText }}; border:1px solid {{ $colorPajakBadgeLine }}; gap:6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        Pajak: {{ $statusPajak }}
                    </span>
                    
                    @if($kendaraan->status === 'aktif')
                        <span style="display:inline-block;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:600;background-color:#16a34a15;color:#16a34a;">Status Kendaraan: Aktif</span>
                    @else
                        <span style="display:inline-block;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:600;background-color:#ef444415;color:#ef4444;">Status Kendaraan: Nonaktif</span>
                    @endif
                    
                    <a href="{{ route('admin.kendaraan.index') }}" class="btn btn-outline" style="padding:6px 16px; display:inline-flex; align-items:center; font-size:13px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Custom Tabs Navigation -->
        <div style="display: flex; gap: 24px; border-bottom: 1px solid var(--gray-200); padding: 0 var(--spacing-lg);">
            <button class="tab-btn active" data-target="tab-info" style="background:none; border:none; padding: 12px 4px; font-size: 14px; font-weight: 500; color: var(--primary-color); border-bottom: 2px solid var(--primary-color); cursor:pointer;">
                Informasi Kendaraan
            </button>
            <button class="tab-btn" data-target="tab-pemegang" style="background:none; border:none; padding: 12px 4px; font-size: 14px; font-weight: 500; color: var(--gray-500); border-bottom: 2px solid transparent; cursor:pointer;">
                Riwayat Pemegang
            </button>
            <button class="tab-btn" data-target="tab-aktivitas" style="background:none; border:none; padding: 12px 4px; font-size: 14px; font-weight: 500; color: var(--gray-500); border-bottom: 2px solid transparent; cursor:pointer;">
                Riwayat Aktivitas Kendaraan
            </button>
        </div>

        <!-- Tab Contents -->
        <div style="padding: var(--spacing-md); background: #f8fafc; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
            
            <!-- Tab 1: Info -->
            <div id="tab-info" class="tab-content" style="display: block;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <!-- Spesifikasi Block -->
                    <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid var(--gray-200);">
                        <h4 style="margin:0 0 16px; font-size:14px; color:var(--gray-500); text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid var(--gray-100); padding-bottom:8px;">Spesifikasi & Pajak</h4>
                        
                        <div style="display:flex; flex-direction:column; gap:12px;">
                            <div style="display:flex; justify-content:space-between; border-bottom:1px dashed var(--gray-100); padding-bottom:8px;">
                                <span style="font-size:13px; color:var(--gray-500);">Nomor Rangka</span>
                                <span style="font-size:13px; font-weight:500; color:var(--gray-800);">{{ $kendaraan->no_rangka ?? '-' }}</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; border-bottom:1px dashed var(--gray-100); padding-bottom:8px;">
                                <span style="font-size:13px; color:var(--gray-500);">Nomor Mesin</span>
                                <span style="font-size:13px; font-weight:500; color:var(--gray-800);">{{ $kendaraan->no_mesin ?? '-' }}</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; border-bottom:1px dashed var(--gray-100); padding-bottom:8px;">
                                <span style="font-size:13px; color:var(--gray-500);">Tanggal Aktif Pajak</span>
                                <span style="font-size:13px; font-weight:500; color:var(--gray-800);">{{ $kendaraan->pajak ? \Carbon\Carbon::parse($kendaraan->pajak)->translatedFormat('d F Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Penggunaan Block -->
                    <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid var(--gray-200);">
                        <h4 style="margin:0 0 16px; font-size:14px; color:var(--gray-500); text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid var(--gray-100); padding-bottom:8px;">Informasi Penggunaan</h4>
                        
                        <div style="display:flex; flex-direction:column; gap:12px;">
                            <div style="display:flex; justify-content:space-between; border-bottom:1px dashed var(--gray-100); padding-bottom:8px;">
                                <span style="font-size:13px; color:var(--gray-500);">Jenis Penggunaan</span>
                                <span style="font-size:13px; font-weight:600; color:var(--gray-800); text-transform:capitalize;">{{ $kendaraan->jenis_penggunaan }}</span>
                            </div>
                            @if($kendaraan->jenis_penggunaan === 'operasional')
                            <div style="display:flex; justify-content:space-between; border-bottom:1px dashed var(--gray-100); padding-bottom:8px;">
                                <span style="font-size:13px; color:var(--gray-500);">Lokasi Operasional</span>
                                <span style="font-size:13px; font-weight:500; color:var(--gray-800);">{{ $kendaraan->lokasi_operasional ?? '-' }}</span>
                            </div>
                            @endif
                            <div style="display:flex; justify-content:space-between; border-bottom:1px dashed var(--gray-100); padding-bottom:8px; align-items:center;">
                                <span style="font-size:13px; color:var(--gray-500);">Token QR Validasi Publik</span>
                                <span style="font-size:15px; font-weight:700; color:var(--primary-color); letter-spacing:1.5px; background:var(--primary-light); padding:4px 8px; border-radius:4px;">
                                    {{ $kendaraan->qrKendaraan->token ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Riwayat Pemegang -->
            <div id="tab-pemegang" class="tab-content" style="display: none;">

                @if(session('success') && request()->has('tab') && request()->tab === 'pemegang')
                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:12px 16px;border-radius:8px;color:#16a34a;font-size:13px;margin-bottom:16px;" id="pemegang-flash">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                <!-- Header: Tombol Ganti Pemegang -->
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <div>
                        <h4 style="margin:0; font-size:15px; font-weight:600; color:#0f172a;">Histori Pemegang Kendaraan</h4>
                        <p style="margin:4px 0 0; font-size:12px; color:#64748b;">Seluruh riwayat penugasan kendaraan ini, diurutkan terbaru.</p>
                    </div>
                    <button type="button" id="btn-ganti-pemegang"
                        style="display:inline-flex;align-items:center;gap:8px;background:#2563eb;color:#fff;border:none;padding:8px 18px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;transition:background 0.2s;"
                        onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                        Ganti Pemegang
                    </button>
                </div>

                <!-- Tabel Histori Pemegang -->
                <div class="table-responsive" style="border-radius:8px;border:1px solid #e2e8f0;overflow:hidden;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama Pegawai</th>
                                <th>NIP</th>
                                <th>Nomor SK</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kendaraan->pemegangs as $p)
                            <tr>
                                <td style="font-weight:500;">{{ $p->pegawai->nama ?? '-' }}</td>
                                <td style="font-family:monospace;font-size:12.5px;">{{ $p->pegawai->nip ?? '-' }}</td>
                                <td>{{ $p->nomor_sk }}</td>
                                <td>{{ $p->tanggal_mulai ? $p->tanggal_mulai->translatedFormat('d M Y') : '-' }}</td>
                                <td>{{ $p->tanggal_selesai ? $p->tanggal_selesai->translatedFormat('d M Y') : '—' }}</td>
                                <td>
                                    @if($p->is_active)
                                        <span class="status-badge active">Aktif</span>
                                    @else
                                        <span class="status-badge inactive">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <div class="empty-content">
                                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                                        <p>Belum ada pemegang yang tercatat.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 3: Riwayat Aktivitas Kendaraan -->
            <div id="tab-aktivitas" class="tab-content" style="display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h4 style="margin:0; font-size:16px; font-weight:600; color:#1e293b;">Daftar Aktivitas Kendaraan</h4>
                    <button type="button" onclick="openAktivitasModal()" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:8px; padding:8px 16px; font-size:13px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Tambah Aktivitas
                    </button>
                </div>

                <div class="table-responsive" style="border-radius:8px;border:1px solid #e2e8f0;overflow:hidden;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Judul Aktivitas</th>
                                <th>Deskripsi</th>
                                <th>Pembuat</th>
                                <th>Waktu Sistem</th>
                                <th class="col-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kendaraan->aktivitas as $akt)
                            <tr>
                                <td style="font-weight:500;">{{ $akt->tanggal_aktivitas->translatedFormat('d M Y') }}</td>
                                <td style="font-weight:600;">{{ $akt->judul_aktivitas }}</td>
                                <td style="color:#64748b;line-height:1.4;">{{ $akt->deskripsi ?? '-' }}</td>
                                <td>{{ $akt->creator->name ?? 'System' }}</td>
                                <td style="font-size:12px;color:#94a3b8;">{{ $akt->created_at->translatedFormat('d M Y, H:i') }}</td>
                                <td class="col-actions">
                                    <div class="action-buttons">
                                        <button type="button" onclick='editAktivitas(@json($akt))' class="btn-action btn-edit" title="Edit">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <form onsubmit="deleteAktivitas(event, {{ $akt->id }})" action="{{ route('admin.kendaraan.aktivitas.destroy', $akt->id) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Hapus">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <div class="empty-content">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        <p>Belum ada riwayat aktivitas kendaraan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- =========== MODAL: Ganti Pemegang =========== -->
<div id="modal-ganti-pemegang" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; width:100%; max-width:540px; margin:20px; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,0.3);">

        <!-- Modal Header -->
        <div style="padding:20px 24px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3 style="margin:0; font-size:16px; font-weight:600; color:#0f172a;">Ganti Pemegang Kendaraan</h3>
                <p style="margin:4px 0 0; font-size:12px; color:#64748b;">{{ $kendaraan->nama_kendaraan }} — {{ $kendaraan->no_polisi }}</p>
            </div>
            <button id="btn-close-modal" type="button"
                style="background:none;border:none;cursor:pointer;color:#94a3b8;padding:4px;"
                onmouseover="this.style.color='#475569'" onmouseout="this.style.color='#94a3b8'">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <!-- Form -->
        <form id="form-ganti-pemegang" action="{{ route('admin.kendaraan.pemegang.store', $kendaraan->id) }}" method="POST">
            @csrf
            <input type="hidden" name="force_replace" id="input-force-replace" value="0">

            <div style="padding:20px 24px; display:flex; flex-direction:column; gap:16px;">

                <!-- Pilih Pegawai -->
                <div>
                    <label style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">Pilih Pegawai <span style="color:#ef4444;">*</span></label>
                    <select name="pegawai_id" id="select-pegawai" required
                        style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; color:#374151; background:#fff; outline:none; cursor:pointer;"
                        onchange="previewPegawai(this.value)">
                        <option value="">— Pilih Pegawai —</option>
                        @foreach($pegawais as $peg)
                            <option value="{{ $peg->id }}">{{ $peg->nama }} — {{ $peg->nip }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Preview Pegawai -->
                <div id="preview-pegawai" style="display:none; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:14px 16px;">
                    <p style="margin:0 0 8px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;">Info Pegawai Terpilih</p>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                        <div><p style="margin:0; font-size:11px; color:#64748b;">Nama</p><p id="prev-nama" style="margin:0; font-size:13px; font-weight:600; color:#0f172a;">—</p></div>
                        <div><p style="margin:0; font-size:11px; color:#64748b;">NIP</p><p id="prev-nip" style="margin:0; font-size:13px; font-family:monospace; color:#0f172a;">—</p></div>
                        <div><p style="margin:0; font-size:11px; color:#64748b;">Jabatan</p><p id="prev-jabatan" style="margin:0; font-size:13px; color:#0f172a;">—</p></div>
                        <div><p style="margin:0; font-size:11px; color:#64748b;">Unit</p><p id="prev-unit" style="margin:0; font-size:13px; color:#0f172a;">—</p></div>
                        <div><p style="margin:0; font-size:11px; color:#64748b;">Sub Unit</p><p id="prev-subunit" style="margin:0; font-size:13px; color:#0f172a;">—</p></div>
                    </div>
                </div>

                <!-- Nomor SK -->
                <div>
                    <label style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">Nomor SK <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nomor_sk" required placeholder="Contoh: SK/001/2026"
                        style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; color:#374151; box-sizing:border-box; outline:none;">
                </div>

                <!-- Tanggal SK & Tanggal Mulai -->
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">Tanggal SK <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="tanggal_sk" required
                            style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; color:#374151; box-sizing:border-box; outline:none;">
                    </div>
                    <div>
                        <label style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">Tanggal Mulai <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="tanggal_mulai" required
                            style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; color:#374151; box-sizing:border-box; outline:none;">
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div style="padding:16px 24px; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" id="btn-cancel-modal"
                    style="padding:9px 18px; border:1px solid #d1d5db; background:#fff; color:#374151; border-radius:8px; font-size:13px; cursor:pointer;">
                    Batal
                </button>
                <button type="submit" id="btn-submit-pemegang"
                    style="padding:9px 18px; background:#2563eb; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:500; cursor:pointer; transition:background 0.2s;"
                    onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                    Simpan Pemegang
                </button>
            </div>
        </form>

    </div>
</div>
<!-- =========== MODAL: Aktivitas Kendaraan =========== -->
<div id="modal-aktivitas" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; width:100%; max-width:500px; margin:20px; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <div style="padding:20px 24px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
            <h3 id="modal-aktivitas-title" style="margin:0; font-size:16px; font-weight:600; color:#0f172a;">Tambah Aktivitas Kendaraan</h3>
            <button onclick="closeAktivitasModal()" type="button" style="background:none;border:none;cursor:pointer;color:#94a3b8;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
        </div>
        <form id="form-aktivitas" method="POST">
            @csrf
            <input type="hidden" name="_method" id="aktivitas-method" value="POST">
            <div style="padding:20px 24px; display:flex; flex-direction:column; gap:16px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">Tanggal Aktivitas <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal_aktivitas" id="akt-tanggal" required style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">Judul Aktivitas <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="judul_aktivitas" id="akt-judul" required placeholder="Misal: Servis Rutin, Ganti Ban, dll" style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">Deskripsi</label>
                    <textarea name="deskripsi" id="akt-deskripsi" rows="3" placeholder="Tambahkan rincian aktivitas jika ada..." style="width:100%; padding: 9px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; font-family:inherit;"></textarea>
                </div>
            </div>
            <div style="padding:16px 24px; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeAktivitasModal()" style="padding:9px 18px; border:1px solid #d1d5db; background:#fff; border-radius:8px; font-size:13px; cursor:pointer;">Batal</button>
                <button type="submit" style="padding:9px 18px; background:#2563eb; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:500; cursor:pointer;">Simpan Aktivitas</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // ---- Tab Switching ----
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            tabBtns.forEach(b => {
                b.classList.remove('active');
                b.style.color = 'var(--gray-500)';
                b.style.borderBottomColor = 'transparent';
            });
            tabContents.forEach(c => { c.style.display = 'none'; });

            btn.classList.add('active');
            btn.style.color = 'var(--primary-color)';
            btn.style.borderBottomColor = 'var(--primary-color)';

            document.getElementById(btn.getAttribute('data-target')).style.display = 'block';
        });
    });

    // ---- Modal Open/Close ----
    const modal = document.getElementById('modal-ganti-pemegang');

    document.getElementById('btn-ganti-pemegang').addEventListener('click', () => {
        modal.style.display = 'flex';
    });
    document.getElementById('btn-close-modal').addEventListener('click', closeModal);
    document.getElementById('btn-cancel-modal').addEventListener('click', closeModal);
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

    function closeModal() {
        modal.style.display = 'none';
        document.getElementById('input-force-replace').value = '0';
    }

    // ---- AJAX Preview Pegawai ----
    window.previewPegawai = function(pegawaiId) {
        const box = document.getElementById('preview-pegawai');
        if (!pegawaiId) { box.style.display = 'none'; return; }

        fetch(`/admin/api/pegawai/${pegawaiId}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('prev-nama').textContent    = data.nama     || '—';
                document.getElementById('prev-nip').textContent     = data.nip      || '—';
                document.getElementById('prev-jabatan').textContent = data.jabatan  || '—';
                document.getElementById('prev-unit').textContent    = data.unit     || '—';
                document.getElementById('prev-subunit').textContent = data.sub_unit || '—';
                box.style.display = 'block';
            })
            .catch(() => { box.style.display = 'none'; });
    };

    // ---- Form Submit: cek needs_confirm ----
    document.getElementById('form-ganti-pemegang').addEventListener('submit', async function(e) {
        const forceReplace = document.getElementById('input-force-replace').value;
        if (forceReplace === '1') return; // sudah dikonfirmasi, lanjut submit biasa

        e.preventDefault();

        const formData = new FormData(this);
        formData.set('force_replace', '0');

        try {
            const res = await fetch(this.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: formData,
            });

            if (!res.ok) throw new Error('Server error');
            const data = await res.json();

            if (data.needs_confirm) {
                // Tampilkan SIKANDIS confirm dialog
                const nama = data.pemegang_lama?.nama || 'Pemegang saat ini';
                const confirmed = await SIKANDIS.confirm({
                    title: 'Konfirmasi Serah Terima',
                    message: `Kendaraan ini saat ini dipegang oleh ${nama}. Pemegang lama akan dinonaktifkan dan digantikan dengan pemegang baru. Lanjutkan?`,
                    confirmText: 'Ya, Ganti Pemegang',
                    cancelText: 'Batal',
                    type: 'warning',
                });

                if (confirmed) {
                    document.getElementById('input-force-replace').value = '1';
                    this.submit();
                }
            } else {
                // Tidak ada pemegang aktif, redirect dari server response
                window.location.href = "{{ route('admin.kendaraan.show', $kendaraan->id) }}?tab=pemegang";
            }
        } catch (err) {
            // Fallback: submit normal
            document.getElementById('input-force-replace').value = '1';
            this.submit();
        }
    });

    // ---- Aktivitas Modal ----
    const modalAkt = document.getElementById('modal-aktivitas');
    const formAkt  = document.getElementById('form-aktivitas');

    window.openAktivitasModal = function() {
        document.getElementById('modal-aktivitas-title').textContent = 'Tambah Aktivitas Kendaraan';
        formAkt.action = "{{ route('admin.kendaraan.aktivitas.store', $kendaraan->id) }}";
        formAkt.reset();
        document.getElementById('aktivitas-method').value = 'POST';
        document.getElementById('akt-tanggal').value = new Date().toISOString().split('T')[0];
        modalAkt.style.display = 'flex';
    };

    window.closeAktivitasModal = function() {
        modalAkt.style.display = 'none';
    };

    window.editAktivitas = function(akt) {
        document.getElementById('modal-aktivitas-title').textContent = 'Edit Aktivitas Kendaraan';
        formAkt.action = `/admin/kendaraan-aktivitas/${akt.id}`;
        document.getElementById('aktivitas-method').value = 'PUT';
        document.getElementById('akt-tanggal').value = akt.tanggal_aktivitas.split('T')[0];
        document.getElementById('akt-judul').value = akt.judul_aktivitas;
        document.getElementById('akt-deskripsi').value = akt.deskripsi || '';
        modalAkt.style.display = 'flex';
    };

    window.deleteAktivitas = async function(e, id) {
        e.preventDefault();
        const confirmed = await SIKANDIS.confirm({
            title: 'Hapus Aktivitas',
            message: 'Apakah Anda yakin ingin menghapus data aktivitas ini? Tindakan ini tidak dapat dibatalkan.',
            confirmText: 'Ya, Hapus',
            type: 'danger'
        });

        if (confirmed) {
            const res = await fetch(`/admin/kendaraan-aktivitas/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            if (res.ok) window.location.href = "{{ route('admin.kendaraan.show', $kendaraan->id) }}?tab=aktivitas";
        }
    };

    // ---- Auto-open tab jika dari redirect ----
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') === 'pemegang') {
        const btn = document.querySelector('[data-target="tab-pemegang"]');
        if (btn) btn.click();
    } else if (urlParams.get('tab') === 'aktivitas') {
        const btn = document.querySelector('[data-target="tab-aktivitas"]');
        if (btn) btn.click();
    }

    // Flash message auto-hide
    const flash = document.getElementById('pemegang-flash');
    if (flash) setTimeout(() => { flash.style.opacity = '0'; flash.style.transition = 'opacity 0.5s'; setTimeout(() => flash.remove(), 500); }, 4000);

});
</script>
@endsection

