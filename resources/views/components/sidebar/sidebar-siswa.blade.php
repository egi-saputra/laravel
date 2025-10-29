@php
use Illuminate\Support\Facades\Auth;
use App\Models\DataSiswa;

$user = Auth::user();
$role = $user->role;

// Ambil data siswa terkait user login
$siswa = DataSiswa::where('user_id', $user->id)->first();
$jabatan = $siswa->jabatan_siswa ?? 'Tidak Ada';

$menus = [
    'default' => [
        ['label' => 'Upload Foto Profil', 'type' => 'modal', 'icon' => 'bi-cloud-upload'],
        ['label' => 'Lengkapi Data Diri', 'route' => route('siswa.data_diri'), 'icon'  => 'bi-person-lines-fill'],
        // ['label' => 'Materi Pembelajaran', 'route' => route('siswa.materi.index'), 'icon'  => 'bi-clipboard-check'],
        // ['label' => 'Upload Tugas Belajar', 'route' => route('siswa.tugas.index'), 'icon'  => 'bi-upload'],
    ],
];

// Menu khusus jabatan
if ($jabatan === 'Sekretaris') {
    $menus['jabatan'][] = [
        'label' => 'Ruang Absensi Kelas',
        'route' => route('siswa.presensi.index'),
        'icon'  => 'bi-file-earmark-text',
    ];
}

// if ($jabatan === 'Bendahara') {
//     $menus['jabatan'][] = [
//         'label' => 'Uang Kas Kelas (Bendahara)',
//         'route' => route('siswa.rekap_kas'),
//         'icon'  => 'bi-cash-stack',
//     ];
// }

$datas = [
    // ['label' => 'Daftar Peserta Didik', 'route' => route('public.daftar_siswa.index'), 'icon' => 'bi-people'],
    ['label' => 'Jadwal Guru Mengajar', 'route' => route('public.jadwal_guru.index'), 'icon' => 'bi-clock-history'],
    ['label' => 'Jadwal Mata Pelajaran', 'route' => route('public.jadwal_mapel.index'), 'icon' => 'bi-journal-bookmark'],
    ['label' => 'Jadwal Guru Piket', 'route' => route('public.jadwal_piket.index'), 'icon' => 'bi-clipboard2-check'],
];

$accounts = [
    ['label' => 'Edit Data Profil', 'route' => route('profile.edit'), 'icon' => 'bi-person'],
    ['label' => 'Ubah Password', 'route' => route('profile.password'), 'icon' => 'bi-key'],
    ['label' => 'Hapus Akun', 'route' => route('profile.delete'), 'icon' => 'bi-trash'],
    // ['label' => 'Logout', 'route' => route('logout'), 'type'  => 'form', 'icon' => 'bi-box-arrow-right'],
];
@endphp

        <!-- SIDEBAR + MODAL -->
        <div x-data="{ open: false, showMenu: false }">

            <!-- SIDEBAR -->
            <div class="w-full m-0 mb-2 bg-white rounded shadow md:mb-0 sm:pb-5">

                {{-- SIDEBAR PROFIL --}}
                <div class="flex flex-col items-center p-4 pt-4">
                    <div class="w-full h-32 rounded shadow-sm bg-sky-800"></div>

                    {{-- @php
                        $foto = Auth::user()->foto_profil;
                        $fotoUrl = $foto
                            ? route('foto-profil') . '?v=' . ($foto->updated_at?->timestamp ?? time())
                            : route('foto-profil') . '?v=' . time(); // fallback default
                    @endphp

                    <img src="{{ $fotoUrl }}"
                        alt="Foto Profil"
                        class="w-24 h-24 rounded-full -mt-14 drop-shadow-md"> --}}

                    @php
                        $user = Auth::user(); // ambil user yang login
                        $fotoUrl = $user->foto_profil
                            ? Storage::url($user->foto_profil->file_path)
                            : asset('storage/default/avatar.jpeg');
                    @endphp

                    <img src="{{ $fotoUrl }}"
                        alt="{{ $user->name }}"
                        class="w-24 h-24 rounded-full -mt-14 drop-shadow-md">


                    <div class="mt-2 mb-4 text-center md:mb-0">
                        <p class="text-sm font-semibold text-gray-700">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- SIDEBAR MENU -->
                <div
                    x-ref="menu"
                    x-bind:style="showMenu ? 'max-height:' + $refs.menu.scrollHeight + 'px' : 'max-height:0'"
                    class="pb-4 mx-8 hidden overflow-hidden transition-all duration-500 ease-in-out
                        md:!max-h-none md:overflow-visible md:block"
                >

                {{-- USER MANAGEMENT --}}
                @isset($menus)
                    <hr class="w-full mb-2">
                    <small class="flex items-center justify-between mt-5">Sistem Manajemen</small>

                    @foreach ($menus as $group => $items)
                        <ul class="text-slate-500">
                            @foreach ($items as $menu)
                                @php $type = $menu['type'] ?? 'link'; @endphp

                                @if ($type === 'form')
                                    <form method="POST" action="{{ $menu['route'] }}">
                                        @csrf
                                        <button type="submit" class="w-full px-2 py-2 text-left rounded hover:bg-gray-100">
                                            <i class="bi {{ $menu['icon'] ?? 'bi-dot' }} me-2"></i>{{ $menu['label'] }}
                                        </button>
                                    </form>
                                @elseif ($type === 'modal')
                                    <li>
                                        <button @click="$dispatch('open-modal-upload-foto')" class="w-full px-2 py-2 text-left rounded hover:bg-gray-100">
                                            <i class="bi {{ $menu['icon'] ?? 'bi-dot' }} me-2"></i>{{ $menu['label'] }}
                                        </button>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $menu['route'] ?? '#' }}" class="block px-2 py-2 rounded hover:bg-gray-100">
                                            <i class="bi {{ $menu['icon'] ?? 'bi-dot' }} me-2"></i>{{ $menu['label'] }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endforeach
                @endisset

                {{-- DATA --}}
                <small class="flex items-center justify-between mt-5">Informasi Umum</small>
                <ul class="text-slate-500">
                    @foreach ($datas as $data)
                        <li>
                            <a href="{{ $data['route'] }}" class="block px-2 py-2 rounded hover:bg-gray-100">
                                <i class="bi {{ $data['icon'] ?? 'bi-dot' }} me-2"></i>{{ $data['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- ACCOUNT --}}
                <p class="mt-5"><small>Pengaturan Akun</small></p>
                <ul class="text-slate-500">
                    @foreach ($accounts as $account)
                        @php $type = $account['type'] ?? 'link'; @endphp
                        @if ($type === 'form')
                            <form method="POST" action="{{ $account['route'] }}">
                                @csrf
                                <button type="submit" class="w-full px-2 py-2 text-left rounded hover:bg-gray-100">
                                    <i class="bi {{ $account['icon'] ?? 'bi-dot' }} me-2"></i>{{ $account['label'] }}
                                </button>
                            </form>
                        @else
                            <li>
                                <a href="{{ $account['route'] }}" class="block px-2 py-2 rounded hover:bg-gray-100">
                                    <i class="bi {{ $account['icon'] ?? 'bi-dot' }} me-2"></i>{{ $account['label'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            {{-- </div> --}}
            </div>
        </div>

    <!-- MODAL -->
    <x-modal-upload-foto />

</div>
