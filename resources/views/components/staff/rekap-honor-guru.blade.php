<div class="bg-white">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold">Hasil Rekap Honor Guru</h2>
        <div class="space-x-2">
            <button id="printGuruBtn" class="px-4 py-2 text-white bg-blue-700 rounded hover:bg-blue-800" data-turbo="false">
                <i class="bi bi-printer"></i> Print Out
            </button>
            <button id="exportPdfGuruBtn" class="px-4 py-2 text-white bg-red-700 rounded hover:bg-red-800">
                <i class="bi bi-file-earmark-pdf-fill"></i> Export PDF
            </button>
        </div>
    </div>

    <p class="font-semibold text-red-600">Keterangan :</p>
    <p class="mb-4">
        Logika Perhitungan Jumlah Total =
        <span class="font-semibold">
            (Kehadiran × Uang Transport) + (Sakit × Uang Sakit) + (Izin × Uang Izin) +
            (Jam Mati × Uang Jam Mati) +
            (Apel Pagi × Uang Apel) +
            (Pembina Apel × Uang Pembina Apel) +
            (Jumlah Upacara × Uang Upacara) +
            (Pembina Upacara × Uang Pembina Upacara) + (Tunjangan Jabatan)
        </span>
        <br>
        <span class="text-sm text-gray-600">
            Catatan: Jam Mati = Jumlah Jam Guru (Jadwal) × Jumlah Minggu (Bulan Ini).
        </span>
    </p>

    <div class="overflow-x-auto" id="tableWrapper">
        <table class="min-w-full border border-gray-300" id="rekapGuruTable">
            <thead class="bg-gray-100">
                <tr class="text-center">
                    <th class="px-4 py-2 border whitespace-nowrap">Nama Guru</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Kehadiran</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Sakit</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Izin</th>
                    {{-- <th class="px-4 py-2 border whitespace-nowrap">Jumlah Jam</th> --}}
                    <th class="px-4 py-2 border whitespace-nowrap">Jam Mati</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Apel Pagi</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Pemb. Apel</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Upacara</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Pemb. Upacara</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Tunjab</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Total</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Paraf Guru</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekap as $row)
                <tr class="text-center">
                    <td class="px-4 py-2 text-left border whitespace-nowrap">{{ $row->name }}</td>
                    <td class="px-4 py-2 border whitespace-nowrap">{{ $row->jumlah_jam_hadir }}</td>
                    <td class="px-4 py-2 border whitespace-nowrap">{{ $row->jumlah_sakit }}</td>
                    <td class="px-4 py-2 border whitespace-nowrap">{{ $row->jumlah_izin }}</td>
                    {{-- <td class="px-4 py-2 border whitespace-nowrap">{{ $row->jumlah_jam_guru }}</td> --}}
                    <td class="px-4 py-2 border whitespace-nowrap">{{ $row->jam_mati }}</td>
                    <td class="px-4 py-2 border whitespace-nowrap">{{ $row->jumlah_apel }}</td>
                    <td class="px-4 py-2 border whitespace-nowrap">{{ $row->jumlah_pembina_apel }}</td>
                    <td class="px-4 py-2 border whitespace-nowrap">{{ $row->jumlah_upacara }}</td>
                    <td class="px-4 py-2 border whitespace-nowrap">{{ $row->jumlah_pembina_upacara }}</td>
                    <td class="px-4 py-2 border whitespace-nowrap">
                        <input type="text"
                            class="w-32 px-2 py-1 text-left border rounded tunjab-input"
                            value="Rp. 0">
                    </td>
                    <td class="px-4 py-2 font-semibold text-left border whitespace-nowrap total-cell">{{ $row->total_rp }}</td>
                    <td class="px-4 py-6 border"></td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-4 py-2 text-center text-gray-500 border">
                        Data tidak ditemukan pada periode yang dipilih.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    // ===== Scroll Tabel dengan Drag =====
    const tableWrapper = document.getElementById("tableWrapper");
    let isDown = false;
    let startX;
    let scrollLeft;

    tableWrapper.addEventListener("mousedown", (e) => {
        isDown = true;
        tableWrapper.classList.add("cursor-grabbing");
        startX = e.pageX - tableWrapper.offsetLeft;
        scrollLeft = tableWrapper.scrollLeft;
    });
    tableWrapper.addEventListener("mouseleave", () => {
        isDown = false;
        tableWrapper.classList.remove("cursor-grabbing");
    });
    tableWrapper.addEventListener("mouseup", () => {
        isDown = false;
        tableWrapper.classList.remove("cursor-grabbing");
    });
    tableWrapper.addEventListener("mousemove", (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - tableWrapper.offsetLeft;
        const walk = (x - startX) * 1.5; // kecepatan scroll
        tableWrapper.scrollLeft = scrollLeft - walk;
    });

    // ===== Support Drag di HP (Touch) =====
    let startXTouch;
    tableWrapper.addEventListener("touchstart", (e) => {
        startXTouch = e.touches[0].pageX - tableWrapper.offsetLeft;
        scrollLeft = tableWrapper.scrollLeft;
    });
    tableWrapper.addEventListener("touchmove", (e) => {
        const x = e.touches[0].pageX - tableWrapper.offsetLeft;
        const walk = (x - startXTouch) * 1.5;
        tableWrapper.scrollLeft = scrollLeft - walk;
    });

    function formatRupiah(angka) {
        let numberString = angka.replace(/[^,\d]/g, '').toString();
        let split = numberString.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah ? 'Rp. ' + rupiah : '';
    }

    document.getElementById('printGuruBtn').addEventListener('click', () => {
        const periodeInput = document.getElementById('periodeBulan')?.value || "";
        let periodeText = "";
        if (periodeInput) {
            const [year, month] = periodeInput.split("-");
            const bulanNama = [
                "Januari","Februari","Maret","April","Mei","Juni",
                "Juli","Agustus","September","Oktober","November","Desember"
            ][parseInt(month) - 1];
            periodeText = `Periode: ${bulanNama} ${year}`;
        }

        const namaSekolah = document.getElementById('namaSekolah')?.value || "";

        // Kumpulkan data tabel seperti export PDF
        let rows = "";
        document.querySelectorAll('#rekapGuruTable tbody tr').forEach(tr => {
            const cells = tr.querySelectorAll('td');
            if (cells.length && cells[0].textContent.trim() !== 'Data tidak ditemukan pada periode yang dipilih.') {
                rows += `
                    <tr>
                        <td class="text-left">${cells[0].textContent.trim()}</td>
                        <td>${cells[1].textContent.trim()}</td>
                        <td>${cells[2].textContent.trim()}</td>
                        <td>${cells[3].textContent.trim()}</td>
                        <td>${cells[4].textContent.trim()}</td>
                        <td>${cells[5].textContent.trim()}</td>
                        <td>${cells[6].textContent.trim()}</td>
                        <td>${cells[7].textContent.trim()}</td>
                        <td>${cells[8].textContent.trim()}</td>
                        <td>${cells[9].querySelector('input')?.value || "Rp. 0"}</td>
                        <td>${cells[10].textContent.trim()}</td>
                        <td></td>
                    </tr>
                `;
            }
        });

        const printWindow = window.open('', '', 'width=1200,height=800');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Rekap Honor Guru</title>
                    <style>
                        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
                        h2 { text-align: center; margin-bottom: 5px; }
                        .sub-title { text-align: center; margin-bottom: 15px; font-size: 13px; }
                        table { border-collapse: collapse; width: 100%; font-size: 11px; }
                        table, th, td { border: 1px solid #333; }
                        th, td { padding: 5px; text-align: center; }
                        th { background: #f1f5f9; font-weight: bold; }
                        .text-left { text-align: left; }
                    </style>
                </head>
                <body>
                    <h2>HASIL REKAP HONOR GURU ${namaSekolah ? '('+namaSekolah+')' : ''}</h2>
                    ${periodeText ? `<div class="sub-title">${periodeText}</div>` : ""}
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Guru</th>
                                <th>Kehadiran</th>
                                <th>Sakit</th>
                                <th>Izin</th>
                                <th>Jam Mati</th>
                                <th>Apel Pagi</th>
                                <th>Pemb. Apel</th>
                                <th>Upacara</th>
                                <th>Pemb. Upacara</th>
                                <th>Tunjab</th>
                                <th>Total</th>
                                <th>Paraf Guru</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rows || `<tr><td colspan="10">Data tidak ditemukan pada periode yang dipilih.</td></tr>`}
                        </tbody>
                    </table>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    });

    document.querySelectorAll('.tunjab-input').forEach(input => {
        input.addEventListener('input', function () {
            // Format ke rupiah
            this.value = formatRupiah(this.value);

            const row = this.closest('tr');
            const totalCell = row.querySelector('.total-cell');

            // ambil total asli
            let originalTotal = totalCell.getAttribute('data-original');
            if (!originalTotal) {
                originalTotal = totalCell.textContent.replace(/[^\d]/g, '');
                totalCell.setAttribute('data-original', originalTotal);
            }

            // ambil nilai tunjab (angka saja)
            const tunjab = parseInt(this.value.replace(/[^\d]/g, '')) || 0;
            const base = parseInt(originalTotal) || 0;
            const newTotal = base + tunjab;

            totalCell.textContent = "Rp. " + newTotal.toLocaleString('id-ID');
        });
    });

    document.getElementById('exportPdfGuruBtn').addEventListener('click', () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });

        const periodeInput = document.getElementById('periodeBulan').value;
        let periodeText = "";
        if (periodeInput) {
            const [year, month] = periodeInput.split("-");
            const bulanNama = [
                "Januari","Februari","Maret","April","Mei","Juni",
                "Juli","Agustus","September","Oktober","November","Desember"
            ][parseInt(month) - 1];
            periodeText = `Periode: ${bulanNama} ${year}`;
        }

        const namaSekolah = document.getElementById('namaSekolah')?.value || "";

        const rows = [];
        document.querySelectorAll('#rekapGuruTable tbody tr').forEach(tr => {
            const cells = tr.querySelectorAll('td');
            if (cells.length && cells[0].textContent.trim() !== 'Data tidak ditemukan pada periode yang dipilih.') {
                let total = cells[7].textContent.trim();
                let totalNum = parseInt(total.replace(/[^\d]/g, '')) || 0;
                total = totalNum ? 'Rp. ' + totalNum.toLocaleString('id-ID') : total;

                rows.push([
                    cells[0].textContent.trim(), // Nama
                    cells[1].textContent.trim(), // Kehadiran
                    cells[2].textContent.trim(), // Sakit
                    cells[3].textContent.trim(), // Izin
                    cells[4].textContent.trim(), // Jam Mati
                    cells[5].textContent.trim(),
                    cells[6].textContent.trim(),
                    cells[7].textContent.trim(),
                    cells[8].textContent.trim(),
                    cells[9].querySelector('input')?.value || "0", // Tunjab
                    cells[10].textContent.trim(), // Total
                    ""
                ]);
            }
        });

        doc.autoTable({
            head: [[
                'Nama Guru','Kehadiran','Sakit','Izin','Jam Mati','Apel Pagi',
                'Pemb. Apel','Upacara','Pemb. Upacara','Tunjab','Total','Paraf Guru'
            ]],
            body: rows,
            theme: 'grid',
            headStyles: {
                fillColor: [241,245,249],
                textColor: 0,
                halign: 'center',
                lineWidth: 0.1,
                lineColor: 0
            },
            styles: {
                fontSize: 9,
                halign: 'center',
                cellPadding: 3,
                lineWidth: 0.1,
                lineColor: 0,
                overflow: 'linebreak'
            },
            columnStyles: {
                0: { cellWidth: 45, halign:'left' },
                4: { halign:'center' },
                9: { halign:'center' },
                10: { cellWidth: 35, halign:'center' }
            },
            tableWidth: 'auto',
            margin: { top: 28, left: 10, right: 10 }, // ⬅️ ini yang penting
            pageBreak: 'auto',
            didDrawPage: function (data) {
                doc.setFontSize(14);
                let judulLengkap = "HASIL REKAP HONOR GURU";
                if (namaSekolah) {
                    judulLengkap += " (" + namaSekolah + ")";
                }
                doc.text(judulLengkap, doc.internal.pageSize.getWidth() / 2, 12, { align: "center" });

                if (periodeText) {
                    doc.setFontSize(11);
                    doc.text(periodeText, doc.internal.pageSize.getWidth() / 2, 18, { align: "center" });
                }
            }
        });

        doc.save('rekap_honor_guru.pdf');
    });
</script>
