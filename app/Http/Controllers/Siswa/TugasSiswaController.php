<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TugasSiswa;
use App\Models\DataKelas;
use App\Models\DataMapel;

class TugasSiswaController extends Controller
{
    /**
     * Tampilkan form input tugas + daftar tugas siswa
     */
    public function index()
    {
        $mapel = DataMapel::all();
        $user = Auth::user();
        $kelasUser = $user->dataSiswa ? $user->dataSiswa->kelas : null;

        // Ambil daftar tugas milik user login
        $tugas = TugasSiswa::where('user_id', Auth::id())->latest()->get();

        return view('siswa.tugas', compact('kelasUser', 'mapel', 'tugas'))
            ->with('pageTitle', 'Input Tugas');
    }

    /**
     * Simpan tugas baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'judul'     => 'required|string|max:255',
            'kelas_id'  => 'required|exists:data_kelas,id',
            'mapel_id'  => 'required|exists:data_mapel,id',
            'file_tugas' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar,jpg,jpeg,png,gif,svg,webp,mp4,mkv,avi,mov,wmv,flv|max:51200', // 50 MB
            // Ukuran untuk validasi sesuai keperluan = (50 MB = 51200, 100 MB = 102400, 10 MB = 10240, 15 MB = 15360, 20 MB = 20480)
        ]);

        // Upload file
        $file = $request->file('file_tugas');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads/tugas', $fileName, 'public');

        // Simpan ke database
        TugasSiswa::create([
            'user_id'   => Auth::id(),
            'nama'      => Auth::user()->name, // ambil otomatis (Input Hidden)
            // 'nama'      => $request->nama,  // ambil otomatis (Read Only, Aktifkan form input namanya)
            'judul'     => $request->judul,
            'kelompok'  => $request->kelompok,
            'kelas_id'  => $request->kelas_id,
            'mapel_id'  => $request->mapel_id,
            'file_name' => $fileName,
            'file_path' => $filePath,
        ]);

        return redirect()->route('siswa.tugas.index')->with('success', 'Tugas berhasil dikumpulkan!');
    }

    /**
     * Hapus tugas
     */
    public function destroy($id)
    {
        $tugas = TugasSiswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Hapus file jika ada
        if ($tugas->file_path && \Storage::disk('public')->exists($tugas->file_path)) {
            \Storage::disk('public')->delete($tugas->file_path);
        }

        $tugas->delete();

        return redirect()->route('siswa.tugas.index')->with('success', 'Tugas berhasil dihapus!');
    }
}
