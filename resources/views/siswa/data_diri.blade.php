<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Kelola Data Siswa') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="mx-4 mt-4 top-16 md:top-0 md:ml-6 md:mt-6 md:h-screen md:mx-0 md:w-auto">
            <x-sidebar />
        </aside>

        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
            <div class="p-4 bg-white rounded shadow">
                <h1 class="mb-4 text-lg font-bold">Form Input Data Diri Siswa</h1>

                <form action="{{ route('siswa.data_diri.update', $siswa->id) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')

                    <!-- Nama Lengkap & Tempat, Tanggal Lahir -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block font-medium">Nama Lengkap <small class="text-red-500">*</small></label>
                            <input type="text" name="nama_lengkap" class="w-full px-3 py-2 border rounded"
                                value="{{ old('nama_lengkap', $siswa->nama_lengkap ?? '') }}" required>
                        </div>
                        <div>
                            <label class="block font-medium">Tempat, Tanggal Lahir <small class="text-red-500">*</small></label>
                            <input type="text" name="tempat_tanggal_lahir" class="w-full px-3 py-2 border rounded"
                                value="{{ old('tempat_tanggal_lahir', $siswa->tempat_tanggal_lahir ?? '') }}" required>
                        </div>
                    </div>

                    <!-- Asal Sekolah & Telepon -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block font-medium">Asal Sekolah</label>
                            <input type="text" name="asal_sekolah" class="w-full px-3 py-2 border rounded"
                                value="{{ old('asal_sekolah', $siswa->asal_sekolah ?? '') }}" required>
                        </div>
                        <div>
                            <label class="block font-medium">Telepon</label>
                            <input type="text" name="telepon" class="w-full px-3 py-2 border rounded"
                                value="{{ old('telepon', $siswa->telepon ?? '') }}" required>
                        </div>
                    </div>

                    <!-- NIS & NISN -->
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block font-medium">NIS</label>
                            <input type="text" name="nis" class="w-full px-3 py-2 border rounded"
                                value="{{ old('nis', $siswa->nis ?? '') }}" required>
                        </div>
                        <div>
                            <label class="block font-medium">NISN</label>
                            <input type="text" name="nisn" class="w-full px-3 py-2 border rounded"
                                value="{{ old('nisn', $siswa->nisn ?? '') }}" required>
                        </div>
                    </div>

                    <!-- Jenis Kelamin & Agama -->
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block font-medium">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full px-3 py-2 border rounded">
                                <option value="" required>-- Pilih --</option>
                                @foreach(['Laki-laki','Perempuan'] as $jk)
                                    <option value="{{ $jk }}" {{ (old('jenis_kelamin', $siswa->jenis_kelamin ?? '') == $jk) ? 'selected' : '' }}>
                                        {{ $jk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium">Agama</label>
                            <select name="agama" class="w-full px-3 py-2 border rounded">
                                <option value="" required>-- Pilih --</option>
                                @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'] as $a)
                                    <option value="{{ $a }}" {{ (old('agama', $siswa->agama ?? '') == $a) ? 'selected' : '' }}>
                                        {{ $a }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block font-medium">Alamat</label>
                        <textarea name="alamat" rows="2" class="w-full px-3 py-2 border rounded" required>{{ old('alamat', $siswa->alamat ?? '') }}</textarea>
                    </div>

                    <!-- RT, RW, Kecamatan, Kota/Kabupaten, Kode Pos -->
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                        <!-- RT -->
                        <div>
                            <label class="block font-medium">RT</label>
                            <input type="text" name="rt" class="w-full px-3 py-2 border rounded" value="{{ old('rt', $siswa->rt ?? '') }}" required>
                        </div>
                        <!-- RW -->
                        <div>
                            <label class="block font-medium">RW</label>
                            <input type="text" name="rw" class="w-full px-3 py-2 border rounded" value="{{ old('rw', $siswa->rw ?? '') }}" required>
                        </div>
                        <!-- Kecamatan -->
                        <div class="col-span-1 md:col-span-1">
                            <label class="block font-medium">Kecamatan</label>
                            <input type="text" name="kecamatan" class="w-full px-3 py-2 border rounded" value="{{ old('kecamatan', $siswa->kecamatan ?? '') }}" required>
                        </div>
                        <!-- Kota/Kabupaten -->
                        <div class="col-span-1 md:col-span-1">
                            <label class="block font-medium">Kota/Kabupaten</label>
                            <input type="text" name="kota_kabupaten" class="w-full px-3 py-2 border rounded" value="{{ old('kota_kabupaten', $siswa->kota_kabupaten ?? '') }}" required>
                        </div>
                        <!-- Kode Pos -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block font-medium">Kode Pos</label>
                            <input type="text" name="kode_pos" class="w-full px-3 py-2 border rounded" value="{{ old('kode_pos', $siswa->kode_pos ?? '') }}" required>
                        </div>
                    </div>

                    <!-- Kelas & Kejuruan -->
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block font-medium">Kelas</label>
                            <select name="kelas_id" class="w-full px-3 py-2 border rounded">
                                <option value="" required>-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ (old('kelas_id', $siswa->kelas_id ?? '') == $k->id) ? 'selected' : '' }}>
                                        {{ $k->kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium">Program Kejuruan</label>
                            <select name="kejuruan_id" class="w-full px-3 py-2 border rounded">
                                <option value="" required>-- Pilih Kejuruan --</option>
                                @foreach($kejuruan as $kj)
                                    <option value="{{ $kj->id }}" {{ (old('kejuruan_id', $siswa->kejuruan_id ?? '') == $kj->id) ? 'selected' : '' }}>
                                        {{ $kj->nama_kejuruan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                    <!-- Tombol Submit Data -->
                    <div class="flex justify-end md:justify-start">
                        <button id="submitButton" type="submit"
                            class="px-4 py-2 text-white bg-blue-600 rounded disabled:cursor-not-allowed"
                            {{ (isset($siswa) && isset($siswa->id) && $siswa->status === 'Valid') ? 'disabled' : '' }}>
                            <i class="bi bi-save"></i> Simpan Data
                        </button>
                    </div>

                    @if(isset($siswa) && $siswa->status === 'Valid' && isset($siswa->updated_at))
                        <span id="syncBadge" class="px-2 py-1 ml-2 text-white bg-green-600 rounded">
                            Akun anda telah tersinkronisasi pada {{ $siswa->updated_at->format('d M Y H:i') }}
                        </span>
                    @endif
                </form>
            </div>

            <!-- Footer -->
            <x-footer :profil="$profil" />
        </main>

        <!-- Bottom Navigation (Mobile Only - Icon Only) -->
    <div id="navhp" class="fixed bottom-0 left-0 right-0 z-50 flex justify-around py-2 bg-white border-t shadow-md md:hidden">

        <!-- Home/Dashboard -->
        <a href="{{ route('siswa.dashboard') }}" class="nav-icon {{ Route::currentRouteName() == 'siswa.dashboard' ? 'active' : '' }}">
            <i class="fas fa-home"></i>
        </a>

        <!-- Data Diri -->
        <a href="{{ route('siswa.data_diri') }}" class="nav-icon {{ request()->routeIs('siswa.data_diri') ? 'active' : '' }}">
            <i class="fas fa-id-card"></i>
        </a>

        <!-- Siswa -->
        <a href="{{ route('public.daftar_siswa.index') }}" class="nav-icon {{ request()->routeIs('public.daftar_siswa.*') ? 'active' : '' }}">
            <i class="fas fa-user-graduate"></i>
        </a>

        <!-- Akademik -->
        <a href="{{ route('siswa.materi.index') }}" class="nav-icon {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
            <i class="fas fa-book"></i>
        </a>

        <!-- Tugas Siswa -->
        <a href="{{ route('siswa.tugas.index') }}" class="nav-icon {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
            <i class="fas fa-tasks"></i>
        </a>
    </div>
    </div>

    <!-- Bottom Navigation (Mobile Only - Icon Only) -->
        <div id="navhp" class="fixed bottom-0 left-0 right-0 z-50 flex justify-around py-2 bg-white border-t shadow-md md:hidden">

            <!-- Home/Dashboard -->
            <a href="{{ route('siswa.dashboard') }}" class="nav-icon {{ Route::currentRouteName() == 'siswa.dashboard' ? 'active' : '' }}">
                <i class="fas fa-home"></i>
            </a>

            <!-- Akademik -->
            <a href="{{ route('siswa.materi.index') }}" class="nav-icon {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
            </a>

            <!-- Siswa -->
            <a href="{{ route('public.daftar_siswa.index') }}" class="nav-icon {{ request()->routeIs('public.daftar_siswa.*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i>
            </a>

            <!-- Tugas Siswa -->
            <a href="{{ route('siswa.tugas.index') }}" class="nav-icon {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
                <i class="fas fa-tasks"></i>
            </a>

            <!-- Informasi Sekolah -->
            <a href="{{ route('public.informasi_sekolah.index') }}" class="nav-icon {{ request()->routeIs('public.informasi_sekolah.index') ? 'active' : '' }}">
                <i class="fas fa-school"></i>
            </a>
        </div>

    <script>
        function syncData() {
            const button = document.getElementById('syncButton');
            const submitButton = document.getElementById('submitButton');
            button.disabled = true;
            submitButton.disabled = true; // setelah sinkron, tidak bisa edit lagi

            fetch("{{ route('siswa.data_diri.sync') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
            })
            .then(res => res.json())
            .then(data => {
                button.textContent = 'Tersinkronisasi';

                let badge = document.querySelector('#syncBadge');
                if (!badge) {
                    badge = document.createElement('span');
                    badge.id = 'syncBadge';
                    badge.className = 'px-2 py-1 text-white bg-green-600 rounded ml-2';
                    button.after(badge);
                }

                badge.textContent = 'Akun anda telah tersinkronisasi pada ' + data.updated_at;
            });
        }
    </script>
</x-app-layout>
