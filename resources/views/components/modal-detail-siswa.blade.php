<!-- Modal Global -->
<div id="detailModal" class="fixed inset-0 z-50 items-center justify-center hidden p-4">
    <div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50"></div>

    <div class="relative w-full max-w-4xl p-6 bg-white rounded-lg shadow-lg overflow-auto max-h-[80vh]">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold"><i class="bi bi-person-lines-fill"></i> Detail Data Siswa</h3>
            <button id="closeModal" class="px-2 py-0 text-xl font-bold md:border md:rounded hover:text-slate-700 border-slate-400 text-slate-400 hover:border-slate-700">&times;</button>
        </div>

        <!-- Foto Profil -->
        <div class="flex justify-center mb-4">
            <img id="modalFoto" src="{{ asset('storage/default/avatar.jpeg') }}" class="object-cover border-4 border-white rounded-full shadow-md w-28 h-28">
        </div>

        <!-- Tabel Detail -->
        <table class="w-full text-sm border border-collapse border-gray-300">
            <tbody>
                <tr class="bg-gray-100"><th class="w-1/4 p-2 text-left border border-gray-300">Nama Lengkap</th><td id="modalNama" class="p-2 border border-gray-300"></td></tr>
                <tr><th class="p-2 text-left border border-gray-300">Asal Sekolah</th><td id="modalAsal" class="p-2 border border-gray-300"></td></tr>
                <tr class="bg-gray-100"><th class="p-2 text-left border border-gray-300">Tempat, Tanggal Lahir</th><td id="modalTtl" class="p-2 border border-gray-300"></td></tr>
                <tr><th class="p-2 text-left border border-gray-300">NIS</th><td id="modalNis" class="p-2 border border-gray-300"></td></tr>
                <tr class="bg-gray-100"><th class="p-2 text-left border border-gray-300">NISN</th><td id="modalNisn" class="p-2 border border-gray-300"></td></tr>
                <tr><th class="p-2 text-left border border-gray-300">Jenis Kelamin</th><td id="modalJk" class="p-2 border border-gray-300"></td></tr>
                <tr class="bg-gray-100"><th class="p-2 text-left border border-gray-300">Agama</th><td id="modalAgama" class="p-2 border border-gray-300"></td></tr>
                <tr><th class="p-2 text-left border border-gray-300">Alamat</th><td id="modalAlamat" class="p-2 border border-gray-300"></td></tr>
                <tr class="bg-gray-100"><th class="p-2 text-left border border-gray-300">RT/RW</th><td id="modalRtRw" class="p-2 border border-gray-300"></td></tr>
                <tr><th class="p-2 text-left border border-gray-300">Kecamatan</th><td id="modalKecamatan" class="p-2 border border-gray-300"></td></tr>
                <tr class="bg-gray-100"><th class="p-2 text-left border border-gray-300">Kota/Kabupaten</th><td id="modalKota" class="p-2 border border-gray-300"></td></tr>
                <tr><th class="p-2 text-left border border-gray-300">Kode Pos</th><td id="modalKodepos" class="p-2 border border-gray-300"></td></tr>
                <tr class="bg-gray-100"><th class="p-2 text-left border border-gray-300">No. HP</th><td id="modalTelepon" class="p-2 border border-gray-300"></td></tr>
                <tr><th class="p-2 text-left border border-gray-300">Kelas</th><td id="modalKelas" class="p-2 border border-gray-300"></td></tr>
                <tr class="bg-gray-100"><th class="p-2 text-left border border-gray-300">Kejuruan</th><td id="modalKejuruan" class="p-2 border border-gray-300"></td></tr>
            </tbody>
        </table>

        <div class="flex justify-end mt-4">
            <button id="closeModalFooter" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Tutup</button>
        </div>
    </div>
</div>

<script>
    const buttons = document.querySelectorAll('.lihat-detail-btn');
    const modal = document.getElementById('detailModal');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalFoto = document.getElementById('modalFoto');

    const modalNama = document.getElementById('modalNama');
    const modalAsal = document.getElementById('modalAsal');
    const modalTtl = document.getElementById('modalTtl');
    const modalNis = document.getElementById('modalNis');
    const modalNisn = document.getElementById('modalNisn');
    const modalJk = document.getElementById('modalJk');
    const modalAgama = document.getElementById('modalAgama');
    const modalAlamat = document.getElementById('modalAlamat');
    const modalRtRw = document.getElementById('modalRtRw');
    const modalKecamatan = document.getElementById('modalKecamatan');
    const modalKota = document.getElementById('modalKota');
    const modalKodepos = document.getElementById('modalKodepos');
    const modalTelepon = document.getElementById('modalTelepon');
    const modalKelas = document.getElementById('modalKelas');
    const modalKejuruan = document.getElementById('modalKejuruan');

    const closeModal = document.getElementById('closeModal');
    const closeModalFooter = document.getElementById('closeModalFooter');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            modalFoto.src = btn.dataset.foto;
            modalNama.textContent = btn.dataset.nama || '-';
            modalAsal.textContent = btn.dataset.asal || '-';
            modalTtl.textContent = btn.dataset.ttl || '-';
            modalNis.textContent = btn.dataset.nis || '-';
            modalNisn.textContent = btn.dataset.nisn || '-';
            modalJk.textContent = btn.dataset.jk || '-';
            modalAgama.textContent = btn.dataset.agama || '-';
            modalAlamat.textContent = btn.dataset.alamat || '-';
            modalRtRw.textContent = (btn.dataset.rt || '-') + ' / ' + (btn.dataset.rw || '-');
            modalKecamatan.textContent = btn.dataset.kecamatan || '-';
            modalKota.textContent = btn.dataset.kota || '-';
            modalKodepos.textContent = btn.dataset.kodepos || '-';
            modalTelepon.innerHTML = btn.dataset.telepon
                ? `<a href="https://wa.me/${btn.dataset.telepon.replace(/[^0-9]/g,'')}" target="_blank" class="text-blue-600 hover:underline">${btn.dataset.telepon}</a>`
                : '-';
            modalKelas.textContent = btn.dataset.kelas || '-';
            modalKejuruan.textContent = btn.dataset.kejuruan || '-';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    function closeModalFunc() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    closeModal.addEventListener('click', closeModalFunc);
    closeModalFooter.addEventListener('click', closeModalFunc);
    modalOverlay.addEventListener('click', closeModalFunc);
</script>
