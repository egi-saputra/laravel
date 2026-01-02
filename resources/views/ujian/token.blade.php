<x-app-layout>
    <div class="flex justify-center mt-10">
        <div class="w-full max-w-md p-6 bg-white shadow-md rounded-lg">

            {{-- Error Message --}}
            @if (session('error'))
                <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Form Token --}}
            <form method="POST" action="{{ route('ujian.validateToken') }}" id="tokenForm">
                @csrf

                <label class="block mb-2 font-semibold text-gray-700">Masukkan Token Ujian</label>

                <input type="text" name="token" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:border-blue-500">

                @error('token')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror

                <button type="submit"
                    class="w-full px-4 py-2 mt-4 font-bold text-white bg-blue-600 rounded hover:bg-blue-700">
                    Submit Token
                </button>

                {{-- Tombol Kembali ke Dashboard --}}
                <a href="{{ route('dashboard') }}"
                    class="w-full block text-center mt-3 px-4 py-2 font-semibold text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                    ← Kembali ke Dashboard
                </a>
            </form>

        </div>
    </div>

    {{-- Non-Turbo Script --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("tokenForm");

            form.addEventListener("submit", function() {
                // Optional: disable button setelah klik submit agar tidak double submit
                form.querySelector("button[type=submit]").disabled = true;
            });
        });
    </script>

</x-app-layout>
