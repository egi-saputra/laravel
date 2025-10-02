<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Hapus Akun
        </h2>
    </x-slot>

    <div class="px-4 py-12 sm:px-6 lg:px-8"> <!-- padding horizontal responsif -->
        <div class="max-w-xl p-4 mx-auto bg-white rounded-lg shadow sm:p-8 dark:bg-gray-800">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
