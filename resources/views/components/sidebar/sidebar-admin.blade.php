@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();

    $menus = [
            ['label' => 'Upload Foto Profil', 'type' => 'modal', 'icon' => 'bi-upload'],
            ['label' => 'Daftar Pengguna', 'route' => route('admin.users.index'), 'icon' => 'bi-people'],
            ['label' => 'Struktural Sekolah', 'route' => route('admin.struktural.index'), 'icon' => 'bi-building'], // bangunan / sekolah
            ['label' => 'Program Kejuruan', 'route' => route('admin.kejuruan.index'), 'icon' => 'bi-briefcase'], // koper / pekerjaan
            ['label' => 'Jadwal Piket Guru', 'route' => route('admin.jadwal.index'), 'icon' => 'bi-clipboard-check'],
            ['label' => 'Jadwal Mengajar', 'route' => route('admin.jadwal_guru.index'), 'icon' => 'bi-clipboard2-check'],
            ['label' => 'Kelola Hak Akses', 'route' => route('admin.akses.index'), 'icon' => 'bi-shield-check'],
            // ['label' => 'Buat Pengumuman', 'route' => route('public.pengumuman.index'), 'icon' => 'bi-megaphone'],
        ];

    // $applications = [
    //     ['label' => 'Konfigurasi Halaman Login', 'route' => route('admin.login'), 'icon' => 'bi-calendar-check'],
    //     ['label' => 'Konfigurasi Halaman Register', 'route' => route('admin.register'), 'icon' => 'bi-graph-up'],
    // ];

    $datas = [
        ['label' => 'Kelola Daftar Guru', 'route' => route('admin.guru.index'), 'icon' => 'bi-people'], // banyak orang / guru
        ['label' => 'Kelola Data Mapel', 'route' => route('admin.mapel.index'), 'icon' => 'bi-journal-bookmark'], // buku / mapel
        ['label' => 'Kelola Data Kelas', 'route' => route('admin.kelas.index'), 'icon' => 'bi-person-lines-fill'], // ikon kelas / daftar siswa
        ['label' => 'Kelola Data Ekskul', 'route' => route('admin.ekskul.index'), 'icon' => 'bi-trophy'], // trofi / ekskul
        ['label' => 'Kelola Data Siswa', 'route' => route('admin.siswa.index'), 'icon' => 'bi-mortarboard'], // topi wisuda / siswa
    ];

    $accounts = [
            ['label' => 'Edit Data Profil', 'route' => route('profile.edit'), 'icon' => 'bi-person'],
            ['label' => 'Ubah Password', 'route' => route('profile.password'), 'icon' => 'bi-key'],
            ['label' => 'Hapus Akun', 'route' => route('profile.delete'), 'icon' => 'bi-trash'],
        ];

    $accounts[] = [
            'label' => 'Logout',
            'route' => route('logout'),
            'type'  => 'form',
            'icon' => 'bi-box-arrow-right',
        ];
@endphp

