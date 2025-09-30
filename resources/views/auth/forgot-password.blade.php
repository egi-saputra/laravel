<x-reg-layout-nologo>
    <div class="mb-10 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="w-full max-w-md mx-auto">
        @csrf

        <!-- Email -->
        <div class="relative mb-2">
            <input id="email" type="email" name="email" placeholder=" " required
                   value="{{ old('email') }}"
                   class="peer w-full px-3 py-2 border border-gray-300 rounded
                          focus:outline-none focus:ring-1 focus:ring-[#063970] focus:border-[#063970]" />
            <label for="email"
                   class="absolute left-3 px-1 text-sm text-gray-500 bg-white transition-all
                          peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base
                          peer-focus:-top-3 peer-focus:text-[#063970] peer-focus:text-sm
                          peer-valid:-top-3 peer-valid:text-[#063970] peer-valid:text-sm">
                Email Address
            </label>
            @error('email')
                <small class="text-red-600">{{ $message }}</small>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end mb-3">
            {{-- <x-primary-button class="w-full py-2 font-semibold text-white rounded-full bg-[#063970] hover:bg-slate-900">
                {{ __('Email Password Reset Link') }}
            </x-primary-button> --}}

            <button type="submit"
                class="px-6 py-2 font-semibold text-white transition-colors rounded-md bg-slate-800 hover:bg-slate-700">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>

    <script>
        // Floating label JS
        document.querySelectorAll('input').forEach(input => {
            const label = input.nextElementSibling;

            if (input.value) {
                label.classList.add('-top-3','text-[#063970]','text-sm');
                label.classList.remove('top-2','text-gray-500','text-base');
            }

            input.addEventListener('input', () => {
                if(input.value) {
                    label.classList.add('-top-3','text-[#063970]','text-sm');
                    label.classList.remove('top-2','text-gray-500','text-base');
                } else if(!input.matches(':focus')) {
                    label.classList.remove('-top-3','text-[#063970]','text-sm');
                    label.classList.add('top-2','text-gray-500','text-base');
                }
            });

            input.addEventListener('focus', () => {
                label.classList.add('-top-3','text-[#063970]','text-sm');
                label.classList.remove('top-2','text-gray-500','text-base');
            });

            input.addEventListener('blur', () => {
                if(!input.value){
                    label.classList.remove('-top-3','text-[#063970]','text-sm');
                    label.classList.add('top-2','text-gray-500','text-base');
                }
            });
        });
    </script>
</x-reg-layout-nologo>
