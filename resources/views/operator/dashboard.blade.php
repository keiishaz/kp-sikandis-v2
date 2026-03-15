@extends('layouts.admin')

@section('title', 'Dashboard Operator — SIKANDIS')
@section('topbar_title', 'Dashboard Operator')

@section('content')
<div class="dashboard">

    {{-- BAGIAN 1 — PAGE INTRO --}}
    <div class="page-intro">
        <div>
            <h2 class="page-heading">Dashboard</h2>
            <p class="page-subheading">Selamat datang. Berikut ringkasan data kendaraan aktif.</p>
        </div>
    </div>

    {{-- BAGIAN 2 — METRICS GRID --}}
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-label">Total Kendaraan Aktif</div>
                <div class="metric-icon metric-icon--blue">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                </div>
            </div>
            <div class="metric-value">{{ $totalKendaraan ?? 0 }}</div>
            <div class="metric-footer">
                <span class="metric-trend metric-trend--up">Valid</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-label">Pajak Hampir Habis</div>
                <div class="metric-icon metric-icon--yellow">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                </div>
            </div>
            <div class="metric-value" style="color: {{ ($pajakHampirHabis ?? 0) > 0 ? 'var(--warning-text)' : 'var(--n-900)' }};">{{ $pajakHampirHabis ?? 0 }}</div>
            <div class="metric-footer">
                <span class="metric-trend metric-trend--neutral" style="background: var(--warning-bg); color: var(--warning-text);">≤ 30 hr</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-label">Pajak Kadaluarsa</div>
                <div class="metric-icon metric-icon--red">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                </div>
            </div>
            <div class="metric-value" style="color: {{ ($pajakKadaluarsa ?? 0) > 0 ? 'var(--danger-text)' : 'var(--n-900)' }};">{{ $pajakKadaluarsa ?? 0 }}</div>
             <div class="metric-footer">
                <span class="metric-trend metric-trend--down">Mati</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-label">Tanpa Pemegang</div>
                <div class="metric-icon metric-icon--purple">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
            </div>
            <div class="metric-value" style="color: {{ ($tanpaPemegang ?? 0) > 0 ? '#7C3AED' : 'var(--n-900)' }};">{{ $tanpaPemegang ?? 0 }}</div>
            <div class="metric-footer">
                <span class="metric-trend-label">Belum dialokasikan</span>
            </div>
        </div>
    </div>

    {{-- BAGIAN 3 — QUICK ACTIONS --}}
    <div class="card card-body-flush">
        <div class="quick-actions">
            <div class="quick-actions-inner">
                <a href="{{ route('operator.kendaraan.create') }}" class="quick-action-item">
                    <div class="quick-action-icon" style="background: var(--brand-50); color: var(--brand-600);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    </div>
                    Tambah Kendaraan
                </a>
                <a href="{{ route('operator.kendaraan.index') }}" class="quick-action-item">
                    <div class="quick-action-icon" style="background: #F5F3FF; color: #7C3AED;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    </div>
                    Data Kendaraan
                </a>
                <a href="{{ route('operator.pegawai.index') }}" class="quick-action-item">
                    <div class="quick-action-icon" style="background: #ECFEFF; color: #0891B2;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                    </div>
                    Data Pegawai
                </a>
                <a href="{{ route('operator.units.index') }}" class="quick-action-item">
                    <div class="quick-action-icon" style="background: #D1FAE5; color: #059669;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                    </div>
                    Unit Kerja
                </a>
            </div>
        </div>
    </div>

    {{-- BAGIAN 4 — DASHBOARD GRID --}}
    <div class="dashboard-grid">
        <div class="dashboard-col-main">
            {{-- Kendaraan Terbaru --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kendaraan Baru Terdaftar</h3>
                    <a href="{{ route('operator.kendaraan.index') }}" class="btn btn-sm btn-ghost">Lihat semua &rarr;</a>
                </div>
                <div class="card-body-flush table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Plat Nomor</th>
                                <th>Nama Kendaraan</th>
                                <th>Kategori</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kendaraanTerbaru as $k)
                                <tr>
                                    <td><span class="plat-badge">{{ $k->no_polisi }}</span></td>
                                    <td>
                                        <div class="cell-primary">{{ $k->nama_kendaraan }}</div>
                                    </td>
                                    <td><span class="badge badge-neutral">{{ $k->kategori?->nama_kategori ?? '-' }}</span></td>
                                    <td class="action-cell">
                                        <a href="{{ route('operator.kendaraan.show', $k->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                            </div>
                                            <div class="empty-state-title">Belum ada kendaraan</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="dashboard-col-side">
            {{-- Peringatan Pajak --}}
            <div class="card card-accent-warning">
                <div class="card-header">
                    <h3 class="card-title">⚠️ Pajak Hampir / Sudah Habis</h3>
                </div>
                <div class="card-body-flush">
                    @forelse($kendaraanPajakWarning as $k)
                        @php $isKritis = $k->sisa_hari <= 7; @endphp
                        <div class="pajak-item" style="{{ $isKritis ? 'background: #FEF2F2;' : '' }}">
                            <div class="pajak-item-info">
                                <div class="pajak-name">{{ $k->nama_kendaraan }}</div>
                                <div class="pajak-plat">
                                    <span class="plat-badge" style="background: transparent; border: none; padding: 0; font-size: 10.5px; color: var(--n-500);">{{ $k->no_polisi }}</span>
                                </div>
                            </div>
                            <div class="days-tag" style="background: {{ $isKritis ? 'var(--danger-bg)' : 'var(--warning-bg)' }}; color: {{ $isKritis ? 'var(--danger-text)' : 'var(--warning-text)' }}; border: 1px solid {{ $isKritis ? 'var(--danger-border)' : 'var(--warning-border)' }};">
                                {{ $k->sisa_hari }} hari
                            </div>
                        </div>
                    @empty
                        <div class="empty-state" style="padding: 32px 16px;">
                            <div class="empty-state-icon" style="color: var(--success-icon);">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <div class="empty-state-text" style="color: var(--n-500);">Semua pajak kendaraan masih aman.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection