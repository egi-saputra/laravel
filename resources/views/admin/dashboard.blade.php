<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($pageTitle ?? '') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen md:flex-row">

        <aside class="hidden mx-0 mt-2 mb-4 md:block md:top-0 md:ml-6 md:mt-6 md:w-auto">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Footer -->
            <x-footer :profil="$profil" />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-0 mb-16 space-y-2 overflow-x-auto md:space-y-6 md:mb-0 md:p-6">
            {{-- ===== Statistik User ===== --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @php
                    $stats = [
                        ['title'=>'Jumlah Admin','count'=>$adminCount,'bg'=>'bg-blue-100','text'=>'text-blue-600','icon'=>'fas fa-user-shield'],
                        ['title'=>'Jumlah Guru','count'=>$guruCount,'bg'=>'bg-purple-100','text'=>'text-purple-600','icon'=>'fas fa-chalkboard-teacher'],
                        ['title'=>'Jumlah Staff','count'=>$staffCount,'bg'=>'bg-yellow-100','text'=>'text-yellow-600','icon'=>'fas fa-user-tie'],
                        ['title'=>'Jumlah Siswa','count'=>$siswaCount,'bg'=>'bg-green-100','text'=>'text-green-600','icon'=>'fas fa-user-graduate'],
                        ['title'=>'Jumlah User','count'=>$userCount,'bg'=>'bg-pink-100','text'=>'text-pink-600','icon'=>'fas fa-users'],
                        ['title'=>'Total Pengguna','count'=>$totalUsers,'bg'=>'bg-indigo-100','text'=>'text-indigo-600','icon'=>'fas fa-user-friends'],
                        ['title'=>'Pengunjung Unik (IP)','count'=>$uniqueVisitors,'bg'=>'bg-teal-100','text'=>'text-teal-600','icon'=>'fas fa-fingerprint'],
                        ['title'=>'Total Pengunjung','count'=>$totalVisitors,'bg'=>'bg-red-100','text'=>'text-red-600','icon'=>'fas fa-eye'],
                    ];
                @endphp

                @foreach ($stats as $stat)
                    <div class="p-6 transition bg-white shadow rounded-2xl hover:shadow-lg">
                        <div class="flex items-center">
                            <div class="p-2 {{ $stat['bg'] }} rounded-full">
                                <i class="text-xl {{ $stat['text'] }} {{ $stat['icon'] }}"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">{{ $stat['title'] }}</p>
                                <h3 class="text-2xl font-bold {{ $stat['text'] }}">{{ $stat['count'] }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ===== Filter & Hapus Visitor ===== --}}
            <div class="flex justify-between mx-2 mb-4 md:mx-0 md:items-center">
                {{-- Filter Role --}}
                <div>
                    <label for="roleFilter" class="mr-2 text-sm font-medium text-gray-700">Filter Role:</label>
                    <select id="roleFilter" class="px-3 py-1 border rounded-lg">
                        <option value="all">Semua</option>
                        <option value="admin">Admin</option>
                        <option value="guru">Guru</option>
                        <option value="staff">Staff</option>
                        <option value="siswa">Siswa</option>
                        <option value="user">User</option>
                    </select>
                </div>

                {{-- Tombol Hapus --}}
                <form id="truncateVisitorForm" action="{{ route('visitor.truncate') }}" method="POST">
                    @csrf
                    <button type="button" id="truncateVisitorBtn"
                        class="hidden px-3 py-1 text-white transition bg-red-600 rounded-lg md:block md:py-2 hover:bg-red-700">
                        <i class="bi bi-trash me-1"></i> Hapus Data Pengunjung
                    </button>
                </form>
            </div>

            {{-- ===== Grid Online Users ===== --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" id="onlineUsersContainer">
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
                <p class="text-center text-gray-500 col-span-full no-users" style="display: none;">Tidak ada user yang sedang online</p>
            </div>

            {{-- ===== Statistik Visitor ===== --}}
            <div class="p-4 mt-4 bg-white shadow-sm rounded-2xl">
                <div class="flex items-center justify-between mb-4">
                    <p class="pl-2 text-sm font-medium text-gray-500 md:text-base">Statistik Pengunjung <span class="hidden sm:inline">Semua User</span></p>
                    <form method="GET" action="{{ url()->current() }}">
                        {{-- <select name="limit" onchange="this.form.submit()"
                                class="px-3 py-1 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua</option>
                            <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                        </select> --}}
                            <select name="limit" onchange="this.form.submit()" class="px-3 py-1 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                            'admin' => ['bg' => 'bg-blue-600', 'bar' => 'bg-blue-600'],
                            'guru'  => ['bg' => 'bg-purple-600', 'bar' => 'bg-purple-600'],
                            'staff' => ['bg' => 'bg-yellow-600', 'bar' => 'bg-yellow-600'],
                            'siswa' => ['bg' => 'bg-green-600', 'bar' => 'bg-green-600'],
                            'user'  => ['bg' => 'bg-pink-600', 'bar' => 'bg-pink-600'],
                        ];
                    @endphp

                    @foreach($visitsByUser as $user)
                        @php
                            $percent = round(($user->total_visits / $maxVisits) * 100);
                            $color = $roleColors[$user->role] ?? ['bg' => 'bg-gray-600', 'bar' => 'bg-gray-600'];
                        @endphp
                        <li class="flex flex-col p-3 transition rounded-lg bg-gray-50 hover:bg-gray-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-700">
                                    {{ $user->name ?? 'User #' . $user->id }} ({{ ucfirst($user->role) }})
                                </span>
                                <span class="inline-block px-3 py-1 text-sm font-semibold text-white {{ $color['bg'] }} rounded-full">
                                    {{ $user->total_visits }}
                                </span>
                            </div>
                            <div class="w-full h-2 bg-gray-200 rounded-full">
                                <div class="h-2 {{ $color['bar'] }} rounded-full transition-all duration-300"
                                     style="width: {{ $percent }}%"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </main>
    </div>

    <script>
        // SweetAlert Hapus Visitor
        document.getElementById('truncateVisitorBtn').addEventListener('click', function() {
            Swal.fire({
                title: '⚠️ Hapus Semua Data Visitor?',
                text: "Data yang sudah dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('truncateVisitorForm').submit();
                }
            });
        });

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

</x-app-layout>
