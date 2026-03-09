<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Tidak Ditemukan — SIKANDIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

        /* ===== HERO ===== */
        .hero {
            background: linear-gradient(150deg, #1d4ed8 0%, #3b82f6 60%, #60a5fa 100%);
            padding: 28px 20px 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

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
            width: 260px; height: 260px;
            top: -80px; right: -60px;
        }

        .hero::after {
            width: 160px; height: 160px;
            bottom: -30px; left: -40px;
        }

        .hero-label,
        .hero-title,
        .hero-meta { position: relative; z-index: 1; }

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
        }

        /* ===== MAIN WRAP ===== */
        .main-wrap {
            max-width: 600px;
            margin: -36px auto 40px;
            padding: 0 16px;
            position: relative;
            z-index: 2;
        }

        /* ===== NOT FOUND CARD ===== */
        .nf-card {
            background: var(--card);
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(30,58,138,0.15);
            padding: 40px 28px;
            text-align: center;
        }

        .nf-icon {
            width: 72px;
            height: 72px;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .nf-icon svg { color: #ef4444; }

        .nf-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 10px;
        }

        .nf-desc {
            font-size: 13.5px;
            color: var(--sub);
            line-height: 1.65;
            max-width: 380px;
            margin: 0 auto 28px;
        }

        .nf-notice {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--light);
            border: 1px solid var(--sky);
            color: var(--navy);
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 12.5px;
            font-weight: 500;
        }

        .nf-notice svg { flex-shrink: 0; }

        /* ===== FOOTER ===== */
        .footer {
            text-align: center;
            font-size: 11.5px;
            color: var(--muted);
            padding: 12px 16px 28px;
        }

        @media (max-width: 520px) {
            .hero-title { font-size: 20px; }
            .nf-card { padding: 28px 18px; }
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
    <div class="hero-label">SIKANDIS</div>
    <h1 class="hero-title">QR Kendaraan Tidak Ditemukan</h1>
</div>

<div class="main-wrap">

    {{-- Not Found Card --}}
    <div class="nf-card">
        <div class="nf-icon">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>

        <h2 class="nf-title">Data Tidak Ditemukan</h2>
        <p class="nf-desc">
            Maaf, kode QR yang Anda pindai tidak terdaftar atau sudah tidak aktif dalam sistem SIKANDIS. Pastikan Anda memindai QR Code resmi dari kendaraan dinas.
        </p>

        <div class="nf-notice">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            Hubungi Dinas Kominfo Kota Bengkulu jika terjadi kesalahan.
        </div>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} SIKANDIS — Sistem Inventarisasi Kendaraan Dinas
    </div>

</div>

</body>
</html>
