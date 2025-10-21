<x-app-dashboard-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Kelola Data Siswa') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="z-0 mx-4 mt-4 md:z-10 top-16 md:top-0 md:ml-6 md:mt-6 md:h-screen md:mx-0 md:w-auto">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
            <!-- Form Tambah Siswa + Upload Excel -->
            <div class="p-4 bg-white rounded shadow">
                <h1 class="inline-block mb-4 text-lg font-bold border-b-2">Tambah Data Peserta Didik</h1>

                <!-- Form Input Manual -->
                <form action="{{ route('admin.siswa.store') }}" method="POST" >
                    @csrf
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block font-medium">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="w-full px-3 py-2 border rounded" required>
                        </div>
                        <div>
                            <label class="block font-medium">Email</label>
                            <input type="email" name="email" class="w-full px-3 py-2 border rounded" required>
                            @error('email')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block font-medium">NIS</label>
                            <input type="text" name="nis" maxlength="20" class="w-full px-3 py-2 border rounded">
                        </div>
                        <div>
                            <label class="block font-medium">NISN</label>
                            <input type="text" name="nisn" maxlength="10" class="w-full px-3 py-2 border rounded" required>
                        </div>
                        <div>
                            <label class="block font-medium">Kelas</label>
                            <select name="kelas_id" class="w-full px-3 py-2 border rounded" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach(\App\Models\DataKelas::all() as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium">Program Kejuruan</label>
                            <select name="kejuruan_id" class="w-full px-3 py-2 border rounded" required>
                                <option value="">-- Pilih Kejuruan --</option>
                                @foreach(\App\Models\DataKejuruan::all() as $kj)
                                    <option value="{{ $kj->id }}">{{ $kj->nama_kejuruan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Hidden default jenis_kelamin & agama -->
                    <input type="hidden" name="jenis_kelamin" value="Laki-laki">
                    <input type="hidden" name="agama" value="Islam">
                    <!-- hidden: password default & role -->
                    <input type="hidden" name="password" value="{{ env('DEFAULT_SISWA_PASSWORD', 'password') }}">
                    <input type="hidden" name="role" value="siswa">
                    <p class="pl-1 mt-4 mb-2 text-xs text-red-600">Keterangan :</p>
                    <p class="pl-1 mb-6 text-xs text-gray-500">Password akan terisi otomatis secara default dengan <span class="italic font-semibold text-slate-700"> "password"</span></p>

                    <div class="flex justify-end mt-4 md:justify-start">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </div>
                </form>

                <hr class="my-6">

                <!-- Tombol Upload & Export -->
                {{-- <div class="flex flex-wrap items-center justify-end gap-3 mt-4">
                    <!-- Form Import -->
                    <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                        @csrf
                        <input type="file" name="file" required accept=".xls,.xlsx,.csv"
                        class="px-3 py-2 text-sm border rounded cursor-pointer focus:outline-none focus:ring focus:border-blue-300">
                        <button type="submit"
                                class="px-4 py-2 text-white bg-green-700 rounded hover:bg-green-800">
                            <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
                        </button>
                    </form>

                    <!-- Download Template -->
                    <a href="{{ route('admin.siswa.template') }}"
                    class="px-4 py-2 text-white rounded bg-slate-700 hover:bg-slate-800">
                        <i class="bi bi-download me-1"></i> Download Template
                    </a>
                </div> --}}

                    <!-- Tombol Upload & Export -->
                    <div class="flex flex-col items-end gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                        {{-- Import User --}}
                        <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data"
                            class="flex flex-col w-full gap-2 sm:flex-row sm:w-auto sm:items-center">
                            @csrf
                            <input type="file" name="file" required accept=".xls,.xlsx,.csv"
                                class="w-full p-2 text-sm border rounded-lg focus:ring focus:ring-green-200 sm:w-auto">
                            <button type="submit"
                                    class="w-full px-4 py-2 font-semibold text-white bg-green-700 rounded shadow sm:w-auto hover:bg-green-800">
                                <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
                            </button>
                        </form>

                        {{-- Export Template --}}
                        <a href="{{ route('admin.siswa.template') }}"
                        class="w-full px-4 py-2 font-semibold text-center text-white rounded shadow bg-slate-700 hover:bg-slate-800 sm:w-auto sm:ml-2">
                            <i class="bi bi-download me-1"></i> Download Template
                        </a>
                    </div>
            </div>

            <!-- Tabel Data Siswa -->
            <div class="p-4 bg-white rounded shadow">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold">Daftar Peserta Didik</h2>

                    <!-- Tombol Hapus Semua -->
                    <div>
                        <button id="hapusSemua" type="button"
                            class="px-4 py-2 text-white bg-red-700 rounded hover:bg-red-800">
                            <i class="bi bi-trash me-1"></i> Hapus <span class="hidden md:inline-block">Semua</span>
                        </button>
                        <form id="formHapusSemua" action="{{ route('admin.siswa.destroyAll') }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <x-admin.tabel-siswa :siswa="$siswa" :kelasList="$kelasList" :kejuruanList="$kejuruanList" :sekolah="$sekolah" />
            </div>

            <div class="mt-4">
                {{ $siswa->links('pagination::tailwind') }}
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />

</x-app-dashboard-layout>
