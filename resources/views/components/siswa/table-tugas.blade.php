            <!-- Riwayat Tugas -->
            <div class="p-4 bg-white rounded shadow">
                <h2 class="mb-4 text-lg font-bold">Riwayat Tugas Saya</h2>

                    <table class="w-full border border-collapse" id="tugasTable">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 border">Judul Tugas</th>
                                <th class="px-4 py-2 border">Mapel</th>
                                <th class="px-4 py-2 border">Kelas</th>
                                <th class="px-4 py-2 border">File Tugas</th>
                                <th class="px-4 py-2 text-center border"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tugas as $t)
                            <tr>
                                <td class="px-4 py-2 border">{{ $t->judul }}</td>
                                <td class="px-4 py-2 border">{{ $t->mapel->mapel ?? '-' }}</td>
                                <td class="px-4 py-2 text-center border">{{ $t->kelas->kelas ?? '-' }}</td>
                                <td class="px-4 py-2 text-center border">
                                    <a href="{{ Storage::url($t->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Download</a>
                                </td>
                                <td class="px-4 py-2 text-center border">
                                    <div x-data="{ open: false, showModal: false }" class="relative inline-block">
                                        <button @click="open = !open" class="px-2 py-1 rounded hover:bg-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                                            </svg>
                                        </button>

                                        <!-- Dropdown -->
                                        <div x-show="open" @click.away="open = false" x-transition
                                            class="absolute top-0 z-20 mr-2 bg-white border rounded shadow-md right-full w-36">
                                            <button type="button" @click="showModal = true; open = false"
                                                    class="block w-full px-4 py-2 text-left hover:bg-gray-100">Edit</button>
                                            <form action="{{ route('siswa.tugas.destroy', $t->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full px-4 py-2 text-left text-red-600 hover:bg-red-100">Delete</button>
                                            </form>
                                        </div>

                                        <!-- Modal Edit -->
                                        <div x-show="showModal" x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                            <div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
                                                <h2 class="mb-4 text-lg font-bold">Edit Tugas</h2>
                                                <form action="{{ route('siswa.tugas.update', $t->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                                    @csrf
                                                    @method('PUT')

                                                    <div>
                                                        <label class="block font-medium">Judul Tugas</label>
                                                        <input type="text" name="judul" value="{{ $t->judul }}" class="w-full px-3 py-2 border rounded" required>
                                                    </div>

                                                    <div>
                                                        <label class="block font-medium">Kelompok</label>
                                                        <input type="text" name="kelompok" value="{{ $t->kelompok }}" class="w-full px-3 py-2 border rounded">
                                                    </div>

                                                    <div>
                                                        <label class="block font-medium">Mata Pelajaran</label>
                                                        <select name="mapel_id" class="w-full px-3 py-2 border rounded" required>
                                                            @foreach($mapel as $m)
                                                                <option value="{{ $m->id }}" {{ $t->mapel_id == $m->id ? 'selected' : '' }}>
                                                                    {{ $m->mapel }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="block font-medium">Upload File (opsional)</label>
                                                        <input type="file" name="file_tugas" class="w-full px-3 py-2 border rounded">
                                                        <small class="text-gray-500">Kosongkan jika tidak ingin ganti file</small>
                                                    </div>

                                                    <div class="flex justify-end gap-2">
                                                        <button type="button" @click="showModal = false"
                                                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                                                        <button type="submit"
                                                                class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-2 text-center">Belum ada tugas yang dibuat</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
