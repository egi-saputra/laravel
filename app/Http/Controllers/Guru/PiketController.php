<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\DataGuru;
use App\Models\JadwalPiket;
use Illuminate\Http\Request;

class PiketController extends Controller
{
    public function index()
    {
        $guru = DataGuru::with('user')->get();
        $jadwalPiket = JadwalPiket::with('user')->get();

        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

        return view('guru.jadwal_piket', [
            'guru' => $guru,
            'jadwalPiket' => $jadwalPiket,
            'pageTitle' => 'Jadwal Piket',
            'hariList' => $hariList
        ]);
    }
}
