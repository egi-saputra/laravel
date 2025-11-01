@props(['rekap', 'periode_mulai', 'periode_akhir', 'kelas_id', 'isGenerated'])

@php
    $totalPresensi = $rekap->sum(fn($s) =>
        $s->hadir_count + $s->sakit_count + $s->izin_count + $s->alpa_count
    );
@endphp

@if($isGenerated)
    @if($rekap->count() > 0 && $totalPresensi > 0)
        <div class="flex flex-col items-start justify-between mb-4 space-y-3 md:flex-row md:items-center md:space-y-0">
            <!-- Judul -->
            <h3 class="text-lg font-bold text-gray-800">
                Hasil Rekap Absensi Siswa
                <span class="block mt-1 text-xs font-normal text-gray-600 md:text-sm">
                    Periode: {{ \Carbon\Carbon::parse($periode_mulai)->translatedFormat('j F Y') }}
                    s/d {{ \Carbon\Carbon::parse($periode_akhir)->translatedFormat('j F Y') }}
                </span>
            </h3>

            <!-- Tombol Aksi -->
            <div class="flex flex-col w-full space-y-2 md:flex-row md:w-auto md:space-y-0 md:space-x-2">
                {{-- Tombol Backup --}}
                <form action="{{ route('guru.absensi_kelas.backup') }}" method="POST" class="w-full md:w-auto" data-turbo="false">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">
                    <input type="hidden" name="periode_mulai" value="{{ $periode_mulai }}">
                    <input type="hidden" name="periode_akhir" value="{{ $periode_akhir }}">
                    <button type="submit" data-turbo="false"
                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 md:w-auto">
                        <i class="bi bi-download me-1"></i> Backup JSON
                    </button>
                </form>

                {{-- Tombol Export Excel --}}
                <form action="{{ route('guru.absensi_kelas.export_excel') }}" method="POST" class="w-full md:w-auto" data-turbo="false">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">
                    <input type="hidden" name="periode_mulai" value="{{ $periode_mulai }}">
                    <input type="hidden" name="periode_akhir" value="{{ $periode_akhir }}">
                    <button type="submit" data-turbo="false"
                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-700 rounded hover:bg-green-800 md:w-auto">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                    </button>
                </form>

                {{-- Tombol Hapus --}}
                <form action="{{ route('guru.absensi_kelas.clear') }}" method="POST" data-turbo="false"
                    onsubmit="return confirm('Yakin ingin menghapus data presensi sesuai periode ini?');"
                    class="w-full md:w-auto">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">
                    <input type="hidden" name="periode_mulai" value="{{ $periode_mulai }}">
                    <input type="hidden" name="periode_akhir" value="{{ $periode_akhir }}">
                    <button type="submit" data-turbo="false"
                        class="flex items-center justify-center w-full px-4 py-2 mb-4 text-sm font-medium text-white bg-red-700 rounded md:mb-0 hover:bg-red-800 md:w-auto">
                        <i class="bi bi-trash me-1"></i> Hapus Data
                    </button>
                </form>
            </div>
        </div>

        <hr class="hidden mt-2 mb-6 md:block">

        {{-- Form Search Nama --}}
        @if ($isGenerated)
            <form method="GET" action="{{ route('guru.absensi_kelas.index') }}"
                class="flex flex-col w-full gap-2 mb-6 md:flex-row md:items-center md:gap-3" data-turbo="false">

                {{-- hidden biar periode tetap ikut saat search --}}
                <input type="hidden" name="periode_mulai" value="{{ $periode_mulai }}">
                <input type="hidden" name="periode_akhir" value="{{ $periode_akhir }}">

                {{-- Input search --}}
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari nama siswa..."
                    class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500 md:flex-grow">

                {{-- Tombol Search & Reset --}}
                <div class="grid grid-cols-2 gap-2 md:flex md:gap-2">
                    <button type="submit" data-turbo="false"
                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded flex-nowrap hover:bg-blue-700 md:w-auto">
                        <i class="bi bi-search me-1"></i> Search
                    </button>

                    <a href="{{ route('guru.absensi_kelas.index', [
                            'periode_mulai' => $periode_mulai,
                            'periode_akhir' => $periode_akhir
                        ]) }}"
                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-center text-white bg-gray-500 rounded flex-nowrap hover:bg-gray-600 md:w-auto" data-turbo="false">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        @endif

        {{-- Tabel Presensi --}}
        <div class="overflow-x-auto border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase md:font-bold md:text-sm whitespace-nowrap">Nama Siswa</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-700 uppercase md:text-sm md:font-bold whitespace-nowrap">Hadir</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-700 uppercase md:font-bold md:text-sm whitespace-nowrap">Sakit</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-700 uppercase md:font-bold md:text-sm whitespace-nowrap">Izin</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-700 uppercase md:font-bold md:text-sm whitespace-nowrap">Alpa</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rekap as $siswa)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $siswa->nama_lengkap }}</td>
                            <td class="px-6 py-3 text-sm text-center text-gray-700 whitespace-nowrap">{{ $siswa->hadir_count }}</td>
                            <td class="px-6 py-3 text-sm text-center text-gray-700 whitespace-nowrap">{{ $siswa->sakit_count }}</td>
                            <td class="px-6 py-3 text-sm text-center text-gray-700 whitespace-nowrap">{{ $siswa->izin_count }}</td>
                            <td class="px-6 py-3 text-sm text-center text-gray-700 whitespace-nowrap">{{ $siswa->alpa_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $rekap->appends(request()->only(['periode_mulai','periode_akhir']))->links('pagination::tailwind') }}
        </div>

    @else
        {{-- Pesan jika tidak ada data --}}
        <div class="p-4 mt-2 text-center text-red-700 bg-red-100 border border-red-400 rounded">
            Tidak ada data presensi untuk periode ini.
        </div>
    @endif
@endif
