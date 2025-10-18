<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Kelola Data Siswa') }}
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
        <main class="flex-1 p-0 mb-16 overflow-x-auto md:mb-0 md:p-6">

            <!-- Versi Mobile: Card -->
            <div class="block md:hidden">
                <x-guru.card-materi :kelas="$kelas" :mapel="$mapel" :materis="$materis" />
            </div>

            <!-- Versi Desktop: Tabel -->
            <div class="hidden md:block">
                <x-guru.tabel-materi :kelas="$kelas" :mapel="$mapel" :materis="$materis" />
            </div>
        </main>
    </div>
</x-app-layout>
