<div class="p-4 bg-white rounded shadow">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold">Daftar Tugas Siswa</h2>

        <!-- Tombol Hapus Semua -->
        <div>
            <button id="hapusSemua" type="button" class="flex items-center px-4 py-2 text-white bg-red-700 rounded hover:bg-red-800">
                <i class="bi bi-trash me-1"></i>
                <p>Hapus <span class="hidden sm:inline">Semua</span></p>
            </button>

            <form id="formHapusSemua"
                  action="{{ route('guru.tugas_siswa.destroyAll') }}"
                  method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    {{-- Filter dan Search --}}
    <form method="GET" action="{{ route('guru.tugas_siswa.index') }}"
        class="flex flex-wrap gap-4 mb-6">

        <input type="text" name="nama" placeholder="Cari Nama"
            class="w-full px-3 py-2 border rounded md:w-auto"
            value="{{ request('nama') }}">

        <select name="kelas"
                class="w-full px-3 py-2 border rounded md:w-auto">
            <option value="Semua">-- Semua Kelas --</option>
            @foreach($kelas as $k)
                <option value="{{ $k->id }}" {{ request('kelas') == $k->id ? 'selected' : '' }}>
                    {{ $k->kelas }}
                </option>
            @endforeach
        </select>

        <select name="mapel"
                class="w-full px-3 py-2 border rounded md:w-auto">
            <option value="Semua">-- Semua Mapel --</option>
            @foreach($mapel as $m)
                <option value="{{ $m->id }}" {{ request('mapel') == $m->id ? 'selected' : '' }}>
                    {{ $m->mapel }}
                </option>
            @endforeach
        </select>

        {{-- Wrapper tombol khusus mobile agar sejajar --}}
        <div class="flex justify-end w-full gap-2 md:w-auto md:justify-start md:gap-4">
            <button type="submit"
                    class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                <i class="bi bi-funnel"></i> Filter
            </button>
            <a href="{{ route('guru.tugas_siswa.index') }}"
            class="px-4 py-2 text-white rounded bg-slate-700 hover:bg-slate-800">
                <i class="bi bi-arrow-clockwise"></i> Reset
            </a>
        </div>
    </form>

    {{-- Tabel List Tugas --}}
    <div class="mb-10 overflow-x-auto md:overflow-x-visible md:mb-3" id="scrollableTable">
            <table class="w-full border border-collapse" id="tugasTable">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="w-12 px-4 py-2 text-center border whitespace-nowrap">No</th>
                        <th class="px-4 py-2 border whitespace-nowrap">Judul Tugas</th>
                        <th class="px-4 py-2 border whitespace-nowrap">Nama Siswa</th>
                        <th class="px-4 py-2 border whitespace-nowrap">Kelompok</th>
                        <th class="px-4 py-2 text-center border whitespace-nowrap">Kelas</th>
                        {{-- <th class="px-4 py-2 text-center border whitespace-nowrap">Mapel</th> --}}
                        <th class="px-4 py-2 border whitespace-nowrap">File Terkait</th>
                        <th class="w-24 px-4 py-2 text-center border"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tugas as $t)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 border whitespace-nowrap">{{ $t->judul ?? '-' }}</td>
                            <td class="px-4 py-2 border whitespace-nowrap">{{ $t->nama ?? '-' }}</td>
                            <td class="px-4 py-2 border whitespace-nowrap">{{ $t->kelompok ?? '-' }}</td>
                            <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $t->kelas->kelas ?? '-' }}</td>
                            {{-- <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $t->mapel->mapel ?? '-' }}</td> --}}
                            <td class="px-4 py-2 text-center border whitespace-nowrap">
                                @if($t->file_path)
                                    <a href="{{ route('guru.view_file_tugas', $t->id) }}"
                                    class="px-4 py-1 text-white bg-blue-600 rounded hover:bg-blue-700">
                                        Lihat Detail
                                    </a>
                                @else
                                    Tidak Ada File!
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center border">
                                <div class="relative inline-block" x-data="{ open: false }">
                                    <button @click="open = !open" class="px-2 py-1 rounded hover:bg-gray-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition
                                        class="absolute top-0 z-20 mr-2 bg-white border rounded shadow-md right-full w-28">
                                        <a href="{{ route('guru.view_file_tugas', $t->id) }}"
                                        class="block w-full px-4 py-2 text-left hover:bg-gray-100">Lihat</a>
                                        <form action="{{ route('guru.tugas_siswa.destroy', $t->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-4 py-2 text-left text-red-600 hover:bg-red-100">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-2 text-center">Belum ada data tugas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>

    <!-- Script Konfirmasi Hapus Semua -->
    <script>
        document.getElementById('hapusSemua').addEventListener('click', function () {
            Swal.fire({
                title: 'Hapus semua tugas ?',
                text: "Semua tugas akan dihapus permanen!",
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

        const slider = document.getElementById('scrollableTable');
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('cursor-grabbing');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('cursor-grabbing');
        });
        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('cursor-grabbing');
        });
        slider.addEventListener('mousemove', (e) => {
            if(!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 1; // scroll-fastness
            slider.scrollLeft = scrollLeft - walk;
        });
    </script>
