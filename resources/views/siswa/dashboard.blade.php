<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? '') }}
        </h2>
    </x-slot>

        <!-- Main Content Mobile Version -->
        <main class="flex-1 p-0 space-y-2 overflow-x-auto md:hidden md:my-2">

            <!-- Menu Aplikasi -->
            <div>
                <!-- Profil -->
                <div class="flex flex-col items-center p-4 pt-4 pb-6 mb-6 border rounded shadow-sm bg-gray-50">
                    <div class="w-full h-32 rounded shadow bg-gradient-to-r from-sky-600 via-blue-700 to-indigo-900 animate-gradient bg-[length:200%_200%]"></div>

                    @php
                        $user = Auth::user(); // ambil user yang login
                        $fotoUrl = $user->foto_profil
                            ? Storage::url($user->foto_profil->file_path)
                            : asset('storage/default/avatar.jpeg');
                    @endphp

                    <img src="{{ $fotoUrl }}"
                        alt="{{ $user->name }}"
                        class="w-24 h-24 rounded-full -mt-14 drop-shadow-md">

                    <div class="mt-2 text-center">
                        <p class="mb-2 text-lg font-semibold text-gray-700 md:text-sm md:mb-0">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500 capitalize md:text-xs">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Grid Menu -->
                <div class="grid grid-cols-3 gap-4 p-0 mb-4 text-center md:rounded-xl md:grid-cols-6">
                    <!-- Menu 1 -->
                    <a href="{{ route('siswa.data_diri') }}" class="flex flex-col items-center justify-center p-4 transition-all shadow backdrop-blur bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                        <i class="mb-2 text-xl text-gray-600 md:text-3xl bi bi-person-lines-fill"></i>
                        <span class="text-xs font-semibold text-gray-700 md:text-sm">Data Diri</span>
                    </a>

                    <!-- Menu 2 -->
                    <a href="{{ route('public.analitycs.index') }}" class="flex flex-col items-center justify-center p-4 transition-all shadow backdrop-blur bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md" data-turbo="false">
                        {{-- <i class="mb-2 text-xl text-red-600 md:text-3xl bi-bar-chart-line"></i> --}}
                        <i class="mb-2 text-2xl text-red-600 md:text-3xl bi bi-graph-up-arrow"></i>
                        {{-- <i class="mb-2 text-xl text-red-600 md:text-3xl fas fa-chart-line"></i> --}}
                        <span class="text-sm font-semibold text-gray-700">Analitycs</span>
                    </a>

                    <!-- Menu 3 -->
                    <a href="{{ route('siswa.presensi.index') }}"
                    class="flex flex-col items-center justify-center p-4 transition-all shadow backdrop-blur bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                        <i class="mb-2 text-3xl text-sky-600 bi bi-journal-bookmark"></i>
                        <span class="text-xs font-semibold text-gray-700 md:text-sm">Absensi</span>
                    </a>

                    <!-- Menu 4 -->
                    <a href="{{ route('public.jadwal_mapel.index') }}" class="flex flex-col items-center justify-center p-4 transition-all shadow backdrop-blur bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                        <i class="mb-2 text-3xl bi bi-calendar2-week text-amber-500"></i>
                        <span class="text-sm font-semibold text-gray-700">Jadwal</span>
                    </a>

                    <!-- Menu 5 -->
                    <a href="#" class="flex flex-col items-center justify-center p-4 transition-all shadow backdrop-blur bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                        <i class="mb-2 text-3xl bi bi-book text-emerald-600"></i>
                        <span class="text-sm font-semibold text-gray-700">Ujian</span>
                    </a>

                    <!-- Menu 6 -->
                    <a href="#" class="flex flex-col items-center justify-center p-4 transition-all shadow backdrop-blur bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                        <i class="mb-2 text-3xl text-indigo-600 bi bi-journal-text"></i>
                        <span class="text-sm font-semibold text-gray-700">Nilai</span>
                    </a>
                </div>
            </div>
        </main>

        <!-- Content Desktop Version -->
        <div class="hidden p-0 space-y-2 overflow-x-auto md:block md:flex-1 md:my-2">
            <!-- Menu Aplikasi -->
            <div>
                <!-- Grid Menu -->
                <div class="grid grid-cols-3 gap-4 p-0 text-center md:rounded-xl md:mb-4 md:grid-cols-6">
                    <!-- Menu 1 -->
                    <a href="#" class="flex flex-col items-center justify-center p-4 transition-all shadow backdrop-blur bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                        <i class="mb-2 text-xl md:text-3xl bi bi-people-fill text-sky-600"></i>
                        <span class="text-xs font-semibold text-gray-700 md:text-sm">Data Siswa</span>
                    </a>

                    <!-- Menu 2 -->
                    <a href="#" class="flex flex-col items-center justify-center p-4 transition-all shadow backdrop-blur bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                        <i class="mb-2 text-3xl bi bi-journal-text text-emerald-600"></i>
                        <span class="text-sm font-semibold text-gray-700">Materi</span>
                    </a>

                    <!-- Menu 3 -->
                    <a href="#" class="flex flex-col items-center justify-center p-4 transition-all shadow backdrop-blur bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                        <i class="mb-2 text-3xl bi bi-calendar2-week text-amber-500"></i>
                        <span class="text-sm font-semibold text-gray-700">Jadwal</span>
                    </a>

                    <!-- Menu 4 -->
                    <a href="#" class="flex flex-col items-center justify-center p-4 transition-all shadow backdrop-blur bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                        <i class="mb-2 text-3xl text-gray-700 bi bi-gear-fill"></i>
                        <span class="text-sm font-semibold text-gray-700">Pengaturan</span>
                    </a>

                    <!-- Menu 5 -->
                    <a href="#" class="flex flex-col items-center justify-center p-4 transition-all shadow backdrop-blur bg-gray-50 rounded-xl hover:bg-sky-50 hover:shadow-md">
                        <i class="mb-2 text-3xl text-indigo-600 bi bi-globe2"></i>
                        <span class="text-sm font-semibold text-gray-700">Website</span>
                    </a>

                    <!-- Menu 6 -->
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                            class="flex flex-col items-center justify-center w-full p-4 transition-all shadow backdrop-blur bg-gray-50 rounded-xl hover:bg-red-50 hover:shadow-md">
                            <i class="mb-2 text-3xl text-red-600 bi bi-box-arrow-right"></i>
                            <span class="text-sm font-semibold text-gray-700">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex-col hidden min-h-screen md:flex md:flex-row">

            <aside class="top-0 hidden p-0 mb-4 mr-6 md:block md:h-screen">
                <!-- Sidebar -->
                <x-sidebar />

                <!-- Footer -->
                <x-footer :profil="$profil" />
            </aside>

            <!-- Main Content Desktop -->
            <main class="flex-1 p-0 mb-24 overflow-x-auto md:space-y-6">
                {{-- ===== Statistik User ===== --}}
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
                    @php
                        $stats = [
                            [
                                'title'=>'Guru',
                                'count'=>$guruCount,
                                'bg'=>'bg-blue-100',
                                'text'=>'text-blue-600',
                                'icon'=>'fas fa-chalkboard-teacher'
                            ],
                            [
                                'title'=>'Staff',
                                'count'=>$staffCount,
                                'bg'=>'bg-yellow-100',
                                'text'=>'text-yellow-600',
                                'icon'=>'fas fa-user-tie'
                            ],
                            [
                                'title'=>'Siswa',
                                'count'=>$siswaCount,
                                'bg'=>'bg-green-100',
                                'text'=>'text-green-600',
                                'icon'=>'fas fa-user-graduate'
                            ],
                            [
                                'title'=>'User',
                                'count'=>$userCount,
                                'bg'=>'bg-pink-100',
                                'text'=>'text-pink-600',
                                'icon'=>'fas fa-users'
                            ],
                            [
                                'title'=>'Total Pengguna',
                                'count'=>$totalUsers,
                                'bg'=>'bg-purple-100',
                                'text'=>'text-purple-600',
                                'icon'=>'fas fa-user-friends'
                            ],
                            [
                                'title'=>'Total Pengunjung',
                                'count'=>$totalVisitors,
                                'bg'=>'bg-red-100',
                                'text'=>'text-red-600',
                                'icon'=>'fas fa-eye'
                            ],
                        ];
                    @endphp

                    @foreach ($stats as $stat)
                        <div class="p-6 transition bg-white rounded-lg shadow hover:shadow-lg">
                            <div class="flex items-center">
                                <div class="p-3 {{ $stat['bg'] }} rounded-full">
                                    <i class="text-2xl {{ $stat['text'] }} {{ $stat['icon'] }}"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">{{ $stat['title'] }}</p>
                                    <h3 class="text-2xl font-bold {{ $stat['text'] }}">{{ $stat['count'] }}</h3>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ===== Filter ===== --}}
                <div class="flex flex-col mb-4 ml-2 space-y-2 md:flex-row md:justify-between md:items-center md:space-y-0">
                    <div>
                        <label for="roleFilter" class="mr-2 text-sm font-medium text-gray-700">Filter Role:</label>
                        <select id="roleFilter" class="px-3 py-1 border rounded-lg">
                            <option value="all">Semua</option>
                            <option value="guru">Guru</option>
                            <option value="staff">Staff</option>
                            <option value="siswa">Siswa</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>

                {{-- ===== Grid Online Users ===== --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" id="onlineUsersContainer" data-turbo="false">
                    @forelse ($onlineUsers as $user)
                        <div class="flex items-center p-4 space-x-4 transition bg-white shadow rounded-xl hover:shadow-lg user-card"
                            data-role="{{ $user->role }}">
                            <span class="inline-block w-3 h-3 bg-green-500 rounded-full"></span>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-700">{{ $user->name }}</h3>
                                <p class="text-xs text-green-600">Online</p>
                                <p class="text-xs text-gray-400">Role: {{ ucfirst($user->role) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 col-span-full no-users">Tidak ada user yang sedang online</p>
                    @endforelse
                    {{-- Pesan jika filter kosong --}}
                    <p class="text-center text-gray-500 col-span-full no-users" style="display: none;">Tidak ada user yang sedang online</p>
                </div>

                {{-- ===== Statistik Visitor ===== --}}
                <div class="p-4 mt-4 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <p class="pl-2 text-sm font-medium text-gray-500 md:text-base">Statistik Pengunjung <span class="hidden sm:inline">Semua User</span></p>
                        <form method="GET" action="{{ url()->current() }}" data-turbo="false">
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
                        @php
                            $maxVisits = $visitsByUser->max('total_visits') ?: 1;
                            $roleColors = [
                                'guru'  => ['bg' => 'bg-blue-600', 'bar' => 'bg-blue-600'],
                                'staff' => ['bg' => 'bg-yellow-600', 'bar' => 'bg-yellow-600'],
                                'siswa' => ['bg' => 'bg-green-600', 'bar' => 'bg-green-600'],
                                'user'  => ['bg' => 'bg-red-600', 'bar' => 'bg-red-600'],
                            ];
                        @endphp

                        @foreach($visitsByUser as $user)
                            @continue($user->role === 'admin') {{-- skip admin --}}
                            @php
                                $percent = round(($user->total_visits / $maxVisits) * 100);
                                $color = $roleColors[$user->role] ?? ['bg' => 'bg-gray-600', 'bar' => 'bg-gray-600'];
                            @endphp
                            <li class="flex flex-col p-3 transition rounded-lg bg-gray-50 hover:bg-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-medium text-gray-700">
                                        {{ $user->name ?? 'User #' . $user->id }}
                                    </span>
                                    <span class="inline-block px-3 py-1 text-sm font-semibold text-white {{ $color['bg'] }} rounded-full">
                                        {{ $user->total_visits }}
                                    </span>
                                </div>
                                <div class="w-full h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 {{ $color['bar'] }} rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </main>
        </div>

        <script>
            document.addEventListener('turbo:load', () => {
                // Pastikan elemen tersedia dulu
                const truncateBtn = document.getElementById('truncateVisitorBtn');
                const truncateForm = document.getElementById('truncateVisitorForm');
                const filterSelect = document.getElementById('roleFilter');
                const userCards = document.querySelectorAll('.user-card');
                const noUsersMsg = document.querySelector('#onlineUsersContainer .no-users:last-of-type');

                // 🔹 Filter role online users
                if (filterSelect && userCards.length > 0 && noUsersMsg) {
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
                }
            });
        </script>

</x-app-layout>
