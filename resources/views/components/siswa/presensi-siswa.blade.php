@php
    $userLoginId = $user->id;
@endphp

<div class="p-6 space-y-4">
    <form action="{{ route('siswa.presensi.store') }}" method="POST">
        @csrf
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead class="text-center bg-gray-100">
                    <tr>
                        <th class="py-2 border border-gray-300">No</th>
                        <th class="px-4 py-2 border border-gray-300">Nama Siswa</th>
                        <th class="px-4 py-2 border border-gray-300">Keterangan</th>
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
                            <td class="px-4 py-2 text-center border border-gray-300">{{ $index + 1 }}</td>
                            {{-- <td class="px-4 py-2 border border-gray-300">
                                {{ optional($siswa->user)->name ?? $siswa->nama_lengkap }}
                            </td> --}}
                            <td class="px-4 py-2 border border-gray-300">{{ $siswa->nama_lengkap }}</td>
                            <td class="px-4 py-2 text-center border border-gray-300">
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

        <div class="mt-4">
            <button type="submit"
                class="px-6 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                {{ $presensiSelesai ? 'disabled' : '' }}>
                Submit / Update Presensi Siswa
            </button>
        </div>
    </form>

    <form action="{{ route('siswa.presensi.selesai') }}" method="POST" class="mt-4">
        @csrf
        <button type="submit"
            class="px-6 py-2 text-white bg-red-600 rounded hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
            {{ $presensiSelesai ? 'disabled' : '' }}>
            Tandai Presensi Selesai
        </button>
    </form>
</div>
