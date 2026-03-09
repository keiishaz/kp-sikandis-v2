@extends('layouts.admin')

@section('title', 'QR Kendaraan - SIKANDIS')
@section('topbar_title', 'QR Kendaraan')

@section('content')

{{-- Hidden form to pass data to print page --}}
<form id="form-print" action="{{ route('admin.qr-kendaraan.print') }}" method="POST" target="_blank" style="display:none;">
    @csrf
    <input type="hidden" name="items" id="input-print-items">
</form>

{{-- Main Card --}}
<div style="background:#fff; border:1px solid var(--gray-200); border-radius:10px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05); overflow:hidden;">

    {{-- Toolbar --}}
    <div style="padding:16px 24px; border-bottom:1px solid var(--gray-200); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; background:#fff;">
        <div>
            <h3 style="margin:0 0 2px; font-size:16px; font-weight:700; color:var(--gray-800);">QR Kendaraan</h3>
            <p style="margin:0; font-size:12.5px; color:var(--gray-500);">Daftar QR Code kendaraan aktif — scan counter &amp; cetak label</p>
        </div>
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            {{-- Search --}}
            <form method="GET" action="{{ route('admin.qr-kendaraan.index') }}" style="display:flex; gap:8px;">
                <div style="position:relative;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:11px; color:var(--gray-400);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kendaraan atau token..."
                        style="height:36px; padding:0 12px 0 32px; border:1px solid var(--gray-300); border-radius:6px; outline:none; width:230px; font-size:13.5px; font-family:inherit;"
                        onfocus="this.style.borderColor='var(--primary-color)'" onblur="this.style.borderColor='var(--gray-300)'">
                </div>
                <button type="submit" class="btn btn-outline" style="height:36px; padding:0 14px; border-color:var(--gray-300); font-weight:600; font-size:13.5px;">Cari</button>
                @if(request('q'))
                    <a href="{{ route('admin.qr-kendaraan.index') }}" class="btn btn-outline" style="height:36px; padding:0 14px; font-size:13.5px;">Reset</a>
                @endif
            </form>

            {{-- Cetak Terpilih --}}
            <button id="btn-print-selected" onclick="printSelected()" disabled
                style="height:36px; display:inline-flex; align-items:center; gap:7px; padding:0 16px; background:var(--primary-color); color:#fff; border:none; border-radius:6px; font-size:13.5px; font-weight:600; cursor:pointer; opacity:0.4; transition:all 0.2s; box-shadow:0 2px 4px rgba(37,99,235,0.2);">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak QR (<span id="selected-count">0</span>)
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:44px; padding:12px 8px 12px 20px;">
                        <input type="checkbox" id="check-all" style="cursor:pointer; width:15px; height:15px;" onchange="toggleAll(this)">
                    </th>
                    <th>Kendaraan</th>
                    <th>Token QR</th>
                    <th style="text-align:center; width:110px;">QR Code</th>
                    <th style="text-align:center; width:140px;">Jumlah Scan</th>
                    <th style="text-align:center; width:100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($qrs as $qr)
                <tr data-token="{{ $qr->token }}" data-nama="{{ $qr->kendaraan->nama_kendaraan }}" data-plat="{{ $qr->kendaraan->no_polisi }}">
                    <td style="padding:12px 8px 12px 20px;">
                        <input type="checkbox" class="row-check" value="{{ $qr->id }}" data-token="{{ $qr->token }}"
                            style="cursor:pointer; width:15px; height:15px;" onchange="updateSelection()">
                    </td>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:38px; height:38px; border-radius:8px; background:#eff6ff; color:var(--primary-color); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                            </div>
                            <div>
                                <div style="font-weight:700; color:#0f172a; font-size:14px; margin-bottom:4px;">{{ $qr->kendaraan->nama_kendaraan }}</div>
                                <div style="display:flex; align-items:center; gap:8px; font-size:12px; color:#475569;">
                                    <span style="font-weight:700; background:#f1f5f9; padding:2px 6px; border-radius:4px; border:1px solid #e2e8f0; font-family:monospace; font-size:11px; color:#1e293b; letter-spacing:0.5px;">{{ $qr->kendaraan->no_polisi }}</span>
                                    <span style="color:#cbd5e1;">•</span>
                                    <span>{{ $qr->kendaraan->kategori->nama_kategori ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('kendaraan.publik', $qr->token) }}" target="_blank" style="text-decoration:none;">
                            <code style="background:#f8fafc; padding:4px 10px; border-radius:6px; font-size:13px; letter-spacing:1.5px; color:#1e40af; font-weight:700; border:1px solid #e2e8f0; font-family:monospace; transition:all 0.2s; display:inline-block;" onmouseover="this.style.background='#eff6ff'; this.style.borderColor='#93c5fd'" onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'">{{ $qr->token }}</code>
                        </a>
                    </td>
                    <td style="text-align:center; vertical-align:middle;">
                        {{-- QR Code sebagai gambar --}}
                        <div style="display:flex; justify-content:center; align-items:center;">
                            <div onclick="openQrModal('{{ $qr->token }}', '{{ route('kendaraan.publik', $qr->token) }}')" style="position:relative; display:inline-block; background:#fff; padding:4px; border:1px solid #e2e8f0; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.06); cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" title="Perbesar QR">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ urlencode(route('kendaraan.publik', $qr->token)) }}&color=000000&bgcolor=ffffff&margin=2"
                                     alt="QR {{ $qr->token }}" width="70" height="70" style="display:block; border-radius:3px;" loading="lazy">
                                {{-- Logo Pemprov di tengah --}}
                                <img src="{{ asset('assets/images/logobkl.png') }}" alt="logo"
                                     style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:16px; height:16px; object-fit:contain; border-radius:2px; background:#fff; padding:1px;">
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <span style="display:inline-flex; align-items:center; gap:5px; background:{{ $qr->scan_count > 0 ? '#eff6ff' : '#f8fafc' }}; color:{{ $qr->scan_count > 0 ? '#1d4ed8' : '#64748b' }}; padding:4px 12px; border-radius:20px; font-size:13px; font-weight:600; border:1px solid {{ $qr->scan_count > 0 ? '#bfdbfe' : '#e2e8f0' }};">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            {{ number_format($qr->scan_count) }}&times;
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <button type="button"
                            onclick="printSingleEl(this)"
                            style="display:inline-flex; align-items:center; gap:5px; padding:6px 12px; background:#f0f9ff; color:#0369a1; border:1px solid #bae6fd; border-radius:6px; font-size:12.5px; font-weight:600; cursor:pointer; transition:all 0.2s;"
                            onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                            Cetak
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:60px 20px; text-align:center; background:#f8fafc;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin:0 auto 16px; display:block;"><rect x="3" y="3" width="5" height="5"></rect><rect x="16" y="3" width="5" height="5"></rect><rect x="3" y="16" width="5" height="5"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                        <h5 style="margin:0 0 6px; font-size:15px; color:var(--gray-600);">Belum ada data QR Kendaraan</h5>
                        <p style="margin:0; font-size:13px; color:var(--gray-400);">QR akan muncul di sini setelah kendaraan terdaftar di sistem.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="padding:16px 24px; border-top:1px solid var(--gray-200); background:#fff;">
        <div class="pagination-wrapper">{{ $qrs->appends(request()->query())->links() }}</div>
    </div>

