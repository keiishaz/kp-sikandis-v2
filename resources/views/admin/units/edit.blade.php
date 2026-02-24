@extends('layouts.admin')

@section('title', 'Edit Unit Kerja')
@section('topbar_title', 'Edit Unit Kerja')

@section('content')
    <section class="form-container">
        <div class="card">

            {{-- Breadcrumb di dalam card --}}
            <nav style="font-size:13px;color:#94a3b8;margin-bottom:20px;display:flex;align-items:center;gap:6px;">
                <a href="{{ route('admin.units.index') }}" style="color:#3b82f6;text-decoration:none;font-weight:500;">Unit Kerja</a>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                <span style="color:#334155;">{{ $unit->nama_unit }}</span>
            </nav>

            <h3 style="margin:0 0 20px;font-size:16px;font-weight:600;color:#0f172a;">Ubah Nama Unit</h3>

            <form method="POST" action="{{ route('admin.units.update', $unit) }}">
                @csrf
                @method('PUT')

                <div class="form-field">
                    <label for="nama_unit" style="display:block;margin-bottom:6px;font-size:13px;font-weight:500;color:#475569;">Nama Unit</label>
                    <input id="nama_unit" name="nama_unit"
                           value="{{ old('nama_unit', $unit->nama_unit) }}"
                           required
                           style="width:100%;box-sizing:border-box;">
                    @error('nama_unit')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:24px;">
                    <a class="btn" href="{{ route('admin.units.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </section>
@endsection
