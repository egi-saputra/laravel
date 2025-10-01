<div class="p-4 bg-white rounded shadow">
    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <!-- Judul -->
        <h2 class="w-full mb-2 text-lg font-bold sm:w-auto sm:mb-0">Jadwal Tugas Piket Guru</h2>

        <!-- Tombol Hapus -->
        <div class="flex justify-end sm:justify-start">
            <button id="hapusSemua" type="button"
                    class="px-4 py-2 text-white bg-red-700 rounded hover:bg-red-800">
                <i class="bi bi-trash me-1"></i> Hapus Semua
            </button>
        </div>

        <!-- Form Hapus -->
        <form id="formHapusSemua" action="{{ route('admin.jadwal.destroyAll') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <div class="overflow-x-auto md:overflow-x-visible">
        <table class="w-full border border-collapse" id="jadwalTable">
            <thead>
                <tr class="bg-gray-100">
                    <th class="w-16 px-4 py-2 text-center border whitespace-nowrap">No</th>
                    <th class="px-4 py-2 text-center border whitespace-nowrap">Hari</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Petugas Piket</th>
                    <th class="px-4 py-2 text-center border"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwalPiket ?? [] as $j)
                <tr>
                    <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $j->hari }}</td>
                    <td class="px-4 py-2 border whitespace-nowrap">{{ $j->guru->user->name ?? '-' }}</td>
                    <td class="px-4 py-2 text-center border ">
                        <div x-data="{ open: false, showModal: false }" class="relative inline-block">
                            <!-- Tombol ⋮ -->
                            <button @click="open = !open" class="px-2 py-1 rounded hover:bg-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                                </svg>
                            </button>

                            <!-- Dropdown ke kiri -->
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute top-0 z-20 mr-2 bg-white border rounded shadow-md right-full w-28">
                                <button type="button" @click="showModal = true; open = false"
                                        class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                                    Edit
                                </button>
                                <form action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2 text-left text-red-600 hover:bg-red-100">
                                        Delete
                                    </button>
                                </form>
                            </div>

                            <!-- Modal Edit -->
                            <div x-show="showModal" x-cloak
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                <div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
                                    <h2 class="mb-4 text-lg font-bold">Edit Jadwal Piket</h2>
                                    <form action="{{ route('admin.jadwal.update', $j->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="block font-medium">Hari</label>
                                            <select name="hari" class="w-full px-3 py-2 border rounded" required>
                                                @foreach($hariList as $hari)
                                                    <option value="{{ $hari }}" {{ $j->hari == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block font-medium">Petugas</label>
                                            <select name="petugas" class="w-full px-3 py-2 border rounded" required>
                                                @foreach($guru as $g)
                                                    <option value="{{ $g->id }}" {{ $j->petugas == $g->id ? 'selected' : '' }}>
                                                        {{ $g->user->name ?? '-' }}
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
                    <td colspan="4" class="py-2 text-center">Belum ada jadwal piket</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <!-- Script Hapus Semua -->
    <script>
        document.getElementById('hapusSemua').addEventListener('click', function() {
            Swal.fire({
                title: 'Yakin gak nyesel ?',
                text: "Semua data jadwal piket akan dihapus!",
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
    </script>
