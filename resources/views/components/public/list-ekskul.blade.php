{{-- TABEL --}}

{{-- <div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="mb-2 text-base font-bold md:text-lg md:mb-4">
            Daftar Ekstrakurikuler <span class="hidden capitalize text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span>
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
        <input type="text" id="searchEkskul" placeholder="Cari ekskul atau pembina ..."
               class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300 md:w-auto">
    </div>

    <!-- Tabel Daftar Ekskul -->
    <div class="overflow-x-auto md:overflow-x-visible">
        <table class="w-full border border-collapse" id="ekskulTable">
            <thead class="text-sm md:text-base">
                <tr class="bg-gray-100">
                    <th class="w-16 px-4 py-2 text-center border whitespace-nowrap">No</th>
                    <th class="px-4 py-2 text-left border md:text-center whitespace-nowrap">Nama Ekskul</th>
                    <th class="px-4 py-2 text-left border md:text-center whitespace-nowrap">Nama Pembina</th>
                </tr>
            </thead>
            <tbody class="text-sm md:text-base">
                @forelse ($ekskul ?? [] as $e)
                    <tr>
                        <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $e->nama_ekskul }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $e->pembina->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-2 text-center">Belum ada data ekskul</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> --}}

<!-- Script Search -->
{{-- <script>
    document.getElementById('searchEkskul').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#ekskulTable tbody tr");

        rows.forEach(row => {
            let namaEkskul = row.cells[1]?.textContent.toLowerCase();
            let pembina = row.cells[2]?.textContent.toLowerCase();

            if (namaEkskul.includes(filter) || pembina.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script> --}}

{{-- ========================================================== --}}

{{-- CARD --}}

<div>
    <div class="mb-4">
        <h2 class="mb-4 ml-2 text-lg font-bold text-gray-800 text-start md:font-extrabold md:text-xl">
            <i class="mr-2 text-2xl text-amber-600 bi bi-trophy"></i> Daftar Ekstrakurikuler
            <span class="hidden capitalize text-sky-900 md:inline-block">
                | {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |
            </span>
        </h2>
        {{-- <hr class="mb-4"> --}}

        <!-- Search Box -->
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 text-gray-500 border-r">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                          d="M21 21l-4.35-4.35m0 0A7.5 7.5 0
                             1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                </svg>
            </span>
            <input type="text" id="searchEkskul"
                   placeholder="Cari ekskul atau pembina ..."
                   class="w-full py-2 pl-12 transition border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-800">
        </div>
    </div>


    <!-- Cards Grid -->
    <div id="ekskulList" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($ekskul ?? [] as $e)
            <div class="p-4 transition-shadow bg-white border rounded-lg shadow-sm hover:shadow-md"
                 data-namaekskul="{{ strtolower($e->nama_ekskul ?? '') }}"
                 data-pembina="{{ strtolower($e->pembina->user->name ?? '') }}">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $e->nama_ekskul }}
                    </h3>
                    <span class="px-2 py-1 text-xs font-medium text-indigo-600 bg-indigo-100 rounded-full">
                        Ekskul
                    </span>
                </div>

                <div class="mt-1 text-sm text-gray-700">
                    <span class="font-medium text-gray-800">Pembina:</span>
                    {{ $e->pembina->user->name ?? '-' }}
                </div>

                <div class="mt-3 text-xs text-gray-400">
                    ID Ekskul: {{ $e->id ?? '-' }}
                </div>
            </div>
        @empty
            <div class="p-4 text-center text-gray-500 border rounded-lg bg-gray-50 col-span-full">
                Belum ada data ekskul.
            </div>
        @endforelse
    </div>
</div>

<!-- Script Search -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('searchEkskul');
        const cards = document.querySelectorAll("#ekskulList > div");

        input.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();

            cards.forEach(card => {
                const namaEkskul = card.dataset.namaekskul || "";
                const pembina = card.dataset.pembina || "";

                if (namaEkskul.includes(filter) || pembina.includes(filter)) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        });
    });
</script>
