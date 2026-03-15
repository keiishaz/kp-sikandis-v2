@extends('layouts.admin')

@section('title', 'Edit Profil Pengguna - SIKANDIS')
@section('topbar_title', 'Edit Profil')

@section('content')
<div class="dashboard">

    {{-- BREADCRUMB & HEADER --}}
    <div style="margin-bottom: 24px;">
        <nav aria-label="breadcrumb" style="margin-bottom: 12px; font-size: 13.5px;">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px;">
                <li>
                    <a href="{{ url()->previous() }}" style="color: var(--n-500); text-decoration: none; font-weight: 500;">Kembali</a>
                </li>
                <li style="color: var(--n-400);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </li>
                <li style="color: var(--n-900); font-weight: 600;" aria-current="page">Pengaturan Profil</li>
            </ol>
        </nav>
        <h2 class="page-heading">Pengaturan Profil</h2>
        <p class="page-subheading" style="margin-top: 4px;">Perbarui informasi pribadi dan atur kata sandi akun Anda.</p>
    </div>



    {{-- FORM CARD --}}
    <div class="card form-page">
        <form action="{{ route('profile.update') }}" method="POST" novalidate>
            @csrf
            @method('PUT')
            
            <div class="card-body" style="display: flex; flex-direction: column; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" class="form-input" required autofocus>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru <span style="color:var(--n-400); font-weight: 400; font-size: 12px; margin-left: 4px;">(Opsional)</span></label>
                    <input type="password" name="password" class="form-input" placeholder="Biarkan kosong jika tidak ingin mengubah">
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Masukan ulang password baru Anda">
                </div>
            </div>

            <div class="card-footer" style="padding: 20px 24px; border-top: 1px solid var(--n-200); background: var(--surface-page); display: flex; justify-content: flex-end; gap: 12px; border-bottom-left-radius: var(--r-xl); border-bottom-right-radius: var(--r-xl);">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
