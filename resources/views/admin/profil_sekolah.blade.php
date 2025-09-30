<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? '') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <aside class="sticky z-10 w-full top-16 md:static md:w-auto md:ml-6 md:mt-6 md:h-screen md:top-0">
            <!-- Sidebar -->
            <x-sidebar />
        </aside>

            <!-- Main Content -->
            <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
                <main class="p-4 bg-white rounded shadow-md">

                @if(session('success'))
                    <div class="p-4 mb-4 text-green-800 bg-green-100 border border-green-200 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.profil_sekolah.storeOrUpdate') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    @php
                        $profil = \App\Models\ProfilSekolah::first();
                        $logoPath = $profil && $profil->file_path
                            ? storage_path('app/public/logo_sekolah/' . ltrim($profil->file_path, '/'))
                            : public_path('images/default-logo.png'); // fallback jangan taruh di storage biar aman

                        $lastModified = file_exists($logoPath) ? filemtime($logoPath) : time();
                    @endphp

                    <!-- Logo Preview -->
                    <div class="flex flex-col items-center mb-4">
                        <div class="w-32 h-32 mb-2 overflow-hidden rounded">
                            @if($profil && $profil->file_path)
                                <img src="{{ url('/logo-sekolah') }}?v={{ $profil->updated_at->timestamp }}" class="object-contain w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 lg:w-32 lg:h-32"
                            alt="Logo Sekolah">
                            @else
                                <img src="https://via.placeholder.com/150" class="object-contain w-full h-full">
                            @endif
                        </div>
                        <input type="file" name="logo" class="p-2 mt-4 mb-4 border rounded w-50 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('logo') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 mt-4 md:grid-cols-2">
                        <!-- Nama Sekolah -->
                        <div>
                            <label class="block font-medium text-gray-700">Nama Sekolah <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $profil->nama_sekolah ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                            @error('nama_sekolah') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kepala Yayasan -->
                        <div>
                            <label class="block font-medium text-gray-700">Nama Kepala Yayasan</label>
                            <input type="text" name="kepala_yayasan" value="{{ old('kepala_yayasan', $profil->kepala_yayasan ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <!-- Kepala Sekolah -->
                        <div>
                            <label class="block font-medium text-gray-700">Nama Kepala Sekolah</label>
                            <input type="text" name="kepala_sekolah" value="{{ old('kepala_sekolah', $profil->kepala_sekolah ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <!-- Akreditasi -->
                        <div>
                            <label class="block font-medium text-gray-700">Akreditasi</label>
                            <input type="text" name="akreditasi" value="{{ old('akreditasi', $profil->akreditasi ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Contoh: A / B / C">
                        </div>

                        <!-- NPSN -->
                        <div>
                            <label class="block font-medium text-gray-700">NPSN</label>
                            <input type="text" name="npsn" value="{{ old('npsn', $profil->npsn ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <!-- No. Izin -->
                        <div>
                            <label class="block font-medium text-gray-700">No. Izin</label>
                            <input type="text" name="no_izin" value="{{ old('no_izin', $profil->no_izin ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <!-- NSS -->
                        <div>
                            <label class="block font-medium text-gray-700">NSS</label>
                            <input type="text" name="nss" value="{{ old('nss', $profil->nss ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label class="block font-medium text-gray-700">Telepon</label>
                            <input type="text" name="telepon" value="{{ old('telepon', $profil->telepon ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $profil->email ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <!-- Website -->
                        <div>
                            <label class="block font-medium text-gray-700">Website</label>
                            <input type="text" name="website" value="{{ old('website', $profil->website ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                    </div>


                    <!-- Alamat -->
                    <div>
                        <label class="block font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" rows="3" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('alamat', $profil->alamat ?? '') }}</textarea>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- RT/RW -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium text-gray-700">RT</label>
                                <input type="text" name="rt" value="{{ old('rt', $profil->rt ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700">RW</label>
                                <input type="text" name="rw" value="{{ old('rw', $profil->rw ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>
                        </div>

                        <!-- Kelurahan -->
                        <div>
                            <label class="block font-medium text-gray-700">Kelurahan/Desa</label>
                            <input type="text" name="kelurahan" value="{{ old('kelurahan', $profil->kelurahan ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label class="block font-medium text-gray-700">Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan', $profil->kecamatan ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <!-- Kabupaten/Kota -->
                        <div>
                            <label class="block font-medium text-gray-700">Kabupaten/Kota</label>
                            <input type="text" name="kabupaten_kota" value="{{ old('kabupaten_kota', $profil->kabupaten_kota ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <!-- Provinsi -->
                        <div>
                            <label class="block font-medium text-gray-700">Provinsi</label>
                            <input type="text" name="provinsi" value="{{ old('provinsi', $profil->provinsi ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <!-- Kode Pos -->
                        <div>
                            <label class="block font-medium text-gray-700">Kode Pos</label>
                            <input type="text" name="kode_pos" value="{{ old('kode_pos', $profil->kode_pos ?? '') }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                    </div>

                    <!-- Visi & Misi -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="block font-medium text-gray-700">Visi</label>
                            <textarea name="visi" rows="3" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('visi', $profil->visi ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="block font-medium text-gray-700">Misi</label>
                            <textarea name="misi" rows="3" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('misi', $profil->misi ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700">
                            {{ $profil ? 'Update Profil' : 'Simpan Profil' }}
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-layout>
