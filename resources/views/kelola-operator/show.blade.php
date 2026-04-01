@extends('layouts.app')

@section('title', 'Detail Operator — SIKANDIS')
@section('topbar_title', 'Kelola Operator')

@section('content')
<style>
    .info-label { font-size: 13px; color: var(--n-500); font-weight: 500; margin-bottom: 2px; }
    .info-value { font-size: 14px; color: var(--n-900); font-weight: 600; }
    .icon-container {
        width: 38px; height: 38px; 
        background: var(--brand-50); 
        border-radius: 10px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        color: var(--brand-600);
        margin-right: 12px;
        flex-shrink: 0;
        border: 1px solid var(--brand-100);
    }
    .data-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px dashed var(--n-200);
    }
    .data-row:last-child { border-bottom: none; padding-bottom: 4px; }
</style>

<div class="dashboard">

    {{-- PAGE HEADER --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
        <div>
            <nav aria-label="breadcrumb" style="margin-bottom: 8px; font-size: 13px;">
                <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                    <li><a href="{{ route('kelola-operator.index') }}" style="color: var(--n-500); text-decoration: none; font-weight: 500;">Kelola Operator</a></li>
                    <li style="color: var(--n-400);"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg></li>
                    <li style="color: var(--n-900); font-weight: 600;">{{ $operator->name }}</li>
                </ol>
            </nav>
            <h2 class="page-heading" style="margin-bottom: 10px; font-size: 22px;">Profil Operator</h2>
            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <span class="plat-badge" style="letter-spacing: 0.5px; font-size: 13px; padding: 4px 12px;">{{ $operator->nip }}</span>
                <span class="badge badge-neutral" style="padding: 4px 10px; font-size: 11.5px;">Role: Operator Admin</span>
                <span class="badge badge-success" style="padding: 4px 10px; font-size: 11.5px;">Status: AKTIF</span>
            </div>
        </div>
        
        <div style="display: flex; gap: 8px; align-items: center;">
            <a href="{{ route('kelola-operator.index') }}" class="btn btn-secondary btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right: 6px;"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali
            </a>
            <a href="{{ route('kelola-operator.edit', $operator->id) }}" class="btn btn-primary btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right: 6px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Profil
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        
        {{-- CARD 1: INFORMASI AKUN --}}
        <div class="card" style="box-shadow: 0 2px 4px rgba(0,0,0,0.04); border: 1px solid var(--n-200);">
            <div class="card-header" style="padding: 16px 20px; display: flex; align-items: center;">
                <div class="icon-container">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <h3 class="card-title" style="font-size: 15px; font-weight: 700;">Informasi Akun</h3>
            </div>
            <div class="card-body" style="padding: 4px 20px 20px;">
                <div class="data-row">
                    <span class="info-label">Nama Lengkap</span>
                    <span class="info-value">{{ $operator->name }}</span>
                </div>
                <div class="data-row">
                    <span class="info-label">NIP / Username</span>
                    <span class="info-value" style="font-family: 'JetBrains Mono', monospace; font-size: 13px;">{{ $operator->nip }}</span>
                </div>
                <div class="data-row">
                    <span class="info-label">Terdaftar Pada</span>
                    <span class="info-value" style="color: var(--n-600); font-size: 13.5px;">{{ $operator->created_at ? \Carbon\Carbon::parse($operator->created_at)->translatedFormat('d F Y, H:i') : '-' }}</span>
                </div>
                <div class="data-row">
                    <span class="info-label">Pembaruan Sandi</span>
                    <span class="info-value" style="color: var(--n-600); font-size: 13.5px;">{{ $operator->password_changed_at ? \Carbon\Carbon::parse($operator->password_changed_at)->translatedFormat('d F Y') : 'Belum Pernah' }}</span>
                </div>
            </div>
        </div>

        {{-- CARD 2: AKTIVITAS LOGIN --}}
        <div class="card" style="box-shadow: 0 2px 4px rgba(0,0,0,0.04); border: 1px solid var(--n-200);">
            <div class="card-header" style="padding: 16px 20px; display: flex; align-items: center;">
                <div class="icon-container">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                </div>
                <h3 class="card-title" style="font-size: 15px; font-weight: 700;">Aktivitas Keamanan</h3>
            </div>
            <div class="card-body" style="padding: 4px 20px 20px;">
                @if($operator->last_login_at)
                    <div class="data-row">
                        <span class="info-label">Sesi Terakhir</span>
                        <span class="info-value" style="color: var(--brand-700); font-size: 13.5px;">{{ \Carbon\Carbon::parse($operator->last_login_at)->translatedFormat('l, d F Y' ) }} — <span style="font-weight: 500;">{{ \Carbon\Carbon::parse($operator->last_login_at)->format('H:i:s') }}</span></span>
                    </div>
                    <div class="data-row">
                        <span class="info-label">Alamat IP</span>
                        <span class="info-value" style="font-family: 'JetBrains Mono', monospace; font-size: 13px;">{{ $operator->last_login_ip ?? '-' }}</span>
                    </div>
                    <div style="padding-top: 14px;">
                        <span class="info-label" style="display: block; margin-bottom: 8px;">Log Perangkat & Browser</span>
                        <div style="padding: 12px; background: var(--n-50); border-radius: 10px; border: 1px solid var(--n-200);">
                            @php
                                $ua = $operator->last_login_user_agent ?? '-';
                                $os = 'Unknown OS'; $browser = 'Unknown Browser';
                                if (preg_match('/windows nt 10/i', $ua)) $os = 'Windows 10/11';
                                elseif (preg_match('/macintosh|mac os x/i', $ua)) $os = 'Mac OS X';
                                elseif (preg_match('/android/i', $ua)) $os = 'Android';
                                elseif (preg_match('/iphone|ipad|ipod/i', $ua)) $os = 'iOS';
                                if (preg_match('/edg/i', $ua)) $browser = 'Microsoft Edge';
                                elseif (preg_match('/chrome/i', $ua)) $browser = 'Google Chrome';
                                elseif (preg_match('/firefox/i', $ua)) $browser = 'Mozilla Firefox';
                                elseif (preg_match('/safari/i', $ua)) $browser = 'Apple Safari';
                            @endphp
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                                <span style="font-size: 10.5px; font-weight: 700; background: #fff; color: var(--n-800); border: 1px solid var(--n-200); padding: 3px 8px; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">{{ $os }}</span>
                                <span style="font-size: 10.5px; font-weight: 700; background: #fff; color: var(--n-800); border: 1px solid var(--n-200); padding: 3px 8px; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">{{ $browser }}</span>
                            </div>
                            <div style="font-family: 'Consolas', monospace; font-size: 10px; color: var(--n-400); word-break: break-all; line-height: 1.5;">
                                {{ $ua }}
                            </div>
                        </div>
                    </div>
                @else
                    <div style="text-align: center; padding: 32px 20px;">
                        <div style="width: 48px; height: 48px; background: var(--n-100); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; color: var(--n-400);">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <h4 style="font-size: 15px; font-weight: 700; color: var(--n-800); margin-bottom: 6px;">Sesi Belum Ditemukan</h4>
                        <p style="font-size: 13.5px; color: var(--n-500); max-width: 240px; margin: 0 auto;">Operator ini belum tercatat melakukan aktivitas login ke dalam aplikasi.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
