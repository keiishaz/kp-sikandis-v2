@extends('layouts.admin')

@section('title', 'Edit Unit Kerja - SIKANDIS')
@section('topbar_title', 'Master Data')

@section('content')
<div class="dashboard">

    {{-- BREADCRUMB & HEADER --}}
    <div style="margin-bottom: 24px;">
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
        <h2 class="page-heading">Ubah Nama Unit</h2>
    </div>

    {{-- FORM CARD --}}
    <div class="card" style="max-width: 600px;">
        <form method="POST" action="{{ route('admin.units.update', $unit) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="nama_unit" class="form-label">Nama Unit <span class="text-danger">*</span></label>
                    <input type="text" id="nama_unit" name="nama_unit" value="{{ old('nama_unit', $unit->nama_unit) }}" class="form-input" autofocus>
                    @error('nama_unit')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card-footer" style="padding: 20px 24px; border-top: 1px solid var(--n-200); background: var(--surface-page); display: flex; justify-content: flex-end; gap: 12px; border-bottom-left-radius: var(--r-xl); border-bottom-right-radius: var(--r-xl);">
                <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

</div>
@endsection
