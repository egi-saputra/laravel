<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

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
            @include('layouts.navigation')

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
            class="fixed flex items-center justify-center w-12 h-12 text-white transition-all duration-300 rounded-full shadow-lg bottom-6 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 hover:shadow-2xl hover:scale-110"
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
        </script>

    </body>
</html>
