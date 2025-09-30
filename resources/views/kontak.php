<?php
require_once 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kontak Kami - SMK Nusantara</title>
  <link href="src/output.css" rel="stylesheet">
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body class="text-gray-800 bg-gray-50">

<!-- Navbar -->
<nav class="sticky top-0 z-50 shadow bg-sky-700">
  <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <a href="index.php" class="flex items-center space-x-2 text-xl font-bold text-slate-100">
        <img src="assets/images/logo.png" class="p-1" width="50" height="50">
        <span>SMK NUSANTARA</span>
      </a>
      <div class="hidden space-x-4 md:flex">
        <a href="index.php" class="font-semibold text-slate-100 hover:underline">Home</a>
        <a href="about.php" class="text-slate-100 hover:underline">About</a>
        <a href="kontak.php" class="text-slate-100 hover:underline">Contact</a>
        <a href="user/login.php" class="text-slate-100 hover:underline">PPDB</a>
      </div>
    </div>
  </div>
</nav>

<!-- Contact Section -->
<section class="py-16 bg-white">
  <div class="max-w-5xl px-6 mx-auto">
    <h1 class="mb-8 text-4xl font-bold text-center text-sky-800">Hubungi Kami</h1>

    <div class="grid gap-10 md:grid-cols-2">
      <!-- Contact Info -->
      <div>
        <h2 class="mb-4 text-2xl font-semibold">Informasi Kontak</h2>
        <p class="mb-4 text-gray-700">Jika Anda memiliki pertanyaan seputar PPDB atau kegiatan sekolah, silakan hubungi kami melalui informasi berikut:</p>
        <ul class="space-y-3 text-gray-700">
          <li><i class="mr-2 bi bi-geo-alt-fill text-sky-700"></i> Jl. Pendidikan No. 123, Citayam, Depok</li>
          <li><i class="mr-2 bi bi-telephone-fill text-sky-700"></i> (021) 1234 5678</li>
          <li><i class="mr-2 bi bi-envelope-fill text-sky-700"></i> info@smknusantara.sch.id</li>
          <li><i class="mr-2 bi bi-globe text-sky-700"></i> www.smknusantara.sch.id</li>
        </ul>

        <!-- Placeholder map -->
        <div class="mt-6">
          <iframe src="https://maps.google.com/maps?q=SMK%20Nusantara%20Citayam&t=&z=15&ie=UTF8&iwloc=&output=embed"
            class="w-full h-64 rounded shadow" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>

      <!-- Contact Form -->
      <div>
        <h2 class="mb-4 text-2xl font-semibold">Kirim Pesan</h2>
        <form class="space-y-4">
          <div>
            <label class="block mb-1 font-medium">Nama Lengkap</label>
            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded" placeholder="Nama Anda" required />
          </div>
          <div>
            <label class="block mb-1 font-medium">Email</label>
            <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded" placeholder="email@domain.com" required />
          </div>
          <div>
            <label class="block mb-1 font-medium">Pesan</label>
            <textarea class="w-full h-32 px-4 py-2 border border-gray-300 rounded resize-none" placeholder="Tulis pesan Anda..." required></textarea>
          </div>
          <button type="submit" class="px-6 py-2 text-white transition rounded bg-sky-700 hover:bg-sky-800">
            <i class="mr-2 bi bi-send-fill"></i> Kirim
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="py-4 text-sm text-center border-t bg-sky-950 text-slate-100">
    <p>&copy; {{ date('Y') }} SIMSTAL | {{ $profil?->nama_sekolah ?? 'SMK Nusantara Citayam' }}</p>
    <p>Semua Hak Cipta Dilindungi</p>
</footer>

</body>
</html>
