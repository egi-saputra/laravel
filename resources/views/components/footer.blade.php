{{-- resources/views/components/footer.blade.php --}}
<footer class="hidden py-6 -ml-5 text-gray-800 bg-transparent md:block">
    <div class="container flex flex-col justify-between px-0 mx-auto md:px-6 md:mx-0 md:flex-row">
        {{-- Nama Sekolah --}}
        <div class="text-center md:text-left">
            <h3 class="text-lg font-semibold">{{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }}</h3>
            <p class="text-sm text-gray-500">© {{ date('Y') }} Semua hak cipta dilindungi.</p>
        </div>
    </div>
</footer>
