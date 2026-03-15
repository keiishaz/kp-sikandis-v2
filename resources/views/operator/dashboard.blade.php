@extends('layouts.admin')

@section('title', 'Dashboard - SIKANDIS')
@section('topbar_title', 'Dashboard Operator')

@section('content')
<style>
.op-stat-grid { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 14px; margin-bottom: 20px; }
.op-stat-card { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:14px 18px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
.op-stat-val { font-size:22px; font-weight:700; color:#0f172a; line-height:1.2; }
.op-stat-lbl { font-size:12px; font-weight:500; color:#64748b; margin-top:3px; }
.op-stat-icon { border-radius:10px; padding:10px; display:flex; align-items:center; justify-content:center; }
.op-grid { display:grid; grid-template-columns: 1fr 1fr; gap:16px; }
.op-card { background:#fff; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
.op-card-header { padding:14px 18px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; }
.op-card-title { font-size:13.5px; font-weight:600; color:#0f172a; }
.op-card-body { padding:0; }
.op-list-item { display:flex; align-items:center; justify-content:space-between; padding:10px 18px; border-bottom:1px solid #f8fafc; font-size:13px; }
.op-list-item:last-child { border-bottom:none; }
.op-quick-grid { display:grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap:10px; padding:14px; }
.op-quick-btn { display:flex; flex-direction:column; align-items:center; gap:8px; padding:14px 10px; border-radius:8px; border:1px solid #e2e8f0; background:#f8fafc; text-decoration:none; color:#334155; font-size:12px; font-weight:500; transition:all 0.2s; text-align:center; }
.op-quick-btn:hover { background:#eff6ff; border-color:#bfdbfe; color:#2563eb; transform:translateY(-1px); }
@media(max-width:768px){.op-stat-grid{grid-template-columns:1fr 1fr;}.op-grid{grid-template-columns:1fr;}.op-quick-grid{grid-template-columns:repeat(2,1fr);}}
</style>

{{-- Flash Messages --}}
@if(session('success'))
<div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:13.5px;display:flex;align-items:center;gap:10px;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    {{ session('success') }}
</div>
@endif

{{-- Stat Cards --}}
<div class="op-stat-grid">
    <div class="op-stat-card">
        <div>
            <div class="op-stat-val">{{ $totalKendaraan }}</div>
            <div class="op-stat-lbl">Kendaraan Aktif</div>
        </div>
        <div class="op-stat-icon" style="background:#eff6ff; color:#3b82f6;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
        </div>
    </div>
    <div class="op-stat-card">
        <div>
            <div class="op-stat-val" style="color:{{ $pajakHampirHabis > 0 ? '#f59e0b' : '#0f172a' }};">{{ $pajakHampirHabis }}</div>
            <div class="op-stat-lbl">Pajak Hampir Habis</div>
        </div>
        <div class="op-stat-icon" style="background:#fffbeb; color:#f59e0b;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        </div>
    </div>
    <div class="op-stat-card">
        <div>
            <div class="op-stat-val" style="color:{{ $pajakKadaluarsa > 0 ? '#ef4444' : '#0f172a' }};">{{ $pajakKadaluarsa }}</div>
            <div class="op-stat-lbl">Pajak Kadaluarsa</div>
        </div>
        <div class="op-stat-icon" style="background:#fef2f2; color:#ef4444;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
        </div>
    </div>
    <div class="op-stat-card">
        <div>
            <div class="op-stat-val" style="color:{{ $tanpaPemegang > 0 ? '#8b5cf6' : '#0f172a' }};">{{ $tanpaPemegang }}</div>
            <div class="op-stat-lbl">Tanpa Pemegang</div>
        </div>
        <div class="op-stat-icon" style="background:#f5f3ff; color:#8b5cf6;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
    </div>
</div>

{{-- Aksi Cepat --}}
<div class="op-card" style="margin-bottom:16px;">
    <div class="op-card-header">
        <span class="op-card-title">⚡ Aksi Cepat</span>
    </div>
    <div class="op-quick-grid">
        <a href="{{ route('operator.kendaraan.create') }}" class="op-quick-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
            Tambah Kendaraan
        </a>
        <a href="{{ route('operator.kendaraan.index') }}" class="op-quick-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
            Data Kendaraan
        </a>
        <a href="{{ route('operator.pegawai.index') }}" class="op-quick-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
            Data Pegawai
        </a>
        <a href="{{ route('operator.units.index') }}" class="op-quick-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
            Unit Kerja
        </a>
    </div>
</div>

{{-- Main Grid: Kendaraan Terbaru + Pajak Warning --}}
<div class="op-grid">
    {{-- Kendaraan Terbaru --}}
    <div class="op-card">
        <div class="op-card-header">
            <span class="op-card-title">🚗 Kendaraan Baru Terdaftar</span>
            <a href="{{ route('operator.kendaraan.index') }}" style="font-size:12px;color:#3b82f6;text-decoration:none;">Lihat Semua →</a>
        </div>
        <div class="op-card-body">
            @forelse($kendaraanTerbaru as $k)
            <div class="op-list-item">
                <div>
                    <div style="font-weight:600;color:#0f172a;font-size:13px;">{{ $k->nama_kendaraan }}</div>
                    <div style="font-size:11.5px;color:#64748b;font-family:monospace;">{{ $k->no_polisi }} &middot; {{ $k->kategori->nama_kategori ?? '-' }}</div>
                </div>
                <a href="{{ route('operator.kendaraan.show', $k->id) }}" style="font-size:12px;color:#3b82f6;text-decoration:none;white-space:nowrap;">Detail →</a>
            </div>
            @empty
            <div style="padding:24px;text-align:center;color:#94a3b8;font-size:13px;">Belum ada kendaraan terdaftar.</div>
            @endforelse
        </div>
    </div>

    {{-- Peringatan Pajak --}}
    <div class="op-card">
        <div class="op-card-header">
            <span class="op-card-title">⚠️ Pajak Hampir / Sudah Habis</span>
        </div>
        <div class="op-card-body">
            @forelse($kendaraanPajakWarning as $k)
            @php $isKritis = $k->sisa_hari <= 7; @endphp
            <div class="op-list-item" style="{{ $isKritis ? 'background:#fff5f5;' : '' }}">
                <div>
                    <div style="font-weight:600;color:#0f172a;font-size:13px;">{{ $k->nama_kendaraan }}</div>
                    <div style="font-size:11.5px;color:#64748b;font-family:monospace;">{{ $k->no_polisi }}</div>
                </div>
                <span style="font-size:11.5px;font-weight:600;padding:3px 8px;border-radius:20px;white-space:nowrap;background:{{ $isKritis ? '#fef2f2' : '#fffbeb' }};color:{{ $isKritis ? '#ef4444' : '#f59e0b' }};border:1px solid {{ $isKritis ? '#fecaca' : '#fde68a' }};">
                    {{ $k->sisa_hari }} hari
                </span>
            </div>
            @empty
            <div style="padding:24px;text-align:center;color:#94a3b8;font-size:13px;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="display:block;margin:0 auto 8px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                Semua pajak kendaraan masih aman.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection