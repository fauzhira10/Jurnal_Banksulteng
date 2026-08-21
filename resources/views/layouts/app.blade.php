<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Jurnal Keluhan') - Bank Sulteng</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bs-navy: #0b2f54;
            --bs-navy-dark: #071f38;
            --bs-navy-light: #13467b;
            --bs-blue: #0066cc;
            --bs-blue-light: #e6f0fa;
            --bs-gold: #f59e0b;
            --bs-gold-light: #fef3c7;
            --bs-success: #10b981;
            --bs-success-light: #d1fae5;
            --bs-danger: #ef4444;
            --bs-danger-light: #fee2e2;
            --bs-warning: #f59e0b;
            --bs-warning-light: #fef3c7;
            --bs-info: #06b6d4;
            --bs-info-light: #cffafe;
            --bs-gray-50: #f8fafc;
            --bs-gray-100: #f1f5f9;
            --bs-gray-200: #e2e8f0;
            --bs-gray-300: #cbd5e1;
            --bs-gray-400: #94a3b8;
            --bs-gray-500: #64748b;
            --bs-gray-600: #475569;
            --bs-gray-700: #334155;
            --bs-gray-800: #1e293b;
            --bs-gray-900: #0f172a;
            --sidebar-width: 270px;
            --topbar-height: 70px;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bs-gray-100);
            color: var(--bs-gray-800);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--bs-navy) 0%, var(--bs-navy-dark) 100%);
            color: #ffffff;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 22px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.15);
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #ffffff 0%, #dbeafe 100%);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--bs-navy);
            font-weight: 800;
            font-size: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }

        .brand-text h2 {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #ffffff;
            line-height: 1.2;
        }

        .brand-text p {
            font-size: 11px;
            color: #93c5fd;
            font-weight: 500;
            margin-top: 2px;
            letter-spacing: 0.3px;
        }

        .sidebar-nav {
            padding: 20px 14px;
            flex-grow: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            font-weight: 700;
            padding: 10px 12px 6px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: var(--radius-md);
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(3px);
        }

        .nav-link.active {
            color: #ffffff;
            background: linear-gradient(90deg, rgba(0, 102, 204, 0.85) 0%, rgba(19, 70, 123, 0.95) 100%);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 102, 204, 0.35);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 15%;
            height: 70%;
            width: 4px;
            background-color: var(--bs-gold);
            border-radius: 0 4px 4px 0;
        }

        .nav-link svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            transition: transform 0.2s;
        }

        .nav-link:hover svg {
            transform: scale(1.08);
        }

        .nav-badge {
            margin-left: auto;
            background: rgba(255, 255, 255, 0.18);
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            color: #ffffff;
        }

        .sidebar-footer {
            padding: 16px 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.25);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .user-block {
            display: flex;
            align-items: center;
            gap: 10px;
            overflow: hidden;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--bs-gold) 0%, #d97706 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        }

        .user-info {
            overflow: hidden;
        }

        .user-name {
            font-size: 12.5px;
            font-weight: 600;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 11px;
            color: #94a3b8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-sidebar-logout {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 7px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .btn-sidebar-logout:hover {
            background: rgba(239, 68, 68, 0.3);
            color: #ffffff;
        }

        /* ================= MAIN WRAPPER ================= */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        /* ================= TOPBAR ================= */
        .topbar {
            height: var(--topbar-height);
            background-color: #ffffff;
            border-bottom: 1px solid var(--bs-gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 900;
            box-shadow: var(--shadow-sm);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-toggle-sidebar {
            background: transparent;
            border: 1px solid var(--bs-gray-200);
            border-radius: var(--radius-sm);
            width: 38px;
            height: 38px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--bs-gray-600);
            transition: all 0.2s;
        }

        .btn-toggle-sidebar:hover {
            background-color: var(--bs-gray-100);
            color: var(--bs-navy);
        }

        .page-heading h1 {
            font-size: 18px;
            font-weight: 700;
            color: var(--bs-navy);
        }

        .page-heading p {
            font-size: 12px;
            color: var(--bs-gray-500);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .system-clock {
            display: flex;
            align-items: center;
            gap: 8px;
            background-color: var(--bs-gray-50);
            border: 1px solid var(--bs-gray-200);
            padding: 7px 14px;
            border-radius: 30px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--bs-gray-700);
        }

        .system-clock svg {
            color: var(--bs-blue);
        }

        .btn-quick-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--bs-blue) 0%, var(--bs-navy) 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(0, 102, 204, 0.25);
            transition: all 0.2s;
        }

        .btn-quick-action:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 102, 204, 0.35);
        }

        .btn-topbar-logout {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #fff5f5;
            color: var(--bs-danger);
            border: 1px solid #fecaca;
            padding: 8px 14px;
            border-radius: var(--radius-md);
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-topbar-logout:hover {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* ================= CONTENT AREA ================= */
        .content-body {
            padding: 25px 30px;
            flex-grow: 1;
        }

        /* ================= FOOTER ================= */
        .page-footer {
            padding: 15px 30px;
            background-color: #ffffff;
            border-top: 1px solid var(--bs-gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: var(--bs-gray-500);
        }

        /* ================= COMMON UI COMPONENTS ================= */
        .card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--bs-gray-200);
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--bs-gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ffffff;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--bs-navy);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body {
            padding: 24px;
        }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            font-size: 13.5px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            line-height: 1.5;
        }

        .alert-success {
            background-color: var(--bs-success-light);
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-danger {
            background-color: var(--bs-danger-light);
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-menunggu {
            background-color: var(--bs-warning-light);
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .badge-success {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .badge-done {
            background-color: var(--bs-success-light);
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .badge-rejected {
            background-color: var(--bs-danger-light);
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }

        .badge-strip, .badge-neutral {
            background-color: #f1f5f9;
            color: #64748b;
            border: 1px solid #cbd5e1;
            font-weight: 700;
        }

        .badge-channel {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
            font-size: 11px;
            font-weight: 600;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: var(--radius-md);
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--bs-blue) 0%, var(--bs-navy) 100%);
            color: #ffffff;
        }

        .btn-primary:hover {
            opacity: 0.95;
            box-shadow: 0 4px 12px rgba(0, 102, 204, 0.3);
        }

        .btn-secondary {
            background-color: var(--bs-gray-100);
            color: var(--bs-gray-700);
            border: 1px solid var(--bs-gray-300);
        }

        .btn-secondary:hover {
            background-color: var(--bs-gray-200);
            color: var(--bs-gray-900);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: var(--radius-sm);
        }

        /* Mobile Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 950;
            backdrop-filter: blur(2px);
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 992px) {
            .btn-toggle-sidebar {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .content-body {
                padding: 20px 15px;
            }

            .topbar {
                padding: 0 15px;
            }

            .system-clock {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="brand-logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <path d="M7 8h10"/>
                <path d="M7 12h10"/>
                <path d="M7 16h6"/>
            </svg>
        </div>
        <div class="brand-text">
            <h2>E-JURNAL KELUHAN</h2>
            <p>PT Bank Sulteng</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>

        <!-- Menu 1: Form Input Jurnal -->
        <a href="{{ route('jurnal.create') }}" class="nav-link {{ request()->routeIs('jurnal.create') || request()->is('/') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            <span>Input Jurnal Keluhan</span>
        </a>

        <!-- Menu 2: Data Keluhan & Pencarian -->
        <a href="{{ route('jurnal.index') }}" class="nav-link {{ request()->routeIs('jurnal.index') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="3" y1="9" x2="21" y2="9"></line>
                <line x1="9" y1="21" x2="9" y2="9"></line>
            </svg>
            <span>Data Keluhan</span>
            <span class="nav-badge" id="sidebarBadgeCount">{{ number_format(\App\Models\Jurnal::count(), 0, ',', '.') }}</span>
        </a>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="user-block">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name ?? 'Administrator' }}</div>
                <div class="user-role">&#64;{{ Auth::user()->username ?? 'admin' }} &bull; Admin</div>
            </div>
        </div>

        <button type="button" class="btn-sidebar-logout" onclick="openLogoutModal()" title="Keluar / Logout">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </button>
    </div>
</aside>

<!-- MAIN CONTENT WRAPPER -->
<div class="main-wrapper">
    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="btn-toggle-sidebar" id="sidebarToggle" title="Toggle Sidebar">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <div class="page-heading">
                <h1>@yield('page_title', 'Jurnal Keluhan Nasabah')</h1>
                <p>@yield('page_subtitle', 'PT Bank Sulteng - Layanan Operasional & Pengaduan Nasabah')</p>
            </div>
        </div>

        <div class="topbar-right">
            <div class="system-clock">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span id="liveClock">Memuat jam...</span>
            </div>

            @yield('topbar_action')
        </div>
    </header>

    <!-- CONTENT BODY -->
    <main class="content-body">
        <!-- Flash Alert Success -->
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Flash Alert Error -->
        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div>
                    <strong>Perhatian! Terdapat kesalahan input:</strong>
                    <ul style="margin: 6px 0 0 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="page-footer">
        <div>&copy; {{ date('Y') }} <strong>PT Bank Pembangunan Daerah Sulawesi Tengah</strong>. Hak Cipta Dilindungi.</div>
        <div>Sistem Pengelolaan Jurnal Keluhan Transaksi v1.3</div>
    </footer>
</div>

<!-- MODAL KONFIRMASI LOGOUT -->
<div class="modal-backdrop" id="logoutModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(15, 23, 42, 0.65); z-index: 1200; backdrop-filter: blur(3px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #ffffff; border-radius: var(--radius-lg); width: 100%; max-width: 420px; box-shadow: var(--shadow-xl); overflow: hidden; animation: modalFadeIn 0.2s ease-out;">
        <div style="padding: 26px 24px 18px; text-align: center;">
            <div style="width: 56px; height: 56px; background-color: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: var(--bs-danger);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </div>
            <h3 style="font-size: 18px; font-weight: 700; color: var(--bs-navy); margin-bottom: 8px;">Konfirmasi Logout</h3>
            <p style="font-size: 13.5px; color: var(--bs-gray-600); line-height: 1.5; margin: 0;">
                Apakah Anda benar-benar ingin keluar dari <strong>Sistem E-Jurnal Bank Sulteng</strong>?
            </p>
        </div>
        <div style="padding: 16px 24px 20px; background-color: var(--bs-gray-50); border-top: 1px solid var(--bs-gray-200); display: flex; justify-content: center; gap: 12px;">
            <button type="button" class="btn btn-secondary" onclick="closeLogoutModal()" style="min-width: 110px;">
                Batal
            </button>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn" style="background-color: var(--bs-danger); color: #ffffff; min-width: 120px; border: none; font-weight: 600;">
                    Ya, Logout
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Live Clock (WITA)
    function updateClock() {
        const now = new Date();
        const options = { 
            weekday: 'short', 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric',
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit', 
            hour12: false,
            timeZone: 'Asia/Makassar' // WITA (Sulawesi Tengah)
        };
        const formatter = new Intl.DateTimeFormat('id-ID', options);
        document.getElementById('liveClock').textContent = formatter.format(now) + ' WITA';
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Mobile Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');

    function toggleSidebar() {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    }

    if(toggleBtn) {
        toggleBtn.addEventListener('click', toggleSidebar);
    }
    if(overlay) {
        overlay.addEventListener('click', toggleSidebar);
    }

    // Modal Logout Functions
    function openLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if(modal) {
            modal.style.display = 'flex';
        }
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if(modal) {
            modal.style.display = 'none';
        }
    }

    // Close Modal on Escape or Click Outside
    window.addEventListener('keydown', function(e) {
        if(e.key === 'Escape') {
            closeLogoutModal();
        }
    });

    const logoutModal = document.getElementById('logoutModal');
    if(logoutModal) {
        logoutModal.addEventListener('click', function(e) {
            if(e.target === this) {
                closeLogoutModal();
            }
        });
    }
</script>

@stack('scripts')
</body>
</html>
