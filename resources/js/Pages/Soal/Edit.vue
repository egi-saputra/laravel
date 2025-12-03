<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ArrowPathIcon, ArrowLeftIcon, CheckIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    soal: Object,
    mapel: Array,
    kelas: Array,
});

const form = useForm({
    mapel_id: props.soal.mapel_id,
    kelas_id: props.soal.kelas_id,
    waktu: props.soal.waktu,
    status: props.soal.status,
    tipe_soal: props.soal.tipe_soal,
    token: props.soal.token,
});

// Fungsi generate token 6 digit
const generateToken = () => {
    form.token = Math.floor(100000 + Math.random() * 900000).toString();
};
</script>

<template>
    <div class="md:py-8 py-2 px-4 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto md:bg-white md:rounded-2xl p-2 md:shadow-xl md:p-8">
            <h1 class="md:text-2xl text-xl font-bold text-gray-800 mb-4 md:mb-8 text-left">Pengaturan Quiz</h1>

            <form @submit.prevent="form.put(`/guru/soal/${props.soal.id}`)" class="space-y-5">
                <!-- Mapel & Kelas -->
                <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Mata Pelajaran</label>
                        <select v-model="form.mapel_id"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                            <option v-for="m in mapel" :value="m.id" :key="m.id">{{ m.mapel }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Unit Kelas</label>
                        <select v-model="form.kelas_id"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                            <option v-for="k in kelas" :value="k.id" :key="k.id">{{ k.kelas }}</option>
                        </select>
                    </div>
                </div> -->

                <!-- Waktu & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Waktu (Menit)</label>
                        <input v-model="form.waktu" type="number"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                            placeholder="Masukkan waktu pengerjaan soal" />
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Status Quiz</label>
                        <select v-model="form.status"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                            <option>Aktif</option>
                            <option>Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <!-- Tipe Soal -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Format Soal</label>
                    <select v-model="form.tipe_soal"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                        <option>Berurutan</option>
                        <option>Acak</option>
                    </select>
                </div>

                <!-- Token -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Token Quiz</label>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input v-model="form.token" type="text" readonly
                            class="flex-1 border border-gray-300 rounded-lg p-3 bg-gray-100 text-gray-700 focus:outline-none focus:ring-2 font-semibold md:font-extrabold focus:ring-green-400 transition"
                            placeholder="Token Soal" />
                        <button type="button" @click="generateToken"
                            class="flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-green-500 to-green-700 text-white font-semibold rounded-lg shadow-md hover:from-green-600 hover:to-green-800 transition">
                            <ArrowPathIcon class="w-5 h-5" />
                            Generate Token Baru
                        </button>
                    </div>
                </div>

                <!-- Tombol aksi -->
                <div class="flex flex-col sm:flex-row gap-4 mt-6">
                    <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white font-semibold rounded-lg shadow-lg hover:from-blue-600 hover:to-blue-800 transition"
                        :disabled="form.processing">

                        <!-- Spinner saat processing -->
                        <svg v-if="form.processing" class="w-5 h-5 animate-spin text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>

                        <!-- Icon default jika tidak processing -->
                        <CheckIcon v-else class="w-5 h-5" />

                        <span>{{ form.processing ? 'Updating process...' : 'Update' }}</span>
                    </button>

                    <Link href="/guru/soal"
                        class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-700 text-white font-semibold rounded-lg shadow-lg hover:from-gray-600 hover:to-gray-800 transition">
                    <ArrowLeftIcon class="w-5 h-5" />
                    Kembali
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>