{{-- Modal QR Besar --}}
<div id="qrModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.6); align-items:center; justify-content:center; backdrop-filter:blur(4px);">
    <div style="background:#fff; padding:24px; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.2); width:320px; text-align:center; position:relative;">
        <button onclick="closeQrModal()" style="position:absolute; top:12px; right:12px; background:#f1f5f9; border:none; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#64748b; font-size:18px; line-height:1; transition:0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">&times;</button>
        <h4 style="margin:0 0 16px; font-size:16px; font-weight:700; color:#1e293b;">Pindai QR Kendaraan</h4>
        <div style="position:relative; display:inline-block; padding:10px; border:2px solid #e2e8f0; border-radius:12px; background:#fff; margin-bottom:16px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);">
            <img id="qrModalImg" src="" alt="QR Besar" style="width:220px; height:220px; display:block; border-radius:4px;">
            <img src="{{ asset('assets/images/logobkl.png') }}" alt="logo" style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:46px; height:46px; padding:3px; background:#fff; border-radius:6px; object-fit:contain; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
        </div>
        <div style="font-size:14px; color:#475569; margin-bottom:6px;">Token ID:</div>
        <code id="qrModalToken" style="font-size:16px; font-weight:700; color:#1e40af; background:#eff6ff; padding:8px 20px; border-radius:6px; letter-spacing:2px; border:1px solid #bfdbfe; font-family:monospace; display:inline-block;"></code>
    </div>
</div>

@push('scripts')
<script>
function openQrModal(token, url) {
    document.getElementById('qrModalToken').textContent = token;
    document.getElementById('qrModalImg').src = `https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=${encodeURIComponent(url)}&color=000000&bgcolor=ffffff&margin=2`;
    document.getElementById('qrModal').style.display = 'flex';
}

function closeQrModal() {
    document.getElementById('qrModal').style.display = 'none';
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
    btn.style.opacity = count === 0 ? '0.4' : '1';
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
