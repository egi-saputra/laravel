{{-- <x-app-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? '') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">

        <aside class="hidden mx-0 mt-2 mb-4 md:block md:top-0 md:ml-6 md:mt-6 md:w-auto">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Footer -->
            <x-footer :profil="$profil" />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-0 !mb-16 space-y-2 overflow-x-auto md:space-y-6 md:mb-0 md:p-6">
            <!-- Form Tambah Data Struktural -->
            <div class="mb-12 md:p-6 md:mb-6 md:bg-white md:rounded md:shadow">
                <h2 class="hidden mb-4 text-xl font-bold md:inline-block">Tambah Data Struktur Internal Sekolah</h2>
                <h2 class="inline-block mb-4 text-xl font-bold md:hidden">Tambahkan Data Struktural</h2>

                <form action="{{ route('admin.struktural.store') }}" method="POST">
                    @csrf

                    <div class="flex flex-col w-full gap-2 md:flex-row">
                    <!-- Jabatan -->
                    <div class="w-full mb-4 ">
                        <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
                        <input type="text" name="jabatan" id="jabatan"
                               class="w-full px-3 py-2 mt-1 border border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                    </div>

                    <!-- Nama GTK (Guru) -->
                    <div class="mb-4">
                        <label for="nama_gtk" class="block text-sm font-medium text-gray-700">Nama GTK</label>
                        <select name="nama_gtk" id="nama_gtk" class="w-full px-3 py-2 mt-1 border border-gray-300 rounded shadow-sm" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus->sortBy(fn($guru) => $guru->user->name ?? '') as $guru)
                                <option value="{{ $guru->id }}" {{ old('nama_gtk') == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->user->name ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full mb-4">
                        <label for="nama_gtk" class="block text-sm font-medium text-gray-700">Nama GTK</label>
                        <select name="nama_gtk" id="nama_gtk" class="w-full px-3 py-2 mt-1 border border-gray-300 rounded shadow-sm" required>
                            <option value="">-- Pilih GTK (Guru / Staff / Admin) --</option>
                            @foreach($gurus as $user)
                                <option value="{{ $user->id }}" {{ old('nama_gtk') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="flex justify-end md:justify-start">
                        <button type="submit"
                                class="px-4 py-2 mt-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            <i class="bi bi-check2-square"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- List/Tabel Data Struktural -->
            <div id="userTableWrapper" class="overflow-x-auto md:overflow-x-visible">
                <x-admin.struktural :struktural="$struktural" :gurus="$gurus" />
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kembalikan posisi scroll window
            const scrollPos = localStorage.getItem('scrollY');
            if (scrollPos) {
                window.scrollTo(0, parseInt(scrollPos));
            }

            // Simpan posisi scroll window setiap ada scroll
            window.addEventListener('scroll', function() {
                localStorage.setItem('scrollY', window.scrollY);
            });

            // Optional: hapus posisi scroll saat pindah halaman tertentu
            window.addEventListener('beforeunload', function() {
                // localStorage.removeItem('scrollY'); // opsional, kalau mau reset
            });
        });
    </script>

</x-app-admin-layout> --}}

<x-app-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            {{ __($pageTitle ?? 'Data Struktural Sekolah') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">

        <!-- Sidebar -->
        <aside class="hidden mx-0 mt-2 mb-4 md:block md:top-0 md:ml-6 md:mt-6 md:w-auto">
            <x-sidebar />
            <x-footer :profil="$profil" />
        </aside>

        <!-- Konten Utama -->
        <main class="flex-1 p-0 space-y-6 overflow-x-auto md:p-6 md:space-y-8">

            <!-- FORM TAMBAH DATA -->
            <div class="transition-all duration-300 border-gray-200 md:p-6 md:bg-white/70 dark:bg-gray-900/60 md:rounded md:shadow backdrop-blur-lg md:border dark:border-gray-700">
                <h2 class="mb-4 text-xl font-bold text-gray-800 dark:text-gray-100">
                    Tambah Data Struktur Internal Sekolah
                </h2>

                <form action="{{ route('admin.struktural.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="flex flex-col gap-4 md:flex-row">
                        <!-- Jabatan -->
                        <div class="flex-1">
                            <label for="jabatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jabatan</label>
                            <input type="text" name="jabatan" id="jabatan"
                                class="w-full px-3 py-2 mt-1 bg-transparent border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:border-gray-700 dark:text-gray-100"
                                placeholder="Contoh: Kepala Sekolah" required>
                        </div>

                        <!-- Nama GTK -->
                        <div class="flex-1">
                            <label for="nama_gtk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama GTK</label>
                            <select name="nama_gtk" id="nama_gtk"
                                class="w-full px-3 py-2 mt-1 bg-transparent border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:border-gray-700 dark:text-gray-100"
                                required>
                                <option value="">-- Pilih GTK (Guru / Staff / Admin) --</option>
                                @foreach($gurus as $user)
                                    <option value="{{ $user->id }}" {{ old('nama_gtk') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-4 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-400">
                            <i class="bi bi-check2-square"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- TABEL DATA STRUKTURAL -->
            <div id="userTableWrapper" class="overflow-x-auto md:overflow-x-visible">
                <x-admin.struktural :struktural="$struktural" :gurus="$gurus" />
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollPos = localStorage.getItem('scrollY');
            if (scrollPos) window.scrollTo(0, parseInt(scrollPos));
            window.addEventListener('scroll', () => localStorage.setItem('scrollY', window.scrollY));
        });
    </script>
</x-app-admin-layout>
