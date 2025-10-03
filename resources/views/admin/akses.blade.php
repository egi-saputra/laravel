<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? '') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="z-0 mx-4 mt-4 md:z-10 top-16 md:top-0 md:ml-6 md:mt-6 md:h-screen md:mx-0 md:w-auto">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 space-y-6 overflow-x-auto md:p-6">

            <div class="p-6 bg-white rounded shadow">
                <h1 class="inline-block mb-8 text-xl font-bold border-b-2 border-slate-600">Kelola Hak Akses Guru</h1>
                <p class="font-semibold text-red-600">Keterangan:</p>
                <p class="mb-4 text-sm text-slate-500">Fitur yang dapat diakses oleh guru yang berstatus <em class="text-green-600">Activated</em> ⇾ Detail Data Siswa, PPDB Online (Coming Soon), Jurnal Online Prakerin (Coming Soon).</p>
                <div class="overflow-x-auto md:overflow-x-visible">
                    <table class="min-w-full border border-collapse border-gray-200 divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-sm font-medium text-left text-gray-700">#</th>
                                <th class="px-4 py-2 text-sm font-medium text-left text-gray-700 whitespace-nowrap">Nama Guru</th>
                                <th class="px-4 py-2 text-sm font-medium text-left text-gray-700 whitespace-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($guru as $index => $g)
                                @php
                                    $akses = $g->hakAkses;
                                    $isActive = $akses && $akses->status === 'Activated';
                                @endphp
                                <tr>
                                    <td class="px-4 py-2 text-sm whitespace-nowrap">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 text-sm whitespace-nowrap">{{ $g->user->name }}</td>
                                    <td class="px-4 py-2 text-sm whitespace-nowrap">
                                        <div x-data="{ on: {{ $isActive ? 'true' : 'false' }} }"
                                            class="inline-flex items-center cursor-pointer">
                                            <!-- Track -->
                                            <div @click="
                                                on = !on;
                                                fetch('{{ route('admin.akses.toggle') }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                    },
                                                    body: JSON.stringify({
                                                        guru_id: {{ $g->id }},
                                                        status: on ? 'Activated' : 'Deactivated'
                                                    })
                                                })
                                                .then(res => res.json())
                                                .then(data => {
                                                    if (!data.success) {
                                                        throw new Error(data.message || 'Unknown error');
                                                    }
                                                })
                                                .catch(err => {
                                                    // revert kalau gagal
                                                    on = !on;
                                                    alert('Gagal update status: ' + err.message);
                                                })
                                            "
                                            class="relative transition-colors duration-300 rounded-full w-14 h-7"
                                            :class="on ? 'bg-green-500' : 'bg-gray-300'">
                                                <!-- Bulatan -->
                                                <div class="absolute w-5 h-5 transition-all duration-300 bg-white rounded-full shadow-md top-1 left-1"
                                                    :class="on ? 'translate-x-7' : ''"></div>
                                            </div>

                                            <!-- Label Status -->
                                            <span class="ml-3 text-sm font-medium"
                                                :class="on ? 'text-green-600' : 'text-red-600'"
                                                x-text="on ? 'Activated' : 'Deactivated'"></span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-backtop-layout>
