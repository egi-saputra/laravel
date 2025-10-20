<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { computed } from 'vue'

// Ambil props dari controller
const props = defineProps({
  materi: Object,
})

const materi = computed(() => props.materi)
</script>

<template>
  <AppLayout title="Detail Materi">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Detail Materi
      </h2>
    </template>

    <div class="flex flex-col min-h-screen md:flex-row">
      <!-- Main Content -->
      <main class="flex-1 p-0 mb-16 space-y-2 overflow-x-auto md:space-y-6 md:mb-0 md:p-6">
        <div class="p-6 bg-white border rounded-lg shadow-lg">
          <!-- Judul -->
          <h1 class="mb-4 text-3xl font-bold text-gray-800">{{ materi.judul }}</h1>

          <!-- Info tambahan -->
          <div class="flex flex-col gap-2 mb-6 md:flex-row md:gap-6">
            <div class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded">
              <span class="font-semibold">Author:</span>
              {{ materi.user?.name || 'Unknown' }}
            </div>
            <div class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded">
              <span class="font-semibold">Kelas:</span>
              {{ materi.kelas?.kelas || '-' }}
            </div>
            <div class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded">
              <span class="font-semibold">Mata Pelajaran:</span>
              {{ materi.mapel?.mapel || '-' }}
            </div>
          </div>

          <!-- Deskripsi -->
          <div class="mb-6 text-gray-700">
            <h2 class="mb-2 text-lg font-semibold">Deskripsi Materi</h2>
            <p v-if="materi.deskripsi" class="whitespace-pre-line" v-html="materi.deskripsi"></p>
            <p v-else class="text-gray-400 italic">
              Tidak ada deskripsi materi / Hanya berupa file!
            </p>
          </div>

          <!-- Isi Materi -->
          <div v-if="materi.materi" class="mb-6 text-gray-700">
            <h2 class="mb-2 text-lg font-semibold">Isi Materi</h2>
            <div class="max-w-full prose" v-html="materi.materi"></div>
          </div>

          <!-- File Materi -->
          <div
            class="flex flex-col items-center justify-center w-full px-4 py-6 mb-6 border rounded-md shadow hover:shadow-lg border-slate-300"
          >
            <template v-if="materi.file_path">
              <template v-if="materi.file_path.endsWith('.pdf')">
                <iframe
                  :src="`/${materi.file_path}`"
                  class="w-full h-[600px] border rounded"
                ></iframe>
              </template>

              <template
                v-else-if="materi.file_path.match(/\.(jpg|jpeg|png|webp)$/i)"
              >
                <img
                  :src="`/${materi.file_path}`"
                  class="max-h-[240px] mx-auto rounded"
                  alt="Preview Gambar"
                />
              </template>

              <template v-else>
                <p class="pt-8 mb-4 text-gray-500">
                  Preview tidak tersedia untuk file ini.
                </p>
              </template>

              <a
                :href="`/${materi.file_path}`"
                download
                class="px-4 py-2 mt-4 text-white bg-blue-600 rounded hover:bg-blue-700"
              >
                Download File
              </a>
            </template>

            <p v-else class="text-gray-500">Tidak ada file yang diupload.</p>
          </div>

          <!-- Tombol kembali -->
          <div class="mt-6">
            <Link
              :href="route('guru.materi.index')"
              class="pl-2 text-blue-600 hover:underline"
            >
              ← Kembali ke Daftar Materi
            </Link>
          </div>
        </div>
      </main>
    </div>
  </AppLayout>
</template>
