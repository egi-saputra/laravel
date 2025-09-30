@props(['id', 'title' => 'Detail'])

<div x-data="{ open: false }" class="inline-block">
    <!-- Trigger -->
    <button type="button"
            @click="open = true"
            class="px-4 py-1 text-white bg-blue-600 rounded-md hover:bg-blue-700">
        Lihat Detail
    </button>
    {{-- <a href="javascript:void(0)"
       @click="open = true"
       class="block text-center text-blue-600 hover:underline">
        Lihat Detail
    </a> --}}

    <!-- Modal -->
    <div x-show="open"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         x-cloak>
        <div class="w-full max-w-3xl p-6 mx-2 bg-white rounded-lg shadow-lg dark:bg-gray-800 sm:mx-0">
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
</div>
