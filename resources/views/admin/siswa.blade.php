<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 flex items-center gap-2">
            <i class="bi bi-people-fill text-blue-600"></i>
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
        <main class="flex-1 p-0 mb-16 overflow-x-auto md:mb-0 md:p-6">

            {{-- Form Tambah Siswa --}}
            <div class="p-6 mb-8 bg-white/80 backdrop-blur-md border border-gray-100 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300">

                <h1 class="text-lg font-bold mb-4 border-b pb-2 flex items-center gap-2">
                    <i class="bi bi-person-plus text-blue-600"></i> Tambah Data Peserta Didik
                </h1>

                <form action="{{ route('admin.siswa.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf

                    {{-- Ganti <x-input> dengan input biasa agar tidak error --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                        <input type="text" name="nis" maxlength="20" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NISN</label>
                        <input type="text" name="nisn" maxlength="10" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select name="kelas_id" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach(\App\Models\DataKelas::all() as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Program Kejuruan</label>
                        <select name="kejuruan_id" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                            <option value="">-- Pilih Kejuruan --</option>
                            @foreach(\App\Models\DataKejuruan::all() as $kj)
                                <option value="{{ $kj->id }}">{{ $kj->nama_kejuruan }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Hidden defaults --}}
                    <input type="hidden" name="jenis_kelamin" value="Laki-laki">
                    <input type="hidden" name="agama" value="Islam">
                    <input type="hidden" name="password" value="{{ env('DEFAULT_SISWA_PASSWORD', 'password') }}">
                    <input type="hidden" name="role" value="siswa">

                    <div class="md:col-span-2 mt-2">
                        <p class="text-sm text-gray-500 italic">
                            Password default akan terisi otomatis:
                            <span class="font-semibold text-blue-600">"password"</span>
                        </p>
                    </div>

                    <div class="md:col-span-2 flex justify-end mt-4">
                        <button type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </div>
                </form>

                <hr class="my-6 border-gray-200">

                {{-- Import & Template --}}
                <div class="flex flex-col items-end gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                    <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data"
                        class="flex flex-col w-full gap-2 sm:flex-row sm:w-auto sm:items-center">
                        @csrf
                        <input type="file" name="file" required accept=".xls,.xlsx,.csv"
                            class="w-full p-2 text-sm border rounded-lg focus:ring focus:ring-green-200 sm:w-auto">
                        <button type="submit"
                            class="w-full sm:w-auto px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow flex items-center gap-2 justify-center">
                            <i class="bi bi-file-earmark-excel"></i> Import Excel
                        </button>
                    </form>

                    <a href="{{ route('admin.siswa.template') }}"
                        class="w-full sm:w-auto px-4 py-2 bg-slate-700 hover:bg-slate-800 text-white rounded-lg shadow flex items-center gap-2 justify-center">
                        <i class="bi bi-download"></i> Download Template
                    </a>
                </div>
            </div>

            {{-- Filter & Table --}}
            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-4">
                    <h2 class="text-lg font-bold flex items-center gap-2">
                        <i class="bi bi-list-ul text-blue-600"></i> Daftar Peserta Didik
                    </h2>

                    {{-- Filter kelas --}}
                    <form method="GET" action="{{ route('admin.siswa.index') }}" class="flex items-center gap-2">
                        <select name="kelas_id" onchange="this.form.submit()"
                            class="border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring focus:ring-blue-200">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->kelas }}
                                </option>
                            @endforeach
                        </select>

                        @if(request('kelas_id'))
                            <a href="{{ route('admin.daftar_siswa.export', ['kelas_id' => request('kelas_id')]) }}"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow flex items-center gap-2">
                                <i class="bi bi-file-earmark-excel"></i> Export Excel
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Jika belum pilih kelas --}}
                @if(!request('kelas_id'))
                    <div class="text-center py-12 text-gray-500">
                        <i class="bi bi-funnel text-3xl mb-2 text-gray-400"></i><br>
                        Silakan pilih kelas terlebih dahulu untuk menampilkan data siswa.
                    </div>
                @else
                    {{-- Tabel siswa --}}
                    <x-admin.tabel-siswa :siswa="$siswa" :kelasList="$kelasList" :kejuruanList="$kejuruanList" :sekolah="$sekolah" />
                @endif
            </div>

        </main>
    </div>
</x-app-layout>
