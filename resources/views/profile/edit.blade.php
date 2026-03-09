@extends('layouts.admin')

@section('title', 'Edit Profil Pengguna')

@section('topbar_title', 'Edit Profil')

@section('content')
<div class="content-header" style="margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
    <a href="{{ url()->previous() }}" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px; background: #fff; border: 1px solid #e2e8f0; color: #475569; text-decoration: none;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
    </a>
    <div>
        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0;">Pengaturan Profil</h2>
        <p style="font-size: 13px; color: #64748b; margin: 4px 0 0;">Perbarui informasi pribadi dan atur kata sandi akun Anda.</p>
    </div>
</div>

<div style="background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="padding: 24px;">
            @if(session('success'))
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 8px;">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'">
                @error('name') <span style="font-size: 12px; color: #ef4444; margin-top: 6px; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 8px;">Password Baru <span style="color:#94a3b8; font-weight:400; font-size:12px; margin-left: 4px;">(Opsional)</span></label>
                <input type="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah password" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'">
                @error('password') <span style="font-size: 12px; color: #ef4444; margin-top: 6px; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 8px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 8px;">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" placeholder="Masukan ulang password baru Anda" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'" onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'">
            </div>
        </div>

        <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ url()->previous() }}" style="padding: 9px 18px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-size: 13px; font-weight: 600; border-radius: 8px; cursor: pointer; text-decoration: none;">Batal</a>
            <button type="submit" style="padding: 9px 18px; border: none; background: #2563eb; color: #fff; font-size: 13px; font-weight: 600; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Simpan Profil
            </button>
        </div>
    </form>
</div>
@endsection
