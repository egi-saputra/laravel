{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 flex items-center gap-2">
            <i class="bi bi-calendar-check text-blue-600"></i>
            {{ __('Absensi Siswa Hari Ini') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-xl p-6 border border-gray-100">

                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-700">
                        Tanggal: <span class="text-blue-600">{{ $today->translatedFormat('l, d F Y') }}</span>
                    </h3>
                    <a href="{{ route('dashboard') }}"
                       class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
                        ← Kembali ke Dashboard
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-gray-700 border border-gray-200 rounded-lg">
                        <thead class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Nama Siswa</th>
                                <th class="px-4 py-3 text-left">Kelas</th>
                                <th class="px-4 py-3 text-left">Keterangan</th>
                                <th class="px-4 py-3 text-left">Jam Presensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presensi as $index => $p)
                                <tr class="border-b hover:bg-blue-50 transition">
                                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-semibold">{{ $p->user->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $p->dataSiswa->kelas->kelas ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $color = match($p->keterangan) {
                                                'Hadir' => 'bg-green-100 text-green-700',
                                                'Izin' => 'bg-yellow-100 text-yellow-700',
                                                'Sakit' => 'bg-orange-100 text-orange-700',
                                                'Alpa', 'Alpha' => 'bg-red-100 text-red-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                            {{ $p->keterangan }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">
                                        {{ $p->created_at->format('H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-6 text-gray-500">
                                        Tidak ada data presensi untuk hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout> --}}

<x-app-layout>

    {{-- Mobile Version --}}
    <div class="md:hidden block">
        <x-slot name="header">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 flex items-center gap-2">
                <i class="bi bi-calendar-check text-blue-600"></i>
                {{ __('Absensi Siswa Hari Ini') }}
            </h2>
        </x-slot>

        <div class="flex flex-col min-h-screen md:flex-row">

        <!-- Main Content -->
        <main class="flex-1 p-0 mb-16 overflow-x-auto md:mb-0 md:p-6">

                    {{-- Header --}}
                    <div class="mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">
                                Tanggal: <span class="text-blue-600">{{ $today->translatedFormat('l, d F Y') }}</span>
                            </h3>
                        </div>

                        <div class="flex flex-col p-2 w-full gap-3">
                            {{-- Filter Kelas --}}
                            <form method="GET" action="{{ route('guru.absensi_hari_ini') }}" class="flex items-center">
                                <select name="kelas" onchange="this.form.submit()"
                                    class="border text-center w-full border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring focus:ring-blue-200">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelasList as $k)
                                        <option value="{{ $k->id }}" {{ request('kelas') == $k->id ? 'selected' : '' }}>
                                            {{ $k->kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            <div class="flex w-full gap-4 justify-center items-center">
                                {{-- Back --}}
                                <a href="{{ route('dashboard') }}"
                                class="px-4 text-center py-2 text-sm font-semibold text-blue-600 border border-blue-500 rounded-lg hover:bg-blue-50 w-full transition">
                                    ← Kembali
                                </a>

                                 {{-- Export Excel --}}
                                <a href="{{ $kelasId ? route('guru.absensi_hari_ini.export', ['kelas' => $kelasId]) : '#' }}"
                                    class="px-4 py-2 w-full text-sm font-semibold text-white rounded-lg inline-flex justify-center gap-2
                                            {{ $kelasId ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 cursor-not-allowed' }}"
                                    {{ !$kelasId ? 'onclick=alert("Pilih kelas terlebih dahulu!")' : '' }}>
                                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Cards --}}
                    @if(!$kelasId)
                        <div class="text-center text-gray-500 py-10">
                            Silakan pilih kelas terlebih dahulu untuk melihat data absensi hari ini.
                        </div>
                    @elseif($presensi->isEmpty())
                        <div class="text-center text-gray-500 py-10">
                            Tidak ada data presensi untuk hari ini di kelas yang dipilih.
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($presensi as $p)
                                @php
                                    $color = match($p->keterangan) {
                                        'Hadir' => 'bg-green-100 text-green-800 border-green-300',
                                        'Izin' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                        'Sakit' => 'bg-orange-100 text-orange-800 border-orange-300',
                                        'Alpa', 'Alpha' => 'bg-red-100 text-red-800 border-red-300',
                                        default => 'bg-gray-100 text-gray-800 border-gray-300',
                                    };
                                @endphp
                                <div class="p-5 bg-white border rounded-xl shadow-sm hover:shadow-md transition duration-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-semibold text-gray-800 text-lg line-clamp-1">
                                            {{ $p->user->name ?? '-' }}
                                        </h4>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full border {{ $color }}">
                                            {{ $p->keterangan }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 text-sm">
                                        <i class="bi bi-building text-blue-600"></i>
                                        Kelas: <strong>{{ $p->dataSiswa->kelas->kelas ?? '-' }}</strong>
                                    </p>
                                    <p class="text-gray-500 text-sm mt-1">
                                        <i class="bi bi-clock"></i>
                                        Jam Presensi: <strong>{{ $p->created_at->format('H:i') }}</strong>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
        </main>
    </div>

    {{-- Desktop Version --}}
    <div class="md:block hidden">
        <x-slot name="header">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 flex items-center gap-2">
                <i class="bi bi-table text-blue-600"></i>
                {{ __('Absensi Siswa Hari Ini') }}
            </h2>
        </x-slot>

        <div class="py-10 md:mb-16">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-xl p-6 border border-gray-100">

                    {{-- Header --}}
                    <div class="mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">
                                Tanggal: <span class="text-blue-600">{{ $today->translatedFormat('l, d F Y') }}</span>
                            </h3>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            {{-- Filter Kelas --}}
                            <form method="GET" action="{{ route('guru.absensi_hari_ini') }}" class="flex items-center gap-2">
                                <select name="kelas" onchange="this.form.submit()"
                                    class="border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring focus:ring-blue-200">
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelasList as $k)
                                        <option value="{{ $k->id }}" {{ request('kelas') == $k->id ? 'selected' : '' }}>
                                            {{ $k->kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            {{-- Export Excel --}}
                            <a href="{{ $kelasId ? route('guru.absensi_hari_ini.export', ['kelas' => $kelasId]) : '#' }}"
                            class="px-4 py-2 text-sm font-semibold text-white rounded-lg flex items-center gap-2
                                    {{ $kelasId ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 cursor-not-allowed' }}"
                            {{ !$kelasId ? 'onclick=alert("Pilih kelas terlebih dahulu!")' : '' }}>
                                <i class="bi bi-file-earmark-excel"></i> Export Excel
                            </a>

                            {{-- Back --}}
                            <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 text-sm font-semibold text-blue-600 border border-blue-500 rounded-lg hover:bg-blue-50 transition">
                                ← Kembali
                            </a>
                        </div>
                    </div>

                    {{-- Tabel --}}
                    @if(!$kelasId)
                        <div class="text-center text-gray-500 py-10">
                            Silakan pilih kelas terlebih dahulu untuk melihat data absensi hari ini.
                        </div>
                    @elseif($presensi->isEmpty())
                        <div class="text-center text-gray-500 py-10">
                            Tidak ada data presensi untuk hari ini di kelas yang dipilih.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-gray-700 border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                <thead class="bg-gradient-to-r from-blue-600 to-blue-800 text-white text-left">
                                    <tr>
                                        <th class="px-4 py-3">#</th>
                                        <th class="px-4 py-3">Nama Siswa</th>
                                        <th class="px-4 py-3">Kelas</th>
                                        <th class="px-4 py-3">Keterangan</th>
                                        <th class="px-4 py-3">Jam Presensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presensi as $index => $p)
                                        @php
                                            $bg = $index % 2 === 0 ? 'bg-gray-50' : 'bg-white';
                                            $color = match($p->keterangan) {
                                                'Hadir' => 'bg-green-100 text-green-800 border-green-300',
                                                'Izin' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                                'Sakit' => 'bg-orange-100 text-orange-800 border-orange-300',
                                                'Alpa', 'Alpha' => 'bg-red-100 text-red-800 border-red-300',
                                                default => 'bg-gray-100 text-gray-800 border-gray-300',
                                            };
                                        @endphp
                                        <tr class="{{ $bg }} hover:bg-blue-50 transition">
                                            <td class="px-4 py-3 font-medium text-gray-600">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 font-semibold">{{ $p->user->name ?? '-' }}</td>
                                            <td class="px-4 py-3">{{ $p->dataSiswa->kelas->kelas ?? '-' }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $color }}">
                                                    {{ $p->keterangan }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-500">{{ $p->created_at->format('H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

