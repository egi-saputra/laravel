{{-- @php
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
</div> --}}

{{-- =================================================== --}}

@php
    $userLoginId = $user->id;
@endphp

<div class="space-y-4">
    <form action="{{ route('siswa.presensi.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($siswaKelas as $index => $siswa)
                @php
                    $lastPresensi = $presensiHariIni[$siswa->id] ?? null;
                    $lastKeterangan = $lastPresensi->keterangan ?? 'OFF';
                    $highlight = $siswa->user_id === $userLoginId
                        ? 'ring-2 ring-yellow-400 bg-yellow-50'
                        : 'hover:shadow-lg';
                @endphp

                <div class="relative p-4 md:mx-0 mx-2 transition-all border rounded-xl shadow-sm bg-white {{ $highlight }}">
                    <span class="absolute px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded-full shadow top-2 right-2">
                        {{ $index + 1 }}
                    </span>

                    <p class="mb-3 text-base font-bold text-gray-900">
                        {{ $siswa->nama_lengkap }}
                    </p>

                    <div class="flex justify-between">
                        <label class="text-sm font-medium text-gray-700">Keterangan</label>
                        @if($presensiSelesai)
                            <span class="px-2 text-xs text-red-700 bg-red-100 rounded">
                                🔒 Presensi terkunci
                            </span>
                        @endif
                    </div>

                    <select name="keterangan[{{ $siswa->id }}]"
                        class="w-full p-2 mt-1 text-sm border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-400"
                        {{ $presensiSelesai ? 'disabled' : '' }}>
                        <option value="Hadir" {{ $lastKeterangan === 'Hadir' ? 'selected' : '' }}>✅ Hadir</option>
                        <option value="Sakit" {{ $lastKeterangan === 'Sakit' ? 'selected' : '' }}>🤒 Sakit</option>
                        <option value="Izin" {{ $lastKeterangan === 'Izin' ? 'selected' : '' }}>📄 Izin</option>
                        <option value="Alpa" {{ $lastKeterangan === 'Alpa' ? 'selected' : '' }}>❌ Alpa</option>
                        <option value="OFF" {{ $lastKeterangan === 'OFF' ? 'selected' : '' }}>⛔ OFF</option>
                    </select>
                </div>
            @empty
                <p class="col-span-3 text-center text-gray-600">
                    Tidak ada siswa di kelas ini.
                </p>
            @endforelse
        </div>

        <div class="flex justify-center mt-6 md:justify-start">
            <button type="submit"
                class="w-full px-5 py-3 text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 md:w-auto disabled:bg-gray-400 disabled:cursor-not-allowed"
                {{ $presensiSelesai ? 'disabled' : '' }}>
                🚀 Submit / Update Presensi
            </button>
        </div>
    </form>

    <form action="{{ route('siswa.presensi.selesai') }}" method="POST" class="mt-2">
        @csrf
        <div class="flex justify-center md:justify-start">
            <button type="submit"
                class="px-6 py-3 text-white rounded-lg shadow bg-slate-600 hover:bg-slate-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                {{ $presensiSelesai ? 'disabled' : '' }}>
                ✅ Tandai Presensi Selesai
            </button>
        </div>
    </form>
</div>

