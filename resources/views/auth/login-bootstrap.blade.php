<x-guest-layout>
    <x-alert />
    <div class="d-flex flex-column flex-md-row min-vh-100">
        <!-- Left Panel -->
        <div class="text-center left-panel d-flex flex-column justify-content-center align-items-center w-100 w-md-50" style="background-color: #063970;">
            <img src="{{ Storage::url('logo_login/login.png') }}" alt="Logo" class="w-50">
            {{-- @if (file_exists(storage_path('app/public/logo/login_vid.mp4')))
                <video class="mb-4 rounded w-50" autoplay muted loop playsinline>
                    <source src="{{ Storage::url('logo/login_vid.mp4') }}" type="video/mp4">
                    Browser Anda tidak mendukung video tag.
                </video>
            @else
                <img src="{{ asset('images/bg_login.png') }}" alt="Logo" class="rounded w-50">
            @endif --}}

            <h3 class="mb-3">ARSIP DIGITAL | SMK NUSANTARA</h3>
            <p class="px-4">
                Portal arsip digital SMK NUSANTARA, Kelola progress menyimpan dan mengambil surat secara online melalui aplikasi arsip digital yang telah kami kembangkan.
            </p>
        </div>

        <!-- Right Panel -->
        <div class="p-4 bg-white d-flex justify-content-center align-items-center w-100 w-md-50 p-md-5">
            <div class="login-container">
                <h4 class=" fw-bold">Sign In</h4>
                <p class="mb-4">Nusantara Archive Directory</p>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-4 form-group position-relative">
                        <input type="email" class="form-control" id="email" name="email" placeholder=" " required>
                        <label for="email">Email</label>
                    </div>

                    <!-- Password -->
                    <div class="mb-3 position-relative form-group">
                        <div class="px-2 border rounded form-password-wrapper d-flex align-items-center position-relative">
                            <input id="password" type="password" name="password"
                                class="border-0 shadow-none form-control"
                                placeholder=" " required />
                            <label for="password" class="floating-label">Password</label>
                            <i class="bi bi-eye fs-6 me-1 text-secondary"
                            id="togglePasswordIcon"
                            onclick="togglePassword()"
                            style="cursor: pointer;"></i>
                        </div>
                    </div>
                    {{-- FILE CSS NYA di folder layouts/guest --}}

                    <div class="mb-4 d-flex justify-content-start align-items-center">
                        <small>
                            <i class="bi bi-lock me-1"></i> Forgot your password? <a href="{{ route('password.request') }}" class="text-blue text-decoration-none fs-sm">Click Here!
                            </a>
                        </small>
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-3 d-grid">
                        <button type="submit" class="text-white font-poppins btn btn-login btn-lg rounded-pill">
                            LOG IN
                        </button>
                    </div>

                    <!-- Links -->
                    <div class="mb-4 d-flex justify-content-center align-items-center">
                        <small>Don't have an account ? <a href="{{ route('register') }}" class="text-decoration-none"> Sign up</a></small>
                    </div>

                    <div class="my-4 d-flex align-items-center">
                        <hr class="flex-grow-1">
                        <span class="mx-3 text-black fw-semibold">OR</span>
                        <hr class="flex-grow-1">
                    </div>


                    <!-- Google -->
                    <div class="mt-3 text-center">
                        <a href="{{ route('google.login') }}"
                        class="gap-2 btn btn-outline-secondary d-flex align-items-center font-poppins justify-content-center rounded-pill fw-semibold">
                            <img src="https://img.icons8.com/color/20/000000/google-logo.png" alt="Google" />
                            Log in with Google
                        </a>
                    </div>

                </form>
                <script>
                    function togglePassword() {
                        const passwordInput = document.getElementById("password");
                        const icon = document.getElementById("togglePasswordIcon");

                        if (passwordInput.type === "password") {
                            passwordInput.type = "text";
                            icon.classList.remove("bi-eye");
                            icon.classList.add("bi-eye-slash");
                        } else {
                            passwordInput.type = "password";
                            icon.classList.remove("bi-eye-slash");
                            icon.classList.add("bi-eye");
                        }
                    }
                </script>

            </div>
        </div>
    </div>
</x-guest-layout>
