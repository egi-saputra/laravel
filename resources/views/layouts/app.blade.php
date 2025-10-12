<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
        <!-- Warna status bar Android -->
        <meta name="theme-color" content="#063970">
        <!-- Warna status bar Safari iOS -->
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Lottie Player -->
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        <!-- Import Heroicons -->
        <script src="https://unpkg.com/heroicons@2.0.16/24/solid/ellipsis-vertical.js"></script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/alpinejs" defer></script>

            <style>
                /* ================================== */
                /* Base Styles */
                /* ================================== */
                *, *::before, *::after {
                    box-sizing: border-box;
                }

                body {
                    margin: 0;
                    padding: 0;
                    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                    background-color: #f3f4f6;
                    color-scheme: only light;
                }

                /* ================================== */
                /* Navbar Sticky */
                /* ================================== */
                nav {
                    position: sticky; /* selalu di top */
                    top: 0;
                    left: 0;
                    width: 100%;
                    z-index: 1000;
                    background-color: #ffffff;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    padding-top: env(safe-area-inset-top);
                    transform: translateZ(0);
                }

                /* ================================== */
                /* Main & Footer */
                /* ================================== */
                main {
                    min-height: calc(100vh - 4rem); /* minimal tinggi untuk menutupi navbar */
                    padding: 1rem;
                    padding-bottom: calc(1rem + env(safe-area-inset-bottom));
                }

                footer {
                    padding-bottom: env(safe-area-inset-bottom);
                }

                /* ================================== */
                /* Mobile Bottom Navigation */
                /* ================================== */
                @media (max-width: 768px) {
                    #navhp {
                        position: fixed;
                        bottom: 0;
                        left: 0;
                        right: 0;
                        z-index: 50;
                        display: flex;
                        justify-around;
                        padding: 0.5rem 0;
                        background: rgba(255, 255, 255, 1);
                        backdrop-filter: blur(8px);
                        border-top: 1px solid #e5e7eb;
                        /* box-shadow: 0 -2px 6px rgba(0,0,0,0.1); */
                        padding-bottom: calc(0.5rem + env(safe-area-inset-bottom)); /* aman untuk iPhone */
                    }

                    .nav-icon {
                        flex: 1;
                        text-align: center;
                        color: #9ca3af;
                        font-size: 1.5rem;
                        transition: all 0.25s ease;
                    }

                    .nav-icon i {
                        transition: transform 0.25s ease, color 0.25s ease;
                    }

                    .nav-icon:hover i {
                        transform: scale(1.15);
                    }

                    .nav-icon.active {
                        color: #2563eb;
                    }

                    .nav-icon.active i {
                        transform: scale(1.25);
                        color: #2563eb;
                    }
                }

                /* Default desktop: sembunyikan #navhp */
                @media (min-width: 769px) {
                    #navhp {
                        display: none;
                    }
                }
            </style>

            <style>
                #minimalLoader {
                    position: fixed;
                    top: 0; left: 0;
                    width: 100%; height: 100%;
                    background-color: #ffffff;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    z-index: 9999;
                    transition: opacity 0.3s ease;
                }

                #minimalLoader.hidden {
                    opacity: 0;
                    pointer-events: none;
                }

                .loader-text {
                    font-weight: bold;
                    font-size: 1.4rem;
                    background: linear-gradient(90deg, #2563eb, #3b82f6, #2563eb);
                    background-clip: text;
                    -webkit-background-clip: text;
                    color: transparent;
                    animation: shine 2s linear infinite;
                    margin-bottom: 20px;
                }

                @keyframes shine {
                    0% { background-position: 200% center; }
                    100% { background-position: -200% center; }
                }

                .loader-bar-wrapper {
                    overflow: hidden;
                    border-radius: 9999px;
                    background-color: #e5e7eb;
                    width: 300px;
                    height: 14px;
                }

                .loader-bar {
                    width: 0%;
                    height: 100%;
                    background: linear-gradient(90deg, #2563eb, #3b82f6);
                    border-radius: 9999px;
                    transition: width 1.5s cubic-bezier(0.77, 0, 0.175, 1); /* smooth live feel */
                }
            </style>

            <style>
                .swal2-popup {
                    @apply mx-6 sm:mx-6 md:mx-auto p-6 rounded-xl;
                }

                .swal2-title {
                    @apply text-lg font-semibold;
                }

                .swal2-content {
                    @apply text-sm text-gray-700;
                }
                #backToTop {
                    opacity: 0;
                    transform: scale(0.8);
                    pointer-events: none;
                    transition: all 0.3s ease;
                }

                #backToTop.show {
                    opacity: 1;
                    transform: scale(1);
                    pointer-events: auto;
                }

                [x-cloak] { display: none !important; }
            </style>

        <x-head-tinymce.tinymce-config/>

    </head>
    <body class="font-sans antialiased bg-gray-100">
        <x-alert />

        <div class="min-h-screen bg-gray-100">
            @php
                $role = auth()->user()->role;
                $routes = [
                    'developer' => [
                        ['label' => 'Dashboard', 'route' => 'dev.dashboard'],
                        ['label' => 'Informasi Sekolah', 'route' => 'public.informasi_sekolah.index'],
                        ['label' => 'Log Out', 'route' => 'logout', 'logout' => true], // tambah logout
                    ],
                    'admin' => [
                        ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
                        ['label' => 'Profil Sekolah', 'route' => 'admin.profil_sekolah'],
                        ['label' => 'Log Out', 'route' => 'logout', 'logout' => true],
                    ],
                    'guru' => [
                        ['label' => 'Dashboard', 'label_mobile' => 'Dashboard', 'route' => 'guru.dashboard'],
                        ['label' => 'Informasi Sekolah', 'label_mobile' => 'Sekolah', 'route' => 'public.informasi_sekolah.index'],
                        ['label' => 'Log Out', 'route' => 'logout', 'logout' => true],
                    ],
                    'staff' => [
                        ['label' => 'Dashboard', 'label_mobile' => 'Dashboard', 'route' => 'staff.dashboard'],
                        ['label' => 'Informasi Sekolah', 'label_mobile' => 'Sekolah', 'route' => 'public.informasi_sekolah.index'],
                        ['label' => 'Log Out', 'route' => 'logout', 'logout' => true],
                    ],
                    'siswa' => [
                        ['label' => 'Dashboard', 'label_mobile' => 'Dashboard', 'route' => 'siswa.dashboard'],
                        ['label' => 'Informasi Sekolah', 'label_mobile' => 'Sekolah', 'route' => 'public.informasi_sekolah.index'],
                    ],
                    'user' => [
                        ['label' => 'Dashboard', 'route' => 'user.dashboard'],
                        ['label' => 'Kegiatan Saya', 'route' => 'user.activities'],
                        ['label' => 'Log Out', 'route' => 'logout', 'logout' => true],
                    ],
                ];
            @endphp

            <nav x-data="{ open: false }"
                class="sticky top-0 z-30 block bg-white border-b border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 md:static">

                <!-- Primary Navigation Menu -->
                <div class="w-full px-0 sm:px-6 lg:px-8">
                    <div class="flex justify-between md:h-16 h-14">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex items-center shrink-0">
                                <a href="{{ route($routes[$role][0]['route']) }}">
                                    <x-application-logo class="hidden md:block w-12 h-12 text-gray-800 fill-current dark:text-gray-200" />
                                </a>
                                <div
                                    class="px-4 text-[#063970] text-lg py-6 mx-auto font-semibold sm:hidden max-w-7xl sm:px-6 lg:px-8">
                                    {{ $profil?->nama_sekolah ?? 'Nama Sekolah Default' }}
                                </div>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 border-b border-gray-100 sm:-my-px sm:ms-4 sm:flex dark:bg-gray-800 dark:border-gray-700">
                                @foreach ($routes[$role] as $menu)
                                    <x-nav-link :href="route($menu['route'])" :active="request()->routeIs($menu['route'])">
                                        {{ __($menu['label']) }}
                                    </x-nav-link>
                                @endforeach
                            </div>
                        </div>

                        <!-- Settings Dropdown + Fullscreen -->
                        <div class="flex items-center space-x-3 sm:ms-6">
                            <!-- Dropdown User -->
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <!-- x-data di sini untuk kontrol rotasi panah -->
                                    <div x-data="{ openDropdown: false }" @click.away="openDropdown = false">
                                        <button
                                            @click="openDropdown = !openDropdown"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">

                                            <!-- Nama user hanya tampil di layar sedang ke atas -->
                                            <div class="hidden sm:block">{{ Auth::user()->name }}</div>

                                            <!-- Ikon panah dengan animasi rotasi -->
                                            <div class="ms-1 transition-transform duration-300 ease-in-out"
                                                :class="openDropdown ? 'rotate-0' : '-rotate-90'">
                                                <svg class="w-6 h-6 md:w-4 md:h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </div>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link href="#" @click.prevent="$dispatch('open-modal-upload-foto')">
                                        <i class="bi bi-person-circle me-2"></i>
                                        {{ __('Photo Profile') }}
                                    </x-dropdown-link>

                                    @if (Auth::user()->role === 'guru')
                                        <x-dropdown-link :href="route('profile.password')">
                                            <i class="bi bi-key-fill me-2"></i>
                                            {{ __('Password') }}
                                        </x-dropdown-link>
                                    @else
                                        <x-dropdown-link :href="route('profile.edit')">
                                            <i class="bi bi-pencil-square me-2"></i>
                                            {{ __('Edit Profile') }}
                                        </x-dropdown-link>
                                    @endif

                                    <x-dropdown-link :href="route('profile.edit')">
                                        <i class="bi bi-key-fill me-2"></i>
                                        {{ __('Password') }}
                                    </x-dropdown-link>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i>
                                            {{ __('Logout') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>

                            <!-- Fullscreen Button (hanya tampil di layar sedang ke atas) -->
                            <button onclick="toggleFullscreen()"
                                class="hidden sm:inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-gray-200 rounded dark:text-gray-400 dark:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 8V4h4M4 16v4h4m8-16h4v4m-4 12h4v-4" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        @foreach ($routes[$role] as $menu)
                            <x-responsive-nav-link :href="route($menu['route'])" :active="request()->routeIs($menu['route'])">
                                {{ __($menu['label_mobile'] ?? $menu['label']) }}
                            </x-responsive-nav-link>
                        @endforeach
                    </div>
                </div>
            </nav>

            @if(!session('alert'))
                <div id="minimalLoader" class="md:hidden block">
                    <div class="loader-text">Loading...</div>
                    <div class="loader-bar-wrapper">
                        <div class="loader-bar" id="loaderBar"></div>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Bottom Navigation (Mobile Only - Icon + Text) -->
            <div id="navhp" class="fixed bottom-0 left-0 right-0 z-50 flex justify-around py-2 text-xs bg-white border-t shadow md:hidden">

                <!-- Home/Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center nav-icon {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                    <i class="text-lg fas fa-chart-line"></i>
                    <small class="text-xs font-semibold">Beranda</small>
                </a>

                @if(auth()->user()->role === 'staff')
                    <!-- Riwayat Presensi untuk Staff -->
                    <a href="{{ route('staff.riwayat_presensi.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('staff.riwayat_presensi.*') ? 'active' : '' }}">
                        <i class="text-lg fas fa-calendar-check"></i>
                        <small class="text-xs font-semibold">Presensi</small>
                    </a>
                @else
                    <!-- Siswa untuk semua selain staff -->
                    <a href="{{ route('public.daftar_siswa.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('public.daftar_siswa.*') ? 'active' : '' }}">
                        <i class="text-lg fas fa-user-graduate"></i>
                        <small class="text-xs font-semibold">Siswa</small>
                    </a>
                @endif

                <!-- Informasi Sekolah -->
                <a href="{{ route('public.informasi_sekolah.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('public.informasi_sekolah.index') ? 'active' : '' }}">
                    <i class="text-lg fas fa-school"></i>
                    <small class="text-xs font-semibold">Sekolah</small>
                </a>

                @if(auth()->user()->role === 'guru')
                    <!-- Materi -->
                    <a href="{{ route('guru.materi.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('guru.materi.*') ? 'active' : '' }}">
                        <i class="text-lg fas fa-book"></i>
                        <small class="text-xs font-semibold">Materi</small>
                    </a>

                    <!-- Tugas -->
                    <a href="{{ route('guru.tugas_siswa.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('guru.tugas_siswa.*') ? 'active' : '' }}">
                        <i class="text-lg fas fa-tasks"></i>
                        <small class="text-xs font-semibold">Tugas</small>
                    </a>
                @elseif(auth()->user()->role === 'staff')
                    <!-- Rekap Honor Guru -->
                    <a href="{{ route('staff.rekap_honor_guru.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('staff.rekap_honor_guru.*') ? 'active' : '' }}">
                        <i class="text-lg fas fa-user-tie"></i>
                        <small class="text-xs font-semibold">Honor Guru</small>
                    </a>

                    <!-- Rekap Honor Staff -->
                    <a href="{{ route('staff.rekap_honor_staff.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('staff.rekap_honor_staff.*') ? 'active' : '' }}">
                        <i class="text-lg fas fa-users"></i>
                        <small class="text-xs font-semibold">Honor Staff</small>
                    </a>
                @else
                    <!-- Siswa: Materi -->
                    <a href="{{ route('siswa.materi.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
                        <i class="text-lg fas fa-book"></i>
                        <small class="text-xs font-semibold">Materi</small>
                    </a>

                    <!-- Siswa: Tugas -->
                    <a href="{{ route('siswa.tugas.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
                        <i class="text-lg fas fa-tasks"></i>
                        <small class="text-xs font-semibold">Tugas</small>
                    </a>
                @endif

            </div>
        </div>

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- jsPDF + AutoTable -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

        @stack('scripts')

        <!-- MODAL -->
        <x-modal-upload-foto />

        <!-- Back to Top -->
        <button id="backToTop"
            class="fixed items-center justify-center hidden w-12 h-12 text-white transition-all duration-300 rounded-full shadow-lg md:flex md:bottom-6 bottom-16 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 hover:shadow-2xl hover:scale-110"
            title="Kembali ke atas">
            <i class="text-xl bi bi-arrow-up"></i>
        </button>

            <!-- Script Fullscreen -->
            <script>
                function toggleFullscreen() {
                    let elem = document.documentElement;
                    if (!document.fullscreenElement) {
                        if (elem.requestFullscreen) {
                            elem.requestFullscreen();
                        } else if (elem.mozRequestFullScreen) {
                            elem.mozRequestFullScreen();
                        } else if (elem.webkitRequestFullscreen) {
                            elem.webkitRequestFullscreen();
                        } else if (elem.msRequestFullscreen) {
                            elem.msRequestFullscreen();
                        }
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        } else if (document.mozCancelFullScreen) {
                            document.mozCancelFullScreen();
                        } else if (document.webkitExitFullscreen) {
                            document.webkitExitFullscreen();
                        } else if (document.msExitFullscreen) {
                            document.msExitFullscreen();
                        }
                    }
                }
            </script>

        <script>
            const backToTopBtn = document.getElementById("backToTop");

            window.addEventListener("scroll", () => {
                if (window.scrollY > 100) {
                    backToTopBtn.classList.add("show");
                } else {
                    backToTopBtn.classList.remove("show");
                }
            });

            backToTopBtn.addEventListener("click", () => {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });

            window.addEventListener("pageshow", function(event) {
                if (event.persisted || window.performance.getEntriesByType("navigation")[0].type === "back_forward") {
                    // reload halaman saat user klik back
                    window.location.href = "/login"; // redirect ke login
                }
            });

            // Simple Bar Loader Live
            document.addEventListener('DOMContentLoaded', function () {
                const loader = document.getElementById('minimalLoader');
                const loaderBar = document.getElementById('loaderBar');

                // Cek apakah ada SweetAlert
                const hasSweetAlert = @json(session('alert') ? true : false);

                if (hasSweetAlert) {
                    // Hilangkan loader sepenuhnya
                    if (loader) loader.remove();
                    if (loaderBar) loaderBar.remove();

                    Swal.fire({
                        icon: '{{ session('alert.type') }}',
                        title: '{{ session('alert.title') ?? ucfirst(session('alert.type')) }}',
                        text: '{{ session('alert.message') }}',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6'
                    });
                } else {
                    // Normal loader
                    setTimeout(() => {
                        loaderBar.style.width = '100%';
                    }, 50);

                    setTimeout(() => {
                        loader.classList.add('hidden');
                    }, 1600);
                }
            });
        </script>

        @if (session('sanctum_token'))
            <script>
                // Simpan token Sanctum dari Laravel session ke localStorage browser
                localStorage.setItem('sanctum_token', "{{ session('sanctum_token') }}");
            </script>
        @endif
    </body>
</html>
