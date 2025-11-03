<x-app-layout>
    <x-slot name="header">
        <h2 class="flex items-center gap-2 text-2xl font-bold leading-tight text-gray-800">
            <i class="text-blue-600 bi bi-speedometer2"></i>
            {{ __($pageTitle ?? 'Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">

        {{-- ===== Sidebar + Footer (Desktop) ===== --}}
        <aside class="hidden h-screen p-4 md:flex md:flex-col md:w-64 lg:w-72 xl:w-80">
            <x-sidebar />
            <div class="mt-auto">
                <x-footer :profil="$profil" />
            </div>
        </aside>

        {{-- ===== Main Content ===== --}}
        <main class="flex-1 px-4 py-6 mb-16 overflow-x-hidden border rounded-lg shadow-sm md:mt-4 md:px-8 md:py-10 bg-gradient-to-br from-slate-50 via-white to-slate-100">

            {{-- ===== Filter & Online Users ===== --}}
            <section class="mb-10">
                <div class="flex flex-col gap-4 mb-6 md:flex-row md:justify-between md:items-center">
                    <h3 class="flex items-center gap-2 text-lg font-semibold text-gray-700">
                        <i class="text-green-600 bi bi-wifi"></i> User Online
                    </h3>
                    <div>
                        <select id="roleFilter"
                            class="px-4 py-2 text-sm transition border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                            <option value="all">Semua Role</option>
                            <option value="guru">Guru</option>
                            <option value="staff">Staff</option>
                            <option value="siswa">Siswa</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>

                <div id="onlineUsersContainer" data-turbo="false"
                    class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @forelse ($onlineUsers as $user)
                        <div data-role="{{ $user->role }}"
                            class="flex items-center gap-4 p-5 transition duration-300 bg-white border border-gray-100 shadow-sm user-card rounded-xl hover:shadow-lg hover:-translate-y-1">
                            <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-full">
                                <i class="text-xl text-green-600 bi bi-person-fill"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $user->name }}</h4>
                                <p class="text-xs font-medium text-green-600">Online</p>
                                <p class="text-xs text-gray-500 capitalize">Role: {{ $user->role }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 col-span-full no-users">Tidak ada user online</p>
                    @endforelse
                </div>
            </section>

            {{-- ===== Statistik Pengguna ===== --}}
            <section class="mb-10">
                <h3 class="flex items-center gap-2 mb-4 text-lg font-semibold text-gray-700">
                    <i class="text-indigo-600 bi bi-people"></i> Statistik Pengguna
                </h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
                    @php
                        $stats = [
                            ['title'=>'Guru', 'count'=>$guruCount, 'color'=>'blue', 'icon'=>'fas fa-chalkboard-teacher'],
                            ['title'=>'Staff', 'count'=>$staffCount, 'color'=>'yellow', 'icon'=>'fas fa-user-tie'],
                            ['title'=>'Siswa', 'count'=>$siswaCount, 'color'=>'green', 'icon'=>'fas fa-user-graduate'],
                            ['title'=>'User', 'count'=>$userCount, 'color'=>'pink', 'icon'=>'fas fa-users'],
                            ['title'=>'Total Pengguna', 'count'=>$totalUsers, 'color'=>'purple', 'icon'=>'fas fa-user-friends'],
                            ['title'=>'Total Pengunjung', 'count'=>$totalVisitors, 'color'=>'red', 'icon'=>'fas fa-eye'],
                        ];
                    @endphp

                    @foreach ($stats as $stat)
                        <div
                            class="p-6 transition duration-300 bg-white border border-gray-100 shadow-sm rounded-2xl hover:shadow-lg hover:-translate-y-1">
                            <div class="flex items-center gap-4">
                                <div
                                    class="p-3 bg-{{ $stat['color'] }}-100 text-{{ $stat['color'] }}-600 rounded-full">
                                    <i class="{{ $stat['icon'] }} text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ $stat['title'] }}</p>
                                    <h4 class="text-2xl font-bold text-{{ $stat['color'] }}-600">
                                        {{ $stat['count'] }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- ===== Statistik Pengunjung ===== --}}
            <section>
                <h3 class="flex items-center gap-2 mb-4 text-lg font-semibold text-gray-700">
                    <i class="text-blue-600 bi bi-graph-up"></i> Statistik Pengunjung
                </h3>

                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-2xl">
                    <div class="flex items-center justify-between mb-5">
                        <p class="text-sm font-medium text-gray-600 md:text-base">Statistik Pengunjung Semua Role</p>
                        {{-- <form method="GET" action="{{ url()->current() }}" data-turbo="false">
                            <select name="limit" onchange="this.form.submit()"
                                class="px-3 py-2 text-sm transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
                                <option value="all" {{ request('limit') === 'all' ? 'selected' : '' }}>Semua</option>
                                <option value="10" {{ request('limit', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </form> --}}
                        <form method="GET" action="{{ url()->current() }}">
                            <select name="limit" onchange="window.location.href='{{ url()->current() }}?limit='+this.value"
                                class="px-3 py-2 text-sm transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
                                <option value="all" {{ request('limit') === 'all' ? 'selected' : '' }}>Semua</option>
                                <option value="10" {{ request('limit', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </form>
                    </div>

                    <ul class="space-y-3">
                        @php
                            $maxVisits = $visitsByUser->max('total_visits') ?: 1;
                            $roleColors = [
                                'guru'  => 'blue',
                                'staff' => 'yellow',
                                'siswa' => 'green',
                                'user'  => 'red',
                            ];
                        @endphp

                        @foreach($visitsByUser as $user)
                            @continue($user->role === 'admin')
                            @php
                                $percent = round(($user->total_visits / $maxVisits) * 100);
                                $color = $roleColors[$user->role] ?? 'gray';
                            @endphp
                            <li
                                class="flex flex-col p-4 transition border border-gray-100 bg-gradient-to-br from-gray-50 to-white rounded-xl hover:shadow-md">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-semibold text-gray-700">{{ $user->name ?? 'User #' . $user->id }}</span>
                                    <span
                                        class="px-3 py-1 text-sm font-semibold text-white rounded-full bg-{{ $color }}-600 shadow-sm">
                                        {{ $user->total_visits }}
                                    </span>
                                </div>
                                <div class="w-full h-2 overflow-hidden bg-gray-200 rounded-full">
                                    <div class="h-2 bg-{{ $color }}-600 rounded-full transition-all duration-500"
                                        style="width: {{ $percent }}%"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>

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
