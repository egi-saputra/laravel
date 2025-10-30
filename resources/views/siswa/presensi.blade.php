@php
use Carbon\Carbon;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ __($pageTitle ?? 'Presensi Siswa') }}
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
            <div class="md:p-6 md:shadow-md md:bg-white md:rounded-lg">

                <div class="flex items-center justify-start w-full mb-6">
                    <h1 class="text-xl font-bold md:px-3 md:mb-0 text-sky-900"><i class="mb-2 text-2xl bi bi-journal-text"></i> Halaman Presensi Siswa <span class="hidden capitalize text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span></h1>
                </div>

                {{-- Judul Presensi Siswa --}}
                <div>
                    <div class="p-0 overflow-x-auto md:p-4 md:overflow-x-visible">
                        <h2 class="mb-3 text-sm font-semibold text-gray-600 md:mb-4 md:text-base">
                            <span class="hidden md:inline-block">{{ $user->name }}, Kamu Adalah Sekretaris Kelas
                            @if($kelas)
                                ({{ $kelas->kelas }}),
                            @endif
                             </span> Lakukan Absensi Online Untuk Kelasmu!
                        </h2>

                        <hr class="hidden mb-4 md:block">

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

            </div>

        </main>
    </div>
</x-app-layout>
