<!-- Tabel Data Struktural -->
<div>
    <div>
        <h2 class="mb-4 text-lg font-bold">Struktur Jabatan Internal Sekolah <span class="hidden capitalize text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span></h2><hr class="mb-4">
    </div>

    <div class="overflow-x-auto md:overflow-x-visible">
        <table class="w-full border border-collapse" id="strukturalTable">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-center border">No</th>
                    <th class="px-4 py-2 border">Jabatan</th>
                    <th class="px-4 py-2 border">Nama Lengkap</th>
                </tr>
            </thead>
            <tbody>
                @forelse($struktural ?? [] as $s)
                    <tr>
                        <td class="px-4 py-2 text-center border">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border">{{ $s->jabatan }}</td>
                        <td class="px-4 py-2 border">{{ $s->guru->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-2 text-center">Belum ada data struktural</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

