{{-- resources/views/components/footer.blade.php --}}
<footer class="hidden py-6 text-gray-800 bg-slate-100 md:block">
    <div class="container flex flex-col justify-between px-0 mx-auto md:px-6 md:mx-0 md:flex-row">
        {{-- Nama Sekolah --}}
        <div class="text-center md:text-left">
            <h3 class="text-lg font-semibold">{{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }}</h3>
            <p class="text-sm text-gray-500">© {{ date('Y') }} Semua hak cipta dilindungi.</p>
        </div>

        {{-- Navigasi Footer (Opsional) --}}
        {{-- <div class="flex justify-center space-x-4 md:justify-end">
            <p class="text-gray-500"><a href="{{ url('/') }}" class="text-gray-500 transition hover:text-gray-800">Terms Service</a> &
            <a href="{{ url('/') }}" class="text-gray-500 transition hover:text-gray-800">Privacy</a></p>
        </div> --}}
    </div>
</footer>
