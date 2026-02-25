@extends('layouts.admin')

@section('title', 'Log Aktivitas')
@section('topbar_title', 'Log Aktivitas')

@section('content')
    <section class="table-container">
        <div class="table-header">
            <h3 class="table-title">Daftar Log Aktivitas</h3>
        </div>

        <form class="table-toolbar" method="GET" action="{{ route('admin.log.aktivitas') }}">
            <div class="table-toolbar-inner" style="display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap; background:#fff; padding:16px; border-radius:8px; border:1px solid #e2e8f0; margin-bottom:20px;">
                
                <div class="toolbar-field" style="display:flex; flex-direction:column; gap:6px; min-width: 180px;">
                    <label style="font-size:12px; font-weight:600; color:#475569; text-transform:uppercase; letter-spacing:0.5px;">Filter Tanggal</label>
                    <input type="date" name="date" style="padding:10px 14px; cursor:pointer; border:1px solid #cbd5e1; border-radius:6px; background:#fff; font-family:inherit; color:#334155; width:100%; box-sizing:border-box; outline:none;" value="{{ request('date') }}">
                </div>

                <div class="toolbar-field" style="flex:1; display:flex; flex-direction:column; gap:6px; min-width: 250px;">
                    <label style="font-size:12px; font-weight:600; color:#475569; text-transform:uppercase; letter-spacing:0.5px;">Pencarian Label / Kata Kunci</label>
                    <div style="position:relative;">
                        <input type="search" name="q" class="table-search-input" style="padding:10px 14px 10px 38px; width:100%; box-sizing:border-box;"
                               placeholder="Cari user, aksi, atau keterangan..." value="{{ request('q') }}" autocomplete="off">
                        <svg style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </div>
                </div>

                <div class="toolbar-actions" style="display:flex; gap:10px;">
                    <button type="submit" class="btn btn-primary" style="height:42px; padding:0 20px;">Filter Data</button>
                    @if(request('q') || request('date'))
                        <a href="{{ route('admin.log.aktivitas') }}" class="btn btn-outline" style="height:42px; padding:0 20px; display:inline-flex; align-items:center;">Reset</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px">No</th>
                        <th style="width:180px">Tanggal & Waktu (WIB)</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Modul</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $i => $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $i }}</td>
                            <td style="white-space:nowrap;font-size:13px;color:#475569">{{ $log['waktu'] ?? '-' }}</td>
                            <td><span style="font-weight:500;">{{ $log['user'] ?? '-' }}</span></td>
                            <td>
                                @php
                                    $aksi = $log['aksi'] ?? '-';
                                    $customStyle = match (true) {
                                        str_contains($aksi, 'TAMBAH') => 'background-color: rgba(34, 197, 94, 0.15); color: #22c55e;',
                                        str_contains($aksi, 'EDIT')   => 'background-color: rgba(234, 179, 8, 0.15); color: #eab308;',
                                        str_contains($aksi, 'HAPUS')  => 'background-color: rgba(239, 68, 68, 0.15); color: #ef4444;',
                                        default                       => 'background-color: rgba(100, 116, 139, 0.15); color: #64748b;'
                                    };
                                @endphp
                                <span @style(['display:inline-block', 'padding:2px 8px', 'border-radius:4px', 'font-size:11px', 'font-weight:600', $customStyle])>{{ $aksi }}</span>
                            </td>
                            <td>{{ $log['modul'] ?? '-' }}</td>
                            <td style="font-size:13px;color:#475569;line-height:1.5;">{!! $log['keterangan'] ?? $log['raw'] !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-content">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12" y2="16"></line>
                                    </svg>
                                    <p>Tidak ada data log aktivitas ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">{{ $logs->appends(request()->query())->links() }}</div>
    </section>
@endsection
