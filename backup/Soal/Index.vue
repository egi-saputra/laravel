<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { EllipsisVerticalIcon, PencilIcon, EyeIcon, TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    soal: Object
});

const flash = usePage().props.flash?.success;

// Untuk track dropdown open state
const openDropdown = ref(null);

function toggleDropdown(id) {
    openDropdown.value = openDropdown.value === id ? null : id;
}
</script>

<template>
    <div class="p-6 bg-gray-50 min-h-screen">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-6">Daftar Soal Ujian</h1>

        <div class="flex justify-between items-center mb-4">
            <Link href="/guru/soal/create"
                class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition">
            + Buat Soal Baru
            </Link>
            <div v-if="flash" class="bg-green-100 text-green-800 px-4 py-2 rounded shadow">
                {{ flash }}
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg shadow">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 border-b text-gray-600 font-medium">ID</th>
                        <th class="p-3 border-b text-gray-600 font-medium">Mapel</th>
                        <th class="p-3 border-b text-gray-600 font-medium">Kelas</th>
                        <th class="p-3 border-b text-gray-600 font-medium">Token</th>
                        <th class="p-3 border-b text-gray-600 font-medium">Status</th>
                        <th class="p-3 border-b text-gray-600 font-medium">Tipe Soal</th>
                        <th class="p-3 border-b text-gray-600 font-medium">Waktu (Menit)</th>
                        <th class="p-3 border-b text-gray-600 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr v-for="item in soal.data" :key="item.id" class="hover:bg-gray-50 transition">
                        <td class="p-3 border-b">{{ item.id }}</td>
                        <td class="p-3 border-b">{{ item.mapel?.mapel }}</td>
                        <td class="p-3 border-b">{{ item.kelas?.kelas }}</td>
                        <td class="p-3 border-b font-semibold text-indigo-600">{{ item.token }}</td>
                        <td class="p-3 border-b">{{ item.status }}</td>
                        <td class="p-3 border-b">{{ item.tipe_soal }}</td>
                        <td class="p-3 border-b">{{ item.waktu }}</td>
                        <td class="p-3 border-b relative">
                            <!-- Tombol 3 titik -->
                            <!-- <button @click="toggleDropdown(item.id)"
                                class="p-2 rounded-full hover:bg-gray-200 transition">
                                <EllipsisVerticalIcon class="w-5 h-5 text-gray-600" />
                            </button> -->
                            <button @click="toggleDropdown(item.id)"
                                class="p-2 rounded-full hover:bg-gray-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M12 6v.01M12 12v.01M12 18v.01" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div v-if="openDropdown === item.id"
                                class="absolute right-24 -mt-6 w-36 bg-white border rounded-lg shadow-lg z-10">
                                <Link :href="`/guru/soal/${item.id}/edit`"
                                    class="flex items-center px-4 py-2 hover:bg-yellow-100 text-yellow-600 font-medium rounded-t-lg">
                                <PencilIcon class="w-4 h-4 mr-2" /> Edit
                                </Link>
                                <Link :href="`/guru/soal/${item.id}`"
                                    class="flex items-center px-4 py-2 hover:bg-green-100 text-green-600 font-medium">
                                <EyeIcon class="w-4 h-4 mr-2" /> Lihat
                                </Link>
                                <Link as="button" method="delete" :href="`/guru/soal/${item.id}`"
                                    @click="openDropdown.value = null"
                                    class="flex items-center px-4 py-2 hover:bg-red-100 text-red-600 font-medium rounded-b-lg"
                                    onclick="return confirm('Yakin ingin hapus?')">
                                <TrashIcon class="w-4 h-4 mr-2" /> Hapus
                                </Link>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-between items-center text-gray-700" v-if="soal.data.length">
            <Link v-if="soal.prev_page_url" :href="soal.prev_page_url"
                class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">
            Sebelumnya
            </Link>
            <span class="font-medium">Halaman {{ soal.current_page }} dari {{ soal.last_page }}</span>
            <Link v-if="soal.next_page_url" :href="soal.next_page_url"
                class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">
            Berikutnya
            </Link>
        </div>
    </div>
</template>
