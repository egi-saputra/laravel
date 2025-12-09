<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 | Not Found</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

    <div class="min-h-screen flex flex-col items-center justify-center px-6">
        <div class="text-center">

            <h1 class="text-4xl text-gray-500 font-bold">402</h1>

            <h2 class="text-2xl text-gray-500 font-semibold mt-4">
                Akses halaman ditangguhkan
            </h2>

            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Silakan lakukan pembayaran server terlebih dahulu.
            </p>

        </div>
    </div>

</body>
</html>
