<div
    x-show="open"
    x-cloak
    @click.self="open = false"
    @keydown.escape.window="open = false"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60"
>
    <div
        @click.away="open = false"
        class="relative w-full max-w-md p-6 mx-4 bg-white shadow-xl rounded-xl md:mx-0"
    >
        <h2 class="pb-2 mb-4 text-2xl font-bold text-gray-800 border-b">Upload Foto Profil</h2>

        <!-- CLOSE BUTTON -->
        <button @click="open = false" class="absolute text-gray-400 top-3 right-3 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- FORM UPLOAD -->
        <form action="{{ route('foto.upload') }}" method="POST" enctype="multipart/form-data" id="form-upload">
            @csrf
            <input type="file" name="foto" required accept="image/*"
            class="block w-full p-2 mb-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            <p class="mb-4 text-sm text-gray-500">
                Ukuran ideal foto: persegi (square), minimal 500×500 px. File maksimal 10MB.
                Format: JPG, PNG, JPEG, atau WebP.
            </p>
        </form>

        <!-- BUTTON ACTION -->
        <div class="flex justify-end space-x-2">
            <button type="submit" form="form-upload"
                    class="px-4 py-2 text-white transition bg-blue-600 rounded shadow-sm hover:bg-blue-700">
                Upload
            </button>

            <form id="hapusFotoForm" action="{{ route('foto.remove') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 text-red-600 border border-red-300 rounded hover:bg-red-50">
                    Remove
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hapusForm = document.getElementById('hapusFotoForm');
        hapusForm.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus foto profil ?',
                text: "Foto profil akan dihapus permanen!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    hapusForm.submit();
                }
            });
        });
    });
</script>
@endpush
