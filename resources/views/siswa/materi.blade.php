<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Daftar Materi Pembelajaran') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="mt-0 md:ml-6 md:block hidden md:mt-6 md:h-screen md:mb-0 mb-4 md:w-auto">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 mt-4 md:mt-6 p-0 md:mb-0 mb-10 space-y-6 overflow-x-auto md:p-6">

            <!-- Tabel Daftar Materi -->
            <x-siswa.list-materi :kelas="$kelas" :mapel="$mapel" :materis="$materis" />
            <!-- Footer -->
            <x-footer :profil="$profil" />
        </main>

        <!-- Bottom Navigation (Mobile Only - Icon + Text) -->
        <div id="navhp" class="fixed bottom-0 left-0 right-0 z-50 flex justify-around py-2 bg-white border-t shadow-md md:hidden text-xs">

            <!-- Home/Dashboard -->
            <a href="{{ route('siswa.dashboard') }}" class="flex flex-col items-center nav-icon {{ Route::currentRouteName() == 'siswa.dashboard' ? 'active' : '' }}">
                <i class="fas fa-chart-line text-lg"></i>
                <small class="text-xs font-semibold">Beranda</small>
            </a>

            <!-- Siswa -->
            <a href="{{ route('public.daftar_siswa.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('public.daftar_siswa.*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate text-lg"></i>
                <small class="text-xs font-semibold">Siswa</small>
            </a>

            <!-- Informasi Sekolah -->
            <a href="{{ route('public.informasi_sekolah.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('public.informasi_sekolah.index') ? 'active' : '' }}">
                <i class="fas fa-school text-lg"></i>
                <small class="text-xs font-semibold">Sekolah</small>
            </a>

            <!-- Akademik -->
            <a href="{{ route('siswa.materi.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
                <i class="fas fa-book text-lg"></i>
                <small class="text-xs font-semibold">Materi</small>
            </a>

            <!-- Tugas Siswa -->
            <a href="{{ route('siswa.tugas.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
                <i class="fas fa-tasks text-lg"></i>
                <small class="text-xs font-semibold">Tugas</small>
            </a>

        </div>
    </div>
</x-app-backtop-layout>
