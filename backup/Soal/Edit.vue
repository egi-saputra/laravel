<script setup>
import { Link, useForm } from '@inertiajs/vue3';

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
    token: props.soal.token, // tambahkan field token
});

// Fungsi generate token 6 digit
const generateToken = () => {
    form.token = Math.floor(100000 + Math.random() * 900000).toString();
};
</script>

<template>
    <div class="p-6 max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Edit Soal</h1>

        <form @submit.prevent="form.put(`/guru/soal/${props.soal.id}`)">
            <!-- Mapel -->
            <div class="mb-3">
                <label>Mapel</label>
                <select v-model="form.mapel_id" class="w-full border p-2">
                    <option v-for="m in mapel" :value="m.id" :key="m.id">{{ m.mapel }}</option>
                </select>
            </div>

            <!-- Kelas -->
            <div class="mb-3">
                <label>Kelas</label>
                <select v-model="form.kelas_id" class="w-full border p-2">
                    <option v-for="k in kelas" :value="k.id" :key="k.id">{{ k.kelas }}</option>
                </select>
            </div>

            <!-- Waktu -->
            <div class="mb-3">
                <label>Waktu (Menit)</label>
                <input v-model="form.waktu" type="number" class="w-full border p-2" />
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label>Status</label>
                <select v-model="form.status" class="w-full border p-2">
                    <option>Aktif</option>
                    <option>Tidak Aktif</option>
                </select>
            </div>

            <!-- Tipe Soal -->
            <div class="mb-3">
                <label>Tipe Soal</label>
                <select v-model="form.tipe_soal" class="w-full border p-2">
                    <option>Berurutan</option>
                    <option>Acak</option>
                </select>
            </div>

            <!-- Token -->
            <div class="mb-3">
                <label>Token Soal</label>
                <div class="flex gap-2">
                    <input v-model="form.token" type="text" class="w-full border p-2" readonly />
                    <button type="button" @click="generateToken" class="px-3 py-2 bg-green-600 text-white rounded">
                        Generate Token Baru
                    </button>
                </div>
            </div>

            <div class="flex gap-2 mt-4">
                <button class="px-4 py-2 bg-blue-600 text-white rounded" :disabled="form.processing">Update</button>
                <Link href="/guru/soal" class="px-4 py-2 bg-gray-500 text-white rounded">Kembali</Link>
            </div>
        </form>
    </div>
</template>
