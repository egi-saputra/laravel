<x-guest-layout>
    <x-alert />

    <div class="flex flex-col min-h-screen md:flex-row">
        <!-- Left Panel -->
        <div class="flex flex-col items-center justify-center w-full p-8 text-center bg-slate-50 md:w-1/2">
            <img src="{{ Storage::url('logo_login/bg_login.png') }}" alt="Logo" class="w-3/4 mt-4 mb-4 md:w-2/3 md:mt-0">
            <h3 class="mt-4 block md:hidden mb-3 text-[#063970] text-xl md:text-2xl font-semibold">
                SIMSTAL | {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }}
            </h3>
            <p class="px-4 hidden md:block text-[#063970] text-justify mb-2 text-sm md:text-base">
                Bergabunglah bersama kami di SIMSTAL {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }}.
                Kelola seluruh progres administrasi, pembelajaran, hingga pekerjaan dengan lebih mudah, cepat, dan terintegrasi. Kami menghadirkan kemudahan akses serta pelayanan digital yang efisien, mendukung terciptanya lingkungan sekolah modern di era digital 4.0.
            </p>
            {{-- <p class="px-4 block md:hidden text-center text-[#063970] mb-4 text-sm md:text-base">
                Bergabunglah bersama kami di SIMSTAL {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }}. Kelola seluruh progres administrasi, pembelajaran, hingga pekerjaan dengan lebih mudah, cepat, dan terintegrasi.
            </p> --}}
        </div>

        <!-- Right Panel -->
        <div class="flex items-center justify-center w-full p-6 mt-14 md:w-1/2 md:p-12 md:mt-0">
            <div class="w-full max-w-md">
                <div class="relative mb-6">
                    <h4 class="mb-2 text-lg font-bold md:text-xl">Sign Up</h4>
                    <p class="mb-10 text-sm text-slate-500 md:text-base">Buat akun Nusantara Member Directory</p>
                </div>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="w-full max-w-md mx-auto">
                    @csrf

                    <!-- Name -->
                    <div class="relative mb-4">
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                            placeholder=" "
                            class="peer w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#063970] focus:border-[#063970]" />
                        <label for="name"
                            class="absolute left-3 top-2 px-1 text-gray-500 bg-white transition-all
                                peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400
                                peer-focus:-top-3 peer-focus:text-[#063970]
                                peer-not-placeholder-shown:-top-3 peer-not-placeholder-shown:text-[#063970]">
                            Name
                        </label>
                        @error('name')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="relative mb-6">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                            placeholder=" "
                            class="peer w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#063970] focus:border-[#063970]" />
                        <label for="email"
                            class="absolute left-3 top-2 px-1 text-gray-500 bg-white transition-all
                                    peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400
                                    peer-focus:-top-3 peer-focus:text-[#063970]
                                    peer-not-placeholder-shown:-top-3 peer-not-placeholder-shown:text-[#063970]">
                            Email Address
                        </label>
                        @error('email')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="relative mb-6">
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder=" "
                            class="peer w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#063970] focus:border-[#063970]" minlength="6" />
                        <label for="password"
                            class="absolute left-3 top-2 px-1 text-gray-500 bg-white transition-all
                                    peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400
                                    peer-focus:-top-3 peer-focus:text-[#063970]
                                    peer-not-placeholder-shown:-top-3 peer-not-placeholder-shown:text-[#063970]">
                            {{-- Password --}}
                        </label>
                        <button type="button" class="absolute text-gray-400 right-3 top-2" onclick="togglePassword('password', 'togglePasswordIcon')">
                            <i id="togglePasswordIcon" class="bi bi-eye"></i>
                        </button>
                        <p class="mt-1 text-xs text-gray-500">Must be at least 6 characters in length</p>
                        @error('password')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="relative mb-3">
                        <input id="password_confirmation" type="password" name="password_confirmation" required placeholder=" "
                            class="peer w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#063970] focus:border-[#063970]" minlength="6" />
                        <label for="password_confirmation"
                            class="absolute left-3 top-2 px-1 text-gray-500 bg-white transition-all
                                    peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400
                                    peer-focus:-top-3 peer-focus:text-[#063970]
                                    peer-not-placeholder-shown:-top-3 peer-not-placeholder-shown:text-[#063970]">
                            Confirm Password
                        </label>
                        <button type="button" class="absolute text-gray-400 right-3 top-2" onclick="togglePassword('password_confirmation', 'toggleConfirmPasswordIcon')">
                            <i id="toggleConfirmPasswordIcon" class="bi bi-eye"></i>
                        </button>
                        @error('password_confirmation')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Links -->
                    <div class="flex items-center justify-start mb-6 font-semibold text-slate-600 ">
                        <small>Already registered?
                            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:underline">Sign in</a>
                        </small>
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-3">
                        <button type="submit"
                                class="w-full py-2 font-semibold text-white transition-colors rounded-full bg-slate-900 hover:bg-slate-800">
                            REGISTER
                        </button>
                    </div>

                    <!-- Terms of service and Privacy Policy -->
                    <div class="flex items-center justify-center mb-3 text-xs font-semibold md:text-sm text-slate-600 ">
                        <small>By creating an account, you agree to our
                            <a href="{{ url('#') }}" class="font-semibold text-blue-600 hover:underline">Terms of Service</a> and <a href="{{ url('#') }}" class="font-semibold text-blue-600 hover:underline">Privacy Policy</a>.
                        </small>
                    </div>
                </form>

                <script>
                    function togglePassword(inputId, iconId) {
                        const passwordInput = document.getElementById(inputId);
                        const icon = document.getElementById(iconId);
                        if (passwordInput.type === "password") {
                            passwordInput.type = "text";
                            icon.classList.replace("bi-eye", "bi-eye-slash");
                        } else {
                            passwordInput.type = "password";
                            icon.classList.replace("bi-eye-slash", "bi-eye");
                        }
                    }

                    document.addEventListener('DOMContentLoaded', () => {
                        const inputs = document.querySelectorAll('input');

                        // Jika ada old input atau error, hilangkan fokus Name
                        const nameInput = document.getElementById('name');
                        if (nameInput.value) {
                            nameInput.blur(); // hilangkan fokus
                        }

                        inputs.forEach(input => {
                            const label = input.nextElementSibling;

                            // Set posisi label sesuai value awal (old input)
                            if (input.value) {
                                label.classList.add('-top-3');
                                label.classList.remove('top-2');
                            }

                            // Event listener
                            input.addEventListener('input', () => {
                                if (input.value) {
                                    label.classList.add('-top-3', 'text-[#063970]', 'text-sm');
                                    label.classList.remove('top-2', 'text-gray-500', 'text-base');
                                } else if (!input.matches(':focus')) {
                                    label.classList.remove('-top-3', 'text-[#063970]', 'text-sm');
                                    label.classList.add('top-2', 'text-gray-500', 'text-base');
                                }
                            });

                            input.addEventListener('focus', () => {
                                label.classList.add('-top-3', 'text-[#063970]', 'text-sm');
                                label.classList.remove('top-2', 'text-gray-500', 'text-base');
                            });

                            input.addEventListener('blur', () => {
                                if (!input.value) {
                                    label.classList.remove('-top-3', 'text-[#063970]', 'text-sm');
                                    label.classList.add('top-2', 'text-gray-500', 'text-base');
                                }
                            });
                        });
                    });
                </script>

            </div>
        </div>
    </div>
</x-guest-layout>

