<!-- Tabel Data Guru -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="mb-4 text-lg font-bold">Jumlah Jam Mengajar |  <span class="capitalize text-sky-900">{{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span></h2><hr class="mb-4">
                </div>

                <!-- Search Box -->
                <div class="relative mb-4">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 border-r text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                        </svg>
                    </span>
                    <input type="text" id="searchInput"
                        placeholder="Cari kode guru atau nama guru..."
                        class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300">
                </div>

                <!-- Table -->
                <div class="overflow-x-auto md:overflow-x-visible">
                    <table class="w-full border border-collapse" id="guruTable">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="w-12 px-4 py-2 text-center border">No</th>
                                <th class="px-4 py-2 border">Nama Lengkap</th>
                                <th class="px-4 py-2 border">Email Guru</th>
                                <th class="px-4 py-2 border">Jumlah Jam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($guru ?? [] as $g)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-center border">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 border">{{ $g->name }}</td>
                                <td class="px-4 py-2 border">{{ $g->email }}</td>
                                <td class="px-4 py-2 text-center border">{{ $guruJam[$g->id] ?? 0 }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-2 text-center">Belum ada data guru</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#guruTable tbody tr");

        rows.forEach(row => {
            let kode = row.cells[0]?.textContent.toLowerCase(); // kolom Kode Guru
            let nama = row.cells[1]?.textContent.toLowerCase(); // kolom Nama Guru

            if (kode.includes(filter) || nama.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
