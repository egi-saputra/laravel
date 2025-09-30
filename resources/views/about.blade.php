<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tentang Kami - SMK Nusantara</title>
  <link href="src/output.css" rel="stylesheet">
</head>
<body class="text-gray-800 bg-gray-50">

<!-- Navbar -->
<nav class="sticky top-0 z-50 shadow bg-sky-700">
  <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <!-- Logo -->
      <a href="index.php" class="flex items-center space-x-2 text-xl font-bold text-slate-100">
        <img src="assets/images/logo.png" class="w-10 h-10" alt="Logo">
        <span>SMK NUSANTARA</span>
      </a>
      <!-- Menu -->
      <div class="hidden space-x-6 md:flex">
        <a href="index.php" class="font-medium text-slate-100 hover:text-sky-200">Home</a>
        <a href="about.php" class="font-medium text-slate-100 hover:text-sky-200">About</a>
        <a href="kontak.php" class="font-medium text-slate-100 hover:text-sky-200">Contact</a>
        <a href="user/login.php" class="px-4 py-2 font-semibold transition bg-white rounded-lg shadow text-sky-700 hover:bg-gray-100">
          PPDB
        </a>
      </div>
    </div>
  </div>
</nav>

<!-- Hero -->
<header class="relative flex items-center justify-center h-[40vh] bg-center bg-cover"
        style="background-image: url('assets/images/hero.png');">
  <div class="absolute inset-0 bg-black bg-opacity-60"></div>
  <div class="relative z-10 px-6 text-center">
    <h1 class="text-4xl font-bold text-white md:text-5xl drop-shadow-lg">
      Tentang SMK Nusantara
    </h1>
    <p class="mt-2 text-lg text-slate-200">Mencetak generasi unggul, berkarakter, dan siap kerja</p>
  </div>
</header>

<!-- Tentang -->
<section class="py-16 bg-white">
  <div class="max-w-5xl px-6 mx-auto">
    <p class="mb-8 text-lg leading-relaxed text-gray-700">
      <strong>SMK Nusantara</strong> didirikan pada tahun 2009 sebagai wujud kepedulian terhadap dunia pendidikan
      dan pengembangan sumber daya manusia di Indonesia, khususnya di daerah Citayam dan sekitarnya.
      Sekolah ini tumbuh dengan semangat mencetak generasi muda yang tidak hanya unggul secara akademis,
      tetapi juga kompeten di dunia kerja.
    </p>

    <!-- Visi -->
    <div class="mb-12">
      <h2 class="mb-3 text-3xl font-bold text-sky-700">Visi</h2>
      <div class="p-6 rounded-lg shadow bg-sky-50">
        <p class="text-gray-700">
          Menjadi sekolah menengah kejuruan unggulan dalam membentuk lulusan yang berakhlak mulia,
          berkompetensi global, dan siap bersaing di dunia kerja maupun dunia usaha.
        </p>
      </div>
    </div>

    <!-- Misi -->
    <div class="mb-12">
      <h2 class="mb-6 text-3xl font-bold text-sky-700">Misi</h2>
      <ul class="grid gap-4 md:grid-cols-2">
        <li class="p-4 transition bg-white rounded-lg shadow hover:shadow-md">
          Menyelenggarakan pendidikan kejuruan berbasis teknologi dan informasi.
        </li>
        <li class="p-4 transition bg-white rounded-lg shadow hover:shadow-md">
          Mengembangkan potensi siswa secara optimal melalui kegiatan intra & ekstrakurikuler.
        </li>
        <li class="p-4 transition bg-white rounded-lg shadow hover:shadow-md">
          Menjalin kemitraan dengan dunia industri dan perguruan tinggi.
        </li>
        <li class="p-4 transition bg-white rounded-lg shadow hover:shadow-md">
          Membentuk karakter siswa yang berintegritas, disiplin, dan bertanggung jawab.
        </li>
      </ul>
    </div>

    <!-- Nilai Mutu -->
    <div class="mb-12">
      <h2 class="mb-6 text-3xl font-bold text-sky-700">Nilai Mutu Sekolah</h2>
      <div class="grid gap-6 md:grid-cols-2">
        <div class="p-6 transition rounded-lg shadow bg-sky-50 hover:shadow-md">
          <h3 class="font-semibold text-sky-700">Integritas</h3>
          <p class="text-gray-700">Menanamkan nilai kejujuran dan tanggung jawab dalam setiap aspek pembelajaran.</p>
        </div>
        <div class="p-6 transition rounded-lg shadow bg-sky-50 hover:shadow-md">
          <h3 class="font-semibold text-sky-700">Profesionalisme</h3>
          <p class="text-gray-700">Mendorong setiap warga sekolah untuk bekerja dengan etika dan keahlian tinggi.</p>
        </div>
        <div class="p-6 transition rounded-lg shadow bg-sky-50 hover:shadow-md">
          <h3 class="font-semibold text-sky-700">Inovasi</h3>
          <p class="text-gray-700">Mengembangkan kreativitas dalam menghadapi tantangan zaman.</p>
        </div>
        <div class="p-6 transition rounded-lg shadow bg-sky-50 hover:shadow-md">
          <h3 class="font-semibold text-sky-700">Kolaborasi</h3>
          <p class="text-gray-700">Membangun kerja sama antara sekolah, siswa, orang tua, dan mitra industri.</p>
        </div>
      </div>
    </div>

    <p class="text-lg leading-relaxed text-gray-700">
      SMK Nusantara terus berkomitmen untuk menjadi pusat pendidikan vokasi yang adaptif, inklusif, dan unggul.
      Dengan dukungan guru profesional, fasilitas modern, dan lingkungan belajar yang kondusif,
      kami yakin dapat mengantarkan generasi muda ke masa depan yang gemilang.
    </p>
  </div>
</section>

<!-- Footer -->
<footer class="py-6 text-sm text-center border-t bg-sky-950 text-slate-100">
  <p>&copy; 2025 SIMSTAL | SMK Nusantara Citayam</p>
  <p class="text-xs text-slate-400">Semua Hak Cipta Dilindungi</p>
</footer>

</body>
</html>
