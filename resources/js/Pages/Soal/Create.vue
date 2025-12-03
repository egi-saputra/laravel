<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { CheckIcon, ArrowLeftIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    mapel: Array,
    kelas: Array,
});

const form = useForm({
    mapel_id: '',
    kelas_id: '',
    waktu: '',
    status: 'Tidak Aktif',
    tipe_soal: 'Berurutan',
});
</script>

<template>
    <div class="py-4 md:py-8 px-4 bg-gray-50 min-h-screen">
        <div class="max-w-xl mx-auto md:border md:border-gray-300 md:bg-white md:rounded-2xl md:shadow-xl md:p-8">
            <h1 class="md:text-2xl md:font-extrabold font-bold text-xl text-gray-800 mb-6 text-left">Buat
                Quiz / Soal Ujian</h1>

            <form @submit.prevent="form.post('/guru/soal')" class="space-y-5">

                <!-- Mapel -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Mata Pelajaran</label>
                    <select v-model="form.mapel_id"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                        <option value="">-- Pilih Mapel --</option>
                        <option v-for="m in mapel" :key="m.id" :value="m.id">{{ m.mapel }}</option>
                    </select>
                    <p v-if="form.errors.mapel_id" class="text-red-600 text-sm mt-1">{{ form.errors.mapel_id }}</p>
                </div>

                <!-- Kelas -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Unit Kelas</label>
                    <select v-model="form.kelas_id"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                        <option value="">-- Pilih Kelas --</option>
                        <option v-for="k in kelas" :key="k.id" :value="k.id">{{ k.kelas }}</option>
                    </select>
                    <p v-if="form.errors.kelas_id" class="text-red-600 text-sm mt-1">{{ form.errors.kelas_id }}</p>
                </div>

                <!-- Waktu -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Waktu (Menit)</label>
                    <input v-model="form.waktu" type="number" placeholder="Masukkan waktu pengerjaan"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                    <p v-if="form.errors.waktu" class="text-red-600 text-sm mt-1">{{ form.errors.waktu }}</p>
                </div>

                <!-- Status & Tipe Soal -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Status Quiz</label>
                        <select v-model="form.status"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                            <option>Aktif</option>
                            <option>Tidak Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Format Soal</label>
                        <select v-model="form.tipe_soal"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                            <option>Berurutan</option>
                            <option>Acak</option>
                        </select>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row gap-4 mt-6">
                    <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white font-semibold rounded-lg shadow-lg hover:from-blue-600 hover:to-blue-800 transition"
                        :disabled="form.processing">
                        <svg v-if="form.processing" class="w-5 h-5 animate-spin text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <CheckIcon v-else class="w-5 h-5" />
                        <span>{{ form.processing ? 'Creating your quiz....' : 'Create Quiz' }}</span>
                    </button>

                    <Link href="/guru/soal/"
                        class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-700 text-white font-semibold rounded-lg shadow-lg hover:from-gray-600 hover:to-gray-800 transition">
                    <ArrowLeftIcon class="w-5 h-5" />
                    Back to Quizes
                    </Link>
                </div>

            </form>
        </div>
    </div>
</template>
