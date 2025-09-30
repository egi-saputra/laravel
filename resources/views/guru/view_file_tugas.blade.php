<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Detail Tugas Siswa') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <aside class="sticky z-10 w-full top-16 md:static md:w-auto md:ml-6 md:mt-6 md:h-screen md:top-0">
            <x-sidebar />
        </aside>

        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
            <div x-data>

                <div class="p-4 bg-white border rounded shadow-sm">
                    <h3 class="inline-block pb-1 pl-1 pr-2 mb-2 text-xl font-semibold text-gray-800 border-b border-slate-300">
                        Judul Tugas ( {{ $tugas->judul }} )
                    </h3>

                    <div class="mb-8 ml-0 space-y-2 md:ml-4">
                        <div class="grid md:grid-cols-[140px_10px_1fr] grid-cols-[160px_10px_1fr] items-start">
                            <span class="font-semibold">Nama Pengirim</span>
                            <span class="text-left">:</span>
                            <span class="capitalize">{{ $tugas->nama ?? '-' }}</span>
                        </div>

                        <div class="grid md:grid-cols-[140px_10px_1fr] grid-cols-[160px_10px_1fr] items-start">
                            <span class="font-semibold">Nama Kelompok</span>
                            <span class="text-left">:</span>
                            <span class="capitalize">{{ $tugas->kelompok ?? '-' }}</span>
                        </div>

                        <div class="grid md:grid-cols-[140px_10px_1fr] grid-cols-[160px_10px_1fr] items-start">
                            <span class="font-semibold">Deskripsi</span>
                            <span class="text-left">:</span>
                            <span class="text-gray-600">
                                {!! strip_tags($tugas->materi ?? '') ?: '<em class="text-gray-400">Tidak ada deskripsi tugas / hanya berupa file!</em>' !!}
                            </span>
                        </div>
                    </div>

                    @if($tugas->file_path)
                        <div class="flex flex-col items-center justify-center w-1/2 pt-6 mx-auto mb-6 border rounded-md shadow hover:shadow-lg border-slate-300">
                            {{-- Preview File --}}
                            @if(Str::endsWith($tugas->file_path, ['.pdf']))
                                <iframe src="{{ asset($tugas->file_path) }}" class="w-full h-[600px] border"></iframe>
                            @elseif(Str::endsWith($tugas->file_path, ['.jpg', '.jpeg', '.png', '.webp']))
                                <img src="{{ asset($tugas->file_path) }}" class="max-h-[240px] mx-auto" alt="Preview Gambar">
                            @else
                                <p class="pt-8 mb-4 text-gray-500">Preview tidak tersedia untuk file ini.</p>
                            @endif

                            {{-- Tombol Download --}}
                            <a href="{{ asset($tugas->file_path) }}" download
                               class="px-4 py-2 mt-4 mb-10 text-white bg-blue-600 rounded hover:bg-blue-700">
                                Download File
                            </a>
                        </div>
                    @else
                        <p class="w-1/2 p-8 mx-auto mb-4 text-center text-gray-500 border rounded shadow">Tidak ada file yang diupload.</p>
                    @endif
                </div>

                <div class="mt-6">
                    <a href="{{ route('guru.tugas_siswa.index') }}"
                       class="pl-2 text-blue-600 hover:underline">
                        ← Kembali ke Daftar Tugas
                    </a>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-layout>
