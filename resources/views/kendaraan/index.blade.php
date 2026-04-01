@extends('layouts.app')

@section('title', 'Data Kendaraan ' . (auth()->user()->role->nama_role === 'admin' ? 'Admin' : 'Operator') . ' — SIKANDIS')
@section('topbar_title', 'Data Kendaraan ' . (auth()->user()->role->nama_role === 'admin' ? 'Admin' : 'Operator'))

@section('content')
<style>
    .filter-panel {
        background: var(--n-50);
        border: 1px solid var(--n-200);
        border-radius: var(--r-md);
        padding: 20px;
        margin-bottom: 20px;
        display: none;
        animation: slideDown 0.3s ease-out;
    }
    .filter-panel.active { display: block; }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .filter-group { margin-bottom: 16px; }
    .filter-label { 
        display: block; 
        font-size: 12px; 
        font-weight: 700; 
        color: var(--n-600); 
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .filter-chip-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
    }
    .filter-chip {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        background: var(--brand-50);
        color: var(--brand-700);
        border: 1px solid var(--brand-200);
        border-radius: 50px;
        font-size: 12.5px;
        font-weight: 500;
    }
    .filter-chip-remove {
        margin-left: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--brand-400);
        transition: color 0.2s;
    }
    .filter-chip-remove:hover { color: var(--brand-700); }

    .summary-text {
        font-size: 13px;
        color: var(--n-500);
        margin-bottom: 12px;
    }
