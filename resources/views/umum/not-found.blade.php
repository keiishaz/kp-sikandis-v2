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

        /* ===== HERO ===== */
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

        .hero-label {
            position: relative;
            z-index: 1;
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
            position: relative;
            z-index: 1;
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* ===== MAIN WRAP ===== */
        .main-wrap {
            max-width: 600px;
            margin: -40px auto 40px;
            padding: 0 16px;
            position: relative;
            z-index: 2;
        }

        /* ===== NOT FOUND CARD ===== */
        .nf-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.01);
            padding: 48px 32px;
            text-align: center;
            border: 1px solid var(--n-200);
        }

        .nf-icon {
            width: 80px;
            height: 80px;
            background: #fef2f2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .nf-icon svg { color: #ef4444; }

        .nf-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--n-900);
            margin-bottom: 12px;
        }

        .nf-desc {
            font-size: 14px;
            color: var(--n-500);
            line-height: 1.6;
            max-width: 400px;
            margin: 0 auto 32px;
        }

        .nf-notice {
            display: inline-flex;
            align-items: flex-start;
            text-align: left;
            gap: 12px;
            background: var(--brand-50);
            border: 1px solid var(--brand-100);
            color: var(--brand-900);
            border-radius: 12px;
            padding: 16px;
            font-size: 13.5px;
            font-weight: 500;
            line-height: 1.5;
        }

        .nf-notice svg { flex-shrink: 0; margin-top: 2px; color: var(--brand-500); }

        /* ===== FOOTER ===== */
        .footer {
            text-align: center;
            font-size: 12px;
            color: var(--n-500);
            padding: 12px 16px 32px;
            line-height: 1.6;
        }

        @media (max-width: 520px) {
            .hero-title { font-size: 24px; }
            .hero { padding: 30px 20px 60px; }
            .nf-card { padding: 36px 20px; }
            .nf-title { font-size: 20px; }
            .nf-notice { flex-direction: column; align-items: center; text-align: center; }
            .nf-notice svg { margin-top: 0; }
        }
    </style>
</head>
<body>

<div class="topbar">
    <img src="{{ asset('assets/images/logobkl.png') }}" alt="Logo">
    <span class="topbar-text">Pemerintah Kota Bengkulu</span>
</div>

<div class="hero">
    <div class="hero-label">SIKANDIS</div>
    <h1 class="hero-title">QR Tidak Ditemukan</h1>
</div>

<div class="main-wrap">

    <div class="nf-card">
        <div class="nf-icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
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
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div>Hubungi Dinas Kominfo Kota Bengkulu jika terjadi kesalahan atau butuh validasi ulang kode verifikasi.</div>
        </div>
    </div>

    <div class="footer">
        Data ini dikelola secara resmi oleh <strong>Dinas Kominfo Kota Bengkulu</strong>.<br>
        &copy; {{ date('Y') }} SIKANDIS — Sistem Informasi Data Kendaraan Dinas
    </div>

</div>

</body>
</html>
