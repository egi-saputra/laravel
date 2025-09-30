<x-app-layout>
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
            <!-- List/Tabel Data Struktural -->
            <x-public.list-struktural :struktural="$struktural" :gurus="$gurus" />
        </main>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-layout>
