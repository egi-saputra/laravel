<!-- Filter & Search -->
<form method="GET" class="grid grid-cols-1 gap-2 mb-4 md:grid-cols-4">
    <!-- Search -->
    <div class="relative md:col-span-2">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 border-r text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="3"
                      d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
            </svg>
        </span>
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Cari nama, email, NIS, NISN"
               class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300">
    </div>

    <!-- Filter Kelas -->
    <select name="kelas_id" class="px-3 py-2 border rounded">
        <option value="">Semua Kelas</option>
        @foreach($kelasList as $k)
            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                {{ $k->kelas }}
            </option>
        @endforeach
    </select>

    <!-- Filter Kejuruan -->
    <select name="kejuruan_id" class="px-3 py-2 border rounded">
        <option value="">Semua Kejuruan</option>
        @foreach($kejuruanList as $kj)
            <option value="{{ $kj->id }}" {{ request('kejuruan_id') == $kj->id ? 'selected' : '' }}>
                {{ $kj->nama_kejuruan }}
            </option>
        @endforeach
    </select>

    <!-- Tombol -->
    <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
            <i class="bi bi-funnel"></i> Filter
        </button>
        <a href="{{ route('admin.siswa.index') }}"
           class="px-4 py-2 text-white rounded bg-slate-700 hover:bg-slate-800">
            <i class="bi bi-arrow-clockwise"></i> Reset
        </a>
    </div>
</form>

<div class="mb-4 overflow-x-auto md:overflow-x-visible">
    <table class="w-full border border-collapse" id="siswaTable">
        <thead>
            <tr class="bg-gray-100">
                <th class="w-12 px-4 py-2 text-center border">No</th>
                <th class="px-4 py-2 border">Nama Lengkap</th>
                <th class="px-4 py-2 border">NIS</th>
                <th class="px-4 py-2 border">NISN</th>
                <th class="px-4 py-2 border">Kelas</th>
                <th class="px-4 py-2 border">Kejuruan</th>
                <th class="px-4 py-2 border">Email</th>
                <th class="w-24 px-4 py-2 text-center border"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($siswa ?? [] as $s)
                <tr class="hover:bg-gray-50" x-data="{ open: false, showModal: false }">
                    <td class="px-4 py-2 text-center border">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border">{{ $s->nama_lengkap }}</td>
                    <td class="px-4 py-2 border">{{ $s->nis ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ $s->nisn ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ $s->kelas->kelas ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ $s->kejuruan->nama_kejuruan ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ $s->user->email ?? '-' }}</td>
                    <td class="px-4 py-2 text-center border">
                        <div class="relative inline-block">
                            <!-- Tombol ⋮ -->
                            <button @click="open = !open" class="px-2 py-1 rounded hover:bg-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-6 h-6 text-gray-700"
                                     fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="open" @click.away="open = false" x-transition
                                 class="absolute top-0 z-20 mr-2 bg-white border rounded shadow-md right-full w-28">
                                <button type="button"
                                        @click="showModal = true; open = false"
                                        class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                                    Edit
                                </button>
                                <form action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full px-4 py-2 text-left text-red-600 hover:bg-red-100">
                                        Hapus
                                    </button>
                                </form>
                            </div>

                            <!-- Modal Edit -->
                            <div x-show="showModal" x-cloak
                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                <div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
                                    <h2 class="mb-4 text-lg font-bold">Edit Siswa</h2>
                                    <form action="{{ route('admin.siswa.update', $s->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="block font-medium text-left">Nama Lengkap</label>
                                            <input type="text" name="nama_lengkap" value="{{ $s->nama_lengkap }}"
                                                   class="w-full px-3 py-2 border rounded" required>
                                        </div>
                                        <div>
                                            <label class="block font-medium text-left">Email</label>
                                            <input type="email" name="email" value="{{ $s->user->email ?? '' }}"
                                                   class="w-full px-3 py-2 border rounded" required>
                                        </div>
                                        <div>
                                            <label class="block font-medium text-left">NIS</label>
                                            <input type="text" name="nis" value="{{ $s->nis ?? '' }}"
                                                   class="w-full px-3 py-2 border rounded">
                                        </div>
                                        <div>
                                            <label class="block font-medium text-left">NISN</label>
                                            <input type="text" name="nisn" value="{{ $s->nisn ?? '' }}"
                                                   class="w-full px-3 py-2 border rounded">
                                        </div>
                                        <div>
                                            <label class="block font-medium text-left">Kelas</label>
                                            <select name="kelas_id" class="w-full px-3 py-2 border rounded" required>
                                                <option value="">-- Pilih Kelas --</option>
                                                @foreach(\App\Models\DataKelas::all() as $kelas)
                                                    <option value="{{ $kelas->id }}" {{ $s->kelas_id == $kelas->id ? 'selected' : '' }}>
                                                        {{ $kelas->kelas }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block font-medium text-left">Program Kejuruan</label>
                                            <select name="kejuruan_id" class="w-full px-3 py-2 border rounded" required>
                                                <option value="">-- Pilih Kejuruan --</option>
                                                @foreach(\App\Models\DataKejuruan::all() as $kj)
                                                    <option value="{{ $kj->id }}" {{ $s->kejuruan_id == $kj->id ? 'selected' : '' }}>
                                                        {{ $kj->nama_kejuruan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="showModal = false"
                                                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                                                Batal
                                            </button>
                                            <button type="submit"
                                                    class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-2 text-center">Belum ada data siswa</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="flex justify-end mb-3">
    <a href="{{ route('admin.daftar_siswa.export') }}"
       class="px-4 py-2 text-white bg-green-800 rounded hover:bg-green-900">
        <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
    </a>
</div>

@if(session('alert'))
    <script>
        Swal.fire({
            icon: "{{ session('alert.type') }}",
            title: "{{ session('alert.title') }}",
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
            html: {!! json_encode(session('alert.message'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!},
            width: '90%',
            didOpen: (popup) => {
                const content = popup.querySelector('.swal2-html-container');
                if(content) {
                    content.style.maxHeight = '300px';
                    content.style.overflowY = 'auto';
                }
            }
        });
    </script>
@endif

<!-- Script Search & Hapus Semua -->
<script>
    document.getElementById('hapusSemua').addEventListener('click', function() {
        Swal.fire({
            title: 'Yakin hapus semua data siswa?',
            text: "Semua data siswa akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formHapusSemua').submit();
            }
        });
    });

    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#siswaTable tbody tr");

        rows.forEach(row => {
            let nama = row.cells[1]?.textContent.toLowerCase();
            let email = row.cells[2]?.textContent.toLowerCase();
            let nisn = row.cells[3]?.textContent.toLowerCase();
            let kelas = row.cells[4]?.textContent.toLowerCase();

            if (nama.includes(filter) || email.includes(filter) || nisn.includes(filter) || kelas.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
