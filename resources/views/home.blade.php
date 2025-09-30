<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PPDB Online - Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="{{ asset('src/output.css') }}" rel="stylesheet">

  <!-- Alpine.js wajib untuk hamburger -->
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="text-gray-800 bg-gray-50">

<!-- Navbar -->
<nav class="sticky top-0 z-50 shadow bg-sky-700" x-data="{ open: false }">
  <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <!-- Logo + Nama Sekolah -->
      <a href="{{ route('home') }}" class="flex items-center space-x-2 text-xl font-bold text-slate-100">
        <x-application-logo class="block w-auto text-white fill-current h-9" />
        <span class="ml-2">{{ $profil?->nama_sekolah ?? 'Nama Sekolah Default' }}</span>
      </a>

      <!-- Desktop Menu -->
      <div class="hidden space-x-4 md:flex">
        <a href="{{ route('home') }}" class="font-semibold text-slate-100 hover:underline">Home</a>
        <a href="{{ route('about') }}" class="text-slate-100 hover:underline">About</a>
        <a href="#" class="text-slate-100 hover:underline">Contact</a>
        <a href="{{ route('login') }}" class="text-slate-100 hover:underline">PPDB</a>
      </div>

      <!-- Mobile Hamburger -->
      <div class="md:hidden">
        <button @click="open = !open" class="text-slate-100 focus:outline-none">
          <i class="bi" :class="open ? 'bi-x-lg text-2xl' : 'bi-list text-3xl'"></i>
        </button>
      </div>
    </div>
  </div>

    <!-- Mobile Menu -->
    <div
    class="absolute z-50 right-4 top-16 md:hidden"
    x-show="open"
    @click.outside="open = false"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    >
    <div class="p-4 shadow-xl w-52 rounded-xl bg-sky-800">
        <nav class="flex flex-col space-y-2 text-left">
        <a href="{{ route('home') }}"
            class="px-3 py-2 text-base font-medium transition rounded-lg text-slate-100 hover:bg-sky-600 hover:pl-5 hover:shadow-md">
            Home
        </a>
        <a href="{{ route('about') }}"
            class="px-3 py-2 text-base font-medium transition rounded-lg text-slate-100 hover:bg-sky-600 hover:pl-5 hover:shadow-md">
            About
        </a>
        <a href="#"
            class="px-3 py-2 text-base font-medium transition rounded-lg text-slate-100 hover:bg-sky-600 hover:pl-5 hover:shadow-md">
            Contact
        </a>
        <a href="{{ route('login') }}"
            class="px-3 py-2 text-base font-medium transition rounded-lg text-slate-100 hover:bg-sky-600 hover:pl-5 hover:shadow-md">
            PPDB
        </a>
        </nav>
    </div>
    </div>
</nav>

<!-- Hero Section -->
<header class="relative flex flex-col items-center justify-center min-h-[70vh] text-center text-white bg-center bg-cover"
        style="background-image: url('{{ asset('assets/images/hero.png') }}');">
  <div class="absolute inset-0 bg-black bg-opacity-60"></div>
  <div class="relative z-10 px-4">
    <h1 class="mb-4 text-4xl font-bold md:text-5xl drop-shadow-lg">
      Selamat Datang di PPDB Online
    </h1>
    <p class="mb-6 text-lg font-medium md:text-xl drop-shadow">
      Pendaftaran Peserta Didik Baru {{ $profil?->nama_sekolah ?? 'SMK Nusantara' }}
    </p>
    <a href="{{ route('login') }}"
       class="px-6 py-3 text-lg font-semibold text-white transition rounded-lg shadow-lg bg-sky-600 hover:bg-sky-700">
      Daftar Sekarang
    </a>
  </div>
</header>

<!-- Main Content -->
<section class="py-12 bg-white">
  <div class="max-w-4xl px-4 mx-auto text-center mt-14">
    <h2 class="mb-3 text-3xl font-bold text-gray-800">SYARAT PENDAFTARAN {{ $profil?->nama_sekolah ?? 'SMK Nusantara' }}</h2>
    <p class="text-lg text-gray-600 mb-14">
      Selamat datang di <strong>{{ $profil?->nama_sekolah ?? 'SMK Nusantara' }}</strong>. Berikut syarat pendaftaran secara online :
    </p>
    <img src="{{ asset('assets/images/2.png') }}" alt="PPDB Image" class="w-full mb-6 rounded-lg shadow" />
  </div>
</section>

<section class="py-12 bg-slate-100">
  <div class="max-w-4xl px-4 mx-auto mt-4 text-center">
    <h2 class="mb-3 text-3xl font-bold text-gray-800">ALUR PENDAFTARAN {{ $profil?->nama_sekolah ?? 'SMK Nusantara' }}</h2>
    <p class="text-lg text-gray-600 mb-14">
      Selamat datang di <strong>{{ $profil?->nama_sekolah ?? 'SMK Nusantara' }}</strong>. Berikut tatacara pendaftaran online :
    </p>
    <img src="{{ asset('assets/images/1.png') }}" alt="PPDB Image" class="w-full mb-6 rounded-lg shadow" />
  </div>
</section>

<!-- Footer -->
<footer class="py-4 text-sm text-center border-t bg-sky-950 text-slate-100">
    <p>&copy; {{ date('Y') }} SIMSTAL | {{ $profil?->nama_sekolah ?? 'SMK Nusantara Citayam' }}</p>
</footer>

</body>
</html>
