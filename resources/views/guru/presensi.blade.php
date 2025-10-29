@php
use Carbon\Carbon;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ __($pageTitle ?? 'Presensi') }}
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
            {{-- Judul Presensi Guru --}}
            <div class="p-4 bg-white rounded shadow-md">
                <div class="overflow-x-auto md:overflow-x-visible">
                    <h2 class="mb-4 text-lg font-bold">Halaman Presensi Guru |  <span class="capitalize text-sky-900">{{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span></h2><hr class="mb-4">
                    <x-guru.presensi-guru
                        :hariIni="$hariIni"
                        :tanggal="$tanggal"
                        :bulan="$bulan"
                        :tahun="$tahun"
                        :guruHariIni="$guruHariIni"
                        :presensiHariIni="$presensiGuruHariIni"
                        :presensiSelesai="$presensiSelesai" />
                </div>
            </div>

            {{-- Judul Presensi Staff --}}
            <div class="p-4 bg-white rounded shadow-md">
                <div class="overflow-x-auto md:overflow-x-visible">
                    <h2 class="mb-4 text-lg font-bold">Presensi Karyawan dan Staff |  <span class="capitalize text-sky-900">{{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span></h2><hr class="mb-4">
                    <x-guru.presensi-staff
                        :hariIni="$hariIni"
                        :tanggal="$tanggal"
                        :bulan="$bulan"
                        :tahun="$tahun"
                        :staffHariIni="$staffHariIni"
                        :presensiStaffHariIni="$presensiStaffHariIni"
                        :presensiSelesai="$presensiSelesai" />
                </div>
            </div>

            @php
                // Ambil tanggal sekarang
                $tanggalSekarang = Carbon::now('Asia/Jakarta')->format('Y-m-d');

                // Cek semua presensi guru hari ini (tanpa peduli siapa usernya)
                $guruSelesai = \App\Models\PresensiGuru::whereDate('created_at', Carbon::today())
                                ->where('presensi_selesai', 1)
                                ->exists();

                // Cek semua presensi staff hari ini (tanpa peduli siapa usernya)
                $staffSelesai = \App\Models\PresensiStaff::whereDate('created_at', Carbon::today())
                                ->where('presensi_selesai', 1)
                                ->exists();

                // ✅ Tombol disable & badge muncul jika salah satu selesai (OR)
                $presensiSelesai = $guruSelesai || $staffSelesai;
            @endphp

            @if($presensiSelesai)
                <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-green-600 rounded">
                    Presensi Hari Ini Telah Selesai
                </span>
            @endif

            <form action="{{ route('guru.presensi.selesai') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit"
                    class="px-6 py-2 text-white rounded
                        {{ $presensiSelesai ? 'bg-gray-400 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700' }}"
                    @if($presensiSelesai) disabled @endif>
                    Selesaikan Presensi Hari Ini!
                </button>
            </form>

        </main>
    </div>
</x-app-layout>
