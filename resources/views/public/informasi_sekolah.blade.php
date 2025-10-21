<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? '') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <aside class="hidden mt-0 mb-4 md:block md:ml-6 md:mt-6 md:h-screen md:mb-0 md:w-auto">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Footer -->
            <x-footer :profil="$profil" />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-0 mb-16 space-y-6 overflow-x-auto md:p-6">
            <!-- Profil Sekolah Card -->
            <div>
                <x-public.profil-sekolah-card />
            </div>

            <!-- List/Tabel Data Struktural -->
            <div class="px-0 py-4 overflow-x-auto bg-transparent rounded shadow-none md:bg-white md:shadow md:px-8 md:overflow-x-visible">
                <x-public.list-struktural :struktural="$struktural" :gurus="$gurus" />
            </div>

            <!-- Tabel Data Guru -->
            {{-- <div class="p-4 overflow-x-auto bg-white rounded shadow">
                <x-public.list-guru :guru="$guru" :guruJam="$guruJam" />
            </div> --}}

            <!-- Tabel Data Kejuruan -->
            <div class="px-0 py-4 overflow-x-auto bg-transparent rounded shadow-none md:bg-white md:shadow md:px-8 md:overflow-x-visible">
                <x-public.list-kejuruan :profil="$profil" :kejuruan="$kejuruan" />
            </div>

            {{-- <div class="px-8 py-4 bg-white rounded shadow">
                <h2 class="mb-2 text-base font-bold md:text-lg md:mb-4">Daftar Program Kejuruan <span class="hidden capitalize text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span></h2>
                <hr class="mb-4">

                <div class="overflow-x-auto md:overflow-x-visible">
                    <table class="min-w-full text-center border border-collapse" id="kejuruanTable">
                        <thead class="text-sm bg-gray-100 md:text-base">
                            <tr>
                                <th class="px-4 py-2 border whitespace-nowrap">No</th>
                                <th class="px-4 py-2 text-left border md:text-center whitespace-nowrap">Program Kejuruan</th>
                                <th class="px-4 py-2 text-left border md:text-center whitespace-nowrap">Kepala Program</th>
                                <th class="px-4 py-2 text-center border whitespace-nowrap">Jumlah Siswa</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm md:text-base">
                            @forelse ($kejuruan ?? [] as $k)
                            <tr>
                                <td class="px-4 py-2 border whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 text-left border whitespace-nowrap">{{ $k->nama_kejuruan }}</td>
                                <td class="px-4 py-2 text-left border whitespace-nowrap">{{ $k->kepalaProgram->user->name ?? $k->kepalaProgram->nama ?? 'Tidak Ada' }}</td>
                                <td class="px-4 py-2 border whitespace-nowrap">{{ $k->siswa_count ?? 0 }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-2 border whitespace-nowrap">Belum ada data kejuruan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div> --}}

            <!-- Tabel Daftar Kelas -->
            <div class="px-0 py-4 overflow-x-auto bg-transparent rounded shadow-none md:bg-white md:shadow md:px-8 md:overflow-x-visible">
                @if(auth()->user()->role === 'admin')
                    <x-data-kelas :kelas="$kelas" :guru="$guru" />
                @else
                    <x-public.list-kelas :kelas="$kelas"/>
                @endif
            </div>

            <!-- Tabel Data Mapel -->
            <div class="px-0 py-4 overflow-x-auto bg-transparent rounded shadow-none md:bg-white md:shadow md:px-8 md:overflow-x-visible">
                <x-public.list-mapel :mapel="$mapel" :guru="$guru" />
            </div>

            <!-- Tabel Data Ekskul -->
            <div class="px-0 py-4 overflow-x-auto bg-transparent rounded shadow-none md:bg-white md:shadow md:px-8 md:overflow-x-visible">
                <x-public.list-ekskul :ekskul="$ekskul" />
            </div>
        </main>
    </div>

        <!-- Bottom Navigation (Mobile Only - Icon + Text) -->
        {{-- <div id="navhp" class="fixed bottom-0 left-0 right-0 z-50 flex justify-around py-2 text-xs bg-white border-t shadow-md md:hidden">

            <!-- Home/Dashboard -->
            <a href="{{ route('siswa.dashboard') }}" class="flex flex-col items-center nav-icon {{ Route::currentRouteName() == 'siswa.dashboard' ? 'active' : '' }}">
                <i class="text-lg fas fa-chart-line"></i>
                <small class="text-xs font-semibold">Beranda</small>
            </a>

            <!-- Siswa -->
            <a href="{{ route('public.daftar_siswa.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('public.daftar_siswa.*') ? 'active' : '' }}">
                <i class="text-lg fas fa-user-graduate"></i>
                <small class="text-xs font-semibold">Siswa</small>
            </a>

            <!-- Informasi Sekolah -->
            <a href="{{ route('public.informasi_sekolah.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('public.informasi_sekolah.index') ? 'active' : '' }}">
                <i class="text-lg fas fa-school"></i>
                <small class="text-xs font-semibold">Sekolah</small>
            </a>

            <!-- Akademik -->
            <a href="{{ route('siswa.materi.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
                <i class="text-lg fas fa-book"></i>
                <small class="text-xs font-semibold">Materi</small>
            </a>

            <!-- Tugas Siswa -->
            <a href="{{ route('siswa.tugas.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
                <i class="text-lg fas fa-tasks"></i>
                <small class="text-xs font-semibold">Tugas</small>
            </a>

        </div> --}}

</x-app-layout>
