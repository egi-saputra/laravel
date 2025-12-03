<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { CheckIcon, ArrowLeftIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    bankSoal: Object,
    soal: Object
});

const form = useForm({
    soal: props.bankSoal.soal,
    tipe_soal: props.bankSoal.tipe_soal,
    jawaban_benar: props.bankSoal.jawaban_benar,
    nilai: props.bankSoal.nilai,
    jenis_lampiran: props.bankSoal.jenis_lampiran,
    link_lampiran: null,
    opsi_a: props.bankSoal.opsi_a,
    opsi_b: props.bankSoal.opsi_b,
    opsi_c: props.bankSoal.opsi_c,
    opsi_d: props.bankSoal.opsi_d,
    opsi_e: props.bankSoal.opsi_e,
});

function submit() {
    form.post(`/guru/bank-soal/${props.bankSoal.id}?_method=PUT`);
}
</script>

<template>
    <div class="py-8 px-4 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto md:bg-white md:rounded-2xl md:shadow-xl md:p-8">
            <h1 class="md:text-2xl text-lg font-bold text-gray-800 mb-6 text-left">Edit Detail Soal</h1>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Soal -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Pertanyaan</label>
                    <textarea v-model="form.soal"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                        rows="4" placeholder="Masukkan pertanyaan"></textarea>
                </div>

                <!-- Tipe Soal, Nilai, Jenis Lampiran -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tipe Soal</label>
                        <select v-model="form.tipe_soal"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                            <option value="PG">Pilihan Ganda</option>
                            <option value="Essay">Essay</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Bobot Nilai</label>
                        <input type="number" v-model="form.nilai" min="1"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                            placeholder="Nilai soal" />
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Jenis Lampiran</label>
                        <select v-model="form.jenis_lampiran"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                            <option value="Tanpa Lampiran">Tanpa Lampiran</option>
                            <option value="Gambar">Gambar</option>
                            <option value="Video">Video</option>
                        </select>
                    </div>
                </div>

                <!-- Link Lampiran -->
                <div v-if="form.jenis_lampiran !== 'Tanpa Lampiran'">
                    <label class="block text-gray-700 font-semibold mb-2">Link Lampiran</label>
                    <input v-model="form.link_lampiran" type="text"
                        :placeholder="props.bankSoal.link_lampiran || 'Masukkan link lampiran'"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-green-400 transition" />
                </div>

                <!-- Opsi PG -->
                <div v-if="form.tipe_soal === 'PG'" class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Opsi Jawaban A</label>
                        <input v-model="form.opsi_a"
                            class="w-full border pl-3 border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Opsi Jawaban B</label>
                        <input v-model="form.opsi_b"
                            class="w-full border pl-3 border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Opsi Jawaban C</label>
                        <input v-model="form.opsi_c"
                            class="w-full border pl-3 border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Opsi Jawaban D</label>
                        <input v-model="form.opsi_d"
                            class="w-full border pl-3 border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Opsi Jawaban E</label>
                        <input v-model="form.opsi_e"
                            class="w-full border pl-3 border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                    </div>
                </div>

                <!-- Jawaban Benar -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Jawaban Benar</label>
                    <input v-if="form.tipe_soal === 'Essay'" v-model="form.jawaban_benar" type="text"
                        placeholder="Jawaban Essay"
                        class="w-full border pl-3 border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
                    <select v-else v-model="form.jawaban_benar"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                        <option value="opsi_a">A. {{ form.opsi_a }}</option>
                        <option value="opsi_b">B. {{ form.opsi_b }}</option>
                        <option value="opsi_c">C. {{ form.opsi_c }}</option>
                        <option value="opsi_d">D. {{ form.opsi_d }}</option>
                        <option value="opsi_e">E. {{ form.opsi_e }}</option>
                    </select>
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
                    <Link :href="`/guru/soal/${props.bankSoal.soal_id}`"
                        class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-700 text-white font-semibold rounded-lg shadow-lg hover:from-gray-600 hover:to-gray-800 transition">
                    <ArrowLeftIcon class="w-5 h-5" />
                    Batal
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>
