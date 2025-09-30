<section id="delete-account" class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
        </p>
    </header>

    <form id="deleteForm" method="post" action="{{ route('profile.destroy') }}">
        @csrf
        @method('delete')

        <div class="mt-6">
            <x-input-label for="password" value="{{ __('Password') }}" />
            <x-text-input id="password" name="password" type="password" class="block w-full mt-1" />
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
        </div>

        <x-danger-button type="button" id="btnDelete" class="mt-4">
            {{ __('Delete Account') }}
        </x-danger-button>
    </form>
</section>

@push('scripts')
<script>
    document.getElementById('btnDelete').addEventListener('click', function () {
        Swal.fire({
            title: 'Yakin hapus akun?',
            text: "Tindakan ini tidak bisa dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    });
</script>
@endpush
