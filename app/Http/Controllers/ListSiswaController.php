<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ListSiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['dataSiswa.kelas', 'dataSiswa.kejuruan', 'foto_profil'])
                     ->where('role', 'siswa');

        // Filter kelas
        if ($request->filled('kelas') && $request->kelas !== 'Semua') {
            $query->whereHas('dataSiswa.kelas', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        // Filter kejuruan
        if ($request->filled('kejuruan') && $request->kejuruan !== 'Semua') {
            $query->whereHas('dataSiswa.kejuruan', function ($q) use ($request) {
                $q->where('nama_kejuruan', $request->kejuruan);
            });
        }

        // Filter nama
        if ($request->filled('nama')) {
            $query->where('name', 'like', '%' . $request->nama . '%');
        }

        // Pagination 20 per halaman, dengan query string agar filter/search tetap dipertahankan
        $perPage = 20;
        $siswa = $query->paginate($perPage)->withQueryString();

        $kelasList = \App\Models\DataKelas::pluck('kelas');
        $kejuruanList = \App\Models\DataKejuruan::pluck('nama_kejuruan');

        return view('public.daftar_siswa', compact('siswa', 'kelasList', 'kejuruanList'));
    }
}
