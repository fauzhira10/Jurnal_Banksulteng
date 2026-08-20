<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas - E-Jurnal Keluhan Bank Sulteng</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bs-navy: #0b2f54;
            --bs-navy-dark: #071f38;
            --bs-blue: #0066cc;
            --bs-blue-light: #e6f0fa;
            --bs-gold: #f59e0b;
            --bs-gray-100: #f1f5f9;
            --bs-gray-200: #e2e8f0;
            --bs-gray-300: #cbd5e1;
            --bs-gray-400: #94a3b8;
            --bs-gray-500: #64748b;
            --bs-gray-600: #475569;
            --bs-gray-700: #334155;
            --bs-gray-800: #1e293b;
            --radius-md: 10px;
            --radius-lg: 16px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: radial-gradient(circle at top right, #13467b 0%, var(--bs-navy) 45%, var(--bs-navy-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle Background Decorative Elements */
        .bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            border-radius: var(--radius-lg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
            overflow: hidden;
            position: relative;
            z-index: 10;
            animation: fadeIn 0.35s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header {
            background: linear-gradient(135deg, var(--bs-navy) 0%, var(--bs-navy-dark) 100%);
            color: #ffffff;
            padding: 32px 28px 24px;
            text-align: center;
            border-bottom: 3px solid var(--bs-gold);
            position: relative;
        }

        .brand-icon {
            width: 52px;
            height: 52px;
            background: #ffffff;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--bs-navy);
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .login-header h1 {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .login-header p {
            font-size: 12px;
            color: #93c5fd;
            font-weight: 500;
        }

        .login-body {
            padding: 32px 28px 30px;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: var(--bs-gray-700);
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: var(--bs-gray-400);
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px 11px 42px;
            border: 1px solid var(--bs-gray-300);
            border-radius: var(--radius-md);
            font-size: 14px;
            color: var(--bs-gray-800);
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .form-control:focus {
            border-color: var(--bs-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.15);
        }

        .btn-toggle-pwd {
            position: absolute;
            right: 12px;
            background: transparent;
            border: none;
            color: var(--bs-gray-400);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .btn-toggle-pwd:hover {
            color: var(--bs-gray-600);
        }

        .btn-login {
            width: 100%;
            padding: 12px 20px;
            background: linear-gradient(135deg, var(--bs-blue) 0%, var(--bs-navy) 100%);
            color: #ffffff;
            border: none;
            border-radius: var(--radius-md);
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 102, 204, 0.35);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 26px;
        }

        .btn-login:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 102, 204, 0.45);
        }

        /* Alert */
        .alert {
            padding: 12px 14px;
            border-radius: var(--radius-md);
            font-size: 13px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .login-footer {
            padding: 16px 28px 20px;
            text-align: center;
            font-size: 11.5px;
            color: var(--bs-gray-400);
            border-top: 1px solid var(--bs-gray-200);
            background: #fafafa;
        }
    </style>
</head>
<body>

<div class="bg-pattern"></div>

<div class="login-card">
    <!-- Header -->
    <div class="login-header">
        <div class="brand-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <path d="M7 8h10"/>
                <path d="M7 12h10"/>
                <path d="M7 16h6"/>
            </svg>
        </div>
        <h1>PORTAL E-JURNAL</h1>
        <p>PT Bank Pembangunan Daerah Sulawesi Tengah</p>
    </div>

    <!-- Body -->
    <div class="login-body">
        <!-- Notifikasi Sukses Logout -->
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Notifikasi Error Login -->
        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <!-- Username Input -->
            <div class="form-group">
                <label for="username">Username Petugas</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </span>
                    <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" required autofocus placeholder="Masukkan username">
                </div>
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan kata sandi">
                    <button type="button" class="btn-toggle-pwd" id="togglePwd" title="Tampilkan/Sembunyikan Sandi">
                        <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-login">
                <span>Masuk ke Sistem</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                    <polyline points="10 17 15 12 10 7"></polyline>
                    <line x1="15" y1="12" x2="3" y2="12"></line>
                </svg>
            </button>
        </form>
    </div>

    <!-- Footer -->
    <div class="login-footer">
        &copy; {{ date('Y') }} PT Bank Sulteng &bull; Layanan Pengaduan & Jurnal Keluhan
    </div>
</div>

<script>
    // Toggle Password Visibility
    const togglePwd = document.getElementById('togglePwd');
    const pwdInput = document.getElementById('password');

    togglePwd.addEventListener('click', function() {
        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            togglePwd.innerHTML = `
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                </svg>`;
        } else {
            pwdInput.type = 'password';
            togglePwd.innerHTML = `
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>`;
        }
    });
</script>

</body>
</html>
