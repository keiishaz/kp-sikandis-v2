<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Sandi SIKANDIS — Kominfo Kota Bengkulu</title>
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
            --warning-bg: #fffbeb;
            --warning-border: #fde68a;
            --warning-text: #b45309;
            --success-bg: #dcfce7;
            --success-text: #166534;
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
        .step-dot.completed {
            background: var(--success-bg);
            color: var(--success-text);
            display: flex; align-items: center; justify-content: center;
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

        .alert-box {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 14px; border-radius: 10px; margin-bottom: 20px;
            border: 1px solid transparent;
        }
        .alert-error {
            background: var(--danger-bg); border-color: var(--danger-border);
        }
        .alert-error svg { color: var(--danger-text); flex-shrink: 0; margin-top: 2px; }
        .alert-error p { color: var(--danger-text); font-size: 12.5px; font-weight: 500; line-height: 1.4; }

        .alert-warning {
            background: var(--warning-bg); border-color: var(--warning-border);
        }
        .alert-warning svg { color: var(--warning-text); flex-shrink: 0; margin-top: 2px; }
        .alert-warning p { color: var(--warning-text); font-size: 12.5px; font-weight: 600; line-height: 1.4; }

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
        .input-readonly {
            background: var(--n-50);
            border-color: var(--n-100);
            color: var(--n-500);
            cursor: not-allowed;
            font-weight: 600;
            font-family: monospace;
            padding-left: 16px;
        }
        .form-input:focus:not(.input-readonly) {
            border-color: var(--brand-600);
            box-shadow: 0 0 0 4px var(--brand-100);
        }

        .btn-toggle {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: var(--n-400);
            cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 4px;
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
        .btn-submit:hover:not(:disabled) {
            background: var(--brand-700);
            transform: translateY(-1px);
        }
        .btn-submit:disabled { background: var(--n-300); opacity: 0.6; cursor: not-allowed; box-shadow: none; }

        .back-link {
            display: block; text-align: center; margin-top: 16px;
            font-size: 13.5px; font-weight: 500; color: var(--n-500);
            text-decoration: none; transition: color 0.2s;
        }
        .back-link:hover { color: var(--brand-600); }

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
                <div class="step-dot" style="background: var(--success-bg); color: var(--success-text); font-size: 10px; display: flex; align-items: center; justify-content: center;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div class="step-dot active"></div>
            </div>

            <div class="form-header">
                <h2 class="form-title">Verifikasi Keamanan</h2>
                <p class="form-subtitle">Masukkan kata sandi untuk melanjutkan</p>
            </div>

            @error('password')
            <div class="alert-box alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <p>{{ $message }}</p>
            </div>
            @enderror

            {{-- Alert Timer --}}
            <div id="timer-container" class="alert-box alert-warning" style="display: none;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <p id="timer"></p>
            </div>

            <form method="POST" action="{{ route('login.password.submit') }}">
                @csrf
                <div class="form-group">
                    <input type="hidden" name="nik" value="{{ session('login_nik') }}">
                    <label for="nik" class="form-label">NIK Terdaftar</label>
                    <input type="text" id="nik" class="form-input input-readonly" value="{{ session('login_nik') }}" readonly tabindex="-1">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Masukkan sandi Anda" required autofocus>
                        
                        <button type="button" id="togglePassword" class="btn-toggle" tabindex="-1">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <svg id="eyeOffIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="btn-submit">
                    Masuk Sekarang
                </button>

                <a href="{{ route('login') }}" class="back-link">
                    &larr; Ganti NIK
                </a>
            </form>
        </main>

        <footer class="login-footer">
            &copy; {{ date('Y') }} Dinas Kominfo Kota Bengkulu<br>
            Tim Magang Project SIKANDIS
        </footer>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');
        const eyeOffIcon = document.querySelector('#eyeOffIcon');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            if (type === 'password') {
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            } else {
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            }
        });

        const lockedUntilString = "{{ session('locked_until') }}";
        if (lockedUntilString) {
            const timerDiv = document.getElementById('timer');
            const timerContainer = document.getElementById('timer-container');
            const submitBtn = document.getElementById('submitBtn');
            const passwordInput = document.getElementById('password');
            const lockedUntil = new Date(lockedUntilString).getTime();

            function updateTimer() {
                const now = new Date().getTime();
                const sec = Math.floor((lockedUntil - now) / 1000); 
                if(sec > 0){
                    timerDiv.textContent = "Akun dikunci. Tunggu " + sec + " detik.";
                    timerContainer.style.display = 'flex';
                    submitBtn.disabled = true;
                    passwordInput.disabled = true;
                } else {
                    timerDiv.textContent = "";
                    timerContainer.style.display = 'none';
                    submitBtn.disabled = false;
                    passwordInput.disabled = false;
                    clearInterval(interval);
                }
            }
            const interval = setInterval(updateTimer, 1000);
            updateTimer();
        }
    </script>
</body>
</html>
