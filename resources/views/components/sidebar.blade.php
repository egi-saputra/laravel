@auth
    @if(auth()->user()->role === 'developer')
        <x-sidebar.sidebar-dev />
    @elseif(auth()->user()->role === 'admin')
        <x-sidebar.sidebar-admin />
    @elseif(auth()->user()->role === 'guru')
        <x-sidebar.sidebar-guru />
    @elseif(auth()->user()->role === 'staff')
        <x-sidebar.sidebar-staff />
    @elseif(auth()->user()->role === 'siswa')
        <x-sidebar.sidebar-siswa />
    @elseif(auth()->user()->role === 'user')
        <x-sidebar.sidebar-user />
    @endif
@endauth

