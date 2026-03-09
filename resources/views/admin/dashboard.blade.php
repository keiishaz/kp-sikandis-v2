@extends('layouts.admin')

@section('title', 'Dashboard Admin — SIKANDIS')
@section('topbar_title', 'Dashboard')

@section('content')

{{-- ===== BARIS 1: KARTU STATISTIK UTAMA (6 kolom, compact) ===== --}}
<div class="db-stat-grid">

    <div class="db-stat-card">
        <div class="db-stat-icon" style="color:#2563eb;background:#dbeafe;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 18.5C19.25 18.5 20.5 17.38 20.5 16C20.5 14.62 19.25 13.5 18 13.5C16.75 13.5 15.5 14.62 15.5 16C15.5 17.38 16.75 18.5 18 18.5ZM19.5 9.5H17V12H21.46L19.5 9.5ZM6 18.5C7.25 18.5 8.5 17.38 8.5 16C8.5 14.62 7.25 13.5 6 13.5C4.75 13.5 3.5 14.62 3.5 16C3.5 17.38 4.75 18.5 6 18.5ZM20 8L23 12V17H21C21 18.66 19.66 20 18 20C16.34 20 15 18.66 15 17H9C9 18.66 7.66 20 6 20C4.34 20 3 18.66 3 17H1V6C1 4.9 1.9 4 3 4H17V8H20Z"/></svg>
        </div>
        <div class="db-stat-value">{{ $totalKendaraan }}</div>
        <div class="db-stat-label">Total Kendaraan</div>
    </div>

    <div class="db-stat-card">
        <div class="db-stat-icon" style="color:#16a34a;background:#dcfce7;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12L3.41 13.41L9 19L21 7L19.59 5.59L9 16.17Z"/></svg>
        </div>
        <div class="db-stat-value">{{ $kendaraanAktif }}</div>
        <div class="db-stat-label">Kendaraan Aktif</div>
    </div>

    <div class="db-stat-card">
        <div class="db-stat-icon" style="color:#0891b2;background:#cffafe;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm-1 14H5c-.55 0-1-.45-1-1V8h16v9c0 .55-.45 1-1 1z"/></svg>
        </div>
        <div class="db-stat-value">{{ $pajakAktif }}</div>
        <div class="db-stat-label">Pajak Berlaku</div>
    </div>

    <div class="db-stat-card">
        <div class="db-stat-icon" style="color:#dc2626;background:#fee2e2;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM7 11H17V13H7V11Z"/></svg>
        </div>
        <div class="db-stat-value">{{ $pajakMati }}</div>
        <div class="db-stat-label">Pajak Kadaluarsa</div>
    </div>

    <a href="{{ route('admin.kendaraan.index') }}" style="text-decoration:none;">
        <div class="db-stat-card" style="border-bottom:3px solid #f59e0b;">
            <div class="db-stat-icon" style="color:#d97706;background:#fef3c7;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M11.99 2C6.47 2 2 6.48 2 12C2 17.52 6.47 22 11.99 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 11.99 2ZM12 16.5C11.17 16.5 10.5 15.83 10.5 15C10.5 14.17 11.17 13.5 12 13.5C12.83 13.5 13.5 14.17 13.5 15C13.5 15.83 12.83 16.5 12 16.5ZM13 12H11V7H13V12Z"/></svg>
            </div>
            <div class="db-stat-value" style="color:#d97706;">{{ $pajakSegera }}</div>
            <div class="db-stat-label">Pajak Segera Habis <small style="color:#f59e0b;">≤ 30 hari</small></div>
        </div>
    </a>

    <div class="db-stat-card">
        <div class="db-stat-icon" style="color:#7c3aed;background:#ede9fe;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" stroke="currentColor" stroke-width="2"/><rect x="14" y="3" width="7" height="7" stroke="currentColor" stroke-width="2"/><rect x="3" y="14" width="7" height="7" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="17.5" r="2.5" fill="currentColor"/></svg>
        </div>
        <div class="db-stat-value" style="color:#7c3aed;">{{ number_format($totalScan) }}</div>
        <div class="db-stat-label">Total Scan QR</div>
    </div>

</div>

