<x-reg-layout-nologo>
    <div class="flex flex-col items-center justify-center px-6 py-10 text-center rounded-md bg-gradient-to-br from-orange-100 via-white to-orange-200">

        <!-- Judul besar -->
        <h1 class="text-5xl font-extrabold text-transparent md:text-7xl bg-clip-text bg-gradient-to-r from-orange-600 to-amber-500 animate-pulse drop-shadow-sm">
            COMING SOON!
        </h1>

        <!-- Garis bawah animatif -->
        <div class="w-24 h-1 mt-4 mb-8 rounded-full md:w-40 bg-gradient-to-r from-orange-500 to-amber-400 animate-pulse"></div>

        <!-- Deskripsi kecil -->
        <p class="max-w-lg mb-12 text-lg font-medium md:text-xl text-slate-700">
            Fitur ini sedang dalam tahap pengembangan.<br><br>
            Nantikan update berikutnya, ya! 🚀
        </p>

        <!-- Tombol kembali -->
        <a href="{{ route('staff.dashboard') }}"
           class="inline-flex items-center gap-2 px-6 py-3 text-lg font-semibold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-orange-600 to-amber-500 hover:scale-105 hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>

        <!-- Footer kecil -->
        {{-- <p class="mt-10 text-sm text-gray-500">
            © {{ date('Y') }} Simstal. All rights reserved.
        </p> --}}
    </div>
</x-reg-layout-nologo>
