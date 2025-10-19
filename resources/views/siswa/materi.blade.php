<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Daftar Materi Pembelajaran') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <aside class="hidden mt-0 mb-4 md:ml-6 md:block md:mt-6 md:h-screen md:mb-0 md:w-auto">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Footer -->
            <x-footer :profil="$profil" />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-0 mb-10 overflow-x-auto md:mb-0 md:p-6">

            <!-- Tabel Daftar Materi -->
            <div class="hidden md:block">
                <x-siswa.list-materi :kelas="$kelas" :mapel="$mapel" :materis="$materis" />
            </div>
            <div class="block md:hidden">
                <x-siswa.card-materi :kelas="$kelas" :mapel="$mapel" :materis="$materis" />
            </div>
        </main>
    </div>
</x-app-layout>