{{-- ===== BARIS 2: AKSI CEPAT ===== --}}
<div class="table-container" style="margin-bottom:18px;padding:14px 18px;">
    <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.7px;margin-bottom:12px;">Aksi Cepat</div>
    <div class="db-quick-actions">
        <a href="{{ route('admin.kendaraan.create') }}" class="quick-action-btn" style="color:#2563eb;">
            <div class="qa-icon" style="background:#dbeafe;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg></div>
            <span>Tambah Kendaraan</span>
        </a>
        <a href="{{ route('admin.qr-kendaraan.index') }}" class="quick-action-btn" style="color:#7c3aed;">
            <div class="qa-icon" style="background:#ede9fe;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" stroke="#7c3aed" stroke-width="2"/><rect x="14" y="3" width="7" height="7" stroke="#7c3aed" stroke-width="2"/><rect x="3" y="14" width="7" height="7" stroke="#7c3aed" stroke-width="2"/><circle cx="17.5" cy="17.5" r="2.5" fill="#7c3aed"/></svg></div>
            <span>Kelola QR</span>
        </a>
        <a href="{{ route('admin.pegawai.index') }}" class="quick-action-btn" style="color:#0891b2;">
            <div class="qa-icon" style="background:#cffafe;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0891b2" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            <span>Pegawai</span>
        </a>
        <a href="{{ route('admin.units.index') }}" class="quick-action-btn" style="color:#059669;">
            <div class="qa-icon" style="background:#d1fae5;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
            <span>Unit Kerja</span>
        </a>
        <a href="{{ route('admin.kelola-operator.index') }}" class="quick-action-btn" style="color:#d97706;">
            <div class="qa-icon" style="background:#fef3c7;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
            <span>Operator</span>
        </a>
        <a href="{{ route('admin.log.aktivitas') }}" class="quick-action-btn" style="color:#dc2626;">
            <div class="qa-icon" style="background:#fee2e2;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
            <span>Log Aktivitas</span>
        </a>
    </div>
</div>

