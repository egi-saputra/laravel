<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no">
    <head>
        <meta charset="utf-8">
        <meta name="google" content="notranslate">
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

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/dompurify@3.1.3/dist/purify.min.js"></script>

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        <!-- Import Heroicons -->
        <script src="https://unpkg.com/heroicons@2.0.16/24/solid/ellipsis-vertical.js"></script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- HotWire -->
        <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.3/dist/turbo.es2017-umd.js"></script>

        <!-- TinyMCE -->
        <script src="{{ asset('assets/tinymce/tinymce.min.js') }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

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
                    /* background-color: #f3f4f6; */
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
                    /* min-height: calc(100vh - 4rem); */
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
                        z-index: 40;
                        display: flex;
                        justify-content: space-around;
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
                        color: #063970;
                    }

                    .nav-icon.active i {
                        transform: scale(1.25);
                        color: #063970;
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

                .cursor-grabbing {
                    cursor: grabbing;
                    cursor: -webkit-grabbing;
                }

                @keyframes gradientMove {
                    0% { background-position: 0% 50%; }
                    50% { background-position: 100% 50%; }
                    100% { background-position: 0% 50%; }
                }

                .animate-gradient {
                    animation: gradientMove 8s ease infinite;
                }

                [x-cloak] { display: none !important; }
            </style>

    </head>
    <body class="font-sans antialiased bg-white md:bg-gray-100">
        <x-alert />

            @php
                $role = auth()->user()->role;
                $routes = [
                    'developer' => [
                        ['label' => 'Dashboard', 'route' => 'dev.dashboard'],
                        ['label' => 'Informasi Sekolah', 'route' => 'public.informasi_sekolah.index'],
                    ],
                    'admin' => [
                        ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
                        ['label' => 'Profil Sekolah', 'route' => 'admin.profil_sekolah'],
                    ],
                    'guru' => [
                        ['label' => 'Dashboard', 'label_mobile' => 'Dashboard', 'route' => 'guru.dashboard'],
                        ['label' => 'Informasi Sekolah', 'label_mobile' => 'Sekolah', 'route' => 'public.informasi_sekolah.index'],
                        // ['label' => 'Log Out', 'route' => 'logout', 'logout' => true],
                    ],
                    'staff' => [
                        ['label' => 'Dashboard', 'label_mobile' => 'Dashboard', 'route' => 'staff.dashboard'],
                        ['label' => 'Informasi Sekolah', 'label_mobile' => 'Sekolah', 'route' => 'public.informasi_sekolah.index'],
                        // ['label' => 'Log Out', 'route' => 'logout', 'logout' => true],
                    ],
                    'siswa' => [
                        ['label' => 'Dashboard', 'label_mobile' => 'Dashboard', 'route' => 'siswa.dashboard'],
                        ['label' => 'Informasi Sekolah', 'label_mobile' => 'Sekolah', 'route' => 'public.informasi_sekolah.index'],
                    ],
                    'user' => [
                        ['label' => 'Dashboard', 'route' => 'user.dashboard'],
                        ['label' => 'Kegiatan Saya', 'route' => 'user.activities'],
                        // ['label' => 'Log Out', 'route' => 'logout', 'logout' => true],
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
                                    <x-application-logo class="hidden w-12 h-12 text-gray-800 fill-current md:block dark:text-gray-200" />
                                </a>
                                <div
                                    class="px-4 text-[#063970] text-lg py-6 mx-auto font-bold sm:hidden max-w-7xl sm:px-6 lg:px-8">
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
                        <div class="flex items-center space-x-3 md:hidden sm:ms-6">
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

                                            <!-- Ikon dengan animasi rotasi -->
                                            <div class="transition-transform duration-300 ease-in-out ms-1"
                                                :class="openDropdown ? 'rotate-0' : '-rotate-90'">
                                                <!-- Ikon panah dengan animasi rotasi -->
                                                {{-- <svg class="fill-current w-7 h-7 bold md:w-4 md:h-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg> --}}

                                                <!-- Ikon gear dengan animasi rotasi -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700 md:w-4 md:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.518-.88 3.285.886 2.405 2.405a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.88 1.518-.887 3.285-2.405 2.405a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.518.88-3.285-.887-2.405-2.405a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.88-1.519.887-3.285 2.405-2.405.999.58 2.147.187 2.573-1.066z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                        </button>
                                    </div>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link href="#" @click.prevent="$dispatch('open-modal-upload-foto')">
                                        <i class="bi bi-person-circle me-2"></i>
                                        {{ __('Upload Foto') }}
                                    </x-dropdown-link>

                                    {{-- @if (Auth::user()->role === 'guru')
                                        <x-dropdown-link :href="route('profile.password')">
                                            <i class="bi bi-key-fill me-2"></i>
                                            {{ __('Password') }}
                                        </x-dropdown-link>
                                    @else --}}
                                        <x-dropdown-link :href="route('profile.edit')">
                                            <i class="bi bi-pencil-square me-2"></i>
                                            {{ __('Edit Profil') }}
                                        </x-dropdown-link>
                                    {{-- @endif --}}

                                    <x-dropdown-link :href="route('profile.password')">
                                        <i class="bi bi-key-fill me-2"></i>
                                        {{ __('Kata Sandi') }}
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
                        </div>

                        <div class="items-center hidden space-x-3 md:flex sm:ms-6">
                            <!-- Fullscreen Button (hanya tampil di layar sedang ke atas) -->
                            <button onclick="toggleFullscreen()"
                                class="items-center hidden px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-gray-200 rounded sm:inline-flex dark:text-gray-400 dark:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none">
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

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            @if (!request()->is('login'))
                    <x-nav-bot :role="$role" />
            @endif

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
        <button data-turbo="false" id="backToTop"
            class="fixed items-center justify-center hidden w-12 h-12 text-white transition-all duration-300 rounded-full shadow-lg md:flex md:bottom-6 bottom-16 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 hover:shadow-2xl hover:scale-110"
            title="Kembali ke atas !z-30">
            <i class="text-xl bi bi-arrow-up"></i>
        </button>

        <!-- Custom Script -->
        <script>
            function initBackToTop() {
                const backToTopBtn = document.getElementById("backToTop");
                if (!backToTopBtn) return;

                // Hapus event listener lama biar gak dobel
                const newBtn = backToTopBtn.cloneNode(true);
                backToTopBtn.parentNode.replaceChild(newBtn, backToTopBtn);

                // Tampilkan tombol saat scroll
                window.addEventListener("scroll", () => {
                    newBtn.classList.toggle("show", window.scrollY > 100);
                });

                // Klik tombol scroll ke atas
                newBtn.addEventListener("click", () => {
                    window.scrollTo({ top: 0, behavior: "smooth" });
                });
            }

            // Jalankan saat pertama kali halaman dimuat
            document.addEventListener('DOMContentLoaded', () => {
                initBackToTop();

                // Redirect saat back/forward
                window.addEventListener("pageshow", function(event) {
                    if (event.persisted || (window.performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
                        window.location.href = "/login";
                    }
                });

                // Simpan token Sanctum
                @if (session('sanctum_token'))
                    localStorage.setItem('sanctum_token', "{{ session('sanctum_token') }}");
                @endif
            });

            // Jalankan ulang setiap kali Turbo memuat halaman baru
            document.addEventListener('turbo:load', initBackToTop);
        </script>


        <!-- Menonaktifkan Loader Turbo Hotwire -->
        {{-- <script>
            // Matikan progress bar bawaan Turbo
            window.Turbo.setProgressBarDelay(999999);
        </script> --}}

        <!-- Script Loader Turbo Hotwire Mobile Only -->
        {{-- <script>
            document.addEventListener("turbo:before-visit", () => {
                const isMobile = window.matchMedia("(max-width: 768px)").matches;

                // Jika desktop, sembunyikan progress bar Turbo
                if (!isMobile) {
                Turbo.navigator.delegate.adapter.progressBar.hide();
                window.Turbo.setProgressBarDelay(999999); // tunda tampilnya agar tidak muncul sama sekali
                } else {
                // Jika mobile, tampilkan dengan delay default (100ms)
                window.Turbo.setProgressBarDelay(100);
                }
            });

            // Optional: pastikan setelah load pertama, bar disembunyikan lagi di desktop
            // document.addEventListener("turbo:load", () => {
            //     const isMobile = window.matchMedia("(max-width: 768px)").matches;
            //     if (!isMobile) {
            //     Turbo.navigator.delegate.adapter.progressBar.hide();
            //     }
            // });
        </script> --}}

        <!-- Script TinyMce Init -->
        <script>
            function initTinyMCE() {
                if (typeof tinymce === 'undefined') return;

                tinymce.remove(); // pastikan gak dobel

                tinymce.init({
                    selector: 'textarea.tinymce, textarea.tinymce-editor',
                    plugins: 'code table lists link image media fullscreen',
                    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media | code fullscreen',
                    height: 300,
                    menubar: false,
                    branding: false
                });
            }

            // Untuk pertama kali load
            document.addEventListener('DOMContentLoaded', initTinyMCE);

            // Untuk navigasi Turbo (maju/mundur antar halaman)
            document.addEventListener('turbo:load', initTinyMCE);
            document.addEventListener('turbo:render', initTinyMCE);

            // Sebelum halaman disimpan ke cache Turbo
            document.addEventListener('turbo:before-cache', function() {
                if (typeof tinymce !== 'undefined') tinymce.remove();
            });
        </script>

    </body>
</html>
