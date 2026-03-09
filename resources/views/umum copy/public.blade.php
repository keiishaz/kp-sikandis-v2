<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kendaraan - SIKANDIS</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
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
            <h1 class="page-title">{{ $kendaraan->nama_kendaraan }}</h1>
            <p class="page-subtitle">Informasi Detail Kendaraan</p>
        </header>

        <!-- Information Flow -->
        <main class="data-flow">
            <div class="data-group">
                <span class="label-text">Nomor Polisi</span>
                <span class="value-text highlight">{{ $kendaraan->no_polisi }}</span>
            </div>

            <div class="data-group">
                <span class="label-text">Jenis Kendaraan</span>
                <span class="value-text">{{ ucfirst($kendaraan->jenis) }}</span>
            </div>

            <div class="data-group">
                <span class="label-text">Tahun Kendaraan</span>
                <span class="value-text">{{ $kendaraan->thn_kendaraan }}</span>
            </div>

            <div class="data-group">
                <span class="label-text">Pemegang</span>
                <span class="value-text">{{ $kendaraan->pemegang }}</span>
            </div>
        </main>

        <footer class="simple-footer">
            <p class="footer-info">
                &copy; {{ date('Y') }} TIM MAGANG PROJECT SIKANDIS
            </p>
        </footer>
    </div>
</body>
</html>
