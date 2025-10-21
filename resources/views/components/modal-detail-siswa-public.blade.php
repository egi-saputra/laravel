<!-- Modal -->
<div id="detailModal" class="fixed inset-0 z-50 items-center justify-center hidden p-4">
    <div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>

    <div class="relative w-full max-w-xl bg-white rounded-2xl shadow-2xl flex flex-col max-h-[80vh] overflow-auto">
        <div class="sticky top-0 z-10 flex items-center justify-between p-4 bg-white border-b">
            <h3 class="flex items-center gap-2 text-xl font-bold text-sky-700">
                <i class="bi bi-person-lines-fill"></i> Detail Siswa
            </h3>
            <button id="closeModal" class="text-xl font-bold text-gray-400 hover:text-gray-700">
                &times;
            </button>
        </div>

        <!-- Foto -->
        <div class="flex justify-center my-4">
            <img id="modalFoto" src="{{ asset('storage/default/avatar.jpeg') }}"
                 class="object-cover border-4 border-white rounded-full shadow-lg w-28 h-28" alt="Foto Siswa">
        </div>

        <!-- Body -->
        <div class="p-4 space-y-3 max-h-[60vh] overflow-x-auto md:overflow-x-visible overflow-y-auto">
            <div class="grid md:grid-cols-[130px_10px_1fr] grid-cols-[110px_10px_1fr] items-center p-2 bg-gray-50 rounded-lg border">
                <span class="font-semibold text-gray-600">Nama</span><span class="text-right">:</span>
                <span class="pl-2" id="modalName"></span>
            </div>
            <div class="grid md:grid-cols-[130px_10px_1fr] grid-cols-[110px_10px_1fr] items-center p-2 bg-gray-50 rounded-lg border">
                <span class="font-semibold text-gray-600">Tempat, Tanggal Lahir</span><span class="text-right">:</span>
                <span class="pl-2" id="modalTempatTanggalLahir"></span>
            </div>
            <div class="grid md:grid-cols-[130px_10px_1fr] grid-cols-[110px_10px_1fr] items-center p-2 bg-gray-50 rounded-lg border">
                <span class="font-semibold text-gray-600">Jenis Kelamin</span><span class="text-right">:</span>
                <span class="pl-2" id="modalJenisKelamin"></span>
            </div>
            <div class="grid md:grid-cols-[130px_10px_1fr] grid-cols-[110px_10px_1fr] items-center p-2 bg-gray-50 rounded-lg border">
                <span class="font-semibold text-gray-600">Agama</span><span class="text-right">:</span>
                <span class="pl-2" id="modalAgama"></span>
            </div>
            <div class="grid md:grid-cols-[130px_10px_1fr] grid-cols-[110px_10px_1fr] items-center p-2 bg-gray-50 rounded-lg border">
                <span class="font-semibold text-gray-600">Asal Sekolah</span><span class="text-right">:</span>
                <span class="pl-2" id="modalAsalSekolah"></span>
            </div>
            <div class="grid md:grid-cols-[130px_10px_1fr] grid-cols-[110px_10px_1fr] items-center p-2 bg-gray-50 rounded-lg border">
                <span class="font-semibold text-gray-600">Kelas</span><span class="text-right">:</span>
                <span class="pl-2" id="modalKelas"></span>
            </div>
            <div class="grid md:grid-cols-[130px_10px_1fr] grid-cols-[110px_10px_1fr] items-center p-2 bg-gray-50 rounded-lg border">
                <span class="font-semibold text-gray-600">Kejuruan</span><span class="text-right">:</span>
                <span class="pl-2" id="modalKejuruan"></span>
            </div>
            <div class="grid md:grid-cols-[130px_10px_1fr] grid-cols-[110px_10px_1fr] items-center p-2 bg-gray-50 rounded-lg border">
                <span class="font-semibold text-gray-600">NIS</span><span class="text-right">:</span>
                <span class="pl-2" id="modalNIS"></span>
            </div>
            <div class="grid md:grid-cols-[130px_10px_1fr] grid-cols-[110px_10px_1fr] items-center p-2 bg-gray-50 rounded-lg border">
                <span class="font-semibold text-gray-600">NISN</span><span class="text-right">:</span>
                <span class="pl-2" id="modalNISN"></span>
            </div>
            <div class="grid md:grid-cols-[130px_10px_1fr] grid-cols-[110px_10px_1fr] items-center p-2 bg-gray-50 rounded-lg border">
                <span class="font-semibold text-gray-600">Email</span><span class="text-right">:</span>
                <span class="pl-2" id="modalEmail"></span>
            </div>
        </div>

        <div class="flex justify-end p-4 bg-white border-t">
            <button id="closeModalFooter" class="px-5 py-2 text-white rounded bg-sky-600 hover:bg-sky-700">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- <script>
    const buttons = document.querySelectorAll('.lihat-detail-btn');
    const modal = document.getElementById('detailModal');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalFoto = document.getElementById('modalFoto');

    const modalName = document.getElementById('modalName');
    const modalTempatTanggalLahir = document.getElementById('modalTempatTanggalLahir');
    const modalJenisKelamin = document.getElementById('modalJenisKelamin');
    const modalAgama = document.getElementById('modalAgama');
    const modalKelas = document.getElementById('modalKelas');
    const modalKejuruan = document.getElementById('modalKejuruan');
    const modalNIS = document.getElementById('modalNIS');
    const modalNISN = document.getElementById('modalNISN');
    const modalAsalSekolah = document.getElementById('modalAsalSekolah');
    const modalEmail = document.getElementById('modalEmail');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            modalFoto.src = btn.dataset.foto;
            modalName.textContent = btn.dataset.name;
            modalTempatTanggalLahir.textContent = btn.dataset.tempatTanggalLahir;
            modalJenisKelamin.textContent = btn.dataset.jenisKelamin;
            modalAgama.textContent = btn.dataset.agama;
            modalKelas.textContent = btn.dataset.kelas;
            modalKejuruan.textContent = btn.dataset.kejuruan;
            modalNIS.textContent = btn.dataset.nis;
            modalNISN.textContent = btn.dataset.nisn;
            modalAsalSekolah.textContent = btn.dataset.asalSekolah;
            modalEmail.textContent = btn.dataset.email;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    function closeModalFunc() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('closeModal').addEventListener('click', closeModalFunc);
    document.getElementById('closeModalFooter').addEventListener('click', closeModalFunc);
    modalOverlay.addEventListener('click', closeModalFunc);