<!-- SIDEBAR + MODAL -->
<div x-data="{ open: false, showMenu: false }">

    <!-- SIDEBAR -->
    <div class="z-30 w-full m-0 bg-white border-none rounded-md shadow-md md:border-r md:rounded sm:pb-5">

        {{-- SIDEBAR PROFIL --}}
        <div class="flex flex-col items-center p-4 pt-4">
            <div class="w-full h-32 rounded shadow-sm bg-sky-800"></div>

            @php
                $foto = Auth::user()->foto_profil;
                $fotoUrl = $foto
                    ? route('foto-profil') . '?v=' . ($foto->updated_at?->timestamp ?? time())
                    : route('foto-profil') . '?v=' . time(); // fallback default
            @endphp

            <img src="{{ $fotoUrl }}"
                alt="Foto Profil"
                class="w-24 h-24 rounded-full -mt-14 drop-shadow-md">


            <div class="mt-2 text-center">
                <p class="text-sm font-semibold text-gray-700">{{ $user->name }}</p>
                <p class="text-xs text-gray-500 capitalize">{{ $user->email }}</p>
            </div>
        </div>

        <!-- TOGGLE MENU - MOBILE -->
        <div class="flex items-center justify-between px-4 py-2 border-b md:hidden">
            <span class="text-sm font-semibold text-gray-600">Menu</span>
            <button @click="showMenu = !showMenu" class="text-gray-600 focus:outline-none">
                <template x-if="!showMenu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </template>
                <template x-if="showMenu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </template>
            </button>
        </div>

        {{-- MENU - DESKTOP + TOGGLE --}}
        <div
            x-ref="menu"
            x-bind:style="showMenu ? 'max-height:' + $refs.menu.scrollHeight + 'px' : 'max-height:0'"
            class="pb-4 mx-8 overflow-hidden transition-all duration-500 ease-in-out
                md:!max-h-none md:overflow-visible md:block"
        >
        {{-- <div
            x-ref="menu"
            :class="showMenu ? 'opacity-100' : 'opacity-0 hidden'"
            class="pb-4 mx-8 transition-all duration-500 ease-in-out md:opacity-100 md:block"
            x-cloak
        > --}}
            {{-- USER MANAGEMENT --}}
            <hr class="w-full mb-2">
            <small class="flex items-center justify-between mt-5">
                Management System
            </small>
            <ul class="text-slate-500">
                @foreach ($menus as $menu)
                    @if (isset($menu['type']) && $menu['type'] === 'form')
                        <form method="POST" action="{{ $menu['route'] }}">
                            @csrf
                            <button type="submit" class="w-full px-2 py-2 text-left rounded hover:bg-gray-100">
                                <i class="bi {{ $menu['icon'] ?? 'bi-dot' }} me-2"></i>{{ $menu['label'] }}
                            </button>
                        </form>
                    @elseif(isset($menu['type']) && $menu['type'] === 'modal')
                        <li>
                            <button @click="open = true" class="w-full px-2 py-2 text-left rounded hover:bg-gray-100">
                                <i class="bi {{ $menu['icon'] ?? 'bi-dot' }} me-2"></i>{{ $menu['label'] }}
                            </button>
                        </li>
                    @else
                        <li>
                            <a href="{{ $menu['route'] }}" class="block px-2 py-2 rounded hover:bg-gray-100">
                                <i class="bi {{ $menu['icon'] ?? 'bi-dot' }} me-2"></i>{{ $menu['label'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            {{-- DATAS MANAGEMENT --}}
            <small class="flex items-center justify-between mt-5">Archives Data</small>
            <ul class="text-slate-500">
                @foreach ($datas as $data)
                    <li>
                        <a href="{{ $data['route'] }}" class="block px-2 py-2 rounded hover:bg-gray-100">
                            <i class="bi {{ $data['icon'] ?? 'bi-dot' }} me-2"></i>{{ $data['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- APPLICATION MANAGEMENT --}}
            {{-- <small class="flex items-center justify-between mt-5">Application Management</small>
            <ul class="text-slate-500">
                @foreach ($applications as $application)
                    <li>
                        <a href="{{ $application['route'] }}" class="block px-2 py-2 rounded hover:bg-gray-100">
                            <i class="bi {{ $application['icon'] ?? 'bi-dot' }} me-2"></i>{{ $application['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul> --}}

            {{-- ACCOUNT SETTINGS --}}
            <p class="mt-5"><small>Account Setting</small></p>
            <ul class="text-slate-500">
                @foreach ($accounts as $account)
                    @if (isset($account['type']) && $account['type'] === 'form')
                        <form method="POST" action="{{ $account['route'] }}">
                            @csrf
                            <button type="submit" class="w-full px-2 py-2 text-left rounded hover:bg-gray-100">
                                <i class="bi {{ $account['icon'] ?? 'bi-dot' }} me-2"></i>{{ $account['label'] }}
                            </button>
                        </form>
                    @elseif(isset($account['type']) && $account['type'] === 'modal')
                        <li>
                            <button @click="open = true" class="w-full px-2 py-2 text-left rounded hover:bg-gray-100">
                                <i class="bi {{ $account['icon'] ?? 'bi-dot' }} me-2"></i>{{ $account['label'] }}
                            </button>
                        </li>
                    @else
                        <li>
                            <a href="{{ $account['route'] }}" class="block px-2 py-2 rounded hover:bg-gray-100">
                                <i class="bi {{ $account['icon'] ?? 'bi-dot' }} me-2"></i>{{ $account['label'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

    </div>

    <!-- MODAL -->
    <x-modal-upload-foto />

</div>
