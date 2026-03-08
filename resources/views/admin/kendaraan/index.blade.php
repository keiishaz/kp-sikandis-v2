@extends('layouts.admin')

@section('title', 'Manajemen Kendaraan - SIKANDIS')
@section('topbar_title', 'Data Kendaraan')

@section('content')
    {{-- Toast Notification --}}
    @if(session('success') || session('error'))
        <div id="toast-notification" class="toast-notification {{ session('success') ? 'toast-success' : 'toast-error' }}">
            @if(session('success'))
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            @else
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            @endif
            <span style="font-size:14px;font-weight:500">{{ session('success') ?? session('error') }}</span>
            <button onclick="document.getElementById('toast-notification').remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;opacity:.6;padding:0;">✕</button>
        </div>
        <script>setTimeout(()=>{const t=document.getElementById('toast-notification');if(t)t.remove();},4000);</script>
    @endif

    {{-- Statistik Mini Rata Kiri Kanan --}}
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom: 24px;">
        <div style="background:#fff; border:1px solid var(--gray-200); border-radius:10px; padding:20px; box-shadow:0 2px 4px rgba(0,0,0,0.02); display:flex; justify-content:space-between; align-items:center;">
            <div>
                <div style="font-size:28px; font-weight:700; color:var(--gray-800); line-height:1.2;">{{ $countAktif }}</div>
                <div style="font-size:13px; font-weight:500; color:var(--gray-500); margin-top:4px;">Total Kendaraan Aktif</div>
            </div>
            <div style="background:#10b98115; color:#10b981; padding:14px; border-radius:12px;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"></path><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
        </div>
        
        <div style="background:#fff; border:1px solid var(--gray-200); border-radius:10px; padding:20px; box-shadow:0 2px 4px rgba(0,0,0,0.02); display:flex; justify-content:space-between; align-items:center;">
            <div>
                <div style="font-size:28px; font-weight:700; color:var(--gray-800); line-height:1.2;">{{ $countNonaktif }}</div>
                <div style="font-size:13px; font-weight:500; color:var(--gray-500); margin-top:4px;">Total Kendaraan Nonaktif</div>
            </div>
            <div style="background:#ef444415; color:#ef4444; padding:14px; border-radius:12px;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
            </div>
        </div>
    </div>

    <!-- Wrapper List Aktif/Nonaktif menempel dengan Card tanpa space -->
    <div style="display:flex; gap:4px; position:relative; z-index:1; margin-left:16px;">
        <a href="{{ route('admin.kendaraan.index', ['status' => 'aktif', 'q' => request('q')]) }}" 
           style="padding:14px 28px; font-size:14px; font-weight:700; text-decoration:none; border-top-left-radius:8px; border-top-right-radius:8px; border:1px solid {{ $status === 'aktif' ? 'var(--primary-color)' : 'var(--gray-200)' }}; border-bottom:none; {{ $status === 'aktif' ? 'background:var(--primary-color); color:#fff; border-bottom:none;' : 'background:#f1f5f9; color:var(--gray-500);' }} transition:all 0.2s ease;">
           Kendaraan Aktif
        </a>
        <a href="{{ route('admin.kendaraan.index', ['status' => 'nonaktif', 'q' => request('q')]) }}" 
           style="padding:14px 28px; font-size:14px; font-weight:700; text-decoration:none; border-top-left-radius:8px; border-top-right-radius:8px; border:1px solid {{ $status === 'nonaktif' ? '#ef4444' : 'var(--gray-200)' }}; border-bottom:none; {{ $status === 'nonaktif' ? 'background:#ef4444; color:#fff; border-bottom:none;' : 'background:#f1f5f9; color:var(--gray-500);' }} transition:all 0.2s ease;">
           Kendaraan Nonaktif
        </a>
    </div>

    <!-- Main Card Body, top border radius ditiadakan agar menempel list tab -->
    <div style="background:#fff; border:1px solid var(--gray-200); border-radius:10px; border-top-left-radius: 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05); overflow:hidden;">
        
        <!-- Toolbar Action Bar -->
        <div style="padding: 16px 24px; border-bottom: 1px solid var(--gray-200); display:flex; justify-content:space-between; align-items:center; background:#fff;">
            <h3 style="margin:0; font-size:16px; font-weight:700; color:var(--gray-800);">Daftar Kendaraan {{ ucfirst($status) }}</h3>
            <div style="display:flex; gap:12px;">
                <form action="{{ route('admin.kendaraan.index') }}" method="GET" style="display: flex; gap: 8px;">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <div style="position:relative;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:12px; top:11px; color:var(--gray-400);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="search" name="q" placeholder="Cari kendaraan..." value="{{ request('q') }}" style="height: 36px; padding: 0 12px 0 34px; border: 1px solid var(--gray-300); border-radius: 6px; outline: none; width: 220px; font-size:13.5px; font-family:inherit; transition:border 0.2s;" onfocus="this.style.borderColor='var(--primary-color)'" onblur="this.style.borderColor='var(--gray-300)'">
                    </div>
                    <button type="submit" class="btn btn-outline" style="height: 36px; display:inline-flex; align-items:center; justify-content:center; padding: 0 14px; border-color:var(--gray-300); font-weight:600; font-size:13.5px;">Cari</button>
                </form>
                <a href="{{ route('admin.kendaraan.create') }}" class="btn btn-primary" style="height: 36px; display: inline-flex; align-items: center; padding: 0 16px; font-weight:600; font-size:13.5px; box-shadow:0 2px 4px rgba(37,99,235,0.2);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Data
                </a>
            </div>
        </div>

        <!-- Modern List Layout instead of Data Table -->
        <div style="padding: 16px; display: flex; flex-direction: column; gap: 10px; background:#f8fafc;">
            @forelse($kendaraans as $kendaraan)
                <!-- Individual Item Row -->
                <div style="background:#fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 2px rgba(0,0,0,0.02); transition: all 0.2s ease;" onmouseover="this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.05)'; this.style.borderColor='#cbd5e1';" onmouseout="this.style.boxShadow='0 1px 2px rgba(0,0,0,0.02)'; this.style.borderColor='#e2e8f0';">
                    
                    <div style="display: flex; gap: 16px; align-items: center;">
                        <!-- Ikon Kendaraan -->
                        <div style="width: 40px; height: 40px; border-radius: 8px; background: {{ $status === 'aktif' ? '#eff6ff' : '#f1f5f9' }}; color: {{ $status === 'aktif' ? 'var(--primary-color)' : '#64748b' }}; display: flex; align-items: center; justify-content: center; flex-shrink:0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                        </div>
                        
                        <div>
                            <h4 style="margin: 0 0 4px; font-size: 14.5px; font-weight: 700; color: #0f172a; letter-spacing: -0.1px;">{{ $kendaraan->nama_kendaraan }}</h4>
                            <div style="display: flex; gap: 12px; align-items: center; font-size: 12.5px; color: #475569;">
                                <!-- Plat Nopol -->
                                <span style="font-weight: 700; color: #1e293b; background:#f1f5f9; padding:2px 6px; border-radius:4px; letter-spacing:0.5px; border:1px solid #e2e8f0; font-family: monospace; font-size: 11.5px;">{{ $kendaraan->no_polisi }}</span>
                                
                                <span style="color:#cbd5e1;">•</span>
                                <!-- Icon Kategori Diganti jadi Tag -->
                                <span style="font-weight: 600; display:flex; align-items:center; gap:4px;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                                    {{ $kendaraan->kategori->nama_kategori ?? '-' }}
                                </span>
                                
                                <span style="color:#cbd5e1;">•</span>
                                <!-- Label Pajak Tracker Realtime dari Controller -->
                                @php
                                    $bgPajak = match($kendaraan->color_pajak) {
                                        'red' => '#fef2f2', 'yellow' => '#fefce8', 'green' => '#f0fdf4', default => '#f8fafc'
                                    };
                                    $borderPajak = match($kendaraan->color_pajak) {
                                        'red' => '#fecaca', 'yellow' => '#fde047', 'green' => '#bbf7d0', default => '#e2e8f0'
                                    };
                                    $textPajak = match($kendaraan->color_pajak) {
                                        'red' => '#dc2626', 'yellow' => '#a16207', 'green' => '#16a34a', default => '#64748b'
                                    };
                                    
                                    $iconPajak = match($kendaraan->color_pajak) {
                                        'red' => '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line>',
                                        'yellow' => '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
                                        default => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>'
                                    };
                                @endphp
                                <span style="background:{{ $bgPajak }}; color:{{ $textPajak }}; border:1px solid {{ $borderPajak }}; padding:2px 6px; border-radius:4px; font-weight:700; font-size:11.5px; display:flex; align-items:center; gap:4px;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">{!! $iconPajak !!}</svg>
                                    Pajak: {{ $kendaraan->status_pajak }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; gap:8px;">
                        <a href="{{ route('admin.kendaraan.show', $kendaraan->id) }}" class="btn-action" title="Detail Kendaraan" style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; background:#f8fafc; color:var(--primary-color); border-radius:6px; border:1px solid #e2e8f0; transition:all 0.2s;" onmouseover="this.style.background='#eff6ff'; this.style.borderColor='#bfdbfe';" onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0';">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </a>
                        <a href="{{ route('admin.kendaraan.edit', $kendaraan->id) }}" class="btn-action" title="Edit Kendaraan" style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; background:#f8fafc; color:var(--primary-color); border-radius:6px; border:1px solid #e2e8f0; transition:all 0.2s;" onmouseover="this.style.background='#eff6ff'; this.style.borderColor='#bfdbfe';" onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0';">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </a>
                        <form action="{{ route('admin.kendaraan.destroy', $kendaraan->id) }}" method="POST" class="d-inline" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="var form = this.closest('form'); SIKANDIS.confirm({title: 'Konfirmasi', message: '{{ $kendaraan->status === 'aktif' ? 'Nonaktifkan kendaraan ini?' : 'Aktifkan kendaraan ini?' }}', confirmText: 'Ya, Lanjutkan', cancelText: 'Batal', type: 'warning'}).then(function(res) { if(res) form.submit(); })" class="btn-action" style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; background:{{ $kendaraan->status === 'aktif' ? '#fef2f2' : '#f0fdf4' }}; color:{{ $kendaraan->status === 'aktif' ? '#dc2626' : '#16a34a' }}; border:1px solid {{ $kendaraan->status === 'aktif' ? '#fecaca' : '#bbf7d0' }}; border-radius:6px; cursor:pointer; transition:all 0.2s;" title="{{ $kendaraan->status === 'aktif' ? 'Nonaktifkan Kendaraan' : 'Aktifkan Kendaraan' }}" onmouseover="this.style.filter='brightness(0.95)';" onmouseout="this.style.filter='none';">
                                @if($kendaraan->status === 'aktif')
                                    <!-- Deactivate Power Icon -->
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                                @else
                                    <!-- Activate Check Icon -->
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                @endif
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <div style="padding: 60px 20px; text-align: center; border: 1px dashed var(--gray-300); border-radius: 12px; background:#fff;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:16px;">
                        <rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle>
                    </svg>
                    <h5 style="margin:0 0 8px; font-size:16px; color:var(--gray-600);">Tidak ada data kendaraan ditemukan</h5>
                    <p style="margin:0; font-size:14px; color:var(--gray-400);">Cobalah membuat kata kunci pencarian baru atau klik Tambah Data untuk inisialisasi awal.</p>
                </div>
            @endforelse
        </div>

        <div style="padding: 16px 24px; border-top: 1px solid var(--gray-200); background:#fff;">
            {{ $kendaraans->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
