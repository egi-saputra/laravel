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

            <div class="flex justify-center">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-white transition duration-200 bg-red-600 rounded shadow md:rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-red-500">
                        ⇽ Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-reg-layout-nologo>
