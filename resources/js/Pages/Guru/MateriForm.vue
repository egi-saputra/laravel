<template>
<div>
  <AppLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ pageTitle || 'Kelola Data Siswa' }}
      </h2>
    </template>

    <div class="flex flex-col min-h-screen md:flex-row">

      <!-- Main Content -->
      <main class="flex-1 p-0 mb-16 space-y-2 overflow-x-auto md:space-y-6 md:mb-0 md:p-6">
        <!-- Form Tambah Materi -->
        <div class="p-4 mb-0 bg-white border rounded-lg shadow-sm md:mb-16 backdrop-blur border-slate-200">
          <h2 class="flex items-center gap-2 mb-4 text-xl font-bold text-slate-800">
            <i class="text-lg text-blue-600 bi bi-journal-text"></i>
            Buat Materi Pembelajaran
          </h2>

          <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-3">
            <div class="w-full gap-4 md:flex">
              <!-- Kelas dropdown -->
              <div class="flex flex-col flex-1">
                <label class="mb-1 font-medium">
                  Unit Kelas <small class="text-red-500">*</small>
                </label>
                <select v-model="form.kelas_id" class="w-full px-3 py-2 mb-4 border rounded" required>
                  <option value="">-- Pilih Kelas --</option>
                  <option v-for="k in kelas" :key="k.id" :value="k.id">{{ k.kelas }}</option>
                </select>
              </div>

              <!-- Mapel dropdown -->
              <div class="flex flex-col flex-1">
                <label class="mb-1 font-medium">
                  Mata Pelajaran <small class="text-red-500">*</small>
                </label>
                <select v-model="form.mapel_id" class="w-full px-3 py-2 mb-3 border rounded" required>
                  <option value="">-- Pilih Mata Pelajaran --</option>
                  <option v-for="m in mapel" :key="m.id" :value="m.id">{{ m.mapel }}</option>
                </select>
              </div>
            </div>

            <!-- Judul Materi -->
            <div>
              <label class="block font-medium">
                Judul Materi <small class="text-red-500">*</small>
              </label>
              <input
                v-model="form.judul"
                type="text"
                class="w-full px-3 py-2 mb-4 border rounded"
                required
              />
            </div>

            <!-- Deskripsi Materi -->
            <div>
              <label class="block font-medium">
                Deskripsi Materi <small class="text-red-500">*</small>
              </label>
              <textarea
                v-model="form.deskripsi"
                rows="3"
                class="w-full px-3 py-2 mb-4 border rounded"
                placeholder="Tuliskan deskripsi singkat materimu!"
                required
              ></textarea>
            </div>

            <!-- Isi Materi (TinyMCEEditor) -->
            <div class="overflow-x-auto md:overflow-x-visible">
              <label class="block mb-2 font-medium">Isi Materi Pembelajaran (Opsional)</label>
              <TinyMCEEditor v-model="form.materi" id="materi" name="materi" />
            </div>

            <!-- Upload File -->
            <div>
              <label class="block mb-2 font-medium">Upload File (Opsional)</label>
              <input
                type="file"
                @change="handleFileUpload"
                class="w-full px-3 py-2 border rounded"
              />
              <small class="text-red-500">
                * Format diperbolehkan: pdf, docx, xlsx, pptx, dll. (Maks 10 MB)
              </small>
            </div>

            <!-- Submit -->
            <div class="flex justify-end md:justify-start">
              <button
                type="submit"
                class="px-4 py-2 mt-2 text-white bg-blue-600 rounded hover:bg-blue-700"
              >
                <i class="bi bi-save"></i> Simpan
              </button>
            </div>
          </form>
        </div>
      </main>
    </div>
  </AppLayout>
  </div>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

// Props dari Laravel controller (Inertia::render)
const props = defineProps({
  pageTitle: String,
  profil: Object,
  kelas: Array,
  mapel: Array,
})

const form = useForm({
  kelas_id: '',
  mapel_id: '',
  judul: '',
  deskripsi: '',
  materi: '',
  file: null,
})

function handleFileUpload(event) {
  form.file = event.target.files[0]
}

function submit() {
  form.post(route('guru.materi.store'))
}
</script>
