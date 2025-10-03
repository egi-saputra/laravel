<div class="space-y-4 rounded">
    <form action="{{ route('guru.presensi.store') }}" method="POST">
        @csrf

        <!-- Tanggal & Hari -->
        <div class="flex flex-col justify-center mb-4 space-y-4 md:flex-row md:space-x-4 md:space-y-0">

            <!-- Hari & Tanggal -->
            <div class="flex w-full gap-4 md:block">
                <div class="w-1/2 md:w-auto">
                    <label for="hari"><small>Hari</small></label>
                    <input type="text" name="hari" value="{{ $hariIni }}" readonly class="w-full p-2 border rounded">
                </div>

                <div class="w-1/2 md:w-auto">
                    <label for="tanggal"><small>Tanggal</small></label>
                    <input type="number" name="tanggal" value="{{ $tanggal }}" class="w-full p-2 border rounded" required>
                </div>
            </div>

            <!-- Bulan & Tahun -->
            <div class="flex w-full gap-4 md:block">
                <div class="w-1/2 md:w-auto">
                    <label for="bulan"><small>Bulan</small></label>
                    <input type="number" name="bulan" value="{{ $bulan }}" class="w-full p-2 border rounded" required>
                </div>

                <div class="w-1/2 md:w-auto">
                    <label for="tahun"><small>Tahun</small></label>
                    <input type="number" name="tahun" value="{{ $tahun }}" class="w-full p-2 border rounded" required>
                </div>
            </div>
        </div>

        <!-- Tabel Presensi -->
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead class="text-center bg-gray-100">
                    <tr>
                        <th class="py-2 border border-gray-300 whitespace-nowrap">Jam</th>
                        <th class="px-4 py-2 border border-gray-300 whitespace-nowrap">Nama Guru</th>
                        <th class="px-4 py-2 border border-gray-300 whitespace-nowrap">Sesi</th>
                        <th class="px-4 py-2 border border-gray-300 whitespace-nowrap">Kelas</th>
                        <th class="px-4 py-2 border border-gray-300 whitespace-nowrap">Keterangan</th>
                        <th class="px-4 py-2 border border-gray-300 whitespace-nowrap">Apel</th>
                        <th class="px-4 py-2 border border-gray-300 whitespace-nowrap">Upacara</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guruHariIni as $jadwal)
                        @php
                            $lastPresensi = $presensiHariIni->firstWhere('jadwal_id', $jadwal->id);
                            $lastKeterangan = $lastPresensi->keterangan ?? 'Tidak Hadir';
                            $lastApel = $lastPresensi->apel ?? 'Tidak';
                            $lastUpacara = $lastPresensi->upacara ?? 'Tidak';
                        @endphp
                        <tr>
                            <td class="px-4 py-2 text-center border border-gray-300 whitespace-nowrap">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                            <td class="px-4 py-2 border border-gray-300 whitespace-nowrap">{{ $jadwal->guru->user->name ?? '-' }}</td>
                            <td class="px-4 py-2 text-center border border-gray-300 whitespace-nowrap">{{ $jadwal->sesi ?? '-' }}</td>
                            <td class="px-4 py-2 text-center border border-gray-300 whitespace-nowrap">{{ $jadwal->kelas->kelas ?? '-' }}</td>

                            <!-- Keterangan -->
                            <td class="px-4 py-2 text-center border border-gray-300 whitespace-nowrap">
                                <select name="keterangan[{{ $jadwal->id }}]">
                                    <option value="Hadir" {{ $lastKeterangan === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="Tidak Hadir" {{ $lastKeterangan === 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                    <option value="Sakit" {{ $lastKeterangan === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="Izin" {{ $lastKeterangan === 'Izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="Tanpa Keterangan" {{ $lastKeterangan === 'Tanpa Keterangan' ? 'selected' : '' }}>Tanpa Keterangan</option>
                                </select>
                            </td>

                            <!-- Apel -->
                            <td class="px-4 py-2 text-center border border-gray-300 whitespace-nowrap">
                                <select name="apel[{{ $jadwal->id }}]">
                                    <option value="Apel" {{ $lastApel === 'Apel' ? 'selected' : '' }}>Apel</option>
                                    <option value="Pembina Apel" {{ $lastApel === 'Pembina Apel' ? 'selected' : '' }}>Pembina Apel</option>
                                    <option value="Tidak" {{ $lastApel === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </td>

                            <!-- Upacara -->
                            <td class="px-4 py-2 text-center border border-gray-300 whitespace-nowrap">
                                <select name="upacara[{{ $jadwal->id }}]">
                                    <option value="Upacara" {{ $lastUpacara === 'Upacara' ? 'selected' : '' }}>Upacara</option>
                                    <option value="Pembina Upacara" {{ $lastUpacara === 'Pembina Upacara' ? 'selected' : '' }}>Pembina Upacara</option>
                                    <option value="Tidak" {{ $lastUpacara === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada guru jadwal hari ini.</td>
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
                Submit / Update Presensi Guru
            </button>
        </div>
    </form>
</div>

<!-- Pagination Guru -->
<div class="mt-4">
    {{ $guruHariIni->appends(request()->except('guru_page'))->links('pagination::tailwind') }}
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const apelSelects = document.querySelectorAll('select[name^="apel"]');
    const upacaraSelects = document.querySelectorAll('select[name^="upacara"]');

    function validatePresensi() {
        // cek apakah ada yang pilih Apel / Pembina Apel
        const apelChosen = Array.from(apelSelects).some(sel => sel.value === 'Apel' || sel.value === 'Pembina Apel');
        const pembinaApel = Array.from(apelSelects).find(sel => sel.value === 'Pembina Apel');

        // cek apakah ada yang pilih Upacara / Pembina Upacara
        const upacaraChosen = Array.from(upacaraSelects).some(sel => sel.value === 'Upacara' || sel.value === 'Pembina Upacara');
        const pembinaUpacara = Array.from(upacaraSelects).find(sel => sel.value === 'Pembina Upacara');

        // 1️⃣ kalau sudah ada yang isi Apel → semua Upacara disable
        if (apelChosen) {
            upacaraSelects.forEach(sel => {
                sel.value = 'Tidak';
                sel.disabled = true;
            });
        } else {
            upacaraSelects.forEach(sel => sel.disabled = false);
        }

        // 2️⃣ kalau sudah ada yang isi Upacara → semua Apel disable
        if (upacaraChosen) {
            apelSelects.forEach(sel => {
                sel.value = 'Tidak';
                sel.disabled = true;
            });
        } else {
            apelSelects.forEach(sel => sel.disabled = false);
        }

        // 3️⃣ pastikan hanya ada satu Pembina Apel
        if (pembinaApel) {
            apelSelects.forEach(sel => {
                if (sel !== pembinaApel && sel.value === 'Pembina Apel') {
                    sel.value = 'Tidak';
                }
            });
        }

        // 4️⃣ pastikan hanya ada satu Pembina Upacara
        if (pembinaUpacara) {
            upacaraSelects.forEach(sel => {
                if (sel !== pembinaUpacara && sel.value === 'Pembina Upacara') {
                    sel.value = 'Tidak';
                }
            });
        }
    }

    // Jalankan saat load
    validatePresensi();

    // Event listener
    apelSelects.forEach(select => select.addEventListener('change', validatePresensi));
    upacaraSelects.forEach(select => select.addEventListener('change', validatePresensi));
});
</script>




