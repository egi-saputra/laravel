<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? '') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <aside class="sticky z-10 w-full top-16 md:static md:w-auto md:ml-6 md:mt-6 md:h-screen md:top-0">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
            <!-- Form Tambah Jadwal Guru + Upload Excel -->
            <div class="p-4 bg-white rounded shadow">
                <h1 class="mb-4 text-lg font-bold">Tambah Jadwal Guru</h1>

                <!-- Form Input Manual -->
                <form action="{{ route('admin.jadwal_guru.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf

                    <!-- Baris 1: Hari & Sesi -->
                    <div class="md:flex md:space-x-4">
                        <div class="mb-3 md:w-1/2 md:mb-0">
                            <label class="block font-medium">Hari</label>
                            <select name="hari" class="w-full px-3 py-2 border rounded" required>
                                <option value="">-- Pilih Hari --</option>
                                {{-- @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h) --}}
                                @foreach($daysOrder as $h)
                                    <option value="{{ $h }}">{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:w-1/2">
                            <label class="block font-medium">Sesi</label>
                            <input type="text" placeholder="Contoh: Pertama" name="sesi" class="w-full px-3 py-2 border rounded" required>
                        </div>
                    </div>

                    <!-- Baris 2: Jam Mulai & Jam Selesai -->
                    <div class="md:flex md:space-x-4">
                        <div class="mb-3 md:w-1/2 md:mb-0">
                            <label class="block font-medium">Jam Mulai</label>
                            <input type="text" name="jam_mulai" class="w-full px-3 py-2 border rounded" placeholder="Contoh: 07:00" required>
                        </div>
                        <div class="md:w-1/2">
                            <label class="block font-medium">Jam Selesai</label>
                            <input type="text" name="jam_selesai" placeholder="Contoh: 08:00" class="w-full px-3 py-2 border rounded" required>
                        </div>
                    </div>

                    <!-- Baris 3: Guru & Kelas -->
                    <div class="md:flex md:space-x-4">
                        <div class="mb-3 md:w-1/2 md:mb-0">
                            <label class="block font-medium">Guru</label>
                            <select name="guru_id" class="w-full px-3 py-2 border rounded" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach($guru as $g)
                                    <option value="{{ $g->id }}">{{ $g->user->name ?? $g->nama }} ({{ $g->kode }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:w-1/2">
                            <label class="block font-medium">Kelas</label>
                            <select name="kelas_id" class="w-full px-3 py-2 border rounded" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->kelas }} ({{ $k->kode }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="px-4 py-2 mt-2 text-white bg-blue-600 rounded hover:bg-blue-700"><i class="bi bi-save"></i>
                            Simpan
                        </button>
                    </div>
                </form>

                <!-- Tombol Upload & Export -->
                <div class="flex flex-wrap items-center justify-end gap-3 mt-3">
                    <form action="{{ route('admin.jadwal_guru.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                        @csrf
                        <input type="file" name="file" required accept=".xls,.xlsx,.csv"
                               class="px-3 py-2 text-sm border rounded cursor-pointer focus:outline-none focus:ring focus:border-blue-300">
                        <button type="submit"
                                class="flex items-center px-3 py-2 text-white bg-green-700 rounded hover:bg-green-800">
                            <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
                        </button>
                    </form>

                    <a href="{{ route('admin.jadwal_guru.export') }}"
                       class="px-4 py-2 text-white rounded bg-slate-700 hover:bg-slate-800">
                        <i class="bi bi-download me-1"></i> Download Template
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto md:overflow-x-visible">
                <!-- Tabel Data Jadwal Guru -->
                <x-admin.jadwal-guru :jadwal="$jadwal" :guru="$guru" :kelas="$kelas" :sekolah="$sekolah" />
            </div>

            <div class="mt-4">
                {{ $jadwal->links('pagination::tailwind') }}
            </div>

        </main>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />

</x-app-backtop-layout>
