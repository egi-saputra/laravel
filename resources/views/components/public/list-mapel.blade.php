<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="mb-4 text-lg font-bold">
            Daftar Mata Pelajaran Dan Guru Pengampu |
            <span class="capitalize text-sky-900">
                {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }}
            </span> |
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
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-center border">No</th>
                    <th class="px-4 py-2 border">Nama Mapel</th>
                    <th class="px-4 py-2 border">Guru Pengampu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mapel ?? [] as $index => $m)
                    <tr>
                        <td class="px-4 py-2 text-center border">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border">{{ $m->mapel }}</td>
                        <td class="px-4 py-2 border">{{ $m->guru->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-2 text-center">Belum ada data mapel</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Script Search -->
<script>
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
</script>
