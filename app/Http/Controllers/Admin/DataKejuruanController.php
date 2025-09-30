<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataKejuruan;
use App\Models\DataGuru;

class DataKejuruanController extends Controller
{
    /**
     * Tampilkan daftar kejuruan
     */
    public function index()
    {
        // Ambil semua kejuruan + relasi kepalaProgram + hitung jumlah siswa
        $kejuruan = DataKejuruan::with('kepalaProgram')
                    ->withCount('siswa')
                    ->get();

        $guru = DataGuru::all(); // ambil semua guru untuk dropdown Kaprog
        $pageTitle = 'Data Kejuruan';

        return view('admin.kejuruan', compact('kejuruan', 'guru', 'pageTitle'));
    }

    /**
     * Simpan data kejuruan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode'          => 'required|string|max:10|unique:program_kejuruan,kode',
            'nama_kejuruan' => 'required|string|max:255',
            'kaprog_id'     => 'nullable|exists:data_guru,id',
        ]);

        DataKejuruan::create([
            'kode'          => $request->kode,
            'nama_kejuruan' => $request->nama_kejuruan,
            'kaprog_id'     => $request->kaprog_id,
        ]);

        return back()->with('alert', [
            'message' => 'Program Kejuruan berhasil ditambahkan!',
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }

    /**
     * Update data kejuruan
     */
    public function update(Request $request, $id)
    {
        $kejuruan = DataKejuruan::findOrFail($id);

        $request->validate([
            'kode'          => 'required|string|max:10|unique:program_kejuruan,kode,' . $kejuruan->id,
            'nama_kejuruan' => 'required|string|max:255',
            'kaprog_id'     => 'nullable|exists:data_guru,id',
        ]);

        $kejuruan->update([
            'kode'          => $request->kode,
            'nama_kejuruan' => $request->nama_kejuruan,
            'kaprog_id'     => $request->kaprog_id,
        ]);

        return back()->with('alert', [
            'message' => 'Data program kejuruan berhasil diperbarui!',
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }

    /**
     * Hapus data kejuruan
     */
    public function destroy($id)
    {
        $kejuruan = DataKejuruan::findOrFail($id);
        $kejuruan->delete();

        return back()->with('alert', [
            'message' => 'Data program kejuruan berhasil dihapus!',
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }
}
