<!-- Wrapper Card -->
<div class="transition-all duration-300 border-gray-200 md:p-6 md:bg-white/70 dark:bg-gray-900/60 md:rounded md:shadow backdrop-blur-lg md:border dark:border-gray-700">

  <!-- Header -->
  <div class="flex justify-between mb-6">
    <h2 class="flex items-center gap-2 mb-2 text-xl font-bold text-gray-800 dark:text-gray-100 sm:mb-0">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
      Daftar Struktural
    </h2>
    <span class="text-sm text-gray-500 dark:text-gray-400">Total: {{ count($struktural ?? []) }}</span>
  </div>

  <!-- Table -->
  <div class="overflow-x-auto border border-gray-200 rounded-md dark:border-gray-700">
    <table class="w-full text-sm text-left text-gray-700 dark:text-gray-200">
      <thead class="text-xs text-gray-800 uppercase bg-gray-100 dark:bg-gray-800/80 dark:text-gray-100">
        <tr>
          <th class="px-4 py-3 text-center">No</th>
          <th class="px-4 py-3">Jabatan</th>
          <th class="px-4 py-3">Nama Lengkap</th>
          <th class="px-4 py-3 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($struktural ?? [] as $s)
        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/60">
          <td class="px-4 py-3 font-semibold text-center">{{ $loop->iteration }}</td>
          <td class="px-4 py-3 whitespace-nowrap">{{ $s->jabatan }}</td>
          <td class="px-4 py-3 whitespace-nowrap">
            <span class="font-medium">{{ $s->user->name ?? '-' }}</span>
            <span class="text-xs text-gray-500 dark:text-gray-400">({{ ucfirst($s->user->role ?? '-') }})</span>
          </td>
          <td class="px-4 py-3 text-center">
            <div x-data="{ open: false, showModal: false }" class="relative inline-block text-left">

              <!-- Tombol menu -->
              <button @click="open = !open"
                class="p-2 transition rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600 dark:text-gray-300"
                  fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z" />
                </svg>
              </button>

              <!-- Dropdown -->
              <div x-show="open" @click.away="open = false" x-transition
                class="absolute right-0 z-20 w-32 mt-2 origin-top-right bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                <button @click="showModal = true; open = false"
                  class="block w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700">
                  ✏️ Edit
                </button>
                <form action="{{ route('admin.struktural.destroy', $s->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                    class="block w-full px-4 py-2 text-left text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30">
                    🗑️ Hapus
                  </button>
                </form>
              </div>

              <!-- ✅ Modal dipindahkan ke level paling atas DOM -->
              <template x-teleport="body">
                <div x-show="showModal" x-cloak
                  class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm">
                  <div class="w-full max-w-md p-6 mx-4 bg-white shadow-lg dark:bg-gray-900 rounded-xl">
                    <h2 class="mb-4 text-lg font-bold text-gray-800 dark:text-gray-100">Edit Struktural</h2>
                    <form action="{{ route('admin.struktural.update', $s->id) }}" method="POST" class="space-y-4">
                      @csrf
                      @method('PUT')
                      <div>
                        <label class="block mb-1 font-medium">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ $s->jabatan }}"
                          class="w-full px-3 py-2 bg-transparent border border-gray-300 rounded-lg outline-none dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                          required>
                      </div>
                      <div>
                        <label class="block mb-1 font-medium">Nama GTK</label>
                        <select name="nama_gtk"
                          class="w-full px-3 py-2 bg-transparent border border-gray-300 rounded-lg outline-none dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
                          required>
                          <option value="">-- Pilih GTK --</option>
                          @foreach($gurus as $guru)
                          <option value="{{ $guru->id }}" {{ $s->nama_gtk == $guru->id ? 'selected' : '' }}>
                            {{ $guru->name }}
                          </option>
                          @endforeach
                        </select>
                      </div>
                      <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showModal = false"
                          class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg dark:text-gray-300 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                          Batal
                        </button>
                        <button type="submit"
                          class="px-4 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                          Simpan
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </template>

            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada data struktural</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
