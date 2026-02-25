@extends('layouts.admin')

@section('title', 'Admin - Kelola Operator')
@section('topbar_title', 'Kelola Operator')

@section('content')
    <section class="table-container">
        <!-- Header Section -->
        <div class="table-header">
            <h3 class="table-title">Kelola Operator</h3>
            <div class="table-header-actions">
                <a href="{{ route('admin.kelola-operator.create') }}" class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span>Tambah Operator</span>
                </a>
            </div>
        </div>

        <!-- Search Toolbar -->
        <form class="table-toolbar" method="GET" action="{{ route('admin.kelola-operator.index') }}">
            <div class="table-toolbar-inner">
                <div class="toolbar-field">
                    <input type="search"
                           name="q"
                           class="table-search-input"
                           placeholder="Cari Nama atau NIP Operator..."
                           value="{{ request('q') }}"
                           autocomplete="off">
                </div>
            </div>
        </form>

        @php
            $baseQuery    = request()->except('page');
            $currentSort  = request('sort');
            $currentDir   = request('dir');

            $sortLink = function (string $key) use ($baseQuery, $currentSort, $currentDir) {
                $dir = ($currentSort === $key && $currentDir === 'asc') ? 'desc' : 'asc';
                return route('admin.kelola-operator.index', array_merge($baseQuery, ['sort' => $key, 'dir' => $dir]));
            };

            $sortIndicator = function (string $key) use ($currentSort, $currentDir) {
                if ($currentSort !== $key) return '';
                return $currentDir === 'asc' ? ' ▲' : ' ▼';
            };
        @endphp

        @if(session('success'))
            <div class="alert alert-success" style="padding: 10px 14px; background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px; margin-bottom: 14px; color: #065f46;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="col-name">
                            <a class="sort-link" href="{{ $sortLink('name') }}">
                                Nama Operator <span class="sort-indicator">{{ $sortIndicator('name') }}</span>
                            </a>
                        </th>
                        <th class="col-nip">
                            <a class="sort-link" href="{{ $sortLink('nip') }}">
                                NIP <span class="sort-indicator">{{ $sortIndicator('nip') }}</span>
                            </a>
                        </th>
                        <th class="col-actions">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($operators as $op)
                        <tr>
                            <td class="col-name" data-label="Nama Operator">
                                <div class="font-semibold text-gray-800">{{ $op->name }}</div>
                            </td>
                            <td class="col-nip" data-label="NIP">
                                <span class="text-sm font-medium text-gray-600">{{ $op->nip }}</span>
                            </td>
                            <td class="col-actions" data-label="Aksi">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.kelola-operator.edit', $op) }}" class="btn-action btn-edit" title="Edit">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.kelola-operator.destroy', $op) }}" method="POST" style="display:inline;">
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
                            <td colspan="3" class="empty-state">
                                <div class="empty-content">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12" y2="16"></line>
                                    </svg>
                                    <p>Data operator tidak ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $operators->links() }}
        </div>
    </section>
@endsection
