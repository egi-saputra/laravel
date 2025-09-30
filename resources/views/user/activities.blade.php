<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Dashboard User</h2>
    </x-slot>

    <div class="flex">

        <!-- Main Content -->
        <div class="p-4 flex-2">
            <!-- Sidebar -->
            <x-sidebar />
        </div>
            <div class="flex-1 p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-2">
                <h3 class="mb-4 text-xl font-semibold">Halaman Activities</h3>
            </div>
        </div>
    </div>
</x-app-layout>
