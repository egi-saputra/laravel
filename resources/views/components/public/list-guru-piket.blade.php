<!-- Tabel Jadwal Piket -->
            <div>
                    <h2 class="mb-4 text-lg font-bold">Jadwal Tugas Guru Piket <span class="hidden capitalize text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span></h2><hr class="mb-4">

                <div class="overflow-x-auto md:overflow-x-visible">
                    <table class="w-full border-collapse border-lg" id="jadwalTable">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="w-16 px-4 py-2 text-center border whitespace-nowrap">No</th>
                            <th class="px-4 py-2 text-center border whitespace-nowrap">Hari</th>
                            <th class="px-4 py-2 border whitespace-nowrap">Petugas Piket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwalPiket ?? [] as $j)
                        <tr>
                            <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 pl-4 border whitespace-nowrap">{{ $j->hari }}</td>
                            <td class="px-4 py-2 pl-4 border whitespace-nowrap">{{ $j->user->name ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-2 text-center">Belum ada jadwal piket</td>
                        </tr>
                        @endforelse
                    </tbody>
                    </table>
                </div>
            </div>
