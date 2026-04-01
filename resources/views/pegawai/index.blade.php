@extends('layouts.app')

@section('title', 'Kelola Pegawai - SIKANDIS')
@section('topbar_title', 'Master Data')

@section('content')
<div class="dashboard">
    {{-- PAGE HEADER --}}
    <div class="page-intro" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 class="page-heading">Kelola Pegawai</h2>
            <p class="page-subheading">Daftar pegawai eksternal/pengguna kendaraan</p>
        </div>
        <div>
            <a href="{{ route('pegawai.create') }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Pegawai
            </a>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card" style="margin-top: 24px;">
        <div class="table-toolbar">
            <form id="searchForm" method="GET" action="{{ route('pegawai.index') }}" class="toolbar-left">
                {{-- Search Input --}}
                <div class="search-input-wrapper">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="search-icon" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="search" name="q" class="search-input" placeholder="Cari nama atau NIP..." value="{{ request('q') }}" autocomplete="off">
                </div>

                {{-- Advanced Filter Toggle --}}
                @php
                    $activeFilterCount = collect(request()->only(['unit_id', 'sub_unit_id']))->filter()->count();
                @endphp
                <button type="button" id="btnToggleFilter" class="btn-filter {{ $activeFilterCount > 0 ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    Filter Advanced
                    @if($activeFilterCount > 0)
                        <span class="filter-badge">{{ $activeFilterCount }}</span>
                    @endif
                </button>
            </form>
        </div>

        {{-- Advanced Filter Panel --}}
        <div id="advancedFilterPanel" class="filter-panel {{ $activeFilterCount > 0 ? 'active' : '' }}">
            <div class="filter-grid">
                {{-- Filter Unit --}}
                <div class="filter-group">
                    <label class="filter-label">Unit Kerja</label>
                    <select name="unit_id" form="searchForm" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Sub Unit --}}
                <div class="filter-group">
                    <label class="filter-label">Sub Unit</label>
                    <select name="sub_unit_id" form="searchForm" class="form-select" onchange="this.form.submit()" {{ !request('unit_id') ? 'disabled' : '' }}>
                        <option value="">Semua Sub Unit</option>
                        @foreach($subUnits as $su)
                            <option value="{{ $su->id }}" {{ request('sub_unit_id') == $su->id ? 'selected' : '' }}>{{ $su->nama_sub_unit }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body-flush table-wrapper">
            {{-- Filter Chips & Summary --}}
            <div class="filter-summary">
                <div class="filter-chip-container">
                    @if($activeFilterCount > 0 || request('q'))
                        @if(request('q'))
                            <div class="filter-chip">
                                Cari: "{{ request('q') }}"
                                <span class="filter-chip-remove" onclick="removeFilter('q')">&times;</span>
                            </div>
                        @endif
                        @if(request('unit_id'))
                            <div class="filter-chip">
                                Unit: {{ $units->firstWhere('id', request('unit_id'))->nama_unit ?? 'Unit' }}
                                <span class="filter-chip-remove" onclick="removeFilter('unit_id')">&times;</span>
                            </div>
                        @endif
                        @if(request('sub_unit_id'))
                            <div class="filter-chip">
                                Sub Unit: {{ $subUnits->firstWhere('id', request('sub_unit_id'))->nama_sub_unit ?? 'Sub Unit' }}
                                <span class="filter-chip-remove" onclick="removeFilter('sub_unit_id')">&times;</span>
                            </div>
                        @endif
                        <a href="{{ route('pegawai.index') }}" class="filter-reset">Reset Semua</a>
                    @endif
                </div>
                <div class="summary-text">
                    Menampilkan <strong>{{ $pegawais->firstItem() ?? 0 }} - {{ $pegawais->lastItem() ?? 0 }}</strong> dari <strong>{{ $pegawais->total() }}</strong> pegawai
                </div>
            </div>

            <div style="padding: 0 20px 20px;">
                <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Pegawai</th>
                        <th>NIP / NIK</th>
                        <th>Unit Kerja</th>
                        <th>Jabatan</th>
                        <th class="action-cell">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawais as $p)
                        <tr>
                            <td><div class="cell-primary">{{ $p->nama }}</div></td>
                            <td>
                                <div style="font-size: 13px; color: var(--n-700);">NIP: {{ $p->nip ?: '-' }}</div>
                                <div style="font-size: 11.5px; color: var(--n-500);">NIK: {{ $p->nik }}</div>
                            </td>
                            <td>
                                <div class="cell-primary">{{ $p->unit->nama_unit ?? '-' }}</div>
                                <div class="cell-secondary">{{ $p->subUnit->nama_sub_unit ?? '-' }}</div>
                            </td>
                            <td><div class="badge badge-neutral">{{ $p->jabatan }}</div></td>
                            <td class="action-cell">
                                <div class="action-group">
                                    <a href="{{ route('pegawai.edit', $p) }}" class="btn btn-icon btn-secondary" title="Edit">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    <form action="{{ route('pegawai.destroy', $p) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pegawai ini?');">
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
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    </div>
                                    <div class="empty-state-title">Data pegawai tidak ditemukan.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pegawais->hasPages())
        <div class="card-footer" style="padding: 16px 20px; border-top: 1px solid var(--n-200);">
            {{ $pegawais->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnToggleFilter = document.getElementById('btnToggleFilter');
    const filterPanel = document.getElementById('advancedFilterPanel');
    
    if (btnToggleFilter && filterPanel) {
        btnToggleFilter.addEventListener('click', () => {
            filterPanel.classList.toggle('active');
        });
    }
});

function removeFilter(key) {
    const form = document.getElementById('searchForm');
    const input = form.querySelector(`input[name="${key}"], select[name="${key}"]`);
    if (input) {
        if (input.tagName === 'SELECT') input.selectedIndex = 0;
        else input.value = '';
    }
    form.submit();
}
</script>
@endpush
@endsection
