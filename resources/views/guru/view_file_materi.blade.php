{{-- <x-app-layout>
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
</x-app-layout> --}}

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

        <!-- Main Content -->
        <main class="flex-1 p-0 mb-16 space-y-2 overflow-x-auto md:space-y-6 md:mb-0 md:p-6">
            <div x-data>

                <!-- Card Materi -->
                <div class="p-6 bg-white border rounded-lg shadow-lg">
                    <!-- Judul Materi -->
                    <h1 class="mb-4 text-3xl font-bold text-gray-800">{{ $materi->judul }}</h1>

                    <!-- Info tambahan -->
                    <div class="flex flex-col gap-2 mb-6 md:flex-row md:gap-6">
                        <div class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded">
                            <span class="font-semibold">Author:</span> {{ $materi->user->name ?? 'Unknown' }}
                        </div>
                        <div class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded">
                            <span class="font-semibold">Kelas:</span> {{ $materi->kelas->kelas ?? '-' }}
                        </div>
                        <div class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded">
                            <span class="font-semibold">Mata Pelajaran:</span> {{ $materi->mapel->mapel ?? '-' }}
                        </div>
                    </div>

                    <!-- Deskripsi Materi -->
                    <div class="mb-6 text-gray-700">
                        <h2 class="mb-2 text-lg font-semibold">Deskripsi Materi</h2>
                        <p class="whitespace-pre-line">
                            {!! $materi->deskripsi ?: '<em class="text-gray-400">Tidak ada deskripsi materi / Hanya berupa file!</em>' !!}
                        </p>
                    </div>

                    <!-- Isi Materi -->
                    @if($materi->materi)
                        <div class="mb-6 text-gray-700">
                            <h2 class="mb-2 text-lg font-semibold">Isi Materi</h2>
                            <div class="max-w-full prose">
                                {!! $materi->materi !!}
                            </div>
                        </div>
                    @endif

                    <!-- File Materi -->
                    <div class="flex flex-col items-center justify-center w-full px-4 py-6 mb-6 border rounded-md shadow hover:shadow-lg border-slate-300">
                        @if($materi->file_path)
                            @if(Str::endsWith($materi->file_path, ['.pdf']))
                                <iframe src="{{ asset($materi->file_path) }}" class="w-full h-[600px] border rounded"></iframe>
                            @elseif(Str::endsWith($materi->file_path, ['.jpg', '.jpeg', '.png', '.webp']))
                                <img src="{{ asset($materi->file_path) }}" class="max-h-[240px] mx-auto rounded" alt="Preview Gambar">
                            @else
                                <p class="pt-8 mb-4 text-gray-500">Preview tidak tersedia untuk file ini.</p>
                            @endif

                            <a href="{{ asset($materi->file_path) }}" download
                               class="px-4 py-2 mt-4 text-white bg-blue-600 rounded hover:bg-blue-700">
                                Download File
                            </a>
                        @else
                            <p class="text-gray-500">Tidak ada file yang diupload.</p>
                        @endif
                    </div>

                    <!-- Tombol kembali -->
                    <div class="mt-6">
                        <a href="{{ route('guru.materi.index') }}"
                           class="pl-2 text-blue-600 hover:underline">
                            ← Kembali ke Daftar Materi
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-layout>

