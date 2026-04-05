<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kendaraan->nama_kendaraan }} — SIKANDIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-50: #eff6ff;
            --brand-100: #dbeafe;
            --brand-500: #3b82f6;
            --brand-600: #2563eb;
            --brand-700: #1d4ed8;
            --brand-900: #1e3a8a;
            --n-50: #f8fafc;
            --n-100: #f1f5f9;
            --n-200: #e2e8f0;
            --n-400: #94a3b8;
            --n-500: #64748b;
            --n-600: #475569;
            --n-800: #1e293b;
            --n-900: #0f172a;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--n-50);
            color: var(--n-900);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: #fff;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            height: 60px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            position: relative;
            z-index: 10;
        }

        .topbar img { height: 38px; width: auto; }

        .topbar-text {
            font-size: 13px;
            font-weight: 700;
            color: var(--n-800);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ===== HERO SECTION ===== */
        .hero {
            background: linear-gradient(135deg, var(--brand-700) 0%, var(--brand-500) 100%);
            padding: 36px 20px 70px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before,
        .hero::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            background: #fff;
            z-index: 0;
        }

        .hero::before { width: 300px; height: 300px; top: -100px; right: -80px; }
        .hero::after { width: 180px; height: 180px; bottom: -40px; left: -50px; }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .hero-label {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
            margin-bottom: 16px;
        }

        .hero-title {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 16px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .hero-meta {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .plat-chip {
            background: #fff;
            color: var(--brand-700);
            font-weight: 800;
            font-size: 15px;
            letter-spacing: 1.5px;
            padding: 6px 16px;
            border-radius: 8px;
            font-family: monospace;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .status-chip {
            font-size: 13px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-aktif { background: #dcfce7; color: #166534; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .status-nonaktif { background: #fee2e2; color: #991b1b; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .dot { width: 8px; height: 8px; border-radius: 50%; }
        .status-aktif .dot { background: #22c55e; box-shadow: 0 0 0 2px #bbf7d0; }
        .status-nonaktif .dot { background: #ef4444; box-shadow: 0 0 0 2px #fecaca; }

        /* ===== MAIN CONTAINER ===== */
        .main-wrap {
            max-width: 600px;
            margin: -40px auto 40px;
            padding: 0 16px;
            position: relative;
            z-index: 2;
        }

        /* ===== FLOAT CARD & CHECKMARK ===== */
        .qr-float-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 24px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.01);
            margin-bottom: 20px;
        }

        .qr-info { flex: 1; min-width: 0; }
        .qr-info h3 { font-size: 16px; font-weight: 700; color: var(--n-900); margin-bottom: 6px; }
        .qr-info p { font-size: 13px; color: var(--n-500); line-height: 1.5; margin-bottom: 0px; }

        /* Animated Checkmark */
        .check-box {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #dcfce7;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(34, 197, 94, 0.2);
        }

        .checkmark-anim {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: block;
            stroke-width: 4;
            stroke: #16a34a;
            stroke-miterlimit: 10;
            box-shadow: inset 0px 0px 0px #16a34a;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }
        .checkmark-circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 4;
            stroke-miterlimit: 10;
            stroke: #16a34a;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        .checkmark-check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            stroke-linecap: round;
            stroke-linejoin: round;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards;
        }

        @keyframes stroke {
            100% { stroke-dashoffset: 0; }
        }
        @keyframes scale {
            0%, 100% { transform: none; }
            50% { transform: scale3d(1.1, 1.1, 1); }
        }
        @keyframes fill {
            100% { box-shadow: inset 0px 0px 0px 30px transparent; }
        }


        /* ===== INFO CARDS ===== */
        .info-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02), 0 1px 2px rgba(0,0,0,0.01);
            border: 1px solid var(--n-200);
            overflow: hidden;
            margin-bottom: 16px;
        }

        .info-card-header {
            background: var(--n-50);
            border-bottom: 1px solid var(--n-200);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .info-card-header .icon-wrap {
            width: 36px;
            height: 36px;
            background: #fff;
            border: 1px solid var(--n-200);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--brand-600);
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .info-card-header h3 {
            font-size: 14px;
            font-weight: 700;
            color: var(--n-800);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-card-body { padding: 4px 0; }

        /* ===== DATA ROWS ===== */
        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            gap: 16px;
            border-bottom: 1px dashed var(--n-200);
        }

        .row:last-child { border-bottom: none; }

        .row-key {
            font-size: 13.5px;
            color: var(--n-500);
            font-weight: 500;
            flex-shrink: 0;
        }

        .row-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--n-900);
            text-align: right;
            word-break: break-word;
        }

        .row-value.capitalize { text-transform: capitalize; }

        .row-value.mono {
            font-family: monospace;
            background: var(--n-100);
            color: var(--n-800);
            padding: 4px 10px;
            border-radius: 6px;
            letter-spacing: 1px;
            font-size: 13px;
        }

        /* ===== PEMEGANG EMPTY ===== */
        .empty-pemegang {
            padding: 30px 20px;
            text-align: center;
            color: var(--n-400);
            font-size: 13.5px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        /* ===== FOOTER ===== */
        .footer {
            text-align: center;
            font-size: 12px;
            color: var(--n-500);
            padding: 12px 16px 32px;
            line-height: 1.6;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 520px) {
            .hero-title { font-size: 24px; }
            .hero { padding: 30px 20px 60px; }
            .qr-float-card { flex-direction: column; text-align: center; gap: 16px; padding: 20px; }
            .row { flex-direction: column; align-items: flex-start; gap: 4px; padding: 12px 20px; }
            .row-value { text-align: left; }
        }
    </style>
</head>
<body>

<div class="topbar">
    <img src="{{ asset('assets/images/logobkl.png') }}" alt="Logo">
    <span class="topbar-text">Pemerintah Kota Bengkulu</span>
</div>

<div class="hero">
    <div class="hero-content">
        <div class="hero-label">{{ $kendaraan->kategori->nama_kategori ?? 'Kendaraan Dinas' }}</div>
        <h1 class="hero-title">{{ $kendaraan->nama_kendaraan }}</h1>
        <div class="hero-meta">
            <span class="plat-chip">{{ $kendaraan->no_polisi }}</span>
            @if($kendaraan->status === 'aktif')
                <span class="status-chip status-aktif"><span class="dot"></span> Aktif</span>
            @else
                <span class="status-chip status-nonaktif"><span class="dot"></span> Nonaktif</span>
            @endif
        </div>
    </div>
</div>

<div class="main-wrap">

    <div class="qr-float-card">
        <div class="check-box">
            <svg class="checkmark-anim" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark-circle" cx="26" cy="26" r="25"/>
                <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>
        <div class="qr-info">
            <h3>Kendaraan Resmi Terverifikasi</h3>
            <p>Kendaraan dinas ini terdaftar secara resmi dan terverifikasi secara sah dalam database SIKANDIS.</p>
        </div>
    </div>

    <div class="info-card">
        <div class="info-card-header">
            <div class="icon-wrap">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <rect x="1" y="3" width="15" height="13" rx="2" ry="2"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle>
                </svg>
            </div>
            <h3>Informasi Kendaraan</h3>
        </div>
        <div class="info-card-body">
            <div class="row">
                <span class="row-key">Nomor Polisi</span>
                <span class="row-value mono">{{ $kendaraan->no_polisi }}</span>
            </div>
            <div class="row">
                <span class="row-key">Kategori</span>
                <span class="row-value">{{ $kendaraan->kategori->nama_kategori ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="row-key">Tahun Keluaran</span>
                <span class="row-value">{{ $kendaraan->tahun ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="row-key">Jenis Penggunaan</span>
                <span class="row-value capitalize">{{ str_replace('_', ' ', $kendaraan->jenis_penggunaan) ?? '-' }}</span>
            </div>
            @if($kendaraan->lokasi_operasional)
            <div class="row">
                <span class="row-key">Lokasi Operasional</span>
                <span class="row-value">{{ $kendaraan->lokasi_operasional }}</span>
            </div>
            @endif
        </div>
    </div>

    <div class="info-card">
        <div class="info-card-header">
            <div class="icon-wrap">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <h3>Daftar Pemegang</h3>
        </div>
        <div class="info-card-body">
            @php $pemegang = $kendaraan->pemegangAktif; @endphp
            @if($pemegang && ($pemegang->pegawai || $pemegang->nama_pegawai))
                <div class="row">
                    <span class="row-key">Nama Pemegang</span>
                    <span class="row-value">{{ $pemegang->display_name }}</span>
                </div>
                <div class="row">
                    <span class="row-key">Jabatan</span>
                    <span class="row-value">{{ $pemegang->display_jabatan }}</span>
                </div>
                <div class="row">
                    <span class="row-key">Unit Kerja</span>
                    <span class="row-value">{{ $pemegang->display_unit }}</span>
                </div>
                @if($pemegang->tanggal_mulai)
                <div class="row">
                    <span class="row-key">Memegang Sejak</span>
                    <span class="row-value">{{ \Carbon\Carbon::parse($pemegang->tanggal_mulai)->translatedFormat('d F Y') }}</span>
                </div>
                @endif
            @else
                <div class="empty-pemegang">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>Kendaraan ini belum memiliki pemegang aktif yang tercatat.</span>
                </div>
            @endif
        </div>
    </div>

    <div class="footer">
        Data ini dikelola secara resmi oleh <strong>Dinas Kominfo Kota Bengkulu</strong>.<br>
        &copy; {{ date('Y') }} SIKANDIS — Sistem Informasi Data Kendaraan Dinas<br>
        Dikembangkan oleh Tim Magang Project SIKANDIS
    </div>

</div>

</body>
</html>
