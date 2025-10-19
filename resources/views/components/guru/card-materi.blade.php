<!-- CARD MODERN: DAFTAR MATERI -->
<div x-data="materiList()">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="flex items-center gap-2 text-xl font-bold text-slate-800">
            <i class="bi bi-journal-text"></i>
            Daftar Materi Pembelajaran
        </h2>
    </div>

    <!-- Search -->
    <div class="relative mb-4">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
            <i class="text-lg bi bi-search"></i>
        </span>
        <input type="text" x-model="search"
               placeholder="Search..."
               class="w-full py-2.5 pl-12 pr-4 border border-slate-300 rounded-xl text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 transition">
    </div>

    <div class="flex flex-wrap items-center justify-end gap-3 mb-4">
        <button id="hapusSemua" type="button"
            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg shadow bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800">
            <i class="bi bi-trash"></i> <span>Hapus Semua</span>
        </button>
        <form id="formHapusSemua"
              action="{{ route('guru.materi.destroyAll') }}"
              method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <!-- Grid Cards -->
    @if(!empty($materis) && count($materis) > 0)
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-2">
            @foreach ($materis ?? [] as $m)
                <div class="relative flex flex-col justify-between p-5 transition-all duration-200 bg-white border shadow-sm border-slate-200 rounded-xl hover:shadow-lg hover:border-blue-300 group"
                    x-show="match('{{ strtolower($m->judul . ' ' . ($m->kelas->kelas ?? '') . ' ' . ($m->mapel->mapel ?? '') . ' ' . strip_tags($m->materi ?? '')) }}')">

                    <!-- Dropdown ⋮ -->
                    <div class="absolute top-3 right-3" x-data="{ open: false }">
                        <button @click="open = !open" class="p-1.5 rounded-full hover:bg-slate-100">
                            <i class="bi bi-three-dots-vertical text-slate-600"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 z-30 w-32 mt-2 overflow-hidden bg-white border rounded-lg shadow-md border-slate-200">
                            <button type="button"
                                @click="openEditModal(
                                    {{ $m->id }},
                                    '{{ addslashes($m->judul) }}',
                                    '{{ $m->kelas_id }}',
                                    '{{ $m->mapel_id }}',
                                    `{{ addslashes($m->deskripsi) }}`,
                                    `{{ addslashes($m->materi ?? '') }}`
                                )"
                                class="block w-full px-4 py-2 text-left transition text-slate-700 hover:bg-slate-100">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </button>
                            <form action="{{ route('guru.materi.destroy', $m->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full px-4 py-2 text-left text-red-600 transition hover:bg-red-50">
                                    <i class="bi bi-trash3 me-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Isi Card -->
                    <div class="mt-3">
                        <h3 class="mb-3 -mt-4 text-lg font-semibold transition text-slate-800 group-hover:text-blue-700">
                            {{ $m->judul }}
                        </h3>
                        <p class="flex flex-wrap gap-3 mb-3 text-sm text-slate-600">
                            <span class="px-2 py-1 font-medium text-blue-700 rounded bg-slate-100">
                                {{ $m->kelas->kelas ?? '-' }}
                            </span>
                            <span class="px-2 py-1 font-medium text-green-700 bg-green-100 rounded">
                                {{ $m->mapel->mapel ?? '-' }}
                            </span>
                        </p>
                        {{-- <div class="text-sm leading-relaxed text-slate-700 line-clamp-3">
                            {!! Str::limit(strip_tags($m->materi), 100, '...') !!}
                        </div> --}}
                        <div class="overflow-hidden text-sm leading-relaxed break-words text-slate-700 text-ellipsis" style="
                            display: -webkit-box;
                            -webkit-line-clamp: 2;
                            -webkit-box-orient: vertical;
                            max-width: 100%;
                            overflow-wrap: break-word;
                            word-break: break-word;
                        ">
                            {{ strip_tags($m->deskripsi) }}
                        </div>
                    </div>

                    <!-- Footer -->
                        <!-- Tombol Lihat Materi di Card -->
                        <div class="flex justify-between gap-2 mt-5">
                            @if($m->file_path)
                                <a href="{{ route('guru.view_file_materi', $m->id) }}"
                                    class="px-3 py-1.5 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-700 rounded-lg shadow hover:from-green-700 hover:to-green-800 transition">
                                    <i class="bi bi-file-earmark-text"></i> Lihat File
                                </a>
                            @else
                                <span class="text-sm italic text-slate-400">Tidak ada file</span>
                            @endif

                            <!-- Detail Materi Component -->
                            @if($m->materi || $m->file_path)
                                <x-detail-materi :title="$m->judul" :materi="$m" />
                            @else
                                <span class="text-sm italic text-slate-400">Tidak ada materi</span>
                            @endif
                        </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center w-full py-16 text-center bg-white border text-slate-500 rounded-2xl border-slate-200">
            <i class="mb-3 text-4xl text-slate-400 bi bi-inbox"></i>
            <p class="text-lg font-medium">Belum ada materi yang dibuat</p>
            <p class="text-sm text-slate-400">Silakan tambahkan materi terlebih dahulu.</p>
        </div>
    @endif

    <!-- Modal Edit -->
    <template x-if="showModal">
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
            <div class="relative w-full max-w-2xl mx-4 overflow-hidden bg-white shadow-xl rounded-xl">

                <!-- Header -->
                <div class="sticky top-0 z-10 px-6 py-4 bg-white shadow-sm">
                    <h2 class="text-lg font-bold text-center text-slate-800">
                        <i class="bi bi-pencil-square me-2"></i> Edit Materi
                    </h2>
                </div>

                <!-- Body -->
                <div class="max-h-[70vh] overflow-y-auto px-6 py-4 space-y-4">
                    <form id="editForm" :action="editAction" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label>Judul</label>
                            <input type="text" name="judul" x-model="editData.judul" class="w-full px-3 py-2 border rounded-lg" required>
                        </div>

                        <div>
                            <label>Kelas</label>
                            <select name="kelas_id" x-model="editData.kelas_id" class="w-full px-3 py-2 border rounded-lg" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach(\App\Models\DataKelas::all() as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label>Mapel</label>
                            <select name="mapel_id" x-model="editData.mapel_id" class="w-full px-3 py-2 border rounded-lg" required>
                                <option value="">-- Pilih Mapel --</option>
                                @foreach(\App\Models\DataMapel::all() as $mapel)
                                    <option value="{{ $mapel->id }}">{{ $mapel->mapel }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" x-model="editData.deskripsi" rows="3" class="w-full px-3 py-2 border rounded" required></textarea>
                        </div>

                        <div>
                            <label>Isi Materi</label>
                            <x-forms-tinymce.tinymce-editor name="materi" class="tinymce-editor bg-gray-50" x-model="editData.materi" />
                        </div>

                        <div>
                            <label>Upload File (opsional)</label>
                            <input type="file" name="file" class="w-full px-3 py-2 border rounded">
                            <small class="text-gray-500">Biarkan kosong jika tidak ingin ganti file.</small>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="sticky bottom-0 flex justify-end gap-3 px-6 py-4 bg-white border-t">
                    <button type="button" @click="closeModal()" class="px-4 py-2 border rounded-md">Batal</button>
                    <button type="submit" form="editForm" class="px-4 py-2 text-white bg-blue-600 rounded-md">Simpan</button>
                </div>

            </div>
        </div>
    </template>

</div>

<script>
    function materiList() {
        return {
            search: '',
            showModal: false,
            editAction: '',
            editData: { id: '', judul: '', kelas_id: '', mapel_id: '', deskripsi: '', materi: '' },

            match(text) {
                return text.toLowerCase().includes(this.search.toLowerCase());
            },

            openEditModal(id, judul, kelas_id, mapel_id, deskripsi, materi) {
                this.editData = { id, judul, kelas_id, mapel_id, deskripsi, materi };
                this.editAction = `/guru/materi/${id}`;
                this.showModal = true;

                this.$nextTick(() => {
                    if (tinymce.get('materi')) tinymce.get('materi').remove();
                    tinymce.init({
                        selector: 'textarea.tinymce-editor',
                        plugins: 'code table lists',
                        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
                        height: window.innerWidth < 640 ? 250 : 350,
                        menubar: false,
                        statusbar: false,
                        resize: false,
                        setup: (editor) => {
                            editor.on('init', () => {
                                editor.setContent(this.editData.materi || '');
                            });
                        },
                        content_style: "body { overflow: visible; }"
                    });
                });
            },

            closeModal() {
                this.showModal = false;
                if (tinymce.get('materi')) tinymce.get('materi').remove();
            }
        }
    }

    // Hapus Semua
    document.getElementById('hapusSemua').addEventListener('click', function () {
        Swal.fire({
            title: 'Hapus Semua Materi?',
            text: "Semua data akan terhapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('formHapusSemua').submit();
        });
    });
</script>
