<x-app-admin-layout>
    <x-slot name="header">
        <h2 class="flex items-center gap-2 text-xl font-semibold leading-tight text-gray-800">
            <i class="text-blue-600 bi bi-people-fill"></i>
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
        <main class="flex-1 p-0 !mb-16 overflow-x-auto md:mb-0 md:p-6">

            {{-- Form Tambah Siswa --}}
            <div class="p-6 mb-8 transition-all duration-300 border border-gray-100 shadow-sm bg-white/80 backdrop-blur-md rounded-2xl hover:shadow-lg">

                <h1 class="flex items-center gap-2 pb-2 mb-4 text-lg font-bold border-b">
                    <i class="text-blue-600 bi bi-person-plus"></i> Tambah Data Peserta Didik
                </h1>

                <form action="{{ route('admin.siswa.store') }}" method="POST" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @csrf

                    {{-- Ganti <x-input> dengan input biasa agar tidak error --}}
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">NIS</label>
                        <input type="text" name="nis" maxlength="20" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">NISN</label>
                        <input type="text" name="nisn" maxlength="10" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Kelas</label>
                        <select name="kelas_id" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach(\App\Models\DataKelas::all() as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Program Kejuruan</label>
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

                    <div class="mt-2 md:col-span-2">
                        <p class="text-sm italic text-gray-500">
                            Password default akan terisi otomatis:
                            <span class="font-semibold text-blue-600">"password"</span>
                        </p>
                    </div>

                    <div class="flex justify-end mt-4 md:col-span-2">
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
                            class="flex items-center justify-center w-full gap-2 px-4 py-2 text-white bg-green-600 rounded-lg shadow sm:w-auto hover:bg-green-700">
                            <i class="bi bi-file-earmark-excel"></i> Import Excel
                        </button>
                    </form>

                    <a href="{{ route('admin.siswa.template') }}"
                        class="flex items-center justify-center w-full gap-2 px-4 py-2 text-white rounded-lg shadow sm:w-auto bg-slate-700 hover:bg-slate-800">
                        <i class="bi bi-download"></i> Download Template
                    </a>
                </div>
            </div>

            {{-- Filter & Table --}}
            <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="flex flex-col justify-between gap-4 mb-4 md:flex-row md:items-center">
                    <h2 class="flex items-center gap-2 text-lg font-bold">
                        <i class="text-blue-600 bi bi-list-ul"></i> Daftar Peserta Didik
                    </h2>

                    {{-- Filter kelas --}}
                    <form method="GET" action="{{ route('admin.siswa.index') }}" class="flex items-center gap-2">
                        <select name="kelas_id" onchange="this.form.submit()"
                            class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring focus:ring-blue-200">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->kelas }}
                                </option>
                            @endforeach
                        </select>

                        @if(request('kelas_id'))
                            <a href="{{ route('admin.daftar_siswa.export', ['kelas_id' => request('kelas_id')]) }}"
                                class="flex items-center gap-2 px-4 py-2 text-white bg-green-600 rounded-lg shadow hover:bg-green-700">
                                <i class="bi bi-file-earmark-excel"></i> Export Excel
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Jika belum pilih kelas --}}
                @if(!request('kelas_id'))
                    <div class="py-12 text-center text-gray-500">
                        <i class="mb-2 text-3xl text-gray-400 bi bi-funnel"></i><br>
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
