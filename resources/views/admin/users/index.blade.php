<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? 'Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <aside class="sticky z-10 w-full top-16 md:static md:w-auto md:ml-6 md:mt-6 md:h-screen md:top-0">
            <x-sidebar />
        </aside>

        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">
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
                                <th class="px-4 py-2 border">Nama</th>
                                <th class="px-4 py-2 border">Email</th>
                                <th class="px-4 py-2 border">Role</th>
                                <th class="px-4 py-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allUsers as $user)
                                <tr>
                                    <td class="px-4 py-2 border whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-4 py-2 border whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="px-4 py-2 text-center capitalize border whitespace-nowrap">{{ $user->role }}</td>
                                    <td class="px-4 py-2 border">
                                        <div class="flex justify-center gap-2">
                                            <button
                                                onclick="openModal({{ $user->id }}, '{{ $user->role }}', '{{ route('admin.users.update', $user->id) }}')"
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
            <div class="mt-4">
                {{ $allUsers->links('pagination::tailwind') }}
            </div>
        </main>
    </div>

    <x-admin.user-edit-modal />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kembalikan posisi scroll window
            const scrollPos = localStorage.getItem('scrollY');
            if (scrollPos) {
                window.scrollTo(0, parseInt(scrollPos));
            }

            // Simpan posisi scroll window setiap ada scroll
            window.addEventListener('scroll', function() {
                localStorage.setItem('scrollY', window.scrollY);
            });

            // Optional: hapus posisi scroll saat pindah halaman tertentu
            window.addEventListener('beforeunload', function() {
                // localStorage.removeItem('scrollY'); // opsional, kalau mau reset
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // DELETE USER
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hancurkan user ini?',
                        text: "User akan dihapus secara permanen!",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hancurkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // MODAL EDIT USER
            window.openModal = function(userId, role, actionUrl) {
                const modal = document.getElementById('userEditModal');
                modal.querySelector('form').action = actionUrl;
                modal.querySelector('[name="role"]').value = role;

                const row = document.querySelector(`form[action$='${userId}']`).closest('tr');
                modal.querySelector('[name="name"]').value = row.children[0].innerText;
                modal.querySelector('[name="email"]').value = row.children[1].innerText;

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
            document.getElementById('userEditModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('flex');
                    this.classList.add('hidden');
                }
            });
        });
    </script>

    <!-- Footer -->
    <x-footer :profil="$profil" />

</x-app-backtop-layout>
