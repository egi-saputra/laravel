<!-- CARD -->
<div>
    <!-- Search & Filter -->
        <h2 class="mb-6 px-1 text-2xl font-bold text-gray-800">
            <i class="mr-2 bi bi-calendar2-week text-amber-500"></i> Daftar Jadwal Mengajar
        </h2>

        <div class="mb-10">
            <form method="GET" class="flex flex-col gap-3 mb-6 rounded-md">
                <select name="hari" class="flex-1 px-3 py-2 border rounded-lg">
                    <option value="">Pilih Hari</option>
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                        <option value="{{ $h }}" {{ request('hari')==$h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                </select>

                <input type="text" name="guru" placeholder="Nama Guru"
                    value="{{ request('guru') }}" class="px-3 py-2 border rounded-lg">

                <input type="text" name="kelas" placeholder="Unit Kelas"
                    value="{{ request('kelas') }}" class="px-3 py-2 border rounded-lg">

                    <div class="flex justify-center w-full gap-3">
                        <button type="submit"
                            class="flex w-full items-center justify-center px-4 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        <a href="{{ route('public.jadwal_guru.index') }}"
                            class="flex w-full items-center justify-center px-4 py-2 text-sm font-medium text-white transition bg-gray-600 rounded-lg hover:bg-gray-700">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </a>
                    </div>
            </form>
        </div>

    <!-- Card List -->
    @if($paginatedJadwal->count())
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
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
