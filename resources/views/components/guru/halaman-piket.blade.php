{{-- @php
    $hariIni = \Carbon\Carbon::now()->locale('id')->dayName;
@endphp

@if($guru && $guru->jadwalPiket()->where('hari', $hariIni)->exists())
    <div class="flex items-center justify-center w-full p-4 bg-white rounded shadow-md md:rounded-xl">
            <form action="{{ route('guru.presensi.index') }}" method="get">
            <h1 class="mb-2 text-lg font-semibold text-center capitalize">
                Halo {{ auth()->user()->name }},
            </h1>
                <p class="mb-4 text-sm font-semibold text-center md:text-base">Anda adalah petugas piket hari ini<span class="hidden md:inline-block"> ({{ $hariIni }})</span>.</p>
            <button type="submit"
                class="px-4 py-2 mx-auto mb-4 text-base font-semibold text-white transition duration-300 bg-red-600 rounded shadow md:text-xl md:font-bold md:rounded-md md:py-6 md:px-8 hover:bg-red-700">
                Klik untuk masuk <span class="hidden md:inline-block">halaman presensi guru! </span><span class="hidden text-xl font-bold text-white md:inline-block"><i class="ml-2 bi bi-arrow-right"></i></span>
            </button>
        </form>
    </div>
@endif --}}


@php
    $hariIni = \Carbon\Carbon::now()->locale('id')->dayName;
@endphp

@if($guru && $guru->jadwalPiket()->where('hari', $hariIni)->exists())
    <div class="flex flex-col items-center justify-center w-full p-4 border rounded-lg shadow-sm bg-gradient-to-br from-slate-50 to-white border-slate-200 md:rounded-xl">
        <form action="{{ route('guru.presensi.index') }}" method="get" class="flex flex-col items-center">
            <h1 class="mb-0 text-base font-semibold text-center capitalize md:mb-2 md:text-lg">
                Halo {{ auth()->user()->name }}
            </h1>
            <p class="mb-4 text-sm font-semibold text-center md:text-base">
                Anda adalah petugas piket hari ini<span class="hidden md:inline-block"> ({{ $hariIni }})</span>.
            </p>
            <button type="submit"
                class="px-4 py-2 mb-2 text-base font-semibold text-white transition duration-300 rounded-lg shadow md:text-xl md:font-bold md:rounded-md md:py-6 md:px-8 hover:bg-red-700 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800">
                Klik untuk masuk
                <span class="hidden md:inline-block">halaman presensi guru!</span>
                <span class="hidden text-xl font-bold text-white md:inline-block">
                    <i class="ml-2 bi bi-arrow-right"></i>
                </span>
            </button>
        </form>
    </div>
@endif
