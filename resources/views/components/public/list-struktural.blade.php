<!-- Tabel Data Struktural -->
{{-- <div>
    <div>
        <h2 class="mb-2 text-base font-bold md:text-lg md:mb-4">Struktur Internal Sekolah <span class="hidden capitalize text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span></h2><hr class="mb-4">
    </div>

    <div class="overflow-x-auto md:overflow-x-visible">
        <table class="w-full border border-collapse" id="strukturalTable">
            <thead class="text-sm md:text-base">
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-center border whitespace-nowrap">No</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Jabatan</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Nama Lengkap</th>
                </tr>
            </thead>
            <tbody class="text-sm md:text-base">
                @forelse($struktural ?? [] as $s)
                    <tr>
                        <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $s->jabatan }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $s->guru->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-2 text-center">Belum ada data struktural</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> --}}

<!-- Card Data Struktural -->
<div>
    <!-- Header -->
    <div class="p-0 mb-4 md:p-4 md:bg-none md:rounded-none md:shadow-none">
        <h2 class="mb-4 ml-2 text-lg font-bold text-gray-800 text-start md:font-extrabold md:text-xl">
            <i class="mr-2 text-2xl text-sky-700 bi bi-diagram-3"></i> Struktur Internal Sekolah
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
            <input type="text" id="searchStruktural"
                placeholder="Cari jabatan atau nama guru..."
                class="w-full py-2 pl-12 border rounded focus:outline-none md:focus:ring focus:ring-none focus:border-amber-600 md:focus:border-blue-300">
        </div>
    </div>

    <!-- Cards Grid -->
    <div id="strukturalList" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($struktural ?? [] as $s)
            <div class="p-4 transition-shadow bg-white border rounded-lg shadow-sm hover:shadow-md"
                data-jabatan="{{ strtolower($s->jabatan ?? '') }}"
                data-nama="{{ strtolower($s->user->name ?? '') }}">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $s->jabatan }}
                    </h3>
                    <span class="px-2 py-1 text-xs font-medium rounded-full text-sky-700 bg-sky-100">
                        {{ $loop->iteration }}
                    </span>
                </div>

                <div class="mt-1 text-sm text-gray-700">
                    <span class="font-medium text-gray-800">Nama:</span><br>
                    {{ $s->user->name ?? '-' }}
                </div>

                <div class="mt-3 text-xs text-gray-400">
                    ID Jabatan: {{ $s->id ?? '-' }}
                </div>
            </div>
        @empty
            <div class="p-4 text-center text-gray-500 border rounded-lg bg-gray-50 col-span-full">
                <p class="text-center text-gray-500">Belum ada data struktural.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Search Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('searchStruktural');
        const cards = document.querySelectorAll("#strukturalList > div");

        input.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();

            cards.forEach(card => {
                const jabatan = card.dataset.jabatan || "";
                const nama = card.dataset.nama || "";

                if (jabatan.includes(filter) || nama.includes(filter)) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        });
    });
</script>
