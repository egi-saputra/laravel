<!-- Bottom Navigation (Mobile Only - Icon + Text) -->
            <div id="navhp" class="fixed bottom-0 left-0 right-0 z-50 flex justify-around py-2 text-xs bg-white border-t shadow md:hidden">

                <!-- Home/Dashboard -->
                <a href="{{ route($role . '.dashboard') }}"
                class="flex flex-col items-center nav-icon {{ request()->routeIs($role.'.dashboard') ? 'active' : '' }}">
                {{-- <i class="text-lg fas fa-chart-line"></i> --}}
                <i class="text-lg fas fa-layer-group"></i>
                {{-- <i class="text-lg fas fa-calendar-check"></i> --}}
                {{-- <i class="text-lg bi bi-grid"></i> --}}
                <small class="text-xs font-semibold">Beranda</small>
                </a>

                {{-- @if(auth()->user()->role === 'staff')
                    <!-- Riwayat Presensi untuk Staff -->
                    <a href="{{ route('staff.riwayat_presensi.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('staff.riwayat_presensi.*') ? 'active' : '' }}">
                        <i class="text-lg fas fa-calendar-check"></i>
                        <small class="text-xs font-semibold">Presensi</small>
                    </a>
                @else --}}
                    <!-- Siswa untuk semua selain staff -->
                    <a href="{{ route('public.daftar_siswa.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('public.daftar_siswa.*') ? 'active' : '' }}">
                        <i class="text-lg fas fa-user-graduate"></i>
                        <small class="text-xs font-semibold">Siswa</small>
                    </a>
                {{-- @endif --}}

                <!-- Informasi Sekolah -->
                <a href="{{ route('public.informasi_sekolah.index') }}" class="flex flex-col items-center nav-icon {{ request()->routeIs('public.informasi_sekolah.*') ? 'active' : '' }}">
                    <i class="text-lg fas fa-school"></i>
                    <small class="text-xs font-semibold">Sekolah</small>
                </a>

                @if(auth()->user()->role === 'guru')
                    <!-- Materi -->
                    <a href="{{ route('guru.materi.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('guru.materi.*') ? 'active' : '' }}">
                        <i class="text-lg fas fa-book"></i>
                        <small class="text-xs font-semibold">Materi</small>
                    </a>

                    <!-- Tugas -->
                    <a href="{{ route('guru.tugas_siswa.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('guru.tugas_siswa.*') ? 'active' : '' }}">
                        <i class="text-lg fas fa-tasks"></i>
                        <small class="text-xs font-semibold">Tugas</small>
                    </a>
                @elseif(auth()->user()->role === 'staff')
                    <!-- Rekap Honor Guru -->
                    <a href="{{ route('public.card_jadwal_guru.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('public.card_jadwal_guru.*') ? 'active' : '' }}">
                        <i class="text-lg bi bi-calendar2-week-fill"></i>
                        <small class="text-xs font-semibold">Jadwal</small>
                    </a>

                    <!-- Rekap Honor Staff -->
                    <a href="{{ route('public.jumlah_jam.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('public.jumlah_jam.*') ? 'active' : '' }}">
                        <i class="text-lg bi bi-clock-fill"></i>
                        <small class="text-xs font-semibold">Jam</small>
                    </a>
                @else
                    <!-- Siswa: Materi -->
                    <a href="{{ route('siswa.materi.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
                        <i class="text-lg fas fa-book"></i>
                        <small class="text-xs font-semibold">Materi</small>
                    </a>

                    <!-- Siswa: Tugas -->
                    <a href="{{ route('siswa.tugas.index') }}"
                    class="flex flex-col items-center nav-icon {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
                        <i class="text-lg fas fa-tasks"></i>
                        <small class="text-xs font-semibold">Tugas</small>
                    </a>
                @endif

            </div>
