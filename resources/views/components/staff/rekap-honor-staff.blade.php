@props(['rekap', 'uangJam', 'uangApel', 'uangUpacara', 'profil', 'periodeBulan'])

<div class="flex flex-col gap-4 p-6 bg-white rounded shadow-md">

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold">Hasil Rekap Honor Staff / Karyawan</h2>
        <button id="exportPdfBtn" class="px-4 py-2 text-white bg-red-700 rounded hover:bg-red-800">
            <i class="bi bi-file-earmark-pdf-fill"></i> Export PDF
        </button>
    </div>

    <p class="font-semibold text-red-600">Keterangan :</p>
    <p class="mb-4">
        Logika Perhitungan Jumlah Total =
        <span class="font-semibold">
            (Jumlah Hadir × Uang Honor) + (Jumlah Apel Pagi × Uang Apel) + (Jumlah Upacara × Uang Upacara)
        </span>
    </p>

    <div class="overflow-x-auto" id="rekapTableWrapper">
        <table class="min-w-full overflow-x-auto border border-gray-300" id="rekapTable">
            <thead class="bg-gray-100 border">
                <tr class="text-center">
                    <th class="px-4 py-2 border whitespace-nowrap">Nama Staff</th>
                    <th class="px-4 py-2 border">Jumlah Kehadiran</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Apel Pagi</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Upacara</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Total</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Paraf</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekap as $index => $row)
                <tr class="text-center">
                    <td class="px-4 py-2 text-left border whitespace-nowrap">{{ $row->name }}</td>
                    <td class="px-4 py-2 border" data-base="{{ $row->jumlah_hadir }}">{{ $row->jumlah_hadir ?: '' }}</td>
                    <td class="px-4 py-2 border" data-base="{{ $row->jumlah_apel }}">{{ $row->jumlah_apel ?: '' }}</td>
                    <td class="px-4 py-2 border" data-base="{{ $row->jumlah_upacara }}">{{ $row->jumlah_upacara ?: '' }}</td>
                    <td class="px-4 py-2 font-semibold border total whitespace-nowrap" id="total-{{ $index }}">
                        Rp {{ number_format($row->total, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-2 border"></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-2 text-center text-gray-500 border">
                        Data tidak ditemukan pada periode yang dipilih.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    const uangJam = {{ $uangJam }};
    const uangApel = {{ $uangApel }};
    const uangUpacara = {{ $uangUpacara }};
    const table = document.getElementById('rekapTable');

    // Hitung ulang total saat load
    table.querySelectorAll('tbody tr').forEach((tr, idx) => {
        const hadir = parseFloat(tr.children[1].getAttribute('data-base')) * uangJam || 0;
        const apel = parseFloat(tr.children[2].getAttribute('data-base')) * uangApel || 0;
        const upacara = parseFloat(tr.children[3].getAttribute('data-base')) * uangUpacara || 0;
        const total = hadir + apel + upacara;
        tr.querySelector('.total').innerText = total ? 'Rp ' + total.toLocaleString('id-ID') : 'Rp 0';
    });

    // Export PDF
    document.getElementById('exportPdfBtn').addEventListener('click', () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });

        const periodeInput = "{{ $periodeBulan ?? '' }}";
        let periodeText = "";
        if(periodeInput) {
            const [year, month] = periodeInput.split("-");
            const bulanNama = [
                "Januari","Februari","Maret","April","Mei","Juni",
                "Juli","Agustus","September","Oktober","November","Desember"
            ][parseInt(month)-1];
            periodeText = `Periode: ${bulanNama} ${year}`;
        }

        const namaSekolah = "{{ $profil->nama_sekolah ?? '' }}";

        const rows = [];
        const table = document.getElementById('rekapTable');
        table.querySelectorAll('tbody tr').forEach(tr => {
            const cells = tr.querySelectorAll('td');
            if(cells.length && !cells[0].textContent.includes('Data tidak ditemukan')) {
                const totalVal = parseInt(cells[4].textContent.replace(/[^\d]/g,'')) || 0;
                rows.push([
                    cells[0].textContent.trim(), // Nama Staff
                    cells[1].textContent.trim() || '', // Hadir
                    cells[2].textContent.trim() || '', // Apel
                    cells[3].textContent.trim() || '', // Upacara
                    totalVal ? 'Rp ' + totalVal.toLocaleString('id-ID') : 'Rp 0', // Total
                    '' // Paraf
                ]);
            }
        });

        doc.autoTable({
            head: [['Nama Staff','Hadir','Apel','Upacara','Total','Paraf Staff / Karyawan']],
            body: rows,
            startY: 30,
            theme: 'grid',
            headStyles: {
                fillColor: [241,245,249],
                textColor: 0,
                halign:'center',
                valign:'middle',
                lineWidth:0.1,
                lineColor:0
            },
            styles: {
                fontSize: 9,
                halign:'center',
                valign:'middle',
                cellPadding:3,
                lineWidth:0.1,
                lineColor:0,
                overflow:'linebreak'
            },
            columnStyles: {
                0: { halign:'left', cellWidth: 50 },
                1: { halign:'center' },
                2: { halign:'center' },
                3: { halign:'center' },
                4: { halign:'center' },
                5: { halign:'center', cellWidth: 35 }
            },
            tableWidth: 'auto',
            margin: { left: 10, right: 10 },
            pageBreak: 'auto',
            didDrawPage: function (data) {
                // Judul di setiap halaman
                doc.setFontSize(14);
                let judulLengkap = "HASIL REKAP HONOR STAFF / KARYAWAN";
                if(namaSekolah) judulLengkap += " (" + namaSekolah + ")";
                doc.text(judulLengkap, doc.internal.pageSize.getWidth() / 2, 12, { align: 'center' });

                // Periode
                if(periodeText){
                    doc.setFontSize(11);
                    doc.text(periodeText, doc.internal.pageSize.getWidth() / 2, 18, { align: 'center' });
                }
            }
        });

        doc.save('rekap_honor_staff.pdf');
    });
</script>