{{-- ===== BARIS 3: GRID UTAMA ===== --}}
<div class="db-main-grid">

    {{-- LOG AKTIVITAS (kiri) --}}
    <section class="table-container activity-log-container" style="min-width:0;">
        <div class="table-header">
            <h3 class="table-title">Log Aktivitas</h3>
            <a href="{{ route('admin.log.aktivitas') }}" class="btn btn-sm btn-link" style="font-size:12px;">
                Semua <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left:2px;"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>
        <div class="activity-list">
            @forelse($recentLogs as $log)
                @php
                    $aksi = strtoupper($log['aksi'] ?? '');
                    $isCreate = str_contains($aksi, 'TAMBAH');
                    $isEdit   = str_contains($aksi, 'EDIT') || str_contains($aksi, 'UPDATE');
                    $isDelete = str_contains($aksi, 'HAPUS') || str_contains($aksi, 'DELETE');
                    $iconColor = $isCreate ? 'green' : ($isEdit ? 'blue' : ($isDelete ? 'red' : 'gray'));
                @endphp
                <div class="activity-item">
                    <div class="activity-icon {{ $iconColor }}">
                        @if($isCreate)  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        @elseif($isEdit) <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        @elseif($isDelete) <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                        @else <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        @endif
                    </div>
                    <div class="activity-content">
                        <div class="activity-desc">
                            <strong>{{ $log['user'] ?? 'Sistem' }}</strong>
                            melakukan <strong>{{ strtolower($log['aksi'] ?? 'aktivitas') }}</strong>
                            @if(!empty($log['modul'])) pada <strong>{{ $log['modul'] }}</strong>@endif
                        </div>
                        <div class="activity-meta">
                            <span class="activity-time">{{ $log['waktu'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div style="padding:20px;text-align:center;color:#94a3b8;font-size:12.5px;">Belum ada aktivitas.</div>
            @endforelse
        </div>
    </section>

    {{-- KONTEN TENGAH-KANAN --}}
    <div style="display:flex;flex-direction:column;gap:18px;min-width:0;overflow:hidden;">

        {{-- Kendaraan Terbaru --}}
        <section class="table-container">
            <div class="table-header">
                <h3 class="table-title">Kendaraan Terbaru</h3>
                <a href="{{ route('admin.kendaraan.index') }}" class="btn btn-sm btn-outline" style="font-size:12px;">Semua</a>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Plat / Nama</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Pemegang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kendaraanTerbaru as $k)
                        <tr>
                            <td>
                                <div style="font-weight:700;font-size:12.5px;">{{ $k->no_polisi }}</div>
                                <div style="font-size:11px;color:#64748b;">{{ $k->nama_kendaraan }}</div>
                            </td>
                            <td><span class="type-badge">{{ $k->kategori?->nama_kategori ?? '-' }}</span></td>
                            <td><span class="status-badge {{ $k->status === 'aktif' ? 'active' : 'inactive' }}">{{ strtoupper($k->status) }}</span></td>
                            <td>
                                @if($k->pemegangAktif?->pegawai)
                                    <div style="font-size:12px;font-weight:500;">{{ $k->pemegangAktif->pegawai->nama }}</div>
                                @else
                                    <span style="font-size:11.5px;color:#cbd5e1;">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty-state"><p>Belum ada data.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        {{-- Baris bawah: Distribusi Kategori (Donut) + QR Top + Pajak Segera --}}
        <div style="display:grid;grid-template-columns:repeat(3, minmax(0,1fr));gap:16px;min-width:0;overflow:hidden;">

            {{-- Distribusi Kategori — Donut visual (SVG) --}}
            <section class="table-container" style="padding:16px;">
                <div style="font-size:12px;font-weight:700;color:#334155;margin-bottom:14px;text-transform:uppercase;letter-spacing:0.5px;">Per Kategori</div>

                {{-- Donut Chart SVG --}}
                @php
                    $colors  = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4'];
                    $total   = $distribusiKategori->sum('kendaraans_count');
                    $offset  = 25;        // start from top
                    $r       = 15.9155;   // radius for 100 circumference
                @endphp
                <div style="display:flex;justify-content:center;margin-bottom:14px;">
                    <svg viewBox="0 0 36 36" width="100" height="100" style="transform:rotate(-90deg);">
                        <circle cx="18" cy="18" r="{{ $r }}" fill="none" stroke="#e2e8f0" stroke-width="3.5"/>
                        @foreach($distribusiKategori as $i => $kat)
                            @php
                                $pct    = $total > 0 ? ($kat->kendaraans_count / $total * 100) : 0;
                                $dash   = round($pct, 2);
                                $gap    = 100 - $dash;
                                $color  = $colors[$i % count($colors)];
                            @endphp
                            <circle cx="18" cy="18" r="{{ $r }}" fill="none"
                                stroke="{{ $color }}" stroke-width="3.5"
                                stroke-dasharray="{{ $dash }} {{ $gap }}"
                                stroke-dashoffset="{{ -$offset + 100 }}"
                                style="transition: stroke-dasharray 0.5s;"/>
                            @php $offset += $pct; @endphp
                        @endforeach
                    </svg>
                </div>

                {{-- Legenda --}}
                <div style="display:flex;flex-direction:column;gap:7px;">
                    @foreach($distribusiKategori as $i => $kat)
                        @php $pct = $total > 0 ? round($kat->kendaraans_count / $total * 100) : 0; @endphp
                        <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;">
                            <div style="display:flex;align-items:center;gap:6px;">
                                <span style="width:10px;height:10px;border-radius:2px;background:{{ $colors[$i % count($colors)] }};flex-shrink:0;"></span>
                                <span style="color:#334155;">{{ $kat->nama_kategori }}</span>
                            </div>
                            <span style="font-weight:700;color:#0f172a;">{{ $kat->kendaraans_count }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- QR Leaderboard --}}
            @if($topQr->count() > 0)
            <section class="table-container" style="padding:16px;">
                <div style="font-size:12px;font-weight:700;color:#334155;margin-bottom:14px;text-transform:uppercase;letter-spacing:0.5px;">QR Paling Dipindai</div>
                <div style="display:flex;flex-direction:column;gap:11px;">
                    @foreach($topQr as $idx => $qr)
                        <div style="display:flex;align-items:center;gap:9px;">
                            <div style="width:22px;height:22px;border-radius:50%;background:{{ $idx===0?'#fbbf24':($idx===1?'#9ca3af':($idx===2?'#cd8c5a':'#e2e8f0')) }};display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:800;color:#fff;flex-shrink:0;">{{ $idx+1 }}</div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-size:12.5px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $qr->kendaraan?->no_polisi ?? $qr->token }}</div>
                                <div style="font-size:11px;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $qr->kendaraan?->nama_kendaraan ?? '-' }}</div>
                            </div>
                            <span style="font-size:12.5px;font-weight:800;color:#7c3aed;">{{ number_format($qr->scan_count) }}×</span>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Pajak Segera Habis — Compact Card List --}}
            @if($daftarPajakSegera->count() > 0)
            <section class="table-container" style="padding:16px;border-top:3px solid #f59e0b;">
                <div style="font-size:12px;font-weight:700;color:#d97706;margin-bottom:14px;text-transform:uppercase;letter-spacing:0.5px;display:flex;align-items:center;gap:5px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="#f59e0b"><path d="M11.99 2C6.47 2 2 6.48 2 12C2 17.52 6.47 22 11.99 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 11.99 2ZM12 16.5C11.17 16.5 10.5 15.83 10.5 15C10.5 14.17 11.17 13.5 12 13.5C12.83 13.5 13.5 14.17 13.5 15C13.5 15.83 12.83 16.5 12 16.5ZM13 12H11V7H13V12Z"/></svg>
                    Pajak Segera Habis
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach($daftarPajakSegera as $k)
                        @php $sisa = \Carbon\Carbon::today()->diffInDays($k->pajak, false); @endphp
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;padding:9px 10px;border-radius:8px;background:#fffbeb;border:1px solid #fde68a;">
                            <div>
                                <div style="font-size:12.5px;font-weight:700;color:#0f172a;">{{ $k->no_polisi }}</div>
                                <div style="font-size:11px;color:#64748b;">{{ \Carbon\Carbon::parse($k->pajak)->isoFormat('D MMM Y') }}</div>
                            </div>
                            <span style="font-size:11.5px;font-weight:700;padding:2px 8px;border-radius:12px;background:{{ $sisa<=7?'#fee2e2':'#fef9c3' }};color:{{ $sisa<=7?'#dc2626':'#92400e' }};white-space:nowrap;">{{ $sisa }}h</span>
                        </div>
                    @endforeach
                </div>
            </section>
            @else
            <section class="table-container" style="padding:16px;display:flex;align-items:center;justify-content:center;text-align:center;">
                <div style="color:#94a3b8;font-size:12px;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" style="margin-bottom:6px;display:block;margin-inline:auto;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Semua pajak kendaraan masih aman.
                </div>
            </section>
            @endif

        </div>
    </div>
