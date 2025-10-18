{{-- <div class="p-6 border shadow-sm bg-gradient-to-br from-slate-50 to-white rounded-2xl border-slate-200" x-data="{}"> --}}
<div -data="{}">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-3 mx-3 mb-3">
        <h1 class="flex items-center gap-2 text-xl font-bold text-slate-800">
            <i class="bi bi-clock"></i>
            Jumlah Jam Mengajar
            <span class="hidden text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span>
        </h1>
        <hr class="w-full border-slate-200 md:hidden">
    </div>

    <!-- Search -->
    <div class="relative mx-2 mb-8">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
            <i class="text-lg bi bi-search"></i>
        </span>
        <input type="text" id="searchInput"
            placeholder="Cari kode guru atau nama guru..."
            class="w-full py-2.5 pl-12 pr-4 border border-slate-300 rounded-xl text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
    </div>

    <!-- Grid Cards -->
    <div class="grid gap-3 md:gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
        @forelse ($guru ?? [] as $g)
            <div class="flex flex-col p-5 transition-all duration-200 border shadow-sm bg-gradient-to-br from-slate-50 to-white rounded-2xl border-slate-200 hover:shadow-lg hover:border-blue-300">
            {{-- <div class="flex flex-col p-5 transition-all duration-200 bg-white border shadow-sm border-slate-200 rounded-xl hover:shadow-lg hover:border-blue-300"> --}}
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-slate-500">No {{ $loop->iteration }}</span>
                    <span class="px-2 py-0.5 text-xs font-medium text-white bg-blue-600 rounded-full">
                        {{ $guruJam[$g->id] ?? 0 }} Jam
                    </span>
                </div>
                <h3 class="mb-1 text-lg font-semibold text-slate-800">{{ $g->name }}</h3>
                <p class="text-sm truncate text-slate-600">{{ $g->email }}</p>
            </div>
        @empty
            <div class="p-5 text-center bg-white border col-span-full text-slate-500 border-slate-200 rounded-xl">
                Belum ada data guru
            </div>
        @endforelse
    </div>
</div>

<!-- Script Search -->
<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let cards = document.querySelectorAll(".grid > div");

        cards.forEach(card => {
            let no = card.querySelector('span.text-sm')?.textContent.toLowerCase() || "";
            let nama = card.querySelector('h3')?.textContent.toLowerCase() || "";

            if (no.includes(filter) || nama.includes(filter)) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    });
</script>
