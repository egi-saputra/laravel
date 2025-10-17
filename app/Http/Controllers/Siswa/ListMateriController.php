<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\DataKelas;
use App\Models\DataMapel;
use App\Models\DataSiswa;
use Illuminate\Support\Facades\Auth;

class ListMateriController extends Controller
{
    /**
     * Tampilkan daftar materi sesuai kelas siswa login
     */
    public function index()
    {
        // Ambil siswa yang login
        $siswa = DataSiswa::where('user_id', Auth::id())->first();

        // Jika tidak ditemukan data siswa
        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        // Ambil materi berdasarkan kelas siswa
        $materis = Materi::with(['user', 'kelas', 'mapel'])
            ->where('kelas_id', $siswa->kelas_id)
            ->latest()
            ->paginate(10);

        // Ambil data kelas & mapel untuk dropdown/filter
        $kelas  = DataKelas::all();
        $mapel  = DataMapel::all();

        // Profil siswa untuk footer
        $profil = Auth::user();

        return view('siswa.materi', compact('materis', 'kelas', 'mapel', 'profil'));
    }

    public function view_file_materi($id)
    {
        $materi = Materi::findOrFail($id);
        return view('siswa.view_file_materi', compact('materi'));
    }
}
