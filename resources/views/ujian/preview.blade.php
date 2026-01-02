<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Informasi Ujian
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-8 px-4">

        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">

            {{-- Title --}}
            <h3 class="text-2xl font-extrabold text-gray-700 mb-6 text-center">
                Informasi Quiz / Soal Ujian
            </h3>

            {{-- Token Section --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 text-center shadow-sm">
                <p class="text-xs text-blue-600 font-semibold uppercase tracking-wide">Token Ujian</p>
                <p class="text-3xl font-extrabold text-blue-700 mt-1">
                    {{ $soal->token }}
                </p>
            </div>

            {{-- Info Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 mb-4 gap-4 text-sm">

                <div class="bg-gray-50 rounded-xl p-4 border hover:shadow-md transition">
                    <p class="text-gray-500 text-xs font-semibold uppercase">Mata Pelajaran</p>
                    <p class="text-gray-800 font-bold text-lg mt-1">
                        {{ $soal->mapel->mapel ?? 'Tidak ada' }}
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border hover:shadow-md transition">
                    <p class="text-gray-500 text-xs font-semibold uppercase">Unit Kelas</p>
                    <p class="text-gray-800 font-bold text-lg mt-1">
                        {{ $soal->kelas->kelas ?? '-' }}
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border hover:shadow-md transition">
                    <p class="text-gray-500 text-xs font-semibold uppercase">Jumlah Soal</p>
                    <p class="text-gray-800 font-bold text-lg mt-1">
                        {{ $jumlahSoal }} Soal
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border hover:shadow-md transition">
                    <p class="text-gray-500 text-xs font-semibold uppercase">Durasi Ujian</p>
                    <p class="text-gray-800 font-bold text-lg mt-1">
                        {{ $soal->waktu }} menit
                    </p>
                </div>

            </div>

            {{-- Warning Section --}}
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm">
                <h4 class="font-bold text-red-700 mb-2">Perhatian!</h4>
                <ul class="list-disc ml-4 list-inside text-red-600 text-sm space-y-1">
                    <li>Kerjakan soal ujian dalam rentang waktu yang sudah ditetapkan.</li>
                    <li>Waktu durasi ujian akan dimulai sejak peserta meng klik tombol kerjakan.</li>
                    <li>Jika waktu ujian telah habis atau selesai, maka ujian akan ditutup secara otomatis.</li>
                    <li>Jangan menutup tab atau membuka tab baru selama ujian berlangsung.</li>
                    <li>Jangan menggunakan tombol back atau refresh browser.</li>
                    <li>Jangan menyalin, menempel, atau mengambil screenshot soal.</li>
                    <li>Jangan menggunakan shortcut untuk copy-paste, print, atau membuka developer tools.</li>
                </ul>
                <p class="mt-2 text-red-700 text-sm">
                    Pelanggaran akan menyebabkan token Anda otomatis diperbarui, status ujian mungkin tidak tersimpan, atau ujian dianggap batal.
                </p>
            </div>

            {{-- Buttons --}}
            <div class="mt-8 flex justify-between items-center">
                <a href="{{ route('ujian.token') }}"
                    class="px-5 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 
                           font-semibold transition shadow-sm hover:shadow-md">
                    ← Kembali
                </a>

                <a href="{{ route('ujian.kerjakan', $soal->id) }}"
                    class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white 
                           font-bold shadow-lg transition transform hover:scale-105">
                    Kerjakan →
                </a>
            </div>

        </div>

    </div>

    {{-- Non-Turbo Script --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Blokir refresh dan back
            window.onbeforeunload = function() {
                return "Jangan menutup atau merefresh halaman saat ujian!";
            };

            // Nonaktifkan shortcut umum
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) &&
                    ['c','v','x','r','t','n','w'].includes(e.key.toLowerCase())) {
                    e.preventDefault();
                    alert("Shortcut dinonaktifkan selama ujian!");
                }
                if (e.key === 'F5') { e.preventDefault(); alert("Reload diblokir!"); }
                if (e.key === 'Escape') { e.preventDefault(); }
            });

            // Nonaktifkan klik kanan
            document.addEventListener('contextmenu', e => e.preventDefault());
        });
    </script>

</x-app-layout>
