@php
use Carbon\Carbon;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ __($pageTitle ?? 'Rekap Honor Guru') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:!flex-row">

        <aside class="hidden mx-0 mt-2 mb-4 md:block md:top-0 md:ml-6 md:mt-6 md:w-auto">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Footer -->
            <x-footer :profil="$profil" />
        </aside>

        <!-- Main Content -->
        <main class="!flex-1 p-0 !mb-16 space-y-2 overflow-x-auto md:space-y-6 md:mb-0 md:p-6">
            <div class="items-center justify-center hidden w-full p-10 bg-white rounded shadow md:flex">
                <h2 class="text-lg font-bold">
                    Rekapitulasi Honor Staff / Karyawan
                    <span class="hidden capitalize text-sky-900 md:inline-block">
                        | {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |
                    </span>
                </h2>
                <hr class="mb-4">
            </div>

            <div class="overflow-x-auto md:overflow-x-visible">
                <div class="mb-6 md:p-6 md:bg-white md:rounded md:shadow-md">
                    <h2 class="mb-4 text-lg font-bold">Mulai Rekap Honor Staff!</h2>

                    <form action="{{ route('staff.rekap_honor_staff.generate') }}" method="POST" class="space-y-6" data-turbo="false">
                        @csrf

                        {{-- Periode Waktu & Uang Kehadiran --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 font-semibold">Periode Waktu</label>
                                <input type="month" name="periode_bulan"
                                    class="w-full p-2 border rounded"
                                    value="{{ request('periode_bulan') }}" required>
                            </div>

                            <div>
                                <label class="block mb-2 font-semibold">Uang Kehadiran</label>
                                <input type="text" name="uang_jam" placeholder="Rp"
                                    class="w-full p-2 border rounded uang-format"
                                    value="{{ request('uang_jam') }}" required>
                                <p class="mt-1 text-xs text-gray-500">
                                    Nilai dikalikan dengan <em>Jumlah Kehadiran</em>.
                                </p>
                            </div>
                        </div>

                        {{-- Uang Sakit & Uang Izin --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 font-semibold">Uang Sakit <span class="text-sm font-semibold text-gray-600">(Opsional)</span></label>
                                <input type="text" name="uang_sakit" placeholder="Rp"
                                    class="w-full p-2 border rounded uang-format"
                                    value="{{ request('uang_sakit') }}">
                                <p class="mt-1 text-xs text-gray-500">
                                    Opsional, isi jika staff tetap menerima honor saat sakit.
                                </p>
                            </div>

                            <div>
                                <label class="block mb-2 font-semibold">Uang Izin <span class="text-sm font-semibold text-gray-600">(Opsional)</span></label>
                                <input type="text" name="uang_izin" placeholder="Rp"
                                    class="w-full p-2 border rounded uang-format"
                                    value="{{ request('uang_izin') }}">
                                <p class="mt-1 text-xs text-gray-500">
                                    Opsional, isi jika staff tetap menerima honor saat izin.
                                </p>
                            </div>
                        </div>

                        {{-- Uang Apel & Upacara --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 font-semibold">Uang Apel <span class="text-sm font-semibold text-gray-600">(Opsional)</span></label>
                                <input type="text" name="uang_apel" placeholder="Rp"
                                    class="w-full p-2 border rounded uang-format"
                                    value="{{ request('uang_apel') }}" required>
                            </div>

                            <div>
                                <label class="block mb-2 font-semibold">Uang Upacara <span class="text-sm font-semibold text-gray-600">(Opsional)</span></label>
                                <input type="text" name="uang_upacara" placeholder="Rp"
                                    class="w-full p-2 border rounded uang-format"
                                    value="{{ request('uang_upacara') }}" required>
                            </div>
                        </div>

                        {{-- Tombol Generate --}}
                        <div class="flex justify-end pt-4 md:justify-start">
                            <button type="submit" data-turbo="false"
                                    class="flex items-center px-6 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z"/>
                                </svg>
                                Generate
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Hasil Rekap --}}
                <div class="mb-6 md:p-6 md:bg-white md:rounded md:shadow-md">
                    @if(isset($rekap) && $rekap->count() > 0)
                        <x-staff.rekap-honor-staff
                            :rekap="$rekap"
                            :uangJam="$uangJam"
                            :uangApel="$uangApel"
                            :uangUpacara="$uangUpacara"
                            :uangSakit="$uangSakit"
                            :uangIzin="$uangIzin"
                            :periodeBulan="$periodeBulan"
                            :profil="$profil"
                        />
                    @elseif($isGenerated && $rekap->count() == 0)
                        <div class="p-4 text-red-700 bg-red-100 border border-red-400 rounded">
                            Tidak ada data yang ditemukan pada periode yang dipilih!
                        </div>
                    @else
                        <div class="p-4 text-center text-blue-700 bg-blue-100 border border-blue-400 rounded">
                            Lakukan kalkulasi penghitungan honor melalui fitur 'Generate' di atas terlebih dahulu!
                        </div>
                    @endif
                </div>
            </div>

            {{-- Script Format Rupiah --}}
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    // Format angka menjadi Rupiah saat diketik
                    document.querySelectorAll('.uang-format').forEach(function (input) {
                        input.addEventListener('input', function () {
                            let value = this.value.replace(/\D/g, ''); // hanya angka
                            this.value = value ? new Intl.NumberFormat('id-ID').format(value) : '';
                        });
                    });

                    // Hapus format sebelum submit
                    const form = document.querySelector('form[data-turbo="false"]');
                    if (form) {
                        form.addEventListener('submit', function () {
                            document.querySelectorAll('.uang-format').forEach(function (input) {
                                input.value = input.value.replace(/\D/g, '');
                            });
                        });
                    }
                });
            </script>
        </main>
    </div>
</x-app-layout>
