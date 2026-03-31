@extends('layouts.app')

@section('title', 'Data Kendaraan ' . (auth()->user()->role->nama_role === 'admin' ? 'Admin' : 'Operator') . ' — SIKANDIS')
@section('topbar_title', 'Data Kendaraan ' . (auth()->user()->role->nama_role === 'admin' ? 'Admin' : 'Operator'))

@section('content')


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
        <a href="{{ route('kendaraan.index', ['status' => 'aktif', 'q' => request('q')]) }}" 
           class="tab-nav-item {{ $status === 'aktif' ? 'active' : '' }}">
            Kendaraan Aktif
            <span class="badge {{ $status === 'aktif' ? 'badge-primary' : 'badge-neutral' }}">{{ $countAktif }}</span>
        </a>
        <a href="{{ route('kendaraan.index', ['status' => 'nonaktif', 'q' => request('q')]) }}" 
           class="tab-nav-item {{ $status === 'nonaktif' ? 'active' : '' }}">
            Kendaraan Nonaktif
           <span class="badge {{ $status === 'nonaktif' ? 'badge-danger' : 'badge-neutral' }}">{{ $countNonaktif }}</span>
        </a>
    </div>

    {{-- SEARCH TOOLBAR & TABLE CARD --}}
    <div class="card" style="border-top-left-radius: 0;">
        <div class="table-toolbar">
            <form action="{{ route('kendaraan.index') }}" method="GET" style="display: flex; gap: 8px;">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="search-input-wrapper">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="search-icon" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nopol atau nama kendaraan..." class="form-input">
                </div>
                <button type="submit" class="btn btn-secondary">Cari</button>
            </form>
            
            <button type="button" onclick="openPrintModal()" class="btn btn-secondary" style="margin-left: auto; color: var(--brand-700); border-color: var(--brand-200); background: var(--brand-50);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Laporan
            </button>
        </div>

        <div class="card-body-flush table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Polisi</th>
                        <th>Nama Kendaraan</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th>Status Pajak</th>
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
                                    <div class="cell-primary">
                                        {{ $k->pemegangAktif->nama_pegawai ?? ($k->pemegangAktif->pegawai->nama ?? 'Pegawai Internal') }}
                                    </div>
                                    @if($k->pemegangAktif->source_system === 'API')
                                        <div style="font-size: 11px; color: var(--n-500); font-family: monospace;">{{ $k->pemegangAktif->nip }}</div>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="action-cell">
                                <a href="{{ route('kendaraan.show', $k->id) }}" class="btn btn-secondary btn-icon" title="Detail">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>
                                <a href="{{ route('kendaraan.edit', $k->id) }}" class="btn btn-secondary btn-icon" title="Edit">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>
                                <form action="{{ route('kendaraan.destroy', $k->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    @php
                                        $btnStyle = $k->status === 'aktif' 
                                            ? 'color: var(--danger-text); border-color: var(--danger-border); background: var(--danger-bg);' 
                                            : 'color: var(--success-text); border-color: var(--success-border); background: var(--success-bg);';
                                        $confirmMsg = $k->status === 'aktif' ? 'Nonaktifkan kendaraan ini?' : 'Aktifkan kendaraan ini?';
                                        $btnTitle = $k->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan';
                                    @endphp
                                    <button type="button" 
                                        onclick="var form = this.closest('form'); SIKANDIS.confirm({title: 'Konfirmasi', message: '{{ $confirmMsg }}', confirmText: 'Ya, Lanjutkan', cancelText: 'Batal', type: 'warning'}).then(function(res) { if(res) form.submit(); })" 
                                        class="btn btn-secondary btn-icon" 
                                        title="{{ $btnTitle }}" 
                                        style="{{ $btnStyle }}">
                                        @if($k->status === 'aktif')
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                                        @else
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        @endif
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle>
                                        </svg>
                                    </div>
                                    <div class="empty-state-title">Tidak ada data kendaraan ditemukan</div>
                                    <div class="empty-state-text">Cobalah kata kunci pencarian yang lain atau tambah kendaraan baru.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($kendaraans->hasPages())
        <div class="card-footer" style="background: white;">
            {{ $kendaraans->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

    {{-- MODAL PRINT FILTER (akan diportal ke body via JS) --}}
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
                        <select name="kategori_id" id="filterKategori" class="form-select" style="width: 100%; padding: 10px 14px; border: 1px solid var(--n-200); border-radius: var(--r-md);">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label" style="font-size: 13.5px; font-weight: 600; color: var(--n-800); margin-bottom: 8px;">Jenis Penggunaan</label>
                        <select name="jenis_penggunaan" id="filterJenis" class="form-select" style="width: 100%; padding: 10px 14px; border: 1px solid var(--n-200); border-radius: var(--r-md);">
                            <option value="">Semua</option>
                            <option value="jabatan">Jabatan</option>
                            <option value="operasional">Operasional</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" style="font-size: 13.5px; font-weight: 600; color: var(--n-800); margin-bottom: 8px;">Status Pajak</label>
                        <select name="status_pajak" id="filterPajak" class="form-select" style="width: 100%; padding: 10px 14px; border: 1px solid var(--n-200); border-radius: var(--r-md);">
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
                            <span id="printCountSpinner" style="display:none; font-size:12px; color: var(--n-400);">memuat...</span>
                        </span>
                    </div>
                </div>

                <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid var(--n-200); display: flex; justify-content: flex-end; gap: 12px; background: var(--n-50); border-bottom-left-radius: var(--r-lg); border-bottom-right-radius: var(--r-lg);">
                    <button type="button" class="btn btn-secondary" onclick="closePrintModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" onclick="closePrintModal()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
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
    // Portal modal ke body agar overlay full viewport (fix shadow issue)
    document.body.appendChild(document.getElementById('modalPrintFilter'));

    // Click outside to close
    document.getElementById('modalPrintFilter').addEventListener('click', function (e) {
        if (e.target === this) closePrintModal();
    });

    // Listen to filter changes for live count
    ['filterKategori', 'filterJenis', 'filterPajak'].forEach(function (id) {
        document.getElementById(id).addEventListener('change', fetchPrintCount);
    });
});

var printCountTimer = null;
var countUrl = '{{ route("kendaraan.print-count") }}';
var printStatus = '{{ $status }}';
var printQ = '{{ addslashes(request("q", "")) }}';

function openPrintModal() {
    document.getElementById('modalPrintFilter').classList.add('active');
    fetchPrintCount();
}

function closePrintModal() {
    document.getElementById('modalPrintFilter').classList.remove('active');
}

function fetchPrintCount() {
    clearTimeout(printCountTimer);
    printCountTimer = setTimeout(function () {
        var params = new URLSearchParams({
            status:           printStatus,
            q:                printQ,
            kategori_id:      document.getElementById('filterKategori').value,
            jenis_penggunaan: document.getElementById('filterJenis').value,
            status_pajak:     document.getElementById('filterPajak').value,
        });

        document.getElementById('printCountVal').textContent = '—';
        document.getElementById('printCountSpinner').style.display = 'inline';

        fetch(countUrl + '?' + params.toString())
            .then(r => r.json())
            .then(function (data) {
                document.getElementById('printCountVal').textContent = data.count;
                document.getElementById('printCountSpinner').style.display = 'none';
            })
            .catch(function () {
                document.getElementById('printCountVal').textContent = '?';
                document.getElementById('printCountSpinner').style.display = 'none';
            });
    }, 300);
}
</script>
@endpush
