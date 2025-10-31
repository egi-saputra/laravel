<!-- Card Jadwal Piket -->
<div class="w-full p-3 mb-10 overflow-hidden border rounded-lg shadow md:p-6 bg-gradient-to-r from-sky-50 via-white to-indigo-100 border-slate-200">

    <!-- Header Card -->
    <div class="flex flex-col items-center justify-between mb-6 text-center md:flex-row md:text-left">
        <h2 class="text-lg font-bold tracking-wide md:text-2xl text-slate-800">
            Jadwal Petugas Guru Piket
            <span class="block mt-1 text-base font-semibold text-transparent capitalize bg-gradient-to-r from-sky-600 via-blue-500 to-indigo-600 bg-clip-text md:inline-block md:mt-0">
                | {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |
            </span>
        </h2>
        <div class="w-24 h-1 mt-3 rounded-full md:mt-0 bg-gradient-to-r from-sky-500 to-indigo-600 animate-pulse"></div>
    </div>

    <!-- Tabel Wrapper -->
    <div class="overflow-x-auto border rounded-lg shadow-inner border-slate-200 bg-white/60 backdrop-blur-sm">
        <table class="w-full text-sm text-slate-700">
            <thead>
                <tr class="text-center text-white bg-gradient-to-r from-sky-600 to-indigo-600">
                    <th class="px-4 py-3 rounded-tl-lg">No</th>
                    <th class="px-4 py-3">Hari</th>
                    <th class="px-4 py-3 rounded-tr-lg">Petugas Piket</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwalPiket ?? [] as $j)
                    <tr class="transition-colors duration-200 even:bg-slate-50 hover:bg-sky-50">
                        <td class="px-4 py-3 font-medium text-center text-slate-800">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-center">{{ $j->hari }}</td>
                        <td class="px-4 py-3 font-semibold text-left md:text-center text-sky-700">{{ $j->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-6 italic text-center text-slate-500">Belum ada jadwal piket</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
