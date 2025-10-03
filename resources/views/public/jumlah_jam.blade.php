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
            <div class="px-8 py-4 overflow-x-auto bg-white rounded shadow">
                <!-- Tabel Jadwal Piket -->
                <x-public.list-guru-piket :jadwalPiket="$jadwalPiket" :guru="$guru"/>
            </div>
            <div class="px-8 py-4 overflow-x-auto bg-white rounded shadow">
                <!-- Tabel Data Guru dan Jumlah Jam -->
                <x-public.list-guru :guru="$guru" :guruJam="$guruJam" />
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-backtop-layout>
