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
            {{-- Tabel Daftar Kelas --}}
            <div class="overflow-x-auto md:overflow-x-visible">
                @if(auth()->user()->role === 'admin')
                    <x-data-kelas :kelas="$kelas" :guru="$guru" />
                    @else
                    <x-public.list-kelas :kelas="$kelas"/>
                @endif
            </div>
        </main>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-layout>
