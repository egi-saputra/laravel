<script setup>
import { Link, useForm } from '@inertiajs/vue3';

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
    link_lampiran: props.bankSoal.link_lampiran, // URL lampiran
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
    <div class="p-6 max-w-3xl mx-auto bg-white shadow rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Edit Bank Soal</h1>

        <form @submit.prevent="submit" class="space-y-4">

            <!-- Tipe Soal -->
            <div>
                <label class="font-semibold">Tipe Soal</label>
                <select v-model="form.tipe_soal" class="w-full border p-2 rounded">
                    <option value="PG">Pilihan Ganda</option>
                    <option value="Essay">Essay</option>
                </select>
            </div>

            <!-- Pertanyaan -->
            <div>
                <label class="font-semibold">Pertanyaan</label>
                <textarea v-model="form.soal" class="w-full border p-2 rounded" rows="4"></textarea>
            </div>

            <!-- Jenis Lampiran -->
            <div>
                <label class="font-semibold">Jenis Lampiran</label>
                <select v-model="form.jenis_lampiran" class="w-full border p-2 rounded">
                    <option value="Tanpa Lampiran">Tanpa Lampiran</option>
                    <option value="Gambar">Gambar</option>
                    <option value="Video">Video</option>
                </select>
            </div>

            <!-- Link Lampiran -->
            <div v-if="form.jenis_lampiran !== 'Tanpa Lampiran'">
                <label class="font-semibold">Link Lampiran</label>
                <input v-model="form.link_lampiran" type="text" placeholder="Masukkan URL"
                    class="w-full border p-2 rounded" />
            </div>

            <!-- Opsi PG -->
            <div v-if="form.tipe_soal === 'PG'" class="space-y-2">
                <label class="font-semibold">Opsi Jawaban</label>
                <input v-model="form.opsi_a" type="text" class="w-full border p-2 rounded" placeholder="Opsi A" />
                <input v-model="form.opsi_b" type="text" class="w-full border p-2 rounded" placeholder="Opsi B" />
                <input v-model="form.opsi_c" type="text" class="w-full border p-2 rounded" placeholder="Opsi C" />
                <input v-model="form.opsi_d" type="text" class="w-full border p-2 rounded" placeholder="Opsi D" />
                <input v-model="form.opsi_e" type="text" class="w-full border p-2 rounded" placeholder="Opsi E" />
            </div>

            <!-- Jawaban Benar -->
            <div>
                <label class="font-semibold">Jawaban Benar</label>
                <input v-if="form.tipe_soal === 'Essay'" v-model="form.jawaban_benar" type="text"
                    placeholder="Jawaban Essay" class="w-full border p-2 rounded" />
                <select v-else v-model="form.jawaban_benar" class="w-full border p-2 rounded">
                    <option value="opsi_a">A</option>
                    <option value="opsi_b">B</option>
                    <option value="opsi_c">C</option>
                    <option value="opsi_d">D</option>
                    <option value="opsi_e">E</option>
                </select>
            </div>

            <!-- Nilai -->
            <div>
                <label class="font-semibold">Nilai Soal</label>
                <input v-model="form.nilai" type="number" min="0" class="w-full border p-2 rounded" />
            </div>

            <!-- Submit -->
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded">Simpan</button>
                <Link :href="`/guru/soal/${props.soal.id}`" class="px-4 py-2 bg-gray-600 text-white rounded">Batal
                </Link>
            </div>
        </form>
    </div>
</template>
