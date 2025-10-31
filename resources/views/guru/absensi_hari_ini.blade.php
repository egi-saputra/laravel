{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="flex items-center gap-2 text-xl font-semibold leading-tight text-gray-800">
            <i class="text-blue-600 bi bi-calendar-check"></i>
            {{ __('Absensi Siswa Hari Ini') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white border border-gray-100 shadow-xl rounded-xl">

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-700">
                        Tanggal: <span class="text-blue-600">{{ $today->translatedFormat('l, d F Y') }}</span>
                    </h3>
                    <a href="{{ route('dashboard') }}"
                       class="text-sm font-semibold text-blue-600 transition hover:text-blue-800">
                        ← Kembali ke Dashboard
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-gray-700 border border-gray-200 rounded-lg">
                        <thead class="text-white bg-gradient-to-r from-blue-600 to-blue-800">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Nama Siswa</th>
                                <th class="px-4 py-3 text-left">Kelas</th>
                                <th class="px-4 py-3 text-left">Keterangan</th>
                                <th class="px-4 py-3 text-left">Jam Presensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presensi as $index => $p)
                                <tr class="transition border-b hover:bg-blue-50">
                                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-semibold">{{ $p->user->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $p->dataSiswa->kelas->kelas ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $color = match($p->keterangan) {
                                                'Hadir' => 'bg-green-100 text-green-700',
                                                'Izin' => 'bg-yellow-100 text-yellow-700',
                                                'Sakit' => 'bg-orange-100 text-orange-700',
                                                'Alpa', 'Alpha' => 'bg-red-100 text-red-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                            {{ $p->keterangan }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">
                                        {{ $p->created_at->format('H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-gray-500">
                                        Tidak ada data presensi untuk hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout> --}}

<x-app-layout>

    {{-- Mobile Version --}}
    <div class="block md:hidden">
        <x-slot name="header">
            <h2 class="flex items-center gap-2 text-xl font-semibold leading-tight text-gray-800">
                <i class="text-blue-600 bi bi-calendar-check"></i>
                {{ __('Absensi Siswa Hari Ini') }}
            </h2>
        </x-slot>

        <div class="flex flex-col min-h-screen md:flex-row">

        <!-- Main Content -->
        <main class="flex-1 p-0 mb-16 overflow-x-auto md:mb-0 md:p-6">

                    {{-- Header --}}
                    <div class="flex flex-col items-center justify-between gap-4 mb-6 md:flex-row">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">
                                Tanggal: <span class="text-blue-600">{{ $today->translatedFormat('l, d F Y') }}</span>
                            </h3>
                        </div>

                        <div class="flex flex-col w-full gap-3 p-2">
                            {{-- Filter Kelas --}}
                            <form method="GET" action="{{ route('guru.absensi_hari_ini') }}" class="flex items-center">
                                <select name="kelas" onchange="this.form.submit()"
                                    class="w-full px-3 py-2 text-sm text-center border border-gray-300 rounded-lg focus:ring focus:ring-blue-200">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelasList as $k)
                                        <option value="{{ $k->id }}" {{ request('kelas') == $k->id ? 'selected' : '' }}>
                                            {{ $k->kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            <div class="flex items-center justify-center w-full gap-4">
                                {{-- Back --}}
                                <a href="{{ route('dashboard') }}"
                                class="w-full px-4 py-2 text-sm font-semibold text-center text-blue-600 transition border border-blue-500 rounded-lg hover:bg-blue-50">
                                    ← Kembali
                                </a>

                                 {{-- Export Excel --}}
                                <a href="{{ $kelasId ? route('guru.absensi_hari_ini.export', ['kelas' => $kelasId]) : '#' }}"
                                    class="px-4 py-2 w-full text-sm font-semibold text-white rounded-lg inline-flex justify-center gap-2
                                            {{ $kelasId ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 cursor-not-allowed' }}"
                                    {{ !$kelasId ? 'onclick=alert("Pilih kelas terlebih dahulu!")' : '' }}>
                                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Cards --}}
                    @if(!$kelasId)
                        <div class="py-10 text-center text-gray-500">
                            Silakan pilih kelas terlebih dahulu untuk melihat data absensi hari ini.
                        </div>
                    @elseif($presensi->isEmpty())
                        <div class="py-10 text-center text-gray-500">
                            Tidak ada data presensi untuk hari ini di kelas yang dipilih.
                        </div>
                    @else
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($presensi as $p)
                                @php
                                    $color = match($p->keterangan) {
                                        'Hadir' => 'bg-green-100 text-green-800 border-green-300',
                                        'Izin' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                        'Sakit' => 'bg-orange-100 text-orange-800 border-orange-300',
                                        'Alpa', 'Alpha' => 'bg-red-100 text-red-800 border-red-300',
                                        default => 'bg-gray-100 text-gray-800 border-gray-300',
                                    };
                                @endphp
                                <div class="p-5 transition duration-200 bg-white border shadow-sm rounded-xl hover:shadow-md">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-lg font-semibold text-gray-800 line-clamp-1">
                                            {{ $p->user->name ?? '-' }}
                                        </h4>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full border {{ $color }}">
                                            {{ $p->keterangan }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        <i class="text-blue-600 bi bi-building"></i>
                                        Kelas: <strong>{{ $p->dataSiswa->kelas->kelas ?? '-' }}</strong>
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        <i class="bi bi-clock"></i>
                                        Jam Presensi: <strong>{{ $p->created_at->format('H:i') }}</strong>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
        </main>
    </div>

    {{-- Desktop Version --}}
    <div class="hidden md:block">
        <x-slot name="header">
            <h2 class="flex items-center gap-2 text-xl font-semibold leading-tight text-gray-800">
                <i class="text-blue-600 bi bi-table"></i>
                {{ __('Absensi Siswa Hari Ini') }}
            </h2>
        </x-slot>

        <div class="md:mb-16">
            <div class="mx-auto max-w-7xl">
                <div class="p-6 bg-white border border-gray-100 shadow-xl rounded-xl">

                    {{-- Header --}}
                    <div class="flex flex-col items-center justify-between gap-4 mb-6 md:flex-row">
                        <div class="flex gap-3">
                            {{-- Back --}}
                            <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 text-sm font-semibold text-blue-600 transition border border-blue-500 rounded-lg hover:bg-blue-50">
                                ← Kembali
                            </a>
                            <h3 class="py-1 text-lg font-semibold text-gray-700">
                                Tanggal: <span class="text-blue-600">{{ $today->translatedFormat('l, d F Y') }}</span>
                            </h3>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            {{-- Filter Kelas --}}
                            <form method="GET" action="{{ route('guru.absensi_hari_ini') }}" class="flex items-center gap-2">
                                <select name="kelas" onchange="this.form.submit()"
                                    class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring focus:ring-blue-200">
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelasList as $k)
                                        <option value="{{ $k->id }}" {{ request('kelas') == $k->id ? 'selected' : '' }}>
                                            {{ $k->kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            {{-- Export Excel --}}
                            <a href="{{ $kelasId ? route('guru.absensi_hari_ini.export', ['kelas' => $kelasId]) : '#' }}"
                            class="px-4 py-2 text-sm font-semibold text-white rounded-lg flex items-center gap-2
                                    {{ $kelasId ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 cursor-not-allowed' }}"
                            {{ !$kelasId ? 'onclick=alert("Pilih kelas terlebih dahulu!")' : '' }}>
                                <i class="bi bi-file-earmark-excel"></i> Export Excel
                            </a>

                            <button id="printAbsensi" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700" data-turbo="false">
                                <i class="bi bi-printer"></i> Print Absensi
                            </button>

                            {{-- Back --}}
                            {{-- <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 text-sm font-semibold text-blue-600 transition border border-blue-500 rounded-lg hover:bg-blue-50">
                                ← Kembali
                            </a> --}}
                        </div>
                    </div>

                    {{-- Tabel --}}
                    @if(!$kelasId)
                        <div class="py-10 text-center text-gray-500">
                            Silakan pilih kelas terlebih dahulu untuk melihat data absensi hari ini.
                        </div>
                    @elseif($presensi->isEmpty())
                        <div class="py-10 text-center text-gray-500">
                            Tidak ada data presensi untuk hari ini di kelas yang dipilih.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full overflow-hidden text-sm text-gray-700 border border-gray-200 rounded-lg shadow-sm">
                                <thead class="text-left text-white bg-gradient-to-r from-blue-600 to-blue-800">
                                    <tr>
                                        <th class="px-4 py-3">No</th>
                                        <th class="px-4 py-3">Nama Siswa</th>
                                        <th class="px-4 py-3">Kelas</th>
                                        <th class="px-4 py-3">Keterangan</th>
                                        <th class="px-4 py-3">Jam Presensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presensi as $index => $p)
                                        @php
                                            $bg = $index % 2 === 0 ? 'bg-gray-50' : 'bg-white';
                                            $color = match($p->keterangan) {
                                                'Hadir' => 'bg-green-100 text-green-800 border-green-300',
                                                'Izin' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                                'Sakit' => 'bg-orange-100 text-orange-800 border-orange-300',
                                                'Alpa', 'Alpha' => 'bg-red-100 text-red-800 border-red-300',
                                                default => 'bg-gray-100 text-gray-800 border-gray-300',
                                            };
                                        @endphp
                                        <tr class="{{ $bg }} hover:bg-blue-50 transition">
                                            <td class="px-4 py-3 font-medium text-gray-600">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 font-semibold">{{ $p->user->name ?? '-' }}</td>
                                            <td class="px-4 py-3">{{ $p->dataSiswa->kelas->kelas ?? '-' }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $color }}">
                                                    {{ $p->keterangan }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-500">{{ $p->created_at->format('H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

{{-- <script>
    document.getElementById('printAbsensi').addEventListener('click', async () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape');

        // Set locale Indonesia
        dayjs.locale('id');
        const today = dayjs(); // tanggal sekarang
        const judul = "Laporan Absensi Siswa Hari Ini — " + today.format('dddd, DD MMMM YYYY');

        // Ambil tabel
        const table = document.querySelector('table');
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText);
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(tr =>
            Array.from(tr.querySelectorAll('td')).map(td => td.innerText)
        );

        doc.autoTable({
            head: [headers],
            body: rows,
            startY: 20,
            margin: { top: 20 },
            styles: {
                fontSize: 10,
                cellPadding: 3,
                lineColor: [150, 150, 150],
                lineWidth: 0.1,
                font: "helvetica"
            },
            headStyles: {
                fillColor: [0, 112, 192],
                textColor: 255,
                lineColor: [150, 150, 150],
                lineWidth: 0.1,
                halign: 'center' // <-- ini yang bikin teks header center
            },
            bodyStyles: {
                halign: 'center' // opsional, kalau mau body tetap rata kiri
            },
            columnStyles: {
                1: { halign: 'left' } // kolom kedua (index 1) kiri
            },
            alternateRowStyles: { fillColor: [240, 240, 240] },
            tableLineWidth: 0.1,
            tableLineColor: [150, 150, 150],
            didDrawPage: (data) => {
                doc.setFontSize(14);
                doc.setFont('helvetica', 'bold');
                const pageWidth = doc.internal.pageSize.getWidth();
                const textWidth = doc.getTextWidth(judul);
                doc.text(judul, (pageWidth - textWidth) / 2, 12);
            }
        });

        doc.autoPrint({ variant: 'non-conform' });
        const printIframe = document.createElement('iframe');
        printIframe.style.display = 'none';
        document.body.appendChild(printIframe);
        printIframe.src = doc.output('bloburl');
    });
</script> --}}

{{-- <script>
    document.getElementById('printAbsensi').addEventListener('click', async () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape');

        // Set locale Indonesia
        dayjs.locale('id');
        const today = dayjs(); // tanggal sekarang
        const judul = "Laporan Absensi Siswa Hari Ini — " + today.format('dddd, DD MMMM YYYY');
        const generated = "Generated from Simstal Query App on: " + today.format('DD/MM/YYYY');

        // Ambil tabel
        const table = document.querySelector('table');
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText);
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(tr =>
            Array.from(tr.querySelectorAll('td')).map(td => td.innerText)
        );

        doc.autoTable({
            head: [headers],
            body: rows,
            startY: 20,
            margin: { top: 20, bottom: 15 },
            styles: {
                fontSize: 10,
                cellPadding: 3,
                lineColor: [150, 150, 150],
                lineWidth: 0.1,
                font: "helvetica"
            },
            headStyles: {
                fillColor: [0, 112, 192],
                textColor: 255,
                lineColor: [150, 150, 150],
                lineWidth: 0.1,
                halign: 'center'
            },
            bodyStyles: {
                halign: 'center'
            },
            columnStyles: {
                1: { halign: 'left' } // kolom kedua rata kiri
            },
            alternateRowStyles: { fillColor: [240, 240, 240] },
            tableLineWidth: 0.1,
            tableLineColor: [150, 150, 150],
            didDrawPage: (data) => {
                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();

                // Header (judul)
                doc.setFontSize(14);
                doc.setFont('helvetica', 'bold');
                const textWidth = doc.getTextWidth(judul);
                doc.text(judul, (pageWidth - textWidth) / 2, 12);

                // Footer
                doc.setFontSize(9);
                doc.setFont('helvetica', 'normal');
                doc.setTextColor(100);
                // kiri: tanggal generate
                doc.text(generated, data.settings.margin.left, pageHeight - 5);
                // kanan: nomor halaman
                const pageNumber = "Page " + doc.internal.getNumberOfPages();
                const pageWidthText = doc.getTextWidth(pageNumber);
                doc.text(pageNumber, pageWidth - data.settings.margin.right - pageWidthText, pageHeight - 5);
            }
        });

        // Print langsung
        doc.autoPrint({ variant: 'non-conform' });
        const printIframe = document.createElement('iframe');
        printIframe.style.display = 'none';
        document.body.appendChild(printIframe);
        printIframe.src = doc.output('bloburl');
    });
</script> --}}

<script>
    document.getElementById('printAbsensi').addEventListener('click', async () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape');

        // Set locale Indonesia
        dayjs.locale('id');
        const today = dayjs();
        const judul = "Laporan Absensi Siswa Hari " + today.format('dddd, DD MMMM YYYY');
        const generated = "Generated from Simstal Query App on: " + today.format('DD/MM/YYYY');

        // Ambil tabel
        const table = document.querySelector('table');
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText);
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(tr =>
            Array.from(tr.querySelectorAll('td')).map(td => td.innerText)
        );

        doc.autoTable({
            head: [headers],
            body: rows,
            startY: 20,
            margin: { top: 20, bottom: 30 },
            styles: {
                fontSize: 10,
                cellPadding: 3,
                lineColor: [150, 150, 150],
                lineWidth: 0.1,
                font: "helvetica"
            },
            headStyles: {
                fillColor: [0, 112, 192],
                textColor: 255,
                lineColor: [150, 150, 150],
                lineWidth: 0.1,
                halign: 'center'
            },
            bodyStyles: {
                halign: 'center'
            },
            columnStyles: {
                1: { halign: 'left' }
            },
            alternateRowStyles: { fillColor: [240, 240, 240] },
            tableLineWidth: 0.1,
            tableLineColor: [150, 150, 150],
            didDrawPage: (data) => {
                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();

                // Header
                doc.setFontSize(14);
                doc.setFont('helvetica', 'semibold');
                const titleWidth = doc.getTextWidth(judul);
                doc.text(judul, (pageWidth - titleWidth) / 2, 12);

                // Footer
                const footerY = pageHeight - 30;
                doc.setFontSize(10);
                doc.setFont('helvetica', 'normal');
                doc.setTextColor(0);

                // Kiri: tanggal generate
                doc.text(generated, data.settings.margin.left, footerY + 20);

                // Kanan: tanda tangan petugas piket
                const ttdWidth = 50; // panjang garis
                const tandaTanganX = pageWidth - data.settings.margin.right - ttdWidth;

                // teks di atas garis, di tengah
                const text = "Petugas Piket";
                const textWidth = doc.getTextWidth(text);
                const textX = tandaTanganX + (ttdWidth - textWidth) / 2;
                doc.text(text, textX, footerY);

                // garis tanda tangan
                doc.line(tandaTanganX, footerY + 20, tandaTanganX + ttdWidth, footerY + 20);
            }
        });

        // Print langsung
        doc.autoPrint({ variant: 'non-conform' });
        const printIframe = document.createElement('iframe');
        printIframe.style.display = 'none';
        document.body.appendChild(printIframe);
        printIframe.src = doc.output('bloburl');
    });
</script>


</x-app-layout>

