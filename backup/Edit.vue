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
    link_lampiran: null, // file baru
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
    <div class="p-6 max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Edit Bank Soal</h1>

        <form @submit.prevent="submit" class="bg-white shadow p-4 rounded space-y-4">
            <div>
                <label class="block font-semibold">Soal</label>
                <textarea v-model="form.soal" class="w-full border rounded p-2" rows="3"></textarea>
            </div>

            <div class="flex gap-4">
                <div>
                    <label class="block font-semibold">Tipe Soal</label>
                    <select v-model="form.tipe_soal" class="border rounded p-2">
                        <option value="PG">PG</option>
                        <option value="Essay">Essay</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold">Nilai</label>
                    <input type="number" v-model="form.nilai" class="border rounded p-2" min="1">
                </div>

                <div>
                    <label class="block font-semibold">Jenis Lampiran</label>
                    <select v-model="form.jenis_lampiran" class="border rounded p-2">
                        <option value="Tanpa Lampiran">Tanpa Lampiran</option>
                        <option value="Gambar">Gambar</option>
                        <option value="Video">Video</option>
                    </select>
                </div>
            </div>

            <div v-if="form.jenis_lampiran !== 'Tanpa Lampiran'">
                <label class="block font-semibold">Upload Lampiran Baru</label>
                <input type="file" @change="e => form.link_lampiran = e.target.files[0]" />
            </div>

            <div v-if="form.tipe_soal === 'PG'" class="grid grid-cols-2 gap-4">
                <div><label>A</label><input v-model="form.opsi_a" class="border rounded p-1 w-full"></div>
                <div><label>B</label><input v-model="form.opsi_b" class="border rounded p-1 w-full"></div>
                <div><label>C</label><input v-model="form.opsi_c" class="border rounded p-1 w-full"></div>
                <div><label>D</label><input v-model="form.opsi_d" class="border rounded p-1 w-full"></div>
                <div><label>E</label><input v-model="form.opsi_e" class="border rounded p-1 w-full"></div>
            </div>

            <div>
                <label class="block font-semibold">Jawaban Benar</label>
                <input v-model="form.jawaban_benar" class="border rounded p-2 w-full">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Simpan</button>
                <Link :href="`/guru/soal/${props.soal.id}`" class="px-4 py-2 bg-gray-600 text-white rounded">Batal
                </Link>
            </div>
        </form>
    </div>
</template>
