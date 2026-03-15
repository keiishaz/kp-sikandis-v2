@extends('layouts.admin')

@section('title', 'Dashboard Admin — SIKANDIS')
@section('topbar_title', 'Dashboard')

@section('content')
<div class="dashboard">

    {{-- BAGIAN 1 — PAGE INTRO --}}
    <div class="page-intro">
        <div>
            <h2 class="page-heading">Dashboard</h2>
            <p class="page-subheading">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    {{-- BAGIAN 2 — METRICS GRID --}}
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-label">Total Kendaraan</div>
                <div class="metric-icon metric-icon--blue">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                </div>
            </div>
            <div class="metric-value">{{ $totalKendaraan }}</div>
            <div class="metric-footer">
                <span class="metric-trend metric-trend--neutral">-</span>
                <span class="metric-trend-label">Total tercatat</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-label">Kendaraan Aktif</div>
                <div class="metric-icon metric-icon--green">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
            </div>
            <div class="metric-value">{{ $kendaraanAktif }}</div>
            <div class="metric-footer">
                <span class="metric-trend metric-trend--up">Aktif</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-label">Pajak Berlaku</div>
                <div class="metric-icon metric-icon--green">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
            </div>
            <div class="metric-value">{{ $pajakAktif ?? 0 }}</div>
            <div class="metric-footer">
                <span class="metric-trend metric-trend--up">Aman</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-label">Pajak Kadaluarsa</div>
                <div class="metric-icon metric-icon--red">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                </div>
            </div>
            <div class="metric-value text-danger" style="color: var(--danger-text);">{{ $pajakMati ?? 0 }}</div>
            <div class="metric-footer">
                <span class="metric-trend metric-trend--down">Mati</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-label">Pajak Segera Habis</div>
                <div class="metric-icon metric-icon--yellow">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                </div>
            </div>
            <div class="metric-value text-warning" style="color: var(--warning-text);">{{ $pajakSegera ?? 0 }}</div>
            <div class="metric-footer">
                <span class="metric-trend metric-trend--neutral" style="background: var(--warning-bg); color: var(--warning-text);">≤ 30 hr</span>
                <span class="metric-trend-label">Perlu dicek</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-label">Total Scan QR</div>
                <div class="metric-icon metric-icon--purple">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><path d="M14 14h7v7h-7z"></path></svg>
                </div>
            </div>
            <div class="metric-value">{{ number_format($totalScan ?? 0) }}</div>
            <div class="metric-footer">
                <span class="metric-trend-label">Dari stiker QR public</span>
            </div>
        </div>
    </div>

    {{-- BAGIAN 3 — QUICK ACTIONS --}}
    <div class="card card-body-flush">
        <div class="quick-actions">
            <div class="quick-actions-inner">
                <a href="{{ route('admin.kendaraan.create') }}" class="quick-action-item">
                    <div class="quick-action-icon" style="background: var(--brand-50); color: var(--brand-600);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    </div>
                    Tambah Kendaraan
                </a>
                <a href="{{ route('admin.qr-kendaraan.index') }}" class="quick-action-item">
                    <div class="quick-action-icon" style="background: #F5F3FF; color: #7C3AED;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><path d="M14 14h7v7h-7z"></path></svg>
                    </div>
                    Kelola QR
                </a>
                <a href="{{ route('admin.pegawai.index') }}" class="quick-action-item">
                    <div class="quick-action-icon" style="background: #ECFEFF; color: #0891B2;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    Pegawai
                </a>
                <a href="{{ route('admin.units.index') }}" class="quick-action-item">
                    <div class="quick-action-icon" style="background: #D1FAE5; color: #059669;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    Unit Kerja
                </a>
                <a href="{{ route('admin.kelola-operator.index') }}" class="quick-action-item">
                    <div class="quick-action-icon" style="background: #FEF3C7; color: #D97706;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    Operator
                </a>
                <a href="{{ route('admin.log.aktivitas') }}" class="quick-action-item">
                    <div class="quick-action-icon" style="background: #FEE2E2; color: #DC2626;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    Log Aktivitas
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
                    <h3 class="card-title">Kendaraan Terbaru</h3>
                    <a href="{{ route('admin.kendaraan.index') }}" class="btn btn-sm btn-ghost">Lihat semua &rarr;</a>
                </div>
                <div class="card-body-flush table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Plat Nomor</th>
                                <th>Nama Kendaraan</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Pemegang</th>
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
                                    <td>
                                        <span class="badge {{ $k->status === 'aktif' ? 'badge-success' : 'badge-danger' }}">
                                            {{ strtoupper($k->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($k->pemegangAktif?->pegawai)
                                            <div class="cell-primary">{{ $k->pemegangAktif->pegawai->nama }}</div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                            </div>
                                            <div class="empty-state-title">Belum ada kendaraan</div>
                                            <div class="empty-state-text">Data kendaraan terbaru akan muncul di sini.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Distribusi per Kategori --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribusi Kategori</h3>
                </div>
                <div class="card-body">
                    <div class="donut-layout">
                        @php
                            $colors  = ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6','#06B6D4'];
                            $total   = $distribusiKategori->sum('kendaraans_count');
                            $offset  = 25;
                        @endphp
                        <div class="donut-chart">
                            <svg viewBox="0 0 36 36" width="160" height="160" style="transform:rotate(-90deg);">
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="var(--n-100)" stroke-width="4"/>
                                @foreach($distribusiKategori as $i => $kat)
                                    @php
                                        $pct    = $total > 0 ? ($kat->kendaraans_count / $total * 100) : 0;
                                        $dash   = round($pct, 2);
                                        $gap    = 100 - $dash;
                                        $color  = $colors[$i % count($colors)];
                                    @endphp
                                    @if($pct > 0)
                                        <circle cx="18" cy="18" r="15.9155" fill="none"
                                            stroke="{{ $color }}" stroke-width="4"
                                            stroke-dasharray="{{ $dash }} {{ $gap }}"
                                            stroke-dashoffset="{{ -$offset + 100 }}" />
                                    @endif
                                    @php $offset += $pct; @endphp
                                @endforeach
                            </svg>
                        </div>
                        <div class="donut-legend">
                            @foreach($distribusiKategori as $i => $kat)
                                <div class="donut-legend-item">
                                    <div style="display:flex; align-items:center;">
                                        <div class="legend-color-dot" style="background: {{ $colors[$i % count($colors)] }};"></div>
                                        <div class="legend-item-name">{{ $kat->nama_kategori }}</div>
                                    </div>
                                    <div class="legend-item-count">{{ $kat->kendaraans_count }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tren Scan QR --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tren Scan QR (Tahun Ini)</h3>
                </div>
                <div class="card-body">
                    <div id="qrScanChart" style="min-height: 300px;"></div>
                </div>
            </div>
        </div>

        <div class="dashboard-col-side">
            {{-- Aktivitas Terbaru --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aktivitas Terbaru</h3>
                    <a href="{{ route('admin.log.aktivitas') }}" class="btn btn-sm btn-ghost">Semua</a>
                </div>
                <div class="card-body" style="padding: 12px 0;">
                    <div class="activity-feed" style="max-height: 420px; overflow-y: auto; padding: 0 12px;">
                        @forelse($recentLogs as $log)
                            @php
                                $aksi = strtoupper($log['aksi'] ?? '');
                                $isCreate = str_contains($aksi, 'TAMBAH');
                                $isEdit   = str_contains($aksi, 'EDIT') || str_contains($aksi, 'UPDATE');
                                $isDelete = str_contains($aksi, 'HAPUS') || str_contains($aksi, 'DELETE');
                                $dotColor = $isCreate ? 'activity-dot--create' : ($isEdit ? 'activity-dot--edit' : ($isDelete ? 'activity-dot--delete' : 'activity-dot--default'));
                            @endphp
                            <div class="activity-item">
                                <div class="activity-dot {{ $dotColor }}">
                                    @if($isCreate) 
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    @elseif($isEdit) 
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    @elseif($isDelete) 
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                                    @else 
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                    @endif
                                </div>
                                <div class="activity-body">
                                    <div class="activity-text">
                                        <strong>{{ $log['user'] ?? 'Sistem' }}</strong> {{ strtolower($log['aksi'] ?? '') }} @if(!empty($log['modul'])) <strong>{{ $log['modul'] }}</strong> @endif
                                    </div>
                                    <div class="activity-time">{{ $log['waktu'] ?? '-' }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state" style="padding: 24px;">
                                <div class="empty-state-text">Belum ada aktivitas</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Pajak Segera Habis --}}
            <div class="card card-accent-warning">
                <div class="card-header">
                    <h3 class="card-title">Pajak Segera Habis</h3>
                    @if($daftarPajakSegera->count() > 0)
                        <span class="badge badge-warning">{{ $daftarPajakSegera->count() }} unit</span>
                    @endif
                </div>
                <div class="card-body-flush">
                    @forelse($daftarPajakSegera as $k)
                        @php $sisa = \Carbon\Carbon::today()->diffInDays($k->pajak, false); @endphp
                        <div class="pajak-item">
                            <div class="pajak-item-info">
                                <div class="pajak-name">{{ $k->nama_kendaraan }}</div>
                                <div class="pajak-plat">
                                    <span class="plat-badge" style="background: transparent; border: none; padding: 0; font-size: 10.5px; color: var(--n-500);">{{ $k->no_polisi }}</span>
                                    &bull; {{ \Carbon\Carbon::parse($k->pajak)->isoFormat('D MMM Y') }}
                                </div>
                            </div>
                            <div class="days-tag" style="background: {{ $sisa <= 7 ? 'var(--danger-bg)' : 'var(--warning-bg)' }}; color: {{ $sisa <= 7 ? 'var(--danger-text)' : 'var(--warning-text)' }};">
                                {{ $sisa }}h
                            </div>
                        </div>
                    @empty
                        <div class="empty-state" style="padding: 32px 16px;">
                            <div class="empty-state-icon" style="color: var(--success-icon);">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <div class="empty-state-text" style="color: var(--n-500);">Semua pajak kendaraan aman</div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Top QR --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top QR Dipindai</h3>
                </div>
                <div class="card-body-flush" style="padding: 10px 0;">
                    @forelse($topQr as $idx => $qr)
                        <div class="qr-rank-item">
                            <div class="rank-badge rank-{{ $idx < 3 ? $idx + 1 : 'other' }}">
                                {{ $idx + 1 }}
                            </div>
                            <div style="flex:1; min-width:0;">
                                <div style="font-size:13px;font-weight:600;color:var(--n-900);">{{ $qr->kendaraan?->no_polisi ?? $qr->token }}</div>
                                <div style="font-size:11.5px;color:var(--n-500); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $qr->kendaraan?->nama_kendaraan ?? '-' }}</div>
                            </div>
                            <div class="qr-scan-count">{{ number_format($qr->scan_count) }}x</div>
                        </div>
                    @empty
                        <div class="empty-state" style="padding: 24px;">
                            <div class="empty-state-text">Belum ada pemindaian QR</div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector("#qrScanChart")) {
            var options = {
                series: [{
                    name: 'Scan QR',
                    data: {!! json_encode($qrChartData ?? []) !!}
                }],
                chart: {
                    height: 300,
                    type: 'area',
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                colors: ['#7C3AED'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: {
                    categories: {!! json_encode($qrChartBulan ?? []) !!},
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: { formatter: function (val) { return Math.floor(val); } }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                }
            };
            var chart = new ApexCharts(document.querySelector("#qrScanChart"), options);
            chart.render();
        }
    });
</script>
@endpush