@php
    $hariIni = \Carbon\Carbon::now()->locale('id')->dayName;
@endphp

@if($guru && $guru->jadwalPiket()->where('hari', $hariIni)->exists())
    <div class="flex flex-col items-center justify-center w-full p-6 rounded-2xl shadow-lg border border-slate-200 bg-gradient-to-br from-slate-50 to-white relative overflow-hidden">
        {{-- Background Accent --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,0,0,0.1),transparent_70%)] pointer-events-none"></div>

        {{-- Greeting --}}
        <h1 class="text-xl md:text-2xl font-bold text-gray-800 text-center z-10">
            👋 Halo, {{ auth()->user()->name }}
        </h1>
        <p class="mt-2 text-sm md:text-base text-gray-600 text-center font-medium z-10">
            Anda adalah <span class="font-semibold text-red-600">petugas piket</span> hari ini
            <span class="block md:inline">({{ ucfirst($hariIni) }})</span>
        </p>

        {{-- Divider --}}
        <div class="w-16 h-1 bg-gradient-to-r from-red-500 to-red-700 rounded-full my-4"></div>

        {{-- Buttons Container --}}
        <div class="flex flex-col md:flex-row gap-4 z-10">
            {{-- Presensi Guru --}}
            <form action="{{ route('guru.presensi.index') }}" method="get">
                <button type="submit"
                    class="group relative overflow-hidden px-6 py-3 md:px-8 md:py-4 font-semibold text-white rounded-xl bg-gradient-to-r from-red-600 to-red-700 shadow-md transition-all duration-300 hover:shadow-lg hover:scale-105">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <i class="bi bi-clipboard-check text-lg"></i>
                        <span>Presensi Guru</span>
                        <i class="bi bi-arrow-right hidden md:inline-block group-hover:translate-x-1 transition-transform"></i>
                    </span>
                    <span class="absolute inset-0 bg-gradient-to-r from-red-500 to-red-700 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                </button>
            </form>

            {{-- Presensi Siswa --}}
            <form action="{{ route('guru.absensi_hari_ini') }}" method="get">
                <button type="submit"
                    class="group relative overflow-hidden px-6 py-3 md:px-8 md:py-4 font-semibold text-white rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 shadow-md transition-all duration-300 hover:shadow-lg hover:scale-105">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <i class="bi bi-people-fill text-lg"></i>
                        <span>Presensi Siswa</span>
                        <i class="bi bi-arrow-right hidden md:inline-block group-hover:translate-x-1 transition-transform"></i>
                    </span>
                    <span class="absolute inset-0 bg-gradient-to-r from-blue-500 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                </button>
            </form>
        </div>
    </div>
@endif