</div>

<style>
    /* ─── SUMMARY STAT CARDS ─── */
    .db-stat-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .db-stat-card {
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 14px 14px 12px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        transition: box-shadow 0.2s;
    }

    .db-stat-card:hover { box-shadow: 0 3px 12px rgba(0,0,0,0.07); }

    .db-stat-icon {
        width: 34px; height: 34px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
    }

    .db-stat-value {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
    }

    .db-stat-label {
        font-size: 11.5px;
        color: #64748b;
        font-weight: 500;
        line-height: 1.3;
    }

    .db-stat-label small { font-size: 10px; display: block; }

    /* ─── AKSI CEPAT ─── */
    .db-quick-actions { display: flex; flex-wrap: wrap; gap: 8px; }

    .quick-action-btn {
        display: flex; align-items: center; gap: 8px;
        padding: 8px 14px;
        border-radius: 8px; border: 1px solid #e2e8f0;
        background: #fff; text-decoration: none;
        font-size: 12.5px; font-weight: 600;
        transition: all 0.2s;
        flex: 1; min-width: 130px;
    }

    .quick-action-btn:hover { background: #f8fafc; box-shadow: 0 2px 8px rgba(0,0,0,0.06); transform: translateY(-1px); }

    .qa-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    /* ─── MAIN GRID ─── */
    .db-main-grid {
        display: grid;
        grid-template-columns: 260px minmax(0, 1fr);
        gap: 18px;
        align-items: start;
        min-width: 0;
    }

    /* ─── LOG AKTIVITAS ─── */
    .activity-list {
        display: flex; flex-direction: column;
        gap: 0.8rem; max-height: 430px;
        overflow-y: auto; padding-right: 2px;
    }

    .activity-list::-webkit-scrollbar { width: 4px; }
    .activity-list::-webkit-scrollbar-track { background: #f8fafc; }
    .activity-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

    .activity-item {
        display: flex; gap: 9px;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .activity-item:last-child { border-bottom: none; padding-bottom: 0; }

    .activity-icon {
        width: 26px; height: 26px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        background: #f1f5f9; color: #64748b;
    }

    .activity-icon.green { background: #dcfce7; color: #16a34a; }
    .activity-icon.blue  { background: #dbeafe; color: #2563eb; }
    .activity-icon.red   { background: #fee2e2; color: #dc2626; }
    .activity-icon.gray  { background: #f1f5f9; color: #64748b; }

    .activity-content { min-width: 0; }

    .activity-desc {
        font-size: 12px; color: #334155;
        font-weight: 400; line-height: 1.45;
    }

    .activity-meta { display: flex; gap: 4px; }
    .activity-user { font-size: 11px; font-weight: 600; color: #64748b; }
    .activity-time { font-size: 10.5px; color: #94a3b8; }

    /* ─── RESPONSIVE ─── */
    @media (max-width: 1100px) {
        .db-stat-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .db-main-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 640px) {
        .db-stat-grid { grid-template-columns: repeat(2, 1fr); }
        .quick-action-btn { min-width: 100%; }
    }
</style>

@endsection