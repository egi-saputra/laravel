<x-guest-layout>
    <x-alert />

    <div class="flex flex-col h-screen md:flex-row">

        <!-- Left Panel -->
        <div class="relative flex flex-col bg-gradient-to-r from-[#063970] via-[#0a4c9c] to-[#063970] text-gray-800 items-center justify-center w-full p-8 overflow-hidden text-center md:w-1/2">
            <!-- Subtle animated gradient -->
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>

            <img src="{{ Storage::url('logo_app/login.png') }}" alt="Logo"
                class="relative z-10 w-2/3 mb-6 md:w-1/2 drop-shadow-lg animate-fade-in" />

            <h3 class="relative z-10 mb-3 text-2xl font-bold tracking-wide text-white md:text-3xl animate-fade-in">
                SIMSTAL | {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }}
            </h3>

            <p class="relative z-10 hidden px-8 mt-2 text-base text-gray-100 md:block animate-fade-in">
                Dalam menghadapi era digital 4.0, <span class="font-semibold">SIMSTAL</span> hadir sebagai solusi terpadu
                untuk mendukung transformasi manajemen sekolah berbasis teknologi digital.
            </p>
        </div>

        <!-- Right Panel -->
        <div class="relative flex flex-col items-center justify-center w-full p-6 bg-white md:w-1/2 md:p-12">

            <div class="w-full max-w-md text-start">

                <h4 class="mb-2 text-xl font-bold text-[#063970] md:text-2xl">Sign In</h4>
                <p class="mb-8 text-sm text-slate-600 md:text-base">Nusantara Member Directory</p>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="relative mb-6">
                        <input type="email" id="email" name="email" placeholder=" " required
                            class="peer w-full px-4 py-3 rounded-full border border-gray-300 bg-white/90
                                   focus:ring-2 focus:ring-[#063970] focus:border-[#063970] outline-none transition-all duration-200" />
                        <label for="email"
                            class="absolute left-4 top-3 px-1 bg-white text-gray-500 text-sm transition-all
                                   peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base
                                   peer-focus:-top-3 peer-focus:text-[#063970] peer-focus:text-sm">
                            Email Address
                        </label>
                    </div>

                    <!-- Password -->
                    <div class="relative mb-4">
                        <input type="password" id="password" name="password" placeholder=" " required minlength="6"
                            class="peer w-full px-4 py-3 rounded-full border border-gray-300 bg-white/90
                                   focus:ring-2 focus:ring-[#063970] focus:border-[#063970] outline-none transition-all duration-200" />
                        <label for="password"
                            class="absolute left-4 top-3 px-1 bg-white text-gray-500 text-sm transition-all
                                   peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base
                                   peer-focus:-top-3 peer-focus:text-[#063970] peer-focus:text-sm">
                            Password
                        </label>
                        <button type="button" class="absolute right-4 top-3 text-gray-400 hover:text-[#063970]" onclick="togglePassword()">
                            <i id="togglePasswordIcon" class="bi bi-eye-slash"></i>
                        </button>

                        @if ($errors->any())
                            <div id="login-error" class="p-2 mt-2 text-sm text-red-600 rounded">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between mb-4 text-sm md:text-base">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="mr-2 accent-[#063970]">
                            Remember Me
                        </label>
                        <a href="{{ route('password.request') }}" class="text-[#063970] hover:underline font-semibold">
                            Forgot Password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-3 mb-4 font-semibold text-white bg-[#063970] rounded-full shadow-md
                               hover:bg-[#052b5d] transition-all duration-200">
                        LOGIN
                    </button>

                    <!-- Signup -->
                    <p class="text-sm text-center md:text-base">
                        Don’t have an account?
                        <a href="{{ route('register') }}" class="font-semibold text-[#063970] hover:underline">Sign up</a>
                    </p>

                    <!-- OR -->
                    <div class="flex items-center my-6">
                        <hr class="flex-grow border-gray-300">
                        <span class="mx-3 text-sm font-semibold text-gray-500">OR</span>
                        <hr class="flex-grow border-gray-300">
                    </div>

                    <!-- Google Login -->
                    <a href="{{ route('google.login') }}"
                       class="flex items-center justify-center w-full gap-2 py-3 font-semibold transition-colors border border-gray-300 rounded-full hover:bg-gray-100">
                        <img src="https://img.icons8.com/color/24/000000/google-logo.png" alt="Google" />
                        Continue with Google
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const icon = document.getElementById("togglePasswordIcon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.replace("bi-eye-slash", "bi-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.replace("bi-eye", "bi-eye-slash");
            }
        }

        // Auto hide error alert
        const errorDiv = document.getElementById('login-error');
        if (errorDiv) {
            setTimeout(() => errorDiv.remove(), 4000);
        }
    </script>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.8s ease forwards;
        }
    </style>
</x-guest-layout>
