<div
    x-data="{ open: false, fileName: '' }"
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
        <h2 class="pb-2 mb-4 text-2xl font-bold text-gray-800 border-b">Upload Foto Profil</h2>

        <!-- FORM UPLOAD -->
        <form action="{{ route('foto.upload') }}" method="POST" enctype="multipart/form-data" id="form-upload">
            @csrf
            <!-- Wrapper untuk input file -->
            <div class="mb-4 relative">
                <!-- Input file di atas label tapi invisible -->
                <input type="file" name="foto" required accept="image/*"
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                       @change="fileName = $event.target.files[0]?.name || ''">

                       <!-- Nama file -->
                        <div class="mt-2 mb-2 text-xs text-gray-600">
                            <span x-text="fileName || 'Belum ada file yang dipilih'"></span>
                        </div>

                <!-- Label sebagai tombol -->
                <div class="inline-flex justify-center w-full border border-gray-300 items-center px-4 py-2 text-slate-800 rounded cursor-pointer hover:border-gray-800">
                    <i class="bi bi-upload mr-2"></i> Pilih File
                </div>

                <!-- Info tambahan -->
                <p class="mt-1 text-xs text-gray-500">
                    Ukuran ideal foto: persegi (square), minimal 500×500 px.<br>
                    Format: JPG, PNG, JPEG, atau WebP. Max Size: 10MB.
                </p>
            </div>
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
