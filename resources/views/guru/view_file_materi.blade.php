<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Detail Materi') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="hidden mx-0 mt-2 mb-4 md:block md:top-0 md:ml-6 md:mt-6 md:h-screen md:w-auto">
            <x-sidebar />
        </aside>

        <main class="flex-1 p-0 mb-16 space-y-2 overflow-x-auto md:space-y-6 md:mb-0 md:p-6">
            <div x-data>

                <div class="p-4 bg-white border rounded shadow-sm">
                    <h3 class="mb-2 text-2xl text-gray-800">Judul Materi : {{ $materi->judul }}</h3>

                    <p class="pb-2 mb-10 text-gray-600">
                        Deskripsi :
                        <span>{!! strip_tags($materi->materi) ?: '<em class="text-gray-400">Tidak ada deskripsi materi / Hanya berupa file!</em>' !!}</span>
                    </p>

                    @if($materi->file_path)
                        <div class="flex flex-col items-center justify-center w-full px-2 pt-6 mx-auto mb-6 border rounded-md shadow hover:shadow-lg border-slate-300">
                            <!-- Preview PDF -->
                            @if(Str::endsWith($materi->file_path, ['.pdf']))
                                <iframe src="{{ asset($materi->file_path) }}" class="w-full h-[600px] border"></iframe>
                            @elseif(Str::endsWith($materi->file_path, ['.jpg', '.jpeg', '.png', '.webp']))
                                <img src="{{ asset($materi->file_path) }}" class="max-h-[240px] mx-auto" alt="Preview Gambar">
                            @else
                                <p class="pt-8 mb-4 text-gray-500">Preview tidak tersedia untuk file ini.</p>
                            @endif

                            <!-- Tombol Download -->
                            <a href="{{ asset($materi->file_path) }}" download
                            class="px-4 py-2 mt-4 mb-10 text-white bg-blue-600 rounded hover:bg-blue-700">
                                Download File
                            </a>
                        </div>
                    @else
                        <p class="text-gray-500">Tidak ada file yang diupload.</p>
                    @endif
                </div>

                <div class="mt-6">
                    <a href="{{ route('guru.materi.index') }}"
                    class="pl-2 text-blue-600 hover:underline">
                        ← Kembali ke Daftar Materi
                    </a>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-layout>
