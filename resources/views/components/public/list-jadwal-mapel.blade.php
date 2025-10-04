<div class="p-4 bg-white rounded shadow">
    <div class="flex items-center justify-between mb-4">
        <h2 class="w-full mr-0 text-lg font-bold md:mr-4 md:w-auto">Daftar Jadwal Pelajaran</h2>
        <div class="flex gap-2">
            <button id="exportPDF" type="button" class="flex items-center px-3 py-2 text-white bg-red-800 rounded hover:bg-red-900">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export
            </button>
        </div>
    </div>

    <!-- Search & Filter -->
    <form method="GET" class="grid grid-cols-1 gap-2 mb-4 md:grid-cols-5">
        <!-- Search -->
        <div class="relative md:col-span-2">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 border-r text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}"
                class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300">
        </div>

        <!-- Filter Hari -->
        <select name="hari" class="px-3 py-2 border rounded">
            <option value="">Semua Hari</option>
            @foreach($hari as $h)
                <option value="{{ $h }}" {{ request('hari')==$h?'selected':'' }}>{{ $h }}</option>
            @endforeach
        </select>

        <!-- Filter Mapel -->
        <input type="text" name="mapel" placeholder="Filter Mapel" value="{{ request('mapel') }}" class="px-3 py-2 border rounded">
        <!-- Filter Kelas -->
        <input type="text" name="kelas" placeholder="Filter Kelas" value="{{ request('kelas') }}" class="px-3 py-2 border rounded">

        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                <i class="bi bi-funnel"></i> Filter
            </button>
            <a href="{{ route('public.jadwal_mapel.index') }}" class="px-4 py-2 text-white rounded bg-slate-700 hover:bg-slate-800">
                <i class="bi bi-arrow-clockwise"></i> Reset
            </a>
        </div>
    </form>

    <div class="overflow-x-auto md:overflow-x-visible">
        <table class="w-full border border-collapse" id="jadwalGuruTable">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border whitespace-nowrap">Hari</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Jam</th>
                    <th class="px-4 py-2 text-left border md:text-center whitespace-nowrap">Nama Mapel</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Unit Kelas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paginatedJadwal as $j)
                <tr>
                    <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $j['hari'] }}</td>
                    <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $j['jam'] }}</td>
                    <td class="px-4 py-2 border whitespace-nowrap">{{ $j['mapel'] }}</td>
                    <td class="px-4 py-2 border whitespace-nowrap">{{ $j['kelas'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-2 text-center">Belum ada data jadwal mapel</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $paginatedJadwal->appends(request()->query())->links() }}
    </div>
</div>

@php
$logoBase64 = $logoBase64 ?? '';
$logoMime   = $logoMime ?? 'png';
@endphp

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

        if(sekolah.logo_base64){
            const mime = (sekolah.logo_mime || 'png').toLowerCase();
            const format = (mime==='jpeg'||mime==='jpg')?'JPEG':'PNG';
            doc.addImage(`data:image/${mime};base64,${sekolah.logo_base64}`, format, margin, yPos-4, 24, 24);
        }

        let tengahX = margin + 24 + 5;
        let tengahY = yPos;

        doc.setFont('times','bold'); doc.setFontSize(14); doc.setTextColor(0,0,255);
        doc.text('YAYASAN NUSANTARA CITAYAM', tengahX, tengahY);

        tengahY += 5; doc.setFontSize(16); doc.setTextColor(255,0,0);
        doc.text(sekolah.nama_sekolah ?? '-', tengahX, tengahY);

        tengahY += 5; doc.setFont('times','normal'); doc.setFontSize(13); doc.setTextColor(0,180,0);
        doc.text(sekolah.akreditasi ?? '-', tengahX, tengahY);

        tengahY += 5; doc.setFontSize(8.8); doc.setTextColor(255,0,0);
        doc.text("Bisnis Daring dan Pemasaran", tengahX, tengahY);

        doc.setTextColor(0,0,255);
        doc.text("| Otomatisasi dan Tata Kelola Perkantoran", tengahX + doc.getTextWidth("Bisnis Daring dan Pemasaran "), tengahY);

        tengahY += 5; doc.setFontSize(9); doc.setTextColor(75,0,130);
        doc.text(`No. Izin: ${sekolah.no_izin ?? '-'} | NPSN: ${sekolah.npsn ?? '-'} | NSS: ${sekolah.nss ?? '-'}`, tengahX, tengahY);

        let kananX = pageWidth - margin - 54;
        let kananY = yPos - 1.5;
        const alamatLines = [
            sekolah.alamat ?? '-',
            `RT ${sekolah.rt ?? '-'} RW ${sekolah.rw ?? '-'} ${sekolah.kelurahan ?? '-'}`,
            `Kec. ${sekolah.kecamatan ?? '-'}, Kab. ${sekolah.kabupaten_kota ?? '-'}`,
            `Prov. ${sekolah.provinsi ?? '-'}. Kode Pos: ${sekolah.kode_pos ?? '-'}`,
            `Telp: ${sekolah.telepon ?? '-'}`,
            `Email: ${sekolah.email ?? '-'}`,
            `Web: ${sekolah.website ?? '-'}`,
        ];
        alamatLines.forEach((line, idx) => doc.text(line, kananX, kananY + idx*3.7));

        let lineY = Math.max(tengahY, kananY + alamatLines.length*3.5) + 0.5;
        doc.setLineWidth(0.6); doc.line(margin,lineY,pageWidth-margin,lineY);
        doc.setLineWidth(0.1); doc.line(margin,lineY+1,pageWidth-margin,lineY+1);

        let yTableTitle = lineY + 10;
        doc.setFont('helvetica','bold'); doc.setFontSize(12); doc.setTextColor(0,0,0);
        doc.text("JADWAL MATA PELAJARAN", margin, yTableTitle);

        const tableRows = [];
        document.querySelectorAll('#jadwalGuruTable tbody tr').forEach(tr => {
            if(tr.style.display==='none') return;
            const cells = tr.querySelectorAll('td');
            tableRows.push([cells[0].textContent, cells[1].textContent, cells[2].textContent, cells[3].textContent]);
        });

        doc.autoTable({
            head: [["Hari","Jam","Mapel","Kelas"]],
            body: tableRows,
            startY: yTableTitle+5,
            theme:'grid',
            headStyles:{fillColor:[241,245,249], textColor:0, halign:'center', valign:'middle', lineColor:[200,200,200], lineWidth:0.3},
            styles:{fontSize:10, halign:'center', valign:'middle', lineColor:[200,200,200], lineWidth:0.1, textColor:0},
            columnStyles:{2:{halign:'left'},3:{halign:'left'}}
        });

        doc.save('Jadwal_Pelajaran.pdf');
    });
});
</script>
