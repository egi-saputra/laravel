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
            <div class="items-center justify-center hidden w-full p-4 bg-white rounded shadow md:flex md:p-10">
                <h2 class="text-lg font-bold">
                    Rekapitulasi Honor Guru <span class="hidden capitalize text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span>
                </h2>
                <hr class="mb-4">
            </div>

            <div class="overflow-x-auto md:overflow-x-visible">
                <div class="mb-6 md:p-6 md:bg-white md:rounded md:shadow-md">
                    <h2 class="mb-4 text-lg font-bold">Mulai Rekap Honor Guru!</h2>

                    <form action="{{ route('staff.rekap_honor_guru.generate') }}" method="POST" class="space-y-4" data-turbo="false">
                        @csrf

                        {{-- Periode --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 font-semibold">Periode Waktu</label>
                                <input type="month" name="periode_bulan" class="w-full p-2 border rounded"
                                    value="{{ request('periode_bulan') }}" required>
                            </div>

                            {{-- hidden untuk dipakai JS/PDF --}}
                            <input type="hidden" id="periodeBulan" value="{{ request('periode_bulan') }}">
                            <input type="hidden" id="namaSekolah" value="{{ $profil->nama_sekolah ?? 'Belum ada data sekolah' }}">

                            <div>
                                <label class="block mb-2 font-semibold">Jumlah Minggu (Bulan Ini)</label>
                                <input type="number" name="jumlah_minggu" class="w-full p-2 bg-gray-100 border rounded"
                                    value="{{ $jumlahMinggu ?? '' }}" readonly disabled>
                            </div>
                        </div>

                        {{-- Tarif / Nominal yang sesuai logika --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 font-semibold">Uang Transport (per jam hadir)</label>
                                <input type="text" name="uang_transport" id="uang_transport"
                                    placeholder="Cth. Rp. 10.000,-" class="w-full p-2 border rounded money-input"
                                    value="{{ request('uang_transport') ?? '' }}" required>
                                <p class="mt-1 text-xs text-gray-500">Nilai yang dikalikan dengan <em>Jumlah Jam</em>.</p>
                            </div>

                            <div>
                                <label class="block mb-2 font-semibold">Uang Jam Mati (per jam jadwal)</label>
                                <input type="text" name="uang_jam_mati" id="uang_jam_mati"
                                    placeholder="Cth. Rp. 5.000,-" class="w-full p-2 border rounded money-input"
                                    value="{{ request('uang_jam_mati') ?? '' }}" required>
                                <p class="mt-1 text-xs text-gray-500">Nilai yang dikalikan dengan (<em>Jumlah Jam Guru × Jumlah Minggu</em>).</p>
                            </div>
                        </div>

                        {{-- Uang Sakit & Izin --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 font-semibold">Uang Sakit <span class="text-sm font-semibold text-gray-600">(Opsional)</span></label>
                                <input type="text" name="uang_sakit" placeholder="Rp. 0,- / Sakit"
                                    class="w-full p-2 border rounded money-input" value="{{ request('uang_sakit') ?? '' }}">
                            </div>
                            <div>
                                <label class="block mb-2 font-semibold">Uang Izin <span class="text-sm font-semibold text-gray-600">(Opsional)</span></label>
                                <input type="text" name="uang_izin" placeholder="Rp. 0,- / Izin"
                                    class="w-full p-2 border rounded money-input" value="{{ request('uang_izin') ?? '' }}">
                            </div>
                        </div>

                        {{-- Uang Apel & Upacara --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 font-semibold">Uang Apel <span class="text-sm font-semibold text-gray-600">(Opsional)</span></label>
                                <input type="text" name="uang_apel" placeholder="Rp. 0,- / Apel"
                                    class="w-full p-2 border rounded money-input" value="{{ request('uang_apel') ?? '' }}" required>
                            </div>
                            <div>
                                <label class="block mb-2 font-semibold">Uang Upacara <span class="text-sm font-semibold text-gray-600">(Opsional)</span></label>
                                <input type="text" name="uang_upacara" placeholder="Rp. 0,- / Upacara"
                                    class="w-full p-2 border rounded money-input" value="{{ request('uang_upacara') ?? '' }}" required>
                            </div>
                        </div>

                        {{-- Uang Pembina --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 font-semibold">Uang Pembina Apel <span class="text-sm font-semibold text-gray-600">(Opsional)</span></label>
                                <input type="text" name="uang_pembina_apel" placeholder="Rp. 0,- / Membina Apel"
                                    class="w-full p-2 border rounded money-input" value="{{ request('uang_pembina_apel') ?? '' }}" required>
                            </div>
                            <div>
                                <label class="block mb-2 font-semibold">Uang Pembina Upacara <span class="text-sm font-semibold text-gray-600">(Opsional)</span></label>
                                <input type="text" name="uang_pembina_upacara" placeholder="Rp. 0,- / Membina upacara"
                                    class="w-full p-2 border rounded money-input" value="{{ request('uang_pembina_upacara') ?? '' }}" required>
                            </div>
                        </div>

                        {{-- Tombol Generate --}}
                        <div class="flex justify-end pt-4 md:justify-start">
                            <button type="submit" class="flex items-center px-6 py-2 text-white bg-blue-600 rounded hover:bg-blue-700" data-turbo="false">
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

                {{-- hasil rekap --}}
                <div class="mb-6 md:p-6 md:bg-white md:rounded md:shadow-md">
                    @if(isset($rekap) && $rekap->count() > 0)
                        <x-staff.rekap-honor-guru :rekap="$rekap" />
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
        </main>
    </div>

    {{-- JS untuk format Rupiah --}}
    {{-- <script>
        document.querySelectorAll('.money-input').forEach(function(input) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, "");
                e.target.value = new Intl.NumberFormat('id-ID').format(value);
            });
        });

        document.querySelector('form').addEventListener('submit', function() {
            document.querySelectorAll('.money-input').forEach(function(input) {
                input.value = input.value.replace(/\./g, "");
            });
        });
    </script> --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Format otomatis ke Rupiah setiap kali user mengetik
            document.querySelectorAll('.money-input').forEach(function(input) {
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, ""); // hanya ambil angka
                    if (value) {
                        e.target.value = new Intl.NumberFormat('id-ID').format(value);
                    } else {
                        e.target.value = "";
                    }
                });
            });

            // Pastikan form hanya dikirim dengan angka mentah (tanpa format)
            const form = document.querySelector('form[data-turbo="false"]');
            if (form) {
                form.addEventListener('submit', function() {
                    document.querySelectorAll('.money-input').forEach(function(input) {
                        // Hapus semua karakter non-angka sebelum kirim
                        input.value = input.value.replace(/\D/g, "");
                    });
                });
            }
        });
    </script>
</x-app-layout>
