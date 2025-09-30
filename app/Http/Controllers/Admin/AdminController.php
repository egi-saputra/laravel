<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    // Dashboard admin
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Profil Sekolah
    public function profil_sekolah()
    {
        return view('admin.profil_sekolah');
    }

    // Kelola Pengguna
    public function users()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Data Guru
    public function guru()
    {
        return view('admin.guru');
    }

    // Data Kelas
    public function kelas()
    {
        return view('admin.kelas');
    }

    // Data Siswa
    public function siswa()
    {
        return view('admin.siswa');
    }

    // Data Mapel
    public function mapel()
    {
        return view('admin.mapel');
    }

    // Laporan kegiatan
    public function reports()
    {
        return view('admin.reports');
    }
}
