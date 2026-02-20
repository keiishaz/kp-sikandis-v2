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

        .btn-login:hover {
            background-color: var(--primary-hover);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.35rem;
        }

        .footer-text {
            margin-top: 2rem;
            font-size: 0.75rem;
            color: var(--text-muted);
            border-top: 1px dashed var(--border);
            padding-top: 1rem;
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
            <p>Sistem Informasi Data Kendaraan Dinas</p>
        </div>

        <form method="POST" action="{{ route('login.nip') }}">
            @csrf

            <div class="form-group">
                <label for="nip">NIP</label>
                <input id="nip" name="nip" type="text" value="{{ old('nip') }}" placeholder="Masukkan NIP" required autofocus>
                @error('nip')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-login">Lanjut</button>
        </form>

        <div class="footer-text">
            &copy; {{ date('Y') }} TIM MAGANG PROJECT SIKANDIS
        </div>
    </div>

</body>
</html>
