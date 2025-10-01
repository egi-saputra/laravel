<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? '') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="z-10 mx-4 mt-4 top-16 md:top-0 md:ml-6 md:mt-6 md:h-screen md:mx-0 md:w-auto">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
                <!-- Form Tambah Guru + Upload Excel Versi 1 -->
                <div class="p-4 bg-white rounded shadow">
                    <h1 class="mb-4 text-lg font-bold">Tambah Guru</h1>

                    <!-- Form Input Manual -->
                    <form action="{{ route('admin.guru.store') }}" method="POST">
                        @csrf
                        <div>
                            <label class="block font-medium">Kode Guru</label>
                            <input type="text" name="kode" class="w-full px-3 py-2 mb-4 border rounded" required>
                        </div>
                        <div>
                            <label class="block font-medium">Nama Lengkap Guru + Gelar (opsional)</label>
                            <input type="text" name="nama" class="w-full px-3 py-2 mb-4 border rounded" required>
                            @error('name')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block font-medium">Email Guru</label>
                            <input type="email" name="email" class="w-full px-3 py-2 border rounded" required>
                            @error('email')
                                <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- hidden: password default & role -->
                        <input type="hidden" name="password" value="{{ env('DEFAULT_GURU_PASSWORD', 'password') }}">
                        <input type="hidden" name="role" value="guru">
                        <p class="pl-1 mt-4 mb-2 text-xs text-red-600">Keterangan :</p>
                        <p class="pl-1 mb-6 text-xs text-gray-500">Password akan terisi otomatis secara default dengan <span class="italic font-semibold text-slate-700"> "password"</span></p>

                        <button type="submit" class="px-4 py-2 mt-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </form>

                    <!-- Tombol Upload & Export -->
                    {{-- <div class="flex flex-wrap items-center justify-end gap-3">
                        <!-- Form Import -->
                        <form action="{{ route('admin.guru.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                            @csrf
                            <input type="file" name="file" required accept=".xls,.xlsx,.csv"
                            class="px-3 py-2 text-sm border rounded cursor-pointer focus:outline-none focus:ring focus:border-blue-300">
                            <button type="submit"
                                    class="px-4 py-2 text-white bg-green-700 rounded hover:bg-green-800">
                                <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
                            </button>
                        </form>

                        <!-- Download Template -->
                        <a href="{{ route('admin.guru.template') }}"
                        class="px-4 py-2 text-white rounded bg-slate-700 hover:bg-slate-800">
                            <i class="bi bi-download me-1"></i> Download Template
                        </a>
                    </div> --}}

                    <!-- Tombol Upload & Export -->
                    <div class="flex flex-col items-end gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                        {{-- Import User --}}
                        <form action="{{ route('admin.guru.import') }}" method="POST" enctype="multipart/form-data"
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
                        <a href="{{ route('admin.guru.template') }}"
                        class="w-full px-4 py-2 font-semibold text-center text-white rounded shadow bg-slate-700 hover:bg-slate-800 sm:w-auto sm:ml-2">
                            <i class="bi bi-download me-1"></i> Download Template
                        </a>
                    </div>
                </div>

            <!-- Tabel Data Guru -->
            <div class="p-4 bg-white rounded shadow">
                {{-- <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold">Daftar Guru</h2>

                    <!-- Tombol Hapus Semua -->
                    <div>
                        <button id="hapusSemua" type="button"
                            class="px-4 py-2 text-white bg-red-700 rounded hover:bg-red-800">
                            <i class="bi bi-trash me-1"></i> Hapus Semua
                        </button>
                        <form id="formHapusSemua" action="{{ route('admin.guru.destroyAll') }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div> --}}
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold">Daftar Guru</h2>

                    <div class="flex gap-2">
                        <button id="hapusSemua" type="button" class="flex items-center px-4 py-2 text-white bg-red-700 rounded hover:bg-red-800">
                            <i class="bi bi-trash me-1"></i>
                            <p>Hapus <span class="hidden sm:inline">Semua</span></p>
                        </button>
                    </div>

                    <form id="formHapusSemua" action="{{ route('admin.guru.destroyAll') }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>

                <!-- Search Box -->
                <div class="relative mb-4">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pr-3 border-r text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                        </svg>
                    </span>
                    <input type="text" id="searchInput"
                        placeholder="Cari kode guru atau nama guru..."
                        class="w-full py-2 pl-12 border rounded focus:outline-none focus:ring focus:border-blue-300">
                </div>

                <!-- Table -->
                <div class="overflow-x-auto md:overflow-x-visible">
                    <table class="w-full border border-collapse" id="guruTable">
                        <thead>
                            <tr class="bg-gray-100">
                                {{-- <th class="w-12 px-4 py-2 text-center border whitespace-nowrap">No</th> --}}
                                <th class="px-4 py-2 text-center border whitespace-nowrap">Kode Guru</th>
                                <th class="px-4 py-2 border whitespace-nowrap">Nama Lengkap</th>
                                <th class="px-4 py-2 border whitespace-nowrap">Email Guru</th>
                                <th class="w-24 px-4 py-2 text-center border whitespace-nowrap"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($guru ?? [] as $g)
                            <tr class="hover:bg-gray-50">
                                {{-- <td class="px-4 py-2 text-center border whitespace-nowrap">{{ $loop->iteration }}</td> --}}
                                <td class="px-4 py-2 text-center border">{{ $g->guru->kode ?? '-' }}
                                </td>
                                <td class="px-4 py-2 border whitespace-nowrap">{{ $g->name }}</td>
                                <td class="px-4 py-2 border whitespace-nowrap">{{ $g->email }}</td>
                                <td class="px-4 py-2 text-center border whitespace-nowrap">
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
                                            <form action="{{ route('admin.guru.destroy', $g->guru->id ?? 0) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full px-4 py-2 text-left text-red-600 hover:bg-red-100"
                                                    @if(!isset($g->guru)) disabled @endif>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Modal Edit -->
                                        <div x-show="showModal" x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                            <div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
                                                <h2 class="mb-4 text-lg font-bold">Edit Guru</h2>
                                                <form action="{{ route('admin.guru.updateUser', $g->id) }}" method="POST" class="space-y-3">
                                                    @csrf
                                                    @method('PUT')
                                                    <div>
                                                        <label class="block font-medium text-left">Kode Guru</label>
                                                        <input type="text" name="kode" value="{{ $g->guru->kode ?? '' }}"
                                                            class="w-full px-3 py-2 border rounded">
                                                    </div>
                                                    <div>
                                                        <label class="block font-medium text-left">Nama Lengkap Guru</label>
                                                        <input type="text" name="name" value="{{ $g->name }}"
                                                            class="w-full px-3 py-2 border rounded" required>
                                                    </div>
                                                    <div>
                                                        <label class="block font-medium text-left">Email</label>
                                                        <input type="email" name="email" value="{{ $g->email }}"
                                                            class="w-full px-3 py-2 border rounded" required>
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
                                <td colspan="3" class="py-2 text-center">Belum ada data guru</td>
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
                title: 'Yakin gak nyesel ?',
                text: "Semua data guru akan dihapus loh!",
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
                let rows = document.querySelectorAll("#guruTable tbody tr");

                rows.forEach(row => {
                    let kode = row.cells[1]?.textContent.toLowerCase(); // kolom Kode Guru
                    let nama = row.cells[2]?.textContent.toLowerCase(); // kolom Nama Guru

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

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-backtop-layout>
