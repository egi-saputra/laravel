<script setup>
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { CheckIcon, ArrowLeftIcon, DocumentArrowUpIcon } from '@heroicons/vue/24/solid';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    soal_id: [Number, String],
});

const form = useForm({
    soal_id: Number(props.soal_id),
    soal: '',
    tipe_soal: 'PG',
    jenis_lampiran: 'Tanpa Lampiran',
    link_lampiran: '',
    opsi_a: '',
    opsi_b: '',
    opsi_c: '',
    opsi_d: '',
    opsi_e: '',
    jawaban_benar: '',
    nilai: 0,
    excel: null,
});

// Submit soal manual
function submitManual() {
    form.post('/guru/bank-soal', {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Soal manual berhasil ditambahkan');
        }
    });
}

// Pilih file Excel
function importExcel(event) {
    form.excel = event.target.files[0] || null;
}

// Submit Excel
function submitExcel() {
    if (!form.excel) return;

    const data = new FormData();
    data.append('excel', form.excel);
    data.append('soal_id', props.soal_id);

    Inertia.post('/guru/bank-soal/import', data, {
        preserveScroll: true,
        onSuccess: () => {
            form.excel = null;
            console.log('Import Excel berhasil');
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
}

const isManualDisabled = computed(() => !!form.excel);

// Download template Excel
function downloadTemplate() {
    Inertia.visit('/guru/bank-soal/template', {
        method: 'get',
        preserveScroll: true,
        only: [],
        onFinish: () => console.log('Download template request sent'),
    });
}
</script>

<template>
    <div class="p-6 max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Tambah Soal Quiz</h1>

        <!-- Form Manual + Import -->
        <form @submit.prevent="submitManual" class="bg-white shadow-xl rounded-2xl p-6 space-y-5">

            <!-- Import Excel + Download Template -->
            <div class="border border-dashed border-gray-300 p-4 rounded-lg bg-gray-50 text-center space-y-2">
                <label class="flex flex-col items-center justify-center cursor-pointer">
                    <DocumentArrowUpIcon class="w-10 h-10 text-blue-500 mb-2" />
                    <span class="text-gray-600 font-semibold mb-1">Upload Excel Soal</span>
                    <span class="text-gray-400 text-sm">(.xlsx / .xls)</span>
                    <input type="file" accept=".xlsx,.xls" @change="importExcel" class="hidden" />
                </label>
                <p v-if="form.excel" class="mt-2 text-green-600 font-medium">{{ form.excel.name }}</p>

                <div class="flex justify-center gap-2 mt-2">
                    <button type="button" @click="submitExcel"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium"
                        :disabled="!form.excel">
                        Import Excel
                    </button>

                    <button type="button" @click="downloadTemplate"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Download Template Excel
                    </button>
                </div>
            </div>

            <!-- Tipe Soal -->
            <div>
                <label class="font-semibold mb-1 block text-gray-700">Tipe Soal</label>
                <select v-model="form.tipe_soal"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                    :disabled="isManualDisabled">
                    <option value="PG">Pilihan Ganda</option>
                    <option value="Essay">Essay</option>
                </select>
            </div>

            <!-- Pertanyaan -->
            <div>
                <label class="font-semibold mb-1 block text-gray-700">Pertanyaan</label>
                <textarea v-model="form.soal" rows="4"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                    placeholder="Masukkan pertanyaan" :disabled="isManualDisabled"></textarea>
            </div>

            <!-- Jenis Lampiran -->
            <div>
                <label class="font-semibold mb-1 block text-gray-700">Jenis Lampiran</label>
                <select v-model="form.jenis_lampiran"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                    :disabled="isManualDisabled">
                    <option value="Tanpa Lampiran">Tanpa Lampiran</option>
                    <option value="Gambar">Gambar</option>
                    <option value="Video">Video</option>
                </select>
            </div>

            <div v-if="form.jenis_lampiran !== 'Tanpa Lampiran'">
                <label class="font-semibold mb-1 block text-gray-700">Link Lampiran</label>
                <input v-model="form.link_lampiran" type="text" placeholder="Masukkan URL"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                    :disabled="isManualDisabled" />
            </div>

            <!-- Opsi Pilihan Ganda -->
            <div v-if="form.tipe_soal === 'PG'" class="grid grid-cols-1 gap-4">
                <div><label class="font-semibold">Jawaban Opsi A</label><input v-model="form.opsi_a"
                        class="w-full border p-2 rounded-lg" :disabled="isManualDisabled" /></div>
                <div><label class="font-semibold">Jawaban Opsi B</label><input v-model="form.opsi_b"
                        class="w-full border p-2 rounded-lg" :disabled="isManualDisabled" /></div>
                <div><label class="font-semibold">Jawaban Opsi C</label><input v-model="form.opsi_c"
                        class="w-full border p-2 rounded-lg" :disabled="isManualDisabled" /></div>
                <div><label class="font-semibold">Jawaban Opsi D</label><input v-model="form.opsi_d"
                        class="w-full border p-2 rounded-lg" :disabled="isManualDisabled" /></div>
                <div><label class="font-semibold">Jawaban Opsi E</label><input v-model="form.opsi_e"
                        class="w-full border p-2 rounded-lg" :disabled="isManualDisabled" /></div>
            </div>

            <!-- Jawaban Benar -->
            <div>
                <label class="font-semibold mb-1 block text-gray-700">Jawaban Benar</label>
                <input v-if="form.tipe_soal === 'Essay'" v-model="form.jawaban_benar" type="text"
                    placeholder="Jawaban Essay"
                    class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                    :disabled="isManualDisabled" />
                <select v-else v-model="form.jawaban_benar"
                    class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                    :disabled="isManualDisabled">
                    <option value="opsi_a">A: {{ form.opsi_a }}</option>
                    <option value="opsi_b">B: {{ form.opsi_b }}</option>
                    <option value="opsi_c">C: {{ form.opsi_c }}</option>
                    <option value="opsi_d">D: {{ form.opsi_d }}</option>
                    <option value="opsi_e">E: {{ form.opsi_e }}</option>
                </select>
            </div>

            <!-- Nilai -->
            <div>
                <label class="font-semibold mb-1 block text-gray-700">Bobot Nilai</label>
                <input v-model="form.nilai" type="number" min="0"
                    class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                    :disabled="isManualDisabled" />
            </div>

            <!-- Submit Manual -->
            <div class="flex flex-col md:flex-row gap-4 mt-4">
                <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white font-semibold rounded-lg shadow-lg hover:from-blue-600 hover:to-blue-800 transition"
                    :disabled="form.processing">
                    <CheckIcon class="w-5 h-5" />
                    <span>Create Quest</span>
                </button>

                <Link :href="`/guru/soal/${props.soal_id}`"
                    class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg shadow hover:bg-gray-700 transition">
                <ArrowLeftIcon class="w-5 h-5" />
                Back to Quiz List
                </Link>
            </div>

        </form>
    </div>
</template>
