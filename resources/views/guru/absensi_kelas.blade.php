@php
use Carbon\Carbon;
@endphp

<x-app-dashboard-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ __($pageTitle ?? 'Rekap Absensi Siswa') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="z-0 mx-4 mt-4 md:z-10 top-16 md:top-0 md:ml-6 md:mt-6 md:h-screen md:mx-0 md:w-auto">
            <x-sidebar />
        </aside>

        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
            <div class="flex items-center justify-center w-full p-10 bg-white rounded shadow">
                <h2 class="mb-0 text-lg font-bold">
                    Halaman Rekap Absensi Siswa
                    <span class="hidden capitalize text-sky-900 md:inline-block">| {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span>
                </h2>
                <hr class="mb-4">
            </div>

            <div class="overflow-x-auto md:overflow-x-visible">
                {{-- Form Filter Periode --}}
                <div class="p-6 mb-6 bg-white rounded shadow-md">
                    <h2 class="mb-4 text-lg font-bold">Detail Riwayat Presensi <span class="hidden md:inline-block">Periode Tertentu</span></h2>

                    <form action="{{ route('guru.absensi_kelas.index') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 font-semibold">Periode Mulai</label>
                                <input type="date" name="periode_mulai" class="w-full p-2 border rounded"
                                    value="{{ request('periode_mulai') ?? '' }}" required>
                            </div>
                            <div>
                                <label class="block mb-2 font-semibold">Periode Akhir</label>
                                <input type="date" name="periode_akhir" class="w-full p-2 border rounded"
                                    value="{{ request('periode_akhir') ?? '' }}" required>
                            </div>
                        </div>

                        <div class="flex justify-end pt-3 md:pt-4 md:justify-start">
                            <button type="submit" class="flex items-center px-6 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z"/>
                                </svg>
                                Generate
                            </button>
                        </div>
                    </form>

                    <hr class="mt-6 mb-3">

                    {{-- Restore Backup --}}
                    <form action="{{ route('guru.absensi_kelas.restore') }}" method="POST" enctype="multipart/form-data" class="inline space-y-4">
                        @csrf
                        <label class="block mb-2 text-sm font-medium text-gray-700">Upload File Backup (Format JSON)</label>
                        <input type="file" name="backup_file" accept=".json,application/json" class="p-2 w-full md:w-auto text-sm text-gray-700 border rounded cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#063970]" required>
                        <div class="flex justify-end md:justify-start">
                        <button type="submit" class="px-4 py-2 text-white rounded bg-slate-700">
                            {{-- <i class="bi bi-arrow-counterclockwise me-1"></i> --}}
                            <i class="bi bi-arrow-repeat"></i> Restore
                        </button>
                        </div>
                    </form>
                </div>

                {{-- Hasil --}}
                <div class="p-6 mb-6 bg-white rounded shadow-md">
                    @if($isGenerated)
                        <x-guru.absensi-kelas
                            :rekap="$rekap"
                            :periode_mulai="$periode_mulai"
                            :periode_akhir="$periode_akhir"
                            :kelas_id="$kelas_id"
                            :isGenerated="$isGenerated"
                        />
                    @else
                        <div class="p-4 text-center text-blue-700 bg-blue-100 border border-blue-400 rounded">
                            Silakan pilih periode, lalu klik tombol Generate untuk melihat data.
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <x-footer :profil="$profil" />
</x-app-dashboard-layout>
