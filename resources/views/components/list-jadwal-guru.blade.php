@php
// Default jika data sekolah kosong
$sekolah = $sekolah ?? (object) [];

// Isi default
$sekolah->nama_sekolah = $sekolah->nama_sekolah ?? 'SMA Negeri 1 Contoh';
$sekolah->alamat       = $sekolah->alamat ?? 'Jl. Pendidikan No. 123, Kota Contoh';
$sekolah->telepon      = $sekolah->telepon ?? '(021) 123456';
$sekolah->email        = $sekolah->email ?? 'info@smanc1contoh.sch.id';

// Ambil file logo pertama yang ada di folder
$logoFolder = storage_path('app/public/logo_sekolah/');
$logoFiles = glob($logoFolder . '*'); // ambil semua file

if (!empty($logoFiles) && file_exists($logoFiles[0])) {
    $logoBase64 = base64_encode(file_get_contents($logoFiles[0]));
} else {
    // fallback jika folder kosong
    $defaultLogo = storage_path('app/public/logo_sekolah/default-logo.png');
    $logoBase64 = file_exists($defaultLogo) ? base64_encode(file_get_contents($defaultLogo)) : '';
}
@endphp

{{-- ============================================================================= --}}

<div class="p-4 bg-white rounded shadow">
    <div class="p-4 bg-white rounded shadow">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold">Daftar Jadwal Mengajar</h2>
        <div class="flex gap-2">
            <button id="exportPDF" type="button" class="flex items-center px-3 py-2 text-white bg-red-800 rounded hover:bg-red-900">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
            </button>

        </div>
    </div>

    <!-- Global Search -->
    <div class="relative mb-4 md:col-span-2">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 border-r text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
            </svg>
        </span>
        <input type="text" id="searchInput" placeholder="Cari..."
            class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300">
    </div>

    <!-- Filter/Sort -->
    <div class="grid grid-cols-1 gap-2 mb-4 md:grid-cols-5">

        <!-- Filter Hari -->
        <select id="filterHari" class="px-3 py-2 border rounded">
            <option value="">Semua Hari</option>
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h)
                <option value="{{ $h }}">{{ $h }}</option>
            @endforeach
        </select>

        <!-- Filter Sesi -->
        <input type="text" id="filterSesi" placeholder="Filter Sesi" class="px-3 py-2 border rounded">

        <!-- Filter Guru / Kelas -->
        <input type="text" id="filterGuru" placeholder="Filter Guru" class="px-3 py-2 border rounded">
        <input type="text" id="filterKelas" placeholder="Filter Kelas" class="px-3 py-2 border rounded">
    </div>

    <div class="overflow-x-auto md:overflow-x-visible">
        <table class="w-full border border-collapse" id="jadwalGuruTable">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">Hari</th>
                    <th class="px-4 py-2 border">Sesi</th>
                    <th class="px-4 py-2 border">Jam</th>
                    <th class="px-4 py-2 border">Nama Guru</th>
                    <th class="px-4 py-2 border">Ruang Kelas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal->unique(function($item) {
                    return $item->hari
                        . $item->sesi
                        . $item->jam_mulai
                        . $item->jam_selesai
                        . $item->guru_id
                        . $item->kelas_id;
                }) as $j)
                <tr>
                    <td class="px-4 py-2 text-center border">{{ $j->hari }}</td>
                    <td class="px-4 py-2 text-center border">{{ $j->sesi }}</td>
                    <td class="px-4 py-2 text-center border">
                        {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                    </td>
                    <td class="px-4 py-2 border">{{ $j->guru->user->name ?? $j->guru->nama ?? '-' }}</td>
                    <td class="px-4 py-2 text-center border">{{ $j->kelas->kelas ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-2 text-center">Belum ada data jadwal guru</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    // Filter + Search
    function applyFilters() {
        let search = document.getElementById('searchInput').value.toLowerCase();
        let filterHari = document.getElementById('filterHari').value.toLowerCase();
        let filterSesi = document.getElementById('filterSesi').value.toLowerCase();
        let filterGuru = document.getElementById('filterGuru').value.toLowerCase();
        let filterKelas = document.getElementById('filterKelas').value.toLowerCase();

        let rows = document.querySelectorAll("#jadwalGuruTable tbody tr");

        rows.forEach(row => {
            let hari = row.cells[0].textContent.toLowerCase();
            let sesi = row.cells[1].textContent.toLowerCase();
            let guru = row.cells[3].textContent.toLowerCase();
            let kelas = row.cells[4].textContent.toLowerCase();
            let text = row.textContent.toLowerCase();

            if ((search === "" || text.includes(search)) &&
                (filterHari === "" || hari.includes(filterHari)) &&
                (filterSesi === "" || sesi.includes(filterSesi)) &&
                (filterGuru === "" || guru.includes(filterGuru)) &&
                (filterKelas === "" || kelas.includes(filterKelas))) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    ['searchInput','filterHari','filterSesi','filterGuru','filterKelas'].forEach(id => {
        document.getElementById(id).addEventListener('input', applyFilters);
    });

    // Data sekolah dari controller
    const sekolah = {!! json_encode($sekolah, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) !!};
    sekolah.logo_base64 = '{{ $logoBase64 }}';

    console.log(sekolah);

    // Export PDF
    document.getElementById('exportPDF').addEventListener('click', () => {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const margin = 14;
    const pageWidth = doc.internal.pageSize.getWidth();
    let yPos = 18;

    // ----- KIRI: Logo -----
    const logoWidth = 24;
    const logoHeight = 24;
    if (sekolah.logo_base64 && sekolah.logo_base64.length > 0) {
        doc.addImage(
            "data:image/png;base64," + sekolah.logo_base64,
            'PNG',
            margin,
            yPos - 4,
            logoWidth,
            logoHeight
        );
    }

    // ----- TENGAH: Nama Sekolah & Info -----
    let tengahX = margin + logoWidth + 5;
    let tengahY = yPos;

    // Yayasan
    doc.setFont('times', 'bold');
    doc.setFontSize(14);
    doc.setTextColor(0, 0, 255);
    doc.text('YAYASAN NUSANTARA CITAYAM', tengahX, tengahY, { align: 'left' });

    tengahY += 6; // jarak antar baris
    // Nama sekolah
    doc.setFontSize(16);
    doc.setTextColor(255, 0, 0);
    doc.text(sekolah.nama_sekolah ?? '-', tengahX, tengahY, { align: 'left' });

    tengahY += 5;
    doc.setFont('times', 'normal');
    doc.setFontSize(13);
    doc.setTextColor(0, 180, 0);
    doc.text('BISNIS MANAJEMEN TERAKREDITASI "B"', tengahX, tengahY, { align: 'left' });

    tengahY += 5;
    doc.setFontSize(8.8);
    doc.setTextColor(255, 0, 0);
    doc.text("Bisnis Daring dan Pemasaran", tengahX, tengahY, { align: 'left' });
    doc.setTextColor(0, 0, 255);
    doc.text(
        "| Otomatisasi dan Tata Kelola Perkantoran",
        tengahX + doc.getTextWidth("Bisnis Daring dan Pemasaran "),
        tengahY,
        { align: 'left' }
    );

    tengahY += 5;
    doc.setFontSize(9);
    doc.setTextColor(75, 0, 130);
    doc.text(
        `No. Izin: ${sekolah.no_izin ?? '-'} | NPSN: ${sekolah.npsn ?? '-'} | NSS: ${sekolah.nss ?? '-'}`,
        tengahX,
        tengahY,
        { align: 'left' }
    );

    // ----- KANAN: Alamat & Kontak -----
    let kananX = pageWidth - margin - 54;
    let kananY = yPos - 1.5;
    doc.setFontSize(8);
    let alamatLines = [
        sekolah.alamat ?? '-',
        `RT ${sekolah.rt ?? '-'} RW ${sekolah.rw ?? '-'} ${sekolah.kelurahan ?? '-'},`,
        `Kec. ${sekolah.kecamatan ?? '-'}, Kab. ${sekolah.kabupaten_kota ?? '-'},`,
        `Prov. ${sekolah.provinsi ?? '-'}. Kode Pos: ${sekolah.kode_pos ?? '-'}.`,
        `Telp: ${sekolah.telepon ?? '-'}`,
        `Email: ${sekolah.email ?? '-'}`,
        `Web: ${sekolah.website ?? '-'}`,
    ];
    alamatLines.forEach((line, idx) => {
        doc.text(line, kananX, kananY + idx * 3.7, { align: 'left' });
    });

    // ----- Garis Kop -----
    let lineY = Math.max(tengahY, kananY + alamatLines.length * 3.7) + 2;
    doc.setLineWidth(0.6);
    doc.line(margin, lineY, pageWidth - margin, lineY);
    let lineY2 = lineY + 1;
    doc.setLineWidth(0.1);
    doc.line(margin, lineY2, pageWidth - margin, lineY2);

    // ----- Judul Tabel -----
    let yTableTitle = lineY2 + 8;
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(12);
    doc.setTextColor(0, 0, 0);
    doc.text("DAFTAR JADWAL MENGAJAR GURU", pageWidth / 2, yTableTitle, { align: 'center' });

    // ----- Ambil Data Tabel -----
    const table = document.getElementById('jadwalGuruTable');
    const headers = ["Hari", "Sesi", "Jam", "Guru", "Ruang Kelas", "Jumlah Jam"];
    const rows = [];
    table.querySelectorAll('tbody tr').forEach(tr => {
        if (tr.style.display === "none") return;
        const cells = tr.querySelectorAll('td');
        rows.push([
            cells[0].textContent.trim(),
            cells[1].textContent.trim(),
            cells[2].textContent.trim(),
            cells[3].textContent.trim(),
            cells[4].textContent.trim(),
            cells[5].textContent.trim(),
        ]);
    });

    // ----- AutoTable -----
    doc.autoTable({
        head: [headers],
        body: rows,
        startY: yTableTitle + 6, // jarak 6pt dari judul
        theme: 'grid',
        headStyles: { fillColor: [241,245,249], textColor:0, halign:'center', valign:'middle', lineColor:[200,200,200], lineWidth:0.3 },
        styles: { fontSize:10, halign:'center', valign:'middle', lineColor:[200,200,200], lineWidth:0.1, textColor:0 },
        columnStyles: {
            3: { halign: 'left' } // kolom Guru rata kiri
        }
    });

    doc.save('jadwal-guru.pdf');
});


    // Alert
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('alert'))
            Swal.fire({
                icon: '{{ session('alert.type') }}',
                title: '{{ session('alert.title') ?? ucfirst(session('alert.type')) }}',
                @if(session('alert.html'))
                    html: `{!! session('alert.message') !!}`,
                @else
                    text: '{{ session('alert.message') }}',
                @endif
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
        @endif
    });
</script>
