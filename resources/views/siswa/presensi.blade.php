@php
use Carbon\Carbon;
@endphp

<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ __($pageTitle ?? 'Presensi Siswa') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="mx-4 mt-4 top-16 md:top-0 md:ml-6 md:mt-6 md:h-screen md:mx-0 md:w-auto">
            <x-sidebar />
        </aside>

        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
            <div class="flex items-center justify-center w-full p-6 bg-white rounded shadow md:p-10">
                <h1 class="text-lg font-semibold">Halaman Presensi Siswa <span class="hidden capitalize text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span></h1>
            </div>

            {{-- Judul Presensi Siswa --}}
            <div class="p-4 bg-white rounded shadow-md">
                <div class="p-0 overflow-x-auto md:p-4 md:overflow-x-visible">
                    <h2 class="mb-4 text-base font-semibold md:text-lg">
                        <span class="hidden md:inline-block">{{ $user->name }}, Kamu Adalah Sekretaris Kelas
                        @if($kelas)
                            ({{ $kelas->kelas }})
                        @endif
                        , </span>Lakukan Absensi Online Untuk Kelasmu!
                    </h2>

                    <hr class="mb-4">

                    <x-siswa.presensi-siswa
                        :siswaKelas="$siswaKelas"
                        :presensiHariIni="$presensiHariIni"
                        :presensiSelesai="$presensiSelesai"
                        :user="$user" />
                </div>
            </div>

            @php
                $userId = $user->id ?? null;

                $presensiSelesaiBadge = \App\Models\PresensiSiswa::where('user_id', $userId)
                                            ->whereDate('created_at', \Carbon\Carbon::today())
                                            ->where('is_selesai', 1)
                                            ->exists();
            @endphp

            @if($presensiSelesaiBadge)
                <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-green-600 rounded">
                    Presensi Hari Ini Telah Dilakukan
                </span>
            @endif
        </main>
    </div>

        <!-- Bottom Navigation (Mobile Only - Icon + Text) -->
        <div id="navhp" class="fixed bottom-0 left-0 right-0 z-50 flex justify-around py-2 bg-white border-t shadow-md md:hidden text-xs">

            <!-- Home/Dashboard -->
            <a href="{{ route('siswa.dashboard') }}" class="flex flex-col items-center nav-icon {{ Route::currentRouteName() == 'siswa.dashboard' ? 'active' : '' }}">
                <i class="fas fa-home text-lg"></i>
                <span>Home</span>
            </a>

            <!-- Akademik -->
            <a href="{{ route('siswa.materi.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
                <i class="fas fa-book text-lg"></i>
                <span>Akademik</span>
            </a>

            <!-- Siswa -->
            <a href="{{ route('public.daftar_siswa.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('public.daftar_siswa.*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate text-lg"></i>
                <span>Siswa</span>
            </a>

            <!-- Tugas Siswa -->
            <a href="{{ route('siswa.tugas.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
                <i class="fas fa-tasks text-lg"></i>
                <span>Tugas</span>
            </a>

            <!-- Informasi Sekolah -->
            <a href="{{ route('public.informasi_sekolah.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('public.informasi_sekolah.index') ? 'active' : '' }}">
                <i class="fas fa-school text-lg"></i>
                <span>Sekolah</span>
            </a>

        </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-backtop-layout>
