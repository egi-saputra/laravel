<x-guest-layout>
    <x-alert />
    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Left Panel -->
        <div class="flex flex-col justify-center items-center w-full md:w-1/2 text-center bg-[#063970] p-8">
            <img src="{{ Storage::url('logo_login/login.png') }}" alt="Logo" class="w-3/4 mt-0 mb-4 md:w-1/2">

            <h3 class="mb-3 text-xl font-semibold text-white md:text-2xl">
                SIMSTAL | {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }}
            </h3>
            <p class="hidden px-4 text-sm text-center text-white md:block md:text-base">Dalam menghadapi era digital 4.0, SIMSTAL hadir sebagai solusi terpadu untuk mendukung transformasi manajemen sekolah berbasis teknologi digital.
            </p>
        </div>

        <!-- Right Panel -->
        <div class="flex flex-col w-full p-8 bg-white md:justify-center md:items-center md:w-1/2">

            <div class="w-full max-w-md">
                <h4 class="mb-2 text-lg font-bold capitalize md:text-xl">Sign In</h4>
                <p class="mb-10 text-sm capitalize text-slate-500 md:text-base">Nusantara Member Directory</p>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="relative mb-4">
                        <input type="email" id="email" name="email" placeholder=" " required
                            class="w-full px-3 py-2 border border-gray-300 rounded-full peer
                                    focus:outline-none focus:ring-1 focus:ring-[#063970]
                                    focus:border-[#063970]
                                    valid:border-[#063970] valid:ring-1 valid:ring-[#063970]" />

                        <label for="email"
                            class="absolute left-5 px-1 text-sm text-gray-500 bg-white transition-all
                                    peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base
                                    peer-focus:-top-3 peer-focus:text-[#063970] peer-focus:text-sm
                                    peer-valid:-top-3 peer-valid:text-[#063970] peer-valid:text-sm">
                            Email Address
                        </label>

                        <!-- dropdown suggestion -->
                        {{-- <ul id="email-suggestions" class="absolute z-10 hidden w-full mt-1 bg-white border border-gray-300 rounded-lg"></ul> --}}
                    </div>

                    <!-- Password -->
                    {{-- <div class="relative mb-4">
                        <input type="password" id="password" name="password" placeholder=" " required
                            class="w-full px-3 py-2 border border-gray-300 rounded peer
                                    focus:outline-none focus:ring-1 focus:ring-[#063970]
                                    focus:border-[#063970]
                                    valid:border-[#063970] valid:ring-1 valid:ring-[#063970]" />
                        <label for="password"
                            class="absolute left-3 px-1 text-sm text-gray-500 bg-white transition-all
                                    peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base
                                    peer-focus:-top-3 peer-focus:text-[#063970] peer-focus:text-sm
                                    peer-valid:-top-3 peer-valid:text-[#063970] peer-valid:text-sm">
                            Password
                        </label>
                        <button type="button" class="absolute text-gray-400 right-3 top-2" onclick="togglePassword()">
                            <i id="togglePasswordIcon" class="bi bi-eye"></i>
                        </button>
                    </div> --}}
                    <!-- Password -->
                    <div class="relative mb-4">
                        <input type="password" id="password" name="password" placeholder=" " required minlength="6"
                            class="peer w-full px-3 py-2 border border-gray-300 rounded-full
                                    focus:outline-none focus:ring-1 focus:ring-[#063970] focus:border-[#063970]" />
                        <label for="password"
                            class="absolute left-5 px-1 text-sm text-gray-500 bg-white transition-all
                                    peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base
                                    peer-focus:-top-3 peer-focus:text-[#063970] peer-focus:text-sm
                                    peer-valid:-top-3 peer-valid:text-[#063970] peer-valid:text-sm">
                            Password
                        </label>
                        <button type="button" class="absolute text-gray-400 right-5 top-2" onclick="togglePassword()">
                            <i id="togglePasswordIcon" class="bi bi-eye-slash"></i>
                        </button>
                        <!-- Alert error login -->
                        @if ($errors->any())
                            <div id="login-error" class="p-1 mb-2 text-sm text-red-700 rounded">
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                            </div>
                        @endif
                    </div>


                    <!-- Forgot Password -->
                    <div class="mb-4 text-sm text-left md:text-base">
                        <small class="flex items-center gap-1">
                            <i class="bi bi-lock"></i>
                            Forgot your password?
                            <a href="{{ route('password.request') }}" class="ml-1 font-semibold text-blue-600 hover:underline">Click Here!</a>
                        </small>
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-3">
                        <button type="submit" class="w-full py-2 font-semibold text-white transition-colors bg-[#063970] rounded-full hover:bg-slate-900">
                            LOG IN
                        </button>
                    </div>

                    <!-- Links -->
                    <div class="mb-4 text-sm text-center md:text-base">
                        <small>Don't have an account?
                            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:underline">Sign up</a>
                        </small>
                    </div>

                    <!-- OR Separator -->
                    <div class="items-center hidden my-4 md:flex">
                        <hr class="flex-grow border-gray-300">
                        <span class="mx-3 text-sm font-semibold text-black md:text-base">OR</span>
                        <hr class="flex-grow border-gray-300">
                    </div>

                    <!-- Google Login -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('google.login') }}"
                           class="flex items-center justify-center w-full gap-2 py-2 font-semibold transition-colors border border-gray-300 rounded-full hover:bg-gray-100 hover:border-[#063970]">
                            <img src="https://img.icons8.com/color/20/000000/google-logo.png" alt="Google" />
                            Log in with Google
                        </a>
                    </div>
                </form>

                <script>
                    const emails = ["user@example.com", "staff@example.com", "guru@example.com", "siswa@example.com", "admin@example.com", "support@website.com"];
                    const input = document.getElementById("email");
                    const suggestionBox = document.getElementById("email-suggestions");

                    input.addEventListener("input", function() {
                        const value = this.value.toLowerCase();
                        suggestionBox.innerHTML = "";
                        if (!value) {
                            suggestionBox.classList.add("hidden");
                            return;
                        }

                        const filtered = emails.filter(email => email.toLowerCase().includes(value));
                        if (filtered.length === 0) {
                            suggestionBox.classList.add("hidden");
                            return;
                        }

                        filtered.forEach(email => {
                            const li = document.createElement("li");
                            li.textContent = email;
                            li.className = "px-3 py-2 cursor-pointer hover:bg-gray-100";
                            li.onclick = () => {
                                input.value = email;
                                suggestionBox.classList.add("hidden");
                            };
                            suggestionBox.appendChild(li);
                        });

                        suggestionBox.classList.remove("hidden");
                    });

                    document.addEventListener("click", (e) => {
                        if (!e.target.closest("#email")) {
                            suggestionBox.classList.add("hidden");
                        }
                    });

                    // Hilangkan alert error otomatis setelah 10 detik
                    const errorDiv = document.getElementById('login-error');
                    if (errorDiv) {
                        setTimeout(() => {
                            errorDiv.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                            setTimeout(() => errorDiv.remove(), 500); // Hapus element setelah fade out
                        }, 3000); // 10000ms = 10 detik
                    }

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

                    // Floating label JS
                    const inputs = document.querySelectorAll('input');
                    inputs.forEach(input => {
                        const label = input.nextElementSibling;

                        // Set initial state
                        if (input.value) {
                            label.classList.add('-top-3', 'text-[#063970]', 'text-sm');
                            label.classList.remove('top-2', 'text-gray-500', 'text-base');
                        }

                        // On input / blur
                        input.addEventListener('input', () => {
                            if (input.value) {
                                label.classList.add('-top-3', 'text-[#063970]', 'text-sm');
                                label.classList.remove('top-2', 'text-gray-500', 'text-base');
                            } else if (!input.matches(':focus')) {
                                // Hanya turunkan label jika input kosong & tidak fokus
                                label.classList.remove('-top-3', 'text-[#063970]', 'text-sm');
                                label.classList.add('top-2', 'text-gray-500', 'text-base');
                            }
                        });

                        // On focus
                        input.addEventListener('focus', () => {
                            label.classList.add('-top-3', 'text-[#063970]', 'text-sm');
                            label.classList.remove('top-2', 'text-gray-500', 'text-base');
                        });

                        // On blur
                        input.addEventListener('blur', () => {
                            if (!input.value) {
                                label.classList.remove('-top-3', 'text-[#063970]', 'text-sm');
                                label.classList.add('top-2', 'text-gray-500', 'text-base');
                            }
                        });
                    });
                </script>

            </div>
        </div>
    </div>
</x-guest-layout>
