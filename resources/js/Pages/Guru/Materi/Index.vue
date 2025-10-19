<template>
  <div class="flex flex-col min-h-screen md:flex-row">

    <!-- Sidebar + Footer -->
    <aside class="hidden mx-0 mt-2 mb-4 md:block md:top-0 md:ml-6 md:mt-6 md:w-auto">
      <inertia :component="'Sidebar'" :props="{ profil: props.profil }" />
      <inertia :component="'Footer'" :props="{ profil: props.profil }" />
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-0 mb-16 space-y-2 overflow-x-auto md:space-y-6 md:mb-0 md:p-6">

      <!-- Form Tambah Materi -->
      <div class="p-4 mb-0 bg-white border rounded-lg shadow-sm md:mb-16 backdrop-blur border-slate-200">
        <h2 class="flex items-center gap-2 mb-4 text-xl font-bold text-slate-800">
          <i class="text-lg text-blue-600 bi bi-journal-text"></i>
          Buat Materi Pembelajaran
        </h2>

        <!-- Form Input Materi -->
        <form
          @submit.prevent="submitForm"
          class="space-y-3"
          enctype="multipart/form-data"
        >
          <!-- Kelas & Mapel -->
          <div class="w-full gap-4 md:flex">
            <div class="flex flex-col flex-1">
              <label class="mb-1 font-medium">Unit Kelas <small class="text-red-500">*</small></label>
              <select v-model="form.kelas_id" required class="w-full px-3 py-2 mb-4 border rounded">
                <option value="">-- Pilih Kelas --</option>
                <option v-for="k in props.kelas" :key="k.id" :value="k.id">{{ k.kelas }}</option>
              </select>
            </div>

            <div class="flex flex-col flex-1">
              <label class="mb-1 font-medium">Mata Pelajaran <small class="text-red-500">*</small></label>
              <select v-model="form.mapel_id" required class="w-full px-3 py-2 mb-3 border rounded">
                <option value="">-- Pilih Mata Pelajaran --</option>
                <option v-for="m in props.mapel" :key="m.id" :value="m.id">{{ m.mapel }}</option>
              </select>
            </div>
          </div>

          <!-- Judul -->
          <div>
            <label class="block font-medium">Judul Materi <small class="text-red-500">*</small></label>
            <input v-model="form.judul" type="text" class="w-full px-3 py-2 mb-4 border rounded" required />
          </div>

          <!-- Deskripsi -->
          <div>
            <label class="block font-medium">Deskripsi Materi <small class="text-red-500">*</small></label>
            <textarea v-model="form.deskripsi" rows="3" class="w-full px-3 py-2 mb-4 border rounded" placeholder="Tuliskan deskripsi singkat materimu!" required></textarea>
          </div>

          <!-- TinyMCE Blade Component -->
          <div class="overflow-x-auto md:overflow-x-visible">
            <label class="block mb-2 font-medium">Isi Materi Pembelajaran (Opsional)</label>
            <div v-html="props.tinymceComponent"></div>
          </div>

          <!-- Upload File -->
          <div>
            <label class="block mb-2 font-medium">Upload File (Opsional)</label>
            <input type="file" @change="handleFileUpload" class="w-full px-3 py-2 border rounded" />
            <small class="text-red-500">* Format diperbolehkan: pdf, docx, xlsx, pptx, dll. (Maks 10 MB)</small>
          </div>

          <input type="hidden" v-model="form.user_id" />

          <div class="flex justify-end md:justify-start">
            <button type="submit" class="px-4 py-2 mt-2 text-white bg-blue-600 rounded hover:bg-blue-700">
              <i class="bi bi-save"></i> Simpan
            </button>
          </div>
        </form>
      </div>

      <!-- Materi Table Blade Component -->
      <div v-html="props.materiTableComponent"></div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
  kelas: {
    type: Array,
    default: () => [],
  },
  mapel: {
    type: Array,
    default: () => [],
  },
  materis: {
    type: Array,
    default: () => [],
  },
  profil: {
    type: Object,
    default: () => ({}),
  },
})

const form = ref({
  kelas_id: '',
  mapel_id: '',
  judul: '',
  deskripsi: '',
  materi: '',
  file: null,
  user_id: '{{ auth()->id() }}'
})

function handleFileUpload(event) {
  form.value.file = event.target.files[0]
}

function submitForm() {
  const data = new FormData()
  Object.keys(form.value).forEach(key => data.append(key, form.value[key]))
  Inertia.post(route('guru.materi.store'), data)
}
</script>
