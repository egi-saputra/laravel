<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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

    <!-- Optional: Custom CSS -->
    {{-- <style>
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active  {
        -webkit-box-shadow: 0 0 0 1000px #fff inset !important; /* pakai background putih */
        box-shadow: 0 0 0 1000px #fff inset !important;
        -webkit-text-fill-color: #212529; /* warna teks normal */
        caret-color: #212529; /* warna kursor */
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .left-panel {
            color: #fff;
            padding: 2rem;
        }

        .left-panel img {
            width: 120px;
            margin-bottom: 20px;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
        }

        .form-label {
            font-weight: 600;
        }

        .btn-login {
            background-color: #063970;
            border: none;
        }

        .btn-login:hover {
            background-color: #212529;
        }

        .btn-outline-secondary {
            border: 1.5px solid #ccc;
            background-color: #fff;
            color: #333;
            padding: 10px 18px;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            border-color: #063970;
            color: #063970;
            background-color: #f9f9f9;
        }

        .form-password-wrapper {
            border: 1px solid #ced4da;
            transition: border-color 0.4s ease !important;
            /* transition: all 0.2s; */
        }

        .form-password-wrapper:focus-within {
            border-color: #063970 !important;
        }

        .form-group {
        position: relative;
        }

        .form-group input {
        width: 100%;
        padding: 0.75rem;
        font-size: 1rem;
        border: 1px solid #ced4da;
        transition: border-color 0.4s ease !important;
        }

        .px-2.border.rounded:focus-within {
            border-color: #063970 !important;          /* warna border saat fokus */
            /* box-shadow: 0 0 0 0.2rem rgba(6, 57, 112, 0.25); */
        }

        .form-control:focus {
        border-color: #063970;
        box-shadow: none !important;
        }

        .form-group label {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        background: transparent;
        transition: 0.2s ease all;
        pointer-events: none; /* supaya klik tetap fokus ke input */
        }

        /* Saat fokus atau ada isi → label naik ke border */
        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label {
        top: 0;
        transform: translateY(-50%);
        font-size: 0.85rem;
        color: #063970;
        background: #fff;   /* biar seperti motong garis */
        padding: 0 .25rem;
        }

        .font-poppins {
        font-family: 'Poppins', sans-serif;
        }

    </style> --}}

    <style>
        /* Nonaktifkan background biru/kuning browser pada autofill */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0px 1000px white inset !important; /* ubah white sesuai bg input */
            -webkit-text-fill-color: #000 !important; /* warna teks tetap hitam */
            transition: background-color 5000s ease-in-out 0s; /* cegah flash biru */
        }

        [x-cloak] { display: none !important; }
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

</head>
<body class="font-sans antialiased">
    <x-alert />
    {{ $slot }}

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
