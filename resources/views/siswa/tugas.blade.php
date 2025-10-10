<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Input Tugas Siswa') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="mt-0 md:block hidden md:ml-6 md:mt-6 md:h-screen md:mb-0 mb-4 md:w-auto">
            <x-sidebar />
        </aside>

        <main class="flex-1 p-0 md:mb-0 mb-16 space-y-6 overflow-x-auto md:p-6">
            <div class="md:px-8 px-4 py-4 bg-white rounded shadow">
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
                    <div class="md:grid grid-cols-1 hidden gap-4 md:grid-cols-2">
                        <div>
                            <label class="block font-medium">Kelas</label>
                            <input type="text" class="w-full px-3 py-2 bg-gray-100 border rounded"
                                   value="{{ $kelasUser->kelas ?? '-' }}" readonly disabled>
                            <!-- hidden agar tetap terkirim ke backend -->
                            <input type="hidden" name="kelas_id" value="{{ auth()->user()->kelas_id }}">
                        </div>
                        <div>
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
                    <div class="flex justify-end md:justify-start">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Daftar Materi -->
                <x-siswa.table-tugas :tugas="$tugas" :gurus="$gurus" />
        </main>
    </div>

        <!-- Bottom Navigation (Mobile Only - Icon + Text) -->
        <div id="navhp" class="fixed bottom-0 left-0 right-0 z-50 flex justify-around py-2 bg-white border-t shadow-md md:hidden text-xs">

            <!-- Home/Dashboard -->
            <a href="{{ route('siswa.dashboard') }}" class="flex flex-col items-center nav-icon {{ Route::currentRouteName() == 'siswa.dashboard' ? 'active' : '' }}">
                <i class="fas fa-chart-line text-lg"></i>
                <small class="text-xs font-semibold">Beranda</small>
            </a>

            <!-- Siswa -->
            <a href="{{ route('public.daftar_siswa.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('public.daftar_siswa.*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate text-lg"></i>
                <small class="text-xs font-semibold">Siswa</small>
            </a>

            <!-- Informasi Sekolah -->
            <a href="{{ route('public.informasi_sekolah.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('public.informasi_sekolah.index') ? 'active' : '' }}">
                <i class="fas fa-school text-lg"></i>
                <small class="text-xs font-semibold">Sekolah</small>
            </a>

            <!-- Akademik -->
            <a href="{{ route('siswa.materi.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
                <i class="fas fa-book text-lg"></i>
                <small class="text-xs font-semibold">Materi</small>
            </a>

            <!-- Tugas Siswa -->
            <a href="{{ route('siswa.tugas.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
                <i class="fas fa-tasks text-lg"></i>
                <small class="text-xs font-semibold">Tugas</small>
            </a>

        </div>

    <x-footer :profil="$profil" />
</x-app-backtop-layout>
