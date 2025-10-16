<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Kelola Tugas Siswa') }}
        </h2>
    </x-slot>

    <div class="flex-col hidden min-h-screen md:flex md:flex-row">
        <!-- Sidebar -->
        <aside class="top-0 hidden p-2 mb-4 mr-4 md:block md:h-screen">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-2 overflow-x-auto md:space-y-6">
            <div class="flex items-center justify-center w-full p-10 bg-white rounded shadow">
                <h2 class="mb-0 text-lg font-bold">
                    Kelola Tugas Peserta Didik
                    <span class="hidden capitalize text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span>
                </h2>
                <hr class="mb-4">
            </div>

            <!-- Tabel Daftar Tugas -->
            <x-guru.list-tugas :kelas="$kelas" :mapel="$mapel" :tugas="$tugas" />
        </main>
    </div>

    <div class="mt-4">
        {{ $tugas->links('pagination::tailwind') }}
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-layout>
