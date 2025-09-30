<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataGuru;
use App\Models\JadwalPiket;

class ListPiketController extends Controller
{
    public function index()
    {
        $guru = DataGuru::with('user')->get();
        $jadwalPiket = JadwalPiket::with('user')->get();

        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

        return view('public.jadwal_piket', [
            'guru' => $guru,
            'jadwalPiket' => $jadwalPiket,
            'pageTitle' => 'Jadwal Piket',
            'hariList' => $hariList
        ]);
    }
}
