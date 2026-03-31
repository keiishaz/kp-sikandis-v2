@extends('layouts.app')

@section('title', 'Edit Sub Unit - SIKANDIS')
@section('topbar_title', 'Master Data')

@section('content')
<div class="form-container">

    {{-- BREADCRUMB & HEADER --}}
    <div style="margin-bottom: 24px;">
        <nav aria-label="breadcrumb" style="margin-bottom: 12px; font-size: 13.5px;">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px;">
                <li>
                    <a href="{{ route('units.index') }}" style="color: var(--n-500); text-decoration: none; font-weight: 500;">Unit Kerja</a>
                </li>
                <li style="color: var(--n-400);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </li>
                <li>
                    <a href="{{ route('units.sub-units.index', $unit) }}" style="color: var(--n-500); text-decoration: none; font-weight: 500;">{{ $unit->nama_unit }}</a>
                </li>
                <li style="color: var(--n-400);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </li>
                <li style="color: var(--n-900); font-weight: 600;" aria-current="page">Edit Sub Unit</li>
            </ol>
        </nav>
        <h2 class="page-heading">Ubah Nama Sub Unit</h2>
    </div>

    {{-- FORM CARD --}}
    <div class="card form-page">
        <form method="POST" action="{{ route('units.sub-units.update', [$unit, $sub_unit]) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="nama_sub_unit" class="form-label">Nama Sub Unit <span class="text-danger">*</span></label>
                    <input type="text" id="nama_sub_unit" name="nama_sub_unit" value="{{ old('nama_sub_unit', $sub_unit->nama_sub_unit) }}" class="form-input" autofocus>
                    @error('nama_sub_unit')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card-footer" style="padding: 20px 24px; border-top: 1px solid var(--n-200); background: var(--surface-page); display: flex; justify-content: flex-end; gap: 12px; border-bottom-left-radius: var(--r-xl); border-bottom-right-radius: var(--r-xl);">
                <a href="{{ route('units.sub-units.index', $unit) }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

</div>
@endsection
