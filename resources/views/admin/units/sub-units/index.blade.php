@extends('layouts.admin')

@section('title', 'Sub Unit - ' . $unit->nama_unit . ' - SIKANDIS')
@section('topbar_title', 'Master Data')

@section('content')
<div class="dashboard">

    {{-- Toast Notification --}}
    @if(session('success') || session('error'))
        <div id="toast-notification" class="toast-notification {{ session('success') ? 'toast-success' : 'toast-error' }}">
            @if(session('success'))
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="toast-icon" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            @else
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="toast-icon" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            @endif
            <span>{{ session('success') ?? session('error') }}</span>
            <button onclick="document.getElementById('toast-notification').remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;opacity:.6;padding:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <script>setTimeout(()=>{const t=document.getElementById('toast-notification');if(t)t.style.opacity='0'; setTimeout(()=>t&&t.remove(),300);},4000);</script>
    @endif

    {{-- BREADCRUMB & HEADER --}}
    <div class="page-intro" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px;">
        <div>
            <nav aria-label="breadcrumb" style="margin-bottom: 12px; font-size: 13.5px;">
                <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <li>
                        <a href="{{ route('admin.units.index') }}" style="color: var(--n-500); text-decoration: none; font-weight: 500;">Unit Kerja</a>
                    </li>
                    <li style="color: var(--n-400);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </li>
                    <li style="color: var(--n-900); font-weight: 600;" aria-current="page">{{ $unit->nama_unit }}</li>
                </ol>
            </nav>
            <h2 class="page-heading">Daftar Sub Unit</h2>
        </div>
        <div>
            <a href="{{ route('admin.units.sub-units.create', $unit) }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Sub Unit
            </a>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card">
        <div class="card-body-flush table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px; text-align: center;">No</th>
                        <th>Nama Sub Unit</th>
                        <th class="action-cell">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subUnits as $i => $subUnit)
                        <tr>
                            <td style="text-align: center;">{{ $subUnits->firstItem() + $i }}</td>
                            <td><div class="cell-primary">{{ $subUnit->nama_sub_unit }}</div></td>
                            <td class="action-cell">
                                <a href="{{ route('admin.units.sub-units.edit', [$unit, $subUnit]) }}" class="btn btn-icon btn-secondary" title="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>
                                <form action="{{ route('admin.units.sub-units.destroy', [$unit, $subUnit]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sub unit ini?');">
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
                                    <div class="empty-state-title">Belum ada data sub unit.</div>
                                    <div class="empty-state-desc">Silakan tambah data sub unit baru.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subUnits->hasPages())
        <div class="card-footer" style="padding: 16px 20px; border-top: 1px solid var(--n-200);">
            {{ $subUnits->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
