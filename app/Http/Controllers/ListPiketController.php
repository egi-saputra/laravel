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

        // Urutan hari yang benar
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        // Sort jadwal piket berdasarkan urutan hari di atas
        $jadwalPiket = $jadwalPiket->sortBy(function ($item) use ($hariList) {
            return array_search($item->hari, $hariList);
        });

        return view('public.jadwal_piket', [
            'guru' => $guru,
            'jadwalPiket' => $jadwalPiket,
            'pageTitle' => 'Jadwal Piket',
            'hariList' => $hariList
        ]);
    }
}
