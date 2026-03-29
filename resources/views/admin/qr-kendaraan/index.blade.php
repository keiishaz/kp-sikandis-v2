@extends('layouts.admin')

@section('title', 'QR Kendaraan - SIKANDIS')
@section('topbar_title', 'QR Kendaraan')

@section('content')
<div class="dashboard">

    {{-- Hidden form to pass data to print page --}}
    <form id="form-print" action="{{ route('admin.qr-kendaraan.print') }}" method="POST" target="_blank" style="display:none;">
        @csrf
        <input type="hidden" name="items" id="input-print-items">
    </form>

    {{-- PAGE HEADER & TOOLBAR --}}
    <div class="page-intro" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 class="page-heading">QR Kendaraan</h2>
            <p class="page-subheading" style="margin-top: 4px;">Daftar QR Code kendaraan aktif — scan counter &amp; cetak label</p>
        </div>
        
        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <form method="GET" action="{{ route('admin.qr-kendaraan.index') }}" style="display: flex; gap: 8px;">
                <div class="search-input-wrapper" style="width: 240px; margin-bottom: 0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="search" name="q" class="search-input" placeholder="Cari kendaraan atau token..." value="{{ request('q') }}" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-secondary">Cari</button>
                @if(request('q'))
                    <a href="{{ route('admin.qr-kendaraan.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </form>

            <button type="button" id="btn-print-selected" onclick="printSelected()" class="btn btn-primary" disabled style="opacity: 0.5;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Terpilih (<span id="selected-count">0</span>)
            </button>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card">
        <div class="card-body-flush table-wrapper">
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
                                {{ number_format($qr->scan_count) }}x Scan
                            </span>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <button type="button" onclick="printSingleEl(this)" class="btn btn-secondary btn-sm" style="color: var(--brand-700); border-color: var(--brand-200); background: var(--brand-50);">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 4px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                Cetak
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="3"></rect><rect x="14" y="7" width="3" height="3"></rect><rect x="7" y="14" width="3" height="3"></rect><rect x="14" y="14" width="3" height="3"></rect></svg>
                                </div>
                                <div class="empty-state-title">Belum ada data QR Kendaraan</div>
                                <div class="empty-state-desc">QR akan otomatis dibuat setelah kendaraan terdaftar.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($qrs->hasPages())
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Portal modal ke body agar overlay full viewport
    document.body.appendChild(document.getElementById('qrModal'));
});

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
@endsection
