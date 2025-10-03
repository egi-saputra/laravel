@props(['rekap', 'periode_mulai', 'periode_akhir', 'kelas_id', 'isGenerated'])

@php
    $totalPresensi = $rekap->sum(fn($s) =>
        $s->hadir_count + $s->sakit_count + $s->izin_count + $s->alpa_count
    );
@endphp

@if($isGenerated)
    @if($rekap->count() > 0 && $totalPresensi > 0)
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">
                Hasil Rekap Absensi Siswa
                <span class="block mt-1 text-sm font-normal text-gray-600">
                    Periode: {{ \Carbon\Carbon::parse($periode_mulai)->translatedFormat('j F Y') }}
                    s/d {{ \Carbon\Carbon::parse($periode_akhir)->translatedFormat('j F Y') }}
                </span>
            </h3>
            <div class="flex space-x-2">
                {{-- Tombol Backup --}}
                <form action="{{ route('guru.absensi_kelas.backup') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">
                    <input type="hidden" name="periode_mulai" value="{{ $periode_mulai }}">
                    <input type="hidden" name="periode_akhir" value="{{ $periode_akhir }}">
                    <button type="submit" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                        <i class="bi bi-download me-1"></i> Backup JSON
                    </button>
                </form>

                {{-- Tombol Export Excel --}}
                <form action="{{ route('guru.absensi_kelas.export_excel') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">
                    <input type="hidden" name="periode_mulai" value="{{ $periode_mulai }}">
                    <input type="hidden" name="periode_akhir" value="{{ $periode_akhir }}">
                    <button type="submit" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-700 rounded hover:bg-green-800">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                    </button>
                </form>

                {{-- Tombol Hapus --}}
                <form action="{{ route('guru.absensi_kelas.clear') }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus data presensi sesuai periode ini?');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">
                    <input type="hidden" name="periode_mulai" value="{{ $periode_mulai }}">
                    <input type="hidden" name="periode_akhir" value="{{ $periode_akhir }}">
                    <button type="submit" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-red-700 rounded hover:bg-red-800">
                        <i class="bi bi-trash me-1"></i> Hapus Data
                    </button>
                </form>
            </div>
        </div>

        {{-- Tabel Presensi --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-700 uppercase whitespace-nowrap">Nama Siswa</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-700 uppercase whitespace-nowrap">Hadir</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-700 uppercase whitespace-nowrap">Sakit</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-700 uppercase whitespace-nowrap">Izin</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-700 uppercase whitespace-nowrap">Alpa</th>
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
