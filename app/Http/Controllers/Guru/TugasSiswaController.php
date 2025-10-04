<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TugasSiswa;
use App\Models\DataKelas;
use App\Models\DataMapel;
use Illuminate\Support\Facades\Storage;

class TugasSiswaController extends Controller
{
    /**
     * Tampilkan daftar tugas siswa.
     */
    // public function index()
    // {
    //     $userId = auth()->id();

    //     // Ambil semua mapel yang diajar oleh guru login
    //     $mapel = DataMapel::whereHas('guru', function($q) use ($userId) {
    //         $q->where('user_id', $userId);
    //     })->get();

    //     // Ambil tugas siswa sesuai mapel milik guru login
    //     $tugas = TugasSiswa::with(['user', 'kelas', 'mapel'])
    //         ->whereIn('mapel_id', $mapel->pluck('id'))
    //         ->latest()
    //         ->paginate(10);

    //     $kelas = DataKelas::all();

    //     return view('guru.tugas_siswa', compact('tugas', 'kelas', 'mapel'));
    // }

    /**
     * Tampilkan daftar tugas siswa dengan paginate.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Ambil semua mapel yang diajar oleh guru login
        $mapel = DataMapel::whereHas('guru', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->get();

        // Ambil semua kelas
        $kelas = DataKelas::all();

        // Query dasar
        $query = TugasSiswa::with(['user', 'kelas', 'mapel'])
            ->whereIn('mapel_id', $mapel->pluck('id'))
            ->latest();

        // Filter Nama Siswa
        if ($request->filled('nama')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama . '%');
            });
        }

        // Filter Kelas
        if ($request->filled('kelas') && $request->kelas !== 'Semua') {
            $query->where('kelas_id', $request->kelas);
        }

        // Filter Mapel
        if ($request->filled('mapel') && $request->mapel !== 'Semua') {
            $query->where('mapel_id', $request->mapel);
        }

        // Ambil data dengan paginate
        $tugas = $query->paginate(20)->appends($request->query());

        return view('guru.tugas_siswa', compact('tugas', 'kelas', 'mapel'));
    }

    /**
     * Hapus tugas tertentu.
     */
    public function destroy($id)
    {
        $userId = auth()->id();

        // Pastikan hanya bisa hapus tugas dari mapel guru login
        $mapelIds = DataMapel::whereHas('guru', fn($q) => $q->where('user_id', $userId))->pluck('id');

        $tugas = TugasSiswa::whereIn('mapel_id', $mapelIds)->findOrFail($id);

        if ($tugas->file_path && Storage::disk('public')->exists(str_replace('storage/', '', $tugas->file_path))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $tugas->file_path));
        }

        $tugas->delete();

        return redirect()->route('guru.tugas_siswa.index')
            ->with('alert', [
                'message' => 'Tugas berhasil dihapus!',
                'type'    => 'success',
                'title'   => 'Berhasil',
            ]);
    }

    /**
     * Hapus semua tugas milik guru login.
     */
    public function destroyAll()
    {
        $userId = auth()->id();

        // Ambil semua mapel guru login
        $mapelIds = DataMapel::whereHas('guru', fn($q) => $q->where('user_id', $userId))->pluck('id');

        // Ambil tugas siswa sesuai mapel guru login
        $tugasList = TugasSiswa::whereIn('mapel_id', $mapelIds)->get();

        foreach ($tugasList as $tugas) {
            if ($tugas->file_path && Storage::disk('public')->exists(str_replace('storage/', '', $tugas->file_path))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $tugas->file_path));
            }
            $tugas->delete();
        }

        return back()->with('alert', [
            'message' => 'Semua tugas milik Anda berhasil dihapus!',
            'type'    => 'success',
            'title'   => 'Berhasil',
        ]);
    }

    /**
     * Lihat file tugas.
     */
    public function view_file_tugas($id)
    {
        $tugas = TugasSiswa::findOrFail($id);
        return view('guru.view_file_tugas', compact('tugas'));
    }

    public function download($id)
    {
        $tugas = TugasSiswa::findOrFail($id);

        if (!\Storage::disk('public')->exists($tugas->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return \Storage::disk('public')->download($tugas->file_path, $tugas->file_name);
    }

}
