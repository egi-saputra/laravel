@php
use Carbon\Carbon;
@endphp

<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ __($pageTitle ?? 'Rekap Honor Guru') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="mx-4 mt-4 top-16 md:top-0 md:ml-6 md:mt-6 md:h-screen md:mx-0 md:w-auto">
            <x-sidebar />
        </aside>

        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
            <div class="flex items-center justify-center w-full p-10 bg-white rounded shadow">
                <h2 class="mb-4 text-lg font-bold">
                    Data Riwayat Presensi Guru Dan Staff |
                    <span class="capitalize text-sky-900">{{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }} |</span>
                </h2>
                <hr class="mb-4">
            </div>

            <div class="overflow-x-auto md:overflow-x-visible">
                <div class="p-6 mb-6 bg-white rounded shadow-md">
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
                        <div class="pt-4">
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
                        <input type="file" name="backup_file" accept=".json,application/json" class="p-2 text-sm text-gray-700 border rounded cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#063970]" required>
                        <button type="submit" class="px-4 py-2 text-white rounded bg-slate-700">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Restore Backup
                        </button>
                    </form>
                </div>

                {{-- hasil --}}
                <div class="p-6 mb-6 bg-white rounded shadow-md">
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

    <x-footer :profil="$profil" />
</x-app-backtop-layout>
