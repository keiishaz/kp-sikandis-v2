@extends('layouts.admin')

@section('title', 'Log Login - SIKANDIS')
@section('topbar_title', 'Log Login')

@section('content')
<div class="dashboard">

    {{-- PAGE HEADER --}}
    <div class="page-intro" style="margin-bottom: 24px;">
        <h2 class="page-heading">Riwayat Autentikasi Pengguna</h2>
    </div>

    {{-- FILTER BAR --}}
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-body" style="padding: 16px 20px;">
            <form method="GET" action="{{ route('admin.log.login') }}" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
                
                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 250px;">
                    <label class="form-label">Pencarian NIP Pengguna</label>
                    <div class="search-input-wrapper" style="max-width: 100%;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="search" name="q" class="search-input" placeholder="Cari NIP pengguna atau keterangan..." value="{{ request('q') }}" autocomplete="off">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0; width: 160px;">
                    <label class="form-label">Filter Tanggal</label>
                    <input type="date" name="date" class="form-input" value="{{ request('date') }}">
                </div>

                <div style="display: flex; gap: 12px; margin-bottom: 2px;">
                    <button type="submit" class="btn btn-primary" style="height: 40px;">Filter</button>
                    @if(request('q') || request('date'))
                        <a href="{{ route('admin.log.login') }}" class="btn btn-secondary" style="height: 40px;">Reset</a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card">
        <div class="card-body-flush table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px; text-align: center;">No</th>
                        <th style="width:200px;">Tanggal & Waktu (WIB)</th>
                        <th>Status Login</th>
                        <th>Keterangan Tambahan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $i => $log)
                        <tr>
                            <td style="text-align: center;">{{ $logs->firstItem() + $i }}</td>
                            <td><span style="font-size: 13px; color: var(--n-600); white-space: nowrap;">{{ $log['waktu'] ?? '-' }}</span></td>
                            <td>
                                @php
                                    $status = $log['status'] ?? '-';
                                    $isSuccess = str_contains($status, 'SUCCESS');
                                    $badgeClass = $isSuccess ? 'badge-success' : 'badge-danger';
                                    $text = $isSuccess ? 'BERHASIL' : 'GAGAL';
                                @endphp
                                <span class="badge {{ $badgeClass }}" style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px;">
                                    @if($isSuccess)
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="success-icon" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    @else
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="danger-icon" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    @endif
                                    {{ $text }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 13px; color: var(--n-600);">
                                    @if(isset($log['keterangan']))
                                        {{ str_replace('—', '', $log['keterangan']) }}
                                    @else
                                        {{ $log['raw'] }}
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" class="info-icon" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                    </div>
                                    <div class="empty-state-title">Tidak ada riwayat login ditemukan.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="card-footer" style="padding: 16px 20px; border-top: 1px solid var(--n-200);">
            {{ $logs->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
