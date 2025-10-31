@php
use Carbon\Carbon;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ __($pageTitle ?? 'Rekap Honor Guru') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:!flex-row">

        <aside class="hidden mx-0 mt-2 mb-4 md:block md:top-0 md:ml-6 md:mt-6 md:w-auto">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Footer -->
            <x-footer :profil="$profil" />
        </aside>

        <!-- Main Content -->
        <main class="!flex-1 p-0 !mb-16 space-y-2 overflow-x-auto md:space-y-6 md:mb-0 md:p-6">
            <div class="items-center justify-center hidden w-full p-10 bg-white rounded shadow md:flex">
                <h2 class="text-lg font-bold">
                    Data Riwayat Presensi Guru Dan Staff |
                    <span class="capitalize text-sky-900">{{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span>
                </h2>
            </div>
            {{-- <div class="flex flex-col items-center justify-center w-full p-8 text-center text-white rounded shadow bg-gradient-to-r from-slate-900 via-indigo-900 to-sky-900">
                <h2 class="mb-2 text-xl font-extrabold tracking-wide md:text-2xl drop-shadow-sm">
                    Data Riwayat Presensi Guru dan Staff |
                    <span class="text-transparent capitalize bg-gradient-to-r from-cyan-300 via-sky-400 to-blue-500 bg-clip-text">
                        {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }}
                    </span>
                </h2>

                <div class="w-24 h-1 mt-2 rounded-full animate-pulse bg-gradient-to-r from-cyan-400 to-blue-500"></div>
            </div> --}}

            <div class="overflow-x-auto md:overflow-x-visible">
                <div class="mb-6 md:p-6 md:bg-white md:rounded md:shadow-md">
                    <h2 class="mb-4 text-lg font-bold">Cari Riwayat Presensi Periode Tertentu</h2>

                    <form action="{{ route('staff.riwayat_presensi.generate') }}" method="POST" class="space-y-4">
                        @csrf

                        {{-- Pilih Sumber Data --}}
                        <div>
                            <label class="block mb-2 font-semibold">Pilih Jenis Riwayat (Guru/Staff)</label>
                            <select name="jenis_presensi" class="w-full p-2 border rounded" required>
                                <option value="">-- Pilih Riwayat --</option>
                                <option value="guru" {{ request('jenis_presensi') == 'guru' ? 'selected' : '' }}>Riwayat Presensi Guru</option>
                                <option value="staff" {{ request('jenis_presensi') == 'staff' ? 'selected' : '' }}>Riwayat Presensi Staff</option>
                            </select>
                        </div>

                        {{-- Periode Mulai & Akhir --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 font-semibold">Periode Mulai</label>
                                <input type="date" name="periode_mulai" class="w-full p-2 border rounded"
                                    value="{{ request('periode_mulai') }}" required>
                            </div>
                            <div>
                                <label class="block mb-2 font-semibold">Periode Akhir</label>
                                <input type="date" name="periode_akhir" class="w-full p-2 border rounded"
                                    value="{{ request('periode_akhir') }}" required>
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="flex justify-end pt-4 md:justify-start">
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
                    <form action="{{ route('staff.riwayat_presensi.restore') }}" method="POST" enctype="multipart/form-data" class="inline space-y-4">
                        @csrf
                        <label class="block mb-2 text-sm font-medium text-gray-700">Upload File Backup (Format JSON)</label>
                        <div class="flex flex-col w-full gap-4 md:flex-row">
                            <input type="file" name="backup_file" accept=".json,application/json" class="p-2 text-sm text-gray-700 border rounded cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#063970]" required>
                            <button type="submit" class="px-4 py-2 text-white rounded bg-slate-700">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restore Backup
                            </button>
                        </div>
                    </form>
                </div>

                {{-- hasil --}}
                <div class="mb-6 md:p-6 md:bg-white md:rounded md:shadow-md">
                    @if(isset($rekap) && $rekap->count() > 0)
                        <x-staff.rekap-presensi :rekap="$rekap" />
                    @elseif($isGenerated && $rekap->count() == 0)
                        <div class="p-4 text-red-700 bg-red-100 border border-red-400 rounded">
                            Tidak ada data yang ditemukan pada periode yang dipilih!
                        </div>
                    @else
                        <div class="p-4 text-center text-blue-700 bg-blue-100 border border-blue-400 rounded">
                            Silakan pilih jenis presensi & periode, lalu klik tombol Generate untuk melihat data.
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>
</x-app-layout>
