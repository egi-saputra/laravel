<div class="p-6 mb-6 bg-white border rounded-lg shadow">
    <h3 class="mb-6 text-xl font-bold text-gray-700">{{ $title ?? 'Tambah Pengguna Baru' }}</h3>

    {{-- Form Tambah User Manual --}}
    <form action="{{ $action ?? route('dev.users.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block mb-1 font-medium text-gray-600">Nama Lengkap</label>
            <input type="text" name="name" id="name" required
                   class="w-full p-2 border rounded-lg focus:ring focus:ring-green-200"
                   placeholder="Masukkan nama lengkap">
        </div>

        <div>
            <label for="email" class="block mb-1 font-medium text-gray-600">Email</label>
            <input type="email" name="email" id="email" required
                   class="w-full p-2 border rounded-lg focus:ring focus:ring-green-200"
                   placeholder="Masukkan email">
        </div>

        <div>
            <label for="password" class="block mb-1 font-medium text-gray-600">Password</label>
            <input type="password" name="password" id="password" required
                   class="w-full p-2 border rounded-lg focus:ring focus:ring-green-200"
                   placeholder="Masukkan password">
        </div>

        <div>
            <label for="role" class="block mb-1 font-medium text-gray-600">Role Pengguna</label>
            <select name="role" id="role" required
                    class="w-full p-2 border rounded-lg focus:ring focus:ring-green-200">
                <option value="">-- Pilih Role --</option>
                <option value="developer">Developer</option>
                <option value="admin">Admin</option>
                <option value="guru">Guru</option>
                <option value="staff">Staff</option>
                <option value="siswa">Siswa</option>
                <option value="user">User</option>
            </select>
        </div>

        {{-- Tombol Simpan --}}
        <button type="submit"
                class="px-4 py-2 font-semibold text-white bg-blue-600 rounded shadow hover:bg-blue-700">
            <i class="bi bi-save"></i> {{ $button ?? 'Simpan Data' }}
        </button>
    </form>

    <hr class="my-6">

    {{-- Form Import & Tombol Template --}}
    <div class="flex flex-wrap items-center justify-end gap-3">
        {{-- Import User --}}
        <form action="{{ route('dev.users.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <input type="file" name="file" required
                   class="p-2 text-sm border rounded-lg focus:ring focus:ring-green-200">
            <button type="submit"
                    class="px-4 py-2 font-semibold text-white bg-green-700 rounded shadow hover:bg-green-800">
                <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
            </button>
        </form>

        {{-- Export Template --}}
        <a href="{{ route('dev.users.export') }}"
           class="px-4 py-2 font-semibold text-white rounded shadow bg-slate-700 hover:bg-slate-800">
            <i class="bi bi-download me-1"></i> Download Template
        </a>
    </div>
</div>

