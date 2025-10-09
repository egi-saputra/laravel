<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                /* Safe Area & Base Styles */
                /* ================================== */
                body {
                    margin: 0;
                    box-sizing: border-box;
                }

                /* Hanya apply safe-area padding untuk device yang mendukung */
                @supports (padding-top: env(safe-area-inset-top)) {
                    @media (max-width: 768px) {
                        body {
                            padding-top: env(safe-area-inset-top);
                            padding-bottom: env(safe-area-inset-bottom);
                        }
                    }
                }

                /* Navbar sticky/top-0 */
                nav {
                    position: sticky;
                    top: 0;
                    left: 0;
                    width: 100%;
                    z-index: 1000;
                    padding-top: env(safe-area-inset-top); /* hanya navbar ikut safe-area */
                    background-color: #ffffff;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }

                /* ================================== */
                /* Mobile Bottom Navigation & Main */
                /* ================================== */
                @media (max-width: 768px) {

                    /* Navbar bawah (mobile) */
                    #navhp {
                        position: fixed;
                        bottom: 0;
                        left: 0;
                        right: 0;
                        z-index: 50;
                        display: flex;
                        justify-around;
                        padding: 0.5rem 0;
                        background: rgba(255, 255, 255, 0.95);
                        backdrop-filter: blur(8px);
                        border-top: 1px solid #e5e7eb;
                        box-shadow: 0 -2px 6px rgba(0,0,0,0.1);
                    }

                    /* Konten utama aman dari navbar bawah */
                    main {
                        margin-top: 0; /* hapus jarak palsu di responsive */
                        padding-bottom: calc(4rem + env(safe-area-inset-bottom));
                        padding: 1rem;
                        box-sizing: border-box;
                    }

                    /* Footer aman dari tombol navigasi di iPhone */
                    footer {
                        margin-bottom: env(safe-area-inset-bottom);
                    }

                    /* Icon navbar */
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
            </style>

            <style>
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
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
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
            ['label' => 'Log Out', 'route' => 'logout', 'logout' => true],
        ],
        'user' => [
            ['label' => 'Dashboard', 'route' => 'user.dashboard'],
            ['label' => 'Kegiatan Saya', 'route' => 'user.activities'],
            ['label' => 'Log Out', 'route' => 'logout', 'logout' => true],
        ],
    ];
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-20 bg-white border-b border-gray-100 shadow-sm dark:bg-gray-800 dark:border-gray-700 md:static">
    <!-- Primary Navigation Menu -->
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route($routes[$role][0]['route']) }}">
                        <x-application-logo class="block w-auto text-gray-800 fill-current h-9 dark:text-gray-200" />
                    </a>
                    <div class="px-4 py-6 mx-auto font-semibold sm:hidden max-w-7xl sm:px-6 lg:px-8">
                        {{ $profil?->nama_sekolah ?? 'Nama Sekolah Default' }}
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 border-b border-gray-100 sm:-my-px sm:ms-10 sm:flex dark:bg-gray-800 dark:border-gray-700">
                    {{-- @foreach ($routes[$role] as $menu)
                        <x-nav-link :href="route($menu['route'])" :active="request()->routeIs($menu['route'])">
                            {{ __($menu['label']) }}
                        </x-nav-link>
                    @endforeach --}}
                    @foreach ($routes[$role] as $menu)
                        @if (isset($menu['logout']) && $menu['logout'] === true)
                            {{-- Logout: hanya tampil di mobile --}}
                            <form method="POST" action="{{ route('logout') }}" class="sm:hidden">
                                @csrf
                                <x-nav-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __($menu['label']) }}
                                </x-nav-link>
                            </form>
                        @else
                            {{-- Normal menu --}}
                            <x-nav-link :href="route($menu['route'])" :active="request()->routeIs($menu['route'])">
                                {{ __($menu['label']) }}
                            </x-nav-link>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Settings Dropdown + Fullscreen -->
            <div class="hidden space-x-3 sm:flex sm:items-center sm:ms-6">


                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if (Auth::user()->role === 'guru')
                            <x-dropdown-link :href="route('profile.password')">
                                {{ __('Password') }}
                            </x-dropdown-link>
                        @else
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                <!-- Fullscreen Button -->
                <button onclick="toggleFullscreen()"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-gray-200 rounded dark:text-gray-400 dark:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none">
                    <!-- Heroicon: fullscreen -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 8V4h4M4 16v4h4m8-16h4v4m-4 12h4v-4" />
                        </svg>
                </button>
            </div>

            <!-- Hamburger (Mobile Menu Button) -->
            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            {{-- @foreach ($routes[$role] as $menu)
                <x-responsive-nav-link :href="route($menu['route'])" :active="request()->routeIs($menu['route'])">
                    {{ __($menu['label']) }}
                </x-responsive-nav-link>
            @endforeach --}}
            {{-- @foreach ($routes[$role] as $menu)
                @if (isset($menu['logout']) && $menu['logout'] === true)
                    <form method="POST" action="{{ route('logout') }}" class="sm:hidden">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __($menu['label']) }}
                        </x-responsive-nav-link>
                    </form>
                @else
                    <x-responsive-nav-link :href="route($menu['route'])" :active="request()->routeIs($menu['route'])">
                        {{ __($menu['label']) }}
                    </x-responsive-nav-link>
                @endif
            @endforeach --}}
            @foreach ($routes[$role] as $menu)
                @if (isset($menu['logout']) && $menu['logout'] === true)
                    <form method="POST" action="{{ route('logout') }}" class="sm:hidden">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __($menu['label']) }}
                        </x-responsive-nav-link>
                    </form>
                @else
                    <x-responsive-nav-link :href="route($menu['route'])" :active="request()->routeIs($menu['route'])">
                        {{ __($menu['label_mobile'] ?? $menu['label']) }}
                    </x-responsive-nav-link>
                @endif
            @endforeach
        </div>

        <!-- Responsive Settings Options -->
        {{-- <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="mt-3 space-y-1">
                @if (Auth::user()->role !== 'guru')
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div> --}}
    </div>
</nav>

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


            <!-- Page Heading -->
            {{-- Untuk label page di bawah navbar utama --}}
            {{-- @if (isset($header))
                <header class="bg-white shadow dark:bg-gray-800">
                    <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                    <div class="px-4 py-6 mx-auto font-bold max-w-7xl sm:px-6 lg:px-8">
                        {{ $profil->nama_sekolah }}
                    </div>
                </header>
            @endif --}}

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- jsPDF + AutoTable -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

        @stack('scripts')

        <!-- Back to Top -->
        <button id="backToTop"
            class="fixed flex items-center justify-center w-12 h-12 text-white transition-all duration-300 rounded-full shadow-lg md:bottom-6 bottom-16 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 hover:shadow-2xl hover:scale-110"
            title="Kembali ke atas">
            <i class="text-xl bi bi-arrow-up"></i>
        </button>

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
        </script>

    </body>
</html>
