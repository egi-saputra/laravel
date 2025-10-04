{{-- Cara penggunaan : <x-data-kelas :kelas="$kelas" /> --}}

<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold md:font-bold">Informasi Daftar Kelas</h2>
        {{-- Tombol Hapus Semua --}}
        <button id="hapusSemua" type="button" class="flex items-center px-4 py-2 text-white bg-red-700 rounded hover:bg-red-800">
            <i class="bi bi-trash me-1"></i>
            <p>Hapus <span class="hidden sm:inline">Semua</span></p>
        </button>
        <form id="formHapusSemua" action="{{ route('admin.kelas.destroyAll') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <!-- Search Box -->
    <div class="relative mb-4">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 border-r text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                      d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
            </svg>
        </span>
        <input type="text" id="searchInput"
               placeholder="Cari kode kelas atau nama kelas..."
               class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300">
    </div>

    <div class="overflow-x-auto md:overflow-x-visible">
        <table class="w-full border border-collapse" id="kelasTable">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-center border whitespace-nowrap">Kode Kelas</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Unit Kelas</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Wali Kelas</th>
                    <th class="px-4 py-2 text-center border whitespace-nowrap">Jumlah Siswa</th>
                    <th class="px-4 py-2 text-center border"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kelas ?? [] as $k)
                    <tr>
                        <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $k->kode }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $k->kelas }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            {{ optional($k->waliKelas->user)->name ?? 'Tidak Ada' }}
                        </td>
                        <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $k->jumlah_siswa ?? 0 }}</td>
                        <td class="px-4 py-2 text-center border">
                            <div x-data="{ open: false, showModal: false }" class="relative inline-block">
                                <!-- Tombol ⋮ -->
                                <button @click="open = !open" class="px-2 py-1 rounded hover:bg-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute top-0 z-20 mr-2 bg-white border rounded shadow-md right-full w-28">
                                    <button type="button"
                                            @click="showModal = true; open = false"
                                            class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full px-4 py-2 text-left text-red-600 hover:bg-red-100">
                                            Delete
                                        </button>
                                    </form>
                                </div>

                                {{-- Modal Edit --}}
                                <div x-show="showModal" x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                    <div class="w-full max-w-md p-6 mx-4 bg-white rounded shadow-lg md:mx-0">
                                        <h2 class="mb-4 text-lg font-bold">Edit Kelas</h2>
                                        <form action="{{ route('admin.kelas.update', $k->id) }}" method="POST" class="space-y-3">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label class="block font-medium text-left">Kode Kelas</label>
                                                <input type="text" name="kode" value="{{ $k->kode }}"
                                                    class="w-full px-3 py-2 border rounded" required>
                                            </div>

                                            <div>
                                                <label class="block font-medium text-left">Nama Kelas</label>
                                                <input type="text" name="kelas" value="{{ $k->kelas }}"
                                                    class="w-full px-3 py-2 border rounded" required>
                                            </div>

                                            <div>
                                                <label class="block font-medium text-left">Wali Kelas</label>
                                                <select name="walas_id" class="w-full px-3 py-2 border rounded">
                                                    <option value="">-- Pilih Wali Kelas --</option>
                                                    @foreach($guru->sortBy(fn($g) => optional($g->user)->name ?? $g->nama) as $g)
                                                        <option value="{{ $g->id }}" {{ $k->walas_id == $g->id ? 'selected' : '' }}>
                                                            {{ optional($g->user)->name ?? $g->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="showModal = false"
                                                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                                                    Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-2 text-center">Belum ada data kelas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Script Search -->
<script>
    document.getElementById('hapusSemua').addEventListener('click', function() {
        Swal.fire({
            title: 'Hapus semua kelas ?',
            text: "Semua data kelas akan dihapus!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
                if (result.isConfirmed) {
                document.getElementById('formHapusSemua').submit();
            }
        });
    });

    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#kelasTable tbody tr");

        rows.forEach(row => {
            let kode = row.cells[0]?.textContent.toLowerCase();
            let nama = row.cells[1]?.textContent.toLowerCase();

            if (kode.includes(filter) || nama.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
            @if(session('alert'))
                Swal.fire({
                    icon: '{{ session('alert.type') }}', // success, error, warning
                    title: '{{ session('alert.title') ?? ucfirst(session('alert.type')) }}',
                    @if(session('alert.html'))
                        html: `{!! session('alert.message') !!}`,
                    @else
                        text: '{{ session('alert.message') }}',
                    @endif
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });
            @endif
        });
</script>
