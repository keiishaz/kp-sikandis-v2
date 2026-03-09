<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kendaraan->nama_kendaraan }} — SIKANDIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        :root {
            --navy:   #1e3a8a;
            --blue:   #2563eb;
            --sky:    #bfdbfe;
            --light:  #eff6ff;
            --text:   #0f172a;
            --sub:    #475569;
            --muted:  #94a3b8;
            --border: #e2e8f0;
            --bg:     #f1f5f9;
            --card:   #ffffff;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: var(--navy);
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            height: 48px;
        }

        .topbar img { height: 34px; width: auto; }

        .topbar-text {
            font-size: 12px;
            font-weight: 600;
            color: rgba(255,255,255,0.9);
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        /* ===== HERO SECTION ===== */
        .hero {
            background: linear-gradient(150deg, #1d4ed8 0%, #3b82f6 60%, #60a5fa 100%);
            padding: 28px 20px 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Elemen dekoratif tipis */
        .hero::before,
        .hero::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            opacity: 0.08;
            background: #fff;
            z-index: 0;
        }

        .hero::before {
            width: 260px;
            height: 260px;
            top: -80px;
            right: -60px;
        }

        .hero::after {
            width: 160px;
            height: 160px;
            bottom: -30px;
            left: -40px;
        }

        .hero-label,
        .hero-title,
        .hero-meta {
            position: relative;
            z-index: 1;
        }

        .hero-label {
            display: inline-block;
            background: rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.8);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.2);
            margin-bottom: 12px;
        }

        .hero-title {
            font-size: 26px;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 10px;
        }

        .hero-meta {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 6px;
        }

        .plat-chip {
            background: rgba(255,255,255,0.18);
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 1.5px;
            padding: 5px 14px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.28);
            font-family: 'Courier New', monospace;
        }

        .status-chip {
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
        }

        .status-aktif   { background: rgba(34,197,94,0.22); color: #86efac; border: 1px solid rgba(74,222,128,0.3); }
        .status-nonaktif{ background: rgba(239,68,68,0.22);  color: #fca5a5; border: 1px solid rgba(248,113,113,0.3); }

        /* ===== MAIN CONTAINER ===== */
        .main-wrap {
            max-width: 600px;
            margin: -36px auto 40px;
            padding: 0 16px;
            position: relative;
            z-index: 2;
        }

        /* ===== QR FLOAT CARD ===== */
        .qr-float-card {
            background: var(--card);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 8px 30px rgba(30,58,138,0.15);
            margin-bottom: 16px;
        }

        .qr-box {
            background: #fff;
            border-radius: 12px;
            padding: 8px;
            flex-shrink: 0;
            display: flex;
            box-shadow: 0 0 0 0 rgba(37,99,235,0.35);
            animation: qr-pulse 2.4s ease-in-out infinite;
        }

        @keyframes qr-pulse {
            0%   { box-shadow: 0 0 0 0 rgba(37,99,235,0.35); }
            60%  { box-shadow: 0 0 0 10px rgba(37,99,235,0); }
            100% { box-shadow: 0 0 0 0  rgba(37,99,235,0); }
        }

        .qr-info {
            flex: 1;
            min-width: 0;
        }

        .qr-info h3 {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
        }

        .qr-info p {
            font-size: 12.5px;
            color: var(--sub);
            line-height: 1.5;
        }

        .qr-token {
            display: inline-block;
            margin-top: 8px;
            background: var(--light);
            border: 1px solid var(--sky);
            color: var(--navy);
            font-family: monospace;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            padding: 3px 10px;
            border-radius: 6px;
        }

        /* ===== INFO CARDS ===== */
        .info-card {
            background: var(--card);
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-bottom: 14px;
        }

        .info-card-header {
            background: var(--light);
            border-bottom: 1px solid var(--border);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-card-header .icon-wrap {
            width: 28px;
            height: 28px;
            background: var(--blue);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-card-header svg { color: #fff; }

        .info-card-header h3 {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-card-body { padding: 4px 0; }

        /* ===== DATA ROWS ===== */
        .row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 11px 20px;
            gap: 12px;
            border-bottom: 1px solid var(--border);
        }

        .row:last-child { border-bottom: none; }

        .row-key {
            font-size: 12.5px;
            color: var(--sub);
            font-weight: 400;
            flex-shrink: 0;
        }

        .row-value {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            text-align: right;
            word-break: break-word;
        }

        .row-value.capitalize { text-transform: capitalize; }

        .row-value.mono {
            font-family: 'Courier New', monospace;
            background: var(--light);
            color: var(--navy);
            padding: 2px 8px;
            border-radius: 5px;
            letter-spacing: 0.8px;
            font-size: 12px;
        }

        /* ===== PEMEGANG EMPTY ===== */
        .empty-pemegang {
            padding: 20px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
            font-style: italic;
        }

        /* ===== FOOTER ===== */
        .footer {
            text-align: center;
            font-size: 11.5px;
            color: var(--muted);
            padding: 4px 16px 24px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 520px) {
            .hero-title { font-size: 21px; }

            .qr-float-card {
                flex-direction: column;
                text-align: center;
            }

            .qr-info { text-align: center; }

            .row {
                flex-direction: column;
                gap: 2px;
            }

            .row-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>

{{-- Top Bar --}}
<div class="topbar">
    <img src="{{ asset('assets/images/logobkl.png') }}" alt="Logo">
    <span class="topbar-text">Dinas Pemerintah Provinsi Bengkulu</span>
</div>

{{-- Hero --}}
<div class="hero">
    <div class="hero-label">{{ $kendaraan->kategori->nama_kategori ?? 'Kendaraan Dinas' }}</div>
    <h1 class="hero-title">{{ $kendaraan->nama_kendaraan }}</h1>
    <div class="hero-meta">
        <span class="plat-chip">{{ $kendaraan->no_polisi }}</span>
        @if($kendaraan->status === 'aktif')
            <span class="status-chip status-aktif">● Aktif</span>
        @else
            <span class="status-chip status-nonaktif">● Nonaktif</span>
        @endif
    </div>
</div>

<div class="main-wrap">

    {{-- QR Float Card --}}
    <div class="qr-float-card">
        <div class="qr-box">
            <div id="qr-code"></div>
        </div>
        <div class="qr-info">
            <h3>Kendaraan Resmi Terverifikasi</h3>
            <p>QR Code ini merupakan identitas digital resmi kendaraan dinas yang terdaftar dalam sistem SIKANDIS.</p>
            <span class="qr-token">{{ $qr->token ?? '-' }}</span>
        </div>
    </div>

    {{-- Informasi Kendaraan --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="icon-wrap">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
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
                <span class="row-key">Tahun</span>
                <span class="row-value">{{ $kendaraan->tahun ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="row-key">Jenis Penggunaan</span>
                <span class="row-value capitalize">{{ $kendaraan->jenis_penggunaan ?? '-' }}</span>
            </div>
            @if($kendaraan->lokasi_operasional)
            <div class="row">
                <span class="row-key">Lokasi Operasional</span>
                <span class="row-value">{{ $kendaraan->lokasi_operasional }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Pemegang Kendaraan --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="icon-wrap">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <h3>Pemegang Kendaraan</h3>
        </div>
        <div class="info-card-body">
            @php $pemegang = $kendaraan->pemegangAktif; @endphp
            @if($pemegang && $pemegang->pegawai)
                <div class="row">
                    <span class="row-key">Nama</span>
                    <span class="row-value">{{ $pemegang->pegawai->nama }}</span>
                </div>
                @if($pemegang->pegawai->jabatan)
                <div class="row">
                    <span class="row-key">Jabatan</span>
                    <span class="row-value">{{ $pemegang->pegawai->jabatan }}</span>
                </div>
                @endif
                @if($pemegang->pegawai->unit)
                <div class="row">
                    <span class="row-key">Unit Kerja</span>
                    <span class="row-value">{{ $pemegang->pegawai->unit->nama_unit }}</span>
                </div>
                @endif
                @if($pemegang->pegawai->subUnit)
                <div class="row">
                    <span class="row-key">Sub Unit</span>
                    <span class="row-value">{{ $pemegang->pegawai->subUnit->nama_sub_unit }}</span>
                </div>
                @endif
                @if($pemegang->tanggal_mulai)
                <div class="row">
                    <span class="row-key">Memegang Sejak</span>
                    <span class="row-value">{{ \Carbon\Carbon::parse($pemegang->tanggal_mulai)->translatedFormat('d F Y') }}</span>
                </div>
                @endif
            @else
                <p class="empty-pemegang">Kendaraan ini belum memiliki pemegang aktif.</p>
            @endif
        </div>
    </div>

    <div class="footer">
        Data ini dikelola secara resmi oleh Dinas Kominfo Kota Bengkulu.<br>
        &copy; {{ date('Y') }} SIKANDIS — Sistem Inventarisasi Kendaraan Dinas
    </div>

</div>

<script>
new QRCode(document.getElementById('qr-code'), {
    text: window.location.href,
    width: 90,
    height: 90,
    colorDark: '#1e3a8a',
    colorLight: '#ffffff',
    correctLevel: QRCode.CorrectLevel.M
});
</script>
</body>
</html>
