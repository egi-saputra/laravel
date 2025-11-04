<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Manajemen Pengguna') }}
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
        <main class="flex-1 p-0 !mb-16 space-y-2 overflow-x-auto md:space-y-6 md:mb-0 md:p-6">
            <x-admin.user-form
                :action="route('admin.users.store')"
                title="Tambah User Baru"
                button="Simpan Data"
            />

            <div class="p-4 bg-white rounded shadow">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold">Daftar Pengguna Semua Role</h2>
                </div>

                <!-- Filter Search & Role -->
                {{-- <form method="GET" class="flex flex-wrap w-full gap-2 mb-4">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama/email..."
                        class="flex-1 min-w-[200px] px-3 py-2 border rounded" />

                    <select name="role" class="w-48 px-3 py-2 border rounded">
                        <option value="">Semua Role</option>
                        @foreach(['admin','guru','staff','siswa','user'] as $role)
                            <option value="{{ $role }}" {{ request('role')==$role?'selected':'' }}>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                    class="px-4 py-2 text-white rounded bg-slate-700 hover:bg-slate-800">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </form> --}}

                <form method="GET" class="flex flex-col w-full gap-2 mb-4">
                    <!-- Input + Select -->
                    <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama/email..."
                            class="w-full sm:flex-1 min-w-[200px] px-3 py-2 border rounded" />

                        <select name="role" class="w-full px-3 py-2 border rounded sm:w-48">
                            <option value="">Semua Role</option>
                            @foreach(['admin','guru','staff','siswa','user'] as $role)
                                <option value="{{ $role }}" {{ request('role')==$role?'selected':'' }}>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buttons (hanya mobile sejajar horizontal) -->
                    <div class="flex flex-row justify-between gap-2 sm:flex-row sm:justify-start sm:gap-2 sm:w-auto">
                        <button type="submit" class="w-1/2 px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 sm:w-auto">
                            <i class="bi bi-funnel"></i> Filter
                        </button>

                        <a href="{{ route('admin.users.index') }}"
                        class="w-1/2 px-4 py-2 text-center text-white rounded bg-slate-700 hover:bg-slate-800 sm:w-auto">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </form>

                <!-- Tabel User -->
                <div id="userTableWrapper" class="overflow-x-auto md:overflow-x-visible">
                    <table class="w-full border border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="w-12 px-4 py-2 text-center border">No</th>
                                <th class="px-4 py-2 text-left border md:text-center ">Nama User</th>
                                <th class="px-4 py-2 text-left border md:text-center">Email Addres</th>
                                <th class="px-4 py-2 text-left border md:text-center">Role</th>
                                <th class="px-4 py-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allUsers as $user)
                                <tr>
                                    <td class="px-4 py-2 text-center border">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 border whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-4 py-2 border whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="px-4 py-2 text-left capitalize border md:text-center whitespace-nowrap">{{ $user->role }}</td>
                                    <td class="px-4 py-2 border">
                                        <div class="flex justify-center gap-2">
                                            <button
                                                onclick="openModal(
                                                    {{ $user->id }},
                                                    '{{ $user->name }}',
                                                    '{{ $user->email }}',
                                                    '{{ $user->role }}',
                                                    '{{ route('admin.users.update', $user->id) }}'
                                                )"
                                                data-turbo="false"
                                                class="px-3 py-1 text-white bg-blue-600 rounded hover:bg-blue-700">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 text-white bg-red-600 rounded hover:bg-red-700">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">Tidak ada user</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4 !z-10">
                {{ $allUsers->links('pagination::tailwind') }}
            </div>
        </main>
    </div>

    <x-admin.user-edit-modal />

    <script>
        document.addEventListener('turbo:load', function() {
            // Kembalikan posisi scroll window
            const scrollPos = localStorage.getItem('scrollY');
            if (scrollPos) {
                window.scrollTo(0, parseInt(scrollPos));
            }

            window.addEventListener('scroll', function() {
                localStorage.setItem('scrollY', window.scrollY);
            });

            // DELETE USER
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus user ini ?',
                        text: "User akan dihapus secara permanen!",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // MODAL EDIT USER
            window.openModal = function (id, name, email, role, actionUrl) {
                const modal = document.getElementById('userEditModal');
                const form = modal.querySelector('#editForm');

                // Set action ke route update user
                form.action = actionUrl;

                // Isi nilai input sesuai user yang diklik
                form.querySelector('[name="name"]').value = name;
                form.querySelector('[name="email"]').value = email;
                form.querySelector('[name="role"]').value = role;

                // Kosongkan password (jangan pernah prefill)
                form.querySelector('[name="new_password"]').value = '';

                // Tampilkan modal
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            // CLOSE MODAL
            document.querySelectorAll('.modal-close').forEach(btn => {
                btn.addEventListener('click', function() {
                    const modal = document.getElementById('userEditModal');
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                });
            });

            // Tutup modal kalau klik area luar
            const modal = document.getElementById('userEditModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.remove('flex');
                        this.classList.add('hidden');
                    }
                });
            }
        });
    </script>

</x-app-layout>
