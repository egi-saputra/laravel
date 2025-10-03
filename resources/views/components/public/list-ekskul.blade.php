<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="mb-2 text-lg font-bold md:mb-4">
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
        <input type="text" id="searchEkskul" placeholder="Cari nama ekskul atau pembina ekskul..."
               class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300 md:w-auto">
    </div>

    <!-- Tabel Daftar Ekskul -->
    <table class="w-full border border-collapse" id="ekskulTable">
        <thead>
            <tr class="bg-gray-100">
                <th class="w-16 px-4 py-2 text-center border whitespace-nowrap">No</th>
                <th class="px-4 py-2 border whitespace-nowrap">Nama Ekskul</th>
                <th class="px-4 py-2 border whitespace-nowrap">Nama Pembina</th>
            </tr>
        </thead>
        <tbody>
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

<!-- Script Search -->
<script>
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
</script>
