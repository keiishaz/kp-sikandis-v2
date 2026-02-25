@extends('layouts.admin')

@section('title', 'Kelola Pegawai')
@section('topbar_title', 'Kelola Pegawai')

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
        <div class="table-header">
            <h3 class="table-title">Daftar Pegawai</h3>
            <div class="table-header-actions">
                <a href="{{ route('admin.pegawai.create') }}" class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span>Tambah Pegawai</span>
                </a>
            </div>
        </div>

        <form class="table-toolbar" method="GET" action="{{ route('admin.pegawai.index') }}">
            <div class="table-toolbar-inner">
                <div class="toolbar-field">
                    <input type="search" name="q" class="table-search-input"
                           placeholder="Cari nama atau NIP..." value="{{ request('q') }}" autocomplete="off">
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px">No</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Unit</th>
                        <th>Sub Unit</th>
                        <th class="col-actions">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawais as $i => $pegawai)
                        <tr>
                            <td>{{ $pegawais->firstItem() + $i }}</td>
                            <td>{{ $pegawai->nama }}</td>
                            <td style="font-family:monospace;letter-spacing:.5px">{{ $pegawai->nip }}</td>
                            <td>{{ $pegawai->jabatan }}</td>
                            <td>{{ $pegawai->unit?->nama_unit ?? '-' }}</td>
                            <td>{{ $pegawai->subUnit?->nama_sub_unit ?? '-' }}</td>
                            <td class="col-actions">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.pegawai.edit', $pegawai) }}" class="btn-action btn-edit" title="Edit">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.pegawai.destroy', $pegawai) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus">
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
                            <td colspan="7" class="empty-state">
                                <div class="empty-content">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12" y2="16"></line>
                                    </svg>
                                    <p>Belum ada data pegawai.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">{{ $pegawais->links() }}</div>
    </section>
@endsection
