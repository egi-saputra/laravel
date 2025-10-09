<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Daftar Materi Pembelajaran') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="mx-4 mt-4 top-16 md:top-0 md:ml-6 md:mt-6 md:h-screen md:mx-0 md:w-auto">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">

            <!-- Tabel Daftar Materi -->
            <x-siswa.list-materi :kelas="$kelas" :mapel="$mapel" :materis="$materis" />
            <!-- Footer -->
            <x-footer :profil="$profil" />
        </main>

        <!-- Bottom Navigation (Mobile Only - Icon Only) -->
        <div id="navhp" class="fixed bottom-0 left-0 right-0 z-50 flex justify-around py-2 bg-white border-t shadow-md md:hidden">

            <!-- Home/Dashboard -->
            <a href="{{ route('siswa.dashboard') }}" class="nav-icon {{ Route::currentRouteName() == 'siswa.dashboard' ? 'active' : '' }}">
                <i class="fas fa-home"></i>
            </a>

            <!-- Data Diri -->
            <a href="{{ route('siswa.data_diri') }}" class="nav-icon {{ request()->routeIs('siswa.data_diri') ? 'active' : '' }}">
                <i class="fas fa-id-card"></i>
            </a>

            <!-- Siswa -->
            <a href="{{ route('public.daftar_siswa.index') }}" class="nav-icon {{ request()->routeIs('public.daftar_siswa.*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i>
            </a>

            <!-- Akademik -->
            <a href="{{ route('siswa.materi.index') }}" class="nav-icon {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
            </a>

            <!-- Tugas Siswa -->
            <a href="{{ route('siswa.tugas.index') }}" class="nav-icon {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
                <i class="fas fa-tasks"></i>
            </a>
        </div>
    </div>
</x-app-backtop-layout>
