<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? '') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="sticky z-10 w-full top-16 md:static md:w-auto md:ml-6 md:mt-6 md:h-screen md:top-0">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
            <!-- Profil Sekolah Card -->
            <div>
                <x-public.profil-sekolah-card />
            </div>

            <!-- List/Tabel Data Struktural -->
            <div class="px-8 py-4 overflow-x-auto bg-white rounded shadow">
                <x-public.list-struktural :struktural="$struktural" :gurus="$gurus" />
            </div>

            <!-- Tabel Data Guru -->
            {{-- <div class="p-4 overflow-x-auto bg-white rounded shadow">
                <x-public.list-guru :guru="$guru" :guruJam="$guruJam" />
            </div> --}}

            <!-- Tabel Data Kejuruan -->
            <div class="px-8 py-4 overflow-x-auto bg-white rounded shadow">
                <h2 class="mb-4 text-lg font-bold">Daftar Program Kejuruan |
                    <span class="capitalize text-sky-900">{{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }}</span> |
                </h2>
                <hr class="mb-4">

                <table class="min-w-full text-center border border-collapse" id="kejuruanTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">No</th>
                            <th class="px-4 py-2 border">Program Kejuruan</th>
                            <th class="px-4 py-2 border">Kepala Program</th>
                            <th class="px-4 py-2 border">Jumlah Siswa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kejuruan ?? [] as $k)
                        <tr>
                            <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-left border">{{ $k->nama_kejuruan }}</td>
                            <td class="px-4 py-2 text-left border">{{ $k->kepalaProgram->user->name ?? $k->kepalaProgram->nama ?? 'Tidak Ada' }}</td>
                            <td class="px-4 py-2 border">{{ $k->siswa_count ?? 0 }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-2 border">Belum ada data kejuruan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tabel Daftar Kelas -->
            <div class="px-8 py-4 overflow-x-auto bg-white rounded shadow">
                @if(auth()->user()->role === 'admin')
                    <x-data-kelas :kelas="$kelas" :guru="$guru" />
                @else
                    <x-public.list-kelas :kelas="$kelas"/>
                @endif
            </div>

            <!-- Tabel Data Mapel -->
            <div class="px-8 py-4 overflow-x-auto bg-white rounded shadow">
                <x-public.list-mapel :mapel="$mapel" :guru="$guru" />
            </div>

            <!-- Tabel Data Ekskul -->
            <div class="px-8 py-4 overflow-x-auto bg-white rounded shadow">
                <x-public.list-ekskul :ekskul="$ekskul" />
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-backtop-layout>
