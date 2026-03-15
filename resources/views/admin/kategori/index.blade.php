@extends('layouts.admin')

@section('title', 'Kelola Kategori Kendaraan - SIKANDIS')
@section('topbar_title', 'Master Data')

@section('content')
<div class="dashboard">


    {{-- PAGE HEADER --}}
    <div class="page-intro" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 class="page-heading">Kelola Kategori Kendaraan</h2>
        </div>
        <div>
            <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Kategori
            </a>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card" style="margin-top: 24px;">
        <div class="table-toolbar" style="padding: 16px 20px; border-bottom: 1px solid var(--n-200);">
            <form method="GET" action="{{ route('admin.kategori.index') }}">
                <div class="search-input-wrapper" style="max-width: 320px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="search" name="q" class="search-input" placeholder="Cari nama kategori..." value="{{ request('q') }}" autocomplete="off">
                </div>
            </form>
        </div>

        <div class="card-body-flush table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px; text-align: center;">No</th>
                        <th>Nama Kategori</th>
                        <th class="action-cell">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $i => $kategori)
                        <tr>
                            <td style="text-align: center;">{{ $kategoris->firstItem() + $i }}</td>
                            <td><div class="cell-primary">{{ $kategori->nama_kategori }}</div></td>
                            <td class="action-cell">
                                <a href="{{ route('admin.kategori.edit', $kategori) }}" class="btn btn-icon btn-secondary" title="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>
                                <form action="{{ route('admin.kategori.destroy', $kategori) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-secondary" style="color: var(--danger-600); border-color: var(--danger-200); background-color: var(--danger-50);" title="Hapus">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    </div>
                                    <div class="empty-state-title">Belum ada data kategori.</div>
                                    <div class="empty-state-desc">Silakan tambah kategori kendaraan baru.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kategoris->hasPages())
        <div class="card-footer" style="padding: 16px 20px; border-top: 1px solid var(--n-200);">
            {{ $kategoris->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
