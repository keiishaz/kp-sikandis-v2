@extends('layouts.admin')

@section('title', 'Tambah Kategori Kendaraan - SIKANDIS')
@section('topbar_title', 'Master Data')

@section('content')
<div class="dashboard">

    {{-- BREADCRUMB & HEADER --}}
    <div style="margin-bottom: 24px;">
        <nav aria-label="breadcrumb" style="margin-bottom: 12px; font-size: 13.5px;">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px;">
                <li>
                    <a href="{{ route('admin.kategori.index') }}" style="color: var(--n-500); text-decoration: none; font-weight: 500;">Kelola Kategori</a>
                </li>
                <li style="color: var(--n-400);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </li>
                <li style="color: var(--n-900); font-weight: 600;" aria-current="page">Tambah Kategori</li>
            </ol>
        </nav>
        <h2 class="page-heading">Form Tambah Kategori</h2>
    </div>

    {{-- FORM CARD --}}
    <div class="card" style="max-width: 600px;">
        <form method="POST" action="{{ route('admin.kategori.store') }}" novalidate>
            @csrf

            <div class="card-body">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori') }}" class="form-input" placeholder="Contoh: Mobil" autofocus>
                    @error('nama_kategori')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card-footer" style="padding: 20px 24px; border-top: 1px solid var(--n-200); background: var(--surface-page); display: flex; justify-content: flex-end; gap: 12px; border-bottom-left-radius: var(--r-xl); border-bottom-right-radius: var(--r-xl);">
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>

</div>
@endsection
