<x-reg-layout-nologo>
    <div class="md:p-10">
        {{-- <p class="mb-3 font-semibold text-center text-gray-700">Hello, this page is</p> --}}
        <div class="flex">
            <main class="flex items-center mx-auto">
                <div class="flex items-center justify-center">
                    <h1 class="inline-block mb-2 text-base font-bold text-orange-700 md:text-lg">Selamat akun anda berhasil dibuat!</h1>
                </div>
            </main>
        </div>
        <p class="mb-8 text-xs font-semibold text-center text-slate-600">Hubungi admin untuk mengaktifkan akun</p>
        <div class="font-semibold text-center hover:text-slate-900 text-slate-700">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                ⇽ Logout
            </a>
        </div>
    </div>
</x-reg-layout-nologo>
