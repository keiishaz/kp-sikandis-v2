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

    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <a href="{{ route('dashboard') }}" class="sidebar-brand" style="text-decoration: none;">
                <div class="brand-icon" style="flex-shrink: 0; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; margin-right: 8px; background: #fff; border-radius: var(--r-md); padding: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <img src="{{ asset('assets/images/logo-kominfo.png') }}" alt="Logo Kominfo" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <div>
                    <div class="brand-name">SIKANDIS</div>
                    <div class="brand-sub">Kominfo Kota Bengkulu</div>
                </div>
            </a>

            <nav class="sidebar-nav">
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 20V14H14V20H19V12H22L12 3L2 12H5V20H10Z" fill="currentColor"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <div class="nav-section-label">Kendaraan</div>
                    <!-- MANAJEMEN KENDARAAN GROUP -->
                    @php
                        $isKendaraanActive = request()->is('kendaraan*') || request()->is('pemegang*') || request()->routeIs('kategori.*') || request()->routeIs('qr-kendaraan.*'); 
                    @endphp
                    <div class="nav-group {{ $isKendaraanActive ? 'expanded' : '' }}">
                        <button class="nav-group-header {{ $isKendaraanActive ? 'active' : '' }}" title="Kendaraan">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                            <span>Kendaraan</span>
                            <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="nav-group-body">
                            <a href="{{ route('kendaraan.index') }}" class="nav-sub-item {{ (request()->routeIs('kendaraan.*') || request()->is('pemegang*')) ? 'active' : '' }}">
                                Data Kendaraan
                            </a>
                            <a href="{{ route('kategori.index') }}" class="nav-sub-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                                Kategori Kendaraan
                            </a>
                            <a href="{{ route('qr-kendaraan.index') }}" class="nav-sub-item {{ request()->routeIs('qr-kendaraan.*') ? 'active' : '' }}">
                                QR Kendaraan
                            </a>
                        </div>
                    </div>

                    @can('manage-master')
                    <div class="nav-section-label">Manajemen Eksternal</div>
                    <!-- MANAJEMEN EKSTERNAL GROUP -->
                    @php
                        $isMasterDataActive = request()->routeIs('units.*', 'pegawai.*', 'kelola-operator.*');
                    @endphp
                    <div class="nav-group {{ $isMasterDataActive ? 'expanded' : '' }}">
                        <button class="nav-group-header {{ $isMasterDataActive ? 'active' : '' }}" title="Manajemen Eksternal">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                            </svg>
                            <span>Manajemen Eksternal</span>
                            <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="nav-group-body">
                            <a href="{{ route('units.index') }}" class="nav-sub-item {{ request()->routeIs('units.*') ? 'active' : '' }}">
                                Unit Eksternal
                            </a>
                            <a href="{{ route('pegawai.index') }}" class="nav-sub-item {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                                Pegawai Eksternal
                            </a>
                            <a href="{{ route('kelola-operator.index') }}" class="nav-sub-item {{ request()->routeIs('kelola-operator.*') ? 'active' : '' }}">
                                Operator
                            </a>
                        </div>
                    </div>
                    @endcan

                    @can('view-log')
                    <div class="nav-section-label">Monitoring</div>
                    <!-- MONITORING & LOG GROUP -->
                    @php
                        $isLogActive = request()->routeIs('log.aktivitas', 'log.login');
                    @endphp
                    <div class="nav-group {{ $isLogActive ? 'expanded' : '' }}">
                        <button class="nav-group-header {{ $isLogActive ? 'active' : '' }}" title="Monitoring & Log">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                            </svg>
                            <span>Monitoring & Log</span>
                            <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="nav-group-body">
                            <a href="{{ route('log.aktivitas') }}" class="nav-sub-item {{ request()->routeIs('log.aktivitas') ? 'active' : '' }}">
                                Log Aktivitas
                            </a>
                            <a href="{{ route('log.login') }}" class="nav-sub-item {{ request()->routeIs('log.login') ? 'active' : '' }}">
                                Log Login
                            </a>
                        </div>
                    </div>
                    @endcan
                @endauth
            </nav>
            
            @auth
            <div class="sidebar-footer">
                @php
                    $user = auth()->user();
                    $roleText = $user->role->nama_role === 'admin' ? 'Admin' : 'Operator';
                    $initials = strtoupper(substr($user->name ?? 'U', 0, 2));
                @endphp
                <div class="user-avatar-sm">{{ $initials }}</div>
                <div>
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-role">{{ $roleText }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin-left: auto;">
                    @csrf
                    <button type="submit" class="logout-btn" title="Keluar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </form>
            </div>
            @endauth
        </aside>

        <div class="sidebar-overlay"></div>

        <div class="main-wrapper">
            <header class="topbar">
                <button class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Alihkan Sidebar" title="Sembunyikan Sidebar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <line x1="9" y1="3" x2="9" y2="21"/>
                    </svg>
                </button>

                <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Alihkan Menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>

                <h1 class="page-title" style="font-size: 15px; font-weight: 600; color: var(--n-800); margin: 0;">@yield('topbar_title', 'Dashboard')</h1>

                <div class="topbar-actions" style="margin-left: auto;">
                    <div class="topbar-user-wrap" style="position: relative;">
                        @auth
                            <button class="topbar-user" onclick="toggleUserDropdown()">
                                <div class="topbar-user-info" style="text-align: right;">
                                    <span class="topbar-name">{{ $user->name }}</span>
                                    <span class="topbar-role">{{ $roleText }}</span>
                                </div>
                                <div class="topbar-avatar">{{ $initials }}</div>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div id="userDropdown" style="display: none; position: absolute; top: calc(100% + 5px); right: 0; background: var(--surface-card); width: 200px; border-radius: var(--r-md); box-shadow: var(--shadow-md); border: 1px solid var(--n-200); z-index: 1000; overflow: hidden;">
                                <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; gap: 8px; width: 100%; padding: 12px 16px; text-decoration: none; color: var(--n-700); font-size: 13.5px; border-bottom: 1px solid var(--n-100); transition: background 0.15s;" onmouseover="this.style.background='var(--n-50)'" onmouseout="this.style.background='transparent'">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    Edit Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="display: flex; align-items: center; gap: 8px; width: 100%; padding: 12px 16px; text-decoration: none; color: var(--danger-text); font-size: 13.5px; border: none; background: transparent; cursor: pointer; text-align: left; transition: background 0.15s; font-family: inherit;" onmouseover="this.style.background='var(--danger-bg)'" onmouseout="this.style.background='transparent'">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>
            </header>

            <main class="page-content">
                {{-- Global Toast Notification --}}
                @if(session('success') || session('error'))
                    <div id="global-toast" class="toast {{ session('success') ? 'toast-success' : 'toast-danger' }}">
                        @if(session('success'))
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="toast-icon" stroke="var(--success-icon)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        @else
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="toast-icon" stroke="var(--danger-icon)" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        @endif
                        <span class="toast-message">{!! session('success') ?? session('error') !!}</span>
                        <button class="toast-close" onclick="document.getElementById('global-toast').remove()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <script>setTimeout(()=>{const t=document.getElementById('global-toast');if(t)t.style.opacity='0'; setTimeout(()=>t&&t.remove(),300);},4000);</script>
                @endif
                @yield('content')
            </main>

            {{-- Unified Footer --}}
            <footer class="main-footer">
                <div class="footer-content">
                    <span>&copy; {{ date('Y') }} Dinas Komunikasi dan Informatika Kota Bengkulu</span>
                    <span class="footer-divider"></span>
                    <span class="footer-credit">Dikembangkan oleh Tim Magang Project SIKANDIS</span>
                </div>
            </footer>
        </div>
    </div>

    <!-- Global Confirmation Modal -->
    <div id="sikandis-confirm-modal" class="modal-overlay">
        <div class="modal modal-confirm">
            <div class="modal-body">
                <div class="confirm-icon confirm-icon--danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
                <h3 class="confirm-title" id="confirm-modal-title">Konfirmasi</h3>
                <p class="confirm-message" id="confirm-modal-message">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
                <div class="confirm-actions">
                    <button type="button" class="btn btn-secondary" id="confirm-btn-cancel" style="width: 100%;">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirm-btn-confirm" style="width: 100%;">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        // ===========================
        // Sidebar Collapse Toggle (Desktop)
        // ===========================
        const appLayout = document.querySelector('.app-layout');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');

        // Restore state on load
        if (localStorage.getItem('sikandis_sidebar_collapsed') === '1') {
            appLayout.classList.add('sidebar-collapsed');
        }

        if (sidebarToggleBtn) {
            sidebarToggleBtn.addEventListener('click', function () {
                appLayout.classList.toggle('sidebar-collapsed');
                const isCollapsed = appLayout.classList.contains('sidebar-collapsed');
                localStorage.setItem('sikandis_sidebar_collapsed', isCollapsed ? '1' : '0');
            });
        }

        // ===========================
        // Mobile Sidebar Drawer
        // ===========================
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.querySelector('.sidebar');
        const sidebarOverlay = document.querySelector('.sidebar-overlay');

        if (mobileMenuBtn && sidebar && sidebarOverlay) {
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.add('open');
                sidebarOverlay.classList.add('active');
            });

            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('active');
            });
        }

        // ===========================
        // Accordion Nav Logic
        // ===========================
        const navHeaders = document.querySelectorAll('.nav-group-header');
        navHeaders.forEach(header => {
            header.addEventListener('click', () => {
                const group = header.parentElement;
                group.classList.toggle('expanded');
            });
        });

        // User Dropdown
        // ===========================
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) {
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            }
        }

        document.addEventListener('click', function (event) {
            const btn = document.querySelector('.topbar-user');
            const dropdown = document.getElementById('userDropdown');
            if (btn && dropdown && !btn.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
