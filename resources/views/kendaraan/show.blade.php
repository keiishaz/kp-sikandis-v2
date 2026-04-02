@extends('layouts.app')

@section('title', 'Detail Kendaraan — SIKANDIS')
@section('topbar_title', 'Data Kendaraan ' . (auth()->user()->role->nama_role === 'admin' ? 'Admin' : 'Operator'))

@section('content')
<style>
    .tab-btn {
        background: none; border: none; padding: 0 0 12px 0;
        font-size: 14.5px; font-weight: 500; color: var(--n-500);
        border-bottom: 2px solid transparent; cursor: pointer;
        margin-bottom: -2px; transition: all 0.15s;
    }
    .tab-btn:hover { color: var(--n-700); }
    .tab-btn.active {
        font-weight: 600; color: var(--brand-600);
        border-bottom: 2px solid var(--brand-600);
    }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    
    .info-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
    }
    @media (max-width: 768px) {
        .info-grid { grid-template-columns: 1fr; }
    }
</style>
<div class="dashboard">

    {{-- PAGE HEADER --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <nav aria-label="breadcrumb" style="margin-bottom: 12px; font-size: 13.5px;">
                <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <li>
                        <a href="{{ route('kendaraan.index') }}" style="color: var(--n-500); text-decoration: none; font-weight: 500;">Kendaraan</a>
                    </li>
                    <li style="color: var(--n-400);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </li>
                    <li style="color: var(--n-900); font-weight: 600;" aria-current="page">{{ $kendaraan->nama_kendaraan }}</li>
                </ol>
            </nav>
            <h2 class="page-heading" style="margin-bottom: 8px;">{{ $kendaraan->nama_kendaraan }}</h2>
            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <span class="plat-badge">{{ $kendaraan->no_polisi }}</span>
                <span class="badge badge-neutral">{{ $kendaraan->kategori->nama_kategori ?? '-' }}</span>
                <span class="badge badge-neutral">Tahun {{ $kendaraan->tahun }}</span>
            </div>
        </div>
        
        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <span class="badge badge-sm {{ $kendaraan->status === 'aktif' ? 'badge-success' : 'badge-danger' }}">
                {{ $kendaraan->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}
            </span>
            @php
                $pajakBadgeClass = match($kendaraan->color_pajak) {
                    'green' => 'badge-success',
                    'yellow' => 'badge-warning',
                    'red' => 'badge-danger',
                    default => 'badge-neutral'
                };
            @endphp
            <span class="badge badge-sm {{ $pajakBadgeClass }}">
                Jatuh Tempo Pajak: {{ $kendaraan->status_pajak }}
            </span>
            <a href="{{ route('kendaraan.edit', $kendaraan->id) }}" class="btn btn-primary btn-sm">Edit Kendaraan</a>
            <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
    </div>

    {{-- CUSTOM TAB NAVIGATION --}}
    @php $activeTab = request('tab', 'info'); @endphp
    <div style="display: flex; gap: 32px; border-bottom: 2px solid var(--n-100); margin-bottom: 24px;">
        <button class="tab-btn {{ $activeTab === 'info' ? 'active' : '' }}" data-target="tab-info">
            Informasi Kendaraan
        </button>
        <button class="tab-btn {{ $activeTab === 'pemegang' ? 'active' : '' }}" data-target="tab-pemegang">
            Riwayat Pemegang
        </button>
        <button class="tab-btn {{ $activeTab === 'aktivitas' ? 'active' : '' }}" data-target="tab-aktivitas">
            Riwayat Aktivitas
        </button>
    </div>

    {{-- TAB CONTENTS --}}
    <div class="tab-contents">
        
        {{-- TAB 1: INFORMASI KENDARAAN --}}
        <div id="tab-info" class="tab-content {{ $activeTab === 'info' ? 'active' : '' }}">
            <div class="info-grid">
                
                {{-- Spesifikasi & Pajak --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <div class="icon-container icon-container--blue" style="width: 32px; height: 32px; border-radius: 8px; background: var(--brand-50); color: var(--brand-600); display: inline-flex; align-items: center; justify-content: center; margin-right: 10px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="info-icon" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            </div>
                            Spesifikasi & Pajak
                        </h3>
                    </div>
                    <div class="card-body">
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--n-200); padding-bottom: 12px;">
                                <span class="text-muted">No. Rangka</span>
                                <span style="font-weight: 500; color: var(--n-900);">{{ $kendaraan->no_rangka ?? '-' }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--n-200); padding-bottom: 12px;">
                                <span class="text-muted">No. Mesin</span>
                                <span style="font-weight: 500; color: var(--n-900);">{{ $kendaraan->no_mesin ?? '-' }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding-bottom: 4px;">
                                <span class="text-muted">Jatuh Tempo Pajak</span>
                                <span style="font-weight: 600; color: var(--n-900);">{{ $kendaraan->pajak ? \Carbon\Carbon::parse($kendaraan->pajak)->translatedFormat('d F Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Penggunaan --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <div class="icon-container icon-container--purple" style="width: 32px; height: 32px; border-radius: 8px; background: #F5F3FF; color: #7C3AED; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="info-icon" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 16 12 12 12 8"></polyline><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            </div>
                            Penggunaan
                        </h3>
                    </div>
                    <div class="card-body">
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--n-200); padding-bottom: 12px;">
                                <span class="text-muted">Jenis Penggunaan</span>
                                <span style="font-weight: 500; color: var(--n-900); text-transform: capitalize;">{{ $kendaraan->jenis_penggunaan }}</span>
                            </div>
                            @if($kendaraan->jenis_penggunaan === 'operasional')
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--n-200); padding-bottom: 12px;">
                                <span class="text-muted">Lokasi Operasional</span>
                                <span style="font-weight: 500; color: var(--n-900);">{{ $kendaraan->lokasi_operasional ?? '-' }}</span>
                            </div>
                            @endif
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 4px;">
                                <span class="text-muted">Token QR Sistem</span>
                                <span class="badge" style="background: var(--brand-100); color: var(--brand-700); font-family: monospace; letter-spacing: 1px;">
                                    {{ $kendaraan->qrKendaraan->token ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- TAB 2: RIWAYAT PEMEGANG --}}
        <div id="tab-pemegang" class="tab-content {{ $activeTab === 'pemegang' ? 'active' : '' }}">


            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Histori Pemegang Kendaraan</h3>
                    <button type="button" id="btn-ganti-pemegang" class="btn btn-primary btn-sm">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                        Ganti Pemegang
                    </button>
                </div>
                <div class="card-body-flush table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama Pegawai</th>
                                <th>Asal Pegawai</th>
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
                                    <td>
                                        <div class="cell-primary" style="font-weight: 600;">{{ $p->nama_pegawai ?? ($p->pegawai->nama ?? 'Pegawai Internal') }}</div>
                                        @if($p->jabatan_pegawai)
                                            <div style="font-size: 11px; color: var(--n-500); margin-top: 2px;">{{ $p->jabatan_pegawai }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->source_system === 'API')
                                            <span class="badge" style="font-size:10px; padding:2px 8px; background:var(--brand-100); color:var(--brand-700); border: 1px solid var(--brand-200);">Internal (API)</span>
                                        @else
                                            <span class="badge" style="font-size:10px; padding:2px 8px; background:var(--n-100); color:var(--n-700); border: 1px solid var(--n-200);">Eksternal</span>
                                        @endif
                                    </td>
                                    <td><span style="font-family: monospace; font-size: 13px; color: var(--n-600);">{{ $p->nip ?? ($p->pegawai->nip ?? '-') }}</span></td>
                                    <td>{{ $p->nomor_sk }}</td>
                                    <td>{{ $p->tanggal_mulai ? $p->tanggal_mulai->translatedFormat('d M Y') : '-' }}</td>
                                    <td>{{ $p->tanggal_selesai ? $p->tanggal_selesai->translatedFormat('d M Y') : '—' }}</td>
                                    <td>
                                        @if($p->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-neutral">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                                            </div>
                                            <div class="empty-state-title">Belum ada pemegang</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TAB 3: RIWAYAT AKTIVITAS --}}
        <div id="tab-aktivitas" class="tab-content {{ $activeTab === 'aktivitas' ? 'active' : '' }}">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Histori Servis & Aktivitas</h3>
                    <button type="button" onclick="openAktivitasModal()" class="btn btn-primary btn-sm">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Tambah Aktivitas
                    </button>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($kendaraan->aktivitas as $akt)
                            <div class="timeline-item">
                                <div class="timeline-date">
                                    <div style="font-weight: 600; color: var(--n-900);">{{ $akt->tanggal_aktivitas->translatedFormat('d M') }}</div>
                                    <div style="font-size: 11px; color: var(--n-500);">{{ $akt->tanggal_aktivitas->translatedFormat('Y') }}</div>
                                </div>
                                <div class="timeline-dot-col">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-line"></div>
                                </div>
                                <div class="timeline-content" style="border: 1px solid var(--n-200); box-shadow: none;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                                        <h4 class="timeline-title" style="margin: 0; padding-top: 2px;">{{ $akt->judul_aktivitas }}</h4>
                                        <div style="display: flex; gap: 6px; align-items: center;">
                                            @if($akt->biaya_terpakai)
                                                <span class="badge" style="background: var(--brand-50); color: var(--brand-700); border: 1px solid var(--brand-200); font-weight: 600; padding: 3px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; font-size: 11px;">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                                    Rp {{ number_format($akt->biaya_terpakai, 0, ',', '.') }}
                                                </span>
                                            @endif
                                            <button type="button" onclick='editAktivitas(@json($akt))' class="btn btn-secondary btn-icon" style="width: 24px; height: 24px; padding: 0; min-height: 24px;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </button>
                                            <form onsubmit="deleteAktivitas(event, {{ $akt->id }})" action="{{ route('kendaraan.aktivitas.destroy', $akt->id) }}" method="POST" style="display: inline; margin: 0;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-secondary btn-icon" style="width: 24px; height: 24px; padding: 0; min-height: 24px;">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <p class="timeline-desc" style="margin: 0; font-size: 13px;">{{ $akt->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                                    <div class="timeline-meta" style="margin-top: 8px; padding-top: 8px; border-top: 1px dashed var(--n-200); font-size: 11.5px; display: flex; justify-content: space-between;">
                                        <span>Oleh: {{ $akt->creator->name ?? 'System' }}</span>
                                        <span>{{ $akt->created_at->translatedFormat('d M Y, H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state" style="padding: 40px; margin: 0 auto; width: 100%;">
                                <div class="empty-state-icon">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                </div>
                                <div class="empty-state-title">Belum ada riwayat aktivitas</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- MODALS SECTION --}}

<!-- =========== MODAL: Ganti Pemegang =========== -->
<div id="modal-ganti-pemegang" class="modal-overlay">
    <div class="modal" style="max-width:540px;">

        <div class="modal-header" style="padding: 20px 24px; border-bottom: 1px solid var(--n-200); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="margin:0; font-size:18px; font-weight:600; color:var(--n-900);">Ganti Pemegang Kendaraan</h3>
                <p style="margin:4px 0 0; font-size:13px; color:var(--n-500);">{{ $kendaraan->nama_kendaraan }} — {{ $kendaraan->no_polisi }}</p>
            </div>
            <button id="btn-close-modal" type="button" class="btn btn-icon btn-secondary" style="border: none;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <form id="form-ganti-pemegang" action="{{ route('kendaraan.pemegang.store', $kendaraan->id) }}" method="POST">
            @csrf
            <input type="hidden" name="force_replace" id="input-force-replace" value="0">

            <div class="modal-body" style="padding: 20px 24px; display: flex; flex-direction: column; gap: 16px;">

                {{-- Pilihan Sumber Data --}}
                <div class="form-group">
                    <label class="form-label">Sumber Data Pegawai <span class="text-danger">*</span></label>
                    <div style="display: flex; gap: 16px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="radio" name="source_system" value="API" checked onchange="toggleSource('API')">
                            <span style="font-weight: 500;">Internal (API)</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="radio" name="source_system" value="Manual" onchange="toggleSource('Manual')">
                            <span style="font-weight: 500;">Eksternal (Manual)</span>
                        </label>
                    </div>
                </div>

                {{-- SECTION: INTERNAL API --}}
                <div id="section-internal" style="display: flex; flex-direction: column; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">NIP Pegawai Internal <span class="text-danger">*</span></label>
                        <div style="display: flex; gap: 8px;">
                            <input type="text" name="nip" id="input-api-nip" class="form-input" placeholder="Ketik NIP..." style="flex: 1;">
                            <button type="button" id="btn-fetch-api" class="btn btn-secondary">Cari Data</button>
                        </div>
                        <small id="api-error" class="text-danger" style="display:none; margin-top:4px; font-size:12px;"></small>
                    </div>
                </div>

                {{-- SECTION: EKSTERNAL MANUAL --}}
                <div id="section-eksternal" style="display: none; flex-direction: column; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Unit Eksternal <span class="text-danger">*</span></label>
                        <select id="select-unit-eksternal" class="form-select">
                            <option value="">— Pilih Unit Eksternal —</option>
                            @foreach(\App\Models\Unit::orderBy('nama_unit')->get() as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group" id="group-search-eksternal" style="display:none; position: relative;">
                        <label class="form-label">Cari Pegawai (Nama/NIP/NIK) <span class="text-danger">*</span></label>
                        <input type="text" id="input-search-eksternal" class="form-input" placeholder="Ketik untuk mencari..." autocomplete="off">
                        
                        <div id="dropdown-eksternal" style="display:none; position:absolute; top:100%; left:0; right:0; background:var(--surface-card); border:1px solid var(--n-200); border-radius:var(--r-md); box-shadow:var(--shadow-md); z-index:1000; max-height:200px; overflow-y:auto; margin-top:4px;">
                            <!-- Dropdown list -->
                        </div>
                    </div>
                    <input type="hidden" name="pegawai_id" id="input-pegawai-id">
                </div>

                <!-- Preview Pegawai -->
                <div id="preview-pegawai" style="display:none; background: var(--surface-page); border: 1px solid var(--n-200); border-radius: var(--r-md); padding: 16px;">
                    <p style="margin:0 0 12px; font-size:11px; font-weight:600; color:var(--n-500); text-transform:uppercase; letter-spacing:0.5px;">Info Pegawai Terpilih</p>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div><p style="margin:0; font-size:12px; color:var(--n-500);">Nama</p><p id="prev-nama" style="margin:0; font-size:13.5px; font-weight:600; color:var(--n-900);">—</p></div>
                        <div><p style="margin:0; font-size:12px; color:var(--n-500);">NIP</p><p id="prev-nip" style="margin:0; font-size:13.5px; font-family:monospace; color:var(--n-900);">—</p></div>
                        <div style="grid-column: 1 / -1;"><p style="margin:0; font-size:11px; color:var(--n-500); text-transform:uppercase; letter-spacing:0.3px;">Jabatan</p><p id="prev-jabatan" style="margin:2px 0 0; font-size:13.5px; font-weight:500; color:var(--n-900);">—</p></div>
                        <div style="grid-column: 1 / -1;"><p style="margin:0; font-size:11px; color:var(--n-500); text-transform:uppercase; letter-spacing:0.3px;">Pangkat / Golongan</p><p id="prev-pangkat" style="margin:2px 0 0; font-size:13.5px; font-weight:500; color:var(--n-900);">—</p></div>
                        <div id="container-prev-unit" style="grid-column: 1 / -1;"><p style="margin:0; font-size:11px; color:var(--n-500); text-transform:uppercase; letter-spacing:0.3px;">Unit Kerja / OPD</p><p id="prev-unit" style="margin:2px 0 0; font-size:13.5px; font-weight:500; color:var(--n-900);">—</p></div>
                        <div id="container-prev-subunit" style="grid-column: 1 / -1;"><p style="margin:0; font-size:11px; color:var(--n-500); text-transform:uppercase; letter-spacing:0.3px;">Sub Unit</p><p id="prev-subunit" style="margin:2px 0 0; font-size:13px; color:var(--n-600);">—</p></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Surat Keputusan (SK) <span class="text-danger">*</span></label>
                    <input type="text" name="nomor_sk" id="input-nomor-sk" class="form-input" required placeholder="Contoh: SK/001/2026">
                    <div id="error-nomor-sk" class="form-error" style="display:none; color:var(--danger-text); font-size:12px; margin-top:4px;"></div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Tanggal SK <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_sk" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_mulai" class="form-input" required>
                    </div>
                </div>

            </div>

            <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid var(--n-200); display: flex; justify-content: flex-end; gap: 12px; background: var(--surface-page); border-bottom-left-radius: var(--r-xl); border-bottom-right-radius: var(--r-xl);">
                <button type="button" id="btn-cancel-modal" class="btn btn-secondary">Batal</button>
                <button type="submit" id="btn-submit-pemegang" class="btn btn-primary">Simpan Pemegang</button>
            </div>
        </form>

    </div>
</div>

<!-- =========== MODAL: Aktivitas Kendaraan =========== -->
<div id="modal-aktivitas" class="modal-overlay">
    <div class="modal" style="max-width:500px;">
        <div class="modal-header" style="padding: 20px 24px; border-bottom: 1px solid var(--n-200); display: flex; justify-content: space-between; align-items: center;">
            <h3 id="modal-aktivitas-title" style="margin:0; font-size:18px; font-weight:600; color:var(--n-900);">Tambah Aktivitas Kendaraan</h3>
            <button onclick="closeAktivitasModal()" type="button" class="btn btn-icon btn-secondary" style="border: none;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="form-aktivitas" method="POST">
            @csrf
            <input type="hidden" name="_method" id="aktivitas-method" value="POST">
            <div class="modal-body" style="padding: 20px 24px; display: flex; flex-direction: column; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Tanggal Aktivitas <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_aktivitas" id="akt-tanggal" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Judul Aktivitas <span class="text-danger">*</span></label>
                    <input type="text" name="judul_aktivitas" id="akt-judul" class="form-input" required placeholder="Misal: Servis Rutin, Ganti Ban, dll">
                </div>
                <div class="form-group">
                    <label class="form-label">Total Biaya Terpakai (Opsional)</label>
                    <div class="input-with-icon" style="position: relative;">
                        <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--n-500); font-weight: 600; font-size: 13.5px;">Rp</span>
                        <input type="number" name="biaya_terpakai" id="akt-biaya" class="form-input" style="padding-left: 40px;" placeholder="0" min="0">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="akt-deskripsi" class="form-textarea" rows="4" placeholder="Tambahkan rincian aktivitas jika ada..." style="width:100%;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid var(--n-200); display: flex; justify-content: flex-end; gap: 12px; background: var(--surface-page); border-bottom-left-radius: var(--r-xl); border-bottom-right-radius: var(--r-xl);">
                <button type="button" onclick="closeAktivitasModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Aktivitas</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // ---- Portal: pindah modal ke <body> agar overlay cover full viewport ----
    ['modal-ganti-pemegang', 'modal-aktivitas'].forEach(id => {
        const el = document.getElementById(id);
        if (el) document.body.appendChild(el);
    });

    // ---- Tab Switching ----
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');


    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            btn.classList.add('active');
            document.getElementById(btn.getAttribute('data-target')).classList.add('active');
        });
    });

    // ---- Modal Open/Close Ganti Pemegang ----
    const modalPemegang = document.getElementById('modal-ganti-pemegang');

    document.getElementById('btn-ganti-pemegang').addEventListener('click', () => {
        modalPemegang.classList.add('active');
    });
    document.getElementById('btn-close-modal').addEventListener('click', closePemegangModal);
    document.getElementById('btn-cancel-modal').addEventListener('click', closePemegangModal);
    modalPemegang.addEventListener('click', e => { if (e.target === modalPemegang) closePemegangModal(); });

    function closePemegangModal() {
        modalPemegang.classList.remove('active');
        document.getElementById('input-force-replace').value = '0';
    }

    // ---- Realtime / Dynamic UI Logic ----
    window.toggleSource = function(source) {
        document.getElementById('section-internal').style.display = source === 'API' ? 'flex' : 'none';
        document.getElementById('section-eksternal').style.display = source === 'Manual' ? 'flex' : 'none';
        
        // Reset previews
        document.getElementById('preview-pegawai').style.display = 'none';
        document.getElementById('input-api-nip').value = '';
        document.getElementById('input-search-eksternal').value = '';
        document.getElementById('input-pegawai-id').value = '';
        document.getElementById('select-unit-eksternal').value = '';
        document.getElementById('group-search-eksternal').style.display = 'none';
    };

    // Internal API Fetching
    document.getElementById('btn-fetch-api').addEventListener('click', async () => {
        const nip = document.getElementById('input-api-nip').value.trim();
        const err = document.getElementById('api-error');
        const box = document.getElementById('preview-pegawai');
        
        if (!nip) return;
        err.style.display = 'none';
        box.style.display = 'none';

        try {
            document.getElementById('btn-fetch-api').textContent = 'Mencari...';
            const res = await fetch(`/api/dummy-pegawai/${nip}`);
            if (!res.ok) throw new Error('Data tidak ditemukan');
            const data = await res.json();
            
            document.getElementById('prev-nama').textContent    = data.nama     || '—';
            document.getElementById('prev-nip').textContent     = data.nip      || '—';
            document.getElementById('prev-jabatan').textContent = data.jabatan  || '—';
            document.getElementById('prev-pangkat').textContent = data.pangkat  || '—';
            document.getElementById('prev-unit').textContent    = data.opd      || '—';
            document.getElementById('container-prev-subunit').style.display = 'none';
            document.getElementById('container-prev-unit').style.display = 'block';
            
            box.style.display = 'block';
        } catch (error) {
            err.textContent = error.message;
            err.style.display = 'block';
        } finally {
             document.getElementById('btn-fetch-api').textContent = 'Cari Data';
        }
    });

    // Eksternal Manual unit change
    document.getElementById('select-unit-eksternal').addEventListener('change', function() {
        document.getElementById('group-search-eksternal').style.display = this.value ? 'block' : 'none';
        document.getElementById('input-search-eksternal').value = '';
        document.getElementById('preview-pegawai').style.display = 'none';
    });

    // Eksternal Search Keyup
    let eksternalTimeout;
    const searchInput = document.getElementById('input-search-eksternal');
    const dropdown = document.getElementById('dropdown-eksternal');
    
    searchInput.addEventListener('input', function() {
        clearTimeout(eksternalTimeout);
        const q = this.value.trim();
        const unitId = document.getElementById('select-unit-eksternal').value;
        
        if (q.length < 2) { dropdown.style.display = 'none'; return; }
        
        eksternalTimeout = setTimeout(async () => {
            const res = await fetch(`/api/eksternal-search?unit_id=${unitId}&q=${encodeURIComponent(q)}`);
            if (res.ok) {
                const results = await res.json();
                dropdown.innerHTML = '';
                if (results.length === 0) {
                    dropdown.innerHTML = '<div style="padding:12px; color:var(--n-500); font-size:13px; text-align:center;">Tidak ada data ditemukan</div>';
                } else {
                    results.forEach(item => {
                        const div = document.createElement('div');
                        div.style.padding = '8px 12px';
                        div.style.cursor = 'pointer';
                        div.style.borderBottom = '1px solid var(--n-100)';
                        div.innerHTML = `<div style="font-weight:600; font-size:13px;">${item.nama}</div><div style="font-size:11px; color:var(--n-500);">${item.nip || item.nik} - ${item.unit}</div>`;
                        div.addEventListener('mouseenter', () => div.style.background = 'var(--n-50)');
                        div.addEventListener('mouseleave', () => div.style.background = 'none');
                        div.addEventListener('click', () => {
                            selectEksternalPegawai(item);
                            dropdown.style.display = 'none';
                        });
                        dropdown.appendChild(div);
                    });
                }
                dropdown.style.display = 'block';
            }
        }, 300);
    });
    
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    function selectEksternalPegawai(data) {
        searchInput.value = data.nama;
        document.getElementById('input-pegawai-id').value = data.id;
        
        document.getElementById('prev-nama').textContent    = data.nama     || '—';
        document.getElementById('prev-nip').textContent     = data.nip || data.nik || '—';
        document.getElementById('prev-jabatan').textContent = data.jabatan  || '—';
        document.getElementById('prev-pangkat').textContent = '—';
        document.getElementById('prev-unit').textContent    = data.unit     || '—';
        document.getElementById('container-prev-subunit').style.display = 'block';
        document.getElementById('container-prev-unit').style.display = 'block';
        document.getElementById('prev-subunit').textContent = data.sub_unit || '—';
        
        document.getElementById('preview-pegawai').style.display = 'block';
    }

    // ---- Form Submit Ganti Pemegang : cek needs_confirm ----
    document.getElementById('form-ganti-pemegang').addEventListener('submit', async function(e) {
        const forceReplace = document.getElementById('input-force-replace').value;
        if (forceReplace === '1') return; // sudah dikonfirmasi, lanjut submit

        e.preventDefault();

        const formData = new FormData(this);
        formData.set('force_replace', '0');

        try {
            const res = await fetch(this.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: formData,
            });

            // Reset error states
            document.getElementById('error-nomor-sk').style.display = 'none';
            document.getElementById('input-nomor-sk').classList.remove('is-invalid');

            if (res.status === 422) {
                const errorData = await res.json();
                if (errorData.errors && errorData.errors.nomor_sk) {
                    const errorEl = document.getElementById('error-nomor-sk');
                    errorEl.textContent = errorData.errors.nomor_sk[0];
                    errorEl.style.display = 'block';
                    document.getElementById('input-nomor-sk').classList.add('is-invalid');
                }
                return; // Stop processing if validation fails
            }

            if (!res.ok) throw new Error('Server error');
            const data = await res.json();

            if (data.needs_confirm) {
                const nama = data.pemegang_lama?.nama || 'Pemegang saat ini';
                const confirmed = await SIKANDIS.confirm({
                    title: 'Konfirmasi Serah Terima',
                    message: `Kendaraan ini saat ini dipegang oleh \b${nama}\b. Pemegang lama akan dinonaktifkan dan digantikan dengan pemegang baru. Lanjutkan?`,
                    confirmText: 'Ya, Ganti Pemegang',
                    cancelText: 'Batal',
                    type: 'warning',
                });

                if (confirmed) {
                    document.getElementById('input-force-replace').value = '1';
                    this.submit();
                }
            } else {
                window.location.href = "{{ route('kendaraan.show', $kendaraan->id) }}?tab=pemegang";
            }
        } catch (err) {
            document.getElementById('input-force-replace').value = '1';
            this.submit();
        }
    });

    // ---- Aktivitas Modal Logic ----
    const modalAkt = document.getElementById('modal-aktivitas');
    const formAkt  = document.getElementById('form-aktivitas');

    window.openAktivitasModal = function() {
        document.getElementById('modal-aktivitas-title').textContent = 'Tambah Aktivitas Kendaraan';
        formAkt.action = "{{ route('kendaraan.aktivitas.store', $kendaraan->id) }}";
        formAkt.reset();
        document.getElementById('aktivitas-method').value = 'POST';
        document.getElementById('akt-tanggal').value = new Date().toISOString().split('T')[0];
        document.getElementById('akt-biaya').value = '';
        modalAkt.classList.add('active');
    };

    window.closeAktivitasModal = function() {
        modalAkt.classList.remove('active');
    };

    window.editAktivitas = function(akt) {
        document.getElementById('modal-aktivitas-title').textContent = 'Edit Aktivitas Kendaraan';
        const baseUrl = "{{ route('kendaraan.aktivitas.update', ':id') }}";
        formAkt.action = baseUrl.replace(':id', akt.id);
        document.getElementById('aktivitas-method').value = 'PUT';
        document.getElementById('akt-tanggal').value = akt.tanggal_aktivitas.split('T')[0];
        document.getElementById('akt-judul').value = akt.judul_aktivitas;
        document.getElementById('akt-biaya').value = akt.biaya_terpakai || '';
        document.getElementById('akt-deskripsi').value = akt.deskripsi || '';
        modalAkt.classList.add('active');
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
            const baseUrl = "{{ route('kendaraan.aktivitas.destroy', ':id') }}";
            const res = await fetch(baseUrl.replace(':id', id), {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            if (res.ok) window.location.href = "{{ route('kendaraan.show', $kendaraan->id) }}?tab=aktivitas";
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

    // Flash message auto-hide if using generic alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

});
</script>
@endsection
