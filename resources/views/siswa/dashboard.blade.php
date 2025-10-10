<x-app-backtop-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? '') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Sidebar -->
        <aside class="mx-0 mt-2 mb-4 md:top-0 md:ml-6 md:mt-6 md:h-screen md:w-auto">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-0 mb-16 space-y-6 overflow-x-auto md:mb-0 md:p-6">

            {{-- ===== Filter ===== --}}
            <div class="flex flex-col mb-4 space-y-2 md:flex-row md:justify-between md:items-center md:space-y-0">
                {{-- Filter Role --}}
                <div>
                    <label for="roleFilter" class="mr-2 text-sm font-medium text-gray-700">Filter Role:</label>
                    <select id="roleFilter" class="px-3 py-1 border rounded-lg">
                        <option value="all">Semua</option>
                        <option value="guru">Guru</option>
                        <option value="siswa">Siswa</option>
                        <option value="user">User</option>
                    </select>
                </div>
            </div>

            {{-- ===== Grid Online Users ===== --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" id="onlineUsersContainer">
                @forelse ($onlineUsers as $user)
                    <div class="flex items-center p-4 space-x-4 transition bg-white shadow rounded-xl md:rounded-2xl hover:shadow-lg user-card"
                         data-role="{{ $user->role }}">
                        <span class="inline-block w-3 h-3 bg-green-500 rounded-full"></span>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700">{{ $user->name }}</h3>
                            <p class="text-xs text-green-600">Online</p>
                            {{-- <p class="text-xs text-gray-400">Role: {{ ucfirst($user->role) }}</p> --}}
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 col-span-full no-users">Tidak ada user yang sedang online</p>
                @endforelse
                {{-- Pesan jika filter kosong --}}
                <p class="text-center text-gray-500 col-span-full no-users" style="display: none;">Tidak ada user yang sedang online</p>
            </div>

            {{-- ===== Statistik User ===== --}}
            <div class="hidden grid-cols-1 gap-6 md:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
                @php
                    $stats = [
                        ['title'=>'Guru','count'=>$guruCount,'bg'=>'bg-purple-100','text'=>'text-purple-600','icon'=>'fas fa-chalkboard-teacher'],
                        ['title'=>'Siswa','count'=>$siswaCount,'bg'=>'bg-green-100','text'=>'text-green-600','icon'=>'fas fa-user-graduate'],
                        ['title'=>'User','count'=>$userCount,'bg'=>'bg-pink-100','text'=>'text-pink-600','icon'=>'fas fa-users'],
                        ['title'=>'Total Pengguna','count'=>$totalUsers,'bg'=>'bg-indigo-100','text'=>'text-indigo-600','icon'=>'fas fa-user-friends'],
                        ['title'=>'Pengunjung Unik (IP)','count'=>$uniqueVisitors,'bg'=>'bg-teal-100','text'=>'text-teal-600','icon'=>'fas fa-fingerprint'],
                        ['title'=>'Total Pengunjung','count'=>$totalVisitors,'bg'=>'bg-red-100','text'=>'text-red-600','icon'=>'fas fa-eye'],
                    ];
                @endphp

                @foreach ($stats as $stat)
                    <div class="p-6 transition bg-white rounded-md shadow md:rounded-2xl hover:shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 {{ $stat['bg'] }} rounded-full">
                                <i class="md:text-2xl text-xl {{ $stat['text'] }} {{ $stat['icon'] }}"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">{{ $stat['title'] }}</p>
                                <h3 class="text-2xl font-bold {{ $stat['text'] }}">{{ $stat['count'] }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ===== Statistik Visitor ===== --}}
            <div class="hidden p-4 mt-4 bg-white rounded-md shadow-sm md:block md:rounded-2xl">
                <div class="flex items-center justify-between mb-3">
                    <p class="pl-2 text-sm font-medium text-gray-500 md:text-base">Statistik Pengunjung <span class="hidden sm:inline">Semua User</span></p>
                    <form method="GET" action="{{ route('visitor.index') }}">
                        <select name="limit" onchange="this.form.submit()"
                                class="px-3 py-1 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua</option>
                            <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </form>
                </div>

                <ul class="space-y-3">
                    @php $maxVisits = $visitsByUser->max('total_visits') ?: 1; @endphp
                    @foreach($visitsByUser as $user)
                        @php $percent = round(($user->total_visits / $maxVisits) * 100); @endphp
                        <li class="flex flex-col p-3 transition rounded-lg bg-gray-50 hover:bg-gray-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-700">
                                    {{ $user->name ?? 'User #' . $user->id }} ({{ ucfirst($user->role) }})
                                </span>
                                <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-blue-600 rounded-full">
                                    {{ $user->total_visits }}
                                </span>
                            </div>
                            <div class="w-full h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-blue-600 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </main>
    </div>

        <!-- Bottom Navigation (Mobile Only - Icon + Text) -->
        <div id="navhp" class="fixed bottom-0 left-0 right-0 z-50 flex justify-around py-2 text-xs bg-white border-t shadow-md md:hidden">

            <!-- Home/Dashboard -->
            <a href="{{ route('siswa.dashboard') }}" class="flex flex-col items-center nav-icon {{ Route::currentRouteName() == 'siswa.dashboard' ? 'active' : '' }}">
                <i class="text-lg fas fa-chart-line"></i>
                <small class="text-xs font-semibold">Beranda</small>
            </a>

            <!-- Siswa -->
            <a href="{{ route('public.daftar_siswa.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('public.daftar_siswa.*') ? 'active' : '' }}">
                <i class="text-lg fas fa-user-graduate"></i>
                <small class="text-xs font-semibold">Siswa</small>
            </a>

            <!-- Informasi Sekolah -->
            <a href="{{ route('public.informasi_sekolah.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('public.informasi_sekolah.index') ? 'active' : '' }}">
                <i class="text-lg fas fa-school"></i>
                <small class="text-xs font-semibold">Sekolah</small>
            </a>

            <!-- Materi -->
            <a href="{{ route('siswa.materi.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
                <i class="text-lg fas fa-book"></i>
                <small class="text-xs font-semibold">Materi</small>
            </a>

            <!-- Tugas Siswa -->
            <a href="{{ route('siswa.tugas.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
                <i class="text-lg fas fa-tasks"></i>
                <small class="text-xs font-semibold">Tugas</small>
            </a>

        </div>

    <script>
        // Filter role online users
        const filterSelect = document.getElementById('roleFilter');
        const userCards = document.querySelectorAll('.user-card');
        const noUsersMsg = document.querySelector('#onlineUsersContainer .no-users:last-of-type');

        filterSelect.addEventListener('change', function() {
            const role = this.value;
            let visibleCount = 0;

            userCards.forEach(card => {
                if (role === 'all' || card.dataset.role === role) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            noUsersMsg.style.display = visibleCount === 0 ? 'block' : 'none';
        });
    </script>

    <!-- Footer -->
    <x-footer :profil="$profil" />
</x-app-backtop-layout>
