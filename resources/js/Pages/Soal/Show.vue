<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowLeftIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    soal: Object,
});
</script>

<template>
    <div class="p-6 max-w-6xl md:bg-transparent bg-slate-50 mx-auto">
        <h1 class="md:text-2xl text-xl md:font-bold font-semibold mb-6 text-gray-800">Detail Quiz / Soal Ujian</h1>

        <!-- Informasi Soal -->
        <div class="mb-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="p-3 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <p class="text-gray-500 text-sm">Token Quiz</p>
                    <p class="font-bold text-indigo-600">{{ soal.token }}</p>
                </div>
                <div class="p-3 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <p class="text-gray-500 text-sm">Status Quiz</p>
                    <p class="font-bold" :class="soal.status === 'Aktif' ? 'text-green-600' : 'text-gray-700'">
                        {{ soal.status }}
                    </p>
                </div>
                <div class="p-3 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <p class="text-gray-500 text-sm">Format Soal</p>
                    <p class="font-semibold">{{ soal.tipe_soal }}</p>
                </div>
                <div class="p-3 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <p class="text-gray-500 text-sm">Waktu Ujian</p>
                    <p class="font-semibold">{{ soal.waktu }} menit</p>
                </div>
                <div class="p-3 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <p class="text-gray-500 text-sm">Mata Pelajaran</p>
                    <p class="font-semibold">{{ soal.mapel?.mapel }}</p>
                </div>
                <div class="p-3 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <p class="text-gray-500 text-sm">Unit Kelas</p>
                    <p class="font-semibold">{{ soal.kelas?.kelas }}</p>
                </div>
            </div>
        </div>

        <!-- Tombol Tambah Bank Soal -->
        <div class="mb-6 flex flex-col md:flex-row justify-between gap-4">
            <Link href="/guru/soal"
                class="md:flex hidden items-center justify-center px-5 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition gap-2">
            <ArrowLeftIcon class="w-5 h-5" />
            <span>Back to Quiz</span>
            </Link>

            <Link :href="`/guru/bank-soal/create?soal_id=${soal.id}`"
                class="flex justify-center items-center gap-2 px-5 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-800 transition text-white font-semibold rounded-lg shadow">
            <span>+ Tambah Soal</span>
            </Link>
        </div>

        <!-- Tabel Bank Soal -->
        <!-- <div class="overflow-x-auto bg-white rounded-xl shadow border border-gray-200">
            <h2 class="text-xl font-semibold mb-3 p-4 border-b border-gray-200">Daftar Bank Soal</h2>
            <table class="min-w-full text-left">
                <thead class="bg-gray-100 text-gray-700 font-medium">
                    <tr>
                        <th class="p-3 border-b">No</th>
                        <th class="p-3 border-b">Pertanyaan</th>
                        <th class="p-3 border-b text-center">Tipe Soal</th>
                        <th class="p-3 border-b text-center">Lampiran</th>
                        <th class="p-3 border-b text-center">Jawaban</th>
                        <th class="p-3 border-b">Opsi Jawaban</th>
                        <th class="p-3 border-b text-center">Bobot Nilai</th>
                        <th class="p-3 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="!soal.bank_soal || soal.bank_soal.length === 0">
                        <td colspan="8" class="text-center p-6 text-gray-400">Belum ada soal untuk quiz ini.</td>
                    </tr>
                    <tr v-for="(item, index) in soal.bank_soal" :key="item.id"
                        class="hover:bg-gray-50 transition cursor-pointer">
                        <td class="p-3 border-b">{{ index + 1 }}</td>
                        <td class="p-3 border-b max-w-xs truncate" :title="item.soal">{{ item.soal }}</td>
                        <td class="p-3 border-b text-center">{{ item.tipe_soal }}</td>
                        <td class="p-3 border-b text-center">
                            <span v-if="item.link_lampiran">
                                <a :href="`/${item.link_lampiran}`" target="_blank" class="text-blue-500 underline">
                                    {{ item.link_lampiran }}
                                </a>
                            </span>
                            <span v-else class="text-gray-400">Tanpa Lampiran</span>
                        </td>
                        <td class="p-3 border-b text-center font-bold text-green-600">{{ item.jawaban_benar }}</td>
                        <td class="p-3 border-b space-y-1">
                            <p v-if="item.opsi_a">A. {{ item.opsi_a }}</p>
                            <p v-if="item.opsi_b">B. {{ item.opsi_b }}</p>
                            <p v-if="item.opsi_c">C. {{ item.opsi_c }}</p>
                            <p v-if="item.opsi_d">D. {{ item.opsi_d }}</p>
                            <p v-if="item.opsi_e">E. {{ item.opsi_e }}</p>
                        </td>
                        <td class="p-3 border-b text-center">{{ item.nilai }}</td>
                        <td class="p-3 border-b text-center flex justify-center gap-2">
                            <Link :href="`/guru/bank-soal/${item.id}/edit`"
                                class="px-3 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                            Edit
                            </Link>
                            <Link as="button" method="delete" :href="`/guru/bank-soal/${item.id}`"
                                class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                                onclick="return confirm('Yakin hapus soal ini?')">
                            Hapus
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> -->

        <!-- Daftar Bank Soal dalam Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-if="!soal.bank_soal || soal.bank_soal.length === 0"
                class="col-span-full text-center text-gray-400 p-6 bg-gray-50 rounded-lg shadow">
                Belum ada soal untuk quiz ini.
            </div>

            <div v-for="(item, index) in soal.bank_soal" :key="item.id"
                class="bg-white rounded-xl shadow-lg border border-gray-200 p-5 flex flex-col justify-between hover:shadow-2xl transition transform">
                <!-- Header -->
                <div class="flex justify-between items-start mb-3">
                    <span class="text-gray-400 font-semibold">No {{ index + 1 }}</span>
                    <span class="text-sm font-medium px-2 py-1 rounded-full bg-indigo-100 text-indigo-700">{{
                        item.tipe_soal }}</span>
                </div>

                <!-- Pertanyaan -->
                <p class="text-green-600 font-bold">Pertanyaan:</p>
                <p class="text-gray-800 font-semibold mb-3 line-clamp-3" :title="item.soal">{{ item.soal }}</p>

                <!-- Pertanyaan -->
                <p class="text-green-600 font-bold">Link Lampiran:</p>
                <p class="text-gray-800 font-semibold mb-3 line-clamp-3" :title="item.soal"><span
                        v-if="item.link_lampiran">
                        <a :href="`/${item.link_lampiran}`" target="_blank" class="text-blue-500 underline">
                            {{ item.link_lampiran }}
                        </a>
                    </span>
                    <span v-else class="text-gray-400">Tanpa Lampiran</span>
                </p>

                <!-- Jawaban Benar -->
                <p class="text-green-600 font-bold mb-3">Jawaban: {{ item.jawaban_benar }}</p>

                <!-- Opsi Jawaban -->
                <div class="space-y-1 mb-4 text-gray-700">
                    <p v-if="item.opsi_a">A. {{ item.opsi_a }}</p>
                    <p v-if="item.opsi_b">B. {{ item.opsi_b }}</p>
                    <p v-if="item.opsi_c">C. {{ item.opsi_c }}</p>
                    <p v-if="item.opsi_d">D. {{ item.opsi_d }}</p>
                    <p v-if="item.opsi_e">E. {{ item.opsi_e }}</p>
                </div>

                <!-- Aksi -->
                <div class="flex justify-end gap-2">
                    <Link :href="`/guru/bank-soal/${item.id}/edit`"
                        class="px-3 py-1 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded hover:from-blue-600 hover:to-blue-800 transition flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Edit
                    </Link>

                    <Link as="button" method="delete" :href="`/guru/bank-soal/${item.id}`"
                        class="px-3 py-1 bg-gradient-to-r from-red-500 to-red-700 text-white rounded hover:from-red-600 hover:to-red-800 transition flex items-center gap-1"
                        onclick="return confirm('Yakin hapus soal ini?')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-6-4h6a2 2 0 012 2v2H7V5a2 2 0 012-2z" />
                    </svg>
                    Hapus
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
