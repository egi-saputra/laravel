<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Input Tugas Siswa') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <aside class="sticky z-10 w-full top-16 md:static md:w-auto md:ml-6 md:mt-6 md:h-screen md:top-0">
            <x-sidebar />
        </aside>

        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
            <div class="p-4 bg-white rounded shadow">
                <h1 class="mb-4 text-lg font-bold">Form Input Tugas</h1>

                <form action="{{ route('siswa.tugas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf

                    <!-- Nama -->
                    {{-- Input Hidden, nonaktifkan komentar jika ingin menampilkan --}}
                    {{-- <div>
                        <label class="block font-medium">Nama</label>
                        <input type="text" class="w-full px-3 py-2 bg-gray-100 border rounded"
                            value="{{ auth()->user()->name }}" readonly>
                        <input type="hidden" name="nama" value="{{ auth()->user()->name }}">
                    </div> --}}

                    <!-- Judul Tugas -->
                    <div>
                        <label class="block font-medium">Judul Tugas</label>
                        <input type="text" name="judul" class="w-full px-3 py-2 border rounded"
                               value="{{ old('judul') }}" required>
                    </div>

                    <!-- Kelompok -->
                    <div>
                        <label class="block font-medium">Kelompok (opsional)</label>
                        <input type="text" name="kelompok" class="w-full px-3 py-2 border rounded"
                               value="{{ old('kelompok') }}">
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label class="block font-medium">Kelas</label>
                        <select name="kelas_id" class="w-full px-3 py-2 border rounded" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Mapel -->
                    <div>
                        <label class="block font-medium">Mata Pelajaran</label>
                        <select name="mapel_id" class="w-full px-3 py-2 border rounded" required>
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($mapel as $m)
                                <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Upload File -->
                    <div>
                        <label class="block font-medium">Upload File</label>
                        <input type="file" name="file_tugas" class="w-full px-3 py-2 border rounded" required>
                        <small class="text-gray-500">Format: PDF, DOCX, PPTX, ZIP (maks 5MB)</small>
                    </div>

                    <!-- Hidden User ID -->
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                    <!-- Tombol Submit -->
                    <div>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            Simpan Tugas
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <x-footer :profil="$profil" />
</x-app-layout>
