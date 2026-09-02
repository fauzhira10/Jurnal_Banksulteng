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
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- ApexCharts CDN (Chart Library) -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.54.1/dist/apexcharts.min.js" defer></script>

    @stack('styles')
</head>
<body class="font-sans bg-slate-100 text-slate-800 min-h-screen flex flex-col overflow-x-hidden antialiased">

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay fixed inset-0 bg-slate-900/50 z-40 backdrop-blur-xs hidden transition-opacity duration-300" id="sidebarOverlay"></div>

<!-- SIDEBAR -->
<aside class="sidebar fixed top-0 bottom-0 left-0 w-[270px] bg-gradient-to-b from-navy to-navy-dark text-white z-50 flex flex-col shadow-2xl transition-transform duration-300 -translate-x-full lg:translate-x-0" id="sidebar">
    <!-- Brand -->
    <a href="{{ route('dashboard') }}" class="p-5 flex items-center gap-3 border-b border-white/10 bg-black/15 hover:bg-black/25 transition-colors group cursor-pointer" title="Buka Dashboard Utama">
        <div class="w-10.5 h-10.5 bg-gradient-to-br from-white to-blue-100 rounded-xl flex items-center justify-center text-navy font-extrabold text-lg shadow-md shrink-0 transition-transform group-hover:scale-105">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <path d="M7 8h10"/>
                <path d="M7 12h10"/>
                <path d="M7 16h6"/>
            </svg>
        </div>
        <div class="overflow-hidden">
            <h2 class="text-[15px] font-bold tracking-wide text-white leading-tight group-hover:text-amber-200 transition-colors">E-JURNAL KELUHAN</h2>
            <p class="text-[11px] text-blue-200 font-medium tracking-wide mt-0.5">PT Bank Sulteng</p>
        </div>
    </a>

    <!-- Navigation Menu -->
    <div class="p-3.5 grow overflow-y-auto flex flex-col gap-1.5">
        <div class="text-[11px] uppercase tracking-wider text-slate-400 font-bold px-3 pt-3 pb-1">Menu Utama</div>

        <!-- Menu 1: Dashboard Utama -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13.5px] font-medium transition-all duration-200 {{ request()->routeIs('dashboard*') ? 'text-white bg-gradient-to-r from-brand-blue to-navy-light font-semibold shadow-lg shadow-brand-blue/30 relative before:content-[\'\'] before:absolute before:left-0 before:top-[15%] before:h-[70%] before:w-1 before:bg-brand-gold before:rounded-r' : 'text-slate-300 hover:text-white hover:bg-white/10 hover:translate-x-1' }}">
            <svg class="w-5 h-5 shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Menu 2: Form Input Jurnal -->
        <a href="{{ route('jurnal.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13.5px] font-medium transition-all duration-200 {{ request()->routeIs('jurnal.create') ? 'text-white bg-gradient-to-r from-brand-blue to-navy-light font-semibold shadow-lg shadow-brand-blue/30 relative before:content-[\'\'] before:absolute before:left-0 before:top-[15%] before:h-[70%] before:w-1 before:bg-brand-gold before:rounded-r' : 'text-slate-300 hover:text-white hover:bg-white/10 hover:translate-x-1' }}">
            <svg class="w-5 h-5 shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            <span>Input Jurnal Keluhan</span>
        </a>

        <!-- Menu 3: Data Keluhan & Pencarian -->
        <a href="{{ route('jurnal.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13.5px] font-medium transition-all duration-200 {{ request()->routeIs('jurnal.index') ? 'text-white bg-gradient-to-r from-brand-blue to-navy-light font-semibold shadow-lg shadow-brand-blue/30 relative before:content-[\'\'] before:absolute before:left-0 before:top-[15%] before:h-[70%] before:w-1 before:bg-brand-gold before:rounded-r' : 'text-slate-300 hover:text-white hover:bg-white/10 hover:translate-x-1' }}">
            <svg class="w-5 h-5 shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="3" y1="9" x2="21" y2="9"></line>
                <line x1="9" y1="21" x2="9" y2="9"></line>
            </svg>
            <span>Data Keluhan</span>
            <span class="ml-auto bg-white/20 px-2 py-0.5 rounded-full text-[11px] font-bold text-white" id="sidebarBadgeCount">{{ number_format(\App\Models\Jurnal::count(), 0, ',', '.') }}</span>
        </a>

        <!-- Menu 4: Monitoring Mesin ATM -->
        <a href="{{ route('atm.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13.5px] font-medium transition-all duration-200 {{ request()->routeIs('atm.*') ? 'text-white bg-gradient-to-r from-brand-blue to-navy-light font-semibold shadow-lg shadow-brand-blue/30 relative before:content-[\'\'] before:absolute before:left-0 before:top-[15%] before:h-[70%] before:w-1 before:bg-brand-gold before:rounded-r' : 'text-slate-300 hover:text-white hover:bg-white/10 hover:translate-x-1' }}">
            <svg class="w-5 h-5 shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                <line x1="6" y1="6" x2="6.01" y2="6"></line>
                <line x1="6" y1="18" x2="6.01" y2="18"></line>
            </svg>
            <span>Monitoring Mesin ATM</span>
        </a>

        <!-- Menu 5: Rekap Laporan Keluhan -->
        <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13.5px] font-medium transition-all duration-200 {{ request()->routeIs('laporan.*') ? 'text-white bg-gradient-to-r from-brand-blue to-navy-light font-semibold shadow-lg shadow-brand-blue/30 relative before:content-[\'\'] before:absolute before:left-0 before:top-[15%] before:h-[70%] before:w-1 before:bg-brand-gold before:rounded-r' : 'text-slate-300 hover:text-white hover:bg-white/10 hover:translate-x-1' }}">
            <svg class="w-5 h-5 shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg>
            <span>Rekap Laporan Keluhan</span>
        </a>
    </div>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-white/10 bg-black/25 flex items-center justify-between gap-2.5">
        <div class="flex items-center gap-2.5 overflow-hidden">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-gold to-amber-600 flex items-center justify-center text-white font-bold text-[13px] shrink-0 shadow-md">
                {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
            </div>
            <div class="overflow-hidden">
                <div class="text-[12.5px] font-semibold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</div>
                <div class="text-[11px] text-slate-400 truncate">&#64;{{ Auth::user()->username ?? 'admin' }} &bull; Admin</div>
            </div>
        </div>

        <button type="button" class="bg-rose-500/15 border border-rose-500/30 text-rose-300 p-2 rounded-lg hover:bg-rose-500/30 hover:text-white transition-colors cursor-pointer shrink-0" onclick="openLogoutModal()" title="Keluar / Logout">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </button>
    </div>
</aside>

<!-- MAIN CONTENT WRAPPER -->
<div class="lg:ml-[270px] min-h-screen flex flex-col transition-all duration-300 bg-slate-100">
    <!-- TOPBAR -->
    <header class="sticky top-0 h-[70px] bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8 z-30 shadow-xs">
        <div class="flex items-center gap-3.5">
            <button class="lg:hidden bg-transparent border border-slate-200 rounded-lg w-9.5 h-9.5 flex items-center justify-center cursor-pointer text-slate-600 hover:bg-slate-100 hover:text-navy transition-colors" id="sidebarToggle" title="Toggle Sidebar">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <div>
                <h1 class="text-[17.5px] font-bold text-navy leading-tight">@yield('page_title', 'Jurnal Keluhan Nasabah')</h1>
                <p class="text-xs text-slate-500 mt-0.5">@yield('page_subtitle', 'PT Bank Sulteng - Layanan Operasional & Pengaduan Nasabah')</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center gap-2 bg-slate-50 border border-slate-200 px-3.5 py-1.5 rounded-full text-[12.5px] font-semibold text-slate-700">
                <svg class="w-3.5 h-3.5 text-brand-blue" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span id="liveClock">Memuat jam...</span>
            </div>

            @yield('topbar_action')
        </div>
    </header>

    <!-- CONTENT BODY -->
    <main class="p-4 sm:p-6 lg:p-7.5 grow">
        <!-- Flash Alert Success -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl p-4 mb-5 text-[13.5px] flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <div>
                    <strong class="font-bold">Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Flash Alert Error -->
        @if (isset($errors) && $errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-900 rounded-xl p-4 mb-5 text-[13.5px] flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div>
                    <strong class="font-bold">Perhatian! Terdapat kesalahan input:</strong>
                    <ul class="list-disc pl-5 mt-1.5 space-y-1">
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
    <footer class="px-4 lg:px-8 py-4 bg-white border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-slate-500">
        <div>&copy; {{ date('Y') }} <strong>PT Bank Pembangunan Daerah Sulawesi Tengah</strong>. Hak Cipta Dilindungi.</div>
        <div>Sistem Pengelolaan Jurnal Keluhan Transaksi v1.3</div>
    </footer>
</div>

<!-- MODAL KONFIRMASI LOGOUT -->
<div class="modal-backdrop fixed inset-0 bg-slate-900/65 z-50 backdrop-blur-xs hidden items-center justify-center p-4" id="logoutModal">
    <div class="bg-white rounded-2xl w-full max-w-[420px] shadow-2xl overflow-hidden animate-modal-in">
        <div class="p-6 text-center">
            <div class="w-14 h-14 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-navy mb-2">Konfirmasi Logout</h3>
            <p class="text-[13.5px] text-slate-600 leading-relaxed">
                Apakah Anda benar-benar ingin keluar dari <strong>Sistem E-Jurnal Bank Sulteng</strong>?
            </p>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-center gap-3">
            <button type="button" class="px-5 py-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 font-semibold text-[13.5px] transition-colors cursor-pointer min-w-[100px]" onclick="closeLogoutModal()">
                Batal
            </button>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="px-5 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold text-[13.5px] shadow-md transition-colors cursor-pointer min-w-[110px]">
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
        const clockEl = document.getElementById('liveClock');
        if (clockEl) {
            clockEl.textContent = formatter.format(now) + ' WITA';
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Mobile Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');

    function toggleSidebar() {
        if(sidebar && overlay) {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }
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
            modal.classList.add('show');
        }
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if(modal) {
            modal.classList.remove('show');
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

