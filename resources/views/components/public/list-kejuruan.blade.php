<div>
    <div class="p-4 mb-4 text-center bg-white rounded-md shadow md:bg-none md:rounded-none md:shadow-none">
        <h2 class="mb-4 text-lg font-bold text-gray-800 md:font-extrabold md:text-xl">
            Daftar Program Kejuruan
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
            <input type="text" id="searchKejuruan"
                placeholder="Cari nama kejuruan atau kepala program..."
                class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300">
        </div>
    </div>

    <!-- Cards Grid -->
    <div id="kejuruanList" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-2">
        @forelse ($kejuruan ?? [] as $k)
            <div class="p-4 transition-shadow bg-white border rounded-lg shadow-sm hover:shadow-md"
                 data-namakejuruan="{{ strtolower($k->nama_kejuruan ?? '') }}"
                 data-kepalaprogram="{{ strtolower($k->kepalaProgram->user->name ?? $k->kepalaProgram->nama ?? '') }}">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $k->nama_kejuruan }}
                    </h3>
                    <span class="px-2 py-1 text-xs font-medium text-orange-600 bg-orange-100 rounded-full">
                        {{ $k->siswa_count ?? 0 }} siswa
                    </span>
                </div>

                <div class="mt-1 text-sm text-gray-700">
                    <span class="font-medium text-gray-800">Kepala Program:</span><br>
                    {{ $k->kepalaProgram->user->name ?? $k->kepalaProgram->nama ?? 'Tidak Ada' }}
                </div>

                <div class="mt-3 text-xs text-gray-400">
                    ID Kejuruan: {{ $k->id ?? '-' }}
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500">Belum ada data kejuruan.</p>
        @endforelse
    </div>
</div>

<!-- Script Search -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('searchKejuruan');
    const cards = document.querySelectorAll("#kejuruanList > div");

    input.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();

        cards.forEach(card => {
            const nama = card.dataset.namakejuruan || "";
            const kepala = card.dataset.kepalaprogram || "";

            if (nama.includes(filter) || kepala.includes(filter)) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    });
});
</script>
