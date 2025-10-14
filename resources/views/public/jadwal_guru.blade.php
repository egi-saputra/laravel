<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? '') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="hidden mt-0 mb-4 md:block md:ml-6 md:mt-6 md:h-screen md:mb-0 md:w-auto">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <!-- Tabel Daftar Mapel -->
        <main class="hidden p-0 mb-16 space-y-6 overflow-x-auto md:block md:flex-1 md:mb-0 md:p-6">
            <!-- Tabel Daftar Mapel -->
            <x-public.list-jadwal-guru :paginatedJadwal="$paginatedJadwal" :sekolah="$sekolah" :pageTitle="$pageTitle" :logoBase64="$logoBase64" :logoMime="$logoMime" />
        </main>

        <!-- Card Daftar Mapel -->
        <main class="flex-1 p-0 mb-16 space-y-6 overflow-x-auto md:hidden md:mb-0 md:p-6">
            <!-- Card Daftar Mapel -->
            <x-public.card-jadwal-guru :paginatedJadwal="$paginatedJadwal" :sekolah="$sekolah" :pageTitle="$pageTitle" :logoBase64="$logoBase64" :logoMime="$logoMime" />
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
</x-app-layout>