</style>

    {{-- PAGE HEADER --}}
    <div class="page-intro">
        <div>
            <h2 class="page-heading">Data Kendaraan</h2>
            <p class="page-subheading">Kelola seluruh data inventaris kendaraan instansi</p>
        </div>
        <div>
            <a href="{{ route('kendaraan.create') }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Kendaraan
            </a>
        </div>
    </div>

    {{-- TAB NAVIGATION --}}
    <div class="tab-nav">
        <a href="{{ route('kendaraan.index', array_merge(request()->query(), ['status' => 'aktif'])) }}" 
           class="tab-nav-item {{ $status === 'aktif' ? 'active' : '' }}">
            Kendaraan Aktif
            <span class="badge {{ $status === 'aktif' ? 'badge-primary' : 'badge-neutral' }}">{{ $countAktif }}</span>
        </a>
        <a href="{{ route('kendaraan.index', array_merge(request()->query(), ['status' => 'nonaktif'])) }}" 
           class="tab-nav-item {{ $status === 'nonaktif' ? 'active' : '' }}">
            Kendaraan Nonaktif
            <span class="badge {{ $status === 'nonaktif' ? 'badge-danger' : 'badge-neutral' }}">{{ $countNonaktif }}</span>
        </a>
    </div>

    {{-- SEARCH TOOLBAR & TABLE CARD --}}
    <div class="card" style="border-top-left-radius: 0;">
        <div class="table-toolbar">
            <form id="searchForm" action="{{ route('kendaraan.index') }}" method="GET" class="toolbar-left">
                <input type="hidden" name="status" value="{{ $status }}">
                
                {{-- Search Input --}}
                <div class="search-input-wrapper">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="search-icon" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nopol atau nama kendaraan..." class="form-input" autocomplete="off">
                </div>

                {{-- Advanced Filter Toggle --}}
                @php
                    $activeFilterCount = collect(request()->except(['q', 'status', 'page']))->filter()->count();
                @endphp
                <button type="button" id="btnToggleFilter" class="btn-filter {{ $activeFilterCount > 0 ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    Filter Advanced
                    @if($activeFilterCount > 0)
                        <span class="filter-badge">{{ $activeFilterCount }}</span>
                    @endif
                </button>

                {{-- HIDDEN INPUTS FOR ALL FILTERS (to ensure they are submitted together) --}}
                <div id="filterInputsContainer">
                    @foreach(request()->except(['q', 'status', 'page', 'kategori_id', 'jenis_penggunaan', 'status_pajak', 'unit_id', 'opd_name']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    {{-- Specific hidden inputs for unit_id and opd_name to map the dinas_opd selection --}}
                    <input type="hidden" name="unit_id" value="{{ request('unit_id') }}">
                    <input type="hidden" name="opd_name" value="{{ request('opd_name') }}">
                </div>
            </form>

            <div class="toolbar-right">
                <button type="button" onclick="openPrintModal()" class="btn btn-secondary" style="height: 42px; color: var(--brand-700); border-color: var(--brand-200); background: var(--brand-50);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    Cetak Laporan
                </button>
            </div>
        </div>

        {{-- THE HIDDEN FILTER PANEL CONTENT --}}
        <div id="advancedFilterPanel" class="filter-panel {{ $activeFilterCount > 0 ? 'active' : '' }}">
            <div class="filter-grid">
                {{-- Filter Kategori --}}
                <div class="filter-group">
                    <label class="filter-label">Kategori</label>
                    <select name="kategori_id" form="searchForm" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Jenis Penggunaan --}}
                <div class="filter-group">
                    <label class="filter-label">Jenis Penggunaan</label>
                    <select name="jenis_penggunaan" form="searchForm" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Jenis</option>
                        <option value="jabatan" {{ request('jenis_penggunaan') == 'jabatan' ? 'selected' : '' }}>Jabatan</option>
                        <option value="operasional" {{ request('jenis_penggunaan') == 'operasional' ? 'selected' : '' }}>Operasional</option>
                    </select>
                </div>

                {{-- Filter Status Pajak --}}
                <div class="filter-group">
                    <label class="filter-label">Status Masa Pajak</label>
                    <select name="status_pajak" form="searchForm" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status_pajak') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="hampir_jatuh_tempo" {{ request('status_pajak') == 'hampir_jatuh_tempo' ? 'selected' : '' }}>Hampir Jatuh Tempo</option>
                        <option value="telah_jatuh_tempo" {{ request('status_pajak') == 'telah_jatuh_tempo' ? 'selected' : '' }}>Telah Jatuh Tempo</option>
                    </select>
                </div>

                {{-- Filter Dinas/OPD (Dinamis) --}}
                <div class="filter-group">
                    <label class="filter-label">Tugas Dinas / OPD</label>
                    <select name="dinas_opd" form="searchForm" class="form-select" id="dinasFilter" onchange="handleDinasChange(this)">
                        <option value="">Semua Dinas</option>
                        
                        <optgroup label="Unit Kerja Internal (Manual)">
                            @foreach($manualUnits as $un)
                                <option value="unit:{{ $un->id }}" {{ request('unit_id') == $un->id ? 'selected' : '' }}>{{ $un->nama_unit }}</option>
                            @endforeach
                        </optgroup>

                        <optgroup label="OPD Penanggung Jawab (API)">
                            @foreach($apiOpds as $opd)
                                <option value="opd:{{ $opd }}" {{ request('opd_name') == $opd ? 'selected' : '' }}>{{ $opd }}</option>
                            @endforeach
                        </optgroup>
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
                    @if(request('kategori_id'))
                        <div class="filter-chip">
                            @php $katName = $kategoris->firstWhere('id', request('kategori_id'))->nama_kategori ?? 'Kategori'; @endphp
                            Kategori: {{ $katName }}
                            <span class="filter-chip-remove" onclick="removeFilter('kategori_id')">&times;</span>
                        </div>
                    @endif
                    @if(request('jenis_penggunaan'))
                        <div class="filter-chip">
                            Jenis: {{ ucfirst(request('jenis_penggunaan')) }}
                            <span class="filter-chip-remove" onclick="removeFilter('jenis_penggunaan')">&times;</span>
                        </div>
                    @endif
                    @if(request('status_pajak'))
                        <div class="filter-chip">
                            Pajak: {{ str_replace('_', ' ', ucfirst(request('status_pajak'))) }}
                            <span class="filter-chip-remove" onclick="removeFilter('status_pajak')">&times;</span>
                        </div>
                    @endif
                    @if(request('unit_id'))
                        <div class="filter-chip">
                            Dinas: {{ $manualUnits->firstWhere('id', request('unit_id'))->nama_unit ?? 'Unit Kerja' }}
                            <span class="filter-chip-remove" onclick="removeDinasFilters()">&times;</span>
                        </div>
                    @endif
                    @if(request('opd_name'))
                        <div class="filter-chip">
                            Dinas (API): {{ request('opd_name') }}
                            <span class="filter-chip-remove" onclick="removeDinasFilters()">&times;</span>
                        </div>
                    @endif
                    <a href="{{ route('kendaraan.index', ['status' => $status]) }}" class="filter-reset">Reset Semua</a>
                </div>
            </div>
            @endif
            <div style="padding: 0 20px 20px;">
                <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Polisi</th>
                        <th>Nama Kendaraan</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th>Status Masa Pajak</th>
                        <th>Pemegang Aktif</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kendaraans as $k)
                        <tr>
                            <td>
                                <span class="plat-badge">{{ $k->no_polisi }}</span>
                            </td>
                            <td>
                                <div class="cell-primary">{{ $k->nama_kendaraan }}</div>
                                <div class="cell-secondary">{{ $k->tahun }}</div>
                            </td>
                            <td>
                                <span class="badge badge-neutral">{{ $k->kategori->nama_kategori ?? '-' }}</span>
                            </td>
                            <td>
                                <span style="text-transform: capitalize; font-size: 13px; color: var(--n-700);">
                                    {{ $k->jenis_penggunaan }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $pajakBadgeClass = match($k->color_pajak) {
                                        'green' => 'badge-success',
                                        'yellow' => 'badge-warning',
                                        'red' => 'badge-danger',
                                        default => 'badge-neutral'
                                    };
                                @endphp
                                <span class="badge {{ $pajakBadgeClass }}">
                                    {{ $k->status_pajak }}
                                </span>
                            </td>
                            <td>
                                @if($k->pemegangAktif)
                                    <div class="cell-primary">{{ $k->pemegangAktif->display_name }}</div>
                                    <div class="cell-secondary" style="font-size: 11px;">{{ $k->pemegangAktif->display_opd }}</div>
                                @else
                                    <span style="font-size: 12px; color: var(--n-400); font-style: italic;">Pool (Standby)</span>
                                @endif
                            </td>
                            <td class="action-cell">
                                <div class="action-group">
                                    <a href="{{ route('kendaraan.show', $k->id) }}" class="btn btn-icon btn-secondary" title="Detail">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 16 12 12 12 8"></polyline><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    </a>
                                    <a href="{{ route('kendaraan.edit', $k->id) }}" class="btn btn-icon btn-secondary" title="Edit">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    @can('delete-kendaraan')
                                    <form action="{{ route('kendaraan.destroy', $k->id) }}" method="POST" style="display:inline;" data-confirm="Apakah Anda yakin ingin mengubah status kendaraan ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-secondary" title="{{ $k->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}" style="{{ $k->status === 'aktif' ? 'color: var(--danger-text); background: var(--danger-bg); border: 1px solid var(--danger-border);' : 'color: var(--success-text); background: var(--success-bg); border: 1px solid var(--success-border);' }}">
                                            @if($k->status === 'aktif')
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                            @else
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            @endif
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                    </div>
                                    <div class="empty-state-title">Tidak ada data kendaraan ditemukan</div>
                                    <div class="empty-state-text">Cobalah kata kunci pencarian yang lain atau ubah filter Anda.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($kendaraans->total() > 0)
        <div class="card-footer" style="background: white; border-top: 1px solid var(--n-200);">
            {{ $kendaraans->links() }}
        </div>
        @endif
    </div>

    {{-- MODAL PRINT FILTER --}}
    <div id="modalPrintFilter" class="modal-overlay">
        <div class="modal" style="max-width: 480px;">
            <div class="modal-header">
                <h3 class="modal-title">Filter Cetak Laporan</h3>
                <button type="button" class="btn-close" onclick="closePrintModal()">&times;</button>
            </div>
            <form id="formPrintFilter" action="{{ route('kendaraan.print') }}" method="GET" target="_blank">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="hidden" name="q" value="{{ request('q') }}">

                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label" style="font-size: 13.5px; font-weight: 600; color: var(--n-800); margin-bottom: 8px;">Kategori Kendaraan</label>
                        <select name="kategori_id" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label" style="font-size: 13.5px; font-weight: 600; color: var(--n-800); margin-bottom: 8px;">Jenis Penggunaan</label>
                        <select name="jenis_penggunaan" class="form-select">
                            <option value="">Semua</option>
                            <option value="jabatan">Jabatan</option>
                            <option value="operasional">Operasional</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" style="font-size: 13.5px; font-weight: 600; color: var(--n-800); margin-bottom: 8px;">Status Pajak</label>
                        <select name="status_pajak" class="form-select">
                            <option value="">Semua</option>
                            <option value="aktif">Aktif</option>
                            <option value="hampir_jatuh_tempo">Hampir Jatuh Tempo</option>
                            <option value="telah_jatuh_tempo">Telah Jatuh Tempo</option>
                        </select>
                    </div>

                    {{-- Live Counter --}}
                    <div id="printCountBox" style="background: var(--brand-50); border: 1px solid var(--brand-200); border-radius: var(--r-md); padding: 10px 14px; display: flex; align-items: center; gap: 10px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--brand-600)" stroke-width="2" style="flex-shrink:0;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        <span style="font-size: 13px; color: var(--brand-700);">
                            Jumlah data yang akan masuk laporan:
                            <strong id="printCountVal" style="font-size: 15px; font-weight: 700;">—</strong>
                        </span>
                    </div>
                </div>

                <div class="modal-footer" style="padding: 16px 24px;">
                    <button type="button" class="btn btn-secondary" onclick="closePrintModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        Cetak Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle Filter Panel
    const btnToggleFilter = document.getElementById('btnToggleFilter');
    const filterPanel = document.getElementById('advancedFilterPanel');
    
    if (btnToggleFilter && filterPanel) {
        btnToggleFilter.addEventListener('click', () => {
            filterPanel.classList.toggle('active');
        });
    }

    // Portal modal
    document.body.appendChild(document.getElementById('modalPrintFilter'));
});

