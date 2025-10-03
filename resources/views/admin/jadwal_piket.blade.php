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
            <!-- Form Tambah Jadwal Piket -->
            <div class="p-4 bg-white rounded shadow">
                <h2 class="mb-4 text-lg font-bold">Tambahkan Jadwal Piket</h2>

                <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- Kolom Hari -->
                        <div>
                            <label class="block mb-1 font-medium">Hari</label>
                            <select name="hari" class="w-full px-3 py-2 border rounded" required>
                                <option value="">-- Pilih Hari --</option>
                                @foreach($hariList as $hari)
                                    <option value="{{ $hari }}">{{ $hari }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kolom Guru -->
                        <div>
                            <label class="block mb-1 font-medium">Guru</label>
                            <select name="petugas" class="w-full px-3 py-2 border rounded" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach($guru->sortBy(fn($g) => $g->user->name ?? '') as $g)
                                    <option value="{{ $g->id }}">{{ $g->user->name ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="flex justify-end mt-3 md:justify-start">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            <i class="bi bi-check2-square"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Jadwal Piket -->
            <div class="p-4 bg-white rounded shadow">
                @if(auth()->user()->role === 'admin')
                    <x-admin.jadwal-piket :jadwalPiket="$jadwalPiket" :guru="$guru" :hariList="$hariList"/>
                @else
                    <x-public.list-guru-piket :jadwalPiket="$jadwalPiket"/>
                @endif
            </div>
        </main>
    </div>

     <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-backtop-layout>
