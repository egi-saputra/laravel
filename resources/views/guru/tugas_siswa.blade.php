<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Kelola Tugas Siswa') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="z-0 mx-4 mt-4 md:z-10 top-16 md:top-0 md:ml-6 md:mt-6 md:h-screen md:mx-0 md:w-auto">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">

            <!-- Tabel Daftar Tugas -->
            <x-guru.list-tugas :kelas="$kelas" :mapel="$mapel" :tugas="$tugas" />
        </main>
    </div>

    <div class="mt-4">
        {{ $tugas->links('pagination::tailwind') }}
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-backtop-layout>
