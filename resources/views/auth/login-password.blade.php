<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SIKANDIS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* System Theme Colors */
            --primary: #1e3a8a;       /* Blue 900 */
            --primary-hover: #1e40af; /* Blue 800 */
            --accent: #3b82f6;        /* Blue 500 */
            --bg-body: #f8fafc;       /* Slate 50 */
            --bg-card: #ffffff;
            --text-main: #1e293b;     /* Slate 800 */
            --text-muted: #64748b;    /* Slate 500 */
            --border: #e2e8f0;        /* Slate 200 */
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --focus-ring: rgba(59, 130, 246, 0.5);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            /* Subtle Background Pattern */
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }

        .login-card {
            background: var(--bg-card);
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            border: 1px solid var(--border);
            text-align: center;
        }

        .brand-logo {
            margin-bottom: 1.5rem;
        }

        .brand-logo img {
            height: 64px; /* Adjust size as needed */
            width: auto;
            object-fit: contain;
        }

        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .login-header p {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
            text-align: left;
        }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        input {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            transition: all 0.2s ease;
            background-color: #fff;
            color: var(--text-main);
        }
        
        input:read-only {
            background-color: #f1f5f9;
            color: var(--text-muted);
            cursor: not-allowed;
        }

        input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--focus-ring);
        }

        .btn-login {
            width: 100%;
            padding: 0.875rem;
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 0.5rem;
        }
        
        .btn-login:disabled {
            background-color: var(--text-muted);
            cursor: not-allowed;
        }

        .btn-login:hover:not(:disabled) {
            background-color: var(--primary-hover);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.35rem;
        }
        
        .timer-message {
            color: #ef4444;
            font-size: 0.90rem;
            font-weight: 600;
            margin-top: 1rem;
            padding: 0.5rem;
            border: 1px dashed #ef4444;
            border-radius: 8px;
            background-color: #fef2f2;
        }

        .footer-text {
            margin-top: 2rem;
            font-size: 0.75rem;
            color: var(--text-muted);
            border-top: 1px dashed var(--border);
            padding-top: 1rem;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 1rem;
            font-size: 0.85rem;
            color: var(--accent);
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-logo">
            <img src="{{ asset('assets/images/logo-kominfo.png') }}" alt="Logo Kominfo">
        </div>
        
        <div class="login-header">
            <h1>SIKANDIS</h1>
            <p>Verifikasi Password</p>
        </div>

        <form method="POST" action="{{ route('login.password.submit') }}">
            @csrf

            <div class="form-group">
                <label for="nip">NIP</label>
                <input id="nip" type="text" value="{{ session('login_nip') }}" readonly>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-container" style="position: relative;">
                    <input id="password" name="password" type="password" placeholder="Masukkan password" required style="padding-right: 2.5rem;" autofocus>
                    <button type="button" id="togglePassword" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center;">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div id="timer-container" style="display: none;">
                <div id="timer" class="timer-message"></div>
            </div>

            <button type="submit" id="submitBtn" class="btn-login">Login</button>
            
            <a href="{{ route('login') }}" class="back-link">← Ganti NIP</a>
        </form>

        <div class="footer-text">
            &copy; {{ date('Y') }} TIM MAGANG PROJECT SIKANDIS
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
                    timerDiv.textContent = "Akun dikunci sementara. Tunggu " + sec + " detik.";
                    timerContainer.style.display = 'block';
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
