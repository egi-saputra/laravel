<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\DataStruktural;
use App\Models\JadwalGuru;
use Intervention\Image\Facades\Image;
use App\Models\ProfilSekolah;
use App\Models\DataKejuruan;
use App\Models\DataEkskul;
use App\Models\DataKelas;
use App\Models\DataMapel;
use App\Models\DataGuru;
use App\Models\User;

class InformasiSekolahController extends Controller
{
    public function index()
    {
        // Profil sekolah
        $profil = ProfilSekolah::first();

        $struktural = DataStruktural::with('user')->get(); // ambil semua data struktural + relasi guru
        $gurus = DataGuru::with('user')->get(); // untuk dropdown pilih guru

        // Data ekskul + relasi pembina
        $ekskul = DataEkskul::with('userPembina')->get();
        $guruUsersEkskul = User::where('role', 'guru')->get();

        // Data guru
        $guru = User::with('guru')->where('role', 'guru')->get();

        // Data kejuruan + relasi kepala program + hitung siswa
        $kejuruan = DataKejuruan::with('kepalaProgram')->withCount('siswa')->get();
        $guruKejuruan = DataGuru::all();

        // Data kelas + relasi wali kelas
        $kelas = DataKelas::with('waliKelas.user')->withCount('siswa')->get();
        $guruKelas = DataGuru::with('user')->whereHas('user', function ($q) {
            $q->where('role', 'guru');
        })->get();

        // Data mapel + relasi guru
        $mapel = DataMapel::with('guru')->get();
        $jadwal = JadwalGuru::with(['guru.user','kelas','mapel'])
            ->get()
            ->unique(function($item) {
                return $item->hari
                    . $item->sesi
                    . $item->jam_mulai
                    . $item->jam_selesai
                    . $item->guru_id
                    . $item->kelas_id;
            });

            // Hitung jumlah jam per guru
            $guruJam = [];
            foreach ($guru as $g) {
                $jumlahJam = $jadwal->where('guru_id', $g->guru->id ?? 0)
                                    ->sum('jumlah_jam');
                $guruJam[$g->id] = $jumlahJam;
            }

        // Kirim semua data ke view
        return view('public.informasi_sekolah', [
            'profil' => $profil,
            'ekskul' => $ekskul,
            // 'guruUsersEkskul' => $guruUsersEkskul,
            'guru' => $guru,
            'gurus' => $gurus,
            'struktural' => $struktural,
            'kejuruan' => $kejuruan,
            'guruKejuruan' => $guruKejuruan,
            'kelas' => $kelas,
            'guruKelas' => $guruKelas,
            'mapel' => $mapel,
            'jadwal' => $jadwal,
            'guruJam' => $guruJam,
            'pageTitle' => 'Informasi Sekolah'
        ]);
    }
}
