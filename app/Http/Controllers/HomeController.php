<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilSekolah; // kalau ada tabel profil

class HomeController extends Controller
{
    public function home()
    {
        // Ambil data profil dari database
        // kalau belum ada database, bisa bikin dummy object
        $profil = ProfilSekolah::first() ?? (object)[
            'nama_sekolah' => 'SMK Nusantara Citayam'
        ];

        return view('home', compact('profil'));
    }

    public function about()
    {
        $profil = ProfilSekolah::first();
        return view('about', compact('profil'));
    }

    public function contact()
    {
        $profil = ProfilSekolah::first();
        return view('contact', compact('profil'));
    }
}
