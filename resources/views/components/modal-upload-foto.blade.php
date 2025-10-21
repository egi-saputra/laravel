<div
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    @keydown.escape.window="open = false"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="fixed inset-0 z-20 flex items-center justify-center bg-black bg-opacity-45"
    x-on:open-modal-upload-foto.window="open = true"
>
    <div
        @click.away="if($event.target.tagName !== 'INPUT') open = false"
        class="relative w-full max-w-md p-6 mx-4 bg-white shadow-xl rounded-xl md:mx-0"
    >
        <h2 class="pb-2 mb-4 text-2xl font-bold text-gray-800 border-b">
            Upload Foto Profil
        </h2>

        <!-- CLOSE BUTTON -->
        <button
            @click="open = false"
            class="absolute text-gray-400 top-3 right-3 hover:text-gray-600"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- FORM UPLOAD -->
        <form
            action="{{ route('foto.upload') }}"
            method="POST"
            enctype="multipart/form-data"
            id="form-upload"
        >
            @csrf

            <div class="mb-4">
                <!-- Teks nama file -->
                <div id="fileName" class="mb-2 text-sm font-medium text-center text-gray-700"></div>

                <!-- Input file -->
                <input
                    type="file"
                    name="foto"
                    id="inputFoto"
                    required
                    accept="image/*"
                    class="hidden"
                >

                <!-- Label sebagai tombol -->
                <label
                    for="inputFoto"
                    class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 rounded cursor-pointer text-slate-800 hover:border-slate-800"
                >
                    <i class="mr-2 bi bi-upload"></i>
                    Pilih File
                </label>

                <!-- Info tambahan -->
                <p class="my-4 text-xs leading-relaxed text-gray-500 md:text-sm">
                    Ukuran ideal foto: persegi
                    <span class="hidden md:inline-block">(square)</span>,
                    minimal 500×500 px.<br class="block md:hidden">
                    Format: JPG, PNG, JPEG, atau WebP. Max Size: 10MB.
                </p>
            </div>
        </form>

        <!-- BUTTON ACTION -->
        <div class="flex justify-end space-x-2">
            <button
                type="submit"
                form="form-upload"
                class="px-4 py-2 text-white transition bg-blue-600 rounded shadow-sm hover:bg-blue-700"
            >
                Upload
            </button>

            <form
                id="hapusFotoForm"
                action="{{ route('foto.remove') }}"
                method="POST"
            >
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="px-4 py-2 text-red-600 border border-red-300 rounded hover:bg-red-50"
                >
                    Remove
                </button>
            </form>
        </div>
    </div>
</div>


@push('scripts')
<script>
document.addEventListener('turbo:load', initFotoScript);
document.addEventListener('turbo:frame-load', initFotoScript);

function initFotoScript() {
    const inputFoto = document.getElementById('inputFoto');
    const fileName = document.getElementById('fileName');
    const hapusForm = document.getElementById('hapusFotoForm');

    if (inputFoto) {
        inputFoto.addEventListener('change', () => {
            fileName.textContent = inputFoto.files?.[0]?.name || '';
        });
    }

    if (hapusForm) {
        hapusForm.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus foto profil?',
                text: "Foto profil akan dihapus permanen!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) hapusForm.submit();
            });
        });
    }
}
</script>
@endpush

