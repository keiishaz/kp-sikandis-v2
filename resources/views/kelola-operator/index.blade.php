@extends('layouts.app')

@section('title', 'Kelola Operator - SIKANDIS')
@section('topbar_title', 'Kelola Operator')

@section('content')
<div class="dashboard">

    {{-- PAGE HEADER --}}
    <div class="page-intro" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 class="page-heading">Kelola Operator</h2>
            <p class="page-subheading">Kelola akun akses untuk operator sistem</p>
        </div>
        <div>
            <a href="{{ route('kelola-operator.create') }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Operator
            </a>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card" style="margin-top: 24px;">
        <div class="table-toolbar">
            <form id="searchForm" method="GET" action="{{ route('kelola-operator.index') }}" class="toolbar-left">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="dir" value="{{ request('dir') }}">

                {{-- Search Input --}}
                <div class="search-input-wrapper">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="search-icon" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="search" name="q" class="search-input" placeholder="Cari Nama atau NIP/NIK Operator..." value="{{ request('q') }}" autocomplete="off">
                </div>

                {{-- Advanced Filter Toggle --}}
                @php
                    $activeFilterCount = collect(request()->only(['login_status']))->filter()->count();
                @endphp
                <button type="button" id="btnToggleFilter" class="btn-filter {{ $activeFilterCount > 0 ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    Filter
                    @if($activeFilterCount > 0)
                        <span class="filter-badge">{{ $activeFilterCount }}</span>
                    @endif
                </button>
            </form>
        </div>

        {{-- Filter Panel --}}
        <div id="advancedFilterPanel" class="filter-panel {{ $activeFilterCount > 0 ? 'active' : '' }}">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Status Login</label>
                    <select name="login_status" form="searchForm" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('login_status') == 'active' ? 'selected' : '' }}>Sudah Pernah Login</option>
                        <option value="never" {{ request('login_status') == 'never' ? 'selected' : '' }}>Belum Pernah Login</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body-flush table-wrapper">
             @if($activeFilterCount > 0 || request('q'))
             <div class="filter-summary">
                <div class="filter-chip-container">
                    @if(request('q'))
                        <div class="filter-chip">
                            Cari: "{{ request('q') }}"
                            <span class="filter-chip-remove" onclick="removeFilter('q')">&times;</span>
                        </div>
                    @endif
                    @if(request('login_status'))
                        <div class="filter-chip">
                            Status: {{ request('login_status') === 'never' ? 'Belum Login' : 'Sudah Login' }}
                            <span class="filter-chip-remove" onclick="removeFilter('login_status')">&times;</span>
                        </div>
                    @endif
                    <a href="{{ route('kelola-operator.index') }}" class="filter-reset">Reset Semua</a>
                </div>
            </div>
            @endif
            <div style="padding: 0 20px 20px;">
                <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Operator</th>
                        <th>NIK</th>
                        <th>NIP</th>
                        <th>Terakhir Login</th>
                        <th class="action-cell">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($operators as $op)
                        <tr>
                            <td><div class="cell-primary">{{ $op->name }}</div></td>
                            <td><span class="plat-badge" style="background:var(--brand-50);border:1px solid var(--brand-100);color:var(--brand-700);">{{ $op->nik }}</span></td>
                            <td><span class="plat-badge" style="background:transparent;border:none;padding:0;color:var(--n-600);">{{ $op->nip ?: '-' }}</span></td>
                            <td>
                                @if(isset($op->last_login_at))
                                    <div style="font-size: 13px; color: var(--n-700);">{{ \Carbon\Carbon::parse($op->last_login_at)->translatedFormat('d M Y, H:i') }}</div>
                                @else
                                    <span class="badge badge-neutral">Belum pernah login</span>
                                @endif
                            </td>
                            <td class="action-cell">
                                <div class="action-group">
                                    <a href="{{ route('kelola-operator.show', $op) }}" class="btn btn-icon btn-secondary" title="Detail">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 16 12 12 12 8"></polyline><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    </a>
                                    <a href="{{ route('kelola-operator.edit', $op) }}" class="btn btn-icon btn-secondary" title="Edit">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    <form action="{{ route('kelola-operator.destroy', $op) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus operator ini?');">
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
                                    <div class="empty-state-title">Data operator tidak ditemukan.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($operators->total() > 0)
        <div class="card-footer" style="padding: 16px 20px; border-top: 1px solid var(--n-200);">
            {{ $operators->links() }}
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
