<x-app-layout>
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
        <main class="flex-1 p-0 mb-16 space-y-2 overflow-x-auto md:space-y-6 md:mb-0 md:p-6">
            <!-- Tabel Daftar Mapel -->
            <div class="overflow-x-auto md:overflow-x-visible">
                <x-public.list-jadwal-mapel
                    :paginatedJadwal="$paginatedJadwal"
                    :sekolah="$sekolah" :hari="$hari"
                    :logoBase64="$logoBase64"
                    :logoMime="$logoMime"
                />
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
</x-app-layout>
