<x-app-backtop-layout>
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
            <!-- Form Tambah Kelas + Upload Excel -->
            <div class="p-4 bg-white rounded shadow">
                <h1 class="mb-4 text-lg font-bold">Tambah Kelas</h1>

                <!-- Form Input Manual -->
                <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <!-- Kode Kelas -->
                    <div>
                        <label class="block font-medium">Kode Kelas</label>
                        <input type="text" name="kode" class="w-full px-3 py-2 border rounded" required>
                    </div>

                    <!-- Nama Kelas -->
                    <div>
                        <label class="block font-medium">Nama Kelas</label>
                        <input type="text" name="kelas" class="w-full px-3 py-2 border rounded" required>
                    </div>

                    <!-- Wali Kelas -->
                    <div>
                        <label class="block font-medium">Wali Kelas</label>
                        <select name="walas_id" class="w-full px-3 py-2 border rounded" required>
                            <option value="">-- Pilih Wali Kelas --</option>
                            @foreach($guru as $g)
                                <option value="{{ $g->id }}">
                                    {{ optional($g->user)->name ?? $g->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                            class="px-4 py-2 mt-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </form>

                <hr class="my-6">

                <!-- Tombol Upload & Export -->
                    <div class="flex flex-col items-end gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                        {{-- Import User --}}
                        <form action="{{ route('admin.kelas.import') }}" method="POST" enctype="multipart/form-data"
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
                        <a href="{{ route('admin.kelas.template') }}"
                        class="w-full px-4 py-2 font-semibold text-center text-white rounded shadow bg-slate-700 hover:bg-slate-800 sm:w-auto sm:ml-2">
                            <i class="bi bi-download me-1"></i> Download Template
                        </a>
                    </div>
            </div>

            {{-- Tabel Daftar Kelas --}}
            <div class="p-4 bg-white rounded shadow">
                @if(auth()->user()->role === 'admin')
                    <x-admin.data-kelas :kelas="$kelas" :guru="$guru" />
                    @else
                    <x-public.list-kelas :kelas="$kelas"/>
                @endif
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-layout>
