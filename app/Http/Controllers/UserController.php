<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Dashboard siswa
    public function dashboard()
    {
        return view('user.dashboard');
    }

    // Kegiatan siswa
    public function activities()
    {
        return view('user.activities');
    }

    // Kegiatan siswa
    public function kegiatan()
    {
        return view('user.kegiatan');
    }
}
