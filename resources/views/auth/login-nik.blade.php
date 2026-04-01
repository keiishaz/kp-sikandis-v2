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
            --n-800: #1e293b;
            --n-900: #0f172a;
            --danger-bg: #fef2f2;
            --danger-border: #fecaca;
            --danger-text: #b91c1c;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--n-50);
            color: var(--n-900);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: 
                radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(30, 58, 138, 0.05) 0px, transparent 50%);
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-box {
            width: 64px;
            height: 64px;
            background: #fff;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 10px;
        }

        .logo-img { width: 100%; height: 100%; object-fit: contain; }

        .app-name {
            font-size: 24px;
            font-weight: 800;
            color: var(--brand-900);
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .app-desc {
            font-size: 13px;
            color: var(--n-500);
            font-weight: 500;
            line-height: 1.4;
        }

        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 20px 40px -12px rgba(15, 23, 42, 0.1);
            border: 1px solid var(--n-100);
        }

        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 32px;
        }
        .step-dot {
            width: 8px; height: 8px; border-radius: 50%; background: var(--n-200);
        }
        .step-dot.active {
            width: 24px; border-radius: 4px; background: var(--brand-600);
        }

        .form-header {
            margin-bottom: 28px;
            text-align: center;
        }
        .form-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--n-800);
            margin-bottom: 8px;
        }
        .form-subtitle {
            font-size: 13.5px;
            color: var(--n-500);
        }

        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 13px;
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
            width: 100%; height: 48px;
            padding: 0 16px 0 42px;
            border: 1.5px solid var(--n-200);
            border-radius: 12px;
            font-size: 14.5px;
            color: var(--n-900);
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
        }
        .form-input:focus {
            border-color: var(--brand-600);
            box-shadow: 0 0 0 4px var(--brand-100);
        }

        .btn-submit {
            width: 100%; height: 48px;
            background: var(--brand-600);
            color: #fff; border: none;
            border-radius: 12px;
            font-size: 14.5px; font-weight: 600;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            gap: 8px; transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        .btn-submit:hover {
            background: var(--brand-700);
            transform: translateY(-1px);
        }

        .alert-error {
            display: flex; align-items: center; gap: 10px;
            background: var(--danger-bg); border: 1px solid var(--danger-border);
            padding: 12px 14px; border-radius: 10px; margin-bottom: 20px;
        }
        .alert-error svg { color: var(--danger-text); flex-shrink: 0; }
        .alert-error p { color: var(--danger-text); font-size: 12.5px; font-weight: 500; }

        .login-footer {
            margin-top: 32px;
            text-align: center;
            font-size: 12px;
            color: var(--n-400);
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <header class="brand-header">
            <div class="logo-box">
                <img src="{{ asset('assets/images/logo-kominfo.png') }}" alt="Logo Kominfo" class="logo-img">
            </div>
            <h1 class="app-name">SIKANDIS</h1>
            <p class="app-desc">Sistem Informasi Kendaraan Dinas<br>Kota Bengkulu</p>
        </header>

        <main class="login-card">
            <div class="step-indicator">
                <div class="step-dot active"></div>
                <div class="step-dot"></div>
            </div>

            <div class="form-header">
                <h2 class="form-title">Autentikasi Pengguna</h2>
                <p class="form-subtitle">Masukkan NIK Anda untuk masuk</p>
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
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
            </form>
        </main>

        <footer class="login-footer">
            &copy; {{ date('Y') }} Dinas Kominfo Kota Bengkulu<br>
            Tim Magang Project SIKANDIS
        </footer>
    </div>

</body>
</html>
