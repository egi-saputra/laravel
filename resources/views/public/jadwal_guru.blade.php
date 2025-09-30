<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? '') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <aside class="sticky z-10 w-full top-16 md:static md:w-auto md:ml-6 md:mt-6 md:h-screen md:top-0">
            <!-- Sidebar -->
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
            <!-- Tabel Daftar Mapel -->
            <x-public.list-jadwal-guru :paginatedJadwal="$paginatedJadwal" :sekolah="$sekolah" :pageTitle="$pageTitle" :logoBase64="$logoBase64" :logoMime="$logoMime" />

            <div class="mt-4">
                {{ $paginatedJadwal->links('pagination::tailwind') }}
                {{-- {{ $jadwal->links('pagination::simple-tailwind') }} --}}
            </div>
        </main>
    </div>

    <!-- Script Search -->
    <script>
        document.getElementById('searchMapel').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#mapelTable tbody tr");

            rows.forEach(row => {
                let kode = row.cells[0]?.textContent.toLowerCase();
                let mapel = row.cells[1]?.textContent.toLowerCase();
                let guru = row.cells[2]?.textContent.toLowerCase();

                if (kode.includes(filter) || mapel.includes(filter) || guru.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-backtop-layout>