</script> --}}

<script>
    document.addEventListener("turbo:load", initDetailModal);
    document.addEventListener("DOMContentLoaded", initDetailModal);

    function initDetailModal() {
        // Gunakan flag khusus per halaman agar bisa re-initialize setelah navigasi
        const pageId = document.body.dataset.pageId || window.location.pathname;
        window._initializedPages = window._initializedPages || {};
        if (window._initializedPages[pageId]) return;
        window._initializedPages[pageId] = true;

        const buttons = document.querySelectorAll('.lihat-detail-btn');
        const modal = document.getElementById('detailModal');
        const modalOverlay = document.getElementById('modalOverlay');
        const modalFoto = document.getElementById('modalFoto');

        const modalName = document.getElementById('modalName');
        const modalTempatTanggalLahir = document.getElementById('modalTempatTanggalLahir');
        const modalJenisKelamin = document.getElementById('modalJenisKelamin');
        const modalAgama = document.getElementById('modalAgama');
        const modalKelas = document.getElementById('modalKelas');
        const modalKejuruan = document.getElementById('modalKejuruan');
        const modalNIS = document.getElementById('modalNIS');
        const modalNISN = document.getElementById('modalNISN');
        const modalAsalSekolah = document.getElementById('modalAsalSekolah');
        const modalEmail = document.getElementById('modalEmail');

        if (!buttons.length || !modal) return; // keamanan tambahan

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                modalFoto.src = btn.dataset.foto || '{{ asset("storage/default/avatar.jpeg") }}';
                modalName.textContent = btn.dataset.name || '-';
                modalTempatTanggalLahir.textContent = btn.dataset.tempatTanggalLahir || '-';
                modalJenisKelamin.textContent = btn.dataset.jenisKelamin || '-';
                modalAgama.textContent = btn.dataset.agama || '-';
                modalKelas.textContent = btn.dataset.kelas || '-';
                modalKejuruan.textContent = btn.dataset.kejuruan || '-';
                modalNIS.textContent = btn.dataset.nis || '-';
                modalNISN.textContent = btn.dataset.nisn || '-';
                modalAsalSekolah.textContent = btn.dataset.asalSekolah || '-';
                modalEmail.textContent = btn.dataset.email || '-';

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });

        function closeModalFunc() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('closeModal')?.addEventListener('click', closeModalFunc);
        document.getElementById('closeModalFooter')?.addEventListener('click', closeModalFunc);
        modalOverlay?.addEventListener('click', closeModalFunc);
    }
</script>