function handleDinasChange(select) {
    const value = select.value;
    const form = document.getElementById('searchForm');
    const unitIdInput = form.querySelector('input[name="unit_id"]');
    const opdNameInput = form.querySelector('input[name="opd_name"]');

    if (value === "") {
        unitIdInput.value = "";
        opdNameInput.value = "";
    } else if (value.startsWith('unit:')) {
        unitIdInput.value = value.split(':')[1];
        opdNameInput.value = "";
    } else if (value.startsWith('opd:')) {
        unitIdInput.value = "";
        opdNameInput.value = value.split(':')[1];
    }
    
    form.submit();
}

function removeFilter(key) {
    const form = document.getElementById('searchForm');
    // Using form.elements is robust for elements linked via form attribute
    const input = form.elements[key];
    if (input) {
        if (input.tagName === 'SELECT') input.selectedIndex = 0;
        else input.value = '';
    }
    form.submit();
}

function removeDinasFilters() {
    const form = document.getElementById('searchForm');
    form.elements['unit_id'].value = '';
    form.elements['opd_name'].value = '';
    const select = document.getElementById('dinasFilter');
    if (select) select.selectedIndex = 0;
    form.submit();
}

function openPrintModal() { document.getElementById('modalPrintFilter').classList.add('active'); fetchPrintCount(); }
function closePrintModal() { document.getElementById('modalPrintFilter').classList.remove('active'); }

async function fetchPrintCount() {
    const form = document.getElementById('formPrintFilter');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    const res = await fetch(`{{ route('kendaraan.print-count') }}?${params.toString()}`);
    const data = await res.json();
    document.getElementById('printCountVal').textContent = data.count;
}

// Modal select change listener
document.querySelectorAll('#formPrintFilter select').forEach(s => s.addEventListener('change', fetchPrintCount));
</script>
@endpush
