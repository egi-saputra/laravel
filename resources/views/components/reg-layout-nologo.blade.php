<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Laravel App' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center min-h-screen px-4 py-6 sm:py-12">
        {{-- Kontainer form --}}
        <div class="w-full p-6 bg-white shadow-md sm:max-w-md dark:bg-gray-800 sm:rounded-lg sm:p-8">
            {{ $slot }}
        </div>
        {{-- Footer opsional --}}
        <div class="mt-6 text-sm text-center text-gray-500 dark:text-gray-400">
            &copy; {{ date('Y') }} {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }}. All rights reserved.
        </div>
    </div>
</body>
</html>
