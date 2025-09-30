<div id="userEditModal"
     class="fixed inset-0 z-[9999] hidden bg-black bg-opacity-50 items-center justify-center">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-xl">
        <h3 class="mb-4 text-lg font-semibold">Edit User</h3>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <!-- Name -->
            <label class="block mb-2">Nama</label>
            <input type="text" name="name"
                   class="w-full p-2 mb-4 border rounded" readonly>

            <!-- Email -->
            <label class="block mb-2">Email</label>
            <input type="email" name="email"
                   class="w-full p-2 mb-4 border rounded">

            <!-- Role -->
            <label class="block mb-2">Role</label>
            <select name="role" class="w-full p-2 mb-4 border rounded">
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="guru">Guru</option>
                <option value="siswa">Siswa</option>
                <option value="user">User</option>
            </select>

            <!-- Password -->
            <label class="block mb-2">Password Baru (opsional)</label>
            <input type="password" name="new_password"
                   class="w-full p-2 border rounded">
            <p class="pl-1 mt-1 mb-4 text-xs text-gray-500">Must be at least 6 characters in length</p>

            <!-- Tombol -->
            <div class="flex justify-end space-x-2">
                <button type="button"
                        class="px-4 py-2 bg-gray-300 rounded modal-close">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 text-white bg-blue-600 rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
