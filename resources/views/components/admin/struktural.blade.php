<!-- Tabel Data Struktural -->
<div class="p-4 bg-white rounded shadow">
    <div class="flex items-center justify-between mb-4">
        <h2 class="mb-4 text-lg font-bold">Daftar Struktural</h2>
    </div>

    <div class="overflow-x-auto md:overflow-x-visible">
        <table class="w-full border border-collapse" id="strukturalTable">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-center border whitespace-nowrap">No</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Jabatan</th>
                    <th class="px-4 py-2 border whitespace-nowrap">Nama Lengkap</th>
                    <th class="px-4 py-2 text-center border"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($struktural ?? [] as $s)
                    <tr>
                        <td class="px-4 py-2 text-center border">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $s->jabatan }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $s->guru->user->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-center border">
                            <div x-data="{ open: false, showModal: false }" class="relative inline-block">
                                <!-- Tombol ⋮ -->
                                <button @click="open = !open"
                                        class="px-2 py-1 rounded hover:bg-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-6 h-6 text-gray-700"
                                        fill="currentColor" viewBox="0 0 20 20">
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
                                    <form action="{{ route('admin.struktural.destroy', $s->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full px-4 py-2 text-left text-red-600 hover:bg-red-100">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                                <!-- Modal Edit -->
                                <div x-show="showModal" x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                    <div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
                                        <h2 class="mb-4 text-lg font-bold">Edit Struktural</h2>
                                        <form action="{{ route('admin.struktural.update', $s->id) }}" method="POST" class="space-y-3">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label class="block font-medium">Jabatan</label>
                                                <input type="text" name="jabatan" value="{{ $s->jabatan }}" class="w-full px-3 py-2 border rounded" required>
                                            </div>
                                            <div>
                                                <label class="block font-medium">Nama GTK</label>
                                                <select name="nama_gtk" id="nama_gtk" class="w-full px-3 py-2 border rounded" required>
                                                    <option value="">-- Pilih Guru --</option>
                                                    @foreach($gurus as $guru)
                                                        <option value="{{ $guru->id }}" {{ $s->nama_gtk == $guru->id ? 'selected' : '' }}>
                                                            {{ $guru->user->name ?? '-' }}
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
                        <td colspan="4" class="py-2 text-center">Belum ada data struktural</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

