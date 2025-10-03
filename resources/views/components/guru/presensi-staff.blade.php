<div class="space-y-4 rounded">
    <form action="{{ route('guru.presensi.staff.store') }}" method="POST">
        @csrf

        <!-- Tanggal & Hari -->
        <div class="flex flex-col mb-4 space-y-4 md:flex-row md:space-x-4 md:space-y-0">

            <!-- Hari & Tanggal -->
            <div class="flex w-full gap-4 md:block">
                <div class="w-1/2 mb-2 md:w-auto md:mb-0">
                    <label for="hari"><small>Hari</small></label>
                    <input type="text" name="hari" value="{{ $hariIni }}" readonly class="w-full p-2 border rounded">
                </div>
                <div class="w-1/2 mb-2 md:w-auto md:mb-0">
                    <label for="tanggal"><small>Tanggal</small></label>
                    <input type="number" name="tanggal" value="{{ $tanggal }}" class="w-full p-2 border rounded" required>
                </div>
            </div>

            <!-- Bulan & Tahun -->
            <div class="flex w-full gap-4 md:block">
                <div class="w-1/2 mb-2 md:w-auto md:mb-0">
                    <label for="bulan"><small>Bulan</small></label>
                    <input type="number" name="bulan" value="{{ $bulan }}" class="w-full p-2 border rounded" required>
                </div>
                <div class="w-1/2 mb-2 md:w-auto md:mb-0">
                    <label for="tahun"><small>Tahun</small></label>
                    <input type="number" name="tahun" value="{{ $tahun }}" class="w-full p-2 border rounded" required>
                </div>
            </div>

        </div>

        <!-- Tabel Presensi Staff -->
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead class="text-center bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border border-gray-300 whitespace-nowrap min-w-max">Nama Staff</th>
                        <th class="px-4 py-2 border border-gray-300 whitespace-nowrap">Keterangan Kehadiran</th>
                        <th class="px-4 py-2 border border-gray-300 whitespace-nowrap">Apel</th>
                        <th class="px-4 py-2 border border-gray-300 whitespace-nowrap">Upacara</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staffHariIni as $staff)
                        @php
                            $lastPresensi = $presensiStaffHariIni->firstWhere('staff_id', $staff->id);
                            $lastKeterangan = $lastPresensi->keterangan ?? 'Tidak Hadir';
                            $lastApel = $lastPresensi->apel ?? 'Tidak';
                            $lastUpacara = $lastPresensi->upacara ?? 'Tidak';
                        @endphp
                        <tr>
                            <td class="px-4 py-2 border border-gray-300 whitespace-nowrap min-w-max">{{ $staff->name }}</td>

                            <!-- Keterangan -->
                            <td class="px-4 py-2 text-center border border-gray-300 whitespace-nowrap">
                                <select name="keterangan[{{ $staff->id }}]">
                                    <option value="Hadir" {{ $lastKeterangan === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="Tidak Hadir" {{ $lastKeterangan === 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                    <option value="Sakit" {{ $lastKeterangan === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="Izin" {{ $lastKeterangan === 'Izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="Tanpa Keterangan" {{ $lastKeterangan === 'Tanpa Keterangan' ? 'selected' : '' }}>Tanpa Keterangan</option>
                                </select>
                            </td>

                            <!-- Apel -->
                            <td class="px-4 py-2 text-center border border-gray-300 whitespace-nowrap">
                                <select name="apel[{{ $staff->id }}]">
                                    <option value="Apel" {{ $lastApel === 'Apel' ? 'selected' : '' }}>Apel</option>
                                    <option value="Tidak" {{ $lastApel === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </td>

                            <!-- Upacara -->
                            <td class="px-4 py-2 text-center border border-gray-300 whitespace-nowrap">
                                <select name="upacara[{{ $staff->id }}]">
                                    <option value="Upacara" {{ $lastUpacara === 'Upacara' ? 'selected' : '' }}>Upacara</option>
                                    <option value="Tidak" {{ $lastUpacara === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center border border-gray-300 whitespace-nowrap">Tidak ada staff hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Submit Button -->
        <div class="mt-4">
            <button type="submit"
                class="px-6 py-2 mt-3 text-white bg-blue-600 rounded hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                @if($presensiSelesai) disabled @endif>
                Submit / Update Presensi Staff
            </button>
        </div>
    </form>
</div>

<!-- Pagination Staff -->
<div class="mt-4">
    {{ $staffHariIni->appends(request()->except('staff_page'))->links('pagination::tailwind') }}
</div>
