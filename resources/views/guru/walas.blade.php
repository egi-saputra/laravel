<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Kelola Data Siswa') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">

        <aside class="hidden mx-0 mt-2 mb-4 md:block md:top-0 md:ml-6 md:mt-6 md:w-auto">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Footer -->
            <x-footer :profil="$profil" />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-0 mb-16 space-y-2 overflow-x-auto md:space-y-6 md:mb-0 md:p-6">
            {{-- <div class="flex items-center justify-center w-full p-10 bg-white rounded shadow">
                <h2 class="mb-0 text-lg font-bold">
                    Kelola Ruang Kelas
                    <span class="hidden capitalize text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span>
                </h2>
                <hr class="mb-4">
            </div> --}}

            <!-- Form Tambah Siswa + Upload Excel -->
            <div class="p-4 mb-6 bg-white rounded-lg shadow md:mb-8">
                {{-- <h1 class="mb-4 text-lg font-bold">Tambahkan Data Siswa <span class="hidden md:inline-block">Untuk Kelasmu!</span></h1> --}}
                <h1 class="flex items-center gap-2 mb-4 text-lg font-semibold md:text-xl md:font-bold text-slate-800">
                    <i class="bi bi-journal-bookmark"></i>
                    Tambahkan Data Siswa
                </h1>

                <!-- Form Input Manual -->
                <form action="{{ route('guru.walas.store') }}" method="POST">
                    @csrf
                    <div class="flex flex-col gap-4 md:flex-row md:gap-4">
                        <!-- Kelas otomatis -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1 font-medium">Kelas</label>
                            <input type="text" value="{{ $kelas->kelas ?? '-' }}"
                                class="w-full px-3 py-2 mb-2 bg-gray-100 border rounded" readonly disabled>
                            <input type="hidden" name="kelas_id" value="{{ $kelas->id ?? '' }}">
                            <small class="mb-4 text-red-600">* Kolom kelas auto generate <span class="hidden md:inline-block">berdasarkan kelas yang diwalikan!</span></small>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 md:flex-row md:gap-4">
                        <!-- NIS -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1 font-medium">NIS (Opsional)</label>
                            <input type="text" name="nis" minlength="4" class="w-full px-3 py-2 border rounded">
                        </div>

                        <!-- NISN -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1 font-medium">NISN (Opsional)</label>
                            <input type="text" name="nisn" minlength="10" maxlength="10" class="w-full px-3 py-2 mb-4 border rounded">
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 md:flex-row md:gap-4">
                        <!-- Nama Lengkap -->
                        <div class="flex flex-col flex-1">
                            <label class="block font-medium">Nama Peserta Didik</label>
                            <input type="text" name="nama_lengkap" class="w-full px-3 py-2 border rounded" required>
                        </div>

                        <!-- Email -->
                        <div class="flex flex-col flex-1">
                            <label class="block font-medium">Email Addres</label>
                            <input type="email" name="email" class="w-full px-3 py-2 border rounded" required>
                            @error('email')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Hidden default password -->
                    <input type="hidden" name="password" value="{{ env('DEFAULT_SISWA_PASSWORD', 'password') }}">
                    <input type="hidden" name="role" value="siswa">
                    <p class="pl-1 mt-4 text-xs text-red-600">Keterangan :</p>
                    <p class="pl-1 mb-6 text-xs text-gray-500">Password akan terisi otomatis dengan <span class="italic font-semibold text-slate-700"> "password"</span></p>

                    <div class="flex justify-end md:justify-start">
                        <button type="submit" class="px-4 py-2 mt-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </div>
                </form>

                <hr class="my-6">

                <!-- Tombol Upload & Export -->
                {{-- <div class="flex flex-wrap items-center justify-end gap-3 mt-4">
                    <!-- Form Import -->
                    <form action="{{ route('guru.walas.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                        @csrf
                        <input type="file" name="file" required accept=".xls,.xlsx,.csv"
                            class="px-3 py-2 text-sm border rounded cursor-pointer focus:outline-none focus:ring focus:border-blue-300">
                        <button type="submit"
                                class="px-4 py-2 text-white bg-green-700 rounded hover:bg-green-800">
                            <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
                        </button>
                    </form>

                    <!-- Download Template -->
                    <a href="{{ route('guru.walas.template') }}"
                    class="px-4 py-2 text-white rounded bg-slate-700 hover:bg-slate-800">
                        <i class="bi bi-download me-1"></i> Download Template
                    </a>
                </div> --}}

                <!-- Tombol Upload & Export -->
                <div class="flex flex-col items-end gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                    {{-- Import User --}}
                    <form action="{{ route('guru.walas.import') }}" method="POST" enctype="multipart/form-data"
                        class="flex flex-col w-full gap-2 sm:flex-row sm:w-auto sm:items-center">
                        @csrf
                        <input type="file" name="file" required accept=".xls,.xlsx,.csv"
                            class="w-full p-2 text-sm border rounded focus:ring focus:ring-green-200 sm:w-auto">
                        <button type="submit"
                                class="w-full px-4 py-2 font-semibold text-white bg-green-700 rounded shadow sm:w-auto hover:bg-green-800">
                            <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
                        </button>
                    </form>

                    {{-- Export Template --}}
                    <a href="{{ route('guru.walas.template') }}"
                    class="w-full px-4 py-2 font-semibold text-center text-white rounded shadow bg-slate-700 hover:bg-slate-800 sm:w-auto sm:ml-2">
                        <i class="bi bi-download me-1"></i> Download Template
                    </a>
                </div>
            </div>

            <!-- Tabel Data Siswa -->
            <div class="p-4 bg-white rounded-lg shadow">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="flex items-center gap-2 text-xl font-bold text-slate-800"><i class="bi bi-person-lines-fill"></i> Daftar Siswa ( {{ $kelas->kelas ?? '' }} )</h2>

                    <!-- Tombol Hapus Semua -->
                    {{-- <div>
                        <button id="hapusSemua" type="button" class="flex items-center px-4 py-2 text-white bg-red-700 rounded hover:bg-red-800">
                            <i class="bi bi-trash me-1"></i>
                            <p>Hapus <span class="hidden sm:inline">Semua</span></p>
                        </button>
                        <form id="formHapusSemua" action="{{ route('guru.walas.destroyAll') }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div> --}}
                </div>

                <!-- Search Box -->
                <div class="relative mb-4">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 border-r text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                        </svg>
                    </span>
                    <input type="text" id="searchInput"
                        placeholder="Search ...."
                        class="w-full py-2.5 pl-12 pr-4 border border-slate-300 rounded-xl text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 transition">
                </div>

                <!-- Tombol Hapus Semua -->
                <div class="flex flex-wrap items-center justify-end gap-3 mb-4">
                    <button id="hapusSemua" type="button"
                        class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg shadow bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800">
                        <i class="bi bi-trash"></i> <span>Hapus Semua</span>
                    </button>
                    <form id="formHapusSemua"
                        action="{{ route('guru.walas.destroyAll') }}"
                        method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto md:overflow-x-visible">
                    <table class="w-full mb-10 border border-collapse md:mb-0" id="siswaTable">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="w-12 px-4 py-2 text-sm text-center border md:text-base">No</th>
                                <th class="px-4 py-2 text-sm text-left border md:text-base md:text-center whitespace-nowrap">Nama Lengkap</th>
                                <th class="px-4 py-2 text-sm text-left border md:text-center md:text-base whitespace-nowrap">Email Siswa</th>
                                <th class="px-4 py-2 text-sm text-center border md:text-base whitespace-nowrap">NIS</th>
                                <th class="px-4 py-2 text-sm text-center border md:text-base whitespace-nowrap">NISN</th>
                                <th class="px-4 py-2 text-sm text-center border md:text-base whitespace-nowrap">Kelas</th>
                                <th class="w-24 px-4 py-2 text-center border"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswa ?? [] as $s)
                            <tr class="hover:bg-gray-50" x-data="{ open: false, showModal: false }">
                                <td class="px-4 py-2 text-sm text-center border md:text-base">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 text-sm border md:text-base whitespace-nowrap">{{ $s->nama_lengkap }}</td>
                                <td class="px-4 py-2 text-sm border md:text-base whitespace-nowrap">{{ $s->user->email ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm text-center border md:text-base whitespace-nowrap">{{ $s->nis ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm text-center border md:text-base whitespace-nowrap">{{ $s->nisn ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm text-center border md:text-base whitespace-nowrap">{{ $s->kelas->kelas ?? '-' }}</td>
                                <td class="px-4 py-2 text-center border">
                                    <div class="relative inline-block">
                                        <!-- Tombol ⋮ -->
                                        <button @click="open = !open" class="px-2 py-1 rounded hover:bg-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-6 h-6 text-gray-700"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                                            </svg>
                                        </button>

                                        <!-- Dropdown -->
                                        <div x-show="open" @click.away="open = false" x-transition
                                            class="absolute top-0 z-20 mr-2 bg-white border rounded shadow-md right-full w-28">
                                            <button type="button" @click="showModal = true; open = false"
                                                    class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                                                Edit
                                            </button>
                                            <form action="{{ route('guru.walas.destroy', $s->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="w-full px-4 py-2 text-left text-red-600 hover:bg-red-100">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Modal Edit -->
                                        <div x-show="showModal" x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                            <div class="w-full max-w-md p-6 mx-4 bg-white rounded shadow-lg md:mx-0">
                                                <h2 class="mb-4 text-lg font-bold">Edit Siswa</h2>
                                                <form action="{{ route('guru.walas.update', $s->id) }}" method="POST" class="space-y-3">
                                                    @csrf
                                                    @method('PUT')
                                                    <div>
                                                        <label class="block font-medium text-left">Nama Lengkap</label>
                                                        <input type="text" name="nama_lengkap" value="{{ $s->nama_lengkap }}"
                                                            class="w-full px-3 py-2 border rounded" required>
                                                    </div>
                                                    <div>
                                                        <label class="block font-medium text-left">Email Siswa</label>
                                                        <input type="email" name="email" value="{{ $s->user->email ?? '' }}"
                                                            class="w-full px-3 py-2 border rounded" required>
                                                    </div>
                                                    <div>
                                                        <label class="block font-medium text-left">Nomor NIS</label>
                                                        <input type="text" name="nis" value="{{ $s->nis ?? '' }}" class="w-full px-3 py-2 border rounded" minlength="4">
                                                    </div>
                                                    <div>
                                                        <label class="block font-medium text-left">Nomor NISN</label>
                                                        <input type="text" name="nisn" value="{{ $s->nisn ?? '' }}" class="w-full px-3 py-2 border rounded" minlength="10" maxlength="10">
                                                    </div>

                                                    <!-- Dropdown Jabatan -->
                                                    <div>
                                                        <label class="block font-medium text-left">Struktur Kelas</label>
                                                        <select name="jabatan_siswa" class="w-full px-3 py-2 border rounded">
                                                            <option value="Tidak Ada" {{ $s->jabatan_siswa == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                                            <option value="Sekretaris" {{ $s->jabatan_siswa == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                                                            <option value="Bendahara" {{ $s->jabatan_siswa == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                                                        </select>
                                                    </div>

                                                    <div class="flex justify-end gap-2">
                                                        <button type="button" @click="showModal = false"
                                                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                                                            Batal
                                                        </button>
                                                        <button type="submit"
                                                                class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                                                            Simpan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-2 text-center">Belum ada data siswa</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Failed Updated!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        </script>
    @endif

    @if(session('alert'))
        <script>
            Swal.fire({
                icon: "{{ session('alert.type') }}",
                title: "{{ session('alert.title') }}",
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
                html: {!! json_encode(session('alert.message'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!},
                width: '90%',
                didOpen: (popup) => {
                    const content = popup.querySelector('.swal2-html-container');
                    if(content) {
                        content.style.maxHeight = '300px';
                        content.style.overflowY = 'auto';
                    }
                }
            });
        </script>
    @endif

    <!-- Script Search & Hapus Semua -->
    <script>
        document.getElementById('hapusSemua').addEventListener('click', function() {
            Swal.fire({
                title: 'Yakin hapus semua data siswa?',
                text: "Semua data siswa akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formHapusSemua').submit();
                }
            });
        });

        document.getElementById('searchInput').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#siswaTable tbody tr");

            rows.forEach(row => {
                let nama = row.cells[1]?.textContent.toLowerCase();
                let email = row.cells[2]?.textContent.toLowerCase();
                let nis = row.cells[3]?.textContent.toLowerCase();
                let nisn = row.cells[4]?.textContent.toLowerCase();
                let kelas = row.cells[5]?.textContent.toLowerCase();

                if (nama.includes(filter) || email.includes(filter) || nis.includes(filter) || nisn.includes(filter) || kelas.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-backtop-layout>
