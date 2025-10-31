<!-- Bottom Navigation (Mobile Only) -->
<div id="navhp"
    class="fixed bottom-3 left-1/2 z-50 flex w-[92%] max-w-md -translate-x-1/2 justify-around rounded-2xl bg-white/80 backdrop-blur-md border border-orange-200 shadow-xl py-2 px-2 md:hidden transition-all duration-300 ease-in-out">

    <!-- Dashboard -->
    <a href="{{ route($role . '.dashboard') }}"
        class="flex flex-col items-center justify-center px-2 py-1 nav-icon {{ request()->routeIs($role.'.dashboard') ? 'active' : '' }}">
        <i class="text-xl transition-colors duration-300 fas fa-layer-group"></i>
        <span class="mt-1 text-[10px] font-semibold">Beranda</span>
    </a>

    <!-- Siswa -->
    <a href="{{ route('public.daftar_siswa.index') }}"
        class="flex flex-col items-center justify-center px-2 py-1 nav-icon {{ request()->routeIs('public.daftar_siswa.*') ? 'active' : '' }}">
        <i class="text-xl transition-colors duration-300 fas fa-user-graduate"></i>
        <span class="mt-1 text-[10px] font-semibold">Siswa</span>
    </a>

    <!-- Sekolah -->
    <a href="{{ route('public.informasi_sekolah.index') }}"
        class="flex flex-col items-center justify-center px-2 py-1 nav-icon {{ request()->routeIs('public.informasi_sekolah.*') ? 'active' : '' }}">
        <i class="text-xl transition-colors duration-300 fas fa-school"></i>
        <span class="mt-1 text-[10px] font-semibold">Sekolah</span>
    </a>

    @if(auth()->user()->role === 'guru')
        <!-- Materi -->
        <a href="{{ route('guru.materi.index') }}"
            class="flex flex-col items-center justify-center px-2 py-1 nav-icon {{ request()->routeIs('guru.materi.*') ? 'active' : '' }}">
            <i class="text-xl transition-colors duration-300 fas fa-book"></i>
            <span class="mt-1 text-[10px] font-semibold">Materi</span>
        </a>

        <!-- Tugas -->
        <a href="{{ route('guru.tugas_siswa.index') }}"
            class="flex flex-col items-center justify-center px-2 py-1 nav-icon {{ request()->routeIs('guru.tugas_siswa.*') ? 'active' : '' }}">
            <i class="text-xl transition-colors duration-300 fas fa-tasks"></i>
            <span class="mt-1 text-[10px] font-semibold">Tugas</span>
        </a>

    @elseif(auth()->user()->role === 'staff')
        <!-- Jadwal -->
        <a href="{{ route('public.card_jadwal_guru.index') }}"
            class="flex flex-col items-center justify-center px-2 py-1 nav-icon {{ request()->routeIs('public.card_jadwal_guru.*') ? 'active' : '' }}">
            <i class="text-xl transition-colors duration-300 bi bi-calendar2-week-fill"></i>
            <span class="mt-1 text-[10px] font-semibold">Jadwal</span>
        </a>

        <!-- Jam -->
        <a href="{{ route('public.jumlah_jam.index') }}"
            class="flex flex-col items-center justify-center px-2 py-1 nav-icon {{ request()->routeIs('public.jumlah_jam.*') ? 'active' : '' }}">
            <i class="text-xl transition-colors duration-300 bi bi-clock-fill"></i>
            <span class="mt-1 text-[10px] font-semibold">Jam</span>
        </a>

    @else
        <!-- Materi -->
        <a href="{{ route('siswa.materi.index') }}"
            class="flex flex-col items-center justify-center px-2 py-1 nav-icon {{ request()->routeIs('siswa.materi.*') ? 'active' : '' }}">
            <i class="text-xl transition-colors duration-300 fas fa-book"></i>
            <span class="mt-1 text-[10px] font-semibold">Materi</span>
        </a>

        <!-- Tugas -->
        <a href="{{ route('siswa.tugas.index') }}"
            class="flex flex-col items-center justify-center px-2 py-1 nav-icon {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
            <i class="text-xl transition-colors duration-300 fas fa-tasks"></i>
            <span class="mt-1 text-[10px] font-semibold">Tugas</span>
        </a>
    @endif
</div>
