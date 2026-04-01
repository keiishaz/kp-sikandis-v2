<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk SIKANDIS — Kominfo Kota Bengkulu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-50: #eff6ff;
            --brand-100: #dbeafe;
            --brand-600: #2563eb;
            --brand-700: #1d4ed8;
            --brand-800: #1e40af;
            --brand-900: #1e3a8a;
            --n-50: #f8fafc;
            --n-100: #f1f5f9;
            --n-200: #e2e8f0;
            --n-400: #94a3b8;
            --n-500: #64748b;
            --n-700: #334155;
            --n-800: #1e293b;
            --n-900: #0f172a;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            background-image: radial-gradient(#cbd5e1 1px, transparent 0);
            background-size: 24px 24px;
            color: var(--n-900);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #fff;
            width: 100%;
            max-width: 820px;
            min-height: 480px;
            display: flex;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px -8px rgba(0, 0, 0, 0.1);
        }

        /* Branding Side (Solid Blue) */
        .brand-side {
            flex: 1;
            background-color: var(--brand-600);
            background-image: 
                radial-gradient(at 0% 0%, rgba(255,255,255,0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(0,0,0,0.08) 0px, transparent 50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            color: #fff;
            text-align: center;
        }

        .logo-box {
            width: 72px;
            height: 72px;
            background: #fff;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            padding: 12px;
        }
        .logo-img { width: 100%; height: 100%; object-fit: contain; }

        .brand-name {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .brand-divider {
            width: 40px;
            height: 3px;
            background: rgba(255,255,255,0.25);
            border-radius: 2px;
            margin-bottom: 20px;
        }

        .brand-desc {
            font-size: 14.5px;
            font-weight: 500;
            line-height: 1.5;
            color: rgba(255,255,255,0.8);
            max-width: 240px;
        }

        /* Form Side (Clean White) */
        .form-side {
            flex: 1;
            background: #fff;
            /* No dots in white panel as requested */
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px;
        }

        .form-header {
            margin-bottom: 32px;
        }
        .form-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--n-900);
            margin-bottom: 8px;
        }
        .form-subtitle {
            font-size: 14px;
            color: var(--n-500);
            line-height: 1.5;
        }

        .step-indicator {
            display: flex;
            justify-content: center; /* Centered as requested */
            gap: 6px;
            margin-bottom: 24px;
        }
        .step-dot {
            width: 24px; height: 5px; border-radius: 3px; background: var(--n-100);
        }
        .step-dot.active {
            background: var(--brand-600);
        }

        .form-group { margin-bottom: 24px; }
        .form-label {
            display: block;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--n-700);
            margin-bottom: 8px;
        }

        .input-wrapper { position: relative; }
        .input-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: var(--n-400); pointer-events: none;
        }
        .form-input {
            width: 100%; height: 50px;
            padding: 0 16px 0 42px;
            border: 2px solid var(--n-50);
            border-radius: 10px;
            font-size: 15px;
            color: var(--n-900);
            background: #fff;
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .form-input:focus {
            border-color: var(--brand-600);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
        }

        .btn-submit {
            width: 100%; height: 50px;
            background: var(--brand-600);
            color: #fff; border: none;
            border-radius: 10px;
            font-size: 15px; font-weight: 600;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            gap: 10px; transition: all 0.2s;
        }
        .btn-submit:hover {
            background: var(--brand-700);
            box-shadow: 0 6px 12px rgba(37, 99, 235, 0.2);
        }

        .alert-error {
            display: flex; align-items: center; gap: 10px;
            background: #fff1f2; border: 1px solid #fecdd3;
            padding: 12px 14px; border-radius: 10px; margin-bottom: 20px;
        }
        .alert-error svg { color: #e11d48; flex-shrink: 0; }
        .alert-error p { color: #9f1239; font-size: 13px; font-weight: 600; }

        .login-footer {
            margin-top: 28px;
            font-size: 12.5px;
            color: var(--n-400);
            line-height: 1.6;
            text-align: center; /* Centered as requested */
        }

        @media (max-width: 860px) {
            .login-card { flex-direction: column; max-width: 420px; min-height: auto; }
            .brand-side { padding: 32px; }
            .form-side { padding: 32px; }
            .brand-name { font-size: 24px; }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Sidebar Branding -->
        <div class="brand-side">
            <div class="logo-box">
                <img src="{{ asset('assets/images/logo-kominfo.png') }}" alt="Logo Kominfo" class="logo-img">
            </div>
            <h1 class="brand-name">SIKANDIS</h1>
            <div class="brand-divider"></div>
            <p class="brand-desc">Sistem Informasi Kendaraan Dinas Kota Bengkulu</p>
        </div>

        <!-- Main Form Area -->
        <main class="form-side">
            <div class="step-indicator">
                <div class="step-dot active"></div>
                <div class="step-dot"></div>
            </div>

            <div class="form-header">
                <h2 class="form-title">Autentikasi Pengguna</h2>
                <p class="form-subtitle">Langkah 1 dari 2: Identifikasi NIK</p>
            </div>

            @error('nik')
            <div class="alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <p>{{ $message }}</p>
            </div>
            @enderror

            <form method="POST" action="{{ route('login.nik') }}">
                @csrf
                <div class="form-group">
                    <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan)</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <input type="text" id="nik" name="nik" class="form-input" placeholder="16 digit NIK..." maxlength="16" required autofocus>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Lanjutkan
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
            </form>

            <footer class="login-footer">
                &copy; {{ date('Y') }} Dinas Kominfo Bengkulu<br>
                Tim Magang SIKANDIS
            </footer>
        </main>
    </div>

</body>
</html>
