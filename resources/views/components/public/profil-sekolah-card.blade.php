<!-- CARD INFORMASI SEKOLAH -->
<div class="block ">
    <!-- Informasi Profil Sekolah -->
    <div class="p-6 mb-6 bg-white rounded-md shadow-md md:rounded-2xl">
        @if($profil)
            @php
                $profil = \App\Models\ProfilSekolah::first();
                $logoPath = $profil && $profil->file_path
                    ? storage_path('app/public/logo_sekolah/' . ltrim($profil->file_path, '/'))
                    : public_path('images/default-logo.png');

                $lastModified = file_exists($logoPath) ? filemtime($logoPath) : time();
            @endphp

            <!-- Container Logo dan Info Sekolah -->
            <div class="flex flex-col items-center justify-center text-center md:justify-start md:flex-row md:text-left md:space-x-8">
                <!-- Logo -->
                <div class="flex items-center justify-center mb-4 overflow-hidden bg-white border rounded-full shadow-sm w-28 h-28 md:w-32 md:h-32 md:mb-0">
                    <img src="{{ url('/logo-sekolah') }}?v={{ $profil->updated_at->timestamp }}"
                        class="object-contain w-24 h-24 transition-transform duration-300 hover:scale-105"
                        alt="Logo Sekolah">
                </div>

                <!-- Info Sekolah -->
                <div class="p-0 space-y-2 md:pl-4 boder-none md:border-l-2">
                    <h2 class="text-xl font-extrabold text-gray-800 md:text-2xl">
                        {{ $profil->nama_sekolah }}
                    </h2>
                    <p class="text-sm font-semibold tracking-wide text-red-600 uppercase md:text-base">
                        <span class="hidden md:block">Akreditasi: </span>{{ $profil->akreditasi ?? '-' }}
                    </p>
                    <div class="text-xs leading-relaxed text-gray-600 md:text-base">
                        <p>
                            {{ $profil->alamat }},
                            RT {{ $profil->rt }} / RW {{ $profil->rw }},
                            Desa {{ $profil->kelurahan }}, Kec. {{ $profil->kecamatan }},
                            Kab. {{ $profil->kabupaten_kota }}, <br>
                            Provinsi {{ $profil->provinsi }},
                            <span class="font-semibold text-gray-800">Kode Pos: {{ $profil->kode_pos }}.</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Garis Pembatas Halus -->
            <div class="block mt-6 border-t border-gray-200 md:hidden"></div>

        @else
            <div class="p-4 text-center text-gray-500 border rounded-lg bg-gray-50 col-span-full">
                Belum ada data informasi Sekolah.
            </div>
        @endif
    </div>

    <!-- Informasi Administratif Sekolah -->
    <div class="pb-2 mb-6 bg-white rounded-md shadow-md md:rounded-2xl">
        @if($profil)
            <div class="flex flex-col">
                <!-- Header -->
                <div class="flex items-center justify-center p-6 mb-0 md:mb-4 md:justify-between">
                    <h2 class="text-lg font-bold text-gray-800 md:text-2xl">
                      <span class="hidden md:inline-block">🏫</span> Informasi Administratif Sekolah
                    </h2>
                    <span class="hidden px-3 py-1 text-sm font-medium rounded-full md:inline-block text-sky-700 bg-sky-100">
                        {{ $profil->nama_sekolah }}
                    </span>
                </div>

                {{-- <hr class="mx-4"> --}}

                <!-- Cards Grid -->
                <div class="grid gap-6 p-4 md:gap-3 md:grid-cols-3">
                    <!-- Card 1 -->
                    <div class="p-4 transition-all border rounded-lg shadow-sm hover:shadow-md">
                        <div class="flex items-center mb-3 space-x-2">
                            <div class="p-2 rounded-lg bg-sky-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20l9-5-9-5-9 5 9 5zm0-10V4l9 5-9 5z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-800">Identitas Sekolah</h3>
                        </div>
                        <div class="space-y-2 text-sm text-gray-700">
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
                    </div>

                    <!-- Card 2 -->
                    <div class="p-4 transition-all border rounded-lg shadow-sm hover:shadow-md">
                        <div class="flex items-center mb-3 space-x-2">
                            <div class="p-2 rounded-lg bg-emerald-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v4a2 2 0 002 2zm2 0v4m-2 4h6" />
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-800">Data Legalitas</h3>
                        </div>
                        <div class="space-y-2 text-sm text-gray-700">
                            <div class="grid md:grid-cols-[90px_10px_1fr] grid-cols-[80px_10px_1fr] items-center">
                                <span class="font-semibold">No. Izin</span>
                                <span class="text-left">:</span>
                                <span>{{ $profil->no_izin }}</span>
                            </div>
                            <div class="grid md:grid-cols-[90px_10px_1fr] grid-cols-[80px_10px_1fr] items-center">
                                <span class="font-semibold">NPSN</span>
                                <span class="text-left">:</span>
                                <span>{{ $profil->npsn }}</span>
                            </div>
                            <div class="grid md:grid-cols-[90px_10px_1fr] grid-cols-[80px_10px_1fr] items-center">
                                <span class="font-semibold">NSS</span>
                                <span class="text-left">:</span>
                                <span>{{ $profil->nss ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="p-4 transition-all border rounded-lg shadow-sm hover:shadow-md">
                        <div class="flex items-center mb-3 space-x-2">
                            <div class="p-2 rounded-lg bg-amber-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-9 13V10" />
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-800">Kontak & Website</h3>
                        </div>
                        <div class="space-y-2 text-sm text-gray-700">
                            <div class="grid grid-cols-[80px_10px_1fr] md:grid-cols-[70px_10px_1fr] items-center">
                                <span class="font-semibold">Email</span>
                                <span class="text-left">:</span>
                                <a href="#" class="text-sm underline break-all md:text-base text-sky-600 hover:text-sky-800">
                                    <span class="break-all">{{ $profil->email }}</span>
                                </a>
                            </div>
                            <div class="grid grid-cols-[80px_10px_1fr] md:grid-cols-[70px_10px_1fr] items-center">
                                <span class="font-semibold">No. Hp</span>
                                <span class="text-left">:</span>
                                <a href="#" class="text-sky-600 hover:text-sky-800"><span>{{ $profil->telepon }}</span></a>
                            </div>
                            <div class="grid grid-cols-[80px_10px_1fr] md:grid-cols-[70px_10px_1fr] items-center">
                                <span class="font-semibold">Email</span>
                                <span class="text-left">:</span>
                                <a href="#" class="text-sm break-all md:text-base text-sky-600 hover:text-sky-800">
                                    <span class="break-all">{{ $profil->website }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="p-4 text-center text-gray-500 border rounded-lg bg-gray-50 col-span-full">
                    Data administrasi sekolah belum tersedia.
            </div>
        @endif
    </div>

    <!-- Visi Misi Sekolah -->
    <div class="pb-2 mb-6 bg-white rounded-md shadow-md md:rounded-2xl">
        @if($profil)
            <!-- Header -->
            <div class="flex items-center justify-center p-6 mb-0 md:mb-4 md:justify-between">
                <h2 class="text-xl font-bold text-gray-800 md:text-2xl">
                    🎯 Visi & Misi Sekolah
                </h2>
                <span class="hidden px-3 py-1 text-sm font-medium rounded-full md:inline-block text-sky-700 bg-sky-100">
                    {{ $profil->nama_sekolah }}
                </span>
            </div>

            <!-- Grid Visi & Misi -->
            <div class="grid grid-cols-1 gap-6 p-4 md:grid-cols-2">
                <!-- Card Visi -->
                <div class="p-4 transition-all border rounded-md shadow-sm md:p-6 md:rounded-xl bg-gradient-to-br from-blue-50 to-white hover:shadow-lg">
                    <div class="flex items-center mb-4 space-x-2">
                        <div class="p-2 rounded-lg bg-sky-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3 0 1.094.588 2.047 1.5 2.598V17h3v-3.402A2.996 2.996 0 0015 11c0-1.657-1.343-3-3-3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1v2m8.485 2.515l-1.415 1.415M21 12h2m-2.515 8.485l-1.415-1.415M12 21v2m-8.485-2.515l1.415-1.415M3 12H1m2.515-8.485l1.415 1.415" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Visi Sekolah</h3>
                    </div>

                    @if($profil->visi)
                        <ul class="pl-4 space-y-2 text-sm leading-relaxed text-gray-700 list-disc marker:text-sky-600">
                            @foreach(explode("\n", $profil->visi) as $line)
                                @if(trim($line) !== '')
                                    <li>{{ $line }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-600">Belum ada data visi.</p>
                    @endif
                </div>

                <!-- Card Misi -->
                <div class="p-4 transition-all border rounded-md shadow-sm md:p-6 md:rounded-xl bg-gradient-to-br from-emerald-50 to-white hover:shadow-lg">
                    <div class="flex items-center mb-4 space-x-2">
                        <div class="p-2 rounded-lg bg-emerald-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Misi Sekolah</h3>
                    </div>

                    @if($profil->misi)
                        <ul class="pl-4 space-y-2 text-sm leading-relaxed text-gray-700 list-decimal marker:text-emerald-600">
                            @foreach(explode("\n", $profil->misi) as $line)
                                @if(trim($line) !== '')
                                    <li>{{ $line }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-600">Belum ada data misi.</p>
                    @endif
                </div>
            </div>
        @else
            <div class="p-4 text-center text-gray-500 border rounded-lg bg-gray-50 col-span-full">
                    Data profil sekolah belum tersedia.
            </div>
        @endif
    </div>

    <!-- Menu Aplikasi -->
    {{-- <div class="pb-6 mb-6 bg-white rounded-md shadow-md md:rounded-2xl">
        <div class="flex items-center justify-between p-6">
            <h2 class="text-xl font-bold text-gray-800 md:text-2xl">📱 Menu Aplikasi</h2>
        </div>

        <!-- Grid Menu -->
        <div class="grid grid-cols-3 gap-4 p-4 text-center md:grid-cols-6">
            <!-- Menu 1 -->
            <a href="{{ route('public.daftar_siswa.index') }}" class="flex flex-col items-center justify-center p-4 transition-all bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                <i class="mb-2 text-xl md:text-3xl bi bi-people-fill text-sky-600"></i>
                <span class="text-xs font-semibold text-gray-700 md:text-sm">Data Siswa</span>
            </a>

            <!-- Menu 2 -->
            <a href="#" class="flex flex-col items-center justify-center p-4 transition-all bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                <i class="mb-2 text-3xl bi bi-journal-text text-emerald-600"></i>
                <span class="text-sm font-semibold text-gray-700">Jurnal</span>
            </a>

            <!-- Menu 3 -->
            <a href="#" class="flex flex-col items-center justify-center p-4 transition-all bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                <i class="mb-2 text-3xl bi bi-calendar2-week text-amber-500"></i>
                <span class="text-sm font-semibold text-gray-700">Kegiatan</span>
            </a>

            <!-- Menu 4 -->
            <a href="#" class="flex flex-col items-center justify-center p-4 transition-all bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                <i class="mb-2 text-3xl text-gray-700 bi bi-gear-fill"></i>
                <span class="text-sm font-semibold text-gray-700">Pengaturan</span>
            </a>

            <!-- Menu 5 -->
            <a href="#" class="flex flex-col items-center justify-center p-4 transition-all bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                <i class="mb-2 text-3xl text-indigo-600 bi bi-globe2"></i>
                <span class="text-sm font-semibold text-gray-700">Website</span>
            </a>

            <!-- Menu 6 -->
            <a href="#" class="flex flex-col items-center justify-center p-4 transition-all bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                <i class="mb-2 text-3xl text-red-600 bi bi-box-arrow-right"></i>
                <span class="text-sm font-semibold text-gray-700">Logout</span>
            </a>
        </div>
    </div> --}}

</div>
