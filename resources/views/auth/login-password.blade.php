<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login SIKANDIS - Verifikasi Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-50: #eff6ff;
            --brand-100: #dbeafe;
            --brand-400: #60a5fa;
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
            --danger-bg: #fef2f2;
            --danger-border: #fecaca;
            --danger-text: #b91c1c;
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
        }

        /* ===== SPLIT LAYOUT ===== */
        .split-layout {
            display: flex;
            flex-direction: row-reverse;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        /* ===== LEFT PANEL (BRANDING) ===== */
        .brand-panel {
            width: 420px;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--brand-900) 0%, var(--brand-700) 100%);
            position: relative;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #fff;
            overflow: hidden;
        }

        .brand-content { position: relative; z-index: 1; }

        .brand-logo-wrapper {
            background: #fff;
            display: inline-flex;
            padding: 12px 16px;
            border-radius: 16px;
            margin-bottom: 32px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }
        .brand-logo { height: 48px; width: auto; display: block; }
        .brand-title { font-size: 32px; font-weight: 800; line-height: 1.1; margin-bottom: 12px; letter-spacing: -0.5px; }
        .brand-subtitle { font-size: 14.5px; color: rgba(255,255,255,0.7); line-height: 1.6; margin-bottom: 40px; max-width: 90%; }

        .feature-list { list-style: none; display: flex; flex-direction: column; gap: 16px; }
        .feature-item { display: flex; align-items: flex-start; gap: 12px; font-size: 14.5px; color: rgba(255,255,255,0.9); font-weight: 500; }
        .feature-icon { width: 20px; height: 20px; color: var(--brand-400); flex-shrink: 0; margin-top: 1px; }

        .brand-footer { position: relative; z-index: 1; font-size: 12px; color: rgba(255,255,255,0.4); }

        /* ===== RIGHT PANEL (FORM) ===== */
        .form-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--n-50);
            position: relative;
        }

        .form-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(var(--n-200) 1.5px, transparent 1.5px);
            background-size: 28px 28px;
            z-index: 0;
        }

        .form-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            background: #fff;
            padding: 48px 40px;
            border-radius: 24px;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.05), 0 10px 20px -5px rgba(0,0,0,0.02);
            border: 1px solid var(--n-100);
        }

        /* ===== STEP INDICATOR ===== */
        .step-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
            max-width: 200px;
            margin-left: auto;
            margin-right: auto;
        }

        .step-line {
            position: absolute;
            top: 14px;
            left: 30px;
            right: 30px;
            height: 2px;
            background: var(--brand-300);
            background: linear-gradient(90deg, var(--success-text) 0%, var(--n-200) 100%);
            z-index: 0;
        }

        .step-item {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .step-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            background: var(--n-200);
            color: var(--n-500);
            border: 4px solid var(--n-50); 
            transition: all 0.3s;
        }

        .step-label { font-size: 11px; font-weight: 600; color: var(--n-400); text-transform: uppercase; letter-spacing: 0.5px; }

        /* Actives (Password Step) */
        .step-item.active .step-dot { background: var(--brand-600); color: #fff; box-shadow: 0 0 0 3px var(--brand-100); }
        .step-item.active .step-label { color: var(--brand-700); }
        
        /* Completed (NIP Step) */
        .step-item.completed .step-dot { background: var(--success-bg); color: var(--success-text); border-color: var(--n-50); }
        .step-item.completed .step-label { color: var(--success-text); }

        /* ===== FORM ELEMENTS ===== */
        .form-header { margin-bottom: 32px; text-align: center; }
        .form-title { font-size: 24px; font-weight: 800; color: var(--n-900); margin-bottom: 6px; letter-spacing: -0.5px; }
        .form-subtitle { font-size: 14px; color: var(--n-500); }

        .alert-box {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        
        .alert-error {
            background: var(--danger-bg);
            border: 1px solid var(--danger-border);
        }
        .alert-error svg { color: var(--danger-text); flex-shrink: 0; margin-top: 2px; }
        .alert-error p { color: var(--danger-text); font-size: 13px; font-weight: 500; line-height: 1.5; }

        .alert-warning {
            background: #fffbeb;
            border: 1px solid #fde68a;
        }
        .alert-warning svg { color: #d97706; flex-shrink: 0; margin-top: 2px; }
        .alert-warning p { color: #b45309; font-size: 13px; font-weight: 600; line-height: 1.5; }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--n-800); margin-bottom: 8px; }

        .input-wrapper { position: relative; }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--n-400);
            pointer-events: none;
        }
        
        .form-input {
            width: 100%;
            height: 48px;
            padding: 0 16px 0 42px;
            background: #fff;
            border: 1px solid var(--n-200);
            border-radius: 10px;
            font-size: 14.5px;
            color: var(--n-900);
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .form-input:focus { border-color: var(--brand-500); box-shadow: 0 0 0 3px var(--brand-100); }
        .form-input::placeholder { color: var(--n-400); }

        .input-readonly {
            background: var(--n-100);
            color: var(--n-600);
            cursor: not-allowed;
            font-family: monospace;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-color: var(--n-200);
            padding-left: 16px; /* No icon for readonly */
        }
        .input-readonly:focus { box-shadow: none; border-color: var(--n-200); }

        .btn-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--n-400);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            border-radius: 4px;
        }
        .btn-toggle:hover { color: var(--n-600); background: var(--n-50); }

        .btn-submit {
            width: 100%;
            height: 48px;
            background: var(--brand-600);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14.5px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s;
            box-shadow: 0 4px 12px rgba(37,99,235,0.2);
        }
        .btn-submit:hover:not(:disabled) { background: var(--brand-700); }
        .btn-submit:disabled { background: var(--n-400); cursor: not-allowed; box-shadow: none; }

        .back-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-top: 16px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--n-500);
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--brand-600); }

        .form-footer { margin-top: 48px; text-align: center; font-size: 12px; color: var(--n-400); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .brand-panel { display: none; }
            .form-panel { background: var(--n-50); padding: 20px; align-items: center; }
            .form-container { padding: 32px 24px; border-radius: 20px; }
            .step-dot { border-color: #fff; } 
            .step-line { background: linear-gradient(90deg, var(--success-text) 0%, #fff 100%); }
            .step-item.completed .step-dot { border-color: #fff; }
        }
    </style>
</head>
<body>

    <div class="split-layout">
        
        {{-- PANEL KIRI: BRANDING --}}
        <div class="brand-panel">
            <div class="brand-content">
                <div class="brand-logo-wrapper">
                    <img src="{{ asset('assets/images/logo-kominfo.png') }}" alt="Logo Kominfo" class="brand-logo">
                </div>
                <h1 class="brand-title">SIKANDIS</h1>
                <p class="brand-subtitle">Sistem Informasi Data Kendaraan Dinas<br>Dinas Kominfo Kota Bengkulu</p>

                <ul class="feature-list">
                    <li class="feature-item">
                        <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Manajemen kendaraan terpusat
                    </li>
                    <li class="feature-item">
                        <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        QR Code verifikasi digital
                    </li>
                    <li class="feature-item">
                        <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Pemantauan pajak real-time
                    </li>
                </ul>
            </div>
            
            <div class="brand-footer">
                &copy; {{ date('Y') }} Tim Magang Project SIKANDIS
            </div>
        </div>

        {{-- PANEL KANAN: FORM --}}
        <div class="form-panel">
            <div class="form-container">
                
                {{-- Step Indicator --}}
                <div class="step-wrapper">
                    <div class="step-line"></div>
                    <div class="step-item completed">
                        <div class="step-dot">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="step-label">NIP</div>
                    </div>
                    <div class="step-item active">
                        <div class="step-dot">2</div>
                        <div class="step-label">Sandi</div>
                    </div>
                </div>

                {{-- Form Header --}}
                <div class="form-header">
                    <h2 class="form-title">Verifikasi Password</h2>
                    <p class="form-subtitle">Masukkan password untuk NIP yang dipilih</p>
                </div>

                {{-- Alert Error --}}
                @error('password')
                <div class="alert-box alert-error">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <p>{{ $message }}</p>
                </div>
                @enderror

                {{-- Alert Timer --}}
                <div id="timer-container" class="alert-box alert-warning" style="display: none;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <p id="timer"></p>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('login.password.submit') }}" novalidate>
                    @csrf
                    
                    <div class="form-group">
                        <input type="hidden" name="nip" value="{{ session('login_nip') }}">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" id="nip" class="form-input input-readonly" value="{{ session('login_nip') }}" readonly tabindex="-1">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <input type="password" id="password" name="password" class="form-input" style="padding-right: 42px;" placeholder="Masukkan password Anda" required autofocus autocomplete="current-password">
                            
                            <button type="button" id="togglePassword" class="btn-toggle" tabindex="-1">
                                <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg id="eyeOffIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn" class="btn-submit">
                        Masuk
                    </button>

                    <a href="{{ route('login') }}" class="back-link">
                        &larr; Ganti NIP
                    </a>
                </form>

            </div>
        </div>

    </div>

    <script>
        // Password Toggle
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

        // Countdown Timer
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
                    timerDiv.textContent = "Akun dikunci sementara. Coba lagi dalam " + sec + " detik.";
                    timerContainer.style.display = 'flex';
                    submitBtn.disabled = true;
                    passwordInput.disabled = true;
                } else {
                    timerDiv.textContent = "";
                    timerContainer.style.display = 'none';
                    submitBtn.disabled = false;
                    passwordInput.disabled = false;
                    if (typeof interval !== 'undefined') clearInterval(interval);
                }
            }

            const interval = setInterval(updateTimer, 1000);
            updateTimer(); // Initial call
        }
    </script>
</body>
</html>
