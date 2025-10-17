<div class="p-4 bg-white rounded shadow">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-bold md:text-lg">Daftar Materi</h2>
    </div>

    <!-- Search Box -->
    <div class="relative mb-6">
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

    <!-- Cards Container -->
    <div id="materiList" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($materis ?? [] as $m)
            <div class="p-4 transition-shadow bg-white border rounded-lg shadow-sm hover:shadow-md" data-judul="{{ strtolower($m->judul ?? '') }}" data-mapel="{{ strtolower($m->mapel->mapel ?? '') }}" data-materi="{{ strtolower(strip_tags($m->materi ?? '')) }}">
                <div class="mb-2 text-sm text-gray-500">{{ $m->mapel->mapel ?? '-' }}</div>
                <h3 class="mb-2 text-lg font-semibold text-gray-800">{{ $m->judul ?? '-' }}</h3>
                <div class="mb-3 text-sm text-gray-700 line-clamp-3">
                    {!! Str::limit(strip_tags($m->materi), 150, '...') ?: 'Tidak ada materi / Hanya berupa file!' !!}
                </div>
                <div class="flex items-center justify-between">
                    <x-detail-materi :title="$m->judul ?? '-' ">
                        {!! $m->materi ?? '' !!}
                    </x-detail-materi>

                    @if($m->file_path)
                        <a href="{{ route('siswa.view_file_materi', $m->id) }}"
                           class="px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                            Lihat File
                        </a>
                    @else
                        <span class="text-xs text-gray-400">Tidak Ada File</span>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500">Belum ada data materi.</p>
        @endforelse
    </div>
</div>

<!-- Script Search -->
<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    const filter = this.value.toLowerCase();
    const cards = document.querySelectorAll("#materiList > div");

    cards.forEach(card => {
        const judul = card.dataset.judul || "";
        const mapel = card.dataset.mapel || "";
        const materi = card.dataset.materi || "";

        if (judul.includes(filter) || mapel.includes(filter) || materi.includes(filter)) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });
});
</script>
