<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Presensi') | Presensi Mahasiswa</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tailwind CSS Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #10b981;
        }

        /* Page transition animation (Filament Laravel style) */
        @keyframes pageFadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .page-transition {
            animation: pageFadeIn 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        /* Filament form focus styles */
        input:focus, select:focus, textarea:focus {
            --tw-ring-color: #10b981 !important;
            border-color: #10b981 !important;
        }
    </style>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Anti Back-Button Cache Reload Protection -->
    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col md:flex-row antialiased">

    <!-- Mobile Header -->
    <header class="bg-slate-900 text-white p-4 flex justify-between items-center md:hidden shadow-md">
        <div class="flex items-center gap-3">
            <div class="bg-gradient-to-tr from-brand-500 to-indigo-600 p-2.5 rounded-xl text-white shadow-lg">
                <i class="fa-solid fa-graduation-cap fa-lg"></i>
            </div>
            <span class="font-bold text-lg tracking-wide bg-gradient-to-r from-white to-slate-300 bg-clip-text text-transparent">Absensi Mhs</span>
        </div>
        <button id="mobile-menu-btn" class="p-2 text-slate-300 hover:text-white focus:outline-none transition-colors">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </header>

    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-950 text-slate-300 flex flex-col justify-between border-r border-slate-900 shadow-2xl transition-all duration-300 transform -translate-x-full md:translate-x-0 md:static md:h-screen shrink-0">
        <div class="flex flex-col flex-1 min-h-0 overflow-y-auto">
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-slate-900/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-tr from-brand-500 to-indigo-500 p-2.5 rounded-xl text-white shadow-lg shadow-brand-500/20">
                        <i class="fa-solid fa-graduation-cap fa-lg"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg tracking-wide text-white leading-tight">Presensi Kampus</h1>
                        <span class="text-xs text-brand-400 font-medium">
                            @if(Auth::check())
                                @if(Auth::user()->role === 'admin')
                                    Admin Sistem
                                @elseif(Auth::user()->role === 'dosen')
                                    Dosen Pengampu
                                @else
                                    Portal Mahasiswa
                                @endif
                            @endif
                        </span>
                    </div>
                </div>
                <button id="close-sidebar-btn" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-900 md:hidden focus:outline-none">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="p-6 space-y-1.5">
                @if(Auth::check())
                    @if(Auth::user()->role === 'admin')
                        <!-- Admin Navigation Options -->
                        <p class="text-xs font-semibold text-slate-500 tracking-wider uppercase mb-3 pl-3 font-sans">Menu Admin</p>
                        
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ Request::is('/') ? 'bg-brand-600 text-white font-medium shadow-lg shadow-brand-600/20' : 'hover:bg-slate-900 hover:text-white' }}">
                            <i class="fa-solid fa-chart-pie text-lg transition-transform group-hover:scale-110 {{ Request::is('/') ? 'text-white' : 'text-slate-400 group-hover:text-brand-400' }}"></i>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('pengguna.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ Request::is('pengguna*') ? 'bg-brand-600 text-white font-medium shadow-lg shadow-brand-600/20' : 'hover:bg-slate-900 hover:text-white' }}">
                            <i class="fa-solid fa-users text-lg transition-transform group-hover:scale-110 {{ Request::is('pengguna*') ? 'text-white' : 'text-slate-400 group-hover:text-brand-400' }}"></i>
                            <span>Kelola Pengguna</span>
                        </a>

                        <a href="{{ route('dosen.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ Request::is('dosen*') ? 'bg-brand-600 text-white font-medium shadow-lg shadow-brand-600/20' : 'hover:bg-slate-900 hover:text-white' }}">
                            <i class="fa-solid fa-user-tie text-lg transition-transform group-hover:scale-110 {{ Request::is('dosen*') ? 'text-white' : 'text-slate-400 group-hover:text-brand-400' }}"></i>
                            <span>Data Dosen</span>
                        </a>
                        
                        <a href="{{ route('mahasiswa.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ Request::is('mahasiswa*') ? 'bg-brand-600 text-white font-medium shadow-lg shadow-brand-600/20' : 'hover:bg-slate-900 hover:text-white' }}">
                            <i class="fa-solid fa-user-graduate text-lg transition-transform group-hover:scale-110 {{ Request::is('mahasiswa*') ? 'text-white' : 'text-slate-400 group-hover:text-brand-400' }}"></i>
                            <span>Data Mahasiswa</span>
                        </a>

                        <a href="{{ route('kelas-kuliah.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ Request::is('kelas-kuliah*') ? 'bg-brand-600 text-white font-medium shadow-lg shadow-brand-600/20' : 'hover:bg-slate-900 hover:text-white' }}">
                            <i class="fa-solid fa-school text-lg transition-transform group-hover:scale-110 {{ Request::is('kelas-kuliah*') ? 'text-white' : 'text-slate-400 group-hover:text-brand-400' }}"></i>
                            <span>Kelas Kuliah</span>
                        </a>

                        <p class="text-xs font-semibold text-slate-500 tracking-wider uppercase pt-4 mb-3 pl-3 font-sans">Laporan</p>

                        <a href="{{ route('presensi.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ Request::is('presensi') || Request::is('presensi/*/edit') ? 'bg-brand-600 text-white font-medium shadow-lg shadow-brand-600/20' : 'hover:bg-slate-900 hover:text-white' }}">
                            <i class="fa-solid fa-calendar-check text-lg transition-transform group-hover:scale-110 {{ Request::is('presensi') || Request::is('presensi/*/edit') ? 'text-white' : 'text-slate-400 group-hover:text-brand-400' }}"></i>
                            <span>Riwayat Presensi</span>
                        </a>

                        <a href="{{ route('presensi.rekap') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ Request::is('rekap-presensi') ? 'bg-brand-600 text-white font-medium shadow-lg shadow-brand-600/20' : 'hover:bg-slate-900 hover:text-white' }}">
                            <i class="fa-solid fa-file-invoice text-lg transition-transform group-hover:scale-110 {{ Request::is('rekap-presensi') ? 'text-white' : 'text-slate-400 group-hover:text-brand-400' }}"></i>
                            <span>Rekap Absensi</span>
                        </a>

                    @elseif(Auth::user()->role === 'dosen')
                        <!-- Dosen Navigation Options -->
                        <p class="text-xs font-semibold text-slate-500 tracking-wider uppercase mb-3 pl-3 font-sans">Menu Dosen</p>
                        
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ Request::is('/') ? 'bg-brand-600 text-white font-medium shadow-lg shadow-brand-600/20' : 'hover:bg-slate-900 hover:text-white' }}">
                            <i class="fa-solid fa-chart-pie text-lg transition-transform group-hover:scale-110 {{ Request::is('/') ? 'text-white' : 'text-slate-400 group-hover:text-brand-400' }}"></i>
                            <span>Dashboard</span>
                        </a>

                        <p class="text-xs font-semibold text-slate-500 tracking-wider uppercase pt-4 mb-3 pl-3 font-sans">Kehadiran</p>

                        <a href="{{ route('presensi.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ Request::is('presensi') || Request::is('presensi/*/edit') ? 'bg-brand-600 text-white font-medium shadow-lg shadow-brand-600/20' : 'hover:bg-slate-900 hover:text-white' }}">
                            <i class="fa-solid fa-calendar-check text-lg transition-transform group-hover:scale-110 {{ Request::is('presensi') || Request::is('presensi/*/edit') ? 'text-white' : 'text-slate-400 group-hover:text-brand-400' }}"></i>
                            <span>Riwayat Presensi</span>
                        </a>

                        <a href="{{ route('presensi.rekap') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ Request::is('rekap-presensi') ? 'bg-brand-600 text-white font-medium shadow-lg shadow-brand-600/20' : 'hover:bg-slate-900 hover:text-white' }}">
                            <i class="fa-solid fa-file-invoice text-lg transition-transform group-hover:scale-110 {{ Request::is('rekap-presensi') ? 'text-white' : 'text-slate-400 group-hover:text-brand-400' }}"></i>
                            <span>Rekap Absensi</span>
                        </a>
                    @elseif(Auth::user()->role === 'mahasiswa')
                        <!-- Mahasiswa Navigation Options -->
                        <p class="text-xs font-semibold text-slate-500 tracking-wider uppercase mb-3 pl-3 font-sans">Portal Mahasiswa</p>
                        
                        <a href="{{ route('mahasiswa.portal') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ Request::is('mahasiswa/portal') ? 'bg-brand-600 text-white font-medium shadow-lg shadow-brand-600/20' : 'hover:bg-slate-900 hover:text-white' }}">
                            <i class="fa-solid fa-clipboard-user text-lg transition-transform group-hover:scale-110 {{ Request::is('mahasiswa/portal') ? 'text-white' : 'text-slate-400 group-hover:text-brand-400' }}"></i>
                            <span>Absen Mandiri GPS</span>
                        </a>
                    @endif
                @endif
            </nav>
        </div>

        <!-- Sidebar Footer Info & Logout Actions -->
        <div class="p-5 border-t border-slate-900/60 bg-slate-950/40 space-y-4 shrink-0">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-8.5 h-8.5 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 font-semibold border border-slate-700 shrink-0">
                        {{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 2)) : 'US' }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-semibold text-white truncate">{{ Auth::check() ? Auth::user()->name : 'User' }}</p>
                        <p class="text-[10px] text-slate-500 truncate font-medium">
                            @if(Auth::check())
                                @if(Auth::user()->role === 'admin')
                                    Super Admin
                                @elseif(Auth::user()->role === 'dosen')
                                    Dosen Pengampu
                                @else
                                    Mahasiswa Aktif
                                @endif
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Logout Button Link -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-rose-400 hover:text-white flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl border border-rose-500/10 hover:bg-rose-500/20 hover:border-rose-500/20 transition-all font-bold text-xs">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Keluar (Logout)</span>
            </a>
        </div>
    </aside>

    <!-- Overlay behind sidebar on mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-950/60 z-40 hidden md:hidden transition-opacity"></div>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-w-0 max-h-screen overflow-y-auto">
        <!-- Top Navbar -->
        <header class="bg-white border-b border-slate-100 px-6 py-4 flex items-center justify-between hidden md:flex shrink-0">
            <div>
                <p class="text-xs font-medium text-slate-400" id="current-day-name">Memuat hari...</p>
                <h2 class="text-sm font-semibold text-slate-600" id="current-date-time">Memuat tanggal & waktu...</h2>
            </div>
            <div class="flex items-center gap-4">
                <div class="relative">
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white"></span>
                    <button class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors">
                        <i class="fa-regular fa-bell text-lg"></i>
                    </button>
                </div>
                <div class="h-6 w-[1px] bg-slate-200"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-xs font-semibold text-slate-800">{{ Auth::check() ? Auth::user()->name : '' }}</p>
                        <span class="text-[10px] bg-brand-50 text-brand-600 px-2 py-0.5 rounded-full font-medium border border-brand-100 uppercase tracking-wide">
                            {{ Auth::check() ? Auth::user()->role : '' }}
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Wrapper -->
        <div class="flex-1 p-6 md:p-8 page-transition">
            <!-- Main Page Content -->
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="bg-slate-50 border-t border-slate-200 py-5 px-8 mt-auto shrink-0">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 bg-gradient-to-tr from-brand-500 to-emerald-400 rounded-lg flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-graduation-cap text-white text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-700 leading-tight">Sistem Presensi Mahasiswa</p>
                        <p class="text-[10px] text-slate-400 font-medium">Berbasis GPS Geofencing</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-[11px] text-slate-400 font-medium">&copy; {{ date('Y') }} Universitas Indonesia. Hak Cipta Dilindungi.</span>
                    <span class="bg-brand-50 text-brand-600 px-2 py-0.5 rounded-full text-[10px] font-bold border border-brand-100">v1.0</span>
                </div>
            </div>
        </footer>
    </main>

    <!-- Sidebar Responsive Control Script -->
    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if(mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            });
        }

        if(closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }

        if(overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }

        // Live Clock
        function updateClock() {
            const timeEl = document.getElementById('current-date-time');
            const dayEl = document.getElementById('current-day-name');
            if(!timeEl || !dayEl) return;

            const now = new Date();
            const optionsDate = { year: 'numeric', month: 'long', day: 'numeric' };
            const optionsDay = { weekday: 'long' };
            
            const hrs = String(now.getHours()).padStart(2, '0');
            const mins = String(now.getMinutes()).padStart(2, '0');
            const secs = String(now.getSeconds()).padStart(2, '0');
            
            dayEl.textContent = now.toLocaleDateString('id-ID', optionsDay);
            timeEl.textContent = `${now.toLocaleDateString('id-ID', optionsDate)} | ${hrs}:${mins}:${secs}`;
        }
        
        updateClock();
        setInterval(updateClock, 1000);

        // SweetAlert2 Session Messages Trigger
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#10b981',
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444',
            });
        @endif
        @if(session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: "{{ session('info') }}",
                confirmButtonColor: '#3b82f6',
            });
        @endif
        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: "{{ session('warning') }}",
                confirmButtonColor: '#f59e0b',
            });
        @endif

        // Global Delete Confirmation dialogs for SweetAlert2
        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('[data-confirm-delete]');
            if (deleteBtn) {
                e.preventDefault();
                const form = deleteBtn.closest('form');
                const message = deleteBtn.getAttribute('data-confirm-delete') || 'Apakah Anda yakin ingin menghapus data ini?';

                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
