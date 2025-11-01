@props(['rekap'])

@php
    $filters = session('riwayat_filters');
    $jenis   = $filters['jenis_presensi'] ?? 'guru';
    $mulai   = $filters['periode_mulai'] ?? null;
    $akhir   = $filters['periode_akhir'] ?? null;
@endphp

<div class="flex items-center justify-between mb-4">
    <h3 class="text-lg font-bold">Data Riwayat Presensi</h3>
    <div class="flex space-x-2">
        {{-- Tombol Backup --}}
        <form action="{{ route('staff.riwayat_presensi.backup') }}" method="POST">
            @csrf
            <input type="hidden" name="jenis_presensi" value="{{ $jenis }}">
            <input type="hidden" name="periode_mulai" value="{{ $mulai }}">
            <input type="hidden" name="periode_akhir" value="{{ $akhir }}">

            <button type="submit" class="px-4 py-2 text-sm text-white bg-green-700 rounded hover:bg-green-800">
                <i class="bi bi-download me-1"></i> Backup Data
            </button>
        </form>

        {{-- Tombol Hapus Data --}}
        <form action="{{ route('staff.riwayat_presensi.clear') }}" method="POST"
              onsubmit="return confirm('Yakin ingin menghapus data presensi sesuai periode ini?');">
            @csrf
            @method('DELETE')
            <input type="hidden" name="jenis_presensi" value="{{ $jenis }}">
            <input type="hidden" name="periode_mulai" value="{{ $mulai }}">
            <input type="hidden" name="periode_akhir" value="{{ $akhir }}">

            <button type="submit" class="px-4 py-2 text-sm text-white bg-red-700 rounded hover:bg-red-800">
                <i class="bi bi-trash me-1"></i> Hapus Data
            </button>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="overflow-x-auto">
    <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-sm font-semibold text-left text-gray-700">Tanggal</th>
                <th class="px-4 py-2 text-sm font-semibold text-left text-gray-700">Nama Petugas</th>
                <th class="px-4 py-2 text-sm font-semibold text-left text-gray-700">
                    {{ $jenis === 'staff' ? 'Nama Staff' : 'Nama Guru' }}
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-left text-gray-700">Jam Ke</th>
                <th class="px-4 py-2 text-sm font-semibold text-left text-gray-700">Kehadiran</th>
                <th class="px-4 py-2 text-sm font-semibold text-left text-gray-700">Apel</th>
                <th class="px-4 py-2 text-sm font-semibold text-left text-gray-700">Upacara</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($rekap as $row)
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-600">{{ $row->nama_petugas ?? '-' }}</td>
                    <td class="px-4 py-2 text-sm text-gray-600">
                        {{ $row->nama_guru ?? $row->nama_staff ?? '-' }}
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-600">{{ $row->sesi ?? '-' }}</td>
                    <td class="px-4 py-2 text-sm text-gray-600">{{ $row->keterangan ?? '-' }}</td>
                    <td class="px-4 py-2 text-sm text-gray-600">{{ $row->apel ?? '-' }}</td>
                    <td class="px-4 py-2 text-sm text-gray-600">{{ $row->upacara ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-2 text-center text-gray-500">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Navigasi Pagination --}}
<div class="mt-4">
    {{ $rekap->links('pagination::tailwind') }}
</div>
