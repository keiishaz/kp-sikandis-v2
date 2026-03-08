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
        <div style="padding: var(--spacing-lg); background: #f8fafc; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
            
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

            <!-- Tab 2: Riwayat Pemegang (Placeholder) -->
            <div id="tab-pemegang" class="tab-content" style="display: none;">
                <div style="background: #fff; padding: 40px; border-radius: 8px; border: 1px dashed var(--gray-300); text-align: center;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:var(--gray-400); margin-bottom:16px;">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <h4 style="margin:0 0 8px; color:var(--gray-700); font-size:16px;">Modul Riwayat Pemegang Belum Diaktifkan</h4>
                    <p style="margin:0; color:var(--gray-500); font-size:13px;">Data penugasan dan serah terima pemegang akan tampil di sini.</p>
                </div>
            </div>

            <!-- Tab 3: Riwayat Aktivitas Kendaraan (Placeholder) -->
            <div id="tab-aktivitas" class="tab-content" style="display: none;">
                <div style="background: #fff; padding: 40px; border-radius: 8px; border: 1px dashed var(--gray-300); text-align: center;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:var(--gray-400); margin-bottom:16px;">
                        <circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <h4 style="margin:0 0 8px; color:var(--gray-700); font-size:16px;">Riwayat Servis / BBM Belum Tersedia</h4>
                    <p style="margin:0; color:var(--gray-500); font-size:13px;">Jejak rekam aktivitas perawatan kendaraan akan dilacak melalui tab ini.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active classes
                tabBtns.forEach(b => {
                    b.classList.remove('active');
                    b.style.color = 'var(--gray-500)';
                    b.style.borderBottomColor = 'transparent';
                });
                tabContents.forEach(c => {
                    c.style.display = 'none';
                });

                // Add active to clicked
                btn.classList.add('active');
                btn.style.color = 'var(--primary-color)';
                btn.style.borderBottomColor = 'var(--primary-color)';

                const targetId = btn.getAttribute('data-target');
                document.getElementById(targetId).style.display = 'block';
            });
        });
    });
</script>
@endsection
