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

                    <!-- MASTER DATA GROUP -->
                    @php
                        $isMasterDataActive = request()->routeIs('admin.units.*', 'admin.pegawai.*', 'admin.kelola-operator.*');
                    @endphp
                    <div class="nav-group {{ $isMasterDataActive ? 'expanded' : '' }}">
                        <button class="nav-group-toggle {{ $isMasterDataActive ? 'active' : '' }}">
                            <div class="nav-link-left">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                                </svg>
                                <span style="font-family:inherit;">Master Data</span>
                            </div>
                            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="nav-group-content">
                            <div class="nav-item">
                                <a href="{{ route('admin.units.index') }}" class="nav-link {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                                    <span>Unit Kerja</span>
                                </a>
                            </div>
                            <div class="nav-item">
                                <a href="{{ route('admin.pegawai.index') }}" class="nav-link {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}">
                                    <span>Pegawai</span>
                                </a>
                            </div>
                            <div class="nav-item">
                                <a href="{{ route('admin.kelola-operator.index') }}" class="nav-link {{ request()->routeIs('admin.kelola-operator.*') ? 'active' : '' }}">
                                    <span>Operator</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- MANAJEMEN KENDARAAN GROUP -->
                    @php
                        $isKendaraanActive = request()->is('admin/kendaraan*') || request()->is('admin/pemegang*') || request()->routeIs('admin.kategori.*'); 
                    @endphp
                    <div class="nav-group {{ $isKendaraanActive ? 'expanded' : '' }}">
                        <button class="nav-group-toggle {{ $isKendaraanActive ? 'active' : '' }}">
                            <div class="nav-link-left">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="3" width="15" height="13"></rect>
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                </svg>
                                <span style="font-family:inherit;">Kendaraan</span>
                            </div>
                            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="nav-group-content">
                            <div class="nav-item">
                                <a href="{{ route('admin.kendaraan.index') }}" class="nav-link {{ request()->routeIs('admin.kendaraan.*') ? 'active' : '' }}">
                                    <span>Data Kendaraan</span>
                                </a>
                            </div>
                            
                            <div class="nav-item">
                                <a href="{{ route('admin.kategori.index') }}" class="nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                                    <span>Kategori Kendaraan</span>
                                </a>
                            </div>
                            
                            <div class="nav-item">
                                <a href="{{ route('admin.qr-kendaraan.index') }}" class="nav-link {{ request()->routeIs('admin.qr-kendaraan.*') ? 'active' : '' }}">
                                    <span>QR Kendaraan</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- MONITORING & LOG GROUP -->
                    @php
                        $isLogActive = request()->routeIs('admin.log.aktivitas', 'admin.log.login');
                    @endphp
                    <div class="nav-group {{ $isLogActive ? 'expanded' : '' }}">
                        <button class="nav-group-toggle {{ $isLogActive ? 'active' : '' }}">
                            <div class="nav-link-left">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                </svg>
                                <span style="font-family:inherit;">Monitoring & Log</span>
                            </div>
                            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="nav-group-content">
                            <div class="nav-item">
                                <a href="{{ route('admin.log.aktivitas') }}" class="nav-link {{ request()->routeIs('admin.log.aktivitas') ? 'active' : '' }}">
                                    <span>Log Aktivitas</span>
                                </a>
                            </div>
                            <div class="nav-item">
                                <a href="{{ route('admin.log.login') }}" class="nav-link {{ request()->routeIs('admin.log.login') ? 'active' : '' }}">
                                    <span>Log Login</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- DASHBOARD OPERATOR --}}
                    <div class="nav-item">
                        <a href="{{ route('operator.dashboard') }}" class="nav-link {{ request()->routeIs('operator.dashboard') ? 'active' : '' }}">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 20V14H14V20H19V12H22L12 3L2 12H5V20H10Z" fill="currentColor"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </div>

                    {{-- KENDARAAN GROUP --}}
                    @php
                        $isOpKendaraanActive = request()->is('operator/kendaraan*');
                    @endphp
                    <div class="nav-group {{ $isOpKendaraanActive ? 'expanded' : '' }}">
                        <button class="nav-group-toggle {{ $isOpKendaraanActive ? 'active' : '' }}">
                            <div class="nav-link-left">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="3" width="15" height="13"></rect>
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                </svg>
                                <span style="font-family:inherit;">Kendaraan</span>
                            </div>
                            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="nav-group-content">
                            <div class="nav-item">
                                <a href="{{ route('operator.kendaraan.index') }}" class="nav-link {{ request()->routeIs('operator.kendaraan.*') ? 'active' : '' }}">
                                    <span>Data Kendaraan</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- MASTER DATA GROUP --}}
                    @php
                        $isOpMasterActive = request()->is('operator/units*') || request()->is('operator/pegawai*') || request()->is('operator/kategori*');
                    @endphp
                    <div class="nav-group {{ $isOpMasterActive ? 'expanded' : '' }}">
                        <button class="nav-group-toggle {{ $isOpMasterActive ? 'active' : '' }}">
                            <div class="nav-link-left">
                                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                                </svg>
                                <span style="font-family:inherit;">Master Data</span>
                            </div>
                            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="nav-group-content">
                            <div class="nav-item">
                                <a href="{{ route('operator.units.index') }}" class="nav-link {{ request()->routeIs('operator.units.*') ? 'active' : '' }}">
                                    <span>Unit Kerja</span>
                                </a>
                            </div>
                            <div class="nav-item">
                                <a href="{{ route('operator.pegawai.index') }}" class="nav-link {{ request()->routeIs('operator.pegawai.*') ? 'active' : '' }}">
                                    <span>Pegawai</span>
                                </a>
                            </div>
                            <div class="nav-item">
                                <a href="{{ route('operator.kategori.index') }}" class="nav-link {{ request()->routeIs('operator.kategori.*') ? 'active' : '' }}">
                                    <span>Kategori Kendaraan</span>
                                </a>
                            </div>
                        </div>
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

            <div class="topbar-user" style="position: relative;">
                @auth
                    @php
                        $user = auth()->user();
                        $roleText = $user->role->nama_role === 'admin' ? 'Admin' : 'Operator';
                        $initials = strtoupper(substr($user->name ?? 'U', 0, 2));
                    @endphp
                    <button class="user-menu-btn" onclick="toggleUserDropdown()" style="display:flex; align-items:center; gap:12px; background:none; border:none; cursor:pointer; text-align:left; padding:4px 8px; border-radius:8px;">
                        <div class="user-info">
                            <div class="user-name">{{ $user->name }}</div>
                            <div class="user-role">{{ $roleText }}</div>
                        </div>
                        <div class="user-avatar">{{ $initials }}</div>
                    </button>

                    <!-- User Dropdown Menu -->
                    <div id="userDropdown" class="user-dropdown-menu" style="display: none; position: absolute; top: calc(100% + 5px); right: 0; background: #fff; width: 200px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; z-index: 1000; overflow: hidden;">
                        <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; gap: 8px; width: 100%; padding: 12px 16px; text-decoration: none; color: #1e293b; font-size: 13px; border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            Edit Profil
                        </a>
                    </div>
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
    <script>
        // Dropdown Logic
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const btn = document.querySelector('.user-menu-btn');
            const dropdown = document.getElementById('userDropdown');
            if (btn && dropdown && !btn.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
