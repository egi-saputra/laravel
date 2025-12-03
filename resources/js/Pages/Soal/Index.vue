<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { PencilIcon, EyeIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { ArrowLeftIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    soal: Object,
    dashboardUrl: String
});

const flash = usePage().props.flash?.success;

// Track dropdown open state
const openDropdown = ref(null);

// Toggle dropdown
function toggleDropdown(id, event) {
    event.stopPropagation(); // mencegah event bubbling
    openDropdown.value = openDropdown.value === id ? null : id;
}

// Tutup dropdown saat klik di luar
function handleClickOutside() {
    openDropdown.value = null;
}

onMounted(() => {
    window.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    window.removeEventListener('click', handleClickOutside);
});

// function goDashboard() {
//     // paksa reload halaman penuh
//     window.location.href = props.dashboardUrl;
// }
function goDashboard() {
    router.visit(props.dashboardUrl, {
        preserveState: false,
        preserveScroll: false,
        replace: true,
        onBefore: () => { window.location.href = props.dashboardUrl } // paksa reload
    });
}
</script>

<template>
    <div class="p-6 max-w-6xl md:bg-transparent md:my-6 bg-slate-50 mx-auto min-h-screen">
        <h1 class="md:text-2xl text-xl font-bold md:font-extrabold text-gray-800 mb-6">Daftar Quiz / Soal Ujian</h1>

        <div class="flex justify-between items-center mb-4" v-if="soal.data && soal.data.length">
            <div class="flex space-x-2">
                <!-- Back to Dashboard Button -->
                <button @click="goDashboard"
                    class="flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-gray-500 to-gray-700 text-white font-semibold rounded-lg shadow-lg hover:from-gray-600 hover:to-gray-800 transition">
                    <ArrowLeftIcon class="w-5 h-5" />
                    Back to Dashboard
                </button>

                <!-- Create New Quiz Button -->
                <Link href="/guru/soal/create"
                    class="flex md:justify-start justify-end px-5 py-2 bg-blue-600 text-white font-medium rounded md:rounded-lg shadow hover:bg-blue-700 transition">
                + Create New Quiz
                </Link>
            </div>

            <div v-if="flash" class="bg-green-100 text-green-800 px-4 py-2 rounded shadow">
                {{ flash }}
            </div>
        </div>

        <div v-if="!soal.data || soal.data.length === 0"
            class="text-center py-20 bg-white rounded-xl shadow border border-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 h-12 w-12 text-gray-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-3-3v6m-6 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-gray-500 mb-4">Belum ada data quiz atau soal.</p>
            <Link href="/guru/soal/create"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
            Buat Quiz Sekarang !
            </Link>
        </div>

        <div v-else class="overflow-x-auto rounded bg-white md:rounded-lg shadow pb-24">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="bg-gray-200 text-center">
                    <tr>
                        <th class="p-3 border-b text-gray-600 font-medium">ID</th>
                        <th class="p-3 border-b text-gray-600 font-medium">Mata Pelajaran</th>
                        <th class="p-3 border-b text-gray-600 font-medium">Unit Kelas</th>
                        <th class="p-3 border-b text-gray-600 font-medium">Token Quiz</th>
                        <th class="p-3 border-b text-gray-600 font-medium">Status Quiz</th>
                        <th class="p-3 border-b text-gray-600 font-medium">Tipe Soal</th>
                        <th class="p-3 border-b text-gray-600 font-medium">Waktu Ujian</th>
                        <th class="p-3 border-b text-gray-600 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr v-for="item in soal.data" :key="item.id" class="hover:bg-gray-50 transition">
                        <td class="p-3 text-center border-b">{{ item.id }}</td>
                        <td class="p-3 border-b">{{ item.mapel?.mapel }}</td>
                        <td class="p-3 text-center border-b">{{ item.kelas?.kelas }}</td>
                        <td class="p-3 border-b text-center font-semibold text-indigo-600">{{ item.token }}</td>
                        <td class="p-3 text-center border-b"
                            :class="item.status === 'Aktif' ? 'text-green-600 font-semibold' : 'text-gray-400 font-medium'">
                            {{ item.status }}
                        </td>
                        <td class="p-3 text-center border-b">{{ item.tipe_soal }}</td>
                        <td class="p-3 text-center border-b">{{ item.waktu }} ( Menit )</td>
                        <td class="p-3 border-b relative">
                            <!-- Tombol 3 titik -->
                            <button @click="toggleDropdown(item.id, $event)"
                                class="p-2 rounded-full hover:bg-gray-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M12 6v.01M12 12v.01M12 18v.01" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div v-if="openDropdown === item.id"
                                class="absolute right-12 md:right-24 -mt-8 w-36 bg-white border rounded-lg shadow-lg z-10">
                                <Link :href="`/guru/soal/${item.id}/edit`"
                                    class="flex items-centerhover:bg-gray-100 w-full px-4 py-2  font-medium rounded-t-lg">
                                <PencilIcon class="w-4 h-4 mr-2" /> Setting
                                </Link>
                                <Link :href="`/guru/soal/${item.id}`"
                                    class="flex items-center px-4 py-2 w-full hover:bg-gray-100 font-medium">
                                <EyeIcon class="w-4 h-4 mr-2" /> Preview
                                </Link>
                                <Link as="button" method="delete" :href="`/guru/soal/${item.id}`"
                                    @click="openDropdown.value = null"
                                    class="flex items-center px-4 py-2 hover:bg-red-100 w-full text-red-600 font-medium rounded-b-lg"
                                    onclick="return confirm('Yakin ingin hapus?')">
                                <TrashIcon class="w-4 h-4 mr-2" /> Delete
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
            <span class="font-medium">Showing of {{ soal.current_page }} from {{ soal.last_page }}</span>
            <Link v-if="soal.next_page_url" :href="soal.next_page_url"
                class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">
            Berikutnya
            </Link>
        </div>
    </div>
</template>
