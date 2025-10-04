<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Kelola Data Siswa') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="mx-4 mt-4 top-16 md:top-0 md:ml-6 md:mt-6 md:h-screen md:mx-0 md:w-auto">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
            <!-- Form Tambah Materi -->
            <div class="p-4 bg-white rounded shadow">
                <h1 class="mb-4 text-lg font-bold">Buat Materi Pembelajaran</h1>

                <!-- Form Input Materi -->
                <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf

                    <div class="w-full gap-4 md:flex">
                        <!-- Kelas dropdown -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1 font-medium">Unit Kelas <small class="text-red-500">*</small></label>
                            <select name="kelas_id" class="w-full px-3 py-2 mb-4 border rounded" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Mapel dropdown -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1 font-medium">Mata Pelajaran <small class="text-red-500">*</small></label>
                            <select name="mapel_id" class="w-full px-3 py-2 mb-3 border rounded" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($mapel as $m)
                                    <option value="{{ $m->id }}">{{ $m->mapel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium">Judul Materi <small class="text-red-500">*</small></label>
                        <input type="text" name="judul" class="w-full px-3 py-2 mb-4 border rounded" required>
                    </div>

                    <div class="overflow-x-auto md:overflow-x-visible">
                        <label class="block mb-2 font-medium">Deskripsi / Isi Materi <small class="text-red-500">*</small></label>
                        <x-forms-tinymce.tinymce-editor name="materi" />
                    </div>

                    <div>
                        <label class="block mb-2 font-medium">Upload File (Opsional)</label>
                        <input type="file" name="file" class="w-full px-3 py-2 border rounded">
                        <small class="text-red-500">* Format diperbolehkan: pdf, docx, xlsx, pptx, dll. (Maks 10 MB)</small>
                    </div>

                    <!-- user_id otomatis dari auth -->
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                    <div class="flex justify-end md:justify-start">
                        <button type="submit"
                            class="px-4 py-2 mt-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Daftar Materi -->
            <x-guru.list-materi :kelas="$kelas" :mapel="$mapel" :materis="$materis" />
        </main>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-backtop-layout>
