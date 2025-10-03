<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Input Tugas Siswa') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="mx-4 mt-4 top-16 md:top-0 md:ml-6 md:mt-6 md:h-screen md:mx-0 md:w-auto">
            <x-sidebar />
        </aside>

        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
            <div class="px-8 py-4 bg-white rounded shadow">
                <h1 class="mb-4 text-lg font-bold">Form Input Tugas</h1>

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
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block font-medium">Kelas</label>
                            <input type="text" class="w-full px-3 py-2 bg-gray-100 border rounded"
                                   value="{{ $kelasUser->kelas ?? '-' }}" readonly disabled>
                            <!-- hidden agar tetap terkirim ke backend -->
                            <input type="hidden" name="kelas_id" value="{{ auth()->user()->kelas_id }}">
                        </div>
                        <div>
                            <label class="block font-medium">Mata Pelajaran</label>
                            <select name="mapel_id" class="w-full px-3 py-2 border rounded">
                                <option value="">-- Pilih Mapel --</option>
                                @foreach($mapel as $m)
                                    <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->mapel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Upload File -->
                    <div>
                        <label class="block font-medium">Upload File</label>
                        <input type="file" name="file_tugas" class="w-full px-3 py-2 border rounded" required>
                        <small class="block mt-1 text-sm leading-relaxed text-gray-500">
                            <span class="font-medium">Format didukung:</span> pdf, doc, docx, xls, xlsx, ppt, pptx, txt, zip, rar, jpg, jpeg, png, gif, svg, webp, mp4, mkv, avi, mov, wmv, flv, dll.
                            <br>
                            <span class="font-medium">Ukuran maksimal:</span> 50MB
                        </small>
                    </div>

                    <!-- Hidden User ID -->
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                    <!-- Tombol Submit -->
                    <div>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Daftar Materi -->
            <div class="p-4 overflow-x-auto bg-white rounded shadow">
                <x-siswa.table-tugas :tugas="$tugas" :mapel="$mapel" />
            </div>
        </main>
    </div>

    <x-footer :profil="$profil" />
</x-app-backtop-layout>
