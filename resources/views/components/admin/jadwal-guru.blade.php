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
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold">Daftar Jadwal Guru</h2>

        <div class="flex gap-2">
            <button id="hapusSemua" type="button" class="flex items-center px-4 py-2 text-white bg-red-700 rounded hover:bg-red-800">
                <i class="bi bi-trash me-1"></i>
                <p>Hapus <span class="hidden sm:inline">Semua</span></p>
            </button>
        </div>

        <form id="formHapusSemua" action="{{ route('admin.jadwal_guru.destroyAll') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <form method="GET" class="grid grid-cols-1 gap-2 mb-4 md:grid-cols-5">
        <!-- Global Search -->
        <div class="relative md:col-span-2">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 border-r text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari..."
                value="{{ request('search') }}"
                class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300">
        </div>

        <!-- Filter Hari -->
        <select name="hari" class="px-3 py-2 border rounded">
            <option value="">Semua Hari</option>
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                <option value="{{ $h }}" {{ request('hari')==$h?'selected':'' }}>{{ $h }}</option>
                {{-- <option>{{ $h }}</option> --}}
            @endforeach
        </select>

        <!-- Filter Sesi -->
        <input type="text" name="sesi" placeholder="Filter Sesi" value="{{ request('sesi') }}" class="px-3 py-2 border rounded">
        {{-- <input type="text" name="sesi" placeholder="Filter Sesi" class="px-3 py-2 border rounded"> --}}

        <!-- Filter Guru / Kelas -->
        <input type="text" name="guru" placeholder="Filter Guru" value="{{ request('guru') }}" class="px-3 py-2 border rounded">
        {{-- <input type="text" name="guru" placeholder="Filter Guru" class="px-3 py-2 border rounded"> --}}

        <input type="text" name="kelas" placeholder="Filter Kelas" value="{{ request('kelas') }}" class="px-3 py-2 border rounded">
        {{-- <input type="text" name="kelas" placeholder="Filter Kelas" class="px-3 py-2 border rounded"> --}}

        {{-- <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Filter</button> --}}
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700"><i class="bi bi-funnel"></i> Filter</button>
            <a href="{{ route('admin.jadwal_guru.index') }}"
            class="px-4 py-2 text-white rounded bg-slate-700 hover:bg-slate-800"><i class="bi bi-arrow-clockwise"></i> Reset</a>
        </div>
    </form>

    <div class="overflow-x-auto md:overflow-x-visible">
        <table class="w-full border border-collapse" id="jadwalGuruTable">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border whitespace-nowrap">Hari</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Sesi</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Jam</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Nama Guru</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Ruang Kelas</th>
                    <th class="px-4 py-2 text-center border"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal as $j)
                <tr>
                    <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $j->hari }}</td>
                    <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $j->sesi }}</td>
                    <td class="px-4 py-2 text-center border whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                    </td>
                    <td class="px-4 py-2 border whitespace-nowrap">{{ $j->guru->user->name ?? $j->guru->nama ?? '-' }}</td>
                    <td class="px-4 py-2 border whitespace-nowrap">{{ $j->kelas->kelas ?? '-' }}</td>
                    <td class="px-4 py-2 text-center border">
                        <div x-data="{ open: false, showModal: false }" class="relative inline-block">
                            <!-- Tombol titik 3 -->
                            <button @click="open = !open" class="px-2 py-1 rounded hover:bg-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                                </svg>
                            </button>

                            <!-- Dropdown Edit/Hapus -->
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute top-0 z-20 mr-2 bg-white border rounded shadow-md right-full w-36">
                                <button type="button" @click="showModal = true; open = false"
                                        class="block w-full px-4 py-2 text-left hover:bg-gray-100">Edit</button>
                                <form action="{{ route('admin.jadwal_guru.destroy', $j->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full px-4 py-2 text-left text-red-600 hover:bg-red-100 delete-btn">
                                        Delete
                                    </button>
                                </form>
                            </div>

                            <!-- Modal Edit -->
                            <div x-show="showModal" x-cloak
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                <div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
                                    <h2 class="mb-4 text-lg font-bold">Edit Jadwal Guru</h2>
                                    <form action="{{ route('admin.jadwal_guru.update', $j->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        @method('PUT')

                                        <div>
                                            <label class="block font-medium">Hari</label>
                                            <select name="hari" class="w-full px-3 py-2 border rounded" required>
                                                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h)
                                                    <option value="{{ $h }}" {{ $j->hari == $h ? 'selected' : '' }}>{{ $h }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block font-medium">Sesi</label>
                                            <input type="text" name="sesi" value="{{ $j->sesi }}" class="w-full px-3 py-2 border rounded" required>
                                        </div>

                                        <div>
                                            <label class="block font-medium">Jam Mulai</label>
                                            <input type="time" name="jam_mulai"
                                            value="{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}"
                                            class="w-full px-3 py-2 border rounded" step="60" required>
                                        </div>

                                        <div>
                                            <label class="block font-medium">Jam Selesai</label>
                                            <input type="time" name="jam_selesai"
                                            value="{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}"
                                            class="w-full px-3 py-2 border rounded" step="60" required>
                                        </div>

                                        <div>
                                            <label class="block font-medium">Guru</label>
                                            <select name="guru_id" class="w-full px-3 py-2 border rounded" required>
                                                @foreach($guru as $g)
                                                    <option value="{{ $g->id }}">{{ $g->user->name ?? $g->nama }} ({{ $g->kode }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block font-medium">Kelas</label>
                                            <select name="kelas_id" class="w-full px-3 py-2 border rounded" required>
                                                @foreach($kelas as $k)
                                                    <option value="{{ $k->id }}" {{ $j->kelas_id == $k->id ? 'selected' : '' }}>
                                                        {{ $k->kelas }} ({{ $k->kode }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="showModal = false"
                                                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                                            <button type="submit"
                                                    class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>

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
    document.addEventListener('DOMContentLoaded', function() {
        // ============================
        // DELETE INDIVIDUAL
        // ============================
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin hapus?',
                    text: "Data jadwal guru ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        // ============================
        // DELETE ALL
        // ============================
        document.getElementById('hapusSemua').addEventListener('click', function() {
            Swal.fire({
                title: 'Yakin?',
                text: "Semua data jadwal guru akan dihapus!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus semua!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) document.getElementById('formHapusSemua').submit();
            });
        });

        // ============================
        // EXPORT PDF
        // ============================
        const sekolah = {!! json_encode($sekolah, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) !!};
        sekolah.logo_base64 = '{{ $logoBase64 }}';

        document.getElementById('exportPDF').addEventListener('click', () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const margin = 14;
            const pageWidth = doc.internal.pageSize.getWidth();

            // Logo
            if (sekolah.logo_base64) {
                doc.addImage("data:image/png;base64," + sekolah.logo_base64, 'PNG', margin, 13, 22, 22);
            }

            // Nama & Alamat
            doc.setFontSize(14);
            doc.setFont('times new roman', 'bold');
            doc.text(sekolah.nama_sekolah, pageWidth/2, 20, {align: 'center'});

            doc.setFontSize(11);
            doc.setFont('times new roman', 'normal');
            doc.text(sekolah.alamat, pageWidth/2, 26, {align: 'center'});
            doc.text(`Telp: ${sekolah.telepon} | Email: ${sekolah.email}`, pageWidth/2, 32, {align: 'center'});

            // Garis
            doc.setLineWidth(0.5);
            doc.line(margin, 36, pageWidth-margin, 36);

            // Judul tabel
            doc.setFontSize(12);
            doc.setFont('times', 'bold');
            doc.text("DAFTAR JADWAL MENGAJAR GURU", pageWidth/2, 45, {align: 'center'});

            // Ambil data dari table HTML
            const table = document.getElementById('jadwalGuruTable');
            const headers = ["Hari","Sesi","Jam","Guru","Ruang Kelas"];
            const rows = [];
            table.querySelectorAll('tbody tr').forEach(tr => {
                const cells = tr.querySelectorAll('td');
                if(cells.length < 5) return;
                rows.push([
                    cells[0].textContent.trim(),
                    cells[1].textContent.trim(),
                    cells[2].textContent.trim(),
                    cells[3].textContent.trim(),
                    cells[4].textContent.trim()
                ]);
            });

            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 50,
                theme: 'grid',
                headStyles: {fillColor:[100,149,237], textColor:255, halign:'center', valign:'middle'},
                styles: {fontSize:10, halign:'center', valign:'middle'},
                columnStyles: {
                    3: { halign: 'left' },
                    4: { halign: 'left' }
                }
            });

            doc.save('jadwal-guru.pdf');
        });

        // ============================
        // ALERT SESSION
        // ============================
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


