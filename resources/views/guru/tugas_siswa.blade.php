<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Kelola Tugas Siswa') }}
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
        <main class="flex-1 p-0 mb-16 space-y-2 overflow-x-auto md:space-y-6 md:mb-0 md:p-6">
            {{-- <div class="flex items-center justify-center w-full p-10 bg-white rounded shadow">
                <h2 class="mb-0 text-lg font-bold">
                    Kelola Tugas Peserta Didik
                    <span class="hidden capitalize text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span>
                </h2>
                <hr class="mb-4">
            </div> --}}

            <!-- Tabel Daftar Tugas -->
            <x-guru.list-tugas :kelas="$kelas" :mapel="$mapel" :tugas="$tugas" />
        </main>
    </div>

    <div class="mt-4">
        {{ $tugas->links('pagination::tailwind') }}
    </div>

</x-app-layout>
