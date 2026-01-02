<x-app-layout>

<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Ujian: {{ $soal->nama_ujian }}
    </h2>
</x-slot>

<div class="px-4 py-6">

    <div class="flex md:flex-row flex-col max-w-6xl mx-auto gap-4">

        {{-- PANEL DAFTAR NOMOR SOAL --}}
        <div class="w-1/4 bg-white shadow-md rounded-lg p-4 h-fit sticky top-4">
            <h3 class="font-semibold mb-3">Daftar Soal</h3>
            <div class="grid md:grid-cols-5 gap-2">
                @foreach ($nomorList as $i => $questId)
                    @php
                        $riwayatItem = \App\Models\RiwayatUjian::where('user_id', auth()->id())
                            ->where('soal_id', $soal->id)
                            ->where('quest_id', $questId)
                            ->first();
                        if ($i+1 == $no) {
                            $warna = 'bg-green-500 text-white';
                        } elseif ($riwayatItem && $riwayatItem->jawaban) {
                            $warna = 'bg-blue-600 text-white';
                        } else {
                            $warna = 'bg-white text-gray-500';
                        }
                    @endphp
                    <a href="{{ route('ujian.kerjakan', ['soal' => $soal->id, 'no' => $i+1]) }}"
                       class="w-10 h-10 border border-gray-700 flex items-center justify-center rounded font-bold {{ $warna }}">
                        {{ $i+1 }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- PANEL UTAMA SOAL --}}
        <div class="w-3/4 p-6 bg-white shadow-md rounded-lg">

            <div id="timer" class="text-xl font-bold text-red-600 mb-4">-</div>

            <h3 class="mb-4 text-lg font-semibold">
                Soal {{ $no }} dari {{ $totalSoal }}
            </h3>

            {{-- Form autosave --}}
            <form id="formUjian">
                @csrf
                <input type="hidden" id="soal_id" value="{{ $soal->id }}">
                <input type="hidden" id="quest_id" value="{{ $quest->id }}">
                <input type="hidden" id="ujian_token" value="{{ $ujianSiswa->token }}">
                <div class="mb-4">{!! $quest->soal !!}</div>

                <div class="space-y-2">
                    @php $jawabanSebelumnya = $riwayat->jawaban; @endphp
                    @foreach (['A','B','C','D','E'] as $opsi)
                        @php $field = "opsi_" . strtolower($opsi); @endphp
                        @if ($quest->$field)
                            <label class="flex items-center gap-2">
                                <input type="radio"
                                       name="jawaban"
                                       value="{{ $opsi }}"
                                       @checked($jawabanSebelumnya == $opsi)>
                                <span>{{ $opsi }}. {!! $quest->$field !!}</span>
                            </label>
                        @endif
                    @endforeach
                </div>
            </form>

            {{-- Form submit otomatis --}}
            <form id="autoSubmitForm" action="{{ route('ujian.submit', $soal->id) }}" method="POST" style="display:none;">
                @csrf
                <input type="hidden" name="token" value="{{ $ujianSiswa->token }}">
            </form>

            <div class="flex justify-between mt-6">
                @if ($no > 1)
                    <a href="{{ route('ujian.kerjakan', ['soal' => $soal->id, 'no' => $no - 1]) }}"
                       class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                        ← Sebelumnya
                    </a>
                @endif

                @if ($no < $totalSoal)
                    <a href="{{ route('ujian.kerjakan', ['soal' => $soal->id, 'no' => $no + 1]) }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Selanjutnya →
                    </a>
                @endif

                @if ($no == $totalSoal)
                    <form action="{{ route('ujian.submit', $soal->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $ujianSiswa->token }}">
                        <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Submit Ujian
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- SCRIPT UTAMA --}}
<script>
document.addEventListener("turbo:load", function() {

    const timerDiv = document.getElementById("timer");
    let sisa = {{ $sisaDetik ?? 0 }};
    let interval;

    // ================= Timer =================
    function updateTimer() {
        const menit = Math.floor(sisa / 60);
        const detik = sisa % 60;
        timerDiv.textContent = `${menit}:${detik < 10 ? '0' : ''}${detik}`;

        if (sisa <= 0) {
            clearInterval(interval);
            timerDiv.textContent = "Waktu Habis!";
            fetch("{{ route('ujian.submit', $soal->id) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ token: document.getElementById("ujian_token").value })
            }).then(() => {
                window.location.href = "{{ route('ujian.token', $soal->id) }}";
            });
            return;
        }
        sisa--;
    }

    clearInterval(interval);
    updateTimer();
    interval = setInterval(updateTimer, 1000);

    // ================= Autosave =================
    document.querySelectorAll('input[name=jawaban]').forEach(radio => {
        radio.addEventListener('change', function () {
            fetch("{{ route('ujian.autosave') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    soal_id: document.getElementById("soal_id").value,
                    quest_id: document.getElementById("quest_id").value,
                    jawaban: this.value,
                    token: document.getElementById("ujian_token").value
                })
            }).catch(err => console.error("Autosave gagal:", err));
        });
    });

    // ================= Anti keluar tab + refresh token =================
    function refreshTokenBeforeExit() {
        const url = "{{ route('ujian.refreshToken', $soal->id) }}";
        const data = new URLSearchParams();
        data.append("_token", "{{ csrf_token() }}");
        navigator.sendBeacon(url, data);
    }

    function blockExit() {
        // Hentikan timer dan autosave
        clearInterval(interval);

        // Reset form (opsional)
        document.getElementById('formUjian').reset();

        // Refresh token
        refreshTokenBeforeExit();

        // Redirect ke halaman input token
        window.location.href = "{{ route('ujian.token') }}";
    }

    document.addEventListener('visibilitychange', function() {
        if (document.hidden) blockExit();
    });

    window.addEventListener('beforeunload', function() {
        refreshTokenBeforeExit(); // biar aman kalau tab ditutup
    });

    // ================= Blokir back browser =================
    history.pushState(null, "", location.href);
    window.onpopstate = function () {
        history.pushState(null, "", location.href);
        alert("Tidak dapat kembali! Ujian sedang berlangsung.");
    };

    // ================= Nonaktifkan copy-paste =================
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.addEventListener('cut', e => e.preventDefault());
    document.addEventListener('copy', e => e.preventDefault());
    document.addEventListener('paste', e => e.preventDefault());

    // ================= Nonaktifkan shortcut =================
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) &&
            ['c','v','x','r','t','n','w'].includes(e.key.toLowerCase())) {
            e.preventDefault();
            alert("Shortcut dinonaktifkan selama ujian!");
        }
        if (e.key === 'F5') { e.preventDefault(); alert("Reload diblokir!"); }
        if (e.key === 'Escape') { e.preventDefault(); }
    });

});
</script>

</x-app-layout>
