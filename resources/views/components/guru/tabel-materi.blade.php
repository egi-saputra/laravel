<div class="p-4 bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="flex items-center gap-2 text-xl font-bold text-slate-800">
            <i class="text-lg text-blue-600 bi bi-journal-text"></i>
            Daftar Materi Pembelajaran
        </h2>

        <!-- Tombol Hapus Semua -->
        <div>
            <button id="hapusSemua" type="button" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-md shadow bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800">
                <i class="bi bi-trash me-1"></i>
                <p>Hapus <span class="hidden sm:inline">Semua</span></p>
            </button>

            <form id="formHapusSemua"
                  action="{{ route('guru.materi.destroyAll') }}"
                  method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <!-- Search Box -->
    <div class="relative mb-4">
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
        <input type="text" id="searchInput"
               placeholder="Cari judul materi, kelas, isi materi..."
               class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300">
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full mb-10 border border-collapse md:mb-0" id="materiTable">
            <thead>
                <tr class="bg-gray-100">
                    <th class="w-12 px-4 py-2 text-center border">No</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Judul Materi</th>
                    <th class="px-4 py-2 text-center border whitespace-nowrap">Kelas</th>
                    <th class="px-4 py-2 text-center border whitespace-nowrap">Mapel</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Isi Materi</th>
                    <th class="px-4 py-2 border whitespace-nowrap">File Terkait</th>
                    <th class="w-24 px-4 py-2 text-center border"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($materis ?? [] as $m)
                    <tr class="hover:bg-gray-50" x-data="{ open: false, showModal: false }">
                        <td class="px-4 py-2 text-center border">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $m->judul ?? '-' }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $m->kelas->kelas ?? '-' }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $m->mapel->mapel ?? '-' }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            <div class="text-center">
                                @if($m->materi)
                                    <x-detail-materi :title="$m->judul" :materi="$m">
                                        {!! $m->materi !!}
                                    </x-detail-materi>
                                @else
                                    Tidak ada materi / Hanya berupa file!
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-2 text-center border whitespace-nowrap">
                            @if($m->file_path)
                                <a href="{{ route('guru.view_file_materi', $m->id) }}"
                                   class="px-4 py-1 text-white bg-blue-600 border-transparent rounded-md hover:bg-blue-700">
                                    {{-- {{ $m->file_name }} --}}
                                    Lihat
                                </a>
                            @else
                                Tidak Ada File!
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center border">
                            <div class="relative inline-block">
                                <!-- Tombol ⋮ -->
                                <button @click="open = !open"
                                        class="px-2 py-1 rounded hover:bg-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-6 h-6 text-gray-700"
                                         fill="currentColor"
                                         viewBox="0 0 20 20">
                                        <path d="M10 3a1.5 1.5 0 110 3
                                                 1.5 1.5 0 010-3zm0 5a1.5 1.5
                                                 0 110 3 1.5 1.5 0 010-3zm0
                                                 5a1.5 1.5 0 110 3 1.5 1.5
                                                 0 010-3z"/>
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition
                                     class="absolute top-0 z-20 mr-2 bg-white border rounded shadow-md right-full w-28">
                                    <button type="button"
                                            @click="showModal = true; open = false"
                                            class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                                        Edit
                                    </button>
                                    <form action="{{ route('guru.materi.destroy', $m->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full px-4 py-2 text-left text-red-600 hover:bg-red-100">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                                <!-- Modal Edit -->
                                <div x-show="showModal"
                                    x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

                                    <div class="relative w-full max-w-2xl p-6 mx-4 bg-white rounded shadow-lg">
                                        <!-- Scrollable content -->
                                        <div class="max-h-[80vh] overflow-y-auto pr-2">
                                            <h2 class="mb-4 text-lg font-bold text-center">Edit Materi</h2>
                                            <form action="{{ route('guru.materi.update', $m->id) }}"
                                                method="POST"
                                                enctype="multipart/form-data"
                                                class="space-y-3">
                                                @csrf
                                                @method('PUT')

                                                <!-- Judul Materi -->
                                                <div>
                                                    <label class="block font-medium text-left">Judul</label>
                                                    <input type="text"
                                                        name="judul"
                                                        value="{{ $m->judul }}"
                                                        class="w-full px-3 py-2 border rounded"
                                                        required>
                                                </div>

                                                <!-- Kelas -->
                                                <div>
                                                    <label class="block font-medium text-left">Kelas</label>
                                                    <select name="kelas_id"
                                                            class="w-full px-3 py-2 border rounded"
                                                            required>
                                                        <option value="">-- Pilih Kelas --</option>
                                                        @foreach(\App\Models\DataKelas::all() as $kelas)
                                                            <option value="{{ $kelas->id }}"
                                                                {{ $m->kelas_id == $kelas->id ? 'selected' : '' }}>
                                                                {{ $kelas->kelas }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Mapel -->
                                                <div>
                                                    <label class="block font-medium text-left">Mata Pelajaran</label>
                                                    <select name="mapel_id"
                                                            class="w-full px-3 py-2 border rounded"
                                                            required>
                                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                                        @foreach(\App\Models\DataMapel::all() as $mapel)
                                                            <option value="{{ $mapel->id }}"
                                                                {{ $m->mapel_id == $mapel->id ? 'selected' : '' }}>
                                                                {{ $mapel->mapel }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Deskripsi Materi -->
                                                <div>
                                                    <label class="block font-medium text-left">Deskripsi Materi</label>
                                                    <textarea name="deskripsi"
                                                            rows="3"
                                                            class="w-full px-3 py-2 border rounded"
                                                            placeholder="Tulis deskripsi singkat materi"
                                                            required>{{ old('deskripsi', $m->deskripsi) }}</textarea>
                                                </div>

                                                <!-- Isi Materi -->
                                                <div>
                                                    <label class="block font-medium text-left">Isi Materi</label>
                                                    <x-forms-tinymce.tinymce-editor name="materi" :value="$m->materi" />
                                                </div>

                                               <div class="mb-4">
                                                    <label class="block mb-2 font-medium text-left">Upload File (opsional)</label>
                                                    <input type="file" name="file"
                                                        class="w-full px-3 py-2 border rounded">
                                                    <small class="block ml-1 text-left text-gray-500">
                                                        <span class="text-red-600">*</span> Biarkan kosong jika tidak ingin ganti file.
                                                    </small>
                                                </div>

                                                <div class="flex justify-end gap-2 pt-4">
                                                    <button type="button"
                                                            @click="showModal = false"
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
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-2 text-center">
                            Belum ada data materi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Script Search & Hapus Semua -->
<script>
    // Konfirmasi hapus semua
    document.getElementById('hapusSemua').addEventListener('click', function () {
        Swal.fire({
            title: 'Hapus Daftar Materi?',
            text: "Semua materi akan dihapus permanen!",
            icon: 'question',
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

    // Pencarian tabel dengan debounce
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#materiTable tbody tr");

        rows.forEach(row => {
            let judul  = row.cells[1]?.textContent.toLowerCase() || "";
            let kelas  = row.cells[2]?.textContent.toLowerCase() || "";
            let mapel  = row.cells[3]?.textContent.toLowerCase() || "";
            let materi = row.cells[4]?.textContent.toLowerCase() || "";
            let file   = row.cells[5]?.textContent.toLowerCase() || "";

            if (
                judul.includes(filter) ||
                kelas.includes(filter) ||
                mapel.includes(filter) ||
                materi.includes(filter) ||
                file.includes(filter)
            ) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
