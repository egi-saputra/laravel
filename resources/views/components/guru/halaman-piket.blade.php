@php
    $hariIni = \Carbon\Carbon::now()->locale('id')->dayName;
@endphp

@if($guru && $guru->jadwalPiket()->where('hari', $hariIni)->exists())
    <div class="flex items-center justify-center w-full p-10 bg-white shadow-md rounded-xl">
        {{-- <form action="{{ url('guru/halaman-piket') }}" method="get"> --}}
        {{-- <form action="{{ route('guru.presensi.create') }}" method="get"> --}}
            <form action="{{ route('guru.presensi.index') }}" method="get">
            <h1 class="mb-2 text-lg font-semibold text-center capitalize">
                Halo {{ auth()->user()->name }},
            </h1>
                <p class="mb-4 text-base font-semibold text-center">Anda adalah petugas piket hari ini ({{ $hariIni }}).</p>
            <button type="submit"
                class="px-8 py-6 mb-4 text-xl font-bold text-white transition duration-300 bg-red-600 rounded-md shadow hover:bg-red-700">
                Klik untuk masuk halaman presensi guru! <span class="text-xl font-bold text-white"><i class="ml-2 bi bi-arrow-right"></i></span>
            </button>
        </form>
    </div>
@endif
