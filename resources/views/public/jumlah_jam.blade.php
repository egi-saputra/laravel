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
            {{-- <div class="px-8 py-4 overflow-x-auto bg-white rounded shadow">
                <!-- Tabel Jadwal Piket -->
                <x-public.list-guru-piket :jadwalPiket="$jadwalPiket" :guru="$guru"/>
            </div> --}}

            <div>
                <!-- Tabel Data Guru dan Jumlah Jam -->
                <x-public.list-jumlah-jam-guru :guru="$guru" :guruJam="$guruJam" />
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-layout>
