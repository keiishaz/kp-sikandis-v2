<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tidak Ditemukan - SIKANDIS</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    
    <style>
        .not-found-container {
            text-align: center;
            padding: 2rem 0;
        }
        .error-icon {
            width: 80px;
            height: 80px;
            background-color: #fee2e2;
            color: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-title);
            margin-bottom: 1rem;
        }
        .error-message {
            color: var(--text-muted);
            margin-bottom: 2rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        .back-button {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: background-color 0.2s;
        }
        .back-button:hover {
            background-color: var(--primary-dark);
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <!-- Header -->
        <header class="hero-header">
            <div class="brand-row">
                <img src="{{ asset('assets/images/logo-kominfo.png') }}" alt="Logo Kominfo">
                DISKOMINFO Pemerintah Kota Bengkulu
            </div>
            <br>
            <h1 class="page-title">Pencarian QR</h1>
            <p class="page-subtitle">Sistem Inventarisasi Kendaraan Dinas</p>
        </header>

        <!-- Not Found Content -->
        <main class="not-found-container">
            <div class="error-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
            </div>
            <h2 class="error-title">Data Tidak Ditemukan</h2>
            <p class="error-message">
                Maaf, kode QR yang Anda pindai tidak terdaftar dalam sistem kami. Pastikan Anda memindai kode QR resmi SIKANDIS.
            </p>
        </main>

        <footer class="simple-footer">
            <p class="footer-info">
                &copy; {{ date('Y') }} TIM MAGANG PROJECT SIKANDIS
            </p>
        </footer>
    </div>
</body>
</html>
