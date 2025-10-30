{{-- Cara penggunaan : <x-data-kelas-views :kelas="$kelas" /> --}}

{{-- <div>
    <h2 class="mb-2 text-base font-bold md:text-lg md:mb-4">Daftar Unit Kelas Dan Wali Kelas <span class="hidden capitalize text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span></h2><hr class="mb-4">

    <!-- Search Box -->
    <div class="relative mb-4">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 text-gray-500 border-r">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                      d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
            </svg>
        </span>
        <input type="text" id="searchKelas"
               placeholder="Cari kode kelas atau nama kelas..."
               class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300">
    </div>

    <div class="overflow-x-auto md:overflow-x-visible">
        <table class="w-full border border-collapse" id="kelasTable">
            <thead class="text-sm text-center md:text-base">
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border whitespace-nowrap">No</th>
                    <th class="px-4 py-2 text-left border md:text-center whitespace-nowrap">Nama Kelas</th>
                    <th class="px-4 py-2 text-left border md:text-center whitespace-nowrap">Wali Kelas</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Jumlah Siswa</th>
                </tr>
            </thead>
            <tbody class="text-sm md:text-base">
                @forelse ($kelas ?? [] as $k)
                    <tr>
                        <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $k->kelas }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            {{ $k->waliKelas?->user?->name ?? 'Tidak Ada' }}
                        </td>
                        <td class="px-4 py-2 text-center border whitespace-nowrap">
                            {{ $k->jumlah_siswa ?? 0 }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-2 text-center whitespace-nowrap">Belum ada data kelas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> --}}

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('searchKelas');
        const rows = document.querySelectorAll("#kelasTable tbody tr");

        input.addEventListener('keyup', function () {
            let filter = input.value.toLowerCase();

            rows.forEach(row => {
                let namaKelas = row.cells[1]?.textContent.toLowerCase() || "";
                let waliKelas = row.cells[2]?.textContent.toLowerCase() || "";

                if (namaKelas.includes(filter) || waliKelas.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    });
</script> --}}

<div>
    <div class="mb-4">
        <h2 class="mb-4 ml-2 text-lg font-bold text-gray-800 text-start md:font-extrabold md:text-xl">
            <i class="mr-2 text-xl text-indigo-600 fas fa-laptop-code"></i>Daftar Unit Kelas
            <span class="hidden capitalize text-sky-900 md:inline-block">
                | {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |
            </span>
        </h2>
        {{-- <hr class="mb-4"> --}}

        <!-- Search Box -->
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 text-gray-500 border-r">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                </svg>
            </span>
            <input type="text" id="searchKelas"
                placeholder="Cari nama kelas atau wali kelas..."
                class="w-full py-2 pl-12 border rounded focus:border-blue-600 focus:outline-none">
        </div>
    </div>

    <!-- Cards Grid -->
    <div id="kelasList" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($kelas ?? [] as $k)
            <div class="p-4 transition-shadow bg-white border rounded-lg shadow-sm hover:shadow-md"
                 data-namakelas="{{ strtolower($k->kelas ?? '') }}"
                 data-walikelas="{{ strtolower($k->waliKelas?->user?->name ?? '') }}">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $k->kelas }}</h3>
                    <span class="px-2 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full">
                        {{ $k->jumlah_siswa ?? 0 }} siswa
                    </span>
                </div>

                <div class="mt-1 text-sm text-gray-600">
                    <span class="font-medium text-gray-700">Wali Kelas:</span>
                    {{ $k->waliKelas?->user?->name ?? 'Tidak Ada' }}
                </div>

                <div class="mt-3 text-xs text-gray-400">
                    ID Kelas: {{ $k->id ?? '-' }}
                </div>
            </div>
        @empty
            <div class="p-4 text-center text-gray-500 border rounded-lg bg-gray-50 col-span-full">
                Belum ada data kelas.
            </div>
        @endforelse
    </div>
</div>

<!-- Search Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('searchKelas');
        const cards = document.querySelectorAll("#kelasList > div");

        input.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();

            cards.forEach(card => {
                const namaKelas = card.dataset.namakelas || "";
                const waliKelas = card.dataset.walikelas || "";

                if (namaKelas.includes(filter) || waliKelas.includes(filter)) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        });
    });
</script>

