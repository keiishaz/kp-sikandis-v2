@extends('layouts.app')

@section('title', 'Kelola Operator - SIKANDIS')
@section('topbar_title', 'Kelola Operator')

@section('content')
<div class="dashboard">



    {{-- PHP Logic --}}
    @php
        $baseQuery    = request()->except('page');
        $currentSort  = request('sort');
        $currentDir   = request('dir');

        $sortLink = function (string $key) use ($baseQuery, $currentSort, $currentDir) {
            $dir = ($currentSort === $key && $currentDir === 'asc') ? 'desc' : 'asc';
            return route('kelola-operator.index', array_merge($baseQuery, ['sort' => $key, 'dir' => $dir]));
        };

        $sortIndicator = function (string $key) use ($currentSort, $currentDir) {
            if ($currentSort !== $key) return '';
            return $currentDir === 'asc' ? ' ▲' : ' ▼';
        };
    @endphp

    {{-- PAGE HEADER --}}
    <div class="page-intro" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 class="page-heading">Kelola Operator</h2>
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
        <div class="table-toolbar" style="padding: 16px 20px; border-bottom: 1px solid var(--n-200);">
            <form method="GET" action="{{ route('kelola-operator.index') }}">
                <div class="search-input-wrapper" style="max-width: 320px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="search" name="q" class="search-input" placeholder="Cari Nama atau NIP Operator..." value="{{ request('q') }}" autocomplete="off">
                </div>
            </form>
        </div>

        <div class="card-body-flush table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>
                            <a href="{{ $sortLink('name') }}" style="color: inherit; text-decoration: none; display: flex; align-items: center;">
                                Nama Operator <span style="font-size: 10px; margin-left: 4px; color: var(--n-400);">{{ $sortIndicator('name') }}</span>
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortLink('nik') }}" style="color: inherit; text-decoration: none; display: flex; align-items: center;">
                                NIK <span style="font-size: 10px; margin-left: 4px; color: var(--n-400);">{{ $sortIndicator('nik') }}</span>
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortLink('nip') }}" style="color: inherit; text-decoration: none; display: flex; align-items: center;">
                                NIP <span style="font-size: 10px; margin-left: 4px; color: var(--n-400);">{{ $sortIndicator('nip') }}</span>
                            </a>
                        </th>
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
                                <a href="{{ route('kelola-operator.show', $op) }}" class="btn btn-icon" style="background:var(--n-100); color:var(--n-600);" title="Detail">
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
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

        @if($operators->hasPages())
        <div class="card-footer" style="padding: 16px 20px; border-top: 1px solid var(--n-200);">
            {{ $operators->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
