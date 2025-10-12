<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Input Tugas Siswa') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="z-50 hidden mt-0 md:block md:ml-6 md:mt-6 md:h-screen md:w-auto">
            <x-sidebar />
        </aside>

        <main class="flex-1 p-0 pb-16 space-y-6 overflow-x-auto md:p-6">
            <div class="px-4 py-4 mb-8 bg-white rounded-md shadow md:rounded md:px-8">
                <h1 class="pb-2 mb-4 text-lg font-semibold text-center md:mb-4 md:text-start md:font-bold">Form Input Tugas</h1>

                <form action="{{ route('siswa.tugas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Nama otomatis -->
                    <input type="hidden" name="nama" value="{{ auth()->user()->name }}">

                    <!-- Judul & Kelompok -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block font-medium">Judul Tugas</label>
                            <input type="text" name="judul" class="w-full px-3 py-2 border rounded"
                                value="{{ old('judul') }}" required>
                        </div>
                        <div>
                            <label class="block font-medium">Kelompok (opsional)</label>
                            <input type="text" name="kelompok" class="w-full px-3 py-2 border rounded"
                                value="{{ old('kelompok') }}">
                        </div>
                    </div>

                    <!-- Kelas & Mapel -->
                    <div class="grid grid-cols-1 gap-4 md:pb-3 md:mb-4 md:grid-cols-2">
                        <div class="hidden mb-4 md:mb-0 md:grid">
                            <label class="block font-medium">Kelas (Auto Generate)</label>
                            <input type="text" class="w-full px-3 py-2 border rounded bg-gray-50"
                                   value="{{ $kelasUser->kelas ?? '-' }}" readonly disabled>
                            <!-- hidden agar tetap terkirim ke backend -->
                            <input type="hidden" name="kelas_id" value="{{ auth()->user()->kelas_id }}">
                        </div>
                        <div class="mb-4 md:mb-0">
                            {{-- Jika mau berdasarkan Mapel --}}
                            {{-- <label class="block font-medium">Mata Pelajaran</label>
                            <select name="mapel_id" class="w-full px-3 py-2 border rounded">
                                <option value="">-- Pilih Mapel --</option>
                                @foreach($mapel as $m)
                                    <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->mapel }}
                                    </option>
                                @endforeach
                            </select> --}}

                            <label class="block font-medium">Nama Guru Mapel</label>
                            <select name="guru_id" class="w-full px-3 py-2 border rounded" required>
                                <option value="">-- Pilih Guru --</option>
                                    @foreach($gurus as $guru)
                                        <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->user->name ?? 'Belum Ada Guru' }}
                                        </option>
                                    @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Upload File -->
                    {{-- <div>
                        <label class="block font-medium">Upload File</label>
                        <input type="file" name="file_tugas" class="w-full px-3 py-2 border rounded" required>
                        <small class="block mt-1 text-sm leading-relaxed text-gray-500">
                            <span class="font-medium">Format didukung:</span> pdf, doc, docx, xls, xlsx, ppt, pptx, txt, zip, rar, jpg, jpeg, png, gif, svg, webp, mp4, mkv, avi, mov, wmv, flv, dll.
                            <br>
                            <span class="font-medium">Ukuran maksimal:</span> 50MB
                        </small>
                    </div> --}}

                    <!-- Upload File Custom Button -->
                    <div class="flex flex-col gap-4 md:flex-row md:items-center">
                        <input type="file" name="file_tugas" id="fileInput" class="hidden" required>
                        <button type="button" onclick="document.getElementById('fileInput').click()"
                            class="w-full px-4 py-2 transition-colors border border-gray-300 rounded md:w-1/3 text-slate-900 md:font-semibold md:border-2 md:border-gray-700 hover:bg-gray-100">
                            <i class="mr-2 bi bi-upload"></i> Upload File Tugas
                        </button>

                        <button type="submit" class="w-full px-4 py-2 text-white bg-blue-600 rounded md:w-auto hover:bg-blue-700">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </div>

                <small class="block mt-2 text-xs text-gray-500 md:text-sm">
                    Format didukung: pdf, doc, docx, xls, xlsx, ppt, pptx, txt, zip, rar, jpg, jpeg, png, mp4, dll. <br class="hidden md:inline-block">
                    Ukuran maksimal: 50MB
                </small>
            </form>
        </div>

        <!-- Tabel Daftar Materi -->
        <div class="p-4 overflow-x-auto bg-white rounded-md shadow md:px-8 md:overflow-x-visible">
            <x-siswa.table-tugas :tugas="$tugas" :gurus="$gurus" />
        </div>
    </main>
</div>

    <x-footer :profil="$profil" />
</x-app-layout>
