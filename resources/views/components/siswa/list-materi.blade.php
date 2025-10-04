<div class="p-4 bg-white rounded shadow">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-bold md:text-lg">Daftar Materi</h2>
    </div>

    <!-- Search Box -->
    <div class="relative mb-4">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 border-r text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="3"
                      d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
            </svg>
        </span>
        <input type="text" id="searchInput"
               placeholder="Cari judul materi, mapel, isi materi..."
               class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300">
    </div>

    <!-- Table -->
    <div class="overflow-x-auto md:overflow-x-visible">
        <table class="w-full border border-collapse" id="materiTable">
            <thead>
                <tr class="bg-gray-100">
                    <th class="w-12 px-4 py-2 text-sm text-center border md:text-base">No</th>
                    <th class="px-4 py-2 text-sm border md:text-base whitespace-nowrap">Judul Materi</th>
                    <th class="px-4 py-2 text-sm text-center border md:text-base whitespace-nowrap">Mapel</th>
                    <th class="px-4 py-2 text-sm border md:text-base whitespace-nowrap">Isi Materi</th>
                    <th class="px-4 py-2 text-sm border md:text-base whitespace-nowrap">File Terkait</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($materis ?? [] as $m)
                    <tr class="hover:bg-gray-50" x-data="{ open: false, showModal: false }">
                        <td class="px-4 py-2 text-sm text-center border md:text-base">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 text-sm border md:text-base whitespace-nowrap">{{ $m->judul ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm border md:text-base whitespace-nowrap">{{ $m->mapel->mapel ?? '-' }}</td>
                        {{-- <td class="px-4 py-2 border">
                            {{ strip_tags($m->materi) ?: 'Tidak ada materi / Hanya berupa file!' }}
                        </td> --}}
                        <td class="px-4 py-2 text-sm border md:text-base whitespace-nowrap">
                            <div class="text-center">
                                <x-detail-materi :title="$m->judul">
                                    {!! $m->materi !!}
                                </x-detail-materi>
                            </div>
                        </td>
                        <td class="px-4 py-2 text-center border whitespace-nowrap">
                            @if($m->file_path)
                                <a href="{{ route('guru.view_file_materi', $m->id) }}"
                                   class="px-4 py-1 text-white bg-blue-600 border-transparent rounded-md hover:bg-blue-700">
                                    Lihat Detail
                                </a>
                            @else
                                Tidak Ada File!
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-2 text-center">
                            Belum ada data materi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Script Search -->
<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#materiTable tbody tr");

        rows.forEach(row => {
            let judul  = row.cells[1]?.textContent.toLowerCase() || "";
            let mapel  = row.cells[2]?.textContent.toLowerCase() || "";
            let materi = row.cells[3]?.textContent.toLowerCase() || "";
            let file   = row.cells[4]?.textContent.toLowerCase() || "";

            if (
                judul.includes(filter) ||
                mapel.includes(filter) ||
                materi.includes(filter) ||
                file.includes(filter)
            ) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
