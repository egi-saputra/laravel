{{-- @props(['id', 'title' => 'Detail'])

<div x-data="{ open: false }" class="inline-block">
    <!-- Trigger -->
    <button type="button"
            @click="open = true"
            class="px-4 py-1 text-white bg-blue-600 rounded-md hover:bg-blue-700">
        Lihat Detail
    </button>

    <!-- Modal -->
    <div x-show="open"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         x-cloak>
        <div class="w-full max-w-3xl p-2 mx-2 bg-white rounded-lg shadow-lg dark:bg-gray-800 sm:mx-0">
            <!-- Header -->
            <div class="flex items-center justify-between pb-2 mb-4 border-b">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $title }}</h3>
                <button @click="open = false"
                        class="text-gray-600 hover:text-gray-800 dark:text-gray-300">
                    ✕
                </button>
            </div>

            <!-- Content -->
            <div class="prose max-h-[70vh] overflow-y-auto dark:prose-invert text-left">
                {!! $slot !!}
            </div>

            <!-- Footer -->
            <div class="flex justify-end mt-4">
                <button @click="open = false"
                        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div> --}}

@props(['id', 'title' => 'Detail', 'materi'])

<div x-data="{ open: false }" class="inline-block">
    <!-- Trigger -->
    {{-- <button type="button"
            @click="open = true"
            class="px-4 py-1 text-white bg-blue-600 rounded-md hover:bg-blue-700">
        Lihat Detail
    </button> --}}
    <button type="button"
            @click="open = true"
            class="px-3 py-1.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow hover:from-blue-700 hover:to-blue-800 transition">
        <i class="bi bi-eye"></i> Lihat Materi
    </button>

    <!-- Modal -->
    <div x-show="open"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         x-cloak>
        <div class="w-full max-w-3xl p-4 mx-2 bg-white rounded-lg shadow-lg dark:bg-gray-800 sm:mx-0">

            <!-- Header -->
            <div class="flex items-center justify-between pb-2 mb-2 border-b">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100"><i class="bi bi-file-earmark-text"></i> {{ $title }}</h3>
                <button @click="open = false"
                        class="hidden text-gray-600 md:block hover:text-gray-800 dark:text-gray-300">
                    ✕
                </button>
            </div>

            <!-- Content -->
            <div class="max-h-[70vh] overflow-y-auto text-left">
                <!-- Judul Materi -->
                {{-- <h2 class="mb-2 text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $materi->judul }}</h2> --}}

                <!-- Info Author, Kelas, Mapel -->
                <div class="mb-4">
                    <div class="mb-2">
                        <span class="py-1 pb-4 text-base font-bold text-slate-800">
                            {{ $materi->mapel->mapel ?? '-' }}
                        </span>
                    </div>
                    <div class="flex justify-start gap-2">
                        <span class="px-2 py-1 text-sm font-semibold rounded text-amber-600 bg-amber-100">
                            {{ $materi->kelas->kelas ?? '-' }}
                        </span>
                        <span class="px-2 py-1 text-sm font-semibold text-[#063970] rounded bg-blue-100">
                            Author: {{ $materi->user->name ?? 'Unknown' }}
                        </span>
                    </div>
                </div>

                <!-- Deskripsi Materi -->
                {{-- <div class="mb-4 text-gray-700 dark:text-gray-200">
                    <h4 class="mb-1 font-semibold">Deskripsi:</h4>
                    <p class="whitespace-pre-line">
                        {!! $materi->deskripsi ?: '<em class="text-gray-400 dark:text-gray-400">Tidak ada deskripsi materi / Hanya berupa file!</em>' !!}
                    </p>
                </div> --}}

                <!-- Isi Materi -->
                @if($materi->materi)
                    <div class="mb-4 text-gray-700 dark:text-gray-200">
                        <h4 class="mb-1 font-semibold">Isi materi :</h4>
                        <div class="max-w-full prose dark:prose-invert">
                            {!! $materi->materi !!}
                        </div>
                    </div>
                @elseif($materi->deskripsi)
                    <div class="mb-4 text-gray-700 dark:text-gray-200">
                        <h4 class="mb-1 font-semibold">Deskripsi Materi:</h4>
                        <div class="max-w-full prose dark:prose-invert">
                            {!! $materi->deskripsi !!}
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada materi</p>
                @endif

                <!-- File Materi -->
                {{-- @if($materi->file_path)
                    <div class="flex flex-col items-center justify-center w-full px-2 py-4 mb-4 border rounded-md shadow hover:shadow-lg border-slate-300 dark:border-gray-600">
                        @if(Str::endsWith($materi->file_path, ['.pdf']))
                            <iframe src="{{ asset($materi->file_path) }}" class="w-full h-[400px] border rounded"></iframe>
                        @elseif(Str::endsWith($materi->file_path, ['.jpg', '.jpeg', '.png', '.webp']))
                            <img src="{{ asset($materi->file_path) }}" class="max-h-[240px] mx-auto rounded" alt="Preview Gambar">
                        @else
                            <p class="pt-4 mb-2 text-gray-500 dark:text-gray-300">Preview tidak tersedia untuk file ini.</p>
                        @endif

                        <a href="{{ asset($materi->file_path) }}" download
                           class="px-4 py-2 mt-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            Download File
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada file yang diupload.</p>
                @endif --}}
            </div>

            <!-- Footer -->
            <div class="flex justify-end mt-4">
                <button @click="open = false"
                        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

