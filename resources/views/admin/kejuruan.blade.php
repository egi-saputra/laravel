<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? '') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="z-0 mx-4 mt-4 md:z-10 top-16 md:top-0 md:ml-6 md:mt-6 md:h-screen md:mx-0 md:w-auto">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
            <!-- Form Tambah Kejuruan -->
            <div class="p-4 bg-white rounded shadow">
                <h1 class="mb-4 text-lg font-bold">Tambah Program Kejuruan</h1>

                <form action="{{ route('admin.kejuruan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <!-- Input Kode Kejuruan -->
                    <div>
                        <label class="block font-medium">Kode Program Kejuruan</label>
                        <input type="text" name="kode" class="w-full px-3 py-2 border rounded" required>
                    </div>

                    <!-- Input Nama Kejuruan -->
                    <div>
                        <label class="block font-medium">Nama Program Kejuruan</label>
                        <input type="text" name="nama_kejuruan" class="w-full px-3 py-2 border rounded" required>
                    </div>

                    <!-- Pilih Kaprog -->
                    <div>
                        <label class="block font-medium">Kepala Program</label>
                        <select name="kaprog_id" id="kaprog_id" class="w-full px-3 py-2 border rounded" required>
                            <option value="">-- Pilih Kaprog --</option>
                            @foreach($guru->sortBy(fn($g) => $g->user->name ?? $g->nama ?? '') as $g)
                                <option value="{{ $g->id }}">
                                    {{ $g->user->name ?? $g->nama ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="flex justify-end md:justify-start">
                        <button type="submit" class="px-4 py-2 mt-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            <i class="bi bi-check2-square"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Data Kejuruan -->
            <div class="p-4 bg-white rounded shadow">
                <h2 class="mb-4 text-lg font-bold">Daftar Program Kejuruan</h2>

                <div class="overflow-x-auto md:overflow-x-visible">
                    <table class="w-full border border-collapse" id="kejuruanTable">
                        <thead>
                            <tr class="bg-gray-100">
                                {{-- <th class="px-4 py-2 text-center border whitespace-nowrap">No</th> --}}
                                <th class="px-4 py-2 text-center border whitespace-nowrap">Kode</th>
                                <th class="px-4 py-2 text-left border md:text-center whitespace-nowrap">Program Kejuruan</th>
                                <th class="px-4 py-2 text-left border md:text-center whitespace-nowrap">Kepala Program</th>
                                <th class="px-4 py-2 text-center border whitespace-nowrap">Jumlah Siswa</th>
                                <th class="px-4 py-2 text-center border"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kejuruan ?? [] as $k)
                            <tr>
                                {{-- <td class="px-4 py-2 text-center border">{{ $loop->iteration }}</td> --}}
                                <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $k->kode }}</td>
                                <td class="px-4 py-2 border whitespace-nowrap">{{ $k->nama_kejuruan }}</td>
                                <td class="px-4 py-2 border whitespace-nowrap">
                                    {{ $k->kepalaProgram->user->name ?? $k->kepalaProgram->nama ?? 'Tidak Ada' }}
                                </td>
                                <td class="px-4 py-2 text-center border whitespace-nowrap">
                                    {{ $k->siswa_count ?? 0 }}
                                </td>
                                <td class="px-4 py-2 text-center border">
                                    <div x-data="{ open: false, showModal: false }" class="relative inline-block">
                                        <!-- Tombol ⋮ -->
                                        <button @click="open = !open"
                                            class="px-2 py-1 rounded hover:bg-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-6 h-6 text-gray-700"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
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
                                            <form action="{{ route('admin.kejuruan.destroy', $k->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full px-4 py-2 text-left text-red-600 hover:bg-red-100">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Modal Edit -->
                                        <div x-show="showModal" x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                            <div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
                                                <h2 class="mb-4 text-lg font-bold">Edit Kejuruan</h2>
                                                <form action="{{ route('admin.kejuruan.update', $k->id) }}" method="POST" class="space-y-3">
                                                    @csrf
                                                    @method('PUT')
                                                    <div>
                                                        <label class="block font-medium">Kode Kejuruan</label>
                                                        <input type="text" name="kode" value="{{ $k->kode }}" class="w-full px-3 py-2 border rounded" required>
                                                    </div>
                                                    <div>
                                                        <label class="block font-medium">Nama Program Kejuruan</label>
                                                        <input type="text" name="nama_kejuruan" value="{{ $k->nama_kejuruan }}" class="w-full px-3 py-2 border rounded" required>
                                                    </div>
                                                    <div>
                                                        <label class="block font-medium">Kepala Program</label>
                                                        <select name="kaprog_id" class="w-full px-3 py-2 border rounded">
                                                            <option value="">-- Pilih Kepala Program --</option>
                                                            @foreach($guru as $g)
                                                                <option value="{{ $g->id }}" {{ $k->kaprog_id == $g->id ? 'selected' : '' }}>
                                                                    {{ $g->user->name ?? $g->nama ?? '-' }}
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
                                                            <i class="bi bi-check2-square"></i> Simpan
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
                                <td colspan="5" class="py-2 text-center">Belum ada data kejuruan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Footer -->
            <x-footer :profil="$profil" />
        </main>
    </div>

</x-app-layout>
