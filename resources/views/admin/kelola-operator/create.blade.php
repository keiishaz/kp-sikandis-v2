@extends('layouts.admin')

@section('title', 'Tambah Operator')
@section('topbar_title', 'Kelola Operator')

@section('content')
    <section class="form-container">
        <div class="card">

            <nav style="font-size:13px;color:#94a3b8;margin-bottom:20px;display:flex;align-items:center;gap:6px;">
                <a href="{{ route('admin.kelola-operator.index') }}" style="color:#3b82f6;text-decoration:none;font-weight:500;">Kelola Operator</a>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                <span style="color:#334155;">Tambah Operator</span>
            </nav>

            <h3 style="margin:0 0 20px;font-size:16px;font-weight:600;color:#0f172a;">Form Tambah Operator</h3>

            <form method="POST" action="{{ route('admin.kelola-operator.store') }}" novalidate>
                @csrf

                <div class="form-field">
                    <label for="name" style="display:block;margin-bottom:6px;font-size:13px;font-weight:500;color:#475569;">Nama</label>
                    <input id="name" name="name" value="{{ old('name') }}"
                           style="width:100%;box-sizing:border-box;" autofocus>
                    @error('name')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label for="nip" style="display:block;margin-bottom:6px;font-size:13px;font-weight:500;color:#475569;">NIP</label>
                    <input id="nip" name="nip" type="text" value="{{ old('nip') }}"
                           style="width:100%;box-sizing:border-box;">
                    @error('nip')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label for="password" style="display:block;margin-bottom:6px;font-size:13px;font-weight:500;color:#475569;">Password</label>
                    <input id="password" name="password" type="password"
                           style="width:100%;box-sizing:border-box;">
                    @error('password')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:24px;">
                    <a class="btn" href="{{ route('admin.kelola-operator.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </section>
@endsection
