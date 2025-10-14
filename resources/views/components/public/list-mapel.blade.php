{{-- <div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="mb-2 text-base font-bold md:text-lg md:mb-4">
            Daftar Mata Pelajaran <span class="hidden md:inline-block">Dan Guru Pengampu</span> <span class="hidden capitalize text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span>
        </h2>
        <hr class="mb-4">
    </div>

    <!-- Search Box -->
    <div class="relative mb-4">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 border-r text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                      d="M21 21l-4.35-4.35m0 0A7.5 7.5 0
                         1110.5 3a7.5 7.5 0 016.15 13.65z"/>
            </svg>
        </span>
        <input type="text" id="searchMapel" placeholder="Cari nama guru atau nama mapel..."
               class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300">
    </div>

    <!-- Tabel Daftar Mapel -->
    <div class="overflow-x-auto md:overflow-x-visible">
        <table class="w-full border border-collapse" id="mapelTable">
            <thead class="text-sm md:text-base">
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-center border whitespace-nowrap">No</th>
                    <th class="px-4 py-2 text-left border md:text-center whitespace-nowrap">Nama Mapel</th>
                    <th class="px-4 py-2 text-left border md:text-center whitespace-nowrap">Guru Pengampu</th>
                </tr>
            </thead>
            <tbody class="text-sm md:text-base">
                @forelse ($mapel ?? [] as $index => $m)
                    <tr>
                        <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $m->mapel }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $m->guru->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-2 text-center whitespace-nowrap">Belum ada data mapel</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> --}}

<!-- Script Search -->
{{-- <script>
    document.getElementById('searchMapel').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#mapelTable tbody tr");

        rows.forEach(row => {
            let kode = row.cells[0]?.textContent.toLowerCase();
            let mapel = row.cells[1]?.textContent.toLowerCase();
            let guru = row.cells[2]?.textContent.toLowerCase();

            if (kode.includes(filter) || mapel.includes(filter) || guru.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script> --}}

{{-- ========================================================= --}}

{{-- <div class="p-6 mb-6 bg-white shadow-md rounded-2xl">
    <!-- Header -->
    <div class="flex flex-col items-center justify-between mb-4 space-y-2 md:flex-row md:space-y-0">
        <h2 class="text-lg font-extrabold text-gray-800 md:text-xl">
            Daftar Mata Pelajaran
            <span class="hidden font-medium text-gray-600 md:inline-block">dan Guru Pengampu</span>
            <span class="hidden text-sky-900 md:inline-block">
                | {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |
            </span>
        </h2>
    </div>

    <!-- Search Box -->
    <div class="relative w-full mx-auto mb-4 md:w-1/2">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 text-gray-500 border-r">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-4.35-4.35m0 0A7.5 7.5 0
                         1110.5 3a7.5 7.5 0 016.15 13.65z"/>
            </svg>
        </span>
        <input type="text" id="searchMapel" placeholder="Cari nama guru atau nama mapel..."
            class="w-full py-2 pl-12 transition border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse md:text-base" id="mapelTable">
            <thead>
                <tr class="text-white bg-sky-700">
                    <th class="px-4 py-2 text-center border border-sky-600">No</th>
                    <th class="px-4 py-2 text-left border border-sky-600 md:text-center">Nama Mapel</th>
                    <th class="px-4 py-2 text-left border border-sky-600 md:text-center">Guru Pengampu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mapel ?? [] as $index => $m)
                    <tr class="transition hover:bg-sky-50">
                        <td class="px-4 py-2 text-center border border-gray-200">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border border-gray-200">{{ $m->mapel }}</td>
                        <td class="px-4 py-2 border border-gray-200">{{ $m->guru->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-3 text-center text-gray-500 border border-gray-200">Belum ada data mapel</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> --}}

<!-- Script Search -->
{{-- <script>
    document.getElementById('searchMapel').addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll("#mapelTable tbody tr");

        rows.forEach(row => {
            const mapel = row.cells[1]?.textContent.toLowerCase() || "";
            const guru = row.cells[2]?.textContent.toLowerCase() || "";

            row.style.display = (mapel.includes(filter) || guru.includes(filter)) ? "" : "none";
        });
    });
</script> --}}

{{-- ========================================================= --}}

<div>
    <!-- Header -->
    <div class="p-4 mb-4 text-center bg-white rounded-md shadow md:bg-none md:rounded-none md:shadow-none">
        <h2 class="mb-4 text-lg font-bold text-gray-800 md:font-extrabold md:text-xl">
            Daftar Mata Pelajaran
            <span class="hidden font-medium text-gray-600 md:inline-block">dan Guru Pengampu</span>
            <span class="hidden text-sky-900 md:inline-block">
                | {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |
            </span>
        </h2>

        <!-- Search Box -->
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 text-gray-500 border-r">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35m0 0A7.5 7.5 0
                             1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                </svg>
            </span>
            <input type="text" id="searchMapel"
                placeholder="Cari nama guru atau nama mapel..."
                class="w-full py-2 pl-12 transition border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
        </div>
    </div>

    <!-- Cards Grid -->
    <div id="mapelList" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
        @forelse ($mapel ?? [] as $m)
            <div class="relative p-5 transition-transform transform bg-white border shadow-sm rounded-xl hover:shadow-md hover:-translate-y-1"
                 data-mapel="{{ strtolower($m->mapel ?? '') }}"
                 data-guru="{{ strtolower($m->guru->user->name ?? '-') }}">

                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $m->mapel }}</h3>
                    <span class="hidden px-2 py-1 text-xs font-medium rounded-full md:inline-block text-sky-700 bg-sky-100">
                        {{ $loop->iteration }}
                    </span>
                </div>

                <div class="text-sm text-gray-700">
                    <p class="font-medium text-gray-800">Guru Pengampu:</p>
                    <p class="text-gray-600">{{ $m->guru->user->name ?? '-' }}</p>
                </div>

                <div class="absolute inset-x-0 bottom-0 h-1 rounded-b-xl bg-gradient-to-r from-sky-400 to-sky-600"></div>
            </div>
        @empty
            <div class="p-4 text-center text-gray-500 border rounded-lg bg-gray-50 col-span-full">
                Belum ada data mata pelajaran.
            </div>
        @endforelse
    </div>
</div>

<!-- Script Search -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('searchMapel');
        const cards = document.querySelectorAll('#mapelList > div[data-mapel]');

        input.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();

            cards.forEach(card => {
                const mapel = card.dataset.mapel || '';
                const guru = card.dataset.guru || '';
                card.style.display = (mapel.includes(filter) || guru.includes(filter)) ? '' : 'none';
            });
        });
    });
</script>

