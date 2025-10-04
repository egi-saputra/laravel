@php
    $userLoginId = $user->id;
@endphp

<div class="space-y-4">
    <form action="{{ route('siswa.presensi.store') }}" method="POST">
        @csrf
        <div class="overflow-x-auto md:overflow-x-visible">
            <table class="min-w-full border border-gray-300">
                <thead class="text-center bg-gray-100">
                    <tr>
                        <th class="hidden py-2 border border-gray-300 md:table-cell whitespace-nowrap">No</th>
                        <th class="px-4 py-2 text-left border border-gray-300 md:text-center whitespace-nowrap ">Nama Siswa</th>
                        <th class="px-4 py-2 border border-gray-300 whitespace-nowrap">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaKelas as $index => $siswa)
                        @php
                            $lastPresensi = $presensiHariIni[$siswa->id] ?? null;
                            $lastKeterangan = $lastPresensi->keterangan ?? 'OFF';
                            $highlight = $siswa->user_id === $userLoginId ? 'bg-yellow-100 font-semibold' : '';
                        @endphp
                        <tr class="{{ $highlight }}">
                            <td class="hidden px-4 py-2 text-center border border-gray-300 md:table-cell whitespace-nowrap">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border border-gray-300 whitespace-nowrap">{{ $siswa->nama_lengkap }}</td>
                            <td class="px-4 py-2 text-center border border-gray-300 whitespace-nowrap">
                                <select name="keterangan[{{ $siswa->id }}]" class="p-1 px-8 border rounded" {{ $presensiSelesai ? 'disabled' : '' }}>
                                    <option value="Hadir" {{ $lastKeterangan === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="Sakit" {{ $lastKeterangan === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="Izin" {{ $lastKeterangan === 'Izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="Alpa" {{ $lastKeterangan === 'Alpa' ? 'selected' : '' }}>Alpa</option>
                                    <option value="OFF" {{ $lastKeterangan === 'OFF' ? 'selected' : '' }}>OFF</option>
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada siswa di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-center mt-4 md:justify-start">
            <button type="submit"
                class="w-full px-4 py-2 text-center text-white bg-blue-600 rounded md:w-auto hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                {{ $presensiSelesai ? 'disabled' : '' }}>
                Submit / Update Presensi Siswa
            </button>
        </div>
    </form>

    <form action="{{ route('siswa.presensi.selesai') }}" method="POST" class="mt-4">
        @csrf
        <div class="flex justify-end md:justify-start">
            <button type="submit"
                class="px-6 py-2 text-white bg-red-600 rounded hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                {{ $presensiSelesai ? 'disabled' : '' }}>
                Tandai Presensi Selesai
            </button>
        </div>
    </form>
</div>
