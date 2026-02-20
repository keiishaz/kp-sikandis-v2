<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIKANDIS')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="{{ asset('assets/images/logo-kominfo.png') }}" alt="Logo Kominfo" style="height: 48px; width: auto; object-fit: contain;">
            </div>
            <h1 class="sidebar-title">SIKANDIS</h1>
            <p class="sidebar-subtitle">Sistem Inventarisasi Kendaraan Dinas<br>Dinas Kominfo Kota Bengkulu</p>
        </div>

        <nav class="sidebar-nav">
            @auth
                @php
                    $isAdmin = auth()->user()->role->nama_role === 'admin';
                    $isOperator = auth()->user()->role->nama_role === 'operator';
                @endphp

                @if($isAdmin)
                    <div class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 20V14H14V20H19V12H22L12 3L2 12H5V20H10Z" fill="currentColor"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </div>

                    <!-- KENDARAAN GROUP -->
                    <div class="nav-group-header">Kendaraan</div>
                    <div class="nav-group-items">
                        <div class="nav-item">
                            <!-- Placeholder for Pemegang -->
                            <a href="#" class="nav-link">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z" fill="currentColor"/>
                                </svg>
                                <span>Kelola Data Pemegang</span>
                            </a>
                        </div>
                        <div class="nav-item">
                            <!-- Placeholder for Kendaraan -->
                            <a href="#" class="nav-link">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5H6.5C5.84 5 5.28 5.42 5.08 6.01L3 12V20C3 20.55 3.45 21 4 21H5C5.55 21 6 20.55 6 20V19H18V20C18 20.55 18.45 21 19 21H20C20.55 21 21 20.55 21 20V12L18.92 6.01ZM6.5 6.5H17.5L18.5 9.5H5.5L6.5 6.5ZM19 17H5V12H19V17Z" fill="currentColor"/>
                                    <path d="M7.5 15C8.32843 15 9 14.3284 9 13.5C9 12.6716 8.32843 12 7.5 12C6.67157 12 6 12.6716 6 13.5C6 14.3284 6.67157 15 7.5 15Z" fill="currentColor"/>
                                    <path d="M16.5 15C17.3284 15 18 14.3284 18 13.5C18 12.6716 17.3284 12 16.5 12C15.6716 12 15 12.6716 15 13.5C15 14.3284 15.6716 15 16.5 15Z" fill="currentColor"/>
                                </svg>
                                <span>Kelola Kendaraan</span>
                            </a>
                        </div>
                    </div>

                    <!-- PENGATURAN GROUP -->
                    <div class="nav-group-header">Pengaturan</div>
                    <div class="nav-group-items">
                        <div class="nav-item">
                            <!-- Placeholder for Units -->
                            <a href="#" class="nav-link">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 7V3H2V21H22V7H12ZM6 19H4V17H6V19ZM6 15H4V13H6V15ZM6 11H4V9H6V11ZM6 7H4V5H6V7ZM10 19H8V17H10V19ZM10 15H8V13H10V15ZM10 11H8V9H10V11ZM10 7H8V5H10V7ZM20 19H12V17H14V15H12V13H14V11H12V9H20V19ZM18 11H16V13H18V11ZM18 15H16V17H18V15Z" fill="currentColor"/>
                                </svg>
                                <span>Kelola Unit Kerja</span>
                            </a>
                        </div>
                        <div class="nav-item">
                            <a href="{{ route('admin.kelola-operator.index') }}" class="nav-link {{ request()->routeIs('admin.kelola-operator.*') ? 'active' : '' }}">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 11C17.66 11 18.99 9.66 18.99 8C18.99 6.34 17.66 5 16 5C14.34 5 13 6.34 13 8C13 9.66 14.34 11 16 11ZM8 11C9.66 11 10.99 9.66 10.99 8C10.99 6.34 9.66 5 8 5C6.34 5 5 6.34 5 8C5 9.66 6.34 11 8 11ZM8 13C5.67 13 1 14.17 1 16.5V19H15V16.5C15 14.17 10.33 13 8 13ZM16 13C15.71 13 15.38 13.02 15.03 13.05C16.19 13.89 17 15.02 17 16.5V19H23V16.5C23 14.17 18.33 13 16 13Z" fill="currentColor"/>
                                </svg>
                                <span>Kelola Operator</span>
                            </a>
                        </div>
                        <div class="nav-item">
                            <!-- Placeholder for Pegawai -->
                            <a href="#" class="nav-link">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z" fill="currentColor"/>
                                </svg>
                                <span>Kelola Pegawai</span>
                            </a>
                        </div>
                        <div class="nav-item">
                            <!-- Placeholder for Kategori -->
                            <a href="#" class="nav-link">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V8C22 6.9 21.1 6 20 6H12L10 4Z" fill="currentColor"/>
                                </svg>
                                <span>Kelola Kategori</span>
                            </a>
                        </div>
                    </div>

                    <!-- RIWAYAT GROUP -->
                    <div class="nav-group-header">Riwayat</div>
                    <div class="nav-group-items">
                        <div class="nav-item">
                            <!-- Placeholder for Activity Logs -->
                            <a href="#" class="nav-link">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13 3C8.03 3 4 7.03 4 12H1L5 16L9 12H6C6 8.13 9.13 5 13 5C16.87 5 20 8.13 20 12C20 15.87 16.87 19 13 19C11.07 19 9.32 18.21 8.06 16.94L6.64 18.36C8.27 20 10.5 21 13 21C17.97 21 22 16.97 22 12C22 7.03 17.97 3 13 3ZM12 8V13L16.28 15.54L17 14.33L13.5 12.25V8H12Z" fill="currentColor"/>
                                </svg>
                                <span>Log Aktivitas</span>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="nav-item">
                        <a href="{{ route('operator.dashboard') }}" class="nav-link {{ request()->routeIs('operator.dashboard') ? 'active' : '' }}">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 13H11V3H3V13ZM3 21H11V15H3V21ZM13 21H21V11H13V21ZM13 3V9H21V3H13Z" fill="currentColor"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </div>
                @endif

                <div class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link nav-link-button">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 7L15.59 8.41L18.17 11H8V13H18.17L15.59 15.58L17 17L22 12L17 7ZM4 5H12V3H4C2.9 3 2 3.9 2 5V19C2 20.1 2.9 21 4 21H12V19H4V5Z" fill="currentColor"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            @endauth
        </nav>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <button class="mobile-toggle" id="mobile-toggle" aria-label="Toggle Menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 18H21V16H3V18ZM3 13H21V11H3V13ZM3 6V8H21V6H3Z" fill="currentColor"/>
                </svg>
            </button>

            <h2 class="topbar-title">@yield('topbar_title', 'Dashboard')</h2>

            <div class="topbar-user">
                @auth
                    @php
                        $user = auth()->user();
                        $roleText = $user->role->nama_role === 'admin' ? 'Admin' : 'Operator';
                        $initials = strtoupper(substr($user->name ?? 'U', 0, 2));
                    @endphp
                    <div class="user-info">
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-role">{{ $roleText }}</div>
                    </div>
                    <div class="user-avatar">{{ $initials }}</div>
                @endauth
            </div>
        </header>

        <main class="content">
            @yield('content')
        </main>
    </div>

    <!-- Global Confirmation Modal -->
    <div id="sikandis-confirm-modal" class="modal-overlay">
        <div class="modal modal-confirm" role="dialog" aria-modal="true">
            <div class="modal-body text-center">
                <div class="confirm-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <h3 class="confirm-title" id="confirm-modal-title">Konfirmasi</h3>
                <p class="confirm-message" id="confirm-modal-message">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
                <div class="confirm-actions">
                    <button type="button" class="btn btn-outline" id="confirm-btn-cancel">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirm-btn-confirm">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
