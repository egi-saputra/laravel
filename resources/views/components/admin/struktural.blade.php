<!-- Tabel Data Struktural -->
{{-- <div class="md:p-6 md:bg-white md:rounded md:shadow">
    <div class="flex items-center justify-between mb-4">
        <h2 class="mb-4 text-lg font-bold">Daftar Struktural</h2>
    </div>

    <div class="overflow-x-auto md:overflow-x-visible">
        <table class="w-full border border-collapse" id="strukturalTable">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-center border whitespace-nowrap">No</th>
                    <th class="px-4 py-2 text-left border md:text-center whitespace-nowrap">Jabatan</th>
                    <th class="px-4 py-2 text-left border md:text-center whitespace-nowrap">Nama Lengkap</th>
                    <th class="px-4 py-2 text-center border"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($struktural ?? [] as $s)
                    <tr>
                        <td class="px-4 py-2 text-center border">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">{{ $s->jabatan }}</td>
                        <td class="px-4 py-2 border whitespace-nowrap">
                            {{ $s->user->name ?? '-' }}
                            <span class="text-gray-500 text-sm">
                                ({{ ucfirst($s->user->role ?? '-') }})
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center border">
                            <div x-data="{ open: false, showModal: false }" class="relative inline-block">
                                <!-- Tombol ⋮ -->
                                <button @click="open = !open"
                                        class="px-2 py-1 rounded hover:bg-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-6 h-6 text-gray-700"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute top-0 z-20 mr-2 bg-white border rounded shadow-md right-full w-28">
                                    <button type="button"
                                            @click="showModal = true; open = false"
                                            class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.struktural.destroy', $s->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full px-4 py-2 text-left text-red-600 hover:bg-red-100">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                                <!-- Modal Edit -->
                                <div x-show="showModal" x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                    <div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
                                        <h2 class="mb-4 text-lg font-bold">Edit Struktural</h2>
                                        <form action="{{ route('admin.struktural.update', $s->id) }}" method="POST" class="space-y-3">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label class="block font-medium">Jabatan</label>
                                                <input type="text" name="jabatan" value="{{ $s->jabatan }}" class="w-full px-3 py-2 border rounded" required>
                                            </div>
                                            <div>
                                                <label class="block font-medium">Nama GTK</label>
                                                <select name="nama_gtk" class="w-full px-3 py-2 border rounded" required>
                                                    <option value="">-- Pilih GTK --</option>
                                                    @foreach($gurus as $guru)
                                                        <option value="{{ $guru->id }}" {{ $s->nama_gtk == $guru->id ? 'selected' : '' }}>
                                                            {{ $guru->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="showModal = false"
                                                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                                                    Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-2 text-center">Belum ada data struktural</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> --}}

<!-- Wrapper Card -->
<div class="md:p-6 md:bg-white/70 dark:bg-gray-900/60 md:rounded md:shadow backdrop-blur-lg md:border border-gray-200 dark:border-gray-700 transition-all duration-300">

  <!-- Header -->
  <div class="flex justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2 sm:mb-0 flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
      Daftar Struktural
    </h2>
    <span class="text-sm text-gray-500 dark:text-gray-400">Total: {{ count($struktural ?? []) }}</span>
  </div>

  <!-- Table -->
  <div class="overflow-x-auto rounded-md border border-gray-200 dark:border-gray-700">
    <table class="w-full text-sm text-left text-gray-700 dark:text-gray-200">
      <thead class="bg-gray-100 dark:bg-gray-800/80 text-gray-800 dark:text-gray-100 uppercase text-xs">
        <tr>
          <th class="px-4 py-3 text-center">No</th>
          <th class="px-4 py-3">Jabatan</th>
          <th class="px-4 py-3">Nama Lengkap</th>
          <th class="px-4 py-3 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($struktural ?? [] as $s)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60 transition">
          <td class="px-4 py-3 text-center font-semibold">{{ $loop->iteration }}</td>
          <td class="px-4 py-3 whitespace-nowrap">{{ $s->jabatan }}</td>
          <td class="px-4 py-3 whitespace-nowrap">
            <span class="font-medium">{{ $s->user->name ?? '-' }}</span>
            <span class="text-xs text-gray-500 dark:text-gray-400">({{ ucfirst($s->user->role ?? '-') }})</span>
          </td>
          <td class="px-4 py-3 text-center">
            <div x-data="{ open: false, showModal: false }" class="relative inline-block text-left">

              <!-- Tombol menu -->
              <button @click="open = !open"
                class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600 dark:text-gray-300"
                  fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z" />
                </svg>
              </button>

              <!-- Dropdown -->
              <div x-show="open" @click.away="open = false" x-transition
                class="absolute right-0 z-20 w-32 mt-2 origin-top-right bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">
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

              <!-- Modal Edit -->
              <div x-show="showModal" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
                <div class="w-full max-w-md p-6 bg-white dark:bg-gray-900 rounded-xl shadow-lg">
                  <h2 class="mb-4 text-lg font-bold text-gray-800 dark:text-gray-100">Edit Struktural</h2>
                  <form action="{{ route('admin.struktural.update', $s->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                      <label class="block font-medium mb-1">Jabatan</label>
                      <input type="text" name="jabatan" value="{{ $s->jabatan }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-transparent focus:ring-2 focus:ring-blue-500 outline-none"
                        required>
                    </div>
                    <div>
                      <label class="block font-medium mb-1">Nama GTK</label>
                      <select name="nama_gtk"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-transparent focus:ring-2 focus:ring-blue-500 outline-none"
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
                        class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                        Batal
                      </button>
                      <button type="submit"
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                        Simpan
                      </button>
                    </div>
                  </form>
                </div>
              </div>

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
