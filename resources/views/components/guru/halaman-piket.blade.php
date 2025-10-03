@php
    $hariIni = \Carbon\Carbon::now()->locale('id')->dayName;
@endphp

@if($guru && $guru->jadwalPiket()->where('hari', $hariIni)->exists())
    <div class="flex items-center justify-center w-full p-10 bg-white rounded shadow-md md:rounded-xl">
        {{-- <form action="{{ url('guru/halaman-piket') }}" method="get"> --}}
        {{-- <form action="{{ route('guru.presensi.create') }}" method="get"> --}}
            <form action="{{ route('guru.presensi.index') }}" method="get">
            <h1 class="mb-2 text-lg font-semibold text-center capitalize">
                Halo {{ auth()->user()->name }},
            </h1>
                <p class="mb-4 text-base font-semibold text-center">Anda adalah petugas piket hari ini<span class="hidden md:inline-block"> ({{ $hariIni }})</span>.</p>
            <button type="submit"
                class="px-4 py-2 mx-auto mb-4 text-xl font-bold text-white transition duration-300 bg-red-600 rounded shadow md:rounded-md md:py-6 md:px-8 hover:bg-red-700">
                Klik untuk masuk <span class="hidden md:inline-block">halaman presensi guru! </span><span class="text-xl font-bold text-white"><i class="ml-2 bi bi-arrow-right"></i></span>
            </button>
        </form>
    </div>
@endif
