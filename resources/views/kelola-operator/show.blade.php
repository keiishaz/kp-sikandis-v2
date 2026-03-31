@extends('layouts.app')

@section('title', 'Detail Operator - SIKANDIS')
@section('topbar_title', 'Detail Operator')

@section('content')
<div class="dashboard">
    {{-- Breadcrumb & Back --}}
    <div style="margin-bottom: 24px;">
        <nav aria-label="breadcrumb" style="margin-bottom: 12px; font-size: 13.5px;">
            <ol style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px;">
                <li>
                    <a href="{{ route('kelola-operator.index') }}" style="color: var(--n-500); text-decoration: none; font-weight: 500;">Kelola Operator</a>
                </li>
                <li style="color: var(--n-400);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </li>
                <li style="color: var(--n-900); font-weight: 600;" aria-current="page">Detail Operator</li>
            </ol>
        </nav>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-heading">Detail Operator: {{ $operator->name }}</h2>
            <div style="display: flex; gap: 8px;">
                <a href="{{ route('kelola-operator.edit', $operator) }}" class="btn btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit Data
                </a>
            </div>
        </div>
    </div>

    <div class="form-page" style="display: flex; flex-direction: column; gap: 20px;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Akun</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label class="form-label" style="opacity: 0.7;">Nama Lengkap</label>
                        <div style="font-weight: 500; font-size: 15px;">{{ $operator->name }}</div>
                    </div>
                    <div>
                        <label class="form-label" style="opacity: 0.7;">NIP</label>
                        <div style="font-weight: 500; font-size: 15px; font-family: monospace; letter-spacing: 0.5px;">{{ $operator->nip }}</div>
                    </div>
                    <div>
                        <label class="form-label" style="opacity: 0.7;">Terdaftar Sejak</label>
                        <div style="font-size: 14px;">{{ $operator->created_at ? \Carbon\Carbon::parse($operator->created_at)->translatedFormat('d M Y, H:i') : '-' }}</div>
                    </div>
                    <div>
                        <label class="form-label" style="opacity: 0.7;">Update Sandi Terakhir</label>
                        <div style="font-size: 14px;">{{ $operator->password_changed_at ? \Carbon\Carbon::parse($operator->password_changed_at)->translatedFormat('d M Y, H:i') : 'Belum pernah diganti' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aktivitas Login Terakhir</h3>
            </div>
            <div class="card-body">
                @if($operator->last_login_at)
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label class="form-label" style="opacity: 0.7;">Waktu Login</label>
                            <div style="font-size: 14px; font-weight: 500; color: var(--brand-700);">
                                {{ \Carbon\Carbon::parse($operator->last_login_at)->translatedFormat('l, d F Y - H:i:s') }}
                            </div>
                        </div>
                        <div>
                            <label class="form-label" style="opacity: 0.7;">Alamat IP</label>
                            <div style="font-size: 14px; font-family: monospace; letter-spacing: 0.5px;">
                                {{ $operator->last_login_ip ?? '-' }}
                            </div>
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <label class="form-label" style="opacity: 0.7;">Perangkat & Browser (User Agent)</label>
                            <div style="font-size: 13.5px; padding: 10px 12px; background: var(--n-50); border-radius: 6px; color: var(--n-600); border: 1px solid var(--n-200); line-height: 1.5;">
                                @php
                                    $ua = $operator->last_login_user_agent ?? '-';
                                    $os = 'Unknown OS';
                                    $browser = 'Unknown Browser';
                                    
                                    if (preg_match('/windows nt 10/i', $ua)) $os = 'Windows 10/11';
                                    elseif (preg_match('/windows nt 6\.3/i', $ua)) $os = 'Windows 8.1';
                                    elseif (preg_match('/windows nt 6\.2/i', $ua)) $os = 'Windows 8';
                                    elseif (preg_match('/windows nt 6\.1/i', $ua)) $os = 'Windows 7';
                                    elseif (preg_match('/macintosh|mac os x/i', $ua)) $os = 'Mac OS X';
                                    elseif (preg_match('/linux/i', $ua)) $os = 'Linux';
                                    elseif (preg_match('/android/i', $ua)) $os = 'Android';
                                    elseif (preg_match('/iphone|ipad|ipod/i', $ua)) $os = 'iOS';
                                    
                                    if (preg_match('/edg/i', $ua)) $browser = 'Edge';
                                    elseif (preg_match('/chrome|crios/i', $ua)) $browser = 'Chrome';
                                    elseif (preg_match('/firefox|fxios/i', $ua)) $browser = 'Firefox';
                                    elseif (preg_match('/safari/i', $ua)) $browser = 'Safari';
                                @endphp
                                @if($ua !== '-')
                                    <div style="font-weight: 500; margin-bottom: 4px; color: var(--n-800);">
                                        {{ $browser }} di {{ $os }}
                                    </div>
                                @endif
                                <div style="font-size: 12.5px; opacity: 0.8; word-break: break-all;">
                                    {{ $ua }}
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="empty-state" style="padding: 24px;">
                        <div class="empty-state-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div class="empty-state-text">Operator ini belum pernah login.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
