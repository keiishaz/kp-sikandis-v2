@extends('layouts.admin')

@section('title', 'Sub Unit - ' . $unit->nama_unit)
@section('topbar_title', 'Sub Unit Kerja')

@section('content')
    {{-- Toast Notification --}}
    @if(session('success') || session('error'))
        <div id="toast-notification" class="toast-notification {{ session('success') ? 'toast-success' : 'toast-error' }}">
            @if(session('success'))
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            @else
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            @endif
            <span style="font-size:14px;font-weight:500">{{ session('success') ?? session('error') }}</span>
            <button onclick="document.getElementById('toast-notification').remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;opacity:.6;padding:0;">✕</button>
        </div>
        <script>setTimeout(()=>{const t=document.getElementById('toast-notification');if(t)t.remove();},4000);</script>
    @endif

    <section class="table-container">

        {{-- Breadcrumb di dalam card/section --}}
        <nav style="font-size:13px;color:#94a3b8;margin-bottom:16px;display:flex;align-items:center;gap:6px;">
            <a href="{{ route('admin.units.index') }}" style="color:#3b82f6;text-decoration:none;font-weight:500;">Unit Kerja</a>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            <span style="color:#334155;">{{ $unit->nama_unit }}</span>
        </nav>

        <div class="table-header">
            <h3 class="table-title">Daftar Sub Unit</h3>
            <div class="table-header-actions">
                <button type="button" class="btn btn-primary" data-modal-open="modal-sub-unit-create">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span>Tambah Sub Unit</span>
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px">No</th>
                        <th>Nama Sub Unit</th>
                        <th class="col-actions">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subUnits as $i => $subUnit)
                        <tr>
                            <td>{{ $subUnits->firstItem() + $i }}</td>
                            <td>{{ $subUnit->nama_sub_unit }}</td>
                            <td class="col-actions">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.units.sub-units.edit', [$unit, $subUnit]) }}"
                                       class="btn-action btn-edit" title="Edit">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.units.sub-units.destroy', [$unit, $subUnit]) }}"
                                          method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus sub unit ini?')">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-state">
                                <div class="empty-content">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12" y2="16"></line>
                                    </svg>
                                    <p>Belum ada sub unit yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">{{ $subUnits->links() }}</div>
    </section>

    {{-- Modal: Tambah Sub Unit --}}
    <div class="modal-overlay {{ $errors->any() ? 'active' : '' }}" id="modal-sub-unit-create">
        <div class="modal" role="dialog" aria-modal="true">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Sub Unit</h3>
                <button type="button" class="modal-close" data-modal-close="modal-sub-unit-create">✕</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.units.sub-units.store', $unit) }}">
                    @csrf
                    <div class="form-field">
                        <label for="nama_sub_unit">Nama Sub Unit</label>
                        <input id="nama_sub_unit" name="nama_sub_unit" value="{{ old('nama_sub_unit') }}" required autofocus>
                        @error('nama_sub_unit')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-modal-close="modal-sub-unit-create">Batal</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
