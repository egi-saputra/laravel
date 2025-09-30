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
        <h2 class="text-lg font-bold">Daftar Jadwal Pelajaran</h2>

        <button id="exportPDF" type="button" class="flex items-center px-3 py-2 text-white bg-red-800 rounded hover:bg-red-900">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
        </button>
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

        <!-- Filter Mapel / Kelas -->
        <input type="text" id="filterMapel" placeholder="Filter Mapel" class="px-3 py-2 border rounded">
        <input type="text" id="filterKelas" placeholder="Filter Kelas" class="px-3 py-2 border rounded">
    </div>

    <div class="overflow-x-auto md:overflow-x-visible">
        <table class="w-full border border-collapse" id="jadwalGuruTable">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">Hari</th>
                    <th class="px-4 py-2 border">Jam</th>
                    <th class="px-4 py-2 border">Nama Mapel</th>
                    <th class="px-4 py-2 border">Kelas</th>
                    <th class="px-4 py-2 text-center border"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal->unique(function($item) {
                    return $item->hari
                        . $item->jam_mulai
                        . $item->jam_selesai
                        . $item->mapel
                        . $item->kelas_id;
                }) as $j)
                <tr>
                    <td class="px-4 py-2 text-center border">{{ $j->hari }}</td>
                    <td class="px-4 py-2 text-center border">
                        {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                    </td>
                    <td class="px-4 py-2 border">
                        {{ $j->mapel->mapel ?? '-' }}
                    </td>
                    <td class="px-4 py-2 text-center border">{{ $j->kelas->kelas ?? '-' }}</td>
                    <td class="px-4 py-2 text-center border">
                        <div x-data="{ open: false, showModal: false }" class="relative inline-block">
                            <!-- Tombol titik 3 -->
                            <button @click="open = !open" class="px-2 py-1 rounded hover:bg-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                                </svg>
                            </button>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-2 text-center">Belum ada data jadwal mapel</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
        function applyFilters() {
            let search = document.getElementById('searchInput').value.toLowerCase();
            let filterHari = document.getElementById('filterHari').value.toLowerCase();
            let filterMapel = document.getElementById('filterMapel').value.toLowerCase();
            let filterKelas = document.getElementById('filterKelas').value.toLowerCase();

            let rows = document.querySelectorAll("#jadwalGuruTable tbody tr");

            rows.forEach(row => {
                let hari   = row.cells[0].textContent.toLowerCase();
                let jam    = row.cells[1].textContent.toLowerCase();
                let mapel  = row.cells[2].textContent.toLowerCase();
                let kelas  = row.cells[3].textContent.toLowerCase();
                let text   = row.textContent.toLowerCase();

                if ((search === "" || text.includes(search)) &&
                    (filterHari === "" || hari.includes(filterHari)) &&
                    (filterMapel === "" || mapel.includes(filterMapel)) &&
                    (filterKelas === "" || kelas.includes(filterKelas))) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

    // Pasang listener ke semua filter dan search
    ['searchInput','filterHari','filterMapel','filterKelas'].forEach(id => {
        document.getElementById(id).addEventListener('input', applyFilters);
    });

    // Data sekolah dari controller
    const sekolah = {!! json_encode($sekolah, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) !!};
    sekolah.logo_base64 = '{{ $logoBase64 }}';

    // Export PDF
    document.getElementById('exportPDF').addEventListener('click', () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const margin = 14;
        const pageWidth = doc.internal.pageSize.getWidth();

        // Tambahkan logo jika ada
        if (sekolah.logo_base64 && sekolah.logo_base64.length > 0) {
            doc.addImage("data:image/png;base64," + sekolah.logo_base64, 'PNG', margin, 13, 22, 22);
        }

        // Nama sekolah (bold, 14pt)
        doc.setFontSize(14);
        doc.setFont('times new roman', 'bold');
        doc.text(sekolah.nama_sekolah, pageWidth / 2, 20, { align: 'center' });

        // Alamat (normal, 11pt)
        doc.setFontSize(11);
        doc.setFont('times new roman', 'normal');
        doc.text(sekolah.alamat, pageWidth / 2, 26, { align: 'center' });

        // Telp & Email
        doc.setFontSize(11);
        // doc.setFont('helvetica', 'normal');
        doc.setFont('times', 'normal');
        doc.text(`Telp: ${sekolah.telepon} | Email: ${sekolah.email}`, pageWidth / 2, 32, { align: 'center' });

        // Garis pembatas
        doc.setLineWidth(0.5);
        doc.line(margin, 36, 196, 36);

        // Judul tabel align-left
        // doc.setFontSize(12);
        // doc.setFont('times new roman', 'bold');
        // doc.text("Daftar Jadwal Mengajar", margin, 45);

        // Judul tabel align-center
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold'); // 'times' di jsPDF, bukan 'times new roman'
        doc.text("DAFTAR JADWAL PELAJARAN", pageWidth / 2, 45, { align: 'center' });

        // Ambil data tabel
        const table = document.getElementById('jadwalGuruTable');
        const headers = ["Hari", "Jam", "Nama Mapel", "Kelas"];
        const rows = [];

        table.querySelectorAll('tbody tr').forEach(tr => {
            if (tr.style.display === "none") return;
            const cells = tr.querySelectorAll('td');
            rows.push([
                cells[0].textContent.trim(),
                cells[1].textContent.trim(),
                cells[2].textContent.trim(),
                cells[3].textContent.trim()
            ]);
        });

        doc.autoTable({
            head: [headers],
            body: rows,
            startY: 50,
            theme: 'grid', // bisa 'striped' juga
            headStyles: {
                fillColor: [241, 245, 249], // slate-100 (abu-abu sangat terang)
                textColor: 0,            // teks putih
                halign: 'center',
                valign: 'middle',
                fillOpacity: 0.4,          // transparansi 60%
                lineColor: [200, 200, 200], // border abu tipis
                lineWidth: 0.3              // ketebalan border tipis
            },
            styles: {
                fontSize: 10,
                halign: 'center',
                valign: 'middle',
                lineColor: [200, 200, 200], // border isi cell (abu-abu tipis)
                lineWidth: 0.1,
                textColor: 0
            },
            columnStyles: {
                2: { halign: 'left' } // Kolom Mapel rata kiri
            }
        });

        doc.save('Jadwal_Pelajaran.pdf');
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
