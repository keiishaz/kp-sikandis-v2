@extends('layouts.app')

@section('title', 'QR Kendaraan ' . (auth()->user()->role->nama_role === 'admin' ? 'Admin' : 'Operator') . ' — SIKANDIS')
@section('topbar_title', 'Data Kendaraan ' . (auth()->user()->role->nama_role === 'admin' ? 'Admin' : 'Operator'))

@section('content')
<div class="dashboard">

    {{-- Hidden form to pass data to print page --}}
    <form id="form-print" action="{{ route('qr-kendaraan.print') }}" method="POST" target="_blank" style="display:none;">
        @csrf
        <input type="hidden" name="items" id="input-print-items">
    </form>

    {{-- PAGE HEADER --}}
    <div class="page-intro">
        <div>
            <h2 class="page-heading">QR Kendaraan</h2>
            <p class="page-subheading">Kelola dan cetak kode QR untuk akses informasi publik kendaraan</p>
        </div>
    </div>

    {{-- PAGE TOOLBAR & TABLE CARD --}}
    <div class="card">
        <div class="table-toolbar">
            <form id="searchForm" method="GET" action="{{ route('qr-kendaraan.index') }}" class="toolbar-left">
                {{-- Search Input --}}
                <div class="search-input-wrapper">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="search-icon" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="search" name="q" class="search-input" placeholder="Cari kendaraan atau token..." value="{{ request('q') }}" autocomplete="off">
                </div>

                {{-- Advanced Filter Toggle --}}
                @php
                    $activeFilterCount = collect(request()->only(['kategori_id', 'scan_status']))->filter()->count();
                @endphp
                <button type="button" id="btnToggleFilter" class="btn-filter {{ $activeFilterCount > 0 ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    Filter
                    @if($activeFilterCount > 0)
                        <span class="filter-badge">{{ $activeFilterCount }}</span>
                    @endif
                </button>
            </form>

            <div class="toolbar-right">
                <button type="button" id="btn-print-selected" onclick="printSelected()" class="btn btn-primary" disabled style="opacity: 0.5; height: 42px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    Cetak Terpilih (<span id="selected-count">0</span>)
                </button>
            </div>
        </div>

        {{-- Filter Panel --}}
        <div id="advancedFilterPanel" class="filter-panel {{ $activeFilterCount > 0 ? 'active' : '' }}">
            <div class="filter-grid">
                {{-- Filter Kategori --}}
                <div class="filter-group">
                    <label class="filter-label">Kategori Kendaraan</label>
                    <select name="kategori_id" form="searchForm" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Scan Status --}}
                <div class="filter-group">
                    <label class="filter-label">Status Distribusi (Scan)</label>
                    <select name="scan_status" form="searchForm" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="never" {{ request('scan_status') == 'never' ? 'selected' : '' }}>Belum Pernah Discan</option>
                        <option value="active" {{ request('scan_status') == 'active' ? 'selected' : '' }}>Sudah Pernah Discan</option>
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
                            Kategori: {{ $kategoris->firstWhere('id', request('kategori_id'))->nama_kategori ?? 'Kategori' }}
                            <span class="filter-chip-remove" onclick="removeFilter('kategori_id')">&times;</span>
                        </div>
                    @endif
                    @if(request('scan_status'))
                        <div class="filter-chip">
                            Status: {{ request('scan_status') === 'never' ? 'Belum Discan' : 'Sudah Discan' }}
                            <span class="filter-chip-remove" onclick="removeFilter('scan_status')">&times;</span>
                        </div>
                    @endif
                    <a href="{{ route('qr-kendaraan.index') }}" class="filter-reset">Reset Semua</a>
                </div>
            </div>
            @endif
            <div style="padding: 0 20px 20px;">
                <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 48px; text-align: center; padding-left: 20px;">
                            <input type="checkbox" id="check-all" class="form-checkbox" onchange="toggleAll(this)">
                        </th>
                        <th>Kendaraan</th>
                        <th>Token QR</th>
                        <th style="text-align: center;">QR Preview</th>
                        <th style="text-align: center;">Jumlah Scan</th>
                        <th style="text-align: center;" class="action-cell">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($qrs as $qr)
                    <tr data-token="{{ $qr->token }}" data-nama="{{ $qr->kendaraan->nama_kendaraan }}" data-plat="{{ $qr->kendaraan->no_polisi }}">
                        <td style="text-align: center; padding-left: 20px;">
                            <input type="checkbox" class="row-check form-checkbox" value="{{ $qr->id }}" data-token="{{ $qr->token }}" onchange="updateSelection()">
                        </td>
                        <td>
                            <div class="cell-primary" style="margin-bottom: 4px;">{{ $qr->kendaraan->nama_kendaraan }}</div>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <span class="plat-badge">{{ $qr->kendaraan->no_polisi }}</span>
                                <span class="badge badge-neutral" style="font-size: 10px;">{{ $qr->kendaraan->kategori->nama_kategori ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('kendaraan.publik', $qr->token) }}" target="_blank" class="badge" style="background: var(--brand-50); color: var(--brand-700); font-family: monospace; font-size: 13.5px; letter-spacing: 1px; text-decoration: none; border: 1px solid var(--brand-200); transition: all 0.2s;" onmouseover="this.style.background='var(--brand-100)'" onmouseout="this.style.background='var(--brand-50)'">
                                {{ $qr->token }}
                            </a>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <div onclick="openQrModal('{{ $qr->token }}', '{{ route('kendaraan.publik', $qr->token) }}')" style="position: relative; display: inline-block; padding: 4px; border: 1px solid var(--n-200); border-radius: var(--r-md); cursor: pointer; transition: transform 0.2s; background: #fff;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" title="Perbesar QR">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ urlencode(route('kendaraan.publik', $qr->token)) }}&color=000000&bgcolor=ffffff&margin=2" alt="QR {{ $qr->token }}" width="70" height="70" style="display: block; border-radius: 4px;" loading="lazy">
                                <img src="{{ asset('assets/images/logobkl.png') }}" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 15px; height: 15px; padding: 2px; background: #ffffff; border-radius: 4px; object-fit: contain;">
                            </div>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <span class="badge {{ $qr->scan_count > 0 ? 'badge-info' : 'badge-neutral' }}" style="padding: 6px 12px; font-size: 13px;">
                                {{ number_format($qr->scan_count, 0, ',', '.') }}
                            </span>
                        </td>
                        <td style="text-align: center; vertical-align: middle;" class="action-cell">
                            <div class="action-group" style="justify-content: center;">
                                <button type="button" onclick="printSingleEl(this)" class="btn btn-secondary btn-sm" style="color: var(--brand-700); border-color: var(--brand-200); background: var(--brand-50);">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 4px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                    Cetak
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="3"></rect><rect x="14" y="7" width="3" height="3"></rect><rect x="7" y="14" width="3" height="3"></rect><rect x="14" y="14" width="3" height="3"></rect></svg>
                                </div>
                                <div class="empty-state-title">Data tidak ditemukan</div>
                                <div class="empty-state-desc">Silakan sesuaikan filter atau kata kunci Anda.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($qrs->total() > 0)
        <div class="card-footer" style="padding: 16px 20px; border-top: 1px solid var(--n-200);">
            {{ $qrs->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>

<div id="qrModal" class="modal-overlay">
    <div class="modal" style="max-width:400px;">
        <div class="modal-header" style="padding: 20px 24px; border-bottom: 1px solid var(--n-200); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin:0; font-size:18px; font-weight:600; color:var(--n-900);">Pindai QR Kendaraan</h3>
            <button onclick="closeQrModal()" type="button" class="btn btn-icon btn-secondary" style="border: none;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="modal-body" style="padding: 32px 24px; display: flex; flex-direction: column; align-items: center; text-align: center;">
            <div style="display: inline-block; padding: 12px; border: 2px solid var(--n-200); border-radius: var(--r-lg); background: #fff; margin-bottom: 24px; box-shadow: var(--shadow-sm); position: relative;">
                <img id="qrModalImg" src="" alt="QR Besar" style="width: 200px; height: 200px; display: block; border-radius: 4px;">
                <img src="{{ asset('assets/images/logobkl.png') }}" alt="logo" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 42px; height: 42px; padding: 2px; background: #fff; border-radius: 6px; object-fit: contain;">
            </div>
            
            <div style="font-size: 13px; color: var(--n-500); margin-bottom: 8px;">Token ID:</div>
            <code id="qrModalToken" style="font-size: 16px; font-weight: 700; color: var(--brand-700); background: var(--brand-50); padding: 8px 24px; border-radius: var(--r-md); letter-spacing: 2px; border: 1px solid var(--brand-200); font-family: monospace; display: inline-block;"></code>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Filter Panel
    const btnToggleFilter = document.getElementById('btnToggleFilter');
    const filterPanel = document.getElementById('advancedFilterPanel');
    
    if (btnToggleFilter && filterPanel) {
        btnToggleFilter.addEventListener('click', () => {
            filterPanel.classList.toggle('active');
        });
    }

    // Portal modal ke body agar overlay full viewport
    document.body.appendChild(document.getElementById('qrModal'));
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

function openQrModal(token, url) {
    document.getElementById('qrModalToken').textContent = token;
    document.getElementById('qrModalImg').src = `https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=${encodeURIComponent(url)}&color=000000&bgcolor=ffffff&margin=2`;
    document.getElementById('qrModal').classList.add('active');
}

function closeQrModal() {
    document.getElementById('qrModal').classList.remove('active');
}

document.getElementById('qrModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQrModal();
    }
});

function toggleAll(master) {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = master.checked);
    updateSelection();
}

function updateSelection() {
    const checked = document.querySelectorAll('.row-check:checked');
    const count = checked.length;
    const btn = document.getElementById('btn-print-selected');
    document.getElementById('selected-count').textContent = count;
    btn.disabled = count === 0;
    btn.style.opacity = count === 0 ? '0.5' : '1';
    btn.style.cursor = count === 0 ? 'not-allowed' : 'pointer';
}

function printSingleEl(btn) {
    const row = btn.closest('tr');
    submitPrint([{ token: row.dataset.token, nama: row.dataset.nama, plat: row.dataset.plat }]);
}

function printSelected() {
    const checked = document.querySelectorAll('.row-check:checked');
    if (checked.length === 0) return;
    const items = Array.from(checked).map(cb => {
        const row = cb.closest('tr');
        return { token: row.dataset.token, nama: row.dataset.nama, plat: row.dataset.plat };
    });
    submitPrint(items);
}

function submitPrint(items) {
    document.getElementById('input-print-items').value = JSON.stringify(items);
    document.getElementById('form-print').submit();
}
</script>
@endpush
@endsection
