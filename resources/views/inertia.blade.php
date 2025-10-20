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

    <!-- Warna status bar -->
    <meta name="theme-color" content="#063970">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Lottie Player -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Vite + Inertia -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
  </head>

  <body class="antialiased bg-gray-50 dark:bg-gray-900">
    <!-- Mount point Inertia -->
    @inertia

    <!-- Back to Top Button -->
    <button id="backToTop"
      class="fixed items-center justify-center hidden w-12 h-12 text-white transition-all duration-300 rounded-full shadow-lg md:flex md:bottom-6 bottom-16 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 hover:shadow-2xl hover:scale-110"
      title="Kembali ke atas">
      <i class="text-xl bi bi-arrow-up"></i>
    </button>

    <script>
      // Tombol "Kembali ke Atas"
      const backToTopBtn = document.getElementById("backToTop");
      window.addEventListener("scroll", () => {
        backToTopBtn.classList.toggle("show", window.scrollY > 100);
      });
      backToTopBtn.addEventListener("click", () => {
        window.scrollTo({ top: 0, behavior: "smooth" });
      });
    </script>

    <script>
      // Loader + SweetAlert Integration
      document.addEventListener('DOMContentLoaded', function () {
        const loader = document.getElementById('minimalLoader');
        const loaderBar = document.getElementById('loaderBar');
        const isMobile = window.innerWidth < 768;
        const hasSweetAlert = @json(session('alert') ? true : false);

        if (!isMobile && loader) loader.remove();

        if (hasSweetAlert) {
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
          if (loaderBar) setTimeout(() => loaderBar.style.width = '100%', 50);
          if (loader) setTimeout(() => loader.classList.add('hidden'), 1600);
        }
      });
    </script>

    @if (session('sanctum_token'))
      <script>
        localStorage.setItem('sanctum_token', "{{ session('sanctum_token') }}");
      </script>
    @endif

    <!-- External JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
  </body>
</html>
