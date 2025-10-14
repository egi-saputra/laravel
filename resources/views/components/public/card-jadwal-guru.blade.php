<!-- CARD -->
<div>
    <div class="flex justify-center p-4 mb-4 bg-white rounded-md">
        <h2 class="text-lg font-bold text-gray-800">
            Jadwal Mengajar Guru
        </h2>

    </div>

    <!-- Search & Filter -->
    <form method="GET" class="grid grid-cols-1 gap-2 mb-6 md:grid-cols-6">
        {{-- <div class="relative md:col-span-2">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0
                        1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari guru / mapel..."
                value="{{ request('search') }}"
                class="w-full py-2 pl-10 pr-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div> --}}

        <select name="hari" class="px-3 py-2 border rounded-lg">
            <option value="">Semua Hari</option>
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                <option value="{{ $h }}" {{ request('hari')==$h ? 'selected' : '' }}>{{ $h }}</option>
            @endforeach
        </select>

        {{-- <input type="text" name="sesi" placeholder="Sesi"
            value="{{ request('sesi') }}" class="px-3 py-2 border rounded-lg"> --}}

        {{-- <input type="text" name="guru" placeholder="Guru"
            value="{{ request('guru') }}" class="px-3 py-2 border rounded-lg"> --}}

        {{-- <input type="text" name="kelas" placeholder="Kelas"
            value="{{ request('kelas') }}" class="px-3 py-2 border rounded-lg"> --}}

        <div class="flex justify-end gap-2 mt-2">
            <button type="submit"
                class="flex items-center justify-center w-1/2 px-4 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            <a href="{{ route('public.jadwal_guru.index') }}"
                class="flex items-center justify-center w-1/2 px-4 py-2 text-sm font-medium text-white transition bg-gray-600 rounded-lg hover:bg-gray-700">
                <i class="bi bi-arrow-clockwise me-1"></i> Reset
            </a>
        </div>
    </form>

    {{-- <div class="flex justify-end w-full">
    <button id="exportPDF" type="button" class="flex items-center self-start px-4 py-2 mb-4 text-sm font-medium text-white transition bg-red-700 rounded hover:bg-red-800 md:self-auto">
        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
    </button>
    </div> --}}

    <!-- Card List -->
    @if($paginatedJadwal->count())
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($paginatedJadwal as $gj)
                <div class="p-4 transition bg-white border rounded-lg shadow hover:shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-3 py-1 text-sm font-medium text-white rounded-full bg-sky-600">
                            {{ $gj['hari'] }}
                        </span>
                        <span class="text-sm font-semibold text-gray-600">{{ $gj['jam'] }}</span>
                    </div>
                    <h3 class="mb-1 text-lg font-semibold text-gray-800">{{ $gj['mapel'] }}</h3>
                    <p class="text-sm text-gray-700">
                        <i class="bi bi-person-fill text-sky-600"></i>
                        {{ $gj['guru'] }}
                    </p>
                    <p class="mt-2 text-sm text-gray-600">
                        <i class="text-indigo-600 bi bi-building-fill"></i>
                        {{ $gj['kelas'] }}
                    </p>
                </div>
            @endforeach
        </div>
    @else
        <p class="py-4 text-center text-gray-600">Belum ada data jadwal guru.</p>
    @endif

    <!-- Pagination Tailwind -->
    <div class="mt-6">
        {{ $paginatedJadwal->links('pagination::tailwind') }}
    </div>
</div>

<!-- Pagination Tailwind -->
{{-- <div class="hidden mt-6 md:block">
    {{ $paginatedJadwal->links('pagination::tailwind') }}
</div> --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sekolah = {!! json_encode($sekolah) !!};
        sekolah.logo_base64 = @json($logoBase64);
        sekolah.logo_mime   = @json($logoMime);

        document.getElementById('exportPDF')?.addEventListener('click', () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const margin = 14;
            const pageWidth = doc.internal.pageSize.getWidth();
            let yPos = 18;

            // ===== Logo =====
            if(sekolah.logo_base64){
                const mime = (sekolah.logo_mime || 'png').toLowerCase();
                const format = (mime === 'jpeg' || mime === 'jpg') ? 'JPEG' : 'PNG';
                const imgData = `data:image/${mime};base64,${sekolah.logo_base64}`;
                doc.addImage(imgData, format, margin, yPos-4, 24, 24);
            }

            // ===== Header Sekolah =====
            doc.setFont('times','bold'); doc.setFontSize(16);
            doc.text(sekolah.nama_sekolah ?? '-', margin + 30, yPos + 4);
            doc.setFontSize(10);
            doc.text(sekolah.alamat ?? '-', margin + 30, yPos + 10);

            yPos += 20;
            doc.setFontSize(12);
            doc.text("Jadwal Mengajar Guru", margin, yPos);

            // ===== Tabel dari Card =====
            const data = [];
            document.querySelectorAll('.grid > div').forEach(card => {
                const hari = card.querySelector('span.bg-sky-600')?.textContent.trim() || '';
                const jam  = card.querySelector('.text-gray-600.font-semibold')?.textContent.trim() || '';
                const mapel = card.querySelector('h3')?.textContent.trim() || '';
                const guru = card.querySelector('.bi-person-fill')?.nextSibling?.textContent.trim() || '';
                const kelas = card.querySelector('.bi-building-fill')?.nextSibling?.textContent.trim() || '';
                data.push([hari, jam, guru, mapel, kelas]);
            });

            doc.autoTable({
                head: [["Hari","Jam","Guru","Mapel","Kelas"]],
                body: data,
                startY: yPos + 5,
                theme: 'grid',
                styles: {fontSize:10, halign:'center'},
                headStyles: {fillColor:[240,240,240]}
            });

            doc.save('Jadwal_Mengajar_Guru.pdf');
        });
    });
</script>
