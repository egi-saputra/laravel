<div class="hidden w-full p-0 m-0">
    <div class="p-6 mb-4 space-y-6 bg-white rounded shadow">
        @if($profil)
            <!-- Header: Logo + Nama Sekolah + Info Singkat -->
            {{-- <div class="flex items-center justify-center pt-4 overflow-hidden rounded">
                @if($profil && $profil->file_path)
                    <img src="{{ asset('storage/' . $profil->file_path) }}" class="object-contain w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 lg:w-32 lg:h-32" alt="Logo Sekolah">
                @else
                    <img src="https://via.placeholder.com/150" class="object-contain w-28 h-28" alt="Logo Sekolah">
                @endif
            </div> --}}
            @php
                $profil = \App\Models\ProfilSekolah::first();
                $logoPath = $profil && $profil->file_path
                    ? storage_path('app/public/logo_sekolah/' . ltrim($profil->file_path, '/'))
                    : public_path('images/default-logo.png'); // fallback jangan taruh di storage biar aman

                $lastModified = file_exists($logoPath) ? filemtime($logoPath) : time();
            @endphp

            <div class="flex items-center justify-center pt-4 overflow-hidden rounded">
                <img src="{{ url('/logo-sekolah') }}?v={{ $profil->updated_at->timestamp }}"
                    class="object-contain w-24 h-24 sm:w-24 sm:h-24 md:w-28 md:h-28 lg:w-32 lg:h-32"
                    alt="Logo Sekolah">
            </div>

            <div class="flex flex-wrap items-start pb-8 space-x-6">
                <!-- Nama Sekolah & Info Singkat -->
                <div class="flex-1">
                    <h2 class="mb-0 -mt-4 text-base font-bold text-center text-gray-800 md:mt-0 md:mb-2 md:text-2xl">
                        {{ $profil->nama_sekolah }}
                    </h2>
                    <h4 class="mb-2 text-sm font-bold text-center text-red-700 md:text-xl">
                        {{ $profil->akreditasi ?? '-' }}
                    </h4>
                    <p class="mb-4 text-xs text-center md:text-base">
                        {{ $profil->alamat }} RT {{ $profil->rt }} RW {{ $profil->rw }} Desa {{ $profil->kelurahan }},
                        Kec. {{ $profil->kecamatan }}, Kab. {{ $profil->kabupaten_kota }},
                        Provinsi {{ $profil->provinsi }}, Kode Pos: {{ $profil->kode_pos }}
                    </p>
                    <hr class="mb-8">

                    <h2 class="inline-block pb-1 mb-4 font-semibold text-left border-b border-slate-300">
                        Informasi Administratif Sekolah
                    </h2>

                    <!-- Grid tanpa overflow-x -->
                    <div class="grid grid-cols-1 gap-6 p-0 text-sm border-none rounded-md shadow-none md:gap-4 md:text-base md:p-4 md:border md:shadow md:grid-cols-3">
                        <!-- Kolom 1 -->
                        <div class="hidden space-y-2 md:block">
                            <div class="grid grid-cols-[120px_10px_1fr] items-center">
                                <span class="font-semibold">Nama Sekolah</span>
                                <span class="text-right">:</span>
                                <span class="pl-2">{{ $profil->nama_sekolah }}</span>
                            </div>
                            <div class="grid grid-cols-[120px_10px_1fr] items-center">
                                <span class="font-semibold">Kepala Yayasan</span>
                                <span class="text-right">:</span>
                                <span class="pl-2">{{ $profil->kepala_yayasan ?? '-' }}</span>
                            </div>
                            <div class="grid grid-cols-[120px_10px_1fr] items-center">
                                <span class="font-semibold">Kepala Sekolah</span>
                                <span class="text-right">:</span>
                                <span class="pl-2">{{ $profil->kepala_sekolah ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Kolom 2 -->
                        <div class="ml-0 space-y-2 md:ml-4">
                            <div class="grid grid-cols-[90px_10px_1fr] items-center">
                                <span class="font-semibold">No. Izin</span>
                                <span class="text-left">:</span>
                                <span>{{ $profil->no_izin }}</span>
                            </div>
                            <div class="grid grid-cols-[90px_10px_1fr] items-center">
                                <span class="font-semibold">NPSN</span>
                                <span class="text-left">:</span>
                                <span>{{ $profil->npsn }}</span>
                            </div>
                            <div class="grid grid-cols-[90px_10px_1fr] items-center">
                                <span class="font-semibold">NSS</span>
                                <span class="text-left">:</span>
                                <span>{{ $profil->nss ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Kolom 3 -->
                        <div class="space-y-2">
                            <div class="grid grid-cols-[90px_10px_1fr] items-center">
                                <span class="font-semibold">Email</span>
                                <span class="text-left">:</span>
                                <a href="#" class="text-sm underline break-all md:text-base text-sky-600 hover:text-sky-800">
                                    <span class="break-all">{{ $profil->website }}</span>
                                </a>
                            </div>
                            <div class="grid grid-cols-[90px_10px_1fr] items-center">
                                <span class="font-semibold">No. Hp</span>
                                <span class="text-left">:</span>
                                <a href="#" class="text-sky-600 hover:text-sky-800"><span>{{ $profil->telepon }}</span></a>
                            </div>
                            <div class="grid grid-cols-[90px_10px_1fr] items-center">
                                <span class="font-semibold">Website</span>
                                <span class="text-left">:</span>
                                <a href="#" class="underline break-all text-sky-600 hover:text-sky-800">
                                    <span class="break-all">{{ $profil->website }}</span>
                                </a>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visi & Misi -->
            <div class="grid grid-cols-1 gap-6 mt-4 md:grid-cols-2">
                <!-- Visi -->
                <div class="p-4 border rounded shadow-md bg-gray-50">
                    <h3 class="mb-2 text-2xl font-bold text-gray-800">Visi :</h3>
                    @if($profil->visi)
                        {{-- <ul class="pl-6 space-y-2 list-disc marker:text-black marker:text-xl"> --}}
                        <ul class="pl-2 space-y-2 text-justify">
                            @foreach(explode("\n", $profil->visi) as $line)
                                @if(trim($line) !== '')
                                    <li class="text-gray-600">{{ $line }}</li>
                                    {{-- <li style="text-indent: 2em;">{{ $line }}</li> --}}
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-700">-</p>
                    @endif
                </div>

                <!-- Misi -->
                <div class="p-4 border rounded shadow-md bg-gray-50">
                    <h3 class="mb-2 text-2xl font-bold text-gray-800">Misi :</h3>
                    @if($profil->misi)
                        <ul class="pl-6 space-y-2 list-disc marker:text-black marker:text-xl">
                            @foreach(explode("\n", $profil->misi) as $line)
                                @if(trim($line) !== '')
                                    <li class="text-gray-600">{{ $line }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-700">-</p>
                    @endif
                </div>
            </div>

        @else
            <p class="text-gray-500">Data profil sekolah belum tersedia.</p>
        @endif
    </div>
</div>
