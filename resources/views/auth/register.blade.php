<x-guest-layout>
    <x-alert />

    <div class="flex flex-col min-h-screen mx-6 md:mx-0 md:flex-row">
        <!-- Left Panel -->
        <div class="relative flex-col items-center justify-center hidden w-full p-8 text-center bg-white md:flex md:w-1/2">

            <!-- Logo & App Name di pojok kanan atas -->
            <div class="absolute flex items-center gap-2 top-4 left-4">
                <img src="{{ Storage::url('logo_app/logo.png') }}" alt="Logo App" class="w-8 h-8">
                <span class="font-semibold capitalize font-poppins text-slate-600">{{ env('APP_NAME', 'MyApp') }}</span>
            </div>

            <img src="{{ Storage::url('logo_app/register.png') }}" alt="Logo" class="w-3/4 mt-4 mb-4 md:w-2/3 md:mt-0">
            <h3 class="mt-4 mb-3 md:hidden block text-[#063970] text-xl md:text-2xl font-semibold">
                SIMSTAL | {{ $profil->nama_sekolah ?? 'Nama Sekolah Belum Diset' }}
            </h3>
        </div>

        <!-- Right Panel -->
        <div class="flex items-center justify-center w-full my-auto bg-white md:my-0 md:w-1/2">
            <div class="w-full max-w-md">
                <div class="relative mb-6">
                    <h4 class="mb-2 text-lg font-bold capitalize md:text-xl">Sign Up</h4>
                    <p class="mb-10 text-sm capitalize text-slate-500 md:text-base">Buat Akun Nusantara Member Directory</p>
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
                            Your Name
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
                            New Password
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
                    <div class="flex items-center justify-center text-xs font-semibold md:text-sm text-slate-600 ">
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

