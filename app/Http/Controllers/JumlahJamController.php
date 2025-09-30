<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\DataStruktural;
use App\Models\JadwalGuru;
use App\Models\ProfilSekolah;
use App\Models\DataKejuruan;
use App\Models\DataEkskul;
use App\Models\DataKelas;
use App\Models\DataMapel;
use App\Models\DataGuru;
use App\Models\User;
use App\Models\JadwalPiket;

class JumlahJamController extends Controller
{
    public function index()
    {
        // Data guru
        $guru = User::with('guru')->where('role', 'guru')->get();
        $jadwalPiket = JadwalPiket::with('user')->get();
        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
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
        return view('public.jumlah_jam', [
            'guru' => $guru,
            'jadwal' => $jadwal,
            'jadwalPiket' => $jadwalPiket,
            'hariList' => $hariList,
            'guruJam' => $guruJam,
            'pageTitle' => ''
        ]);
    }
}
