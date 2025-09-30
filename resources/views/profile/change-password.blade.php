<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Ganti Password</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl p-4 mx-auto bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">
            @include('profile.partials.update-password-form')
        </div>
    </div>
</x-app-layout>
