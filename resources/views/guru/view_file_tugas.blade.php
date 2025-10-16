<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Detail Tugas Siswa') }}
        </h2>
    </x-slot>

    <div class="flex-col min-h-screen flex md:flex-row">
        <!-- Sidebar -->
        <aside class="top-0 hidden p-2 mb-4 mr-4 md:block md:h-screen">
            <x-sidebar />
        </aside>

        <main class="flex-1 p-2 overflow-x-auto md:space-y-6">
            <div x-data>

                <div class="p-4 px-4 bg-white border rounded shadow-sm md:px-6">
                    <h3 class="inline-block pb-1 pl-1 pr-2 mt-0 mb-2 text-base font-semibold text-gray-800 border-b md:text-xl md:mt-4 border-slate-300">
                        Judul Tugas ( {{ $tugas->judul }} )
                    </h3>

                    <div class="mb-8 space-y-2">
                        <div class="grid md:grid-cols-[140px_10px_1fr] grid-cols-[100px_10px_1fr] items-start ml-2">
                            <span class="text-sm font-semibold md:text-base">Nama Pengirim</span>
                            <span class="text-sm text-left md:text-base">:</span>
                            <span class="text-sm capitalize md:text-base">{{ $tugas->nama ?? '-' }}</span>
                        </div>

                        <div class="grid md:grid-cols-[140px_10px_1fr] ml-2 grid-cols-[100px_10px_1fr] items-start">
                            <span class="text-sm font-semibold md:text-base">Nama Kelompok</span>
                            <span class="text-sm text-left md:text-base">:</span>
                            <span class="text-sm capitalize md:text-base">{{ $tugas->kelompok ?? '-' }}</span>
                        </div>

                        {{-- <div class="grid md:grid-cols-[140px_10px_1fr] grid-cols-[100px_10px_1fr] items-start">
                            <span class="text-sm font-semibold md:text-base">Deskripsi</span>
                            <span class="text-sm text-left md:text-base">:</span>
                            <span class="text-sm text-gray-600 md:text-base">
                                {!! strip_tags($tugas->materi ?? '') ?: '<em class="text-gray-400">Tidak ada deskripsi tugas!</em>' !!}
                            </span>
                        </div> --}}
                    </div>

                    @if($tugas->file_path)
                        <div class="flex flex-col items-center justify-center w-full pt-6 mx-auto mb-6 border rounded-md shadow md:w-1/2 hover:shadow-lg border-slate-300">
                            {{-- Preview File --}}
                            @if(Str::endsWith($tugas->file_path, ['.pdf']))
                                <iframe src="{{ asset($tugas->file_path) }}" class="w-full h-[600px] border"></iframe>
                            @elseif(Str::endsWith($tugas->file_path, ['.jpg', '.jpeg', '.png', '.webp']))
                                <img src="{{ asset($tugas->file_path) }}" class="max-h-[240px] mx-auto" alt="Preview Gambar">
                            @else
                                <p class="pt-8 mb-4 text-gray-500">Preview tidak tersedia untuk file ini.</p>
                            @endif

                            {{-- Tombol Download --}}
                            <a href="{{ route('guru.tugas.download', $tugas->id) }}"
                            class="px-4 py-2 mt-4 mb-10 text-white bg-blue-600 rounded hover:bg-blue-700">
                                Download File
                            </a>
                        </div>
                    @else
                        <p class="w-1/2 p-8 mx-auto mb-4 text-center text-gray-500 border rounded shadow">Tidak ada file yang diupload.</p>
                    @endif
                    <div class="block mt-6 md:hidden">
                        <button type="button"
                            onclick="window.location.href='{{ route('guru.tugas_siswa.index') }}'"
                            class="pl-2 font-semibold text-blue-600 hover:underline">
                            ← Back
                        </button>
                    </div>
                </div>

                <div class="hidden mt-6 md:block">
                        <a href="{{ route('guru.tugas_siswa.index') }}"
                           class="pl-2 font-semibold text-blue-600 hover:underline">
                            ← Kembali ke daftar tugas siswa
                        </a>
                    </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-layout>
