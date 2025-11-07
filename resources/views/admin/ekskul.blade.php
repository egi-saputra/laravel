<x-app-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? '') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">

        <aside class="hidden mx-0 mt-2 mb-4 md:block md:top-0 md:ml-6 md:mt-6 md:w-auto">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Footer -->
            <x-footer :profil="$profil" />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-0 !mb-16 space-y-2 overflow-x-auto md:space-y-6 md:mb-0 md:p-6">

            <!-- Form Tambah Ekskul -->
            <div class="p-4 bg-white rounded shadow">
                <h1 class="mb-4 text-lg font-bold">Tambahkan Ekstrakurikuler</h1>

                <form action="{{ route('admin.ekskul.store') }}" method="POST" class="mb-6 space-y-3">
                    @csrf
                    <!-- Input Nama Ekskul -->
                    <div>
                        <label class="block font-medium">Nama Ekskul</label>
                        <input type="text" name="nama_ekskul" class="w-full px-3 py-2 border rounded" required>
                    </div>

                    <!-- Pilih Pembina -->
                    <div>
                        <label class="block font-medium">Pembina Ekskul</label>
                        <select name="ekskul_id" class="w-full px-3 py-2 border rounded" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($guruUsers->sortBy('name') as $user)
                                <option value="{{ $user->guru->id ?? '' }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end md:justify-start">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            <i class="bi bi-check2-square"></i> Simpan
                        </button>
                    </div>
                </form>

                <hr class="my-6">

                    <!-- Tombol Upload & Export -->
                    <div class="flex flex-col items-end gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                        {{-- Import User --}}
                        <form action="{{ route('admin.ekskul.import') }}" method="POST" enctype="multipart/form-data"
                            class="flex flex-col w-full gap-2 sm:flex-row sm:w-auto sm:items-center">
                            @csrf
                            <input type="file" name="file" required accept=".xls,.xlsx,.csv"
                                class="w-full p-2 text-sm border rounded-lg focus:ring focus:ring-green-200 sm:w-auto">
                            <button type="submit"
                                    class="w-full px-4 py-2 font-semibold text-white bg-green-700 rounded shadow sm:w-auto hover:bg-green-800">
                                <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
                            </button>
                        </form>

                        {{-- Export Template --}}
                        <a href="{{ route('admin.ekskul.export') }}"
                        class="w-full px-4 py-2 font-semibold text-center text-white rounded shadow bg-slate-700 hover:bg-slate-800 sm:w-auto sm:ml-2">
                            <i class="bi bi-download me-1"></i> Download Template
                        </a>
                    </div>
            </div>

            <!-- Tabel Data Ekskul -->
            <div class="p-4 bg-white rounded shadow">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold">Daftar Ekstrakurikuler</h2>
                    {{-- Tombol Hapus Semua --}}
                    <button id="hapusSemua" type="button" class="flex items-center px-4 py-2 text-white bg-red-700 rounded hover:bg-red-800">
                        <i class="bi bi-trash me-1"></i>
                        <p>Hapus <span class="hidden sm:inline">Semua</span></p>
                    </button>
                    <form id="formHapusSemua" action="{{ route('admin.ekskul.destroyAll') }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>

                <!-- Search Box -->
                <div class="relative mb-4">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 border-r text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                        </svg>
                    </span>
                    <input type="text" id="searchInput" placeholder="Cari nama ekskul atau pembina..."
                        class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300">
                </div>

                {{-- Tabel Daftar Ekskul --}}
                <div class="overflow-x-auto md:overflow-x-visible">
                    <table class="w-full border border-collapse" id="ekskulTable">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-center border w-16whitespace-nowrap">No</th>
                                <th class="px-4 py-2 border whitespace-nowrap">Nama Ekskul</th>
                                <th class="px-4 py-2 border whitespace-nowrap">Nama Pembina</th>
                                <th class="px-4 py-2 text-center border"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ekskul ?? [] as $e)
                            <tr>
                                <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 border whitespace-nowrap">{{ $e->nama_ekskul }}</td>
                                <td class="px-4 py-2 border whitespace-nowrap">{{ $e->pembina->user->name ?? '-' }}</td>
                                <td class="px-4 py-2 text-center border">
                                    <div x-data="{ open: false, showModal: false }" class="relative inline-block">
                                        <button @click="open = !open" class="px-2 py-1 rounded hover:bg-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                                            </svg>
                                        </button>

                                        <div x-show="open" @click.away="open = false" x-transition
                                            class="absolute top-0 z-20 mr-2 bg-white border rounded shadow-md right-full w-28">
                                            <button type="button" @click="showModal = true; open = false"
                                                    class="block w-full px-4 py-2 text-left hover:bg-gray-100">Edit</button>
                                            <form action="{{ route('admin.ekskul.destroy', $e->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full px-4 py-2 text-left text-red-600 hover:bg-red-100">Delete</button>
                                            </form>
                                        </div>

                                        <!-- Modal Edit -->
                                        <div x-show="showModal" x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                            <div class="w-full max-w-md p-6 mx-4 bg-white rounded shadow-lg md:mx-0">
                                                <h2 class="mb-4 text-lg font-bold">Edit Ekskul</h2>
                                                <form action="{{ route('admin.ekskul.update', $e->id) }}" method="POST" class="space-y-3">
                                                    @csrf
                                                    @method('PUT')

                                                    <!-- Pilih Pembina -->
                                                    <div>
                                                        <label class="block font-medium text-left">Pembina Ekskul</label>
                                                        <select name="ekskul_id" class="w-full px-3 py-2 border rounded" required>
                                                            <option value="">-- Pilih Guru --</option>
                                                            @foreach($guruUsers->sortBy('name') as $user)
                                                                <option value="{{ $user->guru->id ?? '' }}"
                                                                    {{ $e->ekskul_id == ($user->guru->id ?? '') ? 'selected' : '' }}>
                                                                    {{ $user->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Nama Ekskul -->
                                                    <div>
                                                        <label class="block font-medium text-left">Ekstrakurikuler</label>
                                                        <input type="text" name="nama_ekskul" value="{{ $e->nama_ekskul }}" class="w-full px-3 py-2 border rounded" required>
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
                                <td colspan="3" class="py-2 text-center">Belum ada data ekskul</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Script Search -->
    <script>
        document.getElementById('hapusSemua').addEventListener('click', function() {
            Swal.fire({
                title: 'Hapus semua ekskul ?',
                text: "Semua data ekskul akan dihapus!",
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
            let rows = document.querySelectorAll("#ekskulTable tbody tr");

            rows.forEach(row => {
                let namaEkskul = row.cells[0]?.textContent.toLowerCase();
                let pembina = row.cells[1]?.textContent.toLowerCase();

                if (namaEkskul.includes(filter) || pembina.includes(filter)) {
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
</x-app-admin-layout>
