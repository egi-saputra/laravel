<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
    @foreach($siswa as $s)
        <div class="flex flex-col items-center p-4 transition bg-white rounded-lg shadow hover:shadow-lg">
            {{-- Header --}}
            <div class="w-full h-32 rounded shadow-sm bg-gradient-to-r from-sky-600 to-sky-800"></div>

            {{-- Foto Profil --}}
            <img src="{{ $s->foto_profil ? Storage::url($s->foto_profil->file_path) : asset('storage/default/avatar.jpeg') }}"
                 alt="{{ $s->name }}"
                 class="object-cover w-24 h-24 -mt-12 rounded-full shadow-md">

            {{-- Nama & Kelas --}}
            <h3 class="mt-3 text-lg font-semibold text-center line-clamp-1">{{ $s->dataSiswa->nama_lengkap ?? '-' }}</h3>
            <p class="text-sm text-center text-gray-600">
                {{ $s->dataSiswa->kelas->kelas ?? '-' }} |
                {{ $s->dataSiswa->kejuruan->nama_kejuruan ?? '-' }}
            </p>

            {{-- Tombol Lihat Detail --}}
            <button class="flex items-center gap-2 px-4 py-2 mt-4 text-sm font-medium text-white bg-blue-600 rounded-lg lihat-detail-btn hover:bg-blue-700"
                    data-name="{{ $s->dataSiswa->nama_lengkap ?? '-' }}"
                    data-tempat-tanggal-lahir="{{ $s->dataSiswa->tempat_tanggal_lahir ?? '-' }}"
                    data-jenis-kelamin="{{ $s->dataSiswa->jenis_kelamin ?? '-' }}"
                    data-agama="{{ $s->dataSiswa->agama ?? '-' }}"
                    data-asal-sekolah="{{ $s->dataSiswa->asal_sekolah ?? '-' }}"
                    data-kelas="{{ $s->dataSiswa->kelas->kelas ?? '-' }}"
                    data-kejuruan="{{ $s->dataSiswa->kejuruan->nama_kejuruan ?? '-' }}"
                    data-nis="{{ $s->dataSiswa->nis ?? '-' }}"
                    data-nisn="{{ $s->dataSiswa->nisn ?? '-' }}"
                    data-email="{{ $s->email ?? '-' }}"
                    data-foto="{{ $s->foto_profil ? Storage::url($s->foto_profil->file_path) : asset('storage/default/avatar.jpeg') }}">
                <i class="bi bi-eye"></i> Lihat Detail
            </button>
        </div>
    @endforeach
</div>

<x-modal-detail-siswa-public />
