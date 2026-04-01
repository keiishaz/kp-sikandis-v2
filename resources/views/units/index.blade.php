@extends('layouts.app')

@section('title', 'Kelola Unit Kerja - SIKANDIS')
@section('topbar_title', 'Master Data')

@section('content')
<div class="dashboard">


    {{-- PAGE HEADER --}}
    <div class="page-intro" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 class="page-heading">Kelola Unit Kerja</h2>
        </div>
        <div>
            <a href="{{ route('units.create') }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Unit
            </a>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card" style="margin-top: 24px;">
        <div class="table-toolbar">
            <form id="searchForm" method="GET" action="{{ route('units.index') }}" class="toolbar-left">
                <div class="search-input-wrapper">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="search-icon" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="search" name="q" class="search-input" placeholder="Cari nama unit..." value="{{ request('q') }}" autocomplete="off">
                </div>
            </form>
        </div>

        <div class="card-body-flush table-wrapper">
            @if(request('q'))
            <div class="filter-summary">
                <div class="filter-chip-container">
                    <div class="filter-chip">
                        Cari: "{{ request('q') }}"
                        <a href="{{ route('units.index') }}" class="filter-chip-remove" style="text-decoration: none;">&times;</a>
                    </div>
                    <a href="{{ route('units.index') }}" class="filter-reset">Reset Semua</a>
                </div>
            </div>
            @endif
            <div style="padding: 0 20px 20px;">
                <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px; text-align: center;">No</th>
                        <th>Nama Unit</th>
                        <th>Sub Unit</th>
                        <th class="action-cell">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $i => $unit)
                        <tr>
                            <td style="text-align: center;">{{ $units->firstItem() + $i }}</td>
                            <td><div class="cell-primary">{{ $unit->nama_unit }}</div></td>
                            <td>
                                <a href="{{ route('units.sub-units.index', $unit) }}" class="btn btn-secondary btn-icon" style="padding: 6px 12px; width: auto; font-size: 13px;">
                                    Kelola Sub Unit
                                </a>
                            </td>
                            <td class="action-cell">
                                <div class="action-group">
                                    <a href="{{ route('units.edit', $unit) }}" class="btn btn-icon btn-secondary" title="Edit">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    <form action="{{ route('units.destroy', $unit) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus unit kerja ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-secondary" style="color: var(--danger-600); border-color: var(--danger-200); background-color: var(--danger-50);" title="Hapus">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    </div>
                                    <div class="empty-state-title">Belum ada data unit.</div>
                                    <div class="empty-state-desc">Silakan tambah data unit kerja baru.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($units->total() > 0)
        <div class="card-footer" style="padding: 16px 20px; border-top: 1px solid var(--n-200);">
            {{ $units->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
