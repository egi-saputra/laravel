<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Daftar Siswa') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="hidden mt-0 mb-4 md:ml-6 md:block md:mt-6 md:h-screen md:mb-0 md:w-auto">
            <x-sidebar />
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 p-0 mt-4 mb-10 space-y-6 overflow-x-auto md:mt-6 md:mb-0 md:p-6">

            {{-- Search & Filter Form --}}
            <form method="GET" action="{{ route('public.daftar_siswa.index') }}"
                  class="flex flex-col items-end gap-4 mb-6 md:items-center md:flex-row">

                <!-- Global Search -->
                <div class="relative w-full md:col-span-2 md:w-auto">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 border-r text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                        </svg>
                    </span>
                    <input type="text" name="nama" placeholder="Search Name ...." value="{{ request('nama') }}" class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300">
                </div>

                {{-- Filter Kelas --}}
                <select name="kelas" class="w-full px-3 py-2 border rounded md:w-1/4">
                    <option value="Semua">-- Semua Kelas --</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                            {{ $kelas }}
                        </option>
                    @endforeach
                </select>

                {{-- Filter Kejuruan --}}
                <select name="kejuruan" class="w-full px-3 py-2 border rounded md:w-1/4">
                    <option value="Semua">-- Semua Kejuruan --</option>
                    @foreach($kejuruanList as $kejuruan)
                        <option value="{{ $kejuruan }}" {{ request('kejuruan') == $kejuruan ? 'selected' : '' }}>
                            {{ $kejuruan }}
                        </option>
                    @endforeach
                </select>

                {{-- Tombol Filter & Reset --}}
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                        <i class="bi bi-funnel"></i>  Filter
                    </button>
                    <a href="{{ route('public.daftar_siswa.index') }}"
                       class="px-4 py-2 text-white rounded bg-slate-700 hover:bg-slate-800">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>

            {{-- Grid Siswa --}}
            @php
                $guru = \App\Models\DataGuru::where('user_id', auth()->id())->first();
                $hakAkses = $guru ? $guru->hakAkses : null;
            @endphp

            <div id="siswaGrid">
                @if($hakAkses && $hakAkses->status === 'Activated')
                    <x-public.siswa-grid :siswa="$siswa" />
                @else
                    <x-public.siswa-grid-public :siswa="$siswa" />
                @endif
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $siswa->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-layout>
